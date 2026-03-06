<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header--> 
<!--Detail Info-->
<div class="container innerpages">
 
 <?php $this->load->view('common/bottom_ads');?>
  <div class="row"> 
    
    <!--Signup-->
    <div class="col-md-6 col-md-offset-3">
    <!--Login-->
    <form name="forgot_form" id="forgot_form" action="" method="post">
      <div class="loginbox">
        <h3>Recuperar cuenta</h3>
        <?php if ($msg):?>
          <div class="alert alert-danger">
            <?php echo $msg;?>    
          </div>
        <?php endif;?>
        <?php echo validation_errors(); ?> <?php echo $this->session->flashdata('msg');?>
        <div class="row">
          <div class="col-md-3">
            <label class="input-group-addon">Email <span>*</span></label>
          </div>
          <div class="col-md-9">
            <input type="text" name="email" id="email" class="form-control" value="<?php echo set_value('email'); ?>" placeholder="Email" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <label class="input-group-addon">Tipo de usuario <span>*</span></label>
          </div>
          <div class="col-md-9">
            <select name="user_type" class="form-control">
              <option value="1" <?php echo set_value('user_type') == '1' || $user_type == '1' ? 'selected="selected"' : ''; ?>>Postulante</option>
              <option value="2" <?php echo set_value('user_type') == '2' || $user_type == '2' ? 'selected="selected"' : ''; ?>>Cuenta empresarial</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <label class="input-group-addon"></label>
          </div>
          <div class="col-md-9">
            <div class="input-group">
              <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('google_recaptcha_api_site_key'); ?>"></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" style="text-align:center;">
            <input type="submit" value="Recuperar cuenta" class="btn btn-primary" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" style="text-align:center;">¿Eres miembro? 
              <?php 
                $url_login_list = [
                    '1' => site_url('login'),
                    '2' => site_url('employer-login'),
                ];            
              ?>
              <a href="<?php echo $url_login_list[$user_type] ?? '#'; ?>">Iniciar sesión</a>
          </div>
        </div>
      </div>
    </form>
    <!--/Login-->

    </div>
    <!--/Signup-->   
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
  $(function(){
    $( 'select[name="user_type"]').change(function(){
      window.location = "?user_type=" + $(this).val();
    });
  });
</script>
</body>
</html>