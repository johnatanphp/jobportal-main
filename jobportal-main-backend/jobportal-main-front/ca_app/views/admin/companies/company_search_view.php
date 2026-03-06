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
    <h1> Companías
      <!--<small>advanced tables</small>--> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <!--<li><a href="#">Examples</a></li>-->
      <li class="active">Companías</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Listado de compañías</h3>
            <!--Pagination-->
            <div class="paginationWrap"> <?php echo ($result) ? $links : ''; ?> </div>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <form method="get" action="<?php echo site_url('admin/companies'); ?>">
              <div class="row" style="background-color:#3C8DBC; padding:10px; margin:0;">
            
                <div class="col-md-8 margin-bottom-special">
                  <input class="form-control" 
                         name="query" 
                         type="text" 
                         placeholder="Buscar" 
                         value="<?php echo html_escape($filter['query']); ?>" 
                         style="min-width: 100%;">
                </div>

                <div class="col-md-4 margin-bottom-special">
                  <input class="btn" name="submit" value="Buscar" type="submit">
                  &nbsp;&nbsp;
                  <input class="btn" name="button" value="Ver todos" type="button" onClick="document.location='<?php echo site_url('admin/companies');?>';">
                </div>
              </div>
            </form>

            <div class="clearfix">&nbsp;</div>
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Compañía</th>
                  <th>RUC</th>
                  <th>Teléfono</th>
                  <th>Admin</th>
                  <th>Área</th>
                  <th>Usuarios</th>
                  <th>Empleos</th>
                  <th>Sitio web</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($result as $row): ?> 
                  <tr id="row_<?php echo $row->ID;?>">
                    <td align="center">
                      <img src="<?php echo img_pic_company($row->company_logo, 'thumb'); ?>" /><br />
                      <?php echo $row->company_name; ?>
                    </td>
                    <td><?php echo $row->company_ruc; ?></td>
                    <td><?php echo $row->company_phone;?></td>
                    <td><?php echo $row->admin_users;?></td>
                    <td><?php echo $row->industry_name; ?> </td>
                    <td>
                      <a class="btn btn-primary btn-xs" 
                          href="<?php echo site_url('admin/employers/search_by_company/' . $row->ID); ?>" 
                          target="_blank">
                          Ver
                      </a>
                    </td>
                    <td> 
                      <?php echo $this->Posted_job->count_records('tbl_post_jobs','company_ID', $row->ID); ?>
                    </td>
                    <td><?php echo $row->company_website;?></td>
                    <td>
                      <?php 
                        if ($row->sts == 'active') {
                          $class_label = 'success';
                        } elseif($row->sts == 'blocked') {
                          $class_label = 'danger';
                        } else {
                          $class_label = 'warning';
                        }
                      ?>
                      <a onClick="company_update_status(<?php echo $row->ID; ?>);" 
                         href="javascript:;" 
                         id="sts_<?php echo $row->ID;?>">
                        <span class="label label-<?php echo $class_label;?>"><?php echo camelize($row->sts); ?></span> 
                      </a>

                      <div class="dropdown" style="display:inline-block;margin-left:5px;">
                        <button class="btn btn-default btn-xs dropdown-toggle" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          Gestión
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                          <li><a href="#" class="manage-consultants" data-company-id="<?php echo $row->ID; ?>">Consultoras</a></li>
                          <li><a href="#" class="manage-business-units" data-company-id="<?php echo $row->ID; ?>">Unidad de negocio</a></li>
                          <li><a href="#" class="manage-clients" data-company-id="<?php echo $row->ID; ?>">Clientes</a></li>
                          <li><a href="#" class="manage-cost-centers" data-company-id="<?php echo $row->ID; ?>">Centros de costos</a></li>
                          <li><a href="#" class="manage-sap-connection" data-company-id="<?php echo $row->ID; ?>">SAP conexión</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>

                <?php if (empty($result)): ?>
                  <tr>
                    <td colspan="8" align="center" class="text-red">No Record found!</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          
          <!--Pagination-->
          <div class="paginationWrap"> <?php echo ($result) ? $links : ''; ?> </div>
          
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

<?php $this->load->view('admin/workflow_consultants/partials/modal_consultants_list'); ?>
<?php $this->load->view('admin/workflow_consultants/partials/modal_consultants_add'); ?>
<?php $this->load->view('admin/workflow_consultants/partials/modal_consultants_edit'); ?>

<?php $this->load->view('admin/business_units/partials/modal_business_units_list'); ?>
<?php $this->load->view('admin/business_units/partials/modal_business_units_add'); ?>
<?php $this->load->view('admin/business_units/partials/modal_business_units_edit'); ?>

<?php $this->load->view('admin/workflow_clients/partials/modal_clients_list'); ?>
<?php $this->load->view('admin/workflow_clients/partials/modal_clients_add'); ?>
<?php $this->load->view('admin/workflow_clients/partials/modal_clients_edit'); ?>

<?php $this->load->view('admin/workflow_cost_centers/partials/modal_cost_centers_list'); ?>
<?php $this->load->view('admin/workflow_cost_centers/partials/modal_cost_centers_add'); ?>
<?php $this->load->view('admin/workflow_cost_centers/partials/modal_cost_centers_edit'); ?>

<?php $this->load->view('admin/common/footer'); ?>

<?php $this->load->view('admin/workflow_consultants/scripts/manage_consultants'); ?>
<?php $this->load->view('admin/business_units/scripts/manage_business_units'); ?>
<?php $this->load->view('admin/workflow_clients/scripts/manage_clients'); ?>
<?php $this->load->view('admin/workflow_cost_centers/scripts/manage_cost_centers'); ?>

<?php $this->load->view('admin/sap_connections/common/modal_sap_connection'); ?>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

<script type="text/javascript">
  
    function company_update_status(id) {

      var current_status = $("#sts_" + id + " span").html();
      var url = "<?php echo site_url('admin/companies/status/'); ?>" + id + '/' + current_status;
      
      $.get(url, function (sts) {

        var class_label = 'success';
        
        if (sts != 'active') {
          class_label = 'danger';
        }

        $( "#sts_" + id).html('<span class="label label-' + class_label + '">' + sts + '</span>');
      });
    }
</script>
