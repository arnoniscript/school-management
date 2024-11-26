<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;

class EnrollmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_enrollment_for_course()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($admin)->post(route('courses.enrollments.store', $course), [
            'students' => [$student->id],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.enrollments', $course));

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_non_admin_cannot_create_enrollment_for_course()
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.enrollments.store', $course), [
            'students' => [$student->id],
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_admin_can_view_enrollments_for_course()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $students = Student::factory()->count(3)->create();

        foreach ($students as $student) {
            Enrollment::factory()->create([
                'course_id' => $course->id,
                'student_id' => $student->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('courses.enrollments', $course));

        $response->assertStatus(200);
        $response->assertViewHas('students', function ($viewStudents) use ($students) {
            return $students->pluck('id')->diff($viewStudents->pluck('id'))->isEmpty();
        });
    }

    public function test_non_admin_cannot_view_enrollments_for_course()
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create();

        $response = $this->actingAs($user)->get(route('courses.enrollments', $course));

        $response->assertStatus(403);
    }
    public function test_admin_can_view_all_enrollments_with_filters()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Enrollment::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get(route('enrollments.all', [
            'student_name' => 'John',
            'course_name' => 'Math',
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('enrollments');
    }

    public function test_non_admin_cannot_view_all_enrollments()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get(route('enrollments.all'));

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_view_enrollments_for_closed_course()
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create([
            'max_students' => 1,
            'final_date' => now()->subDay(),
        ]);
        Enrollment::factory()->create(['course_id' => $course->id]);

        $response = $this->actingAs($user)->get(route('courses.enrollments', $course));

        $response->assertStatus(403);
    }
    public function test_non_admin_cannot_create_enrollments_for_other_students()
    {
        $user = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.enrollments.store', $course), [
            'students' => [$student->id],
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('enrollments', ['course_id' => $course->id, 'student_id' => $student->id]);
    }

    public function test_admin_can_create_enrollments()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($admin)->post(route('courses.enrollments.store', $course), [
            'students' => [$student->id],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('courses.enrollments', $course));
        $this->assertDatabaseHas('enrollments', ['course_id' => $course->id, 'student_id' => $student->id]);
    }

    public function test_admin_can_bulk_delete_enrollments()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $enrollments = Enrollment::factory()->count(3)->create();

        $response = $this->actingAs($admin)->delete(route('enrollments.bulk-delete'), [
            'selected_enrollments' => $enrollments->pluck('id')->toArray(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('enrollments.all'));

        foreach ($enrollments as $enrollment) {
            $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
        }
    }
    public function test_non_admin_cannot_bulk_delete_enrollments()
    {
        $user = User::factory()->create(['role' => 'student']);
        $enrollments = Enrollment::factory()->count(3)->create();

        $response = $this->actingAs($user)->delete(route('enrollments.bulk-delete'), [
            'selected_enrollments' => $enrollments->pluck('id')->toArray(),
        ]);

        $response->assertStatus(403);

        foreach ($enrollments as $enrollment) {
            $this->assertDatabaseHas('enrollments', ['id' => $enrollment->id]);
        }
    }

}
