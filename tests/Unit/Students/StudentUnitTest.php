<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;




class StudentUnitTest extends TestCase
{

    public function test_student_creation_fails_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'full_name' => '', // Nome inválido
            'email' => 'not-an-email', // Email inválido
            'cpf' => '123', // CPF inválido
        ];

        Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'cpf' => 'required|string|size:11',
        ])->validate();
    }
}
