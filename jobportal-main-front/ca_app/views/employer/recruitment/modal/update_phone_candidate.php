<div class="modal fade" id="phoneModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="phoneModalLabel">Actualizar Teléfono</h4>
      </div>
      <?php echo form_open('employer/recruitment_processes/update_phone', ['id' => 'form-update-seeker-phone']); ?>
        <div class="modal-body">
          <input name="mobile_phone" type="text" class="form-control" id="candidate_mobile_phone" value="" required/>
          <input name="id" type="hidden" class="form-control" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script type="module">
  $(function(){
   
    var iti_candidate_mobile = window.intlTelInput($( '#candidate_mobile_phone' )[0], {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      i18n: window.intlTelInputI18nEs,
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: 'pe',
      //hiddenInput: () => ({ phone: "full_mobile_phone_number"}),
    });
    
    $( '#candidate_mobile_phone' ).data('iti-instance', iti_candidate_mobile);
    //$( ".iti__search-input" ).attr({'placeholder' : 'Buscar'});
      
  });
</script>