<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
            {{ __('Matrícula no Curso: ') . $course->name }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <!-- Formulário de Filtro -->
        @if ($user->isAdmin())
            <form method="GET" action="{{ route('courses.enrollments', $course) }}" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Nome do Estudante"
                            value="{{ request('name') }}">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="cpf" class="form-control" placeholder="CPF"
                            value="{{ request('cpf') }}">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Filtrar</button>
                    <a href="{{ route('courses.enrollments', $course) }}" class="btn btn-secondary">Limpar Filtros</a>
                </div>
            </form>
        @endif

        <form action="{{ route('courses.enrollments.store', $course) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Selecione os Alunos:</label>
                <p><strong id="remaining-spots">{{ $course->max_students - count($enrollments) }}</strong> vagas restantes</p>
                <ul class="list-group" id="student-list">
                    @foreach ($students as $student)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $student->full_name }} ({{ $student->cpf }})</span>
                            <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                {{ in_array($student->id, $enrollments) ? 'checked' : '' }}
                                {{ in_array($student->id, $enrollments) ? 'data-checked="true"' : '' }}>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($user->isAdmin())
                <button type="submit" class="btn btn-primary">Atualizar Matrículas</button>
            @else
                @if (!in_array($students->first()->id, $enrollments))
                    <button type="submit" class="btn btn-primary">Confirmar Matrícula</button>
                @else
                    <p class="text-danger">Você já se matriculou neste curso.</p>
                @endif
            @endif

            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        @if(session('success'))
            <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('#student-list input[type="checkbox"]');
            const remainingSpotsElement = document.getElementById('remaining-spots');
            const maxSpots = {{ $course->max_students }};
            const initiallyChecked = {{ count($enrollments) }};
            let checkedCount = initiallyChecked;

            // Atualiza o número de vagas restantes
            const updateRemainingSpots = () => {
                const remainingSpots = maxSpots - checkedCount;
                remainingSpotsElement.textContent = remainingSpots;
            };

            // Inicializa as vagas restantes
            updateRemainingSpots();

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', (event) => {
                    if (event.target.checked) {
                        if (checkedCount < maxSpots) {
                            checkedCount++;
                        } else {
                            event.target.checked = false; // Desabilitar se exceder o limite
                        }
                    } else {
                        checkedCount--;
                    }
                    updateRemainingSpots();
                });
            });

            // Inicializar toasts do Bootstrap
            const successToast = document.getElementById('successToast');
            const errorToast = document.getElementById('errorToast');

            if (successToast) {
                new bootstrap.Toast(successToast).show();
            }

            if (errorToast) {
                new bootstrap.Toast(errorToast).show();
            }
        });
    </script>
</x-app-layout>
