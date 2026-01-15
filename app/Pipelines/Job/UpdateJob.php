<?php

namespace App\Pipelines\Job;

class UpdateJob
{
    public function handle($data, $next)
    {
        $work = $data['work'];
        $request = $data['request'];

        $work->update([
            'title'       => $request->title ?? $work->title,
            'description' => $request->description ?? $work->description,
            'category_id' => $request->category_id ?? $work->category_id,
            'location'    => $request->location ?? $work->location,
            'status'      => $request->status ?? $work->status,
            'expiry_date' => $request->expiry_date ?? $work->expiry_date,
        ]);

        return $next($work);
    }
}