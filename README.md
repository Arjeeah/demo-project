<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Event Management API - README</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 20px;
      color: #333;
    }
    .container {
      max-width: 960px;
      margin: 0 auto;
      background: #fff;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h1, h2, h3 {
      color: #2c3e50;
    }
    h1 {
      text-align: center;
      margin-bottom: 10px;
    }
    .toc ul {
      list-style: none;
      padding-left: 0;
    }
    .toc li {
      margin-bottom: 8px;
    }
    a {
      color: #3498db;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    pre {
      background: #2d2d2d;
      color: #f8f8f2;
      padding: 15px;
      overflow-x: auto;
      border-radius: 4px;
    }
    code {
      font-family: Consolas, monospace;
    }
    .section {
      margin-bottom: 40px;
    }
    hr {
      border: none;
      border-top: 1px solid #ddd;
      margin: 40px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Event Management API</h1>
    <p>This project is a backend API for an Event Management Platform built using Laravel. It demonstrates robust RESTful API design, token-based authentication with Laravel Sanctum, and role/permission management with Spatie Laravel Permission. The project is designed to meet the requirements for a backend developer interview project.</p>
    
    <div class="section toc">
      <h2>Table of Contents</h2>
      <ul>
        <li><a href="#business-scenario">Business Scenario</a></li>
        <li><a href="#technologies-libraries">Technologies &amp; Libraries</a></li>
        <li><a href="#project-setup-configuration">Project Setup &amp; Configuration</a></li>
        <li><a href="#env-settings">.env Settings</a></li>
        <li><a href="#migrations-seeders-testing">Migrations, Seeders, and Testing</a></li>
        <li><a href="#api-endpoints">API Endpoints</a></li>
        <li><a href="#performance-optimizations">Performance Optimizations</a></li>
        <li><a href="#additional-libraries-packages">Additional Libraries &amp; Packages</a></li>
        <li><a href="#summary">Summary</a></li>
      </ul>
    </div>
    
    <div class="section" id="business-scenario">
      <h2>Business Scenario</h2>
      <p>The API simulates an Event Management Platform where:</p>
      <ul>
        <li><strong>Admins</strong> have full control over the platform.</li>
        <li><strong>Event Managers</strong> can create, update, and delete events.</li>
        <li><strong>Attendees</strong> can view events and purchase tickets.</li>
        <li><strong>Sponsors</strong> support events.</li>
        <li><strong>Comments</strong> can be added to events and venues using polymorphic relationships.</li>
      </ul>
      <h3>Resources Created</h3>
      <ul>
        <li><strong>User:</strong> Handles authentication and role assignment.</li>
        <li><strong>Event:</strong> Represents events with details such as title, description, start/end dates, and associated venue.</li>
        <li><strong>Venue:</strong> Represents locations where events are held.</li>
        <li><strong>Ticket:</strong> Manages ticket purchases linking events and users.</li>
        <li><strong>Sponsor:</strong> Represents organizations that sponsor events.</li>
        <li><strong>Comment:</strong> Demonstrates polymorphic relationships by allowing comments on both events and venues.</li>
      </ul>
    </div>
    
    <div class="section" id="technologies-libraries">
      <h2>Technologies &amp; Libraries</h2>
      <ul>
        <li><strong>Laravel 10+</strong> – The PHP framework used to build the API.</li>
        <li><strong>MySQL</strong> – The relational database.</li>
        <li><strong>Laravel Sanctum</strong> – Provides token-based authentication for the API.</li>
        <li><strong>Spatie Laravel Permission</strong> – Manages roles and permissions.</li>
        <li><strong>Faker</strong> – Used for generating fake data via Laravel factories.</li>
        <li><strong>PHPUnit</strong> – For automated feature and unit tests.</li>
      </ul>
    </div>
    
    <div class="section" id="project-setup-configuration">
      <h2>Project Setup &amp; Configuration</h2>
      <h3>Prerequisites</h3>
      <ul>
        <li>PHP 8.0+</li>
        <li>Composer</li>
        <li>MySQL</li>
        <li>Node.js (optional, if you plan to add frontend assets)</li>
      </ul>
      <h3>Installation</h3>
      <ol>
        <li><strong>Clone the Repository</strong>
          <pre><code>git clone https://github.com/yourusername/event-management-api.git
cd event-management-api</code></pre>
        </li>
        <li><strong>Install Dependencies</strong>
          <pre><code>composer install</code></pre>
        </li>
        <li><strong>Install Sanctum &amp; Spatie Packages</strong>
          <pre><code>composer require laravel/sanctum spatie/laravel-permission</code></pre>
        </li>
        <li><strong>Publish Vendor Assets</strong>
          <pre><code>php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"</code></pre>
        </li>
      </ol>
    </div>
    
    <div class="section" id="env-settings">
      <h2>.env Settings</h2>
      <p>Copy the example environment file and update the settings:</p>
      <pre><code>cp .env.example .env</code></pre>
      <p>Then, open the <code>.env</code> file and set your database credentials and other sensitive data:</p>
      <pre><code>APP_NAME="Event Management API"
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
SESSION_LIFETIME=120</code></pre>
    </div>
    
    <div class="section" id="migrations-seeders-testing">
      <h2>Migrations, Seeders, and Testing</h2>
      <h3>Running Migrations</h3>
      <pre><code>php artisan migrate</code></pre>
      <h3>Running Seeders</h3>
      <p>Seed the database with sample data (venues, events, tickets, sponsors, comments, and roles/permissions):</p>
      <pre><code>php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=VenueSeeder
php artisan db:seed --class=EventSeeder
php artisan db:seed --class=TicketSeeder
php artisan db:seed --class=SponsorSeeder
php artisan db:seed --class=CommentSeeder</code></pre>
      <p>Alternatively, seed all at once by adding them to <code>DatabaseSeeder.php</code> and running:</p>
      <pre><code>php artisan migrate:fresh --seed</code></pre>
      <h3>Running Tests</h3>
      <pre><code>php artisan test</code></pre>
      <p>The tests cover all endpoints (Events, Venues, Tickets, Sponsors, and Comments) and verify that the API meets the requirements.</p>
    </div>
    
    <div class="section" id="api-endpoints">
      <h2>API Endpoints</h2>
      <p>The API routes are grouped by resource and protected by authentication and role/permission middleware.</p>
      <h3>Example: Event Routes</h3>
      <ul>
        <li><strong>GET</strong> <code>/api/events</code> – List events (with filtering via query parameters like <code>title</code>, <code>start_date_from</code>, and <code>start_date_to</code>).</li>
        <li><strong>GET</strong> <code>/api/events/{id}</code> – Retrieve details for a specific event.</li>
        <li><strong>POST</strong> <code>/api/events</code> – Create a new event (requires <code>create event</code> permission).</li>
        <li><strong>PUT/PATCH</strong> <code>/api/events/{id}</code> – Update an event (requires <code>edit event</code> permission).</li>
        <li><strong>DELETE</strong> <code>/api/events/{id}</code> – Delete an event (requires <code>delete event</code> permission).</li>
        <li><strong>POST</strong> <code>/api/events/{id}/attach-sponsor</code> – Attach a sponsor to an event.</li>
        <li><strong>GET</strong> <code>/api/events/{id}/attendees</code> – List event attendees.</li>
        <li><strong>POST</strong> <code>/api/events/{id}/add-comment</code> – Add a comment to an event.</li>
      </ul>
      <p>Other controllers (VenueController, TicketController, SponsorController, CommentController) have similar CRUD and custom relationship endpoints. See the <code>routes/api.php</code> file for full details.</p>
    </div>
    
    <div class="section" id="performance-optimizations">
      <h2>Performance Optimizations</h2>
      <h3>Caching</h3>
      <p>To improve performance, caching is implemented in the Event index endpoint. For example, the endpoint caches the filtered events for 60 seconds using Laravel's Cache facade:</p>
      <pre><code>use Illuminate\Support\Facades\Cache;

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
}</code></pre>
    </div>
    
    <div class="section" id="additional-libraries-packages">
      <h2>Additional Libraries &amp; Packages</h2>
      <ul>
        <li><strong>Laravel Sanctum:</strong> Used for API token authentication. It provides a simple authentication system for SPAs and mobile applications.</li>
        <li><strong>Spatie Laravel Permission:</strong> This package simplifies role and permission management. It allows fine-grained access control for API endpoints.</li>
        <li><strong>Faker:</strong> Used to generate realistic fake data for testing and seeding the database.</li>
        <li><strong>PHPUnit:</strong> The testing framework used to write and run unit and feature tests.</li>
      </ul>
    </div>
    
    <div class="section" id="summary">
      <h2>Summary</h2>
      <p>This project demonstrates:</p>
      <ul>
        <li>A robust Laravel API design using RESTful principles.</li>
        <li>Token-based authentication with Laravel Sanctum.</li>
        <li>Role and permission management with Spatie.</li>
        <li>Complex database relationships including one-to-many, many-to-many, and polymorphic relationships.</li>
        <li>Automated tests that cover all endpoints and functionality.</li>
        <li>Performance optimizations via caching.</li>
        <li>Background job processing with queues (if implemented separately).</li>
      </ul>
      <p>Feel free to customize the project further, add more endpoints, or expand the test suite as needed.</p>
    </div>
  </div>
</body>
</html>
