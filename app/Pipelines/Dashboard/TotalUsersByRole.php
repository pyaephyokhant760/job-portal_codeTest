<?php


namespace App\Pipelines\Dashboard;

use Closure;
use App\DTOs\DashboardData; 
use Illuminate\Support\Facades\DB;

class TotalUsersByRole {
    public function handle(DashboardData $content, Closure $next) {
        
        $content->usersByRole = DB::table('roles')
            ->leftJoin('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('roles.name', DB::raw('count(model_has_roles.model_id) as total'))
            ->groupBy('roles.id', 'roles.name') 
            ->get()
            ->toArray();

        return $next($content);
    }
}