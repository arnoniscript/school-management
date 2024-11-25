<form method="GET" action="{{ route('courses.index') }}" class="mb-4">
    <div class="row g-2">
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Nome do curso"
                value="{{ request('name') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_students" class="form-control" placeholder="Num. Min. de Vagas"
                value="{{ request('max_students') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="final_date" class="form-control" value="{{ request('final_date') }}">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">Tipo</option>
                <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>EAD</option>
                <option value="presencial" {{ request('type') == 'presencial' ? 'selected' : '' }}>Presencial</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
    </div>
</form>