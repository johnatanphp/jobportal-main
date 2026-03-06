<style>
  #modal-sent-candidates textarea {
    resize: none;
  }

  .container-select-request {
    position: relative;
    display: block;
    width: 100%;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .selector-change { 
    position: absolute;
    top: 5px;
    right: 8px;
    font-size: 12px;
  }

  .selector-request {
    padding: 8px 5px;
  }
  
  .selector-request span {
    display: block;
    line-height: 1.5;
  }
</style>

<div id="modal-sent-candidates" class="modal modal-style-1 in" role="dialog">
  <div class="modal-dialog" style="width: 98%;max-width: 850px;">
    <!-- Modal content-->
    <div class="modal-content">
      <?php echo form_open('employer/recruitment_tray/recruitment_send_candidates/sent_candidates', ['id' => 'form-sent-candidates']); ?>
        <div class="modal-header">
          <h4 class="modal-title">Enviar a contratación</h4>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-style-1" data-dismiss="modal">Cerrar</button>
          <button id="btn-sent-candidates" type="submit" class="btn btn-primary btn-style-1">Enviar</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script type="module">
$(function(){ 

  $(document).on('click', 'a[data-target="#modal-sent-candidates"]', function(e) {
    e.preventDefault();
    
    const rowsIds = tableTrayCandidates.getSelected(0);

    if (rowsIds.length == 0) {
      toastr["warning"]('Por favor debe seleccionar al menos 1 candidato');
      return;
    }

    $( '#modal-sent-candidates .modal-title' ).html('Espere un momento');
    $( '#modal-sent-candidates .modal-body' ).hide();
    $( '#modal-sent-candidates .modal-footer' ).hide();
    $( '#modal-sent-candidates' ).modal('show');

    const url = "<?php echo site_url('employer/recruitment_tray/recruitment_send_candidates/modal_sent_candidates'); ?>";
    const data = {
      client_code: "<?php echo $client->code; ?>",
      tray_ids: rowsIds
    };
    $.post(url, data, function(res){
      $( '#modal-sent-candidates .modal-body' ).html(res).show();
      $( '#modal-sent-candidates .modal-footer' ).show();
    })
    .fail(function(e){
      toastr["error"]('Ha ocurrido un error');
    })
    .always(function(){
      $( '#modal-sent-candidates .modal-title' ).html('Enviar a contratación');
    });
    return false;
  });

  $( '#form-sent-candidates' ).submit(function(e){
    e.preventDefault();

    const payrollAdministrators = $('select[name="payroll_administrators[]"]').val();
    const sentNotify = $( 'select[name="sent_notify_to"]', '#form-sent-candidates').val();

    if ((payrollAdministrators || []).length == 0 && sentNotify == '1') {
      toastr["warning"]('No hay administradores de nómina seleccionados');
      return;
    }

    if ((payrollAdministrators || []).length == 0 && sentNotify == '2') {
      toastr["warning"]('No hay administradores de nómina registrados para este cliente');
      return;
    }

    const params = $(this).serialize();
    const url = $(this).prop('action');

    const btnSubmit = $( 'button[type="submit"]', this);
    btnSubmit.text('Enviando...');
    $( '#modal-sent-candidates .modal-content' ).addClass('load load-image');

    $.post(url, params, function(res) {

      if (!res.status) {
        toastr["warning"](res.message);
        return false;
      }

      reloadSearchCandidates();
      toastr["success"](res.message);
      $( '#modal-sent-candidates' ).modal('hide');
    }, 'json')
    .fail(function(e){
      toastr["error"]('Ha ocurrido un error');
    })
    .always(function(){
      btnSubmit.text('Enviar');
      $( '#modal-sent-candidates .modal-content' ).removeClass('load load-image');
    });
    return false;
  });
});
</script>
