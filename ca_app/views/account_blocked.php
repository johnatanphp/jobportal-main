<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title>Cuenta suspendida</title>
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
  <div class="row"> 
    <!--Signup-->
    <div class="col-md-5 col-md-offset-4">
      <div class="loginbox">
        <h3>Cuenta bloqueada</h3>
        <br />
        <div class="alert alert-danger">
            Su cuenta está bloqueada, por favor póngase en contacto con el administrador del sitio para obtener más información.
        </div>
        <div class="row">
          <div class="col-md-12">
            <a href="<?php echo base_url('login'); ?>" class="btn btn-primary pull-right">Aceptar</a>
          </div>
        </div>
      </div>
    </div>
    <!--/Login--> 
  </div>
</div>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
</body>
</html>