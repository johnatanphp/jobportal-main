<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url('public/css/select2/select2.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('public/css/select2/customize-select2.css'); ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
<style>
  .ui-button {
  	margin-left: -1px;
  }

  .g-recaptcha {
    width: 300px;
    margin: 0 auto;
  }

  #check_disability {
    vertical-align: middle;
    margin: 0 5px 0 0;
  }

  .nav-pills li.active a, 
  .nav-pills li.active a:focus {
    background: #005da4;
    color: #ffffff;
  }

  .iti--inline-dropdown .iti__dropdown-content {
    z-index: 10;
  }
</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header-->
<div class="container detailinfo">
<div class="row"> <?php echo form_open_multipart('jobseeker_signup',array('name' => 'seeker_form', 'id' => 'seeker_form', 'onSubmit' => 'return validate_form(this);'));?>
  <div class="col-md-10">
    
    <h3> Crear cuenta postulante</h3>
    <br />
    <ul class="nav nav-pills nav-justified" style="display:none;">
      <li class="active" >
        <a href="#" >Soy postulante</a>
      </li>
      <li><a href="<?php echo site_url('employer_signup'); ?>">Soy empresa</a></li>
    </ul>
    <p>
      El proceso de registro no toma más de un par de minutos, luego podrá cargar su CV para ponerlo a disposición de los empleadores que lo busquen.
    </p>
    <div align="center" style="display: none;">
      <div class="row">
        <div class="col-md-6">
          <a href="<?php echo $login_url_fb; ?>" style="display:block;font-size: 15px;margin-bottom: 7px;">
            <i class="fa fa-facebook-square"></i>
            Registrarse con Facebook
          </a>
        </div>
        <div class="col-md-6">
          <a href="<?php echo $login_url_linkedin; ?>" style="display:block;font-size: 15px;">
            <i class="fa fa-linkedin-square"></i>
            Registrarse con Linkedin
          </a>
        </div>
      </div>
    </div>
    <br/>
      <?php if (form_error('g-recaptcha-response') != ''): ?>
      <div class="alert alert-danger"> 
          No se pudo registrar el postulante - La reCaptcha es inválida
      </div>
      <?php endif; ?>
    <!--Account info-->
     <div class="info-required">
        <span>*</span> Campos obligatorios 
      </div>
    <div class="formwraper">
      <div class="titlehead">Datos de ingreso</div>
      <div>
        <input type="hidden" name="type_register" value="<?php echo $type_register; ?>">
      </div>
      <div class="formint">
        <div class="alert alert-info hide">
          <table>
            <tr>
              <td width="40">
                 <i class="material-icons">
                  lock
                </i>
              </td>
              <td>
                <div style="padding-bottom: 5px;"><b>La contraseña debe cumplir con los siguientes requisitos:</b></div>  

                <p>Al menos 8 caracteres de longitud, letras minúsculas, letras mayúsculas, números y al menos 1 caracter especial.</p>
              </td>
            </tr>
          </table>
        </div>

        <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
          <label class="input-group-addon">País residencia <span>*</span></label>
          <select name="country" id="country" class="form-control" style="width:100%">
            <?php 
          foreach($result_countries as $row_country):
            if (empty($row_country->phone_code)) {
              continue;
            }
            $selected = ($visitor_country->ID == $row_country->ID)?'selected="selected"':'';
        ?>
            <option value="<?php echo $row_country->ID;?>" 
                    data-iso-alfa2-code="<?php echo $row_country->iso_3166_1_alpha2; ?>"
              <?php echo $selected;?>>
              <?php echo $row_country->country_name;?>
            </option>
            <?php endforeach;?>
          </select>
          <?php echo form_error('country'); ?>
        </div>

        <div class="input-group <?php echo (form_error('email'))?'has-error':'';?>">
          <label class="input-group-addon">Email <span>*</span></label>
          <input name="email" type="text" class="form-control" id="email" placeholder="Email" value="<?php echo set_value('email'); ?>" maxlength="150">
          <?php echo form_error('email'); ?>
        </div>
        <?php if ($type_register != '2'): ?>
          <div class="input-group <?php echo (form_error('pass'))?'has-error':'';?>">
            <label class="input-group-addon">Contraseña <span>*</span></label>
            <input name="pass" type="password" class="form-control" id="pass" autocomplete="off" placeholder="Contraseña" value="<?php echo set_value('pass'); ?>" maxlength="100">
            <span style="color: #777;font-style: italic;">Ejemplo: estreLLA7583!!</span>
            <?php echo form_error('pass'); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    
    <!--Personal info-->
    <div class="formwraper">
      <div class="titlehead">Información Personal</div>
      <div class="formint">
        <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
          <label class="input-group-addon">Nombre(s) <span>*</span></label>
          <input name="full_name" type="text" class="form-control" id="full_name" placeholder="Nombre(s)" value="<?php echo set_value('full_name'); ?>" maxlength="30">
          <?php echo form_error('full_name'); ?>
        </div>
        <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
          <label class="input-group-addon">Apellido Paterno <span>*</span></label>
          <input name="paternal_last_name" type="text" class="form-control" id="paternal_last_name" placeholder="Apellido Paterno" value="<?php echo set_value('paternal_last_name'); ?>" maxlength="30">
          <?php echo form_error('paternal_last_name'); ?>
        </div>
        <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
          <label class="input-group-addon">Apellido Materno <span>*</span></label>
          <input name="maternal_last_name" type="text" class="form-control" id="maternal_last_name" placeholder="Apellido Materno" value="<?php echo set_value('maternal_last_name'); ?>" maxlength="30">
          <?php echo form_error('maternal_last_name'); ?>
        </div>
        <div class="input-group <?php echo (form_error('document_number'))?'has-error':'';?>">
          <label class="input-group-addon">Documento <span>*</span></label>
          <table width="100%">
            <tr>
              <td width="20%">
                <select id="document_type" name="document_type" class="form-control">
                  <option value="">Tipo de documento</option>
                  <?php foreach ($document_types as $doc_type): ?>
                    <option value="<?php echo $doc_type->id; ?>" <?php echo (set_value('document_type') == $doc_type->id) ? 'selected' : ''; ?>>
                      <?php e($doc_type->name); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php echo form_error('document_type'); ?>
              </td>
              <td>
                <input name="document_number" type="text" class="form-control" id="document_number" placeholder="Número de documento" value="<?php echo set_value('document_number'); ?>" maxlength="40">
                <?php echo form_error('document_number'); ?>
              </td>
            </tr>
          </table>
        </div>

        <div class="input-group <?php echo (form_error('gender'))?'has-error':'';?>">
          <label class="input-group-addon">Sexo <span>*</span></label>
          <select class="form-control" name="gender" id="gender">
            <option value="">Seleccione</option>
            <?php foreach ($genders as $row_gender): ?>
              <option value="<?php echo $row_gender->id; ?>" <?php echo (set_value('gender') == $row_gender->id) ? 'selected' : ''; ?>>
                <?php e($row_gender->name); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('gender'); ?> </div>
        <div class="input-group <?php echo (form_error('dob_day'))?'has-error':'';?>">
          <label class="input-group-addon">Fecha de nacimiento <span>*</span></label>
          <select class="form-control" name="dob_day" id="dob_day">
            <option value="">Día</option>
            <?php 
			  	for($dy=1;$dy<=31;$dy++):
				$day =sprintf("%02s", $dy);
              	$selected = (set_value('dob_day')==$day)?'selected="selected"':'';
			  ?>
            <option value="<?php echo $day;?>" <?php echo $selected;?>><?php echo $day;?></option>
            <?php endfor;?>
          </select>
          <select class="form-control" name="dob_month" id="dob_month">
            <option value="">Mes</option>
            <?php for($mnth=1;$mnth<=12;$mnth++):
			  	$month =sprintf("%02s", $mnth);
				$dummy_date = '2014-'.$month.'-'.'01';
			  	$selected = (set_value('dob_month')==$month)?'selected="selected"':'';
			  ?>
            <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
            <?php endfor;?>
          </select>
          <select class="form-control" name="dob_year" id="dob_year">
            <option value="">Año</option>
            <?php for($year=date("Y")-10;$year>=1901;$year--):
			  	$selected = (set_value('dob_year')==$year)?'selected="selected"':'';
				if((set_value('dob_year')=='' && $year=='1980')){
					$selected = 'selected="selected"';
				}
			  ?>
            <option value="<?php echo $year;?>" <?php echo $selected;?>><?php echo $year;?></option>
            <?php endfor;?>
          </select>
          <?php echo form_error('dob_day'); echo form_error('dob_month'); echo form_error('dob_month'); ?> </div>
        
        <div class="input-group <?php echo (form_error('civil_status')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Estado civil <span>*</span></label>
          <select class="form-control" name="civil_status" id="civil_status">
            <option value="">Seleccione</option>

            <?php foreach ($civil_status as $row): ?>
              <option value="<?php echo $row->id; ?>" <?php echo (set_value('civil_status') == $row->id) ? 'selected' : ''; ?>>
                <?php e($row->name); ?>
              </option>
            <?php endforeach; ?>

          </select>
          <?php echo form_error('civil_status'); ?>
        </div>
        <div class="input-group <?php echo (form_error('full_mobile_number'))?'has-error':'';?>">
          <label class="input-group-addon">Teléfono móvil <span>*</span></label>
          <input name="mobile_number" 
                 type="text" 
                 class="form-control" 
                 id="mobile_number" 
                 value="" 
                 maxlength="16"
                 />  
          <?php echo form_error('full_mobile_number'); ?>
        </div>

        <div class="input-group <?php echo (form_error('nationality'))?'has-error':'';?>">
          <label class="input-group-addon" name="nationality">Nacionalidad <span>*</span></label>
          <select class="form-control" name="nationality" id="nationality" style="width:100%;">
            <option value="">Seleccione</option>
            <?php foreach($nationalities as $row_country): 
              if($row_country->country_citizen!=''):
                    $selected = (set_value('nationality')==$row_country->ID)?'selected="selected"':'';
                  
            ?>
            <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_citizen;?></option>
            <?php endif; endforeach;?>
          </select>
          <?php echo form_error('nationality'); ?> 
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
        <div class="input-group <?php echo (form_error('disability')) ? 'has-error' : '';?>">
          <label class="input-group-addon">
            <input id="check_disability" type="checkbox" name="check_disability" value="true" <?php echo (set_value('check_disability') != null ? 'checked' : ''); ?>>
            Presento discapacidad
          </label>
          <select class="form-control" name="disability" id="disability">
            <?php foreach ($disabilities as $row): ?>
              <option value="<?php echo $row->id; ?>" <?php echo (set_value('disability') == $row->id) ? 'selected' : ''; ?>>
                <?php e($row->name); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('disability'); ?>
        </div>  
      </div>
    </div>

    <!--Professional info-->
    <div class="formwraper" style="display: none;">
      <div class="titlehead">Subir Currículum</div>
      <div class="formint">
        <div class="input-group <?php echo (form_error('cv_file') || $msg)?'has-error':'';?>">
          <label class="input-group-addon">Cargar currículum (Opcional)<span></span></label>
          <input type="file" class="form-control" name="cv_file" id="cv_file" value="<?php echo set_value('cv_file'); ?>" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document"/>
          <p>Los formatos permitidos son: .doc, .docx y .pdf con un máximo de 4 MB.</p>
          <?php 
					echo form_error('cv_file'); 
					echo ($msg!='')?'<div class="alert alert-error"> <a class="close" data-dismiss="alert">×</a>'.$msg.'</div>':'';
			?>
        </div>
      </div>
    </div>

    <div class="formwraper" style="border: 0;">
      <div class="formint" style="padding: 0;">

        <div style="font-size: 13px;">
          Al crear la cuenta acepta los siguientes terminos y condiciones:
          <?php if ($this->config->item('site_terms') == 1): ?>
            <div>
              Acepta los <a href="<?php echo site_url('terms.html'); ?>" target="_blank">Terminos y condiciones de uso</a>
            </div>
          <?php endif; ?>

          <?php if ($this->config->item('site_privacy_policy') == 1): ?>
            <div>
              Acepta la <a href="<?php echo site_url('privacy-policy.html'); ?>" target="_blank">Política de privacidad de datos</a>       
            </div>
          <?php endif; ?>

          <div>
            Acepta poder recibir novedades, promociones y publicidad
          </div>
        </div>

        <div>
          <div class="rinput-group">     
            <div class="g-recaptcha"  data-sitekey="<?php echo $this->config->item('google_recaptcha_api_invisible_site_key'); ?>" data-callback="seekerFormSubmit" data-size="invisible">
            </div>
          </div>

          <div align="center">
            <br/>
            <input type="submit" name="submit_button" id="submit_button" value="Crear cuenta" class="btn btn-primary" />
          </div>
        </div>

      </div>
    </div>

    <!--/Job Detail--> 
    <?php echo form_close();?>    
  </div>
  
  <?php $this->load->view('common/right_ads');?>
  
