<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
            {{ __('Estudantes') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <!-- Botão Criar Estudante -->
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">
                <i class="bi bi-plus-circle"></i> Criar Estudante
            </button>
        </div>

        <!-- Barra de Busca -->
        <x-search-bar />
    
        <div class="d-flex justify-content-between align-items-center mb-3">
        <x-number-per-page :route="route('students.index')" />
    <x-order-dropdown 
        :route="route('students.index')" 
        :fields="[
            'full_name' => 'Nome Completo',
            'cpf' => 'CPF',
            'email' => 'Email',
            'birth_date' => 'Data de Nascimento',
        ]" 
    />
</div>

        <!-- Tabela de Estudantes -->
        <x-student-table :students="$students" />

        <!-- Modal para Criação de Estudante -->
        <x-create-student-modal />
    </div>

    <script>
        document.getElementById('create-student-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('students.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Estudante criado com sucesso!');
                    location.reload();
                })
                .catch(error => {
                    alert('Erro ao criar estudante. Verifique os dados e tente novamente.');
                });
        });
    </script>
</x-app-layout>