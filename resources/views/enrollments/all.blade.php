<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
            {{ __('Todas as Matrículas') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <!-- Formulário de Filtros -->
        <form method="GET" action="{{ route('enrollments.all') }}" class="mb-4">
            <div class="row g-2 align-items-center">
                <!-- Campo para Nome do Aluno -->
                <div class="col-md-3">
                    <input type="text" name="student_name" class="form-control" placeholder="Nome do Aluno"
                        value="{{ request('student_name') }}">
                </div>

                <!-- Campo para Nome do Curso -->
                <div class="col-md-3">
                    <input type="text" name="course_name" class="form-control" placeholder="Nome do Curso"
                        value="{{ request('course_name') }}">
                </div>

                <!-- Intervalo de Datas -->
                <div class="col-md-4">
                    <div class="p-3 rounded" style="background-color: #f8f9fa;">
                        <label for="date-range" class="form-label mb-2">Filtrar por Intervalo de Data</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                            <span class="input-group-text">até</span>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Botão de Filtrar -->
                <div class="col-md-2 text-center">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>



        <x-number-per-page :route="route('enrollments.all')" />

        <!-- Tabela -->
        <form method="POST" action="{{ route('enrollments.bulk-delete') }}" id="delete-form">
            @csrf
            @method('DELETE')

            <!-- Botão de Lixeira -->
            <div class="mb-3 d-none" id="delete-button-container">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Deletar Selecionados
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            @if(auth()->user()->isAdmin())
                                <th><input type="checkbox" id="select-all"></th>
                            @endif
                            <th>Nome do Aluno</th>
                            <th>Nome do Curso</th>
                            <th>Data de Matrícula</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr data-enrollment-id="{{ $enrollment->id }}">
                                @if(auth()->user()->isAdmin())
                                    <td>
                                        <input type="checkbox" class="select-enrollment-checkbox" value="{{ $enrollment->id }}"
                                            name="selected_enrollments[]">
                                    </td>
                                @endif
                                <td>{{ $enrollment->student->full_name }}</td>
                                <td>{{ $enrollment->course->name }}</td>
                                <td>{{ $enrollment->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhuma matrícula encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(auth()->user()->isAdmin())
                const checkboxes = document.querySelectorAll('.select-enrollment-checkbox');
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
</x-app-layout>