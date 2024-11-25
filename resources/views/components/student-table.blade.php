<div class="table-responsive mt-3">
    <table class="table table-striped table-hover" style="table-layout: fixed; width: 100%;">
        <thead class="table-dark">
            <tr>
                <th style="width: 25%;">Nome Completo</th>
                <th style="width: 20%;">Nome da Mãe</th>
                <th style="width: 15%;">Data de Nascimento</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 10%;">CPF</th>
                <th style="width: 10%;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
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
                    <td colspan="6" class="text-center">Nenhum estudante encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $students->links() }}
</div>