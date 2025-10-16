

<?php
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'log.user.activity'])->group(function () {

    // مشاريع
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // مهام
    Route::get('/projects/{project_id}/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/projects/{project_id}/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    // تعليقات
    Route::get('/tasks/{task_id}/comments', [CommentController::class, 'index']);
    Route::post('/tasks/{task_id}/comments', [CommentController::class, 'store']);
});
