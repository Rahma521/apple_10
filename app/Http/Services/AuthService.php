<?php

namespace App\Http\Services;

use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    use ResponseTrait;

    public function validateAdmin($admin, $password): ?JsonResponse
    {
        $password = ! Hash::check($password, $admin->password);

        return match (true) {
            ! $admin, $password => self::failResponse(422, __('application.unauthorized')),
            // ! $admin->is_verified => self::failResponse(420, __('application.not_verified')),
            //!$admin->is_active => self::failResponse(422, __('application.not_active')),

            default => null
        };
    }

    public function validateUser(array $credentials, $user): ?JsonResponse
    {
        $user = User::whereEmail($credentials['email'])->first();
        $password = ! Hash::check($credentials['password'], $user->password);

        return match (true) {
            ! $user, $password, $user->deleted_at != null  => self::failResponse(422, __('application.unauthorized')),
            ! $user->email_verified_at != null => self::failResponse(420, __('application.not_verified')),
          //  ! $user->is_active => self::failResponse(422, __('application.not_active')),
            default => null
        };
    }

}
