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
            'recruiter_id'   => auth()->id(), // လက်ရှိ login ဝင်ထားသူကို recruiter အဖြစ်သတ်မှတ်
            'date_time'      => $request->date_time,
            'type'           => $request->type ?? 'offline',
            'link'           => $request->link,
        ]);

        return $next($interview);
    }
}