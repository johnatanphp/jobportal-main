<div id="modal-save-rrhh-assignment" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          Asignación Gestor de Nómina
        </h4>
      </div>
    </div>
  </div>
</div>
<script>
$(function(){

  $( '.btn-save-rrhh-assignment' ).click(function() {    
    const url = "<?php echo site_url('employer/recruitment_candidates/save_rrhh_assignment'); ?>";
    const data = {
      'process_id': $( '#global_process_id' ).val()
    };

    $( "#modal-save-rrhh-assignment .modal-content" ).html(`
      <div class="modal-header">
        <h4 class="modal-title">
          Cargando...
        </h4>
      </div>
    `);
    $( '#modal-save-rrhh-assignment' ).modal('show');

    $.get(url, data, function(content) {
      $( "#modal-save-rrhh-assignment .modal-content" ).html(content);
    })
    .fail(function (){
      toastr["error"]('¡Ha ocurrido un error al solicitar los recursos!');
    });
  });

});
</script>