<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
            {{ __('Cursos') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <!-- Botão Criar Curso -->
        <div class="d-flex justify-content-between mb-3">
            @if(auth()->user()->role === 'admin')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal">
                    <i class="bi bi-plus-circle"></i> Criar Curso
                </button>
            @endif
        </div>

        <!-- Filtros de Busca -->
        <x-course-filter />

       

        <div class="d-flex justify-content-between align-items-center mb-3">
        <x-number-per-page :route="route('courses.index')" />
    <x-order-dropdown 
        :route="route('courses.index')" 
        :fields="[
            'name' => 'Nome',
            'max_students' => 'Máximo de Estudantes',
            'final_date' => 'Data Máxima',
            'type' => 'Tipo',
        ]" 
    />
</div>


        <!-- Tabela de Cursos -->
        <div class="table-responsive mt-3">
            <x-course-list :courses="$courses" />
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center mt-4">
            {{ $courses->links() }}
        </div>
    </div>

    <!-- Modal para Criação de Curso -->
    <div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCourseModalLabel">Criar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-course-form id="create-course-form" :action="route('courses.store')" method="POST" />
                </div>
            </div>
        </div>
    </div>

    <!-- Script para Submissão do Formulário -->
    <script>
        document.getElementById('create-course-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('courses.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        // Retornar JSON para capturar mensagem de erro
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    const modalElement = document.getElementById('createCourseModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);

                    // Fechar o modal após sucesso
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Exibir alerta de sucesso
                    alert('Curso criado com sucesso!');

                    // Recarregar a página
                    location.reload();
                })
                .catch(error => {
                    console.error('Erro:', error);

                    // Exibir alerta de erro
                    alert('Erro ao criar curso. Tente novamente.');
                });
        });
    </script>
</x-app-layout>