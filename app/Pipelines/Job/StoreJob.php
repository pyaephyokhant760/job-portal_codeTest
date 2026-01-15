<?php

// app/Pipelines/Jobs/StoreJob.php
namespace App\Pipelines\Job;

use App\Models\Work;
use Illuminate\Support\Facades\Auth;

class StoreJob {
    public function handle($request, $next) {
        $job = Work::create([
            'employer_id' => Auth::id(), 
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location'    => $request->location,
            'status'      => 'active', 
            'expiry_date' => $request->expiry_date,
        ]);

        return $next($job);
    }
}

?>