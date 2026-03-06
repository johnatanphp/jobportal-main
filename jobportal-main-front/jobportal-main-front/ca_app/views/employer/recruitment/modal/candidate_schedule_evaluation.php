<?php
  $is_edit = $schedule_evaluation ? true : false;
  $date = @$schedule_evaluation ? format_date($schedule_evaluation->date, 'd/m/Y') : '';
  $hour = @$schedule_evaluation ? format_date($schedule_evaluation->hour, 'h:i') : ''; 
  $code_hour = @$schedule_evaluation ? format_date($schedule_evaluation->hour, 'a') : ''; 
?>
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Programar evaluación al candidato</h4>
    </div>
    <div class="modal-body">
      <?php if ($schedule_evaluation): ?>
        <div class="msg-info">
          ¡Evaluación ya está programada!
          <button id="btn-send-notification-schedule-evaluation" 
                  class="btn btn-primary btn-xs pull-right" 
                  data-job-id="<?php echo $job_id; ?>"
                  data-candidate-id="<?php echo $candidate_id; ?>">
            Volver avisar al candidato
          </button>
        </div>
      <?php endif; ?>
    
      <form id="form-schedule-evaluation" method="POST">
        <div class="row">
          <div class="col-md-12">
            <div class="formwraper">
              <input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>">
              <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
      
              <div class="formint">

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
                  <textarea name="place" class="form-control"><?php echo @$schedule_evaluation->place; ?></textarea>
                </div>
                <div class="input-group">
                  <label class="input-group-addon">Más detalles<span></span></label>
                  <textarea name="more_details" class="form-control"><?php echo @$schedule_evaluation->more_details; ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button id="btn-schedule-evaluation" type="button" class="btn btn-primary pull-right">
              Programar evaluación
            </button>  
          </div>
        </div>
      </form>  
    </div>
  </div>
</div>
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
  
  function sendNotificationScheduleEvaluation() {

    var btnSend = $( "#btn-send-notification-schedule-evaluation" ); 
    var url = "<?php echo base_url('employer/recruitment_evaluations/send_notification_schedule_evaluation'); ?>";
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

  function scheduleEvaluation() {

    var data = $( "#form-schedule-evaluation" ).serialize();

    $( "#form-schedule-evaluation :input" ).prop('disabled', true);

    var url = "<?php echo base_url('employer/recruitment_evaluations/schedule_evaluation'); ?>";
    $.post(url, data, function(response) {
      var status = response.success;
      if (status) {
        toastr["success"]("¡La evaluación ha sido programada con éxito!");
        $( ".modal" ).modal('hide');
      } else {
        toastr["error"]("¡Ocurrio un error al programar la evaluación del candidato!");
      }
    }, 'json')
    .fail(function(){
      alert("¡Ha ocurrido un error!");
    }).always(function(){
      $( "#form-schedule-evaluation :input" ).prop('disabled', false);      
    });
  }

  $( "#btn-schedule-evaluation" ).click(function() {
    if (confirm('¿Está seguro de programar la evaluación?')) {
      scheduleEvaluation();  
    }
  });

  $( "#btn-send-notification-schedule-evaluation" ).click(function(){
    sendNotificationScheduleEvaluation();
  });
</script>
