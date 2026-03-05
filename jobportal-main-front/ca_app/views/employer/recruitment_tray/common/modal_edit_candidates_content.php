<?php echo form_open_multipart('employer/recruitment_tray/candidate_register/edit', ['id' => 'form-candidate-edit']);?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Editar datos - <?php e($candidate->first_name); ?></h4>
  </div>
  <div class="modal-body">
    <div class="formwraper">
      <input type="hidden" value="<?php echo $candidate->ID; ?>" name="id">
      
      <div class="input-group <?php echo (form_error('mobile'))?'has-error':'';?>">
        <label class="input-group-addon">Teléfono móvil <span>*</span></label>
        <table width="100%">
          <tr>
            <td>
              <input name="mobile" type="text" class="form-control" value="<?php echo $candidate->mobile; ?>" maxlength="16" required/>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-style-1" data-dismiss="modal">Cerrar</button>
    <button id="btn-edit-candidates" type="submit" class="btn btn-primary btn-style-1">Guardar</button>
  </div>
<?php echo form_close(); ?>

<script>
(function(){

  function init() {
    $( '#form-candidate-edit' ).submit(function(e) {
      e.preventDefault();
      const inputMobile = document.querySelector('input[name="mobile"]');
      const dataExtra = {
        'mobile_phone_number': ($(inputMobile).data('intl-instance')).getNumber(intlTelInput.utils.numberFormat.E164)
      };

      const params = $(this).serialize() + '&' + $.param(dataExtra);
      const url = $(this).prop('action');

      $( '#modal-edit-candidates .modal-content' ).addClass('load load-image');

      $.post(url, params, function(res) {

        if (!res.status) {
          toastr["warning"](res.message);
          return false;
        }

        toastr["success"](res.message);
        $( '#modal-edit-candidates' ).modal('hide');
      }, 'json')
      .fail(function(e){
        toastr["error"]('Ha ocurrido un error');
      })
      .always(function(){
        $( '#modal-edit-candidates .modal-content' ).removeClass('load load-image');
      });
      return false;
    });

    const inputMobile = document.querySelector('input[name="mobile"]');
    const intlTelMobileNumber = window.intlTelInput(inputMobile, {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: 'pe',
      hiddenInput: () => ({ phone: "full_mobile_number"}),
    });

    $(inputMobile).data('intl-instance', intlTelMobileNumber);
    $( ".iti__search-input" ).attr({'placeholder' : 'Buscar'});
  }

  init();
})();
</script>