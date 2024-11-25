<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="{{ $route }}" class="d-flex">
        @foreach(request()->except(['sort', 'direction']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="me-3">
            <label for="sort" class="form-label me-2">Ordenar por</label>
            <select name="sort" id="sort" class="form-select">
                @foreach($fields as $field => $label)
                    <option value="{{ $field }}" {{ request('sort') == $field ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="me-3">
            <label for="direction" class="form-label me-2">Ordem</label>
            <select name="direction" id="direction" class="form-select">
                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descendente</option>
            </select>
        </div>

        <div class="align-self-end">
            <button type="submit" class="btn btn-primary">Aplicar</button>
        </div>
    </form>
</div>