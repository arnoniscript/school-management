<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
            {{ __('Detalhes do Curso') }}
        </h2>
    </x-slot>

    <div class="container mt-4">

        <form action="{{ route('courses.update', $course) }}" method="POST" id="edit-course-form">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nome do Curso</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $course->name }}" required>
            </div>

            <div class="mb-3">
                <label for="max_students" class="form-label">Máximo de Estudantes</label>
                <input type="number" class="form-control" id="max_students" name="max_students"
                    value="{{ $course->max_students }}" required>
            </div>

            <div class="mb-3">
                <label for="final_date" class="form-label">Data Máxima de Matrícula</label>
                <input type="date" class="form-control" id="final_date" name="final_date"
                    value="{{ $course->final_date }}" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Tipo</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="online" {{ $course->type == 'online' ? 'selected' : '' }}>EAD</option>
                    <option value="presencial" {{ $course->type == 'presencial' ? 'selected' : '' }}>Presencial</option>
                </select>
            </div>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                Voltar
            </a>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>

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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successToast = document.getElementById('successToast');
            if (successToast) {
                const toast = new bootstrap.Toast(successToast);
                toast.show();
            }
        });
    </script>
</x-app-layout>