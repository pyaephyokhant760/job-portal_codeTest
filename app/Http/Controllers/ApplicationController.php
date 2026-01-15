<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ApplicationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:application-list|application-store|application-update', only: ['index']),
            new Middleware('permission:application-store', only: ['store']),
            new Middleware('permission:application-update', only: ['update']),

        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return app(Pipeline::class)
            ->send(Application::query()) 
            ->through([
                \App\Pipelines\Application\FilterByRole::class, 
            ])
            ->then(function ($query) {
                
                $applications = $query->with(['user', 'work'])->get();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Applications retrieved successfully',
                    'data' => $applications 
                ], 200);
            });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:works,id',
            'cv_file' => 'required|file|mimes:pdf,docx|max:2048', 
        ]);

        return app(Pipeline::class)
            ->send($request)
            ->through([
                \App\Pipelines\Application\UploadCvFile::class,
                \App\Pipelines\Application\StoreApplication::class,
            ])
            ->then(fn($application) => response()->json($application, 201));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application) {
        return app(Pipeline::class)
        ->send([
            'application' => $application,
            'status' => $request->status, // shortlist, reject, reviewed
        ])
        ->through([ 
            \App\Pipelines\Application\UpdateStatus::class,    
        ])
        ->then(fn($data) => response()->json(['message' => 'Application status updated!']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
