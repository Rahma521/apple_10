<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\Auth\UserResetPasswordRequest;
use App\Http\Requests\Auth\UserVerifyRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Mail\VerificationCodeMail;
use App\Models\Cart;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mail;

class AuthController extends Controller
{
    use ResponseTrait;

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(UserRegisterRequest $request)
    {
        $userData = $request->safe()->except('password');
        $userData['password'] = bcrypt($request->password);
        $userData['code'] = generate_verification_code();
        $user = User::create($userData);
        Cart::create(['user_id' => $user->id]);

       // $user->access_token = $user->createToken('PersonalAccessToken')->plainTextToken;

       // Mail::to($user->email)->send(new VerificationCodeMail( $userData['code'] ));
        return self::successResponse(__('application.must_verify'), UserResource::make($user));
    }

    public function verify(UserVerifyRequest $request): JsonResponse
    {
        $user = User::whereCode($request->code)->first();

        if ($user->code != $request->code) {
            return self::failResponse(422, __('application.wrong_code'));
        }
        $user->update(['email_verified_at' => now()]);
       // $user->access_token = $user->createToken('PersonalAccessToken')->plainTextToken;
        return self::successResponse(__('application.verified'), UserResource::make($user));

    }

    public function resendCode(EmailRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->email)->first();
        $user->update(['code' => generate_verification_code()]);

       // Mail::to($user->email)->send(new VerificationCodeMail($user->code));
        return self::successResponse(__('application.resend_code'), UserResource::make($user));
    }

    public function forgotPassword(EmailRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->email)->first();
        $user->update(['code' => generate_verification_code()]);
       // Mail::to($user->email)->send(new VerificationCodeMail($user->code));
        return self::successResponse(__('application.resend_code'), UserResource::make($user));
    }

    public function resetPassword(UserResetPasswordRequest $request): JsonResponse
    {
        $user = User::whereCode($request->code)->first();
        $user->update(['password' => bcrypt($request->password)]);

        return self::successResponse(__('application.password_updated'), UserResource::make($user));
    }


    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::whereEmail($request->email)->first();
        $validationResult = $this->authService->validateUser($credentials, $user);

        if ($validationResult instanceof JsonResponse) {
            return $validationResult;
        }

        $user->access_token = $user->createToken('PersonalAccessToken')->plainTextToken;

        return self::successResponse(__('application.login_successfully'), UserResource::make($user));
    }

    public function logout(Request $request)
    {
        auth('sanctum')->user()->currentAccessToken()->delete();

        return self::successResponse(__('application.log_out'));
    }


//    public function logout(Request $request)
//    {
//        $user = auth('sanctum')->user();
//        if ($user instanceof User) {
//            $user->currentAccessToken()->delete();
//            return self::successResponse(__('application.log_out'));
//        }
//
//        return self::errorResponse(__('application.unauthorized'), 403);
//    }

}
