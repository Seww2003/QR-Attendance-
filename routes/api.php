<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;


// ========== LOGIN & TOKEN ==========
Route::prefix('admin')->name('admin.')->group(function () {
    
    // PUBLIC ROUTE
    Route::post('/login', [AdminController::class, 'login']);

    // PROTECTED ROUTES (require token)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/students', [AdminController::class, 'students']);
    });
});
    

// ========== ADMIN STUDENTS API ==========
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

    Route::get('/students', [AdminController::class, 'students']);          // LIST
    Route::post('/students', [AdminController::class, 'storeStudent']);     // CREATE
    Route::get('/students/{id}', [AdminController::class, 'showStudent']);  // VIEW ONE
    Route::put('/students/{id}', [AdminController::class, 'updateStudent']); // UPDATE
    Route::delete('/students/{id}', [AdminController::class, 'destroyStudent']); // DELETE
});
