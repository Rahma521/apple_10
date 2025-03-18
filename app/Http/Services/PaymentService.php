<?php

namespace App\Http\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected $baseUrl;
    protected $entityId;
    protected $authToken;
    protected $currency;

    public function __construct()
    {
        $this->baseUrl = env('HYPERPAY_BASE_URL', 'https://eu-test.oppwa.com/v1');
        $this->entityId = env('HYPERPAY_ENTITY_ID', '8ac7a4c79394bdc801939736f17e063d');
        $this->authToken = env('HYPERPAY_AUTH_TOKEN', 'OGFjN2E0Yzc5Mzk0YmRjODAxOTM5NzM2ZjFhNzA2NDF8enlac1lYckc4QXk6bjYzI1NHNng=');
        $this->currency = env('HYPERPAY_CURRENCY', 'SAR');
    }

    public function processPayment(Order $order, array $cardData)
    {
        // Check if order is already paid
        if ($this->isOrderPaid($order)) {
            throw new Exception('This order has already been paid for.');
        }

        try {
            DB::beginTransaction();

            $response = $this->createPaymentRequest($order, $cardData);

            // Create payment transaction record
            $this->createPaymentTransaction(
                order: $order,
                response: $response,
                cardNumber: $cardData['number']
            );

            // Update order payment status
            $this->updateOrderPaymentStatus($order, $response);

            DB::commit();

            return [
                'success' => $response['result']['code'] === '000.100.110',
                'data' => $response,
                'message' => $response['result']['description']
            ];
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Payment processing failed: ' . $e->getMessage());
        }
    }

    private function createPaymentTransaction(Order $order, array $response, string $cardNumber)
    {
        $lastFourDigits = substr($cardNumber, -4);

        return PaymentTransaction::create([
            'amount' => $order->total,
            'transaction_id' => $response['id'] ?? null,
            'order_id' => $order->id,
            'last_four_digits' => $lastFourDigits,
            'user_id' => $order->user_id,
            'status' => $response['result']['code'] === '000.100.110',
        ]);
    }

    private function createPaymentRequest(Order $order, array $cardData)
    {

        $data = http_build_query([
            'entityId' => $this->entityId,
            'amount' => number_format($order->total, 2, '.', ''),
            //  'currency' => $this->currency,
            'currency' => 'EUR',
            'paymentBrand' => $cardData['brand']??'VISA',
            'paymentType' => 'PA',
            'card.number' => $cardData['number'],
            'card.holder' => $cardData['holder'],
            'card.expiryMonth' => $cardData['expiry_month'],
            'card.expiryYear' => $cardData['expiry_year'],
            'card.cvv' => $cardData['cvv']
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->withBody($data, 'application/x-www-form-urlencoded')
            ->post($this->baseUrl . '/payments');

        if (!$response->successful()) {
            \Log::error('Payment Request Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'request_data' => $data
            ]);
            throw new Exception('Payment request failed: ' . $response->body());
        }

        return $response->json();
    }

    private function isOrderPaid(Order $order): bool
    {
        if ($order->payment_status === 1) {
            return true;
        }

        $successfulTransaction = PaymentTransaction::where('order_id', $order->id)
            ->where('status', true)
            ->exists();

        return $successfulTransaction;
    }

    private function updateOrderPaymentStatus(Order $order, array $paymentResponse)
    {
        $isSuccessful = $paymentResponse['result']['code'] === '000.100.110';

        $order->update([
            'payment_status' => $isSuccessful ? 1 : 0,
            'payment_id' => $paymentResponse['id'] ?? null,
            'payment_response' => json_encode($paymentResponse)
        ]);
    }

    public function checkPaymentStatus($paymentId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->authToken
        ])->get($this->baseUrl . '/payments/' . $paymentId);

        if (!$response->successful()) {
            throw new Exception('Payment status check failed: ' . $response->body());
        }

        return $response->json();
    }
}
