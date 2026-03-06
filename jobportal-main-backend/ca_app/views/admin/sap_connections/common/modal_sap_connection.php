<div class="modal fade" id="modal-sap-connections">
  <div class="modal-dialog">
    <div class="modal-content"></div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<script>
$(function(){
  $( '.manage-sap-connection' ).click(function() {

    const companyId = $(this).data('company-id');
    const url = "<?php echo site_url('admin/sap_connections/save/'); ?>" + companyId;

    $( '#modal-sap-connections .modal-content' ).html(`
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Cargando...</h4>
      </div>
    `);
    $( '#modal-sap-connections' ).modal('show');

    $.get(url, {}, function (resContent) {
      $( '#modal-sap-connections .modal-content' ).html(resContent);
    });
  });
});
</script>