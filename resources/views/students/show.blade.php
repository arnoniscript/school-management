<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight">
            {{ __('Editar Estudante') }}
        </h2>
    </x-slot>

    <div class="container mt-4">

        <!-- Formulário de Edição -->
        <form action="{{ route('students.update', $student) }}" method="POST" id="edit-student-form">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="full_name" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                    value="{{ old('full_name', $student->full_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="mother_name" class="form-label">Nome da Mãe</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name"
                    value="{{ old('mother_name', $student->mother_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="birth_date" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date"
                    value="{{ old('birth_date', $student->birth_date) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $student->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf', $student->cpf) }}"
                    required>
            </div>

            <a href="{{ route('students.index') }}" class="btn btn-secondary">Voltar</a>

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