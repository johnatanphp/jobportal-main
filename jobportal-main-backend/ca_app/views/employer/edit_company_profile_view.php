<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
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
</style>
<?php $this->load->view('common/before_head_close'); ?>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row">
  <div class="col-md-3">
  <div class="dashiconwrp">
    <?php $this->load->view('employer/common/menu/sidebar');?>
  </div>
  </div>
    
    <div class="col-md-9">
      <?php echo $this->session->flashdata('msg');?> 
      <!--Professional info-->
      <div class="formwraper">
        <div class="titlehead">Información de la empresa</div>
        <div class="row"> <?php echo form_open_multipart('employer/edit_company',array('name' => 'emp_comp_form', 'id' => 'emp_comp_form', 'onSubmit' => 'return validate_employer_company_form(this);'));?>
          <div class="col-md-12">
            <div class="formint">
              <div class="info-required">
                <span>*</span> Campos obligatorios 
              </div>
              <div class="input-group <?php echo (form_error('company_ruc'))?'has-error':'';?>">
                <label class="input-group-addon">RUC de la empresa <span>*</span></label>
                <input name="company_ruc" type="text" class="form-control" id="company_ruc" value="<?php echo $company_ruc; ?>" maxlength="50" />
                <?php echo form_error('company_ruc'); ?>
              </div>
              <div class="input-group <?php echo (form_error('company_name'))?'has-error':'';?>">
                <label class="input-group-addon">Nombre de la empresa <span>*</span></label>
                <input name="company_name" type="text" class="form-control" id="company_name" value="<?php echo $company_name; ?>" maxlength="50" />
                <?php echo form_error('company_name'); ?>
              </div>
              
              <div class="input-group <?php echo (form_error('industry_id'))?'has-error':'';?>">
                <label class="input-group-addon">Industria <span>*</span></label>
                <select name="industry_id" id="industry_id" class="form-control" style="max-width:350px;">
                  <option value="" selected>Seleccione</option>
                  <?php foreach($result_industries as $row_industry):
				  			$selected = ($industry_id==$row_industry->ID)?'selected="selected"':'';
				  ?>
                  <option value="<?php echo $row_industry->ID;?>" <?php echo $selected;?>><?php echo $row_industry->industry_name;?></option>
                  <?php endforeach;?>
                </select>
                <?php echo form_error('industry_id'); ?> </div>
              <div class="input-group <?php echo (form_error('ownership_type'))?'has-error':'';?>">
                <label class="input-group-addon">Tipo de organización </label>
                <select class="form-control" name="ownership_type" id="ownership_type">
                  <option value="Private" <?php echo ($ownership_type=='Private')?'selected="selected"':'';?>>Privada</option>
                  <option value="Public" <?php echo ($ownership_type=='Public')?'selected="selected"':'';?>>Pública</option>
                  <option value="Government" <?php echo ($ownership_type=='Government')?'selected="selected"':'';?>>Gubernamental</option>
                  <option value="Semi-Government" <?php echo ($ownership_type=='Semi-Government')?'selected="selected"':'';?>>Semi gubernamental</option>
                  <option value="NGO" <?php echo ($ownership_type=='NGO')?'selected="selected"':'';?>>O.N.G</option>
                </select>
                <?php echo form_error('ownership_type'); ?> </div>
              <div class="input-group <?php echo (form_error('company_location'))?'has-error':'';?>">
                <label class="input-group-addon">Dirección <span>*</span></label>
                <textarea class="form-control" name="company_location" id="company_location" ><?php echo $company_location; ?></textarea>
                <?php echo form_error('company_location'); ?> </div>
              <div class="input-group <?php echo (form_error('company_country'))?'has-error':'';?>">
                <label class="input-group-addon">País / Ubicación <span>*</span></label>
                <select name="company_country" id="country" class="form-control"  style="width:50%">
                  <?php 
					foreach($result_countries as $row_country):
						$selected = ($company_country==$row_country->country_name)?'selected="selected"':'';
						
				?>
                  <option value="<?php echo  $row_country->ID . '-' . $row_country->country_name;?>" <?php echo $selected;?>><?php echo $row_country->country_name;?></option>
                  <?php endforeach;?>
                </select>
                <?php echo form_error('company_country'); ?>
               
                <div class="demo">
                  
                
                <input name="company_city" type="text" class="form-control" id="city_text" style="max-width:165px;" value="<?php echo $company_city; ?>" maxlength="50"></div>
                <?php echo form_error('company_city'); ?> </div>
              <div class="input-group <?php echo (form_error('company_phone'))?'has-error':'';?>">
                <label class="input-group-addon">Teléfono fijo <span>*</span></label>
                <input type="phone" class="form-control" name="company_phone" id="company_phone" value="<?php echo $company_phone; ?>" maxlength="20" />
                <?php echo form_error('company_phone'); ?> </div>
              
              <div class="input-group <?php echo (form_error('company_website'))?'has-error':'';?>">
                <label class="input-group-addon">Sitio web de la empresa <span>*</span></label>
                <input name="company_website" type="text" class="form-control" id="company_website" value="<?php echo $company_website; ?>" maxlength="155">
                <?php echo form_error('company_website'); ?> </div>
              <div class="input-group <?php echo (form_error('no_of_employees'))?'has-error':'';?>">
                <label class="input-group-addon">N° de empleados <span>*</span></label>
                <select name="no_of_employees" id="no_of_employees" class="form-control">
                  <option value="1-10" <?php echo ($no_of_employees=='1-10')?'selected':''; ?>>1-10</option>
                  <option value="11-50" <?php echo ($no_of_employees=='11-50')?'selected':''; ?>>11-50</option>
                  <option value="51-100" <?php echo ($no_of_employees=='51-100')?'selected':''; ?>>51-100</option>
                  <option value="101-300" <?php echo ($no_of_employees=='101-300')?'selected':''; ?>>101-300</option>
                  <option value="301-600" <?php echo ($no_of_employees=='301-600')?'selected':''; ?>>301-600</option>
                  <option value="601-1000" <?php echo ($no_of_employees=='601-1000')?'selected':''; ?>>601-1000</option>
                  <option value="1001-1500" <?php echo ($no_of_employees=='1001-1500')?'selected':''; ?>>1001-1500</option>
                  <option value="1501-2000" <?php echo ($no_of_employees=='1501-2000')?'selected':''; ?>>1501-2000</option>
                  <option value="More than 2000" <?php echo ($no_of_employees=='More than 2000')?'selected':''; ?>>Más de 2000</option>
                </select>
                <?php echo form_error('no_of_employees'); ?> </div>
              <div class="input-group <?php echo (form_error('company_description'))?'has-error':'';?>">
                <label class="input-group-addon">Descripción de la empresa <span>*</span></label>
                <textarea class="form-control" name="company_description" id="company_description" rows="8" cols="30" ><?php echo $company_description; ?></textarea>
                <?php echo form_error('company_description'); ?> </div>
              <div align="center">
                <input type="submit" name="submit_button" id="submit_button" value="Actualizar" class="btn btn-primary" />
              </div>
            </div>
          </div>
          <?php echo form_close();?>
        </div>
      </div>
    </div>
    <!--/Job Detail-->
    
    
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script> 
<script type="text/javascript"> var cy = '<?php echo $company_country;?>'; </script> 
<script type="text/javascript">
$(document).ready(function(){
	
  $(".fa-upload").click(function(){
	  $("#upload_logo").click();
  });

  $("#upload_logo").change(function(){
	  ext_array = ['png','jpg','jpeg','gif'];	
	  var ext = $('#upload_logo').val().split('.').pop().toLowerCase();
	  if($.inArray(ext, ext_array) == -1) {
		  alert('Invalid file provided!');
		  return false;
	  }
	 this.form.submit();
  });

});
</script>
</body>
</html>