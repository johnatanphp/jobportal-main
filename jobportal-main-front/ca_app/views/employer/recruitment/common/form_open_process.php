<?php echo form_open('employer/Recruitment_processes/open_process', ['id' => 'form-recruitment-open-process']); ?>
  <div class="modal-header">
    <h4 class="modal-title">Abrir proceso</h4>
  </div>
  <div class="modal-body">
    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

    <div>
      <label style="display: block;">Etapas del proceso</label>
      <span>Por favor elige las etapas del proceso, luego podrás añadir o quitar las que consideres.</span>
      <br>
      <br>
    </div>
    <?php foreach ($stages as $stage): ?>

      <div style="padding: 6px 2px;;border-bottom: 1px solid #eeeeee;">
        <div style="display:flex;height: 25px;">
          <label style="margin:0;flex:1;font-weight: normal;"><?php echo $stage->name; ?></label>
          <div class="checkbox-wrapper-s1">
            <div class="round">
              <input type="checkbox" 
                 id="<?php echo $stage->id; ?>"
                 name="stages[]" 
                 class="stages-check"
                 value="<?php echo $stage->id; ?>" 
                 <?php echo $stage->id == 6 || $stage->id == 7 ? 'disabled' : ''; ?>
                 checked>
              <label for="<?php echo $stage->id; ?>"></label>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    
    <?php if ($job->request_ID): ?>
      <br>
      <div style="display: flex; justify-content: space-between;padding: 4px;">
        <label>Activar expiración automatica</label>
        <div class="checkbox-wrapper-s1">
            <div class="round">
              <input id="apply_expiration_date" type="checkbox" name="apply_expiration_date" value="1">
              <label for="apply_expiration_date"></label>
            </div>
          </div>
      </div>
      <div class="container-expiration-date" style="display: none;">
        <div style="padding: 6px 0;">Si se aplica la fecha de expiración, el proceso terminará automaticamente en la fecha seleccionada.</div>

        <input type="date" name="expiration_date" class="form-control" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
      </div>
    <?php endif; ?>

  </div>
  <br>
  <br>
  <div class="modal-footer">
    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancelar</button>
    <button class="btn btn-sm btn-primary" type="submit">Abrir proceso</button>
  </div>
<?php echo form_close(); ?>

<script>
$(function(){

  $( 'input[name="apply_expiration_date"]' ).change(function(){

    if ($(this).is(':checked')) {
      $( '.container-expiration-date' ).show();
      $( 'input[name="expiration_date"]' ).prop('required', true);
    } else {
      $( 'input[name="expiration_date"]' ).removeAttr('required');
      $( '.container-expiration-date' ).hide();
    }
  });

  $( '#form-recruitment-open-process' ).submit(function(e){
    e.preventDefault();
    
    const url = $(this).prop('action');
    const data = $(this).serialize();
    
    $( '#modal-recruitment-confirm-open-process .modal-dialog' ).addClass('load load-image');
    $.post(url, data, function(response){
      const status = response.status;

      if (status) {
        window.location = response.data.process_url;
      } else {
        $( '#modal-recruitment-confirm-open-process .modal-dialog' ).removeClass('load load-image');
        toastr["error"](response.message);
      }
    }, 'json')
    .fail(function(){
      toastr["error"]('¡Un error ha ocurrido!');
      $( '#modal-recruitment-confirm-open-process .modal-dialog' ).removeClass('load load-image');
    });

    return false;
  });
});
</script>