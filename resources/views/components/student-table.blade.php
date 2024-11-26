<form method="POST" action="{{ route('students.bulk-delete') }}" id="delete-student-form">
    @csrf
    @method('DELETE')

    <!-- Botão de Lixeira (Somente para Admins) -->
    @if(auth()->user()->isAdmin())
        <div class="mb-3 d-none" id="delete-student-button-container">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Deletar Selecionados
            </button>
        </div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover" style="table-layout: fixed; width: 100%;">
            <thead class="table-dark">
                <tr>
                    @if(auth()->user()->isAdmin())
                        <th style="width: 5%;"><input type="checkbox" id="select-all-students"></th>
                    @endif
                    <th style="width: 25%;">Nome Completo</th>
                    <th style="width: 20%;">Nome da Mãe</th>
                    <th style="width: 15%;">Data de Nascimento</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 10%;">CPF</th>
                    <th style="width: 10%;">Ações</th>
                </tr>
            </thead>
            <tbody id="student-list">
                @forelse ($students as $student)
                    <tr data-student-id="{{ $student->id }}">
                        @if(auth()->user()->isAdmin())
                            <td>
                                <input type="checkbox" class="select-student-checkbox" value="{{ $student->id }}"
                                    name="selected_students[]">
                            </td>
                        @endif
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->mother_name }}</td>
                        <td>{{ $student->birth_date }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->cpf }}</td>
                        <td>
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhum estudante encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $students->links() }}
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(auth()->user()->isAdmin())
            const checkboxes = document.querySelectorAll('.select-student-checkbox');
            const deleteButtonContainer = document.getElementById('delete-student-button-container');
            const selectAllCheckbox = document.getElementById('select-all-students');

            // Atualiza o botão de deletar ao alterar checkboxes
            const updateDeleteButton = () => {
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                deleteButtonContainer.classList.toggle('d-none', !anyChecked);
            };

            // Marcar/desmarcar todos os checkboxes
            selectAllCheckbox.addEventListener('change', (event) => {
                const isChecked = event.target.checked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    checkbox.closest('tr').classList.toggle('table-warning', isChecked);
                });
                updateDeleteButton();
            });

            // Atualizar destaque e botão ao clicar individualmente
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