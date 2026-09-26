<?php

use App\Http\Controllers\TaskController;

// Optional: Redirect root URL to tasks index
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// 1. Custom static/action routes MUST come BEFORE Route::resource()
Route::get('/tasks/trash', [TaskController::class, 'trash'])->name('tasks.trash');
Route::patch('/tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
Route::delete('/tasks/{id}/force-delete', [TaskController::class, 'forceDelete'])->name('tasks.force-delete');
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');

// 2. Resource routes come after
Route::resource('tasks', TaskController::class);
