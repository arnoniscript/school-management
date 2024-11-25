<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Vagas Restantes</th>
            <th>Data Máxima</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($courses as $course)
            <tr>
                <td>{{ $course->name }}</td>
                <td>{{ $course->max_students }}</td>
                <td>{{ $course->final_date }}</td>
                <td>{{ $course->type == 'online' ? 'EAD' : 'Presencial' }}</td>
                <td>
                    <a href="#" class="btn btn-success btn-sm">
                        <i class="bi bi-person-plus"></i> Matricular
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="#" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Nenhum curso encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>