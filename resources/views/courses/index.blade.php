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
                    <x-course-form />
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
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Curso criado com sucesso!');
                    location.reload();
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao criar curso. Verifique os dados e tente novamente.');
                });
        });
    </script>
</x-app-layout>