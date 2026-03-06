<div id="modal-short-list-send-by-email" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 450px;">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enviar Terna o Short list</h4>
      </div>
      <div class="modal-body">
        <button style="margin-bottom: 10px;" class="btn btn-primary btn-xs pull-right add-recipient">Agregar destinatario</button>
        <div class="content-recipients">
          <?php echo form_open('employer/recruitment_processes/send_terna_short_list', ['id' => 'form-send-terna-short-list']); ?>
          <input type="hidden" name="process_id" value="">
          <div>
            <table width="100%" class="table tbl-recipients">
              <tr>
                <td>
                    <input type="text" name="emails[]" class="form-control" placeholder="Email destinatario" required>
                </td>
                <td></td>
              </tr>
            </table>
          </div>
          <div style="text-align: center;padding-top: 10px;">
              <button class="btn btn-primary" type="submit">Enviar</button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function(){

  $( '.add-recipient', '#modal-short-list-send-by-email').click(function(){
    var row_recipient = `
      <tr>
        <td>
          <input type="text" name="emails[]" class="form-control" placeholder="Email destinatario" required>
        </td>
        <td>
          <button type="button" class="btn btn-xs" onclick="$(this).closest('tr').remove();">
            <i class="glyphicon glyphicon-remove"></i>
          </button>
        </td>
      </tr>`;
    $( ".tbl-recipients tbody", '#modal-short-list-send-by-email').prepend(row_recipient);
  });

  $( '#form-send-terna-short-list' ).submit(function(e){
    e.preventDefault();
    if (!window.confirm("¿Está seguro de enviar la terna o short list?")) {
      return;
    }

    const btnSubmit = $( 'button[type=submit]', this);
    btnSubmit.prop('disabled', true);
    btnSubmit.html('Enviando...');

    const data = $(this).serialize();
    const url = $(this).prop('action');

    $.post(url, data, function(response) {
      var status = response.success;
    
      if (status) {
        toastr["success"](response.message);
        $( '#modal-short-list-send-by-email' ).modal('hide');
        ($( '#form-send-terna-short-list' )[0]).reset();
      } else {
        toastr["error"](response.message);
      }
    }, 'json')
    .fail(function(){
      toastr["danger"]("¡Ha ocurrido un error!");
    }).always(function(){
      btnSubmit.prop('disabled', false);
      btnSubmit.html('Enviar');
    });

    return false;
  });
});
</script>