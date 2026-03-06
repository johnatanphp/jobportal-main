<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
</head>
<body>
<div class="loginwrap">
  <div class="loginfrm">
    <img src="<?php echo base_url('public/images/overall_blue.png');?>" class="mainlogologin">
     <div class="err"><?php echo $msg;?></div>
      <?php echo form_open('', array('name' => 'login_form', 'id' => 'login-form')); ?>    
        <div class="formwrp">
          <label>Usuario</label>
          <input name="username" class="frmfield" id="username" type="text">
          <?php echo form_error('username', '<div class="err"><span>', '</span></div>'); ?>
          <label>Contraseña</label>
          <input name="password" class="frmfield" id="password" type="password">
          <?php echo form_error('password', '<div class="err"><span>', '</span></div>'); ?>
          <div class="logbtnwr">
            <div class="rinput-group">     
              <div class="g-recaptcha"  data-sitekey="<?php echo $this->config->item('google_recaptcha_api_invisible_site_key'); ?>" data-callback="loginSubmit" data-size="invisible">
              </div>
            </div>
            <input value="Login" class="loginbtn" type="submit">
          </div>
        </div>
      <?php echo form_close(); ?>
  </div>
  
  <div class="clearfix"></div>
</div>
<script src="<?php echo base_url('public/js/jquery-3.2.1.min.js'); ?>"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
  
    function loginSubmit() {
      $( "#login-form" )[0].submit();
    }

    $(document).ready(function() {
       $( "#login-form" ).submit(function(e) {
        e.preventDefault();
        if ($.trim($( "#username").val()) != '' && $.trim($( "#password").val()) != '') {
          grecaptcha.execute();
        }
        return false;
       });
    });
</script>
</body>
</html>