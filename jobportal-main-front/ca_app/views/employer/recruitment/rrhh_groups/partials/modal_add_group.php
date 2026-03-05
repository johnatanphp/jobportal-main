<!-- Modal -->
<div id="modal-add-group" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
            Agregar Grupo RRHH
        </h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('employer/recruitment/rrhh_groups/create', ['id' => 'form-create-group']); ?>

            <label>Nombre</label>
            <input type="text" class="form-control" name="name" required>
            <br>
            <div align="center">
                <button class="btn btn-sm btn-primary">Crear</button>
            </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>