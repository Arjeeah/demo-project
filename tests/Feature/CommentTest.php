<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    
    protected function setUp(): void {
          parent::setUp();
                Role::firstOrCreate(['name' => 'admin']);

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $permissions = ['view comment', 'create comment', 'edit comment', 'delete comment'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $this->user->givePermissionTo($permissions);
    }

    /** @test */
    public function test_can_list_comments_with_filters() {
        $venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);
        Comment::factory()->count(3)->create([
            'commentable_type' => 'App\Models\Event',
            'commentable_id'   => $event->id,
            'user_id'          => $this->user->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/comments?commentable_type=App\Models\Event&commentable_id=' . $event->id);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'user_id', 'content', 'commentable_type', 'commentable_id']
                 ]);
    }

    /** @test */
    public function test_can_create_comment() {
        $venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);

        $data = [
            'content'          => 'Great event!',
            'commentable_type' => 'App\Models\Event',
            'commentable_id'   => $event->id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/comments', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['content' => 'Great event!']);
        $this->assertDatabaseHas('comments', ['content' => 'Great event!']);
    }

    /** @test */
    public function test_can_update_comment() {
$venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);        $comment = Comment::factory()->create([
            'commentable_type' => 'App\Models\Event',
            'commentable_id'   => $event->id,
            'user_id'          => $this->user->id,
            'content'          => 'Initial comment'
        ]);

        $data = ['content' => 'Updated comment'];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/comments/{$comment->id}", $data);
        $response->assertStatus(200)
                 ->assertJsonFragment(['content' => 'Updated comment']);
        $this->assertDatabaseHas('comments', ['content' => 'Updated comment']);
    }

    /** @test */
    public function test_can_delete_comment() {
$venue = \App\Models\Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);
                $comment = Comment::factory()->create([
            'commentable_type' => 'App\Models\Event',
            'commentable_id'   => $event->id,
            'user_id'          => $this->user->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/comments/{$comment->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
