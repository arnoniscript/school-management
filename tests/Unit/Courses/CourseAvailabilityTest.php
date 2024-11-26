<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Course;
use App\Models\Enrollment;

class CourseAvailabilityTest extends TestCase
{
    public function test_remaining_spots_calculation()
    {
        $course = Course::factory()->create(['max_students' => 50]);

        Enrollment::factory()->count(3)->create(['course_id' => $course->id]);

        $this->assertEquals(47, $course->remainingSpots());
    }
}

