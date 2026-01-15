<?php

namespace App\Pipelines\Application;

use Closure;

class UploadCvFile
{
    public function handle($request, Closure $next)
    {
        if ($request->hasFile('cv_file')) {
            
            $path = $request->file('cv_file')->store('resumes', 'public');
            
            
            $request->merge(['cv_path' => $path]);
        }

        return $next($request);
    }
}