<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            
            new Middleware('permission:category-list|category-store|category-update|category-delete', only: ['index']),
            new Middleware('permission:category-store', only: ['store']),
            new Middleware('permission:category-update', only: ['update']),
            new Middleware('permission:category-delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Category::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);

        return app(Pipeline::class)
            ->send($request)
            ->through([\App\Pipelines\Category\StoreCategory::class])
            ->then(fn($category) => response()->json($category, 201));
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
    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|unique:categories,name,' . $category->id]);

        return app(Pipeline::class)
            ->send(['request' => $request, 'category' => $category])
            ->through([\App\Pipelines\Category\UpdateCategory::class])
            ->then(fn($data) => response()->json(['message' => 'Updated!', 'data' => $data]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Deleted!']);
    }
}
