<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;

// Public routes: Registration and Login.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes using Sanctum middleware.
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::prefix('events')->group(function () {
        // List all events – requires permission to view events.
        Route::get('/', [EventController::class, 'index'])->middleware('permission:view event');

        // Retrieve a specific event – requires permission to view events.
        Route::get('/{id}', [EventController::class, 'show'])->middleware('permission:view event');

        // Create a new event – requires permission to create events.
        Route::post('/', [EventController::class, 'store'])->middleware('permission:create event');

        // Update an existing event – requires permission to edit events.
        Route::put('/{id}', [EventController::class, 'update'])->middleware('permission:edit event');
        Route::patch('/{id}', [EventController::class, 'update'])->middleware('permission:edit event');

        // Delete an event – requires permission to delete events.
        Route::delete('/{id}', [EventController::class, 'destroy'])->middleware('permission:delete event');

        // Attach a sponsor to an event – requires permission to edit events.
        Route::post('/{id}/attach-sponsor', [EventController::class, 'attachSponsor'])->middleware('permission:edit event');

        // List all attendees of an event – requires permission to view events.
        Route::get('/{id}/attendees', [EventController::class, 'listAttendees'])->middleware('permission:view event');

        // Add a comment to an event – requires permission to view events (or a dedicated "comment" permission).
        Route::post('/{id}/add-comment', [EventController::class, 'addComment'])->middleware('permission:view event');
    });

});
