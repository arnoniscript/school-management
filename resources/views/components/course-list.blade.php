<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Vagas Restantes</th>
            <th>Data Máxima</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->max_students }}</td>
                    <td>{{ $course->final_date }}</td>
                    <td>{{ $course->type == 'online' ? 'EAD' : 'Presencial' }}</td>
                    <td>
                        @php
                            // Buscar o estudante vinculado ao usuário autenticado
                            $student = \App\Models\Student::where('email', auth()->user()->email)->first();
                            $isEnrolled = $student
                                ? \App\Models\Enrollment::where('course_id', $course->id)
                                    ->where('student_id', $student->id)
                                    ->exists()
                                : false;
                        @endphp

                        @if($isEnrolled && auth()->user()->role !== 'admin')
                            <button class="btn btn-secondary btn-sm" disabled data-bs-toggle="tooltip"
                                title="Matrícula já realizada">
                                <i class="bi bi-person-check"></i> Matrícula Realizada
                            </button>
                        @else
                            <a href="{{ route('courses.enrollments', $course->id) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-person-plus"></i> Matricular
                            </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                        @endif
                    </td>
                </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Nenhum curso encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    // Inicializar tooltips do Bootstrap
    document.addEventListener('DOMContentLoaded', () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>