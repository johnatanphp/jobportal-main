<style>
  #modal-screening-batches-create .formwraper {
    box-shadow: none;
    border: 0;
    padding: 20px 15px;
  }

  .btn-screening-batches-list {
    color: #0d6efd;
    font-size: 16px;
    font-family: 'Inter', sans-serif;
    border: 1.5px solid #0d6efd;
    border-radius: 6px;
    display: block;
    cursor: pointer;
    background: white;
    padding: 0.8em 2em;
  }
</style>

<div id="modal-screening-batches-create" class="modal modal-style-1 in" role="dialog">
  <div class="modal-dialog" style="width: 98%;max-width: 800px;">
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
  $(document).on('click', '#btn-screening-batch-create', function(e) {
    e.preventDefault();

    const rowsIds = tableTrayCandidates.getSelected(0);

    if (rowsIds.length == 0) {
      toastr["warning"]('Por favor debe seleccionar al menos 1 candidato');
      return;
    }

    $( '#modal-screening-batches-create .modal-content' ).html(`
      <div class="modal-header">
        <h4 class="modal-title">Espere un momento...</h4>
      </div>
    `);
    $( '#modal-screening-batches-create' ).modal('show');

    const url = "<?php echo site_url('employer/recruitment_tray/screening_batches/form_create'); ?>";    
    const data = {
      client_code: "<?php echo $client->code; ?>",
      tray_ids: rowsIds
    };

    $.get(url, data, function(res){
      $( '#modal-screening-batches-create .modal-content' ).html(res);
    });
    return false;
  });
});
</script>