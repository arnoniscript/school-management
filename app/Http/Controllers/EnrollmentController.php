<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{

    public function allEnrollments(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }

        $query = Enrollment::query();

        if ($request->filled('student_name')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('full_name', 'LIKE', '%' . $request->input('student_name') . '%');
            });
        }

        if ($request->filled('course_name')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->input('course_name') . '%');
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);

        $perPage = $request->input('per_page', 15);
        $enrollments = $query->with(['student', 'course'])->paginate($perPage)->appends($request->query());

        return view('enrollments.all', compact('enrollments'));
    }
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
    public function bulkDelete(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }
        $validated = $request->validate([
            'selected_enrollments' => 'required|array',
            'selected_enrollments.*' => 'exists:enrollments,id',
        ]);

        Enrollment::whereIn('id', $validated['selected_enrollments'])->delete();

        return redirect()->route('enrollments.all')->with('success', 'Matrículas deletadas com sucesso!');
    }

}

