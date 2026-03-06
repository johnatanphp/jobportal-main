<div class="modal fade" id="modal-level-studies-edit">
  <div class="modal-dialog">
    <form role="form" method="post" action="<?php echo site_url('admin/level_studies/edit'); ?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Editar Grado de estudios</h4>
        </div>
        <div class="modal-body"> 
          <!-- /.box-header --> 
          <!-- form start -->
          <div class="box-body">
            <input type="hidden" name="id" value="">
            <div class="form-group">
              <input type="text" class="form-control"  name="name" value="" placeholder="Nombre" required>
            </div>

            <div class="form-group">
              <input type="text" class="form-control"  name="valorization_score" value="" placeholder="Valorización puntaje">
            </div>

            <div class="form-group">
              <input type="text" class="form-control"  name="valorization_grade" value="" placeholder="Valorización grado">
            </div>

            <div class="form-group">
              <select name="active" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" name="submitter" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>