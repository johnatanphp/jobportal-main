<style>
</style>

<div id="modal-screening-batches-list" class="modal modal-style-1 in" role="dialog">
  <div class="modal-dialog" style="width: 98%;max-width: 1600px;">
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
  $(document).on('click', 'a[data-target="#modal-screening-batches-list"]', function(e) {
    e.preventDefault();

    $( '#modal-screening-batches-list .modal-content' ).html(`
      <div class="modal-header">
        <h4 class="modal-title">Espere un momento...</h4>
      </div>
    `);
    $( '#modal-screening-batches-list' ).modal('show');

    const url = "<?php echo site_url('employer/recruitment_tray/screening_batches/list'); ?>";    
    const data = {
      client_code: "<?php echo $client->code; ?>"
    };

    $.get(url, data, function(res){
      $( '#modal-screening-batches-list .modal-content' ).html(res);
    });
    return false;
  });
});
</script>