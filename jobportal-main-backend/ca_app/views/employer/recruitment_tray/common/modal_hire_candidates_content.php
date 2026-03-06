<style>
  .form-group label span {
    color: red;
  }

  .step-container {
    display: flex;
    margin-bottom: 20px;
    counter-reset: step;
  }

  .step-container div {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #bcbfc3ff;
    margin-right: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  .step-container div::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
    z-index: 9;
  }

  .step-container div.completed::after {
    background-color: green;
  }

  .step-container div.active::after {
    background-color: #0D6EFD;
  }

  .step-container div.future::after {
    background-color: #6C757D;
  }

  .step-container .line {
    height: 2px;
    background-color: #ccc;
    position: relative;
    top: 15px;
    width: 60px;
  }

  .step-container .line.completed {
    background-color: green;
  }

  .step-circle {
    counter-increment: step;
  }

  .step-circle::before {
    color: #fff;
    content: counter(step);
    position: absolute;
    z-index: 10;
  }

  .form-group.fg-error label {
    color: red;
  }

  .form-group.fg-error .fg-error-message {
    color: red;
  }

  .form-group.fg-error .select2-container,
  .form-group.fg-error input,
  .form-group.fg-error select
  {
    border: 1px solid red;
  }
</style>
<?php echo form_open('employer/recruitment_tray/processes/hire_candidates', ['id' => 'form-hire-candidates']); ?>
  <input type="hidden" name="client_code" value="<?php echo $client->code; ?>">

  <?php foreach ($tray_ids as $tray_id): ?>
    <input type="hidden" name="tray_ids[]" value="<?php echo $tray_id; ?>">
  <?php endforeach; ?>

  <?php foreach ($tray_types as $tray_type_id): ?>
    <input type="hidden" name="tray_types[]" value="<?php echo $tray_type_id; ?>">
  <?php endforeach; ?>

  <div class="modal-header" style="border: 0;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"></h4>
  </div>
  <div class="modal-body">

    <?php if (count($errors) > 0): ?>
      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <thead>
              <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Error</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($errors as $error): ?>
                <tr>
                  <td><?php e($error['document_number']); ?></td>
                  <td><?php e($error['first_name']); ?></td>
                  <td style="color: red;"><?php e($error['error']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-12">
        <div style="display: flex;align-content: center;justify-content: center;">
          <div id="step-container-candidate-hire" class="step-container"></div>
        </div>
      </div>
    </div>
    <?php if (count($errors) == 0): ?>
      <div class="row">

        <?php //if (in_array(2, $tray_types)): ?>
          <div class="step hide">
            <div class="step-title hide">
              <div style="padding: 10px 5px;text-align: center;">
                <h4 style="display: block;font-weight: bold;padding-bottom: 5px;">Contratación con solicitud</h4>
                <span style="font-weight: normal;font-size: 15px;">Complete la información de contratación para los candidatos que tienen solicitudes asociadas.</span>
              </div>
            </div>

            <div class="col-md-12">
              <div>
                <table class="table" style="margin-bottom: 0;">
                  <tr>
                    <td>
                      <div class="form-group">
                        <label>Contrato desde <span>*</span></label>
                        <input type="date" 
                                name="request_contract_start_date" 
                                value="" 
                                class="form-control" 
                                min="<?php echo date('Y-m-d'); ?>"
                                required/>
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <label>Contrato hasta <span>*</span></label>
                        <input type="date" 
                                name="request_contract_end_date" 
                                value="" 
                                class="form-control" 
                                min="<?php echo date('Y-m-d'); ?>"
                                required/>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        <?php //endif; ?>

        <div class="step hide">
          <div class="step-title hide">
            <div style="padding: 10px 5px;text-align: center;">
              <h4 style="display: block;font-weight: bold;padding-bottom: 5px;">Confirme la contratación</h4>
            </div>
          </div>
          <div class="col-md-12">
            <span style="font-size: 16px;display:block;padding: 35px 15px;">
              Se enviarán los datos al sistema de nómina. Para confirmar la contratación, haga clic en 'Contratar'.
            </span>
          </div>
        </div>          
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-style-1 step-cancel hide" data-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-style-1 step-back hide">Atrás</button>
      <button type="button" class="btn btn-primary btn-style-1 step-next hide">Siguiente</button>
      <button type="submit" class="btn btn-primary btn-style-1 step-finish hide">Contratar</button>
    </div>
  <?php endif; ?>
<?php echo form_close(); ?>

<script>
$(function(){

  function getJobLayouts() {
    const consultantCode = $( 'select[name="consultant_code"]', '#form-hire-candidates').val();
    const clientCode = "<?php echo $client->code; ?>"
    const data  = {
      consultant_code: consultantCode,
      client_code: clientCode,
    }

    const url = "<?php echo site_url('employer/recruitment_tray/processes/get_job_layouts'); ?>";
    const selectLayouts = $( 'select[name="job_layout_id"]', '#form-hire-candidates' );
    selectLayouts.html('<option value="">Cargando...</option>').prop('disabled', true); 
    
    $.post(url, data, function(res) {

      const jobLayouts = res.data || [];
      
      $.each(jobLayouts, function(i, row) {
        selectLayouts.append(`
          <option value="${row.id}" data-code-integration="${row.code_integration}">
            ${row.code ? row.code + ' - ' + row.job_title : row.job_title}
          </option>
        `);
      });
    }, 'json')
    .fail(function() {
      toastr["error"]('¡Ha ocurrido un error al tratar de listar los layouts de puestos!');
    }).always(function() {
      selectLayouts.find("option:eq(0)").text("Seleccione");
      selectLayouts.prop('disabled', false);
      selectLayouts.select2();
    });  
  }

  function createStep() {
    window.stepIndex = -1;
    const modalParent = document.getElementById('modal-hire-candidates');
    
    let steps = modalParent.getElementsByClassName("step");
    let stepContainer = document.getElementById("step-container-candidate-hire");
    
    for (let i = 0; i < steps.length; i++) {
      let circle = document.createElement("div");
      circle.className = "step-circle";
      stepContainer.appendChild(circle);

      if (i < steps.length - 1) {
        let line = document.createElement("div");
        line.className = "line";
        stepContainer.appendChild(line);
      }
    }
  }

  function validate(stepIndex) {
    const elements = $( '.step:eq(' + (stepIndex - 1)+ ')', '#modal-hire-candidates').find('input, select, textarea').toArray();

    const errors = [];
    for (inputIndex in elements) {
      let input = elements[inputIndex];
      if ($.trim($(input).val()) == '') {
        errors.push([input, 'Debe seleccionar una opción.']);
      }
    }

    if (errors.length > 0) {
      createErrors(errors);
    }

    return errors.length == 0;
  }

  function createErrors(errors) {

    $( '.fg-error-message' ).remove();
    $( '.fg-error' ).removeClass('fg-error');

    for (errorIndex in errors) {
      const inputError = errors[errorIndex];
      
      const inputElement = inputError[0];
      const message = inputError[1];

      $(inputElement).closest('.form-group').append(`<div class="fg-error-message">${message}</div>`);
      $(inputElement).closest('.form-group').addClass('fg-error');
    }
  }

  function showStep(stepIndex) {

    if (stepIndex > 1 && !validate(stepIndex - 1)) {
      return;
    }
   
    $( '.step-cancel', '#modal-hire-candidates').addClass('hide');
    $( '.step-next', '#modal-hire-candidates').addClass('hide');
    $( '.step-back', '#modal-hire-candidates').addClass('hide');
    $( '.step-finish', '#modal-hire-candidates').addClass('hide');
    
    $( '.step', '#modal-hire-candidates').addClass('hide');

    $('.step-circle', '#modal-hire-candidates').removeClass('active');
    $( '.step:eq(' + (stepIndex - 1)+ ')', '#modal-hire-candidates').removeClass('hide');
    const stepTitleHtml = $( '.step:eq(' + (stepIndex - 1) + ')', '#modal-hire-candidates').find('.step-title').html();
    $( '#modal-hire-candidates .modal-title' ).html(stepTitleHtml);

    const stepLength = $( '.step', '#modal-hire-candidates').length;

    if (stepIndex == 1) {
      $( '.step-next', '#modal-hire-candidates').removeClass('hide');
      $( '.step-cancel', '#modal-hire-candidates').removeClass('hide');
    }

    if (stepIndex > 1 && stepIndex < stepLength) {
      $( '.step-next', '#modal-hire-candidates').removeClass('hide');
      $( '.step-back' ).removeClass('hide');
    }

    if (stepIndex == stepLength) {
      $( '.step-finish', '#modal-hire-candidates').removeClass('hide');
      $( '.step-back', '#modal-hire-candidates').removeClass('hide');
    }

    window.stepIndex = stepIndex;

    $( '.step-circle', '#modal-hire-candidates').each(function(i, e){
      if ((i + 1) <= window.stepIndex) {
        $(this).addClass('active');
      }
    });
  }

  function init() {

    createStep();

    $( '.step-back', '#modal-hire-candidates').click(function(e) {
      showStep(window.stepIndex - 1);
    });

    $( '.step-next', '#modal-hire-candidates').click(function(e) {
      showStep(window.stepIndex + 1);
    });

    $( 'input, select, textarea', '.step .form-group ' ).change(function(e){
      $(this).closest('.form-group').find('.fg-error-message').remove();
      $(this).closest('.form-group').removeClass('fg-error');
    });

    $( '#form-hire-candidates' ).submit(function(e) {
      e.preventDefault();

      const params = $(this).serialize();
      const url = $(this).prop('action');

      $( '#modal-hire-candidates .modal-content' ).addClass('load load-image');
  
      $.post(url, params, function(res) {

        if (!res.status) {
          toastr["warning"](res.message);
          return false;
        }

        reloadSearchCandidates();
        toastr["success"](res.message);
        $( '#modal-hire-candidates' ).modal('hide');
      }, 'json')
      .fail(function(e){
        toastr["error"]('Ha ocurrido un error');
      })
      .always(function(){
        $( '#modal-hire-candidates .modal-content' ).removeClass('load load-image');
      });
      return false;
    });

    $( 'input[name=contract_start_date]', '#form-hire-candidates' ).change(function(){
      $( 'input[name=contract_end_date]', '#form-hire-candidates').val('');
      $( 'input[name=contract_end_date]', '#form-hire-candidates').prop('min', $(this).val());
    }); 

    $( 'select[name="consultant_code"]', '#form-hire-candidates' ).change(function(){
      getJobLayouts();
    });

    $( 'select[name="consultant_code"]', '#form-hire-candidates' ).select2();
    $( 'select[name="job_layout_id"]', '#form-hire-candidates' ).select2();

    showStep(1);
  }

  init();
});

</script>