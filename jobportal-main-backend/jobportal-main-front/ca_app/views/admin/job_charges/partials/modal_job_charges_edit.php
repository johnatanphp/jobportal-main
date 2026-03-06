<div class="modal fade" id="modal-job-charges-edit">
  <div class="modal-dialog">
    <form id="job-charges-form-edit" role="form" method="post" action="<?php echo site_url('admin/job_charges/edit'); ?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Editar  Grupo Ocupacional</h4>
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
              <select name="status" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="active" selected>Activo</option>
                <option value="inactive">Inactivo</option>
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