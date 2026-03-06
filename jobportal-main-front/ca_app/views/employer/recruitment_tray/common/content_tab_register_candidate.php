<style>
  #modal-add-candidates .formwraper {
    box-shadow: none;
    border: 0;
    padding: 20px 15px;
  }

  .iti--allow-dropdown {
    width: 100%;
  }
</style>
<div class="formwraper">
  <div class="input-group">
    <label class="input-group-addon">Documento <span>*</span></label>
    <table width="100%" class="tbl-seeker-document-number">
      <tr>
        <td width="80">
          <select name="search_document_type" class="form-control">
            <option value="">Tipo de Doc.</option>
            <?php foreach ($document_types as $doc_type): ?>
              <option value="<?php echo $doc_type->id; ?>">
                <?php e($doc_type->abbreviation); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </td>
        <td>
          <input name="search_document_number" type="text" class="form-control" placeholder="Número de documento" value="" maxlength="40">
        </td>
        <td>
          <button class="btn btn-xs btn-primary btn-style-1" type="button" name="search_doc_number" style="margin:5px;">Buscar</button>
        </td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td>
          <button type="button" class="btn btn-xs btn-primary btn-style-1 cancel-seeker-register">Cancelar</button>
        </td>
      </tr>
    </table>
  </div>

  <?php echo form_open_multipart('employer/recruitment_tray/candidate_register/create', ['name' => 'seeker_form', 'id' => 'form-candidate-register']);?>
    <input type="hidden" value="<?php echo $client->code; ?>" name="client_code">
    <input type="hidden" name="document_type">
    <input type="hidden" name="document_number">
    <input type="hidden" value="0" name="reniec">
    <input type="hidden" value="" name="current_address">
    <input type="hidden" value="" name="photo">
      
    <div class="input-group" style="display:none;">
        <label class="input-group-addon">Foto <span></span></label>
        <img src="" 
            alt="Foto" 
            class="photo" 
            style="width:80px;">
    </div>

    <div class="input-group <?php echo (form_error('email'))?'has-error':'';?>">
        <label class="input-group-addon">Email <span>*</span></label>
        <input name="email" type="text" class="form-control" id="email" placeholder="Email" value="" maxlength="100" required>
        <?php echo form_error('email'); ?>
    </div>

    <div class="input-group <?php echo (form_error('pass'))?'has-error':'';?>">
        <label class="input-group-addon">Contraseña <span>*</span></label>
        <table width="100%">
            <tr>
                <td width="60%">
                  <input name="pass" 
                          type="password" 
                          class="form-control" 
                          id="pass" 
                          autocomplete="off" 
                          placeholder="Contraseña" 
                          value="" 
                          maxlength="15" 
                          required>
                </td>
                <td style="padding:5px;">
                  <button class="btn btn-xs btn-default btn-generate-password" 
                          type="button">
                          Generar
                  </button>
                  <button class="btn btn-xs btn-default btn-toggle-show-hide-password" 
                          type="button">
                          Ver / Ocultar
                  </button>
                </td>
            </tr>
        </table>
        
        <span style="color: #777;font-style: italic;display:block;">Debe contener mayúsculas, minúsculas, números y caracteres especiales.</span>
        <span style="color: #777;font-style: italic;">Ejemplo: estreLLA7583!!</span>
        <?php echo form_error('pass'); ?> 
    </div>

    <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
        <label class="input-group-addon">Nombre(s) <span>*</span></label>
        <input name="full_name" type="text" class="form-control" id="full_name" placeholder="Nombre(s)" value="" maxlength="30" required>
        <?php echo form_error('full_name'); ?>
    </div>
    <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
        <label class="input-group-addon">Apellido Paterno <span>*</span></label>
        <input name="paternal_last_name" type="text" class="form-control" id="paternal_last_name" placeholder="Apellido Paterno" value="" maxlength="30" required>
        <?php echo form_error('paternal_last_name'); ?>
    </div>
    <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
        <label class="input-group-addon">Apellido Materno <span>*</span></label>
        <input name="maternal_last_name" type="text" class="form-control" id="maternal_last_name" placeholder="Apellido Materno" value="" maxlength="30" required>
        <?php echo form_error('maternal_last_name'); ?>
    </div>

    <div class="input-group <?php echo (form_error('gender'))?'has-error':'';?>">
        <label class="input-group-addon">Sexo <span>*</span></label>
        <select class="form-control" name="gender" id="gender" required>
            <option value="">Seleccione</option>
            <?php foreach ($genders as $row_gender): ?>
            <option value="<?php echo $row_gender->id; ?>">
                <?php e($row_gender->name); ?>
            </option>
            <?php endforeach; ?>                               
        </select>
        <?php echo form_error('gender'); ?> </div>
    <div class="input-group <?php echo (form_error('dob_day'))?'has-error':'';?>">
        <label class="input-group-addon">Fecha de nacimiento <span>*</span></label>
        <select class="form-control" name="dob_day" id="dob_day" required>
        <option value="">Día</option>
        <?php 
        for($dy=1;$dy<=31;$dy++):
          $day = sprintf("%02s", $dy);
                  $selected = (set_value('dob_day')==$day)?'selected="selected"':'';
          ?>
          <option value="<?php echo $day;?>" <?php echo $selected;?>><?php echo $day;?></option>
        <?php endfor;?>
        </select>
        <select class="form-control" name="dob_month" id="dob_month" required>
        <option value="">Mes</option>
        <?php for($mnth=1;$mnth<=12;$mnth++):
        $month =sprintf("%02s", $mnth);
        $dummy_date = '2014-'.$month.'-'.'01';
        $selected = (set_value('dob_month')==$month)?'selected="selected"':'';?>
        <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(date('M', strtotime($dummy_date)));?></option>
        <?php endfor;?>
        </select>
        <select class="form-control" name="dob_year" id="dob_year" required>
        <option value="">Año</option>
        <?php for($year=date("Y")-10;$year>=1901;$year--):
        $selected = (set_value('dob_year')==$year)?'selected="selected"':'';
        if((set_value('dob_year')=='' && $year=='1980')){
            $selected = 'selected="selected"';
        }?>
        <option value="<?php echo $year;?>" <?php echo $selected;?>><?php echo $year;?></option>
        <?php endfor;?>
        </select>
        <?php echo form_error('dob_day'); echo form_error('dob_month'); echo form_error('dob_month'); ?> 
    </div>

    <div class="input-group <?php echo (form_error('civil_status')) ? 'has-error' : '';?>">
        <label class="input-group-addon">Estado civil <span>*</span></label>
        <select class="form-control" name="civil_status" id="civil_status" required>
        <option value="">Seleccione</option>

        <?php foreach ($civil_status as $row_civil_status): ?>
            <option value="<?php echo $row_civil_status->id; ?>">
            <?php e($row_civil_status->name); ?>
            </option>
        <?php endforeach; ?>   
    
        </select>
        <?php echo form_error('civil_status'); ?>
    </div>
    <div class="input-group <?php echo (form_error('mobile_number'))?'has-error':'';?>">
        <label class="input-group-addon">Teléfono móvil <span>*</span></label>
        <input name="mobile_number" 
               type="text" 
               class="form-control" 
               id="mobile_number" 
               value="" 
               maxlength="15" 
               style="width: 100%;"
               required/>
        <?php echo form_error('mobile_number'); ?>
    </div>

    <div class="input-group <?php echo (form_error('nationality'))?'has-error':'';?>">
        <label class="input-group-addon" name="nationality">Nacionalidad <span>*</span></label>
        <select class="form-control" name="nationality" id="nationality" style="width:100%;" required>
        <?php foreach($result_countries as $row_country): 
            if ($row_country->country_citizen!=''):
              $selected = (set_value('nationality')==$row_country->ID)?'selected="selected"':'';
                
        ?>
        <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_citizen;?></option>
        <?php endif; endforeach;?>
        </select>
        <?php echo form_error('nationality'); ?> 
    </div>
    <div class="input-group <?php echo (form_error('disability')) ? 'has-error' : '';?>">
        <label class="input-group-addon">
        <input id="check_disability" type="checkbox" name="check_disability" value="true" <?php echo (set_value('check_disability') != null ? 'checked' : ''); ?>>
        Presento discapacidad
        </label>
        <select class="form-control" name="disability" id="disability" required>
            <?php foreach ($disabilities as $row_disability): ?>
                <option value="<?php echo $row_disability->id; ?>">
                <?php e($row_disability->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('disability'); ?>
    </div>  

    <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
        <label class="input-group-addon">País <span>*</span></label>
        <select name="country" id="country" class="form-control" style="width:50%" required>
        <?php 
        foreach($result_countries as $row_country):

        if (empty($row_country->phone_code)) {
            continue;
        }

        $selected = (set_value('country') == $row_country->ID)?'selected="selected"':'';
        
        ?>
        <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_name;?></option>
        <?php endforeach;?>
        </select>
        <?php echo form_error('country'); ?>
    </div>

    <div class="input-group <?php echo (form_error('city'))?'has-error':'';?>">
      <label class="input-group-addon">Ubicación / Ciudad<span> *</span></label>
      <input id="city_text" name="city" type="text" class="form-control" value="<?php echo set_value("city"); ?>" autocomplete="off">

        <div id="ubigeos" class="row">
          <div class="col-md-4">
            <select id="department" name="department" class="form-control" style="width: 100%;">
            <option value="">Departamentos</option>  
            <?php foreach ($departments as $row): ?>
            <?php 
              $department = $row->department; 
              $department_selected =  $department == set_value('department') ? 'selected="selected"' : '';
            ?>
            <option value="<?php echo $department; ?>" <?php echo $department_selected; ?>>
              <?php echo $department; ?>    
            </option>
            <?php endforeach; ?>  
            </select>
          </div>
            
          <div class="col-md-4">
              <select id="provinces" name="province" class="form-control" style="width: 100%;">
              <option value="">Provincias</option>  
              <?php foreach ($provinces as $row): ?>
                  <?php 
                    $province = $row->province; 
                    $province_selected =  $province == set_value('province') ? 'selected="selected"' : '';
                  ?>
                  <option value="<?php echo $province; ?>" <?php echo $province_selected; ?>>
                    <?php echo $province; ?>    
                  </option>
              <?php endforeach; ?>  
              </select>
          </div>
          <div class="col-md-4">
            <select id="districts" name="district" class="form-control" style="width: 100%;">
              <option value="">Distritos</option>  
              <?php foreach ($districts as $row): ?>
                <?php 
                  $district = $row->district; 
                  $district_selected =  $district == set_value('district') ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo $district; ?>" <?php echo $district_selected; ?>>
                  <?php echo $district; ?>    
                </option>
              <?php endforeach; ?>  
            </select>
          </div>
        </div>
      </div>
  <?php echo form_close(); ?>
</div>

<script>
$(function(){
  function getProvinces(filters, valueSelect) {
            
    const selectValue = valueSelect || false;
    const url = "<?php echo site_url('ubigeos/get_provinces_by'); ?>";
    const data = filters;

    $.post(url, data, function(result){
      provinces = result.data;
      
      $( "#provinces" ).empty().html("<option>Buscando...</option>");

      let provinces_options = '<option value="">Provincias</option>';

      for (row in provinces) {
        province_row = provinces[row];
        provinces_options+=`<option value="${province_row.province}">${province_row.province}</option>`;
      }

      $( "#provinces" ).html(provinces_options);

      if (selectValue) {
        $( "#provinces" ).val(selectValue);
      }

    }, 'json');
  }

  function getDistricts(filters, valueSelect) {
    const selectValue = valueSelect || false;
    const url = "<?php echo site_url('ubigeos/get_districts_by'); ?>";
    const data = filters;

    $.post(url, data, function(result){
      const districts = result.data;
      
      $( "#districts" ).empty().html("<option>Buscando...</option>");

      let distrinct_options = '<option value="">Distritos</option>';

      for (row in districts) {
        distrinct_row = districts[row];
        distrinct_options+=`<option value="${distrinct_row.district}">${distrinct_row.district}</option>`;
      }

      $( "#districts" ).html(distrinct_options);

      if (selectValue) {
        $( "#districts" ).val(selectValue);
      }

    }, 'json');
  }

  function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min) + min);
  }

  function getRandonStr(str, minlength, maxlength) {
    var pass = '';
    var minlength = minlength || 3;
    var maxlength = maxlength || 4;

    for (let i = 1; i <= getRandomInt(minlength, maxlength); i++) {
        var char = Math.floor(Math.random() * str.length);
        pass += str.charAt(char)
    }
      
    return pass;
  }

  function generatePassord() {
    pass = '';

    pass+=getRandonStr('abcdefghijklmnopqrstubwsyz');
    pass+=getRandonStr('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    pass+=getRandonStr('1234567890');
    pass+=getRandonStr('.-*@.!+$', 2, 2);
    
    return pass;
  }

  function resetFormSeekerRegister() {  
    $( '#modal-add-candidates img.photo' ).prop('src', '');
    $( '#modal-add-candidates img.photo' ).closest('.input-group').hide();
    $( '.tbl-seeker-document-number select[name="search_document_type"]' ).val('');
    $( '.tbl-seeker-document-number input[name="search_document_number"]' ).val('');
    $( '#modal-add-candidates input[name="current_address"]' ).val('');
    $( '#modal-add-candidates input[name="photo"]' ).val('');
    $( '#modal-add-candidates input[name="reniec"]' ).val('0');

    form = $( '#form-candidate-register' );
    form[0].reset();

    $( "#country" ).val("<?php echo $country->ID; ?>");
    $( "#nationality" ).val("<?php echo $country->ID; ?>");
  
    $( "#country" ).change();
    $( "#check_disability" ).change();

    $( '#form-candidate-register input[type="text"]'  ).prop('disabled', true);
    $( '#form-candidate-register input[type="password"]' ).prop('disabled', true);
    $( '#form-candidate-register select' ).prop('disabled', true);
    $( '#form-candidate-register button' ).prop('disabled', true);

    $( '.tbl-seeker-document-number tr:eq(0)' ).show();    
    $( '.tbl-seeker-document-number tr:eq(1)' ).hide();    
    
    $( '#modal-add-candidates .container-seeker-message' ).html('');
    $( '#modal-add-candidates .container-search-seeker' ).show();

    $( '#btn-register-candidates' ).prop('disabled', true);
    $( '#btn-register-candidates' ).html('Agregar');
  }

  function init() {
    //Valores por defecto
    const country = "<?php echo $country->ID; ?>";
    const nationality = "<?php echo $country->ID; ?>";
   
    $( "#country" ).val(country);
    $( "#nationality" ).val(nationality);
    
    $( "#check_disability" ).change(function(){
        
      $( "#disability" ).hide();

      if ($(this).is(':checked')) {
        $( "#disability" ).show();
      }
    });

    $( "#country" ).change(function() {
      $( ".city_err" ).closest('.input-group').removeClass('has-error');
      $( ".city_err" ).remove();
      $( ".department_err" ).remove();
      $( ".provinces_err" ).remove();
      $( ".districts_err" ).remove();
      
      $( "#city_text" ).hide().prop('disabled', true);
      $( "#department" ).prop('disabled', true);
      $( "#provinces" ).prop('disabled', true);
      $( "#districts" ).prop('disabled', true);
      
      if ($(this).val() == '56') { 
        $( "#ubigeos" ).show();
        $( "#department" ).prop('disabled', false);
        $( "#provinces" ).prop('disabled', false);
        $( "#districts" ).prop('disabled', false);
      } else {
        $( "#city_text" ).show().prop('disabled', false);
        $( "#ubigeos" ).hide();
      }
    });

    $( "#department" ).change(function(){
      getProvinces({
        department: $(this).val() 
      });
    });

    $( "#provinces" ).change(function(){
      getDistricts({
        province: $(this).val() 
      });
    });

    $( '#form-candidate-register' ).submit(function(e){
      e.preventDefault();

      $( '#modal-add-candidates .modal-content' ).addClass('load load-image');
      const btnSubmit = $( '#btn-register-candidates' );
      btnSubmit.text('Agregando...');
     
      const dataPhone = {
        'full_mobile_phone_number': ($('#mobile_number' ).data('iti-instance')).getNumber(intlTelInput.utils.numberFormat.E164)
      };

      const data = $(this).serialize() + '&' + $.param(dataPhone);
      const url = $(this).prop('action');
      const form = $(this);

      $.post(url, data, function(response){
          
        if (!response.status) {
          toastr["error"](response.message);
          return;
        } 

        if (response.status) {
          toastr["success"](response.message);
          searchCandidates(1, {});
          resetFormSeekerRegister();
        }
          
      }, 'json')
      .fail(function(e) {
        toastr["error"]('Ha ocurrido un error al intentar registrar el postulante');
      }).always(function(){
        $( '#modal-add-candidates .modal-content' ).removeClass('load load-image');
        btnSubmit.text('Agregar');
      });

      return false;
    });

    $( '#btn-register-candidates' ).click(function(){
      $( '#form-candidate-register' ).submit();
    });

    $( '.tbl-seeker-document-number button[name="search_doc_number"]' ).click(function(){

      $( '#modal-add-candidates .container-seeker-message' ).html('');
  
      const doc_type = $.trim($( '.tbl-seeker-document-number select[name="search_document_type"]' ).val());
      const doc_number = $.trim($( '.tbl-seeker-document-number input[name="search_document_number"]' ).val());
      const document_type_name = $( '.tbl-seeker-document-number select[name="search_document_type"]' ).find('option:selected').text();

      if (doc_type == '') {
        toastr["warning"]('Por favor seleccione el tipo de documento');
        return;
      }

      if (doc_number == '') {
        toastr["warning"]('Por favor ingrese número de documento');
        return;
      }   

      const btnSearch = $(this);
      btnSearch.prop('disabled', true);
      btnSearch.html('Buscando...');

      const url = "<?php echo site_url('employer/recruitment_tray/candidate_register/check_seeker'); ?>";
      const data = {
        'doc_type': doc_type,
        'doc_number': doc_number
      };

      $( '#modal-add-candidates .container-seeker-message' ).html(
        `<div style="text-align:center;">
          Consultando...
        </div>`
      );
      
      $.post(url, data, function(response) {
        
        if (response.seeker_id) {
          toastr["warning"]('Ya existe una cuenta registrada con el numero de identidad ingresado');
          return;
        }

        $( '#modal-add-candidates .container-seeker-message' ).html(``);
        $( '#modal-add-candidates .container-search-seeker' ).hide();

        table = $( '#modal-add-candidates .tbl-seeker-document-number' );
        table.find('tr:eq(1) td:eq(0)').html(`${document_type_name} - ${doc_number}` );

        $( '#form-candidate-register input[type="text"]'  ).prop('disabled', false);
        $( '#form-candidate-register input[type="password"]' ).prop('disabled', false);
        $( '#form-candidate-register select' ).prop('disabled', false);
        $( '#form-candidate-register button' ).prop('disabled', false);

        table.find('tr:eq(0)').hide();  
        table.find('tr:eq(1)').show();
        
        $( '#modal-add-candidates input[name="document_type"]' ).val(doc_type);
        $( '#modal-add-candidates input[name="document_number"]' ).val(doc_number);
        $( '#modal-add-candidates .container-form-create' ).show();

        $( '#btn-register-candidates' ).prop('disabled', false);

        if (!response.first_name) {
          return;
        }

        if (response.photo) {
          $( '#modal-add-candidates img.photo' ).prop('src', `data:image/png;base64,${response.photo}`);
          $( '#modal-add-candidates img.photo' ).closest('.input-group').show();
        }
      
        dobPart = $.trim(response.dob).split('-');
        
        $( '#modal-add-candidates input[name="reniec"]' ).val('1');
        $( '#modal-add-candidates input[name="full_name"]' ).val(response.first_name);
        $( '#modal-add-candidates input[name="paternal_last_name"]' ).val(response.paternal_last_name);
        $( '#modal-add-candidates input[name="maternal_last_name"]' ).val(response.maternal_last_name);
        $( '#modal-add-candidates select[name="gender"]' ).val(response.gender);
        $( '#modal-add-candidates select[name="dob_day"]' ).val(dobPart[2]);
        $( '#modal-add-candidates select[name="dob_month"]' ).val(dobPart[1]);
        $( '#modal-add-candidates select[name="dob_year"]' ).val(dobPart[0]);
        $( '#modal-add-candidates select[name="civil_status"]' ).val(response.civil_status);
        $( '#modal-add-candidates input[name="current_address"]' ).val(response.address);
        $( '#modal-add-candidates input[name="photo"]' ).val(response.photo);

        ubigeoPart = $.trim(response.ubigeo_text).split(', ');

        $( '#modal-add-candidates select[name="department"]' ).val(ubigeoPart[0]);

        getProvinces({
          department: ubigeoPart[0]
        }, ubigeoPart[1]);

        getDistricts({
          province: ubigeoPart[1] 
        }, ubigeoPart[2]);

      }, 'json')
      .fail(function(e) {
        toastr["error"]('Ha ocurrido un error al verificar el documento de identidad');
      })
      .always(function(){
        btnSearch.prop('disabled', false);
        btnSearch.html('Buscar');
      });
    });

    $( '#modal-add-candidates .cancel-seeker-register' ).click(function(){
      resetFormSeekerRegister();
    });

    $( document ).on('click', '#modal-add-candidates .container-seeker-message button', function(){
      resetFormSeekerRegister();
    });

    $( '.btn-generate-password' ).click(function(){
      $( '#form-candidate-register input[name="pass"]' ).val(generatePassord());
    });

    $( '.btn-toggle-show-hide-password' ).click(function(){
      var type = $( '#form-candidate-register input[name="pass"]' ).prop('type');
      $( '#form-candidate-register input[name="pass"]' ).prop('type', (type == 'password' ? 'text' : 'password'));
    });

    $( "#check_disability" ).change();
    $( "#country" ).change();

    $( '#form-candidate-register input[type="text"]'  ).prop('disabled', true);
    $( '#form-candidate-register input[type="password"]' ).prop('disabled', true);
    $( '#form-candidate-register select' ).prop('disabled', true);
    $( '#form-candidate-register button' ).prop('disabled', true);

    $( '.tbl-seeker-document-number tr:eq(0)' ).show();
    $( '.tbl-seeker-document-number tr:eq(1)' ).hide();

    const iti_mobile = window.intlTelInput($( '#mobile_number' )[0], {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: "<?php echo $country->iso_3166_1_alpha2; ?>",
      //hiddenInput: () => ({ phone: "full_mobile_phone_number"}),
    });

    $( '#mobile_number' ).data('iti-instance', iti_mobile);
  }

  //inicializar eventos y componentes
  init();
});
</script>