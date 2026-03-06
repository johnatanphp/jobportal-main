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

</style>
<?php echo form_open('employer/recruitment_tray/screening_batches/create', ['id' => 'form-screening-batches-create']); ?>
  <input type="hidden" name="client_code" value="<?php echo $client->code; ?>">
  <div class="modal-header" style="border: 0;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"></h4>
  </div>
  <div class="modal-body">

    <div class="row">
      <div class="col-md-12">
        <div style="display: flex;align-content: center;justify-content: center;">
          <div id="step-container-screening-batch-create" class="step-container"></div>
        </div>
      </div>
    </div>
      <div class="row">
        <div class="step">
          <div class="step-title hide">
            <div style="padding: 10px 5px;text-align: center;">
              <h4 style="display: block;font-weight: bold;padding-bottom: 5px;">Screening para candidatos seleccionados</h4>
              <span style="font-weight: normal;font-size: 15px;">Se listan los postulantes a los cuales se le creará screening masivo.</span>
            </div>
          </div>
          <div class="col-md-12" style="background: #f4f4f4;">
            <div style="background: #f4f4f4;padding: 10px;">
                <?php foreach ($candidates as $candidate): ?>
                <div class="list-candidates-item">
                  <table width="100%">
                    <tbody>
                      <tr>
                        <td width="150"><?php echo $candidate->document_type . ' ' . $candidate->document_number; ?></td>
                        <td>
                          <?php echo $candidate->first_name . ' ' . $candidate->last_name; ?>
                          <span style="display: block; color: #555;font-weight: normal;font-size: 13px;"><?php echo $candidate->email; ?></span>
                          <?php if ($candidate->total_screening_batch_in_progress > 0): ?>
                            <span style="display: block; color:red;font-weight: normal;font-size: 13px;">
                              No puede generar otra solicitud. <br> Candidato tiene una solicitud de screening en cola.
                            </span>
                          <?php endif; ?>
                        </td>                
                        <td width="20">
                          <?php if ($candidate->total_screening_batch_in_progress == 0): ?>
                            <input type="checkbox" name="tray_id[]" value="<?php echo $candidate->tray_id; ?>" checked class="candidates-selected">
                          <?php endif; ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <?php endforeach; ?>
            </div>

    
          </div>
        </div>
  
        <div class="step hide">
          <div class="step-title hide">
            <div style="padding: 10px 5px;text-align: center;">
              <h4 style="display: block;font-weight: bold;padding-bottom: 5px;">Datos del screening</h4>
              <span style="font-weight: normal;font-size: 15px;">Por favor ingrese los datos para completar la solicitud de creacion de Screening.</span>
            </div>
          </div>
          <div class="col-md-12">

            <div class="formwraper">
              <div class="input-group">
                <label class="input-group-addon"> Tipo <span>*</span></label>
                <select name="type" class="form-control" required>
                  <option value="">Seleccione</option>
                  <option value="1">Básico</option>
                  <option value="2">Integral</option>
                </select>
              </div>   

              <div class="input-group">
                <label class="input-group-addon"> Puesto <span>*</span></label>
                <input name="job_title" type="text" class="form-control" placeholder="" value="" maxlength="30" required>
              </div> 

              <div class="input-group">
                <label class="input-group-addon"> Tipo de egreso <span>*</span></label>
                <select class="form-control"
                        name="type_expense"
                        required
                        >
                    <option value="">Seleccione</option>
                    <option value="EI">EI</option>
                    <option value="EONF">EONF</option>
                    <option value="EOF">EOF</option>
                    <option value="EOFDP">EOFDP</option>
                    <option value="EODT">EODT</option>
                </select>
              </div> 

              <div class="input-group">
                <label class="input-group-addon"> Centro de costo <span>*</span></label>
                <select name="cost_center" 
                        class="form-control" 
                        required
                        style="width:100%">
                  <option value="">Seleccione</option>

                  <?php foreach ($cost_centers as $row): ?>
                    <option value="<?php echo $row->COD_CCOSTO; ?>">
                      <?php echo $row->COD_CCOSTO; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div> 

              <div class="input-group">
                <label class="input-group-addon"> Estructura de costo (código) <span></span></label>
                <input type="text" name="eecc_code" class="form-control" maxlength="20">
              </div>

              <div class="input-group">
                <label class="input-group-addon">Centro de costo cliente <span></span></label>
                <input type="text" name="cost_center_client" class="form-control" maxlength="50">
              </div>

            </div>

          </div>
        </div>  
        
        <div class="step hide">
          <div class="step-title hide">
            <div style="padding: 10px 5px;text-align: center;">
              <h4 style="display: block;font-weight: bold;padding-bottom: 5px;">Confirmar creación del screening</h4>
            </div>
          </div>
          <div class="col-md-12">
             <span style="font-size: 16px;display:block;padding: 35px 50px;line-height: 1.5;">
              Se procesaran los datos ingresados para generar los screnning de los postulantes. Para confirmar la creación, haga clic en 'Crear lote'.
            </span>
          </div>
        </div>  

      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-style-1 step-cancel hide" data-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-style-1 step-back hide">Atrás</button>
      <button type="button" class="btn btn-primary btn-style-1 step-next hide">Siguiente</button>
      <button type="submit" class="btn btn-primary btn-style-1 step-finish hide">Crear lote</button>
    </div>
