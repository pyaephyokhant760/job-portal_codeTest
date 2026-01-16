<?php

// app/Pipelines/Registration/CreateUserAccount.php
namespace App\Pipelines\Registration;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAccount {
    public function handle($command, $next) {
        $user = User::create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => Hash::make($command->password),
        ]);
        return $next((object) ['user' => $user, 'role' => $command->role]);
    }
}



?>