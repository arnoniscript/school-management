@props(['action' => '#', 'method' => 'POST', 'course' => null, 'id' => 'course-form'])

<form action="{{ $action }}" method="POST" id="{{ $id }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <!-- Campos do formulário -->
    <div class="form-group">
        <label for="name">Nome do Curso</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $course->name ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="max_students">Máximo de Estudantes</label>
        <input type="number" class="form-control" id="max_students" name="max_students" min="1"
            value="{{ $course->max_students ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="final_date">Data Máxima de Matrícula</label>
        <input type="date" class="form-control" id="final_date" name="final_date"
            value="{{ $course->final_date ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="type">Tipo</label>
        <select class="form-control" id="type" name="type" required>
            <option value="online" {{ ($course->type ?? '') === 'online' ? 'selected' : '' }}>EAD</option>
            <option value="presencial" {{ ($course->type ?? '') === 'presencial' ? 'selected' : '' }}>Presencial</option>
        </select>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</form>