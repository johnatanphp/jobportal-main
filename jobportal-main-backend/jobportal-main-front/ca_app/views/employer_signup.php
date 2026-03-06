<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo base_url('public/autocomplete/demo.css'); ?>">
<style>
  .ui-button {
  	margin-left: -1px;
  }
  .ui-button-icon-only .ui-button-text {
  	padding: 0.35em;
  }
  .ui-autocomplete-input {
  	margin: 0;
  	padding: 0.48em 0 0.47em 0.45em;
  }

  .nav-pills li.active a, 
  .nav-pills li.active a:focus {
    background: #005da4;
    color: #ffffff;
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
  <div class="row"> 
    <?php echo form_open_multipart('employer_signup?code=' . (isset($_GET['code']) ? $_GET['code'] : ''), array('name' => 'emp_form', 'id' => 'emp_form', 'onSubmit' => 'return validate_employer_form(this);'));?>
    <div class="col-md-10">
      <h3 style="padding: 6px 0px;">Crear cuenta empresa</h3>

      <ul class="nav nav-pills nav-justified">
        <li>
          <a href="<?php echo site_url('jobseeker-signup'); ?>">Soy postulante</a>
        </li>
        <li class="active">
          <a href="#">Soy empresa</a>
        </li>
      </ul>

      <br />
      <?php echo $this->session->flashdata('success_msg'); ?>
      <!--Account info-->
      <div class="info-required">
        <span>*</span> Campos obligatorios 
      </div>
      <div class="formwraper">
        <div class="titlehead">Información de la cuenta</div>
        <div class="formint">

          <div class="alert alert-info">
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
          <div class="input-group <?php echo (form_error('email'))?'has-error':'';?>">
            <label class="input-group-addon">Email <span>*</span></label>
            <input name="email" type="text" class="form-control" id="email" placeholder="Email" value="<?php echo set_value('email'); ?>" maxlength="150">
            <?php echo form_error('email'); ?> </div>
          <div class="input-group <?php echo (form_error('pass_code'))?'has-error':'';?>">
            <label class="input-group-addon">Contraseña <span>*</span></label>
            <input name="pass" type="password" class="form-control" id="pass" placeholder="Contraseña" value="<?php echo set_value('pass_code'); ?>" maxlength="100">
            <?php echo form_error('pass_code'); ?> </div>
          <div class="input-group <?php echo (form_error('confirm_pass'))?'has-error':'';?>">
            <label class="input-group-addon">Confirmar la contraseña <span>*</span></label>
            <input name="confirm_pass" type="password" class="form-control" id="confirm_pass" placeholder="Confirmar la contraseña" value="<?php echo set_value('confirm_pass'); ?>" maxlength="100">
            <?php echo form_error('confirm_pass'); ?> </div>
        </div>
      </div>
      
      <!--Personal info-->
      <div class="formwraper">
        <div class="titlehead">Información de la empresa</div>
        <div class="formint">
          <div class="input-group <?php echo (form_error('company_ruc'))?'has-error':'';?>">
            <label class="input-group-addon">RUC de la empresa <span>*</span></label>
            
            <table width="100%">
              <?php 
                $company_ruc_name = trim(set_value('company_ruc')) != '' &&
                                    trim(set_value('company_name')) != '';            
              ?>
              <tr>
                <td>
                  <input name="company_ruc" 
                   type="text" 
                   class="form-control" 
                   id="company_ruc" 
                   value="<?php echo set_value('company_ruc'); ?>" 
                   maxlength="11" 
                   placeholder="RUC de la empresa"
                   <?php echo $company_ruc_name ? 'readonly="true"' : ''; ?>/>
                </td>
                <td width="20%" align="center">
                  <button id="search-company"
                          class="btn btn-xs btn-primary hide" 
                          type="button"
                          data-search="<?php echo $company_ruc_name ? 0 : 1; ?>">
                          <?php echo $company_ruc_name ? 'Cambiar' : 'Verificar'; ?>
                  </button>
                </td>
              </tr>
            </table>  
            <?php echo form_error('company_ruc'); ?>
          </div>
        
          <div class="input-group <?php echo (form_error('company_name'))?'has-error':'';?>">
            <label class="input-group-addon">Nombre de la empresa <span>*</span></label>
            <input name="company_name" 
                   type="text" 
                   class="form-control" 
                   id="company_name" 
                   placeholder="Nombre de la empresa" 
                   value="<?php echo set_value('company_name'); ?>" 
                   />
            <?php echo form_error('company_name'); ?> </div>

          <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
            <label class="input-group-addon">Nombre del empleador <span>*</span></label>
            <input name="full_name" type="text" class="form-control" id="full_name" placeholder="Nombre del empleador" value="<?php echo set_value('full_name'); ?>" maxlength="40">
            <?php echo form_error('full_name'); ?>
          </div>
          
          <div class="input-group <?php echo (form_error('industry_id'))?'has-error':'';?>">
            <label class="input-group-addon">Industria <span>*</span></label>
            <select name="industry_id" id="industry_id" class="form-control" style="max-width:350px;">
              <option value="" selected>Seleccione</option>
              <?php foreach($result_industries as $row_industry):
				  			$selected = (set_value('industry_id')==$row_industry->ID)?'selected="selected"':'';
				  ?>
              <option value="<?php echo $row_industry->ID;?>" <?php echo $selected;?>><?php echo $row_industry->industry_name;?></option>
              <?php endforeach;?>
            </select>
            <?php echo form_error('industry_id'); ?> </div>
          
          <div class="input-group <?php echo (form_error('ownership_type'))?'has-error':'';?>">
            <label class="input-group-addon">Tipo de organización</label>
            <select class="form-control" name="ownership_type" id="ownership_type">
              <option value="Private">Privada</option>
              <option value="Public">Pública</option>
              <option value="Government">Gubernamental</option>
              <option value="Semi-Government">Semi gubernamental</option>
              <option value="NGO">O.N.G</option>
            </select>
            <?php echo form_error('ownership_type'); ?> </div>
          
          <div class="input-group <?php echo (form_error('company_location'))?'has-error':'';?>">
            <label class="input-group-addon">Dirección <span>*</span></label>
            <textarea class="form-control" name="company_location" id="company_location" ><?php echo set_value('company_location'); ?></textarea>
            <?php echo form_error('company_location'); ?> </div>


          <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
            <label class="input-group-addon">País <span>*</span></label>
            <select name="country" id="country" class="form-control" style="width:50%">
            <?php 
            foreach($result_countries as $row_country):
              $selected = (set_value('country')==$row_country->country_name)?'selected="selected"':'';
            ?>
              <option value="<?php echo $row_country->ID . '-' . $row_country->country_name;?>" <?php echo $selected;?>>
                <?php echo $row_country->country_name;?>    
              </option>
              <?php endforeach;?>
            </select>
            <?php echo form_error('country'); ?>
          </div>

          <div class="input-group <?php echo (form_error('city'))?'has-error':'';?>">
            <label class="input-group-addon">Ubicación / Ciudad<span> *</span></label>
            <input id="city_text" name="city" type="text" class="form-control" value="<?php echo set_value("city"); ?>" autocomplete="off">
            
            <select id="city_dropdown" name="city" style="width: 100%;">
              <option value="">Seleccione</option>  
              <?php foreach ($result_ubigeos as $row_ubigeo): ?>
                  <?php 
                    $city_value = $row_ubigeo->order_administrative1 . ', ' . $row_ubigeo->order_administrative2 . ', ' . $row_ubigeo->order_administrative3; 
                    $city_selected =  $city_value == set_value('city') ? 'selected="selected"' : '';
                  ?>
                  <option value="<?php echo $city_value; ?>" <?php echo $city_selected; ?>><?php echo $city_value; ?></option>
              <?php endforeach; ?>  
            </select>
            <?php echo form_error('city'); ?>
          </div>

          <div class="input-group <?php echo (form_error('company_phone'))?'has-error':'';?>">
            <label class="input-group-addon">Teléfono fijo <span></span></label>
            <table>
              <tr>
                <td width="20%">
                  <select id="company_phone_code" name="company_phone_code" class="form-control" style="max-width: 200px;">
                    <?php 
                      foreach ($result_countries as $row_country):
                        if (!$row_country->phone_code) {
                          continue;
                        }
                        $company_phone_code = '+' . $row_country->phone_code;
                        $selected = (set_value('company_phone_code') == $company_phone_code) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $company_phone_code; ?>" <?php echo $selected; ?> ><?php echo $row_country->country_name . " (" . $company_phone_code . ")"; ?></option>
                    <?php endforeach ?>
                  </select>  
                </td>
                <td>
                  <input type="phone" class="form-control" name="company_phone" id="company_phone" placeholder="Teléfono fijo" value="<?php echo set_value('company_phone'); ?>" maxlength="20" />
                </td>
              </tr>
            </table>
            <?php echo form_error('company_phone'); ?>
          </div>
          
          <div class="input-group <?php echo (form_error('mobile_phone'))?'has-error':'';?>">
            <label class="input-group-addon">Teléfono móvil <span>*</span></label>

            <table>
              <tr>
                <td width="20%">                  
                  <select id="mobile_phone_code" name="mobile_phone_code" class="form-control" style="max-width: 200px;">
                    <?php 
                      foreach ($result_countries as $row_country):
                        if (!$row_country->phone_code) {
                          continue;
                        }
                        $mobile_phone_code = '+' . $row_country->phone_code;
                        $selected = (set_value('mobile_phone_code') == $mobile_phone_code) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $mobile_phone_code; ?>" <?php echo $selected; ?> ><?php echo $row_country->country_name . " (" . $mobile_phone_code . ")"; ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
                <td>
                  <input name="mobile_phone" type="text" class="form-control" id="mobile_phone" placeholder="Teléfono móvil" value="<?php echo set_value('mobile_phone'); ?>" maxlength="15"/>
                </td>
              </tr>
            </table>
            <?php echo form_error('mobile_phone'); ?>
          </div>
          
          <div class="input-group <?php echo (form_error('company_website'))?'has-error':'';?>">
            <label class="input-group-addon">Sitio web de la empresa <span>*</span></label>
            <input name="company_website" type="text" class="form-control" id="company_website" placeholder="Sitio web de la empresa" value="<?php echo set_value('company_website'); ?>" maxlength="155">
            <?php echo form_error('company_website'); ?> </div>
          
          <div class="input-group <?php echo (form_error('no_of_employees'))?'has-error':'';?>">
            <label class="input-group-addon">N° de empleados <span></span></label>
            <select name="no_of_employees" id="no_of_employees" class="form-control">
              <option value="1-10" <?php echo (set_value('no_of_employees')=='1-10')?'selected':''; ?>>1-10</option>
              <option value="11-50" <?php echo (set_value('no_of_employees')=='11-50')?'selected':''; ?>>11-50</option>
              <option value="51-100" <?php echo (set_value('no_of_employees')=='51-100')?'selected':''; ?>>51-100</option>
              <option value="101-300" <?php echo (set_value('no_of_employees')=='101-300')?'selected':''; ?>>101-300</option>
              <option value="301-600" <?php echo (set_value('no_of_employees')=='301-600')?'selected':''; ?>>301-600</option>
              <option value="601-1000" <?php echo (set_value('no_of_employees')=='601-1000')?'selected':''; ?>>601-1000</option>
              <option value="1001-1500" <?php echo (set_value('no_of_employees')=='1001-1500')?'selected':''; ?>>1001-1500</option>
              <option value="1501-2000" <?php echo (set_value('no_of_employees')=='1501-2000')?'selected':''; ?>>1501-2000</option>
              <option value="More than 2000" <?php echo (set_value('no_of_employees')=='More than 2000')?'selected':''; ?>>Más de 2000</option>
            </select>
            <?php echo form_error('no_of_employees'); ?> </div>
          
          <div class="input-group <?php echo (form_error('company_description'))?'has-error':'';?>">
            <label class="input-group-addon">Misión de la empresa<span> *</span></label>
            <textarea class="form-control" name="company_description" id="company_description" ><?php echo set_value('company_description'); ?></textarea>
            <?php echo form_error('company_description'); ?> </div>
          
          <div class="input-group <?php echo (form_error('company_logo'))?'has-error':'';?>">
            <label class="input-group-addon">Logo de la empresa <span>*</span></label>
            <input type="file" class="form-control" name="company_logo" id="company_logo" accept="image/*" />
            <p>Los formatos permitidos son: .jpg, .jpeg, .gif o .png con un máximo de 2 MB.</p>
            <?php echo form_error('company_logo'); ?>
          </div>

          <div class="input-group <?php echo (form_error('g-recaptcha-response'))?'has-error':'';?>" style="margin-top:20px;width: 100%">
            <label class="input-group-addon"></label>
            <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('google_recaptcha_api_site_key'); ?>"></div>
            <?php echo form_error('g-recaptcha-response'); ?>
          </div>
          <div align="center" style="border-top: 1px solid #ddd; padding: 10px 0;">
            <input type="submit" name="submit_button" id="submit_button" value="Crear cuenta" class="btn btn-primary" />
          </div>
        
        </div>
      </div>
      
      <!--Professional info-->
      
    </div>
    <!--/Job Detail--> 
    <?php echo form_close();?>
    <?php $this->load->view('common/right_ads');?>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
<?php $this->load->view('common/before_body_close'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="<?php echo base_url('public/js/validate_employer.js?t=1619643574');?>" type="text/javascript"></script> 
<script type="text/javascript">

$(function() {

  var country = "<?php echo set_value('country'); ?>";

  var mobile_phone_code = "<?php echo set_value('mobile_phone_code'); ?>";
  var company_phone_code = "<?php echo set_value('company_phone_code'); ?>";
  
  if (country == '') {
    $( "#country" ).val('56-Perú');
  }

  if (mobile_phone_code == '') {
    $( "#mobile_phone_code" ).val('+51');
  }

  if (company_phone_code == '') {
    $( "#company_phone_code" ).val('+51');
  }

  $( "#company_logo" ).change(function(){

    if (this.files && this.files[0]) {

      var maxAllowedSize = 2 * 1024 * 1024;
      if (this.files[0].size > maxAllowedSize) {
        toastr["error"]("El logo debe ser máximo 2MB");
        this.value = ''
      }
    }
  });

  $( "#country" ).change(function() {
    $( ".city_err" ).remove();

    var select2 = $( "#city_dropdown" ).next(".select2-container");
    select2.closest('div').removeClass( 'has-error' );

    $( "#city_text" ).hide().prop('disabled', true);
    $( "#city_dropdown" ).prop('disabled', true);
    
    if ($(this).val() == 'Perú') { 
      $( "#city_text" ).val("");
      $( "#city_dropdown" ).prop('disabled', false);
      select2.show();
    } else {
      $( "#city_text" ).show().prop('disabled', false);
      select2.hide();
    }
  });

  function searchCompany(ruc) {

    $( "#company_name" ).val("");
    $( "#search-company" ).prop('disabled', true);
    $( "#search-company" ).text("Buscando...");

    var url = "<?php echo site_url('employer_signup/search_company'); ?>";
    $.ajax({
      type: 'GET',
      data: {
        ruc: ruc
      },
      url: url,
      dataType: 'json',
      success: function(response) { 
        var company = response.data;

        if (response.success) {
          $( "#company_name" ).val(company.name);
          $( "#company_name" ).prop('readonly', true);
          $( "#company_ruc" ).prop('readonly', true);
          $( "#search-company" ).data('search', '0');
          $( "#search-company" ).text("Cambiar");
          $( ".company_err" ).remove();
          $( "#company_name" ).blur();
        } else {
          $( "#search-company" ).text("Verificar");
          toastr["error"](response.message);
        }
      }
    })
    .fail(function() {
      $( "#search-company" ).text("Verificar");
    }).always(function() {
      $( "#search-company" ).prop('disabled', false);
    });  
  }

  $( "#search-company" ).click(function() {

    if ($(this).data('search') == '1') {
      searchCompany($( "#company_ruc" ).val());
    } else {
      $( "#company_name" ).val("");
      $( "#company_name" ).prop('readonly', true);
      $( "#company_ruc" ).val("");
      $( "#company_ruc" ).prop('readonly', false);
      $(this).data('search', '1');
      $(this).text('Verificar');
    }
  });

  $( "#city_dropdown" ).select2();
  $( "#country" ).change();

});
</script>
</body>
</html>