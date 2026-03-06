<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
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
    <h1> Gestión de beneficios laborales 
      <!--<small>advanced tables</small>--> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <!--<li><a href="#">Examples</a></li>-->
      <li class="active">Beneficios laborales</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
  <?php if($this->session->flashdata('added_action')==true): ?>
      <div class="message-container">
      	<div class="callout callout-success">
        <h4>Beneficio laboral creado exitosamente!</h4>
      </div>
      </div>
      <?php endif;?>
      
      <?php if($this->session->flashdata('update_action')==true): ?>
      <div class="message-container">
      	<div class="callout callout-success">
        <h4>El registro se ha actualizado con éxito.</h4>
      </div>
      </div>
      <?php endif;?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Beneficios laborales</h3>
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
              <div class="col-md-4">
              <?php if ($company_id): ?>
                <div class="text-right" style="padding-bottom:2px;">
                  <input type="button" class="btn btn-primary btn-sm" value="Nuevo" onClick="load_lobor_benefit_add_form();"/>
                </div>
                <br>
                <?php endif; ?>
              </div>
            </div>
            <br>

            <div class=" table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Beneficio laboral</th>
                    <th>Estado</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if($result):
                  foreach($result as $row):?>
                    <tr id="row_<?php echo $row->ID; ?>">
                      <td><?php echo $row->benefit_name;?></td>    
                      <td>
                        <?php echo $row->active ? 'Activo' : 'Inactivo'; ?>
                      </td>
                      <td>
                        <a href="javascript:;" onClick="load_labor_benefit_edit_form(<?php echo $row->ID; ?>)" class="btn btn-success btn-xs">Editar</a> 
                      </td>
                    </tr>
                  <?php endforeach; else:?>
                    <tr>
                      <td colspan="7" align="center" class="text-red">¡Ningún resultado encontrado!</td>
                    </tr>
                  <?php
                    endif;
                  ?>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          
          <!--Pagination-->
          <div class="paginationWrap"> <?php echo ($result) ? $links : '';?> </div>
          
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
        
        <!-- /.box --> 
      </div>
    </div>
  </section>
  <!-- /.content --> 
</aside>
<div class="modal fade" id="add_page_form">
  <div class="modal-dialog">
    <form role="form" method="post" action="<?php echo base_url('admin/laboral_benefits/add');?>" onSubmit="return validate_add_labor_benefit_form(this)">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Nuevo beneficio laboral</h4>
        </div>
        <div class="modal-body"> 
          <!-- /.box-header --> 
          <!-- form start -->
          <div class="box-body">
            <div class="form-group">
              <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
              <input type="text" class="form-control"  id="benefit_name" name="benefit_name" value="<?php echo set_value('benefit_name');?>" placeholder="Beneficio laboral">
              <?php echo form_error('benefit_name'); ?> </div>
          </div>
          
          <!-- /.box-body --> 
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" name="submitter" class="btn btn-primary">Crear</button>
        </div>
      </div>
    </form>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- Edit Model-->
<div class="modal fade" id="edit_page_form">
  <div class="modal-dialog">
    <form role="form" method="post" action="<?php echo base_url('admin/laboral_benefits/update');?>" onSubmit="return validate_edit_labor_benefit_form(this)">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Editar beneficio laboral</h4>
        </div>
        <div class="modal-body"> 
          <!-- /.box-header --> 
          <!-- form start -->
          <div class="box-body">
            <div class="form-group">
              <input type="text" class="form-control"  id="edit_benefit_name" name="edit_benefit_name" value="<?php echo set_value('city_name');?>" placeholder="Beneficio laboral">
              <?php echo form_error('edit_benefit_name'); ?> 
            </div>
            <div class="form-group">
                <select id="edit_benefit_active" name="active" class="form-control">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              <?php echo form_error('active'); ?>
            </div>
          </div>
          
              <input type="hidden" name="benefit_id" id="benefit_id" />
          <!-- /.box-body --> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" name="submitter" class="btn btn-primary">Actualizar</button>
        </div>
      </div>
    </form>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 
<!-- /.right-side -->
<?php $this->load->view('admin/common/footer'); ?>
<?php if(validation_errors() != false){?>
<script type="text/javascript"> 
	$('#add_page_form').modal('show');
</script>
<?php } ?>
<script>
  $(function(){
    $( '#company-id' ).change(function(){
      window.location = "<?php echo site_url('admin/laboral_benefits/'); ?>" + $(this).val();
    });
  });
</script>