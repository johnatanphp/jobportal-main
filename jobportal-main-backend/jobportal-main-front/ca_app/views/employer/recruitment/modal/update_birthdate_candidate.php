<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="calendarModalLabel">Actualizar Fecha de Nacimiento</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                <label for="email-candidate" class="col-form-label">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="birthdate-candidate">
                <input type="hidden" class="form-control" id="id-candidate">
            </div>
            
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_update_birthdate">Guardar</button>
      </div>
    </div>
  </div>
</div>