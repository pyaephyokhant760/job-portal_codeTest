<?php
namespace App\Pipelines\Registration; 



class AssignUserRole {
    public function handle($data, $next) {
        $data->user->assignRole($data->role);
        return $next($data->user);
    }
}


?>