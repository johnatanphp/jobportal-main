<?php foreach ($tray_ids as $tray_id): ?>
  <input type="hidden" name="tray_ids[]" value="<?php echo $tray_id; ?>">
<?php endforeach; ?>
<input type="hidden" name="client_code" value="<?php echo $client->code; ?>">
<div class="row">
  <div class="col-md-6">
      
    <div class="form-group">
      <label>Solicitud</label>
      <div class="container-select-request">
        <a style="display: block; text-align: center;color: #000;text-decoration: underline;" 
           href="#" 
           class="select-requests">Asignar solicitud</a>
      </div>
    </div>
      
    <div class="form-group">
      <label>Notificar administradores</label>
      <select name="sent_notify_to" class="form-control" style="width: 100%;">
        <option value="2">Todos</option>
        <option value="1">Elegir</option>
      </select>
    </div>
    
    <div class="form-group">
      <label>Administradores de nómina</label>
      <select name="payroll_administrators[]" class="form-control" multiple style="width: 100%;">
        <?php foreach ($payroll_administrators as $row): ?>
          <option value="<?php echo $row->id; ?>"><?php e($row->first_name)?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Comentarios (Opcional)</label>
      <textarea name="comments" class="form-control" rows="8"></textarea>
    </div>
  </div>
</div>

<script type="text/javascript">

(function(){

  function changeNotityTo(e) {

    if (this.value == '1') {
      $('select[name="payroll_administrators[]"] option').prop('selected', false);
      $( 'select[name="payroll_administrators[]"]', '#form-sent-candidates' ).select2({
        closeOnSelect: false
      });
      $( 'select[name="payroll_administrators[]"]', '#form-sent-candidates' ).closest('.form-group').show();
    }

    if (this.value == '2') {
      $('select[name="payroll_administrators[]"] option').prop('selected', true);
      $( 'select[name="payroll_administrators[]"]', '#form-sent-candidates' ).select2({
        closeOnSelect: false
      });
      $( 'select[name="payroll_administrators[]"]', '#form-sent-candidates' ).closest('.form-group').hide();
    }
  }

  function showModalSelectRequests(e) {
    
    const button = e.target;

    if (!button.classList.contains('select-requests')) {
      return;
    }

    const clientCode = "<?php echo $client->code; ?>";
    const url = "<?php echo site_url('employer/recruitment_tray/recruitment_send_candidates/modal_select_staff_requests'); ?>" + '?client_code=' + clientCode;
    const modal = document.querySelector('#modal-hiring-send-select-staff-requests');

    const modalContent = modal.querySelector('.modal-content');

    modalContent.classList.add('load', 'load-image');

    $(modal).modal('show');

    fetch(url)
    .then(response => {
      modalContent.classList.remove('load', 'load-image');
    
      if (!response.ok) {
        throw new Error('Server responded with status: ' + response.status);
      }
      return response.text();
    })
    .then(response => {
      $(modalContent).html(response);
    })
    .catch(error => {
      toastr["error"]('Ha ocurrido un error');        
    });
  }

  function init() {
    document.addEventListener('click', showModalSelectRequests);
    document.querySelector('#form-sent-candidates select[name="sent_notify_to"]').addEventListener('change', changeNotityTo);
    document.querySelector('#form-sent-candidates select[name="sent_notify_to"]').dispatchEvent(new Event('change'));
  }

  init();

})();
</script>