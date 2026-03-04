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

  #table-authorities {
    margin-top: 25px;
  }

  #table-authorities th {
    font-weight: bold;
    font-size: 15px;
    padding: 6px;
    background: #ddd;
  }

  #table-authorities td {
    font-size: 14px;
    padding: 5px;
  }

  #table-authorities tr:nth-child(even) {
    background-color: #eee;
  }

  #table-authorities tr:nth-child(odd) {
    background-color: #fff;
  }

  .btn-delete-authority {
    margin:0;
    padding: 0;
    border:0;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    background: #e76767;
    color: #fff;
    font-size: 10px;
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
      <a href="<?php echo base_url('admin/staff_request_authorities'); ?>" style="color:#fff;margin-right: 5px;">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
      </a> 
      Gestionar autoridades
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
      <?php if (validation_errors() != false):?>
      <div class="message-container">
        <div class="callout callout-danger">
          <h4>¡Por favor verifique los datos del formulario!</h4>
        </div>
      </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('add_action') == true): ?>
        <div class="message-container">
          <div class="callout callout-success">
            <h4>¡La autoridad ha sido agregada con éxito!</h4>
          </div>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('update_action') == true): ?>
        <div class="message-container">
          <div class="callout callout-success">
            <h4>¡La autoridad ha sido actualizada con éxito!</h4>
          </div>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('delete_action') == true): ?>
        <div class="message-container">
          <div class="callout callout-success">
            <h4>¡La autoridad ha sido eliminada con éxito!</h4>
          </div>
        </div>
      <?php endif; ?>
      <div class="col-md-12"> 
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
            <h2 class="box-title">
              
              <?php if ($authority_type == 1): ?>
                Aprobador de Unidad de Negocio
              <?php endif; ?>

              <?php if ($authority_type == 2): ?>
                Gerente Administrativo
              <?php endif; ?>
              
              <?php if ($authority_type == 3): ?>
                Gerente / Jefe de Area
              <?php endif; ?>

              <?php if ($authority_type == 4): ?>
                GERENTE DE GESTIÓN DE DESARROLLO HUMANO
              <?php endif; ?> 
            </h2>
          </div>
          
          <!-- /.box-header --> 
          <!-- form start -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-9">
                    <div style="padding-bottom: 10px;">
                      <a href="<?php echo site_url('admin/staff_request_authorities/create/' . $company_id . '/' . $authority_type); ?>" class="btn btn-sm btn-primary pull-right" >
                        Nueva autoridad
                      </a>
                    </div>
                    <div style="margin-top: 5px;">
                      <table id="table-authorities" width="100%">
                        <thead>
                          <tr>
                            <th width="5%"></th>
                            <th>Autoridad responsable</th>
                            <th>Email</th>
                            <?php if ($authority_type == 1): ?>
                              <th>Unidad de negocio</th>
                            <?php endif; ?>
                            <th>Estado</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($result_authorities as $row_authority): ?>
                            <tr>
                              <td>
                                <a class="btn-edit-authority js-edit-authority" 
                                   href="<?php echo site_url('admin/staff_request_authorities/edit/' . $row_authority->ID); ?>">
                                  <i class="glyphicon glyphicon-pencil"></i>
                                </a>
                              </td>
                              <td>
                                <?php echo $row_authority->name; ?>
                              </td>
                              <td>
                                <?php echo $row_authority->email; ?>
                              </td>
                              <?php if ($authority_type == 1): //DIRECTOR RESPONSABLE ?>
                                <td><?php echo $row_authority->business_unit_name; ?></td>
                              <?php endif; ?>
                              <td>
                                <?php echo $row_authority->active ? 'Activo' : 'Inactivo'; ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      <?php if (count($result_authorities) == 0): ?>
                        <div style="padding: 5px;">¡Ningún resultado encontrado!</div>
                      <?php endif; ?>
                      </div>
                  </div>
                </div>
              </div>  
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
<script type="text/javascript">
  $(document).ready(function(){

    $( ".btn-delete-authority" ).click(function(){

      if (!confirm("¿Desea eliminar la autoridad?")) {
        return;
      }

      var data = {
        authority_id : $(this).data('authority-id')
      };

      $.post("<?php echo base_url('admin/staff_request_authorities/delete_authority/'); ?>", data, function(){
        window.location.assign(window.location);
      }).fail(function(){
        alert("¡Ha ocurrido un error!");
      });
    });
  });
</script>
