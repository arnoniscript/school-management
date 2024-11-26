<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Student;
use App\Models\User;


class StudentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_creation_with_valid_data()
    {
        $data = [
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '2000-01-01',
            'email' => 'johndoe@example.com',
            'cpf' => '12345678901',
        ];

        $student = Student::create($data);

        $this->assertDatabaseHas('students', ['email' => 'johndoe@example.com']);
    }

    public function test_students_index_page_displays_students()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Student::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertViewHas('students');
    }

    public function test_admin_can_create_student()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('students.store'), [
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '2000-01-01',
            'email' => 'johndoe@example.com',
            'cpf' => '12345678901',
        ]);

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Estudante criado com sucesso!']);

        $this->assertDatabaseHas('students', [
            'email' => 'johndoe@example.com',
            'cpf' => '12345678901',
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
        ]);
    }


    public function test_non_admin_cannot_create_student()
    {
        $studentUser = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($studentUser)->post(route('students.store'), [
            'full_name' => 'John Doe',
            'mother_name' => 'Jane Doe',
            'birth_date' => '2000-01-01',
            'email' => 'johndoe@example.com',
            'cpf' => '12345678901',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('students', ['email' => 'johndoe@example.com']);
    }

    public function test_non_admin_cannot_view_students_index()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get(route('students.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_student()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = Student::factory()->create([
            'full_name' => 'Old Name',
            'email' => 'oldemail@example.com',
        ]);

        $response = $this->actingAs($admin)->put(route('students.update', $student), [
            'full_name' => 'New Name',
            'mother_name' => $student->mother_name,
            'birth_date' => $student->birth_date,
            'email' => 'newemail@example.com',
            'cpf' => $student->cpf,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('students.show', $student));
        $this->assertDatabaseHas('students', ['full_name' => 'New Name', 'email' => 'newemail@example.com']);
    }

    public function test_non_admin_cannot_update_student()
    {
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create();

        $response = $this->actingAs($user)->put(route('students.update', $student), [
            'full_name' => 'Updated Name',
            'mother_name' => $student->mother_name,
            'birth_date' => $student->birth_date,
            'email' => $student->email,
            'cpf' => $student->cpf,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('students', ['full_name' => $student->full_name]);
    }

    public function test_admin_can_bulk_delete_students()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $students = Student::factory()->count(3)->create();

        $response = $this->actingAs($admin)->delete(route('students.bulk-delete'), [
            'selected_students' => $students->pluck('id')->toArray(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('students.index'));

        foreach ($students as $student) {
            $this->assertDatabaseMissing('students', ['id' => $student->id]);
        }
    }
    public function test_non_admin_cannot_bulk_delete_students()
    {
        $user = User::factory()->create(['role' => 'student']);
        $students = Student::factory()->count(3)->create();

        $response = $this->actingAs($user)->delete(route('students.bulk-delete'), [
            'selected_students' => $students->pluck('id')->toArray(),
        ]);

        $response->assertStatus(403);

        foreach ($students as $student) {
            $this->assertDatabaseHas('students', ['id' => $student->id]);
        }
    }

}
