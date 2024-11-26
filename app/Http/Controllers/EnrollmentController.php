<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request, Course $course)
    {
        $user = auth()->user();

        // Verificar se o número de vagas está esgotado ou se a data máxima foi ultrapassada
        if (!$user->isAdmin() && ($course->enrollments->count() >= $course->max_students || now()->greaterThan($course->final_date))) {
            abort(403, 'As matrículas estão encerradas para este curso.');
        }

        if ($user->isAdmin()) {
            $studentsQuery = Student::query();

            // Aplicar filtros de busca
            if ($request->filled('name')) {
                $studentsQuery->where('full_name', 'LIKE', '%' . $request->input('name') . '%');
            }

            if ($request->filled('cpf')) {
                $studentsQuery->where('cpf', 'LIKE', '%' . $request->input('cpf') . '%');
            }

            $students = $studentsQuery->get();
        } else {
            // Verificar se o usuário está vinculado a um estudante
            $student = Student::where('email', $user->email)->first();
            if (!$student) {
                abort(403, 'Você não está autorizado a se matricular.');
            }
            $students = collect([$student]);
        }

        // Obter matrículas existentes
        $enrollments = Enrollment::where('course_id', $course->id)->pluck('student_id')->toArray();

        return view('enrollments.index', compact('course', 'students', 'enrollments', 'user'));
    }

    public function store(Request $request, Course $course)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && ($course->enrollments->count() >= $course->max_students || now()->greaterThan($course->final_date))) {
            abort(403, 'As matrículas estão encerradas para este curso.');
        }

        $validated = $request->validate([
            'students' => 'array', // `students` pode ser opcional
            'students.*' => 'exists:students,id',
        ]);

        if (!$user->isAdmin()) {
            $student = Student::where('email', $user->email)->first();
            if (!$student) {
                abort(403, 'Você não está autorizado a se matricular.');
            }

            $validated['students'] = [$student->id];
        }

        $existingEnrollments = Enrollment::where('course_id', $course->id)->pluck('student_id')->toArray();
        $selectedStudents = $validated['students'] ?? [];

        $studentsToAdd = array_diff($selectedStudents, $existingEnrollments);

        $studentsToRemove = array_diff($existingEnrollments, $selectedStudents);

        foreach ($studentsToAdd as $studentId) {
            Enrollment::updateOrCreate(
                ['course_id' => $course->id, 'student_id' => $studentId],
                ['enrollment_date' => now()]
            );
        }

        foreach ($studentsToRemove as $studentId) {
            Enrollment::where('course_id', $course->id)
                ->where('student_id', $studentId)
                ->delete();
        }

        return redirect()->route('courses.enrollments', $course)->with('success', 'Matrículas atualizadas com sucesso!');
    }
}

