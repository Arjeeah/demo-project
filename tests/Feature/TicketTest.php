<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void {
         parent::setUp();
                Role::firstOrCreate(['name' => 'admin']);

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $permissions = ['view ticket', 'create ticket', 'edit ticket', 'delete ticket'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $this->user->givePermissionTo($permissions);
    }

    /** @test */
    public function test_can_list_tickets_with_filters() {
        $venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);
        Ticket::factory()->count(3)->create([
            'event_id' => $event->id,
            'user_id'  => $this->user->id,
        ]);
        
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/tickets?event_id=' . $event->id);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'event_id', 'user_id', 'seat_number', 'purchase_date']
                 ]);
    }

    /** @test */
    public function test_can_create_ticket() {
        $venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);
        $data = [
            'event_id'    => $event->id,
            'seat_number' => 'A12',
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/tickets', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['seat_number' => 'A12']);
        $this->assertDatabaseHas('tickets', ['seat_number' => 'A12']);
    }

    /** @test */
    public function test_can_update_ticket() {
$venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);        $ticket = Ticket::factory()->create([
            'event_id'    => $event->id,
            'user_id'     => $this->user->id,
            'seat_number' => 'B10'
        ]);
        $data = ['seat_number' => 'B11'];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/tickets/{$ticket->id}", $data);
        $response->assertStatus(200)
                 ->assertJsonFragment(['seat_number' => 'B11']);
        $this->assertDatabaseHas('tickets', ['seat_number' => 'B11']);
    }

    /** @test */
    public function test_can_delete_ticket() {
$venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);        $ticket = Ticket::factory()->create([
            'event_id'    => $event->id,
            'user_id'     => $this->user->id,
        ]);
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/tickets/{$ticket->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }
}
