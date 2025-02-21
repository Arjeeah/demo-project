# Event Management API - README

This project is a backend API for an Event Management Platform built using Laravel. It demonstrates robust RESTful API design, token-based authentication with Laravel Sanctum, and role/permission management with Spatie Laravel Permission. The project is designed to meet the requirements for a backend developer interview project.

## Table of Contents

- [Business Scenario](#business-scenario)
- [Technologies & Libraries](#technologies--libraries)
- [Project Setup & Configuration](#project-setup--configuration)
- [.env Settings](#env-settings)
- [Migrations, Seeders, and Testing](#migrations-seeders-and-testing)
- [API Endpoints](#api-endpoints)
- [Performance Optimizations](#performance-optimizations)
- [Additional Libraries & Packages](#additional-libraries--packages)
- [Summary](#summary)

## Business Scenario

The API simulates an Event Management Platform where:

- **Admins** have full control over the platform.
- **Event Managers** can create, update, and delete events.
- **Attendees** can view events and purchase tickets.
- **Sponsors** support events.
- **Comments** can be added to events and venues using polymorphic relationships.

### Resources Created

- **User:** Handles authentication and role assignment.
- **Event:** Represents events with details such as title, description, start/end dates, and associated venue.
- **Venue:** Represents locations where events are held.
- **Ticket:** Manages ticket purchases linking events and users.
- **Sponsor:** Represents organizations that sponsor events.
- **Comment:** Demonstrates polymorphic relationships by allowing comments on both events and venues.

## Technologies & Libraries

- **Laravel 10+:** The PHP framework used to build the API.
- **MySQL:** The relational database.
- **Laravel Sanctum:** Provides token-based authentication for the API.
- **Spatie Laravel Permission:** Manages roles and permissions.
- **Faker:** Used for generating fake data via Laravel factories.
- **PHPUnit:** For automated feature and unit tests.

## Project Setup & Configuration

### Prerequisites

- PHP 8.0+
- Composer
- MySQL
- Node.js (optional, if you plan to add frontend assets)

### Installation

1. **Clone the Repository**
    ```bash
    git clone https://github.com/Arjeeah/demo-project.git
    cd demo-project
    ```
2. **Install Dependencies**
    ```bash
    composer install
    ```
3. **Install Sanctum & Spatie Packages**
    ```bash
    composer require laravel/sanctum spatie/laravel-permission
    ```
4. **Publish Vendor Assets**
    ```bash
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
    ```

## .env Settings

Copy the example environment file and update the settings:
```bash
cp .env.example .env
```
Then, open the `.env` file and set your database credentials and other sensitive data:
```env
APP_NAME="Event Management API"
APP_ENV=local
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=your_mysql_user
DB_PASSWORD=your_mysql_password

CACHE_DRIVER=file
QUEUE_CONNECTION=database

SANCTUM_STATEFUL_DOMAINS=localhost
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Migrations, Seeders, and Testing

### Running Migrations
```bash
php artisan migrate
```

### Running Seeders

Seed the database with sample data (venues, events, tickets, sponsors, comments, and roles/permissions):
```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=VenueSeeder
php artisan db:seed --class=EventSeeder
php artisan db:seed --class=TicketSeeder
php artisan db:seed --class=SponsorSeeder
php artisan db:seed --class=CommentSeeder
```
Alternatively, seed all at once by adding them to `DatabaseSeeder.php` and running:
```bash
php artisan migrate:fresh --seed
```

### Running Tests
```bash
php artisan test
```
The tests cover all endpoints (Events, Venues, Tickets, Sponsors, and Comments) and verify that the API meets the requirements.

## API Endpoints

The API routes are grouped by resource and protected by authentication and role/permission middleware.

### Example: Event Routes

- **GET** `/api/events` – List events (with filtering via query parameters like `title`, `start_date_from`, and `start_date_to`).
- **GET** `/api/events/{id}` – Retrieve details for a specific event.
- **POST** `/api/events` – Create a new event (requires `create event` permission).
- **PUT/PATCH** `/api/events/{id}` – Update an event (requires `edit event` permission).
- **DELETE** `/api/events/{id}` – Delete an event (requires `delete event` permission).
- **POST** `/api/events/{id}/attach-sponsor` – Attach a sponsor to an event.
- **GET** `/api/events/{id}/attendees` – List event attendees.
- **POST** `/api/events/{id}/add-comment` – Add a comment to an event.

Other controllers (VenueController, TicketController, SponsorController, CommentController) have similar CRUD and custom relationship endpoints. See the `routes/api.php` file for full details.

## Performance Optimizations

### Caching

To improve performance, caching is implemented in the Event index endpoint. For example, the endpoint caches the filtered events for 60 seconds using Laravel's Cache facade:
```php
use Illuminate\Support\Facades\Cache;

public function index(Request $request)
{
    $cacheKey = 'events_' . md5($request->fullUrl());

    $events = Cache::remember($cacheKey, 60, function () use ($request) {
        $query = Event::with(['venue', 'manager', 'sponsors', 'attendees', 'comments']);

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->query('title') . '%');
        }
        if ($request->has('start_date_from')) {
            $query->where('start_date', '>=', $request->query('start_date_from'));
        }
        if ($request->has('start_date_to')) {
            $query->where('start_date', '<=', $request->query('start_date_to'));
        }

        return $query->get();
    });

    return response()->json($events);
}
```

## Additional Libraries & Packages

- **Laravel Sanctum:** Used for API token authentication. It provides a simple authentication system for SPAs and mobile applications.
- **Spatie Laravel Permission:** This package simplifies role and permission management. It allows fine-grained access control for API endpoints.
- **Faker:** Used to generate realistic fake data for testing and seeding the database.
- **PHPUnit:** The testing framework used to write and run unit and feature tests.

## Summary

This project demonstrates:

- A robust Laravel API design using RESTful principles.
- Token-based authentication with Laravel Sanctum.
- Role and permission management with Spatie.
- Complex database relationships including one-to-many, many-to-many, and polymorphic relationships.
- Automated tests that cover all endpoints and functionality.
- Performance optimizations via caching.
