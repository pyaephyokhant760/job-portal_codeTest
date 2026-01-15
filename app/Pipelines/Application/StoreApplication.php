<?php
namespace App\Pipelines\Application;

use App\Models\Application;
use Closure;

class StoreApplication
{
    public function handle($request, Closure $next)
    {
        $application = Application::create([
            'job_id'  => $request->job_id,
            'user_id' => $request->user()->id,
            'cv_path' => $request->cv_path,
            'status'  => 'pending', // Default
            'score'   => 0,         // Default သို့မဟုတ် AI/Algorithm ဖြင့် တွက်ချက်ထားသည့် score
        ]);

        return $next($application);
    }
}