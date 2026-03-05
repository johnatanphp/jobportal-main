<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url('public/css/select2/select2.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('public/css/select2/customize-select2.css'); ?>">
<style>
  .ui-autocomplete { 
    z-index:99999999; 
  }

  #check_disability {
    vertical-align: middle;
    margin: 0 5px 0 0;
  }

  .sub-title-h3 { 
    margin-top: 20px;
  }
</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php 
  $this->load->view('common/header'); 
  $style = !is_jobseeker_data_complete() ? 'wrap_disable' : 'wrap_enabled';
?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row"> <?php echo form_open_multipart('jobseeker/my_account',array('name' => 'account_form', 'id' => 'account_form', 'onSubmit' => 'return validate_account_form(this);'));?>
     <div class="col-md-3">
    <div class="dashiconwrp <?php echo $style; ?>">
        <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
      </div>
    </div>
    
    <div class="col-md-9">  
      <?php if (!is_jobseeker_data_complete()): ?>
      <div class="alert alert-info" style="font-size: 17px;padding: 10px 8px;">
        Por favor termina de ingresar todos tus datos personales y ubicación
      </div>
    <?php endif; ?>

    <?php echo $this->session->flashdata('msg');?>
  
      <!--Personal info-->
      <div class="formwraper">
        <div class="titlehead">Actualizar perfil</div>
        <div class="formint">
          <div class="info-required">
            <span>*</span> Campos obligatorios
          </div>
          <h3 class="sub-title-h3">País de residencia</h3>
          <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
            <label class="input-group-addon">País <span>*</span></label>
            <select name="country" id="country" class="form-control" style="width:100%">
              <option value="">Seleccione</option>
              <?php 
                foreach($result_countries as $row_country):
                  $selected = ($row->country == $row_country->ID) ? 'selected="selected"':'';
              ?>
              <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_name;?></option>
              <?php endforeach;?>
            </select>
            <?php echo form_error('country'); ?>
          </div>

          <h3 class="sub-title-h3">Datos personales</h3>
          <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
            <label class="input-group-addon">Nombre <span>*</span></label>
            <input name="full_name" type="text" class="form-control" id="full_name" placeholder="Nombre" value="<?php echo $row->first_name; ?>" maxlength="40">
            <?php echo form_error('full_name'); ?> 
          </div>

          <div class="input-group <?php echo (form_error('paternal_last_name'))?'has-error':'';?>">
            <label class="input-group-addon">Apellido paterno <span>*</span></label>
            <input name="paternal_last_name" type="text" class="form-control" id="paternal_last_name" placeholder="Apellido paterno" value="<?php echo $row->paternal_last_name; ?>" maxlength="30">
            <?php echo form_error('paternal_last_name'); ?> 
          </div>

          <div class="input-group <?php echo (form_error('maternal_last_name'))?'has-error':'';?>">
            <label class="input-group-addon">Apellido materno <span>*</span></label>
            <input name="maternal_last_name" type="text" class="form-control" id="maternal_last_name" placeholder="Apellido materno" value="<?php echo $row->maternal_last_name; ?>" maxlength="30">
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
                      <option value="<?php echo $doc_type->id; ?>" <?php echo ($row->document_type == $doc_type->id) ? 'selected' : ''; ?>>
                        <?php e($doc_type->name); ?>
                      </option>
                    <?php endforeach; ?>         
                  </select>
                  <?php echo form_error('document_type'); ?>
                </td>
                <td>
                  <input name="document_number" type="text" class="form-control" id="document_number" placeholder="Número de documento" value="<?php echo $row->document_number; ?>" maxlength="40">
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
                <option value="<?php echo $row_gender->id; ?>" <?php echo ($row->gender == $row_gender->id) ? 'selected' : ''; ?>>
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
			  	$dob = explode('-', $row->dob);
				
			  for($dy=1;$dy<=31;$dy++):
			  	$day =sprintf("%02s", $dy);
              	$selected = ($dob[2]==$day)?'selected="selected"':'';
			  ?>
              <option value="<?php echo $day;?>" <?php echo $selected;?>><?php echo $day;?></option>
              <?php endfor;?>
            </select>
            <select class="form-control" name="dob_month" id="dob_month">
              <option value="">Mes</option>
              <?php for($mnth=1;$mnth<=12;$mnth++):
			  	$month =sprintf("%02s", $mnth);
			  	$selected = ($dob[1]==$month)?'selected="selected"':'';
				$dummy_date = '2014-'.$month.'-'.'01';
			  ?>
              <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(_date_locale_format(strtotime($dummy_date), 'MMMM'));?></option>
              <?php endfor;?>
            </select>
            <select class="form-control" name="dob_year" id="dob_year">
              <option value="">Año</option>
              <?php for($year=date("Y")-10;$year>=1901;$year--):
			  	$selected = ($dob[0]==$year)?'selected="selected"':'';
				if(($dob[0]=='' && $year=='1980')){
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

              <?php foreach ($civil_status as $row_civil_status): ?>
                <option value="<?php echo $row_civil_status->id; ?>" <?php echo ($row->civil_status == $row_civil_status->id) ? 'selected' : ''; ?>>
                  <?php e($row_civil_status->name); ?>
                </option>
              <?php endforeach; ?>   
            </select>
            <?php echo form_error('civil_status'); ?>
          </div>
          <div class="input-group <?php echo (form_error('nationality'))?'has-error':'';?>">
            <label class="input-group-addon" name="nationality">Nacionalidad <span>*</span></label>
            <select class="form-control" name="nationality" id="nationality" style="width:100%;">
              <option value="">Seleccione</option>
              <?php foreach($nationalities as $row_country): 
                if ($row_country->country_citizen!=''):
                  $selected = ($row->nationality==$row_country->ID)?'selected="selected"':'';

              ?>
                <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_citizen;?></option>
              <?php 
                endif; 
                endforeach;
              ?>
            </select>
            <?php echo form_error('nationality'); ?> 
          </div>
          <div class="input-group <?php echo (form_error('full_mobile_phone_number'))?'has-error':'';?>">
            <label class="input-group-addon">Teléfono móvil <span>*</span></label>
            <?php 
              $mobile = phone_number_format($row->mobile); 
            ?>
            <input name="mobile" 
                   type="text" 
                   class="form-control" 
                   id="mobile" 
                   value="<?php echo $mobile; ?>" 
                   maxlength="15" />
                
            <?php echo form_error('full_mobile_phone_number'); ?> </div>
          <div class="input-group <?php echo (form_error('full_home_phone_number')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Teléfono residencial</label>
            <?php 
              $home_phone = phone_number_format($row->home_phone);
            ?>
            <input name="home_phone" 
                   type="text" 
                   class="form-control" 
                   id="home_phone" 
                   value="<?php echo $home_phone; ?>" 
                   maxlength="15">
            <?php echo form_error('full_home_phone_number'); ?>
          </div>
          
          <div class="input-group <?php echo (form_error('disability')) ? 'has-error' : '';?>">
            <label class="input-group-addon">
              <input id="check_disability" type="checkbox" name="check_disability" value="true" <?php echo ($row->disability != null ? 'checked' : ''); ?>>
              Presento discapacidad
            </label>
            <select class="form-control" name="disability" id="disability">
              <option value="">Seleccione</option>
              <?php foreach ($disabilities as $row_disability): ?>
                <option value="<?php echo $row_disability->id; ?>" <?php echo ($row->disability == $row_disability->id) ? 'selected' : ''; ?>>
                  <?php e($row_disability->name); ?>
                </option>
              <?php endforeach; ?>
          </select>
            <?php echo form_error('disability'); ?>
          </div>
          <h3 class="sub-title-h3" style="margin-top: 25px;">Detalle ubicación</h3>
          <div class="input-group <?php echo (form_error('city'))?'has-error':'';?>">
            <label class="input-group-addon">Ubicación / Ciudad <span>*</span></label>
            <input id="city_text" name="city" type="text" class="form-control" value="<?php echo $row->city; ?>" autocomplete="off">
            <select id="city_dropdown" name="city" style="width: 100%;">
              <option value="">Seleccione</option>
              <?php foreach ($result_ubigeos as $row_ubigeo): ?>
                <?php 
                  $city_value = $row_ubigeo->order_administrative1 . ', ' . $row_ubigeo->order_administrative2 . ', ' . $row_ubigeo->order_administrative3; 
                  $city_selected =  $city_value == $row->city ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo $city_value; ?>" <?php echo $city_selected; ?>> <?php echo $city_value; ?></option>
              <?php endforeach; ?>  
            </select>
            <?php echo form_error('city'); ?>
          </div>

          <div class="input-group <?php echo (form_error('present_address'))?'has-error':'';?>">
            <label class="input-group-addon">Dirección <span>*</span></label>
            <textarea class="form-control" name="present_address" id="present_address" ><?php echo set_value('present_address') ? set_value('present_address') : $row->present_address; ?></textarea>
            <span style="font-size:12px;color:red;display:none;" class="present-address-alert-length"></span>
            <?php echo form_error('present_address'); ?>
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Redes sociales</h3>

          <div class="input-group">
            <label class="input-group-addon">Facebook</label>
            <input name="facebook" type="text" class="form-control" id="facebook" value="<?php echo $row->facebook; ?>" maxlength="70" placeholder="https://www.facebook.com/alguien">
          </div>
          <div class="input-group">
            <label class="input-group-addon">Linkedin</label>
            <input name="linkedin" type="text" class="form-control" id="linkedin" value="<?php echo $row->linkedin; ?>" maxlength="70" placeholder="https://www.linkedin.com/in/alguien">
          </div>
          <div class="input-group">
            <label class="input-group-addon">Twitter</label>
            <input name="twitter" type="text" class="form-control" id="twitter" value="<?php echo $row->twitter; ?>" maxlength="70" placeholder="@alguien">
          </div>

          <div align="center">
            <input type="submit" name="submit_button" id="submit_button" value="Actualizar" class="btn btn-primary" />
          </div>
        </div>
      </div>
    </div>
    <!--/Job Detail--> 
    <?php echo form_close();?>
    
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/validate_jobseeker.js?t=1640097925');?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script>  

<script type="text/javascript">
(function(){

  function changeLocation() {

    $( ".city_err" ).remove();

    const selectUbigeo = $( "#city_dropdown" );
    selectUbigeo.closest('div').removeClass( 'has-error' );
    selectUbigeo.hide();
    
    $( "#city_text" ).hide().prop('disabled', true);
    $( "#city_dropdown" ).hide().prop('disabled', true);
    
    if ($( "#country" ).val() == '56') { 
      $( "#city_text" ).val("");
      $( "#city_dropdown" ).show().prop('disabled', false);
      selectUbigeo.select2();
    } else {
      $( "#city_text" ).show().prop('disabled', false);
      
      try {
        selectUbigeo.select2('destroy');
      } catch (e) {}
    }
  }

  function searchDocumentTypes(e) {

    const url = "<?php echo site_url('jobseeker/my_account/get_document_types'); ?>?country_id=" + this.value; 
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
      console.log(error);
      toastr["error"]('Ha ocurrido un error');        
    });
  }

  function init() {

    document.querySelector("#country").addEventListener("change", changeLocation);
    document.querySelector("#country").addEventListener("change", searchDocumentTypes);
   
    $( "#check_disability" ).change(function(){  
      $( "#disability" ).hide();

      if ($(this).is(':checked')) {
        $( "#disability" ).show();
      }
    });

    $( '#present_address' ).keyup(function() {
      const maxLength = 100;
      const value = $(this).val();

      $( '.present-address-alert-length' ).hide();

      if (value.length > maxLength) {
        $( '.present-address-alert-length' ).text(`Máximo 100 caracteres de longitud, actuales: ${ parseInt(value.length) }`).show();
      } else {
        $(this).closest('.input-group').removeClass('has-error');
        $( '.errowbox', $(this).closest('.input-group')).remove();
      }
    });

    window.intlTelInput($( '#mobile' )[0], {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: "<?php echo $seeker_country->iso_3166_1_alpha2; ?>",
      hiddenInput: () => ({ phone: "full_mobile_phone_number"}),
    });

    window.intlTelInput($( '#home_phone' )[0], {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: "<?php echo $seeker_country->iso_3166_1_alpha2; ?>",
      hiddenInput: () => ({ phone: "full_home_phone_number"}),
    });

    $( ".iti__search-input" ).attr({'placeholder' : 'Buscar'});

    changeLocation();
    $( '#check_disability' ).change();
    $( '#present_address' ).keyup();
  }

  init();

})();
</script>
</body>
</html>
