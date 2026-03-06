<style>
  #modal-hire-candidates textarea {
    resize: none;
  }

  #modal-hire-candidates .table tr td {
    border: 0;
  }

  #modal-hire-candidates .modal-body {
    padding-top: 0;
  }
</style>

<div id="modal-hire-candidates" class="modal modal-style-1 in" data-backdrop="static" role="dialog">
  <div class="modal-dialog" style="width: 98%;max-width: 550px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Espere un momento...</h4>
      </div>
    </div>
  </div>
</div>
<script type="module">
$(function(){ 
  $(document).on('click', '#btn-hire-candidates', function(e) {
    e.preventDefault();
    
    const rowsIds = tableTrayCandidates.getSelected(0);

    if (rowsIds.length == 0) {
      toastr["warning"]('Por favor debe seleccionar al menos 1 candidato');
      return;
    }

    $( '#modal-hire-candidates .modal-content' ).html(`
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
    `);
    $( '#modal-hire-candidates' ).modal('show');
    const url = "<?php echo site_url('employer/recruitment_tray/processes/modal_hire_candidates'); ?>";
    const data = {
      client_code: "<?php echo $client->code; ?>",
      tray_ids: rowsIds
    };
    $.post(url, data, function(res){
      $( '#modal-hire-candidates .modal-content' ).html(res);
    });
    return false;
  });
});
</script>
