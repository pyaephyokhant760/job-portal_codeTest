<?php

namespace App\Pipelines\Application;


class UpdateStatus {
    public function handle($data, $next) {
        $data['application']->update(['status' => $data['status']]);
        return $next($data);
    }
}