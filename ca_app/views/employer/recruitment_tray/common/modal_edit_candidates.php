<style>
  #modal-edit-candidates textarea {
    resize: none;
  }

  #modal-esit-candidates .table tr td {
    border: 0;
  }

  #modal-edit-candidates .formwraper {
    box-shadow: none;
    border: 0;
    padding: 20px 15px;
  }
</style>

<div id="modal-edit-candidates" class="modal modal-style-1 in" role="dialog">
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
  $(document).on('click', 'a[data-target="#modal-edit-candidates"]', function(e) {
    e.preventDefault();

    $( '#modal-edit-candidates .modal-content' ).html(`
      <div class="modal-header">
        <h4 class="modal-title">Espere un momento...</h4>
      </div>
    `);
    $( '#modal-edit-candidates' ).modal('show');
    const candidateId = $(this).data('candidate-id');
    const url = "<?php echo site_url('employer/recruitment_tray/candidate_register/modal_edit'); ?>";
    const data = {
      candidate_id: candidateId
    };
    $.post(url, data, function(res){
      $( '#modal-edit-candidates .modal-content' ).html(res);
    });
    return false;
  });
});
</script>