</div>
<?php $this->load->view('common/bottom_ads');?>
</div>
</div>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
<script src="<?php echo base_url('public/js/validate_jobseeker.js?t=1640097925');?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script>

<script>
  function seekerFormSubmit() {
    $( '#seeker_form' )[0].submit();
  }
</script>
<script type="text/javascript">

(function(){
  function selectLocation() {
    $( ".city_err" ).closest('.input-group').removeClass('has-error');
    $( ".city_err" ).remove();
    $( ".department_err" ).remove();
    $( ".provinces_err" ).remove();
    $( ".districts_err" ).remove();
    
    $( "#city_text" ).hide().prop('disabled', true);
    $( "#department" ).prop('disabled', true);
    $( "#provinces" ).prop('disabled', true);
    $( "#districts" ).prop('disabled', true);
    
    if ($( "#country" ).val() == '56') { 
      $( "#ubigeos" ).show();
      $( "#department" ).prop('disabled', false);
      $( "#provinces" ).prop('disabled', false);
      $( "#districts" ).prop('disabled', false);
      
    } else {
      $( "#city_text" ).show().prop('disabled', false);
      $( "#ubigeos" ).hide();
    }

    const countryValue = $( "#country" ).val();
    $( "#nationality" ).val(countryValue);
  
    if ($( "#mobile_number" ).val() == '') {
      const isoCountry = document.querySelector("#country").querySelector('option:checked').dataset.isoAlfa2Code;
      $( "#mobile_number" ).data('intl-instance').setCountry(isoCountry);
    }
  }

  function searchDocumentTypes() {

    const selectCountry = document.querySelector("#country");

    const url = "<?php echo site_url('jobseeker_signup/get_document_types'); ?>?country_id=" + selectCountry.value; 
    fetch(url)
    .then(response => {
  
      if (!response.ok) {
        throw new Error('Server responded with status: ' + response.status);
      }
      return response.json();
    })
    .then(response => {
      const documentTypes = response.data;
      const select = document.querySelector("#document_type");
      let options = '<option value="">Seleccione</option>';

      for (let documentIndex in documentTypes) {
        let document = documentTypes[documentIndex];
        options += `<option value="${document.id}">${document.name}</option>`;
      }
      select.innerHTML = options;
    })
    .catch(error => {
      toastr["error"]('Ha ocurrido un error');        
    });
  }

  function changeEventCountry(e) {
    selectLocation();
    searchDocumentTypes();
  }

  function init() {

    document.querySelector("#country").addEventListener("change", changeEventCountry);

    $( "#check_disability" ).change(function(){
      
      $( "#disability" ).hide();

      if ($(this).is(':checked')) {
        $( "#disability" ).show();
      }
    });

    $( "#department" ).change(function(){

      var url = "<?php echo site_url('ubigeos/get_provinces_by'); ?>";
      
      var data = {
        department: $(this).val() 
      };

      $.post(url, data, function(result){
        provinces = result.data;
        
        $( "#provinces" ).empty().html("<option>Buscando...</option>");

        var provinces_options = '<option value="">Provincias</option>';

        for (row in provinces) {
          province_row = provinces[row];

          provinces_options+=`<option value="${province_row.province}">${province_row.province}</option>`;
        }

        $( "#provinces" ).html(provinces_options);

      }, 'json');
    });

    $( "#provinces" ).change(function(){

      var url = "<?php echo site_url('ubigeos/get_districts_by'); ?>";
      
      var data = {
        province: $(this).val() 
      };

      $.post(url, data, function(result){
        districts = result.data;
        
        $( "#districts" ).empty().html("<option>Buscando...</option>");

        var distrinct_options = '<option value="">Distritos</option>';

        for (row in districts) {
          distrinct_row = districts[row];

          distrinct_options+=`<option value="${distrinct_row.district}">${distrinct_row.district}</option>`;
        }

        $( "#districts" ).html(distrinct_options);

      }, 'json');
    });

    const intlTelMobileNumber = window.intlTelInput($( '#mobile_number' )[0], {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: 'pe',
      hiddenInput: () => ({ phone: "full_mobile_number"}),
    });

    $( "#mobile_number" ).data('intl-instance', intlTelMobileNumber);
    $( ".iti__search-input" ).attr({'placeholder' : 'Buscar'});
    $( "#check_disability" ).change();
    selectLocation();
  }

  init();

})();

</script>
</body>
</html>
