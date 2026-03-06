<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Comentarios sobre la entrevista</h4>
    </div>
    <?php echo form_open('', array('id' => 'form-interview-comments')); ?>
      <div class="modal-body">
      	<div>
        	<label>Comentarios</label>
          <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
          <input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>">

          <textarea id="interview-comments" class="form-control" name="interview_comments" rows="5"><?php echo $interview->interview_comments; ?></textarea>
     	</div>
      </div>
      <div class="modal-footer">
        <button id="save-interview-comments" type="button" class="btn btn-primary">Guardar</button>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    
    function saveInterviewComments() {

      var data = $( "#form-interview-comments" ).serialize();

      $( "#form-interview-comments :input" ).prop('disabled', true);

      var url = "<?php echo site_url('employer/recruitment_interviews/save_comments'); ?>";
      $.post(url, data, function(response) {
        var status = response.success;
        if (status) {
          toastr["success"]("¡El comentario fue guardado con éxito!");
          $( ".modal" ).modal('hide');
        } else {
          toastr["error"]("¡Ocurrio un error al guardar el comentario!");
        }
      }, 'json')
      .fail(function(){
        alert("¡Ha ocurrido un error!");
      }).always(function(){
        $( "#form-interview-comments :input" ).prop('disabled', false);      
      });
    }

    $( "#save-interview-comments" ).click(function() {
        saveInterviewComments();  
    });
  })
</script>
