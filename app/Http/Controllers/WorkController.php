<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
// ဒီနှစ်ကြောင်းကို မဖြစ်မနေ ထည့်ပေးရပါမယ်
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WorkController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:work-list|work-store|work-update|work-delete', only: ['index']),
            new Middleware('permission:work-store', only: ['work_store']),
            new Middleware('permission:work-update', only: ['work_update']),
            new Middleware('permission:work-delete', only: ['work_destroy']),
        ];
    }
    //index
    public function index()
    {
        return app(\Illuminate\Pipeline\Pipeline::class)
            ->send(Work::query())
            ->through([
                
                \App\Pipelines\Job\FilterByTitle::class,
                \App\Pipelines\Job\FilterByLocation::class,
                \App\Pipelines\Job\FilterByCategory::class,
                \App\Pipelines\Job\GenerateJobReport::class,
            ])
            ->then(function ($payload) {
                $query = $payload['query'];
                $report = $payload['report_data'];

                $perPage = (int) request('per_page', 10);
                $page = (int) request('page', 1);

                $works = $query->with('category:id,name')
                    ->latest()
                    ->paginate($perPage, ['*'], 'page', $page);

                return response()->json([
                    'status'  => 'success',
                    'reports' => $report,
                    'data'    => $works->items(),
                    'meta'    => [
                        'current_page' => $works->currentPage(),
                        'last_page'    => $works->lastPage(),
                        'per_page'     => $works->perPage(),
                        'total'        => $works->total(),
                    ]
                ], 200);
            });
    }

    //create
    public function work_store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required'
        ]);

        return app(Pipeline::class)
            ->send($request)

            ->through([\App\Pipelines\Job\StoreJob::class])
            ->then(fn($job) => response()->json($job, 201));
    }

    //update
    public function work_update(Request $request, Work $work)
    {
        $user = $request->user();

        if ($user->id !== $work->employer_id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return app(\Illuminate\Pipeline\Pipeline::class)
            ->send([
                'request' => $request,
                'work' => $work
            ])
            ->through([
                \App\Pipelines\Job\UpdateJob::class,
            ])
            ->then(fn($updatedWork) => response()->json([
                'message' => 'Work updated successfully',
                'data' => $updatedWork
            ]));
    }

    // Delete Method
    public function work_destroy(Request $request, Work $work)
    {
        $user = $request->user();

        if ($user->id !== $work->employer_id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $work->delete();

        return response()->json(['message' => 'Work deleted successfully']);
    }
}
