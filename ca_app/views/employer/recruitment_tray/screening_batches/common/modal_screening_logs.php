<style>
</style>

<div id="modal-screening-batches-logs" class="modal modal-style-1 in" role="dialog">
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
  $(document).on('click', '.btn-show-error', function(e) {
    e.preventDefault();

    $( '#modal-screening-batches-logs .modal-content' ).html(`
      <div class="modal-header">
        <h4 class="modal-title">Espere un momento...</h4>
      </div>
    `);
    $( '#modal-screening-batches-logs' ).modal('show');

    const url = "<?php echo site_url('employer/recruitment_tray/screening_batches/logs'); ?>";    
    const batchId = $(this).data('id');
    const data = {
      id: batchId
    };

    $.get(url, data, function(res){
      $( '#modal-screening-batches-logs .modal-content' ).html(res);
    });
    return false;
  });
});
</script>