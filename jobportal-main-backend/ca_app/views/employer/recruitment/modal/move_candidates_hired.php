<div id="modal-move-candidate-hired" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content"></div>
  </div>
</div>
<script>
$(function(){
  $(document).on('click', '.move-candidates-hired', function() {    
    const candidatesSelected = $( "input[name='candidate_ids[]']:checked" ).length;
    
    if (candidatesSelected == 0) {
      return;
    }

    const maxSendCandidates = 25;

    if (candidatesSelected > maxSendCandidates) {
      toastr["warning"](`¡La cantidad máxima de envio a contratación es de ${maxSendCandidates} candidatos, si necesita más envie de ${maxSendCandidates} en ${maxSendCandidates}!`);                
      return;
    }

    const url = "<?php echo site_url('employer/recruitment_candidates/move_candidates_hired'); ?>";
    const data = {
      'process_id': $( '#global_process_id' ).val()
    };

    $( "#modal-move-candidate-hired .modal-content" ).html(`
      <div class="modal-header">
        <h4 class="modal-title">
          Cargando...
        </h4>
      </div>
    `);
    $( '#modal-move-candidate-hired' ).modal('show');

    $.get(url, data, function(content) {
      $( "#modal-move-candidate-hired .modal-content" ).html(content);
    })
    .fail(function (){
      toastr["error"]('¡Ha ocurrido un error al solicitar los recursos!');
    });
  });
});
</script>