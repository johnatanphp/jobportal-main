<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title;?></title>
  <?php $this->load->view('common/before_head_close'); ?>
  <style type="text/css">
    
    .msg-info {
      font-style: normal;
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
      <?php echo form_open_multipart('candidate/entry_job_seeker/' . $jobseeker->entry_key,array('name' => 'entry_job_seeker_form', 'id' => 'entry_job_seeker_form'));?> 
      <div class="col-md-3">
        <div class="dashiconwrp">
        </div>
      </div>
      
      <div class="col-md-9"> <?php echo $this->session->flashdata('msg');?> 
        
        <!--Account info-->
        <div class="formwraper">
          <div class="titlehead">Ingreso Postulante -  <?php echo $jobseeker->first_name; ?></div>
          <div class="formint">
            <div  class="msg-info" style="padding: 10px 10px;">
              Hola <?php echo $jobseeker->first_name; ?>, necesitamos que configures una contraseña para terminar de crear tu cuenta en el portal,
              y así puedas comenzar a postularte en la gran variedad de empleos que tenemos en Overall.
            </div>
            <br />
            <div>
              <input type="hidden" name="entry_key" value="<?php echo $jobseeker->entry_key; ?>">
            </div>
            <div class="input-group <?php echo (form_error('first_name'))?'has-error':'';?>">
              <label class="input-group-addon">Nombre<span>*</span></label>
              <input name="first_name" type="text" class="form-control" id="first_name" placeholder="Nombre" value="<?php echo $jobseeker->first_name; ?>" maxlength="30">
              <?php echo form_error('first_name'); ?> 
            </div>
         
            <?php 
              @list($p_last_name, $m_last_name) = explode(' ', $jobseeker->last_name);
            ?>
            <div class="input-group <?php echo (form_error('paternal_last_name'))?'has-error':'';?>">
              <label class="input-group-addon">Apellido paterno <span>*</span></label>
              <input name="paternal_last_name" type="text" class="form-control" id="paternal_last_name" placeholder="Apellido paterno" value="<?php echo $p_last_name; ?>" maxlength="30">
              <?php echo form_error('paternal_last_name'); ?> 
            </div>

            <div class="input-group <?php echo (form_error('maternal_last_name'))?'has-error':'';?>">
              <label class="input-group-addon">Apellido materno <span>*</span></label>
              <input name="maternal_last_name" type="test" class="form-control" id="maternal_last_name" placeholder="Apellido materno" value="<?php echo $m_last_name; ?>" maxlength="30">
              <?php echo form_error('maternal_last_name'); ?> 
            </div>
            <div>
              <hr />
            </div>

            <div class="input-group <?php echo (form_error('new_password'))?'has-error':'';?>">
              <label class="input-group-addon">Contraseña <span>*</span></label>
              <input name="new_password" type="password" class="form-control" id="new_password" placeholder="Nueva contraseña" value="<?php echo set_value('password'); ?>" maxlength="100">
              <?php echo form_error('new_password'); ?> 
            </div>
            
            <div class="input-group <?php echo (form_error('confirm_password'))?'has-error':'';?>">
              <label class="input-group-addon">Confirmar contraseña <span>*</span></label>
              <input name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="Confirmar contraseña" value="<?php echo set_value('confirm_password'); ?>" maxlength="100">
              <?php echo form_error('confirm_password'); ?>
            </div>
            
            <div align="center">
              <input type="submit" name="submit_button" id="submit_button" value="Ingresar" class="btn btn-primary" />
            </div>
          </div>
        </div>
      </div>
      <!--/Job Detail--> 
      <?php echo form_close(); ?>
    </div>
  </div>
  <?php $this->load->view('common/bottom_ads');?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <?php $this->load->view('common/before_body_close'); ?>
</body>
</html>
