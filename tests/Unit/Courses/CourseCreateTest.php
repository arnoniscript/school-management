<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;



class CourseCreateTest extends TestCase
{
    public function test_course_can_be_created_with_valid_data()
    {
        $course = new Course([
            'name' => 'Test Course',
            'max_students' => 50,
            'final_date' => '2024-12-31',
            'type' => 'online',
        ]);

        $this->assertEquals('Test Course', $course->name);
        $this->assertEquals(50, $course->max_students);
        $this->assertEquals('2024-12-31', $course->final_date);
        $this->assertEquals('online', $course->type);
    }

    public function test_course_creation_fails_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'max_students' => -1,
            'final_date' => 'not-a-date',
            'type' => 'invalid-type',
        ];

        $translator = new Translator(new ArrayLoader(), 'en');
        $validatorFactory = new Factory($translator);

        $validatorFactory->make($data, [
            'name' => 'required|string|max:255',
            'max_students' => 'required|integer|min:1',
            'final_date' => 'required|date',
            'type' => 'required|in:online,presencial',
        ])->validate();
    }
}
