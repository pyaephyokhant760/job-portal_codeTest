<?php

namespace App\Pipelines\Interview;
use Closure;

class UpdateInterviewOutcome
{
    public function handle($data, Closure $next)
    {
        $interview = $data['interview'];
        $request = $data['request'];

        $interview->update([
            'outcome' => $request->outcome ?? $interview->outcome,
            'date_time' => $request->date_time ?? $interview->date_time,
        ]);

        return $next($interview);
    }
}