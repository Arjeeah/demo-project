<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VenueTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    
    protected function setUp(): void {
        parent::setUp();
                Role::firstOrCreate(['name' => 'admin']);

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $permissions = ['view venue', 'create venue', 'edit venue', 'delete venue'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $this->user->givePermissionTo($permissions);
    }

    /** @test */
    public function test_can_list_venues_with_filter() {
        Venue::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'web')
                         ->getJson('/api/venues?name=');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'address', 'capacity']
                 ]);
    }

    /** @test */
    public function test_can_create_venue() {
        $data = [
            'name'     => 'Main Venue',
            'address'  => '123 Main St',
            'capacity' => 500,
        ];

        $response = $this->actingAs($this->user, 'web')
                         ->postJson('/api/venues', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Main Venue']);
        $this->assertDatabaseHas('venues', ['name' => 'Main Venue']);
    }

    /** @test */
    public function test_can_update_venue() {
        $venue = Venue::factory()->create(['name' => 'Old Venue']);
        $data = ['name' => 'Updated Venue'];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/venues/{$venue->id}", $data);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Venue']);
        $this->assertDatabaseHas('venues', ['name' => 'Updated Venue']);
    }

    /** @test */
    public function test_can_delete_venue() {
        $venue = Venue::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/venues/{$venue->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('venues', ['id' => $venue->id]);
    }
}
