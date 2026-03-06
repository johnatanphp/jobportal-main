<style>
  #modal-tray-candidates-status .form-group {
    margin-bottom: 5px;
  }
</style>
<div id="modal-tray-candidates-status" class="modal modal-style-1 in" role="dialog">
  <div class="modal-dialog" style="width: 98%;max-width: 600px;">
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

  $(document).on('click', 'a[data-target="#modal-tray-candidates-status"]', function(e) {
    e.preventDefault();
    
    const trayCandidateId = $(this).data('tray-candidate-id');

    $( '#modal-tray-candidates-status .modal-content' ).html(`
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
    `);
    $( '#modal-tray-candidates-status' ).modal('show');
    const url = "<?php echo site_url('employer/recruitment_tray/process_candidates/modal_candidate_status'); ?>";
    const data = {
      tray_id: trayCandidateId
    };
    $.post(url, data, function(res){
      $( '#modal-tray-candidates-status .modal-content' ).html(res);
    });
    return false;
  });

});
</script>