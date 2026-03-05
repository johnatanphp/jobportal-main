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
</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php 
  $this->load->view('common/header'); 
?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row"> 
    <?php echo form_open_multipart('jobseeker_update_entry', ['name' => 'account_form', 'id' => 'account_form', 'onSubmit' => 'return validate_account_form(this);']);?>
    <input type="hidden" name="t" value="<?php echo $token; ?>">    
    <div class="col-md-3"></div>
    
    <div class="col-md-9">  
    
      <?php echo $this->session->flashdata('msg');?>
  
      <!--Personal info-->
      <div class="formwraper">
        <div class="titlehead">Actualizar datos</div>
        <div class="formint">
          <div class="alert alert-info" style="padding: 10px 8px;">
            Hola <b><?php e($row->first_name); ?></b>, por favor actualiza tus datos para que podamos brindarte mejores oportunidades de empleos y puedas trabajar con nosostros.
          </div>

          <h3 class="sub-title-h3">Datos Personales</h3>
          <div class="info-required">
            <span>*</span> Campos obligatorios
          </div>
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
              <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(date('M', strtotime($dummy_date)));?></option>
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
            <label class="input-group-addon">Estado civil<span>*</span></label>
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
              <?php foreach($result_countries as $row_country): 
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
          <div class="input-group <?php echo (form_error('mobile'))?'has-error':'';?>">
            <label class="input-group-addon">Teléfono móvil <span>*</span></label>
            <?php 
              $mobile_parts = explode(' ', $row->mobile);
              $count_mobile_parts = count($mobile_parts);

              $mobile_phone_code = '';
              $mobile = '';

              if ($count_mobile_parts == 3) {
                $mobile_phone_code = $mobile_parts[0] . ' ' . $mobile_parts[1];
                $mobile = $mobile_parts[2];
              } elseif ($count_mobile_parts == 2)  {
                $mobile_phone_code = $mobile_parts[0];
                $mobile = $mobile_parts[1];
              } else {
                $mobile = $row->mobile; 
              }
            ?>
            <table width="100%">
              <tr>
                <td width="150">
                  <select id="mobile_phone_code" name="mobile_phone_code" class="form-control" style="width:150px;">
                    <?php 
                      foreach ($result_countries as $row_country):
                        if (empty($row_country->phone_code)) {
                          continue;
                        }
                      $country_phone_code = '+' . $row_country->phone_code;
                      $selected = ($mobile_phone_code == $country_phone_code) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $country_phone_code; ?>" <?php echo $selected;?>><?php echo $row_country->country_name . " (" . $country_phone_code . ")"; ?></option>
                    <?php endforeach ?>
                  </select>     
                </td>
                <td>
                  <input name="mobile" type="text" class="form-control" id="mobile" value="<?php echo $mobile; ?>" maxlength="15" />
                </td>
              </tr>
            </table>
            <?php echo form_error('mobile'); ?> </div>
          <div class="input-group">
            <label class="input-group-addon">Teléfono residencial</label>
            <?php 
              $home_phone_parts = explode(' ', $row->home_phone);
              $count_hp_parts = count($home_phone_parts);

              $home_phone_code = '';
              $home_phone = '';

              if ($count_hp_parts == 3) {
                $home_phone_code = $home_phone_parts[0] . ' ' . $home_phone_parts[1];
                $home_phone = $home_phone_parts[2];
              } elseif ($count_hp_parts == 2)  {
                $home_phone_code = $home_phone_parts[0];
                $home_phone = $home_phone_parts[1];
              } else {
                $home_phone = $row->home_phone; 
              }
            ?>
            <table width="100%">
              <tr>
                <td width="150">
                  <select id="home_phone_code" name="home_phone_code" class="form-control" style="width:150px;">
                    <?php 
                      foreach($result_countries as $row_country):
                        if (empty($row_country->phone_code)) {
                          continue;
                        }
                        $country_phone_code = '+' . $row_country->phone_code;
                        $selected = ($home_phone_code == $country_phone_code) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $country_phone_code; ?>" <?php echo $selected;?>><?php echo $row_country->country_name . " (" . $country_phone_code . ")"; ?></option>
                    <?php endforeach ?>
                  </select>     
                </td>
                <td>
                  <input name="home_phone" type="text" class="form-control" id="home_phone" value="<?php echo $home_phone; ?>" maxlength="20">
                </td>
              </tr>
            </table>
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
          <h3 class="sub-title-h3" style="margin-top: 25px;">Ubicación</h3>
          <div class="info-required">
            <span>*</span> Campos obligatorios
          </div>
         <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
            <label class="input-group-addon">País<span>*</span></label>
            <select name="country" id="country" class="form-control" style="width:50%">
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
          <div class="input-group <?php echo (form_error('city'))?'has-error':'';?>">
            <label class="input-group-addon">Ubicación / Ciudad <span> *</span></label>
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

          <div class="input-group <?php echo (form_error('current_address'))?'has-error':'';?>">
            <label class="input-group-addon">Dirección<span>*</span></label>
            <textarea class="form-control" name="present_address" id="present_address" ><?php echo $row->present_address; ?></textarea>
            <?php echo form_error('current_address'); ?>
          </div>
          
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
            <input type="submit" 
                   name="submit_button" 
                   id="submit_button" 
                   value="Guardar" class="btn btn-primary" />
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
<script src="<?php echo base_url('public/js/validate_jobseeker.js?t=1640097923');?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script>  

<script type="text/javascript">
$(document).ready(function(){

  //Valores por defecto
  var country = "<?php echo $row->country; ?>";
  var nationality = "<?php echo $row->nationality; ?>";
  var mobile_phone_code = "<?php echo $mobile_phone_code; ?>";
  var home_phone_code = "<?php echo $home_phone_code; ?>";

  if (country == '') {
    $( "#country" ).val('56');
  }

  if (nationality == '') {
    $( "#nationality" ).val('56');
  }

  if (mobile_phone_code == '') {
    $( "#mobile_phone_code" ).val('+51');
  }

  if (home_phone_code == '') {
    $( "#home_phone_code" ).val('+51');
  }

  $( "#check_disability" ).change(function(){  
    $( "#disability" ).hide();

    if ($(this).is(':checked')) {
      $( "#disability" ).show();
    }
  });

  $( "#country" ).change(function() {
    $( ".city_err" ).remove();

    var select2 = $( "#city_dropdown" ).next(".select2-container");
    select2.closest('div').removeClass( 'has-error' );

    $( "#city_text" ).hide().prop('disabled', true);
    $( "#city_dropdown" ).prop('disabled', true);
    
    if ($(this).val() == '56') { 
      $( "#city_text" ).val("");
      $( "#city_dropdown" ).prop('disabled', false);
      select2.show();
    } else {
      $( "#city_text" ).show().prop('disabled', false);
      select2.hide();
    }
  });

  $( "#city_dropdown" ).select2();
  $( "#check_disability" ).change();
  $( "#country" ).change();
});
</script>
</body>
</html>
