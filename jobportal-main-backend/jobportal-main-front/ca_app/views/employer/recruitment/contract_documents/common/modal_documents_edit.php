<div class="modal fade" id="modal-contract-documents-edit">
  <div class="modal-dialog">
  <?php echo form_open('employer/recruitment/contract_documents/edit', ['id' => 'form-contract-documents-edit', 'method' => 'post']); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Editar RyS Documento</h4>
        </div>
        <div class="modal-body"> 
          <!-- /.box-header --> 
          <!-- form start -->
          <div class="box-body">
            <input type="hidden" name="id" value="">

            <div class="form-group">
              <label>Nombre</label>
              <input type="text" class="form-control"  name="name" value="" placeholder="Nombre" required>
            </div>

            <div class="form-group">
              <label>Tipo documento</label>
              <select name="option_type_id" class="form-control" required>
                <option value="">Seleccione Tipo</option>
                <option value="1">Adjunto</option>
                <option value="2">Programado</option>
                <option value="3">Formulario</option>
              </select>
            </div>

            <div class="container-attach">
              <div class="form-group">
                <label>Formatos del adjunto permitido</label>
                <br>
                <?php foreach(get_allowed_files() as $file_format): ?>
                  <span style="margin-right:7px;">
                    <input type="checkbox" name="attach_allowed_file[]" value="<?php e($file_format); ?>">
                    <label for=""><?php e($file_format); ?></label>
                  </span>
                <?php endforeach; ?>
              </div>

              <div class="form-group">
                <label>Máximo tamaño del adjunto</label>
                <select name="attach_max_size" class="form-control">
                  <option value="">Seleccione</option>
                  <option value="1">1 MB</option>
                  <option value="2">2 MB</option>
                  <option value="3">3 MB</option>
                  <option value="4">4 MB</option>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label>Asignar grupo</label>
              <select name="group_id" class="form-control">
                <option value="">Ninguno</option>
                <option value="1">Ficha de ingreso</option>
              </select>
            </div>
          
            <div class="form-group">
              <label>Estado</label>
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
    <?php echo form_close(); ?>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>