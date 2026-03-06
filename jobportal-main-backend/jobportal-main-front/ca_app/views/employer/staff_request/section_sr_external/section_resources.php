<div id="section-resources">
  <div class="step-title">
    Recursos
  </div>

  <div class="step-inputs">

    <!-- Inicio seccion EMO -->
    <div id="section-resource-emo" class="cont-section-resource">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">EMO</h3>
      <div style="padding-top: 15px;">
        <div class="input-group">
          <label class="input-group-addon">Tipo <span>*</span></label>
          <select name="emo_type" class="form-control js-resource-input" id="type_emo" style="width: 100%;">
            <option value="0">No aplica</option>
            <?php foreach ($exam_emo_types as $row): ?>
              <option value="<?php echo $row->id; ?>">
                <?php echo $row->name; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
              
        <div class="input-group cont-emo-protocol-detail" style="display:none;">
          <label class="input-group-addon">Detallar protocolo <span>*</span></label>
          <input id="protocol-detail" type="text" name="emo_protocol_detail" class="form-control" placeholder="Detallar Protocolo">
        </div>

        <div class="input-group cont-emo-expense-type">
          <label class="input-group-addon">Tipo egreso <span>*</span></label>
          <select class="form-control js-resource-type-expense"
                  name="emo_expense_type"
                  >
              <option value="">Seleccione</option>
              <?php foreach ($expense_types as $row): ?>
                <option value="<?php echo $row->id; ?>"><?php echo $row->id; ?></option>
              <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-emo-staff-charge">
          <label class="input-group-addon">Encargado<span></span></label>
          <input type="text" name="emo_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    
    <!-- Fin seccion EMO -->

    <!-- Inicio seccion Screening -->
    <div id="section-resource-screening" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">SCREENING</h3>

      <div style="padding-top: 15px;">

        <div class="input-group" >
          <label class="input-group-addon">Tipo <span>*</span></label>
          <select name="screening_type" class="form-control js-resource-input" id="type_screening" style="width: 100%;">
    
            <option value="0">No aplica</option>
            <?php foreach ($screening_types as $row): ?>
              <option value="<?php echo $row->id; ?>">
                <?php echo $row->name; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>     
              
        <div class="input-group cont-screening-expense-type">
          <label class="input-group-addon">Tipo Egreso <span>*</span></label> 
          <select class="form-control js-resource-type-expense"
                  name="screening_expense_type">
              <option value="">Seleccione</option>
              <?php foreach ($expense_types as $row): ?>
                <option value="<?php echo $row->id; ?>"><?php echo $row->id; ?></option>
              <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-screening-perform-stage">
          <label class="input-group-addon">Realizar en etapa <span>*</span></label> 
          <select class="form-control js-resource-stage"
                name="screening_perform_stage">
          
            <?php $perform_on_stage = null; ?>

            <option value="">Seleccione</option>
            <?php foreach ($rys_stages as $index => $stage): ?>
              
              <option value="<?php echo $index; ?>" 
                      <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                <?php echo $stage; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-screening-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="screening_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion Screening -->

    <!-- Inicio seccion COVID-19 -->
    <div id="section-resource-covid19" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">EXAMEN COVID-19</h3>
    
      <div style="padding-top: 15px;">
              
        <div class="input-group">
          <label class="input-group-addon">Tipo <span>*</span></label>

          <select name="covid19_type" 
                  class="form-control js-resource-input" 
                  id="exam_type_covid"
                  style="width: 100%;">

            <option value="0">No aplica</option>
            <?php foreach ($exam_covid19_types as $row): ?>
              <option value="<?php echo $row->id; ?>">
                <?php echo $row->name; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-covid19-expense-type">
          <label class="input-group-addon">Tipo Egreso <span>*</span></label> 
          <select class="form-control js-resource-type-expense"
                  name="covid19_expense_type">
              <option value="">Seleccione</option>
              <?php foreach ($expense_types as $row): ?>
                <option value="<?php echo $row->id; ?>"><?php echo $row->id; ?></option>
              <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-covid19-perform-stage">
          <label class="input-group-addon">Realizar en etapa <span>*</span></label> 
          <select class="form-control js-resource-stage"
                name="covid19_perform_stage">
          
            <?php $perform_on_stage = null; ?>

            <option value="">Seleccione</option>
            <?php foreach ($rys_stages as $index => $stage): ?>
              
              <option value="<?php echo $index; ?>" 
                      <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                <?php echo $stage; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-covid19-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="covid19_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion COVID-19 -->

    <!-- Inicio seccion Examenes complementarios -->
    <div id="section-resource-exam-complementary" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">EXAMENES COMPLEMENTARIOS</h3>
    
      <div style="padding-top: 15px;">

        <div class="input-group">
          <label class="input-group-addon">Examen <span>*</span></label> 
          <select id="exams-complementary" 
                  name="exam_complementary_type" 
                  class="form-control js-resource-input"
                  style="width: 100%;">
            <?php 
              $type_exam_complementary_selected = '';
            ?>
            <?php foreach (options_exams_complementary() as $option): ?>
              <option value="<?php echo $option; ?>" <?php echo $option == $type_exam_complementary_selected ? 'selected="selected"' : '';?>>
                <?php echo $option; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-exams-complementary-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="exam_complementary_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion Examenes complementarios -->

    <!-- Inicio seccion Verificación domiciliaria -->
    <div id="section-resource-verify-home" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">VERIFICACION DOMICILIARIA</h3>
    
      <div style="padding-top: 15px;">

        <div class="input-group">
          <label class="input-group-addon">Verificar <span>*</span></label> 
          <select name="verify_home" 
                  class="form-control js-resource-input" 
                  id="home_verification">
            
            <option value="0">
              NO
            </option>
            <option value="1">
              SÍ
            </option>
          </select>
        </div>

        <div class="input-group cont-home-verification-perform-stage">
          <label class="input-group-addon">Realizar en etapa <span>*</span></label> 
          <select class="form-control js-resource-stage"
                name="verify_home_perform_stage">
          
            <?php $perform_on_stage = null; ?>

            <option value="">Seleccione</option>
            <?php foreach ($rys_stages as $index => $stage): ?>
              
              <option value="<?php echo $index; ?>" 
                      <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                <?php echo $stage; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-home-verification-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="verify_home_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion Verificación domiciliaria -->

    <!-- Inicio seccion Verificación crediticia -->
    <div id="section-resource-verify-credit" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">VERIFICACION CREDITICIA</h3>
    
      <div style="padding-top: 15px;">

        <div class="input-group ">
          <label class="input-group-addon">Verificar <span>*</span></label> 
          <select name="verify_credit" 
                  class="form-control js-resource-input" 
                  id="credit_verification">
            
            <option value="0">
              NO
            </option>
            <option value="1">
              SÍ
            </option>
          </select>
        </div>

        <div class="input-group cont-credit-verification-perform-stage">
          <label class="input-group-addon">Realizar en etapa <span></span></label> 
          <select class="form-control js-resource-stage"
                name="verify_credit_perform_stage">
          
            <?php $perform_on_stage = null; ?>

            <option value="">Seleccione</option>
            <?php foreach ($rys_stages as $index => $stage): ?>
              
              <option value="<?php echo $index; ?>" 
                      <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                <?php echo $stage; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-credit-verification-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="verify_credit_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion Verificación crediticia -->

    <!-- Inicio seccion Verificación laboral -->
    <div id="section-resource-verify-labor" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">VERIFICACION LABORAL</h3>
    
      <div style="padding-top: 15px;">

        <div class="input-group">
          <label class="input-group-addon">Verificar <span>*</span></label> 
          <select name="verify_labor" 
                  class="form-control js-resource-input" 
                  id="labor_verification">
            
            <option value="0">
              NO
            </option>
            <option value="1">
              SÍ
            </option>
          </select>
        </div>

        <div class="input-group cont-labor-verification-perform-stage">
          <label class="input-group-addon">Realizar en etapa <span></span></label> 
          <select class="form-control js-resource-stage"
                name="verify_labor_perform_stage">
          
            <?php $perform_on_stage = null; ?>

            <option value="">Seleccione</option>
            <?php foreach ($rys_stages as $index => $stage): ?>
              
              <option value="<?php echo $index; ?>" 
                      <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                <?php echo $stage; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-labor-verification-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="verify_labor_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion Verificación laboral -->

    <!-- Inicio seccion Verificación grados y titulos -->
    <div id="section-resource-verify-degree" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">VERIFICACION GRADOS Y TITULOS</h3>
    
      <div style="padding-top: 15px;">

        <div class="input-group">
          <label class="input-group-addon">Verificar <span>*</span></label> 
          <select name="verify_degree" 
                  class="form-control js-resource-input" 
                  id="degrees_titles_verification">
            
            <option value="0">
              NO
            </option>
            <option value="1">
              SÍ
            </option>
          </select>
        </div>

        <div class="input-group cont-degrees-titles-verification-perform-stage">
          <label class="input-group-addon">Realizar en etapa <span></span></label> 
          <select class="form-control js-resource-stage"
                name="verify_degree_perform_stage">
          
            <?php $perform_on_stage = null; ?>

            <option value="">Seleccione</option>
            <?php foreach ($rys_stages as $index => $stage): ?>
              
              <option value="<?php echo $index; ?>" 
                      <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                <?php echo $stage; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-degrees-titles-verification-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="verify_degree_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion Verificación grados y titulos -->

    <!-- Inicio seccion Verificación presencial grados y titulos -->
    <div id="section-resource-verify-degree-person" class="cont-section-resource" style="padding-top: 20px;">
      <h3 style="font-size:14px;border-bottom: 1px solid #bbb;padding:2px;">VERIFICACION PRESENCIAL GRADOS Y TITULOS</h3>
    
      <div style="padding-top: 15px;">

        <div class="input-group">
          <label class="input-group-addon">Verificar <span>*</span></label> 
          <select name="verify_degree_person" 
                  class="form-control js-resource-input" 
                  id="degrees_titles_person_verification">
            
            <option value="0">
              NO
            </option>
            <option value="1">
              SÍ
            </option>
          </select>
        </div>

        <div class="input-group cont-degrees-titles-person-verification-perform-stage">
          <label class="input-group-addon">Realizar en etapa <span></span></label> 
          <select class="form-control js-resource-stage"
                name="verify_degree_person_perform_stage">
          
            <?php $perform_on_stage = null; ?>

            <option value="">Seleccione</option>
            <?php foreach ($rys_stages as $index => $stage): ?>
              
              <option value="<?php echo $index; ?>" 
                      <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                <?php echo $stage; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="input-group cont-degrees-titles-person-verification-staff-charge">
          <label class="input-group-addon">Encargado <span></span></label> 
          <input type="text" name="verify_degree_person_staff_charge" class="form-control" value="">
        </div>

      </div>
    </div>
    <!-- Fin seccion Verificación laboral -->

  </div>
