<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $title;?></title>
  <?php $this->load->view('admin/common/meta_tags'); ?>
  <?php $this->load->view('admin/common/before_head_close'); ?>
  <style>
      #wrapper-skills {
      padding: 5px;
      margin-top: 5px;
      position: relative;
    }

    #wrapper-skills span {
      padding: 4px 6px;
      background: #eee;
      display: inline-block;
      margin: 6px 3px;
    }

    #wrapper-skills span button {
      background: transparent;
      border: none;
      color: #f63e3e;
      padding: 3px;
      margin: 0;
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
    <h1>
      Gestión de Países 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <!--<li><a href="#">Examples</a></li>-->
      <li class="active">Gestión de Países</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
  <?php if($this->session->flashdata('added_action')==true): ?>
      <div class="message-container">
      	<div class="callout callout-success">
        <h4>El registro ha sido guardado con éxito.</h4>
      </div>
      </div>
      <?php endif;?>
      
      <?php if($this->session->flashdata('update_action')==true): ?>
      <div class="message-container">
      	<div class="callout callout-success">
        <h4>Registro actualizado con éxito.</h4>
      </div>
      </div>
      <?php endif;?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Países</h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <div class="text-right" style="padding-bottom:2px;display:none;">
              <input type="button" class="btn btn-primary btn-sm" value="Agregar" onClick="load_countries_add_form();" />
            </div>

            <?php echo form_open('admin/countries', array('method' => 'get')); ?>
              <table width="100%" style="margin-bottom: 15px;">
                <tr>
                  <td width="90%">
                    <input type="text" name="query" value="<?php echo $filters['query']; ?>" class="form-control" style="min-width: 100%;" placeholder="Buscar país">
                  </td>
                  <td width="10%">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                      <i class="glyphicon glyphicon-search"></i>
                    </button>
                  </td>    
                </tr>
              </table>
            <?php echo form_close(); ?>
          
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>País</th>
                  <th>Nacionalidad</th>
                  <th>ISO 3166-1 alpha2</th>
                  <th>ISO 3166-1 alpha3</th>
                  <th>Dominio</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php 
				if($result):
					foreach($result as $row):?>
                <tr id="row_<?php echo $row->ID;?>">
                  <td><?php echo $row->country_name;?></td>
                  <td><?php echo $row->country_citizen;?></td>
                  <td><?php echo $row->iso_3166_1_alpha2; ?></td>
                  <td><?php echo $row->iso_3166_1_alpha3; ?></td>
                  <td><?php echo $row->domain; ?></td>
                  <td>
                    <a href="javascript:;" onClick="load_countries_edit_form(<?php echo $row->ID;?>);" class="btn btn-success btn-xs">Editar</a> 

                    <div class="dropdown" style="display:inline-block;margin-left:5px;">
                      <button class="btn btn-default btn-xs dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Gestión
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="#" class="manage-job-charges" data-country-id="<?php echo $row->ID; ?>">Grupo ocupacional</a></li>
                        <li><a href="#" class="manage-internal-areas" data-country-id="<?php echo $row->ID; ?>">Áreas pertenecientes</a></li>
                        <li><a href="#" class="manage-work-experiences" data-country-id="<?php echo $row->ID; ?>">Experiencias laborales</a></li>
                        <li><a href="#" class="manage-level-studies" data-country-id="<?php echo $row->ID; ?>">Grado de estudios</a></li>
                        <li><a href="#" class="manage-risk-criteria" data-country-id="<?php echo $row->ID; ?>">Criterio de riesgo</a></li>
                        <li><a href="#" class="manage-rys-documents" data-country-id="<?php echo $row->ID; ?>">Documentos del reclutamiento</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
                <?php endforeach; else:?>
                <tr>
                  <td colspan="6" align="center" class="text-red">No Record found!</td>
                </tr>
                <?php
					endif;
				?>
              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
          
          <!--Pagination-->
          <div class="paginationWrap"> <?php echo ($result)?$links:'';?> </div>
          
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
        
        <!-- /.box --> 
      </div>
    </div>
  </section>
  <!-- /.content --> 
</aside>  

<!-- /.right-side -->
<?php $this->load->view('admin/common/footer'); ?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

<?php $this->load->view('admin/countries/partials/modal_country_add'); ?>
<?php $this->load->view('admin/countries/partials/modal_country_edit'); ?>
<?php $this->load->view('admin/job_charges/partials/modal_job_charges_list'); ?>
<?php $this->load->view('admin/internal_areas/partials/modal_internal_areas_list'); ?>
<?php $this->load->view('admin/work_experiences/partials/modal_work_experiences_list'); ?>
<?php $this->load->view('admin/risk_criteria/partials/modal_risk_criteria_list'); ?>
<?php $this->load->view('admin/level_studies/partials/modal_level_studies_list'); ?>
<?php $this->load->view('admin/recruitment_documents/common/modal_documents_list'); ?>

<?php if(validation_errors() != false){?>
  <script type="text/javascript"> 
    $('#add_page_form').modal('show');
  </script>
<?php } ?>
