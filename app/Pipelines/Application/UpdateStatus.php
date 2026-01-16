<?php

namespace App\Pipelines\Application;

use Closure;
class UpdateStatus {
    public function handle($data, Closure $next) 
    {
        if (isset($data['status'])) {
            // update method ထဲမှာ တိုက်ရိုက် String အဖြစ် Cast လုပ်ပါ
            $data['application']->update([
                'status' => (string) $data['status']
            ]);
        }

        return $next($data);
    }
}