<div class="d-flex justify-content-start align-items-center mb-3">
    <form method="GET" action="{{ $route }}">
        @foreach(request()->except('per_page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="d-flex align-items-center">
            <label for="per_page" class="form-label me-2">Itens por página</label>
            <input type="number" id="per_page" name="per_page" class="form-control me-3"
                value="{{ request('per_page') }}" min="1" style="width: 100px;">
            <button type="submit" class="btn btn-primary">Aplicar</button>
        </div>
    </form>
</div>