<form method="POST" action="{{ route('courses.bulk-delete') }}" id="delete-form">
    @csrf
    @method('DELETE')

    @if(auth()->user()->isAdmin())
        <div class="mb-3 d-none" id="delete-button-container">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Deletar Selecionados
            </button>
        </div>
    @endif

    <table class="table table-striped table-hover" style="table-layout: fixed; width: 100%;">
        <thead class="table-dark">
            <tr>
                @if(auth()->user()->isAdmin())
                    <th style="width: 5%;"><input type="checkbox" id="select-all"></th>
                @endif
                <th style="width: 30%;">Nome</th>
                <th style="width: 15%;">Vagas Restantes</th>
                <th style="width: 15%;">Data Máxima</th>
                <th style="width: 15%;">Tipo</th>
                <th style="width: 20%;">Ações</th>
            </tr>
        </thead>
        <tbody id="course-list">
            @foreach ($courses as $course)
                        <tr data-course-id="{{ $course->id }}">
                            @if(auth()->user()->isAdmin())
                                <td>
                                    <input type="checkbox" class="select-course-checkbox" value="{{ $course->id }}"
                                        name="selected_courses[]">
                                </td>
                            @endif
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

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                @endif
                            </td>
                        </tr>
            @endforeach
        </tbody>
    </table>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(auth()->user()->isAdmin())
            const checkboxes = document.querySelectorAll('.select-course-checkbox');
            const deleteButtonContainer = document.getElementById('delete-button-container');
            const selectAllCheckbox = document.getElementById('select-all');

            const updateDeleteButton = () => {
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                deleteButtonContainer.classList.toggle('d-none', !anyChecked);
            };

            selectAllCheckbox.addEventListener('change', (event) => {
                const isChecked = event.target.checked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    checkbox.closest('tr').classList.toggle('table-warning', isChecked);
                });
                updateDeleteButton();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', (event) => {
                    const row = event.target.closest('tr');
                    row.classList.toggle('table-warning', event.target.checked);
                    updateDeleteButton();
                });
            });
        @endif
    });
</script>