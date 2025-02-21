<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
     protected function setUp(): void
    {
          parent::setUp();
                Role::firstOrCreate(['name' => 'admin']);

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $permissions = ['view event', 'create event', 'edit event', 'delete event'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $this->user->givePermissionTo($permissions);
    }

    /** @test */
    public function test_can_list_events_with_filters()
    {
        $venue = Venue::factory()->create();
        Event::factory()->count(3)->create(['venue_id' => $venue->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/events');
        if ($response->status() !== 200) {
            dd($response->json());
        }
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'description', 'start_date', 'end_date', 'venue_id']
                 ])
                 ->assertJsonCount(3);
    }

    /** @test */
    public function test_can_create_event()
    {
        $venue = Venue::factory()->create();

        $data = [
            'title'       => 'Test Event',
            'description' => 'This is a test event',
            'start_date'  => now()->addDay()->toDateTimeString(),
            'end_date'    => now()->addDays(2)->toDateTimeString(),
            'venue_id'    => $venue->id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/events', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Event']);
        $this->assertDatabaseHas('events', ['title' => 'Test Event']);
    }

    /** @test */
    public function test_can_update_event() {
        $venue = Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id, 'title' => 'Old Title']);

        $data = ['title' => 'Updated Title'];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/events/{$event->id}", $data);
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);
        $this->assertDatabaseHas('events', ['title' => 'Updated Title']);
    }

    /** @test */
    public function test_can_delete_event() {
        $venue = Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/events/{$event->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
