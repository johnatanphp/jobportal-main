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

  #table-alert-emails {
    margin-top: 25px;
  }

  #table-alert-emails th {
    font-weight: bold;
    font-size: 15px;
    padding: 6px;
    background: #ddd;
  }

  #table-alert-emails td {
    font-size: 14px;
    padding: 5px;
  }

  #table-alert-emails tr:nth-child(even) {
    background-color: #eee;
  }

  #table-authorities tr:nth-child(odd) {
    background-color: #fff;
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
      Gestion alertas - MOF
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('admin/mofs');?>"><i class="fa fa-dashboard"></i> MOF</a></li>
      <li class="active">Gestion alertas</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- title row -->
    <div class="row">
      <?php if ($this->session->flashdata('save_action') == true): ?>
        <div class="message-container">
          <div class="callout callout-success">
            <h4>¡Los datos han sido guardados con éxito!</h4>
          </div>
        </div>
      <?php endif; ?>
      <div class="col-md-12"> 
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
            <h2 class="box-title">
                Alertas MOF
            </h2>
          </div>
          
          <!-- /.box-header --> 
          <!-- form start -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                    <?php echo form_open('admin/mofs/save_alert_emails'); ?>
                        <div style="padding-bottom: 10px;">
                          <button class="btn btn-sm btn-success pull-right" >
                            Guardar
                          </button>
                          <a id="add-email" 
                             href="#" 
                             class="pull-left" 
                             style="text-decoration: underline;">
                            Agregar
                          </a>
                        </div>
                        <br />
                        <div style="margin-top: 5px;">
                          <table id="table-alert-emails" width="100%">
                            <thead>
                              <tr>
                                <th>Email</th>
                                <th width="5%"></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($alert_emails as $row): ?>
                                <tr>
                                  <td>
                                    <input type="text" 
                                           name="emails[]" 
                                           class="form-control" 
                                           value="<?php echo $row->email; ?>">
                                  </td>
                                  <td>
                                    <button class="btn btn-xs btn-danger"
                                            type="button" 
                                            onclick="$(this).closest('tr').remove();">
                                        Quitar
                                    </button>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                    <?php echo form_close(); ?>
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

    $( "#add-email" ).click(function (){
        $( "#table-alert-emails tbody" ).prepend(`
            <tr>
                <td>
                    <input type="text" class="form-control" name="emails[]">
                </td>
                <td>
                    <button class="btn btn-xs btn-danger"
                            type="button" 
                            onclick="return $(this).closest('tr').remove();">
                        Quitar
                    </button>
                </td>
            </tr>    
        `);
    });
  });
</script>
