<form id="create-course-form">
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Curso</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="max_students" class="form-label">Máximo de Estudantes</label>
        <input type="number" class="form-control" id="max_students" name="max_students" required>
    </div>
    <div class="mb-3">
        <label for="final_date" class="form-label">Data Máxima de Matrícula</label>
        <input type="date" class="form-control" id="final_date" name="final_date" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Tipo</label>
        <select class="form-select" id="type" name="type" required>
            <option value="online">EAD</option>
            <option value="presencial">Presencial</option>
        </select>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</form>