<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
<?php $this->load->view('admin/common/datepicker'); ?>
</head>
<body class="skin-blue">
<?php $this->load->view('admin/common/after_body_open'); ?>
<?php $this->load->view('admin/common/header'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
<?php $this->load->view('admin/common/left_side'); ?>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Configuración
      <!--<small>advanced tables</small>--> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('admin/config');?>">Configuración</a></li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- title row -->
    <div class="row">      
      <?php echo $this->session->flashdata('success_msg'); ?>

      <div class="col-md-12"> 
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
           
          </div>
          <!-- /.box-header --> 
          <!-- form start -->
        
        <div class="box-body">
            <div class="row">
                <div class="col-sm-5">
                    <div style="padding: 10px 0px;">
                        <div class="row">
                            <label class="col-md-12">Enviar correo de prueba a:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="email-test" placeholder="Email">    
                            </div>
                            <div class="col-sm-2">
                                <button id="btn-send-email" type="button" class="btn btn-success btn-xs" onclick="sendTestEmail();">Enviar</button>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box --> 
        
      </div>
      <!-- /.col --> 
    </div>
    <!-- info row --> 
    
  </section>
  <!-- /.content --> 
</aside>
<!-- /.right-side -->
<?php $this->load->view('admin/common/footer'); ?>