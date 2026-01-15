<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Commands\RegisterUserCommand;
use Illuminate\Pipeline\Pipeline;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // ၁။ Request မှ Command (DTO) ပြောင်းခြင်း
        $command = new RegisterUserCommand(
            $request->name,
            $request->email,
            $request->password,
            $request->role
        );

        // ၂။ Pipeline ထဲသို့ ပို့လွှတ်ခြင်း
        return app(Pipeline::class)
            ->send($command)
            ->through([
                \App\Pipelines\Registration\CreateUserAccount::class,
                \App\Pipelines\Registration\AssignUserRole::class,
                // \App\Pipelines\Registration\SendWelcomeEmail::class,
            ])
            ->then(fn($user) => response()->json([
                'message' => 'User created successfully',
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 201));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        return app(Pipeline::class)
            ->send($request)
            ->through([
                \App\Pipelines\Registration\VerifyTwoFactor::class,
                \App\Pipelines\Registration\LoginUser::class,
            ])
            ->then(function ($user) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successful',
                    'token'   => $token,
                    'success' => 200
                ]);
            });
    }

    public function enable2fa(Request $request)
    {
        return app(\Illuminate\Pipeline\Pipeline::class)
            ->send($request->user())
            ->through([
                \App\Pipelines\TwoFactor\EnableTwoFactor::class,
            ])
            ->then(function ($user) {
                return response()->json([
                    'message' => '2FA generated successfully',
                    'secret'  => $user->two_factor_secret,
                    'qr_url'  => $user->qr_code_url, // Pipeline ထဲမှာ ထည့်ပေးလိုက်တဲ့ url
                ]);
            });
    }
}
