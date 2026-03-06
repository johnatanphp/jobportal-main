<?php
  $is_edit = $candidate_interview ? true : false;
  $date = $candidate_interview ? format_date($candidate_interview->date, 'd/m/Y') : '';
  $hour = $candidate_interview ? format_date($candidate_interview->hour, 'h:i') : ''; 
  $code_hour = $candidate_interview ? format_date($candidate_interview->hour, 'a') : ''; 
?>
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Programar entrevista al candidato</h4>
    </div>
    <div class="modal-body">
      <?php if ($candidate_interview): ?>
        <div class="msg-info">
          ¡Entrevista ya está programada!
          <button id="btn-send-notification-interview" 
                  class="btn btn-primary btn-xs pull-right" 
                  data-job-id="<?php echo $job_id; ?>"
                  data-candidate-id="<?php echo $candidate_id; ?>">
            Volver avisar al candidato
          </button>
        </div>
      <?php endif; ?>
    
      <form id="form-schedule-inerview" method="POST">
        <div class="row">
          <div class="col-md-12">
            <div class="formwraper">
              <input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>">
              <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
              <div class="formint">
                
                <?php if ($staff_recruiter): ?>
                  <div class="input-group">
                    <label class="input-group-addon">Responsable <span></span></label>
                    <label><?php echo $staff_recruiter->first_name; ?></label>
                  </div>
                <?php endif; ?>
                
                <div class="input-group">
                  <label class="input-group-addon">Fecha <span>*</span></label>
                  <input name="date" type="text" class="form-control datepicker" value="<?php echo $date; ?>" placeholder="Fecha"/>
                </div>
                <div class="input-group">
                  <label class="input-group-addon">Hora <span>*</span></label>
                  <table>
                    <tr>
                      <td width="10%">
                        <input style="text-align: center;" name="hour" type="text" class="form-control" data-timepicker value="<?php echo $hour; ?>" placeholder="hh:mm"/>
                      </td>
                      <td width="10%">
                        <select class="form-control" name="code_hour">
                          <option value="am" <?php echo $code_hour =='am' ? 'selected' : ''; ?>>AM</option>
                          <option value="pm" <?php echo $code_hour =='pm' ? 'selected' : ''; ?>>PM</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="input-group">
                  <label class="input-group-addon">Lugar <span>*</span></label>
                  <textarea name="place" class="form-control"><?php echo @$candidate_interview->place; ?></textarea>
                </div>
                <div class="input-group">
                  <label class="input-group-addon">Comentario<span></span></label>
                  <textarea name="more_details" class="form-control"><?php echo @$candidate_interview->more_details; ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button id="btn-schedule-inerview" type="button" class="btn btn-primary pull-right">
              Programar entrevista
            </button>  
          </div>
        </div>
      </form>  
    </div>
  </div>
</div>
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

  function sendNotificationInterview() {

    var btnSend = $( "#btn-send-notification-interview" ); 
    var url = "<?php echo site_url('employer/recruitment_short_list/candidates/send_notification_interview'); ?>";
    var data = {
      job_id: btnSend.data('job-id'),
      candidate_id: btnSend.data('candidate-id')
    };

    btnSend.prop('disabled', true);
    
    $.post(url, data, function(response) {
      var status = response.success;
      if (status) {
        toastr["success"]("¡Se ha enviado la notificación al candidato!");
      } else {
        toastr["error"]("¡Error al enviar la notificación!");
      }
    }, 'json')
    .fail(function(){
      alert("¡Ha ocurrido un error!");
    }).always(function(){
      btnSend.prop('disabled', false);      
    });
  }
  
  function scheduleInterview() {

    var data = $( "#form-schedule-inerview" ).serialize();

    $( "#form-schedule-inerview :input" ).prop('disabled', true);

    var url = "<?php echo site_url('employer/recruitment_short_list/candidates/schedule_interview'); ?>";
    $.post(url, data, function(response) {
      var status = response.success;
      if (status) {
        toastr["success"]("¡La entrevista ha sido programada con éxito!");
        $( ".modal" ).modal('hide');
      } else {
        toastr["error"]("¡Ocurrio un error al programar la entrevista del candidato!");
      }
    }, 'json')
    .fail(function(){
      alert("¡Ha ocurrido un error!");
    }).always(function(){
      $( "#form-schedule-inerview :input" ).prop('disabled', false);      
    });
  }

  $( "#btn-schedule-inerview" ).click(function() {
    if (confirm('¿Está seguro de programar la entrevista con el candidato?')) {
      scheduleInterview();  
    }
  });

  $( "#btn-send-notification-interview" ).click(function(){
    sendNotificationInterview();
  });

</script>
