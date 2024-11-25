<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importando Auth facade

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Filtros da URL
        $query = Course::query();

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        if ($request->filled('max_students')) {
            $query->where('max_students', '>=', $request->input('max_students'));
        }

        if ($request->filled('final_date')) {
            $query->whereDate('final_date', '<=', $request->input('final_date'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $courses = $query->paginate(10)->appends($request->query());

        return view('courses.index', compact('courses'));
    }


    public function store(Request $request)
    {
        // Verifica se o usuário logado é admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_students' => 'required|integer|min:1',
            'final_date' => 'required|date',
            'type' => 'required|in:online,presencial',
        ]);

        Course::create($validated);

        return response()->json(['message' => 'Curso criado com sucesso!']);
    }
}
