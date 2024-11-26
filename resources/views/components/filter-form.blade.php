<div class="mb-4">
    <form method="GET" action="{{ $action }}">
        <div class="row">
            @foreach ($fields as $field)
                <div class="col-md-6">
                    <input type="text" name="{{ $field['name'] }}" class="form-control"
                        placeholder="{{ $field['placeholder'] }}" value="{{ request($field['name']) }}">
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-success">Filtrar</button>
            <a href="{{ $action }}" class="btn btn-secondary">Limpar Filtros</a>
        </div>
    </form>
</div>