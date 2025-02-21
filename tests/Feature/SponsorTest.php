<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Sponsor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class SponsorTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    
    protected function setUp(): void {
           parent::setUp();
                Role::firstOrCreate(['name' => 'admin']);

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $permissions = ['view sponsor', 'create sponsor', 'edit sponsor', 'delete sponsor'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $this->user->givePermissionTo($permissions);
    }

    /** @test */
    public function test_can_list_sponsors_with_filters() {
        Sponsor::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/sponsors?name=');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'description', 'logo_url']
                 ]);
    }

    /** @test */
    public function test_can_create_sponsor() {
        $data = [
            'name'        => 'Acme Corp',
            'description' => 'Leading supplier of widgets.',
            'logo_url'    => 'https://example.com/logo.png'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/sponsors', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Acme Corp']);
        $this->assertDatabaseHas('sponsors', ['name' => 'Acme Corp']);
    }

    /** @test */
    public function test_can_update_sponsor() {
        $sponsor = Sponsor::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/sponsors/{$sponsor->id}", $data);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'New Name']);
        $this->assertDatabaseHas('sponsors', ['name' => 'New Name']);
    }

    /** @test */
    public function test_can_delete_sponsor() {
        $sponsor = Sponsor::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/sponsors/{$sponsor->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('sponsors', ['id' => $sponsor->id]);
    }
}
