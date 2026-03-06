<!DOCTYPE html>
<html lang="ES_PE">
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
<div class="container detailinfo">
  <div class="row">
    <div class="col-md-12"><!--Job Detail-->
      <div class="boxwraper">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-12">Verificación completada</div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="companydescription">
          <div class="row">
            <div class="col-md-12" align="center">
              <h4 style="color: #008216;">
                <b>¡Cuenta empresa ha sido activada con éxito!</b>
              </h4>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!--/Job Detail--> 
    
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
</body>
</html>