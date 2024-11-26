<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        $courses = Course::all();
        $students = Student::all();

        foreach ($students as $student) {
            Enrollment::create([
                'course_id' => $courses->random()->id,
                'student_id' => $student->id,
                'enrollment_date' => now(),
            ]);
        }
    }
}
