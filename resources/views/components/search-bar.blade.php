<div class="mb-4">
    <form method="GET" action="{{ route('students.index') }}">
        <!-- Primeira Linha: Nome e Nome da Mãe -->
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="full_name" class="form-control" placeholder="Nome do Estudante"
                    value="{{ request('full_name') }}">
            </div>
            <div class="col-md-6">
                <input type="text" name="mother_name" class="form-control" placeholder="Nome da Mãe"
                    value="{{ request('mother_name') }}">
            </div>
        </div>

        <!-- Segunda Linha: CPF, Data de Nascimento e Email -->
        <div class="row mt-3">
            <div class="col-md-4">
                <input type="text" name="cpf" class="form-control" placeholder="CPF" value="{{ request('cpf') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="birth_date" class="form-control" value="{{ request('birth_date') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="mt-3">
            <button type="submit" class="btn btn-success">Filtrar</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</div>