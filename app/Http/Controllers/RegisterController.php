<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Commands\LoginUserCommand;
use App\Http\Controllers\Controller;
use App\Commands\RegisterUserCommand;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        
        $command = new RegisterUserCommand(
            $request->name,
            $request->email,
            $request->password,
            $request->role
        );

        return app(Pipeline::class)
            ->send($command)
            ->through([
                \App\Pipelines\User\ValidateRegisterUser::class,
                \App\Pipelines\Registration\CreateUserAccount::class,
                \App\Pipelines\Registration\AssignUserRole::class,
                
            ])
            ->then(fn($user) => response()->json([
                'message' => 'User created successfully',
                'token' => $user->createToken('auth_token')->plainTextToken,
                'role' => $user->role,
                "success" => 200
            ], 201));
    }

    /*********************************************************************** */

    public function login(Request $request)
    {
        $command = new LoginUserCommand( 
            $request->email,
            $request->password,
        );

        return app(Pipeline::class)
            ->send($command)
            ->through([
                \App\Pipelines\User\ValidateLoginUser::class,
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

    /*********************************************************************** */

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

    /*********************************************************************** */
}
