<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
<style type="text/css">
  .box-title {
    font-size: 17px !important;
    line-height: 1.5;
  }
</style>
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
    <h1> Gestionar autoridades
      <!--<small>advanced tables</small>--> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Gestionar autoridades</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- title row -->
    <div class="row">
      <div class="col-md-12"> 
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
            <h2 class="box-title">
             Tipos de autoridades 
            </h2>
          </div>
          <!-- /.box-header -->    
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <label>
                    Empresa
                  </label>
                  <select id="company-id" class="form-control" style="min-width:100%;">
                    <option value="">Seleccione</option>
                    <?php foreach ($companies as $row): ?>
                      <option value="<?php echo $row->ID;  ?>" <?php echo $row->ID == $company_id ? "selected" : ""; ?>>
                        <?php echo $row->company_ruc . ' - ' . $row->company_name;  ?>    
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <br>
              
              <?php if ($company_id > 0): ?>
                <div class="row">
                  <div class="col-md-8"> 
                    <div class="list-group">
                      <a href="<?php echo site_url('admin/staff_request_authorities/' . $company_id .'/1'); ?>" class="list-group-item">APROBADOR DE UNIDAD DE NEGOCIO</a>
                      <a href="<?php echo site_url('admin/staff_request_authorities/' . $company_id .'/2'); ?>" class="list-group-item">GERENTE ADMINISTRATIVO</a>
                      <a href="<?php echo site_url('admin/staff_request_authorities/' . $company_id .'/4'); ?>" class="list-group-item">
                        GERENTE DE GESTIÓN DE DESARROLLO HUMANO 
                      </a>
                      <a href="<?php echo site_url('admin/staff_request_authorities/' . $company_id .'/3'); ?>" class="list-group-item">GERENTE / JEFE DE AREA</a>
                    </div> 
                  </div>
                  </div>
                </div>  
              <?php endif; ?>

            </div>
            <!-- /.box-body -->
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
<script>
  $(function(){

    $( '#company-id' ).change(function(){
        window.location = "<?php echo site_url('admin/staff_request_authorities/'); ?>" + $(this).val();
    });
  });
</script>
