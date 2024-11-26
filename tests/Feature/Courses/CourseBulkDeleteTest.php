<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;

class CourseBulkDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_bulk_delete_courses()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $courses = Course::factory()->count(3)->create();

        $response = $this->actingAs($admin)->delete(route('courses.bulk-delete'), [
            'selected_courses' => $courses->pluck('id')->toArray(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.index'));

        foreach ($courses as $course) {
            $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        }
    }

    public function test_non_admin_cannot_bulk_delete_courses()
    {
        $user = User::factory()->create(['role' => 'student']);
        $courses = Course::factory()->count(3)->create();

        $response = $this->actingAs($user)->delete(route('courses.bulk-delete'), [
            'selected_courses' => $courses->pluck('id')->toArray(),
        ]);

        $response->assertStatus(403);

        foreach ($courses as $course) {
            $this->assertDatabaseHas('courses', ['id' => $course->id]);
        }
    }
}
