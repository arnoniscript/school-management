<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run()
    {

        Student::create([
            'full_name' => 'Estudante Exemplo',
            'mother_name' => 'Mãe Exemplo',
            'birth_date' => '2000-01-01',
            'email' => 'student@example.com',
            'cpf' => '12345678901',
        ]);

        Student::factory(50)->create();
    }
}
