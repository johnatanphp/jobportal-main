<?php echo form_open('admin/sap_connections/save/' . $company_id, ['id' => 'form-sap-connection-save']); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">SAP conexión</h4>
  </div>
  <div class="modal-body">
    <div class="box-body">
      <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
      <div class="form-group">
        <label>API Root URL</label>
        <input type="text" class="form-control"  name="api_url" placeholder="API Root URL" value="<?php e(@$sap_connection->api_url); ?>" required>
      </div>
      <div class="form-group">
        <label>API Usuario</label>
        <input type="text" class="form-control"  name="api_username" placeholder="API Usuario" value="<?php e(@$sap_connection->api_username); ?>" required>
      </div>
      <div class="form-group">
        <label>API Contraseña</label>
        <input type="text" class="form-control"  name="api_password" placeholder="API Contraseña" value="<?php e(@$sap_connection->api_password); ?>" required>
      </div>
      <div class="form-group">
        <label>API Base de datos</label>
        <input type="text" class="form-control"  name="api_db" placeholder="API Base de datos" value="<?php e(@$sap_connection->api_db); ?>" required>
      </div>
      <div class="form-group">
        <label>API Estado</label>
        <select name="active" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="1" <?php echo @$sap_connection->active == '1' ? 'selected' : ''; ?>>Activa</option>
          <option value="0" <?php echo @$sap_connection->active == '0' ? 'selected' : ''; ?>>Inactiva</option>
        </select>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <div class="row">
      <div class="col-md-4" style="text-align: left;">
        <button id="sap-test-connection" type="button" class="btn btn-default">Probar conexión</button>
      </div>
      <div class="col-md-8">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" name="submitter" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
<?php echo form_close(); ?>

<script>
$(function(){
  $( '#form-sap-connection-save' ).submit(function(e){
    e.preventDefault();
    const url = $(this).prop('action');
    const data = $(this).serialize(); 

    const btnSubmit = $(this).find('button[type="submit"]');
    btnSubmit.prop('disabled', true);
    btnSubmit.html('Guardando...');

    $.post(url, data, function(res){

      btnSubmit.prop('disabled', false);
      btnSubmit.html('Guardar');

      if (!res.status) {
        toastr["error"](res.message);
        return;
      }

      toastr["success"](res.message);

    }, 'json')
    .fail(function(e){
      btnSubmit.prop('disabled', false);
      btnSubmit.html('Guardar');
      toastr["error"]('Error al tratar de guardar los datos');
    });

    return false;
  });

  $( '#sap-test-connection' ).click(function() {
    const url = "<?php echo site_url('admin/sap_connections/test'); ?>";
    const data = $( '#form-sap-connection-save' ).serialize();

    const btnTest = $(this);
    btnTest.prop('disabled', true);
    btnTest.html('Probando...');

    $.post(url, data, function(res) {

      btnTest.prop('disabled', false);
      btnTest.html('Probar conexión');

      if (!res.status) {
        toastr["error"](res.message);
        return;
      }

      toastr["success"](res.message);

    }, 'json')
    .fail(function(e){
      btnTest.prop('disabled', false);
      btnTest.html('Probar conexión');
      toastr["error"]('Error al tratar de hacer el test');
    });
  });
});
</script>