<?php

namespace Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'title',
                    'description',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function test_user_can_list_tasks()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'title',
                        'description',
                        'status',
                        'updated_at',
                        'created_at',
                        'id'
                    ]
                ]
            ]);
    }

    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $task = Task::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/tasks/' . $task->id, [
            'title' => 'Updated Task',
            'description' => 'This task has been updated',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'title',
                    'description',
                    'status',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $task = Task::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete('/api/tasks/' . $task->id);

        $response->assertStatus(204);
    }
}
