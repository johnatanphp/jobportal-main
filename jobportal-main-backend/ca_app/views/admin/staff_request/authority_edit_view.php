<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
<link rel="stylesheet" href="<?php echo base_url('public/css/select2/select2.min.css'); ?>">

<style type="text/css">
  
  .sub-title-h3 {
    font-size: 16px;
    padding: 8px 2px;
    text-transform: uppercase;
    border-bottom: 1px solid #888;
    margin: 0px 0 20px 0px;
    display: block;
  }

  .errowbox {
    color: #f56954;
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
    <h1>Gestionar Autoridades
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('admin/staff_request_authorities');?>">Gestionar autoridades</a></li>
      <li class="active">Editar autoridad</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- title row -->
    <div class="row">
      <?php if(validation_errors() != false):?>
        <div class="message-container">
        	<div class="callout callout-danger">
            <h4>¡Por favor verifica algunos datos del formulario!</h4>
          </div>
        </div>
      <?php endif;?>
      
      <div class="col-md-8"> 
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title" style="display: block;float:none;">
              Editar autoridad
            </h3>
          </div>
          
          <!-- /.box-header --> 
          <!-- form start -->
          <?php echo form_open('admin/staff_request_authorities/edit/' . $authority->ID); ?>
            <div class="box-body">
              
              <div class="formint">
                <h3 class="sub-title-h3">Datos de la autoridad <span> </span></h3>
                <div class="form-group <?php echo (form_error('authority_name'))?'has-error':'';?>">
                  <label >Nombres y apellidos <span>*</span></label>
                  <input name="authority_name" type="authority_name" class="form-control" placeholder="Nombre y apellido" value="<?php echo set_value('authority_name') ? set_value('authority_name') : $authority->name; ?>">
                  <?php echo form_error('authority_name'); ?>
                </div>
                
                <div class="form-group <?php echo (form_error('authority_email'))?'has-error':'';?>">
                  <label >Email <span>*</span></label>
                  <input name="authority_email" type="text" class="form-control" placeholder="Email" value="<?php echo set_value('authority_email') ? set_value('authority_email') : $authority->email; ?>">
                  <?php echo form_error('authority_email'); ?>
                </div>
                <?php if ($authority->type_authority_ID == 1): ?>
                  <div class="form-group <?php echo (form_error('authority_business_unit')) ? 'has-error':'';?>">
                      <label >Unidad de negocio <span>*</span></label>
                      <select id="authority-business-unit" name="authority_business_unit" class="form-control">
                        <option value="">Unidad de Negocio</option>
                       
                        <?php foreach ($business_units as $row): ?>
                          <option value="<?php echo $row->ID; ?>" <?php echo $row->ID == $authority->business_unit_ID ? "selected" : ""; ?>>
                              <?php echo $row->business_unit_name; ?>
                          </option>
                        <?php endforeach; ?>

                      </select>
                    <?php echo form_error('authority_email'); ?>
                  </div>
                <?php endif; ?>

                <div class="form-group <?php echo (form_error('authority_status')) ? 'has-error':'';?>">
                    <label >Estado <span>*</span></label>
                    <select name="authority_status" class="form-control">
                      <option value="1" <?php echo $authority->active == '1' ? 'selected="selected"' : '';?>>Activo</option>
                      <option value="0" <?php echo $authority->active == '0' ? 'selected="selected"' : '';?>>
                        Inactivo
                      </option>
                    </select>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer" style="text-align: center;padding-top: 30px;">
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
          <?php echo form_close(); ?>
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
