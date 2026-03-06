<?php
  $is_edit = !empty($interview->interview); 
?>
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Programar entrevista</h4>
    </div>
    <form id="form-shedule-interview">
      <div class="modal-body">
      	<div>
          <input id="candidate_id" type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>">
          <input id="job_id" type="hidden" name="job_id" value="<?php echo $job_id; ?>">
          <label style="padding: 10px 0;">Mensaje para el candidato</label>
          <button id="edit-msg-interview" type="button" class="btn btn-primary" style="float: right; <?php echo !$is_edit ? 'display: none;' : ''; ?>">Editar</button>
          <textarea id="msg-interview" class="form-control" name="msg_interview" rows="10" <?php echo $is_edit ? 'readonly="true"' : ''; ?>><?php echo $is_edit ? $interview->interview : ''; ?></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btn-save-interview" type="submit" class="btn btn-primary" style="<?php echo $is_edit ? 'display: none;' : ''; ?>">
          Programar entrevista
        </button>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">

  $( "#form-shedule-interview" ).submit(function(){
    schuduleInterviewCandidate();
    return false;
  });

  function schuduleInterviewCandidate() {
    var data =  $( "#form-shedule-interview" ).serialize();
    var url = "<?php echo base_url('employer/recruitment_short_list/candidates/schedule_interview'); ?>";
    
    $.post(url, data, function(response) {
      var status = response.success;
      if (status) {
        toastr["success"]("¡Se ha programado la entrevista!");
        $( "#msg-interview" ).attr('readonly', true);
        $( "#btn-save-interview" ).hide();
        $( "#edit-msg-interview" ).show();
      } else {
        toastr["error"]("¡Ocurrio un error al programar la entrevista!");
      }
    }, 'json')
    .fail(function(){
      alert("¡Ha ocurrido un error!");
    });
  }
  
  $( "#edit-msg-interview" ).click(function() {
    $( "#msg-interview" ).attr('readonly', false);
    $( "#btn-save-interview" ).show();
    $( "#edit-msg-interview" ).hide();
  });
</script>
