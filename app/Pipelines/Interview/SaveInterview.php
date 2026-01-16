<?php

namespace App\Pipelines\Interview;
use App\Models\Interview;
use Closure;

class SaveInterview
{
    public function handle($request, Closure $next)
    {
        $interview = Interview::create([
            'application_id' => $request->application_id,
            'recruiter_id'   => auth()->id(), 
            'candidate_email' => $request->candidate_email,
            'date_time'      => $request->date_time,
            'type'           => $request->type ?? 'offline',
            'link'           => $request->link,
        ]);

        return $next($interview);
    }
}