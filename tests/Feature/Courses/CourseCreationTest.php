<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class CourseCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_course()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('courses.store'), [
            'name' => 'New Course',
            'max_students' => 30,
            'final_date' => '2024-12-31',
            'type' => 'online',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Curso criado com sucesso!',
        ]);

        $this->assertDatabaseHas('courses', ['name' => 'New Course']);
    }

    public function test_non_admin_cannot_create_course()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => 'New Course',
            'max_students' => 30,
            'final_date' => '2024-12-31',
            'type' => 'online',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('courses', ['name' => 'New Course']);
    }
}
