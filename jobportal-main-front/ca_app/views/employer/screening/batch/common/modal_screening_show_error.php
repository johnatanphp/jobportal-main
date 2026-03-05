<div id="modal-batch-screening-error" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content"></div>
  </div>
</div>

<script type="module">
$(function(){
  $(document).on('click', '.btn-show-error', function(){
    const url = "<?php echo site_url('employer/screening/batch/detail_error'); ?>";
    const data = {
      'id': $(this).data('id')
    };

    $( '#modal-batch-screening-error .modal-content' ).html(`
      <div class="modal-header">
        <h4 class="modal-title">Espere</h4>
      </div>`
    );
    $( '#modal-batch-screening-error' ).modal('show');

    $.get(url, data, function(res){
      $( '#modal-batch-screening-error .modal-content' ).html(res);

      try {
        $( '#screening-batch-item-error pre' ).html(JSON.stringify(JSON.parse($( '#screening-batch-item-error pre' ).html()), null, 2));
      } catch (e) {} 
    });
  });
});
</script>