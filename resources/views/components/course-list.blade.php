<table class="table table-striped table-hover" style="table-layout: fixed; width: 100%;">
    <thead class="table-dark">
        <tr>
            <th style="width: 30%;">Nome</th>
            <th style="width: 15%;">Vagas Restantes</th>
            <th style="width: 20%;">Data Máxima</th>
            <th style="width: 15%;">Tipo</th>
            <th style="width: 20%;">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->max_students - $course->enrollments->count() }}</td>
                    <td>{{ $course->final_date }}</td>
                    <td>{{ $course->type == 'online' ? 'EAD' : 'Presencial' }}</td>
                    <td>
                        @php
                            $student = \App\Models\Student::where('email', auth()->user()->email)->first();
                            $isEnrolled = $student
                                ? $course->enrollments->where('student_id', $student->id)->count() > 0
                                : false;

                            $enrollmentClosed = now()->greaterThan($course->final_date) || $course->enrollments->count() >= $course->max_students;
                        @endphp

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('courses.enrollments', $course->id) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-person-plus"></i> Matricular
                            </a>
                        @else
                            @if($isEnrolled)
                                <button class="btn btn-secondary btn-sm" disabled data-bs-toggle="tooltip"
                                    title="Matrícula já realizada">
                                    <i class="bi bi-person-check"></i> Matrícula Realizada
                                </button>
                            @elseif($enrollmentClosed)
                                <button class="btn btn-secondary btn-sm" disabled data-bs-toggle="tooltip"
                                    title="Matrículas encerradas">
                                    <i class="bi bi-lock"></i> Matrículas Encerradas
                                </button>
                            @else
                                <a href="{{ route('courses.enrollments', $course->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-person-plus"></i> Matricular
                                </a>
                            @endif
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
    document.addEventListener('DOMContentLoaded', () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>