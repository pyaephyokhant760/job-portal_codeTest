<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class InterviewController extends Controller implements HasMiddleware
{
     public static function middleware(): array
    {
        return [
            new Middleware('permission:interview-list|interview-store|interview-update|', only: ['index']),
            new Middleware('permission:interview-store', only: ['work_store']),
            new Middleware('permission:interview-update', only: ['work_update']),
        ];
    }

    /*********************************************************************** */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return app(Pipeline::class)
            ->send(Interview::query())
            ->through([\App\Pipelines\Interview\FilterInterviews::class])
            ->then(fn($query) => response()->json($query->with(['application', 'recruiter'])->get()));
    }

    /*********************************************************************** */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return app(Pipeline::class)
            ->send($request)
            ->through([
                \App\Pipelines\Interview\ValidateInterviewRequest::class,
                \App\Pipelines\Interview\SaveInterview::class,
                \App\Pipelines\Interview\SendInterviewNotification::class,
            ])
            
            ->then(fn($interview) => response()->json($interview, 201));
    }

    /*********************************************************************** */

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    /*********************************************************************** */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $interview = Interview::findOrFail($id);
        return app(Pipeline::class)
            ->send(['request' => $request, 'interview' => $interview])
            ->through([\App\Pipelines\Interview\UpdateInterviewOutcome::class])
            ->then(fn($interview) => response()->json($interview));
    }

    /*********************************************************************** */
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
