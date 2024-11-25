<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }

        $query = Student::query();

        if ($request->filled('full_name')) {
            $query->where('full_name', 'LIKE', '%' . $request->input('full_name') . '%');
        }

        if ($request->filled('mother_name')) {
            $query->where('mother_name', 'LIKE', '%' . $request->input('mother_name') . '%');
        }

        if ($request->filled('cpf')) {
            $query->where('cpf', 'LIKE', '%' . $request->input('cpf') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->input('email') . '%');
        }

        if ($request->filled('birth_date')) {
            $query->where('birth_date', $request->input('birth_date'));
        }

        $perPage = is_numeric($request->input('per_page')) && $request->input('per_page') > 0
            ? (int) $request->input('per_page')
            : 15;
        $sort = $request->input('sort', 'full_name'); // Ordenar por 'full_name' por padrão
        $direction = $request->input('direction', 'asc'); // Ordem ascendente por padrão
        $query->orderBy($sort, $direction);

        $students = $query->paginate($perPage)->appends($request->query());

        return view('students.index', compact('students'));
    }


    public function store(Request $request)
    {

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'required|email|unique:students,email',
            'cpf' => 'required|string|size:11|unique:students,cpf|regex:/^\d{11}$/',

        ]);

        Student::create($validated);

        return response()->json(['message' => 'Estudante criado com sucesso!']);
    }

    public function show(Student $student)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }

        return view('students.show', compact('student'));
    }
    public function update(Request $request, Student $student)
    {

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'cpf' => 'required|string|size:11|unique:students,cpf,' . $student->id,
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)->with('success', 'Estudante atualizado com sucesso!');
    }


}
