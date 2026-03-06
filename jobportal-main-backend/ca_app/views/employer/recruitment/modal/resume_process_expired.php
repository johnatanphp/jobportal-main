<div id="modal-resume-process-expired" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 350px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reanudar proceso</h4>
      </div>
    </div>
  </div>
</div>
<script>
$(function(){
  $( '.btn-resume-process-expired' ).click(function(){
    $( '#modal-resume-process-expired' ).modal('show');

    const url = "<?php echo site_url('employer/recruitment_processes/resume_process_expired'); ?>";

    $( '#modal-resume-process-expired .modal-content' ).html('<div class="modal-header">Cargando...</div>');
    $( '#modal-resume-process-expired' ).modal('show');

    const data = {
      'job_id': $(this).data('job-id')
    }

    $.get(url, data, function(response) {
      $( '#modal-resume-process-expired .modal-content' ).html(response);
    });
  });
});  
</script>