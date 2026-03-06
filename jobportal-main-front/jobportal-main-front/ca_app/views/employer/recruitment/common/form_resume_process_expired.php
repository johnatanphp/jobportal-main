<?php echo form_open('employer/Recruitment_processes/resume_process_expired', ['id' => 'form-recruitment-resume-process-expired']); ?>
  <div class="modal-header">
    <h4 class="modal-title">Reanudar proceso</h4>
  </div>
  <div class="modal-body">
    <input type="hidden" name="job_id" value="<?php echo $process->job_ID; ?>">
      <div style="padding: 2px 0;">
      <label style="display: block;">Fecha de expiración</label>
        <div style="padding: 6px 0;">Para reanudar el proceso, elija una nueva fecha de expiración.</div>
        <input type="date" name="expiration_date" class="form-control" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
      </div>
  </div>
  <br>
  <br>
  <div class="modal-footer">
    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancelar</button>
    <button class="btn btn-sm btn-primary" type="submit">Reanudar</button>
  </div>
<?php echo form_close(); ?>

<script>
$(function(){

  $( '#form-recruitment-resume-process-expired' ).submit(function(e){
    e.preventDefault();
    
    const url = $(this).prop('action');
    const data = $(this).serialize();
    
    $( '#modal-resume-process-expired .modal-dialog' ).addClass('load load-image');
    $.post(url, data, function(response){
      const status = response.status;

      if (status) {
        toastr["success"](response.message);

        setTimeout(function(){
            window.location.reload();
        }, "1000");
      } else {
        $( '#modal-resume-process-expired .modal-dialog' ).removeClass('load load-image');
        toastr["error"](response.message);
      }
    }, 'json')
    .fail(function(){
      toastr["error"]('¡Un error ha ocurrido!');
      $( '#modal-resume-process-expired .modal-dialog' ).removeClass('load load-image');
    });

    return false;
  });
});
</script>