<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Course;
use App\Models\User;


class CourseFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_courses_index_page_displays_courses()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Course::factory()->count(5)->create();

        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertViewHas('courses');
    }

    public function test_admin_can_view_course()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        $response = $this->actingAs($admin)->get(route('courses.show', $course));

        $response->assertStatus(200);
        $response->assertViewIs('courses.show');
        $response->assertViewHas('course', $course);
    }

    public function test_non_admin_cannot_view_course()
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create();

        $response = $this->actingAs($user)->get(route('courses.show', $course));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_course()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();

        $updatedData = [
            'name' => 'Updated Course',
            'max_students' => 50,
            'final_date' => '2024-12-31',
            'type' => 'presencial',
        ];

        $response = $this->actingAs($admin)->put(route('courses.update', $course), $updatedData);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.show', $course));
        $this->assertDatabaseHas('courses', ['name' => 'Updated Course']);
    }

    public function test_non_admin_cannot_update_course()
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create();

        $updatedData = [
            'name' => 'Updated Course',
            'max_students' => 50,
            'final_date' => '2024-12-31',
            'type' => 'presencial',
        ];

        $response = $this->actingAs($user)->put(route('courses.update', $course), $updatedData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('courses', ['name' => 'Updated Course']);
    }

    public function test_courses_index_with_pagination_and_sorting()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Course::factory()->count(10)->create();

        $response = $this->actingAs($admin)->get(route('courses.index', [
            'per_page' => 5,
            'sort' => 'max_students',
            'direction' => 'desc',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('courses');
        $this->assertCount(5, $response->viewData('courses'));
    }



}
