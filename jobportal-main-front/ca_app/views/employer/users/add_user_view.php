<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
    <link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
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
    
      <div class="col-md-9"> <?php echo $this->session->flashdata('msg');?> 
        
        <div class="formwraper">
          <div class="titlehead">
            <a href="<?php echo site_url('employer/users/list_users/search'); ?>" style="color:#fff;">
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            Agregar usuario
          </div>
          <div class="row"> <?php echo form_open_multipart('employer/users/add_user',array('onSubmit' => ''));?>
            <div class="col-md-12">
              <div class="formint">
                <div class="info-required">
                  <span>*</span> Campos obligatorios 
                </div>
                <div class="input-group <?php echo (form_error('email'))?'has-error':'';?>">
                  <label class="input-group-addon">Email <span>*</span></label>
                  <input name="email" type="text" class="form-control" id="employer_email" value="<?php echo set_value('email'); ?>">
                  <?php echo form_error('email'); ?>
                </div>

                <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
                  <label class="input-group-addon">Nombre <span>*</span></label>
                  <input name="full_name" type="text" class="form-control" id="employer_full_name" value="<?php echo set_value('full_name'); ?>">
                  <?php echo form_error('full_name'); ?>
                </div>

                <div class="input-group <?php echo (form_error('position')) ? 'has-error':'';?>">
                  <label class="input-group-addon">Cargo<span></span></label>
                  <input name="position" type="text" class="form-control" id="charge_name" value="<?php echo set_value('position'); ?>">
                  <?php echo form_error('position'); ?>
                </div>

                <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
                  <label class="input-group-addon">País <span>*</span></label>
                  <select name="country" id="employer_country" class="form-control" style="width:50%">
                    <option value="">Seleccione</option>
                    <?php 
                      foreach($result_countries as $row_country):
                      $selected = (set_value('country')==$row_country->country_name)?'selected="selected"':'';
                    ?>
                    <option value="<?php echo $row_country->country_name;?>" <?php echo $selected;?>><?php echo $row_country->country_name;?></option>
                    <?php endforeach;?>
                  </select>
                  <?php echo form_error('country'); ?>
                </div>

                <div class="input-group <?php echo (form_error('city'))?'has-error':'';?>">
                  <label class="input-group-addon">Ubicación / Ciudad <span>*</span></label>
                  <input name="city" type="text" class="form-control" id="employer_location" value="<?php echo set_value('city'); ?>" autocomplete="off" />
                  <?php echo form_error('city'); ?>
                </div>

                <div class="input-group <?php echo (form_error('mobile_phone'))?'has-error':'';?>">
                  <label class="input-group-addon">Teléfono móvil <span>*</span></label>
                  <table>
                    <tr>
                      <td width="15%">
                        <select id="employer_mobile_phone_code" name="mobile_phone_code" class="form-control">
                          <?php 
                            foreach($result_countries as $row_country):
                              $country_phone_code = $row_country->phone_code;
                              $selected = (set_value('mobile_phone_code') == $country_phone_code) ? 'selected="selected"' : '';
                          ?>
                          <option value="<?php echo $country_phone_code; ?>" <?php echo $selected;?>><?php echo $row_country->country_name . " (+" . $country_phone_code . ")"; ?></option>
                          <?php endforeach ?>
                        </select>     
                      </td>
                      <td>
                      <input name="mobile_phone" type="text" class="form-control" id="employer_mobile_phone" value="<?php echo set_value('mobile_phone'); ?>" />
                      </td>
                    </tr>
                  </table>
                  <?php echo form_error('mobile_phone'); ?>
                </div>

                <div class="input-group <?php echo (form_error('home_phone'))?'has-error':'';?>">
                  <label class="input-group-addon">Teléfono fijo <span></span></label>
                  <table>
                    <tr>
                      <td width="10%">
                        <select id="employer_home_phone_code" name="home_phone_code" class="form-control">
                          <?php 
                            foreach($result_countries as $row_country):
                              $country_phone_code = $row_country->phone_code;
                              $selected = (set_value('home_phone_code') == $country_phone_code) ? 'selected="selected"' : '';
                          ?>
                          <option value="<?php echo $country_phone_code; ?>" <?php echo $selected;?>><?php echo $row_country->country_name . " (+" . $country_phone_code . ")"; ?></option>
                          <?php endforeach ?>
                        </select>     
                      </td>
                      <td>
                      <input type="text" class="form-control" name="home_phone" id="employer_home_phone" value="<?php echo set_value('home_phone'); ?>" />
                      </td>
                    </tr>
                  </table>
                  <?php echo form_error('home_phone'); ?>
                </div>                
                <div align="center">
                  <input type="submit" name="submit_button" id="submit_button" value="Guardar" class="btn btn-primary" />
                </div>
              </div>
            </div>
            <?php echo form_close();?>
          
          </div>
        </div>
      </div>
   
    </div>
  </div>
  <?php $this->load->view('common/bottom_ads');?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
  <?php $this->load->view('common/before_body_close'); ?>
  <script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script> 
  <script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>  

  <script type="text/javascript">
    $(document).ready(function() {

      //Search suggestions ubigeos
      $( "#employer_location" ).autocomplete({
        source: function(request, response) {
          $.getJSON(baseUrl + "/ubigeos/search_suggestions",{
            term: request.term,
            country: $( "#employer_country" ).val() 
          }, response);
        },
        minLength: 0,
      });

      var country = "<?php echo set_value('country'); ?>";
      var mobile_phone_code = "<?php echo set_value('mobile_phone_code'); ?>";
      var home_phone_code = "<?php echo set_value('home_phone_code'); ?>";

      if (country == '') {
        $( '#employer_country' ).val('Perú');
      }

      if (home_phone_code == '') {
        $( '#employer_home_phone_code' ).val('51');
      }

      if (mobile_phone_code == '') {
        $( '#employer_mobile_phone_code' ).val('51');
      }
      
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