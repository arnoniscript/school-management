<?php

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition()
    {
        return [
            'course_id' => \App\Models\Course::factory(),
            'student_id' => \App\Models\Student::factory(),
            'enrollment_date' => now(),
        ];
    }
}
