<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\SponsorController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\VenueController;

// Public routes: Registration and Login.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes using Sanctum middleware.
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
//------------------------------------Events---------------------------------------

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
    //------------------------------------Venues---------------------------------------
    Route::prefix('venues')->group(function () {
         // List all venues with optional filtering (requires "view venue" permission).
    Route::get('/', [VenueController::class, 'index'])->middleware('permission:view venue');

    // Retrieve a specific venue.
    Route::get('/{id}', [VenueController::class, 'show'])->middleware('permission:view venue');

    // Create a new venue (requires "create venue" permission).
    Route::post('/', [VenueController::class, 'store'])->middleware('permission:create venue');

    // Update an existing venue (requires "edit venue" permission).
    Route::put('/{id}', [VenueController::class, 'update'])->middleware('permission:edit venue');
    Route::patch('/{id}', [VenueController::class, 'update'])->middleware('permission:edit venue');

    // Delete a venue (requires "delete venue" permission).
    Route::delete('/{id}', [VenueController::class, 'destroy'])->middleware('permission:delete venue');

    // Additional endpoints:
    // Add a comment to a venue (using polymorphic relationship).
    Route::post('/{id}/add-comment', [VenueController::class, 'addComment'])->middleware('permission:view venue');

    // List all events at the venue.
    Route::get('/{id}/events', [VenueController::class, 'listEvents'])->middleware('permission:view venue');
    });

    //------------------------------------Tickets---------------------------------------
    Route::prefix('tickets')->group(function () {
       // List all tickets with optional filtering.
    Route::get('/', [TicketController::class, 'index'])->middleware('permission:view ticket');
    
    // Retrieve a specific ticket.
    Route::get('/{id}', [TicketController::class, 'show'])->middleware('permission:view ticket');
    
    // Create a new ticket.
    Route::post('/', [TicketController::class, 'store'])->middleware('permission:create ticket');
    
    // Update an existing ticket.
    Route::put('/{id}', [TicketController::class, 'update'])->middleware('permission:edit ticket');
    Route::patch('/{id}', [TicketController::class, 'update'])->middleware('permission:edit ticket');
    
    // Delete a ticket.
    Route::delete('/{id}', [TicketController::class, 'destroy'])->middleware('permission:delete ticket');
    });

    //------------------------------------Sponsors---------------------------------------
    Route::prefix('sponsors')->group(function () {
          // List all sponsors with filtering (requires "view sponsor" permission)
    Route::get('/', [SponsorController::class, 'index'])->middleware('permission:view sponsor');

    // Retrieve a specific sponsor (requires "view sponsor" permission)
    Route::get('/{id}', [SponsorController::class, 'show'])->middleware('permission:view sponsor');

    // Create a new sponsor (requires "create sponsor" permission)
    Route::post('/', [SponsorController::class, 'store'])->middleware('permission:create sponsor');

    // Update an existing sponsor (requires "edit sponsor" permission)
    Route::put('/{id}', [SponsorController::class, 'update'])->middleware('permission:edit sponsor');
    Route::patch('/{id}', [SponsorController::class, 'update'])->middleware('permission:edit sponsor');

    // Delete a sponsor (requires "delete sponsor" permission)
    Route::delete('/{id}', [SponsorController::class, 'destroy'])->middleware('permission:delete sponsor');

    // Attach an event to this sponsor (demonstrates many-to-many relationship; requires "edit sponsor" permission)
    Route::post('/{id}/attach-event', [SponsorController::class, 'attachEvent'])->middleware('permission:edit sponsor');

    });

    //------------------------------------Comments---------------------------------------
    Route::prefix('comments')->group(function () {
         // List all comments with optional filtering (requires "view comment" permission)
    Route::get('/', [CommentController::class, 'index'])->middleware('permission:view comment');

    // Retrieve a specific comment (requires "view comment" permission)
    Route::get('/{id}', [CommentController::class, 'show'])->middleware('permission:view comment');

    // Create a new comment (requires "create comment" permission)
    Route::post('/', [CommentController::class, 'store'])->middleware('permission:create comment');

    // Update an existing comment (requires "edit comment" permission)
    Route::put('/{id}', [CommentController::class, 'update'])->middleware('permission:edit comment');
    Route::patch('/{id}', [CommentController::class, 'update'])->middleware('permission:edit comment');

    // Delete a comment (requires "delete comment" permission)
    Route::delete('/{id}', [CommentController::class, 'destroy'])->middleware('permission:delete comment');

    });

});
