<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Http\Resources\AdminResource;
use App\Http\Services\AuthService;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ResponseTrait;
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AdminLoginRequest $request)
    {
        $admin = Admin::whereEmail($request->email)->first();
        $validationResult = $this->authService->validateAdmin($admin, $request->password);

        if ($validationResult instanceof JsonResponse) {
            return $validationResult;
        }

        $admin->access_token = $admin->createToken('PersonalAccessToken')->plainTextToken;

        return self::successResponse(__('application.login_successfully'), AdminResource::make($admin));
    }
}
