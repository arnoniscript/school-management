<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

class EnrollmentUnitTest extends TestCase
{
    public function test_enrollment_belongs_to_student()
    {
        $enrollment = Enrollment::factory()->create();
        $this->assertInstanceOf(Student::class, $enrollment->student);
    }

    public function test_enrollment_belongs_to_course()
    {
        $enrollment = Enrollment::factory()->create();
        $this->assertInstanceOf(Course::class, $enrollment->course);
    }

    public function test_enrollment_date_is_set_correctly()
    {
        $enrollment = Enrollment::factory()->create(['enrollment_date' => '2024-01-01']);

        $this->assertEquals('2024-01-01', $enrollment->enrollment_date);
    }

}