</div>

<script type="module">
$(function(){
  $( '#type_emo' ).change(function(){
    const emoIndex = $(this).prop('selectedIndex');

    $( '.cont-emo-expense-type' ).hide();
    $( '.cont-emo-staff-charge' ).hide();
    
    if (emoIndex != 0) {
      $( '.cont-emo-expense-type' ).show();
      $( '.cont-emo-staff-charge' ).show();
    }

    $( '.cont-emo-protocol-detail' ).hide();
    
    if ($(this).val() == '10') {
      $( '.cont-emo-protocol-detail' ).show();
    }

    $( '.info-error', '#section-resource-emo' ).remove();
    $( '.has-error', '#section-resource-emo' ).removeClass('has-error');
  });

  $( '#type_screening' ).change(function(){

    const screeningIndex = $(this).prop('selectedIndex');

    $( '.cont-screening-expense-type' ).hide();
    $( '.cont-screening-perform-stage' ).hide();
    $( '.cont-screening-staff-charge' ).hide();

    if (screeningIndex != 0) {
      $( '.cont-screening-expense-type' ).show();
      $( '.cont-screening-perform-stage' ).show();
      $( '.cont-screening-staff-charge' ).show();
    }

    $( '.info-error', '#section-resource-screening' ).remove();
    $( '.has-error', '#section-resource-screening' ).removeClass('has-error');
  }); 

  $( '#exam_type_covid' ).change(function(){
    const covid19Index = $(this).prop('selectedIndex');

    $( '.cont-covid19-expense-type' ).hide();
    $( '.cont-covid19-perform-stage' ).hide();
    $( '.cont-covid19-staff-charge' ).hide();

    if (covid19Index != 0) {
      $( '.cont-covid19-expense-type' ).show();
      $( '.cont-covid19-perform-stage' ).show();
      $( '.cont-covid19-staff-charge' ).show();
    }

    $( '.info-error', '#section-resource-covid19' ).remove();
    $( '.has-error', '#section-resource-covid19' ).removeClass('has-error');
  }); 

  $( '#exams-complementary' ).change(function(){
    const selectIndex = $(this).prop('selectedIndex');

    $( '.cont-exams-complementary-staff-charge' ).hide();
    
    if (selectIndex != 0) {
      $( '.cont-exams-complementary-staff-charge' ).show();
    }
  }); 

  $( '#home_verification' ).change(function(){
    const selectIndex = $(this).prop('selectedIndex');

    $( '.cont-home-verification-staff-charge' ).hide();
    $( '.cont-home-verification-perform-stage' ).hide();
    
    if (selectIndex != 0) {
      $( '.cont-home-verification-staff-charge' ).show();
      $( '.cont-home-verification-perform-stage' ).show();
    }

    $( '.info-error', '#section-resource-verify-home' ).remove();
    $( '.has-error', '#section-resource-verify-home' ).removeClass('has-error');
  }); 

  $( '#credit_verification' ).change(function(){
    const selectIndex = $(this).prop('selectedIndex');

    $( '.cont-credit-verification-staff-charge' ).hide();
    $( '.cont-credit-verification-perform-stage' ).hide();
    
    if (selectIndex != 0) {
      $( '.cont-credit-verification-staff-charge' ).show();
      $( '.cont-credit-verification-perform-stage' ).show();
    }

    $( '.info-error', '#section-resource-verify-credit' ).remove();
    $( '.has-error', '#section-resource-verify-credit' ).removeClass('has-error');
  }); 

  $( '#labor_verification' ).change(function(){
    const selectIndex = $(this).prop('selectedIndex');

    $( '.cont-labor-verification-staff-charge' ).hide();
    $( '.cont-labor-verification-perform-stage' ).hide();
    
    if (selectIndex != 0) {
      $( '.cont-labor-verification-staff-charge' ).show();
      $( '.cont-labor-verification-perform-stage' ).show();
    }

    $( '.info-error', '#section-resource-verify-labor' ).remove();
    $( '.has-error', '#section-resource-verify-labor' ).removeClass('has-error');
  }); 

  $( '#degrees_titles_verification' ).change(function(){
    const selectIndex = $(this).prop('selectedIndex');

    $( '.cont-degrees-titles-verification-staff-charge' ).hide();
    $( '.cont-degrees-titles-verification-perform-stage' ).hide();
    
    if (selectIndex != 0) {
      $( '.cont-degrees-titles-verification-staff-charge' ).show();
      $( '.cont-degrees-titles-verification-perform-stage' ).show();
    }

    $( '.info-error', '#section-resource-verify-degree' ).remove();
    $( '.has-error', '#section-resource-verify-degree' ).removeClass('has-error');
  }); 

  $( '#degrees_titles_person_verification' ).change(function(){
    const selectIndex = $(this).prop('selectedIndex');

    $( '.cont-degrees-titles-person-verification-staff-charge' ).hide();
    $( '.cont-degrees-titles-person-verification-perform-stage' ).hide();
    
    if (selectIndex != 0) {
      $( '.cont-degrees-titles-person-verification-staff-charge' ).show();
      $( '.cont-degrees-titles-person-verification-perform-stage' ).show();
    }

    $( '.info-error', '#section-resource-verify-degree-person' ).remove();
    $( '.has-error', '#section-resource-verify-degree-person' ).removeClass('has-error');
  }); 

  $( '.js-resource-type-expense' ).change(function(){
    $(this).data('select', true);
  });

  $( '#type_emo' ).change();
  $( '#type_screening' ).change();
  $( '#exam_type_covid' ).change();
  $( '#exams-complementary' ).change();
  $( '#home_verification' ).change();
  $( '#credit_verification' ).change();
  $( '#labor_verification' ).change();
  $( '#degrees_titles_verification' ).change();
  $( '#degrees_titles_person_verification' ).change();
});
</script>