<?php echo form_close(); ?>

<script>
$(function() {
  function createStep() {
    window.stepIndex = -1;
  
    const modalParent = document.getElementById('modal-screening-batches-create');
    let steps = modalParent.getElementsByClassName("step");
    let stepContainer = document.getElementById("step-container-screening-batch-create");

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
    const elements = $( '.step:eq(' + (stepIndex - 1)+ ')', '#modal-screening-batches-create').find('input, select, textarea').toArray();

    const errors = [];
    for (inputIndex in elements) {
      let input = elements[inputIndex];

      if ($.trim($(input).val()) == '' && $(input).prop('required') && input.tagName.toLowerCase() == 'input') {
        errors.push([input, 'Este campo es requerido']);
      }

      if ($.trim($(input).val()) == '' && $(input).prop('required') && input.tagName.toLowerCase() == 'select') {
        errors.push([input, 'Debe seleccionar una opción']);
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

      $(inputElement).closest('.input-group').append(`<div class="fg-error-message">${message}</div>`);
      $(inputElement).closest('.input-group').addClass('fg-error');
    }
  }

  function showStep(stepIndex) {

    if (stepIndex > 1 && !validate(stepIndex - 1)) {
      return;
    }
  
    $( '.step-cancel', '#modal-screening-batches-create').addClass('hide');
    $( '.step-next', '#modal-screening-batches-create').addClass('hide');
    $( '.step-back', '#modal-screening-batches-create').addClass('hide');
    $( '.step-finish', '#modal-screening-batches-create').addClass('hide');
    
    $( '.step', '#modal-screening-batches-create').addClass('hide');

    $('.step-circle', '#modal-screening-batches-create').removeClass('active');
    $( '.step:eq(' + (stepIndex - 1)+ ')', '#modal-screening-batches-create').removeClass('hide');
    const stepTitleHtml = $( '.step:eq(' + (stepIndex - 1) + ')', '#modal-screening-batches-create').find('.step-title').html();
    $( '#modal-screening-batches-create .modal-title' ).html(stepTitleHtml);

    const stepLength = $( '.step', '#modal-screening-batches-create').length;

    if (stepIndex == 1) {
      $( '.step-next', '#modal-screening-batches-create').removeClass('hide');
      $( '.step-cancel', '#modal-screening-batches-create').removeClass('hide');
    }

    if (stepIndex > 1 && stepIndex < stepLength) {
      $( '.step-next', '#modal-screening-batches-create').removeClass('hide');
      $( '.step-back', '#modal-screening-batches-create').removeClass('hide');
    }

    if (stepIndex == stepLength) {
      $( '.step-finish', '#modal-screening-batches-create').removeClass('hide');
      $( '.step-back', '#modal-screening-batches-create').removeClass('hide');
    }

    window.stepIndex = stepIndex;

    $( '.step-circle', '#modal-screening-batches-create').each(function(i, e){
      if ((i + 1) <= window.stepIndex) {
        $(this).addClass('active');
      }
    });
  }

  function createMessageSuccess()
  {
    $( '#modal-screening-batches-create .modal-content' ).html(`
      <div class="modal-header" style="border: 0;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 style="display: block;font-weight: bold;padding: 10px 5px;text-align: center;">
          ¡Listo, Lote creado con éxito!
        </h4>
      </div>
      <div class="modal-body">
        <div style="font-size: 16px;padding: 2em 4em;line-height: 1.5;">
          <span style="color: green; font-size: 3em; text-align: center; display:block;">
            <span class="glyphicon glyphicon-ok"></span>
          </span>  
          La generación de screening puede tardar algunos minutos. Puedes revisar la bandeja de lotes de screening para más detalles.
        </div>

        <div style="font-size: 20px;padding: 2em;text-align: center;" >
          <a href="#" class="btn-screening-batches-list">Ir a la bandeja de lotes</a>
        </div>
      </div>
    `);
  }

  function init() {

    createStep();

    $( '.step-back', '#modal-screening-batches-create').click(function(e) {
      showStep(window.stepIndex - 1);
    });

    $( '.step-next', '#modal-screening-batches-create').click(function(e) {
      showStep(window.stepIndex + 1);
    });

    $( 'input, select, textarea', '.step .input-group' ).change(function(e){
      $(this).closest('.input-group').find('.fg-error-message').remove();
      $(this).closest('.input-group').removeClass('fg-error');
    });

    $( '#form-screening-batches-create' ).submit(function(e) {
      e.preventDefault();

      const params = $(this).serialize();
      const url = $(this).prop('action');

      $( '#modal-screening-batches-create .modal-content' ).addClass('load load-image');
  
      $.post(url, params, function(res) {

        if (!res.status) {
          toastr["warning"](res.message);
          return;
        }

        createMessageSuccess();

      }, 'json')
      .fail(function(e){
        toastr["error"]('Ha ocurrido un error');
      })
      .always(function(){
        $( '#modal-screening-batches-create .modal-content' ).removeClass('load load-image');
      });
      return false;
    });

    $(document).on('click', '.btn-screening-batches-list', function(e){
      $( '#modal-screening-batches-create' ).modal('hide');
      $( 'a[data-target="#modal-screening-batches-list"]' ).click();
    });

    $( 'select[name="cost_center"]' ).select2();

    showStep(1);
  }

  init();
});

</script>