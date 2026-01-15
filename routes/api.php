<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', [\App\Http\Controllers\RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/2fa/enable', [RegisterController::class, 'enable2fa']);
    Route::get('work',[WorkController::class,'index']);
    Route::post('work/create',[WorkController::class,'work_store']);
    Route::patch('work/update/{id}',[WorkController::class,'work_update']);
    Route::get('work/delete',[WorkController::class,'work_destroy']);
    
    Route::post('/job/store', [WorkController::class, 'work_store']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('applications', ApplicationController::class)->only(['index', 'store', 'show']);
});
