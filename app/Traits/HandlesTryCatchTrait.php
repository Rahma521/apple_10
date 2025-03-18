<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

trait HandlesTryCatchTrait
{
    /**
     * Execute logic with try-catch and return a JSON response.
     *
     * @param callable $logic The logic to execute.
     * @param string|null $successMessage The success message to return.
     * @param string|null $errorMessage The error message to return.
     * @return JsonResponse
     */
    protected function execute(callable $logic, $successMessage = null, $errorMessage = null): JsonResponse
    {
        try {
            // Execute the logic
            call_user_func($logic);

            // Return a success JSON response
            return response()->json([
                'status' => 'success',
                'message' => $successMessage,
            ]);
        } catch (Exception $e) {

            Log::error('Failed to execute logic: ' . $e->getMessage());
            // Return an error JSON response
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage ?? 'An error occurred while processing your request.',
//                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
