<!-- Modal -->
<div id="modal-edit-group" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
            Editar Grupo RRHH
        </h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('employer/recruitment/rrhh_groups/edit', ['id' => 'form-edit-group']); ?>

            <input id="group-edit-id" type="hidden" name="id" value="">
            <label>Nombre</label>
            <input id="group-edit-name" type="text" class="form-control" name="name" required>
            <br>
            <div align="center">
                <button class="btn btn-sm btn-primary">Guardar</button>
            </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
