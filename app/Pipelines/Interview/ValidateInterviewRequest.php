<?php

namespace App\Pipelines\Interview;

use Closure;
use Illuminate\Http\Request;

class ValidateInterviewRequest
{
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'date_time'      => 'required|date|after:now', 
            'type'           => 'required|in:online,offline',
            'link'           => 'required_if:type,online|nullable|url',
            'outcome'        => 'nullable|in:pending,pass,fail',
        ]);

        return $next($request);
    }
}