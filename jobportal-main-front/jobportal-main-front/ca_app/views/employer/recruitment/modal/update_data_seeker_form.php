<div class="modal-content">
  <?php echo form_open('employer/recruitment_processes/update_seeker_data/' . $candidate->ID, ['id' => 'form-update-seeker-data']); ?>
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4 class="modal-title">Actualizar datos</h4>
    </div>
    <div class="modal-body">
      <div class="formwraper">
        <div class="input-group">
          <label class="input-group-addon">Nombre <span>*</span></label>
          <input name="first_name" type="text" class="form-control" placeholder="Nombre" value="<?php echo $candidate->first_name; ?>" maxlength="40" required>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Apellido paterno <span>*</span></label>
          <input name="paternal_last_name" type="text" class="form-control" placeholder="Apellido paterno" value="<?php echo $candidate->paternal_last_name; ?>" maxlength="30" required>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Apellido materno <span>*</span></label>
          <input name="maternal_last_name" type="text" class="form-control" placeholder="Apellido materno" value="<?php echo $candidate->maternal_last_name; ?>" maxlength="30" required>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Sexo <span>*</span></label>
          <select class="form-control" name="gender" required>
            <option value="">Seleccione</option>
            <?php foreach ($genders as $row_gender): ?>
              <option value="<?php echo $row_gender->id; ?>" <?php echo ($candidate->gender == $row_gender->id) ? 'selected' : ''; ?>>
                <?php e($row_gender->name); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Estado civil <span>*</span></label>
          <select class="form-control" name="civil_status" required>
            <option value="">Seleccione</option>

            <?php foreach ($civil_status as $row_civil_status): ?>
              <option value="<?php echo $row_civil_status->id; ?>" <?php echo ($candidate->civil_status == $row_civil_status->id) ? 'selected' : ''; ?>>
                <?php e($row_civil_status->name); ?>
              </option>
            <?php endforeach; ?>   
          </select>
        </div>

      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  <?php echo form_close(); ?>
</div>
<script>
$(function(){

  $( '#form-update-seeker-data' ).submit(function(e){
    e.preventDefault();

    const form = $(this);
    const data = form.serialize();
    const url = form.prop('action');

    $( '#modal-update-data-seeker .modal-content' ).addClass('load load-image');

    $.post(url, data, function(response) {
        const status = response.status;
        if (status) {
          toastr["success"](response.message);
          //$( '#table-rys-search-candidates' ).DataTable().ajax.reload();
          $( '#modal-update-data-seeker' ).modal('hide');
        } else {
          toastr["error"](response.message);
        }
    }, 'json')
    .fail(function(){
      toastr["error"]("¡Ha ocurrido un error!");
    }).always(function(){
      $( '#modal-update-data-seeker .modal-content' ).removeClass('load load-image');
    });
    
    return false;
  });

  $( '#form-update-seeker-data input[name="first_name"]' ).bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
  });
  
  $( '#form-update-seeker-data input[name="paternal_last_name"]').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
  });
  
  $( '#form-update-seeker-data input[name="maternal_last_name"]').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
  });
});
</script>