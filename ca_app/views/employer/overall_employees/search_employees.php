<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <?php $this->load->view('common/before_head_close'); ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <style type="text/css"> 

        .formwraper p {
          font-size:13px;
        }

        .wrapper-table table td {
          padding: 4px;
        }

        .title-detail {
          padding: 6px 0;
          border-bottom: 1px solid #ccc;
          font-size: 16px;

        }

        #tbl-employee-experiences_paginate {
          text-align: right;
        }

        #tbl-employee-experiences_paginate a {
          margin: 0 4px;
        }

      </style>
  </head>
  <body>
  <?php $this->load->view('common/after_body_open'); ?>
  <div class="siteWraper">
  <!--Header-->
  <?php $this->load->view('common/header'); ?>
  <!--/Header-->
  <div class="container detailinfo">
    <div class="row">
    <div class="col-md-3">
    <div class="dashiconwrp">
      <?php $this->load->view('employer/common/menu/sidebar');?>
    </div>
    </div>
    
      <div class="col-md-9"> 
      <?php echo $this->session->flashdata('msg');?>
        <!--Job Application-->
        <div class="formwraper">
          <div class="titlehead">
            <div class="row">
              <div class="col-md-12">
                <b>Consultas empleados overall</b>
              </div>
            </div>
          </div>
          
          <!--Job Description-->
          <div class="table-search"> 
            <?php echo form_open('employer/overall_employees/search', array('method' => 'get')); ?> 
              <table width="100%">
                <tr>
                  <td width="90%">
                    <input type="text" name="query" class="form-control" value="<?php echo html_escape($query); ?>" placeholder="Buscar empleados por dni, nombres y apellidos">      
                  </td>
                  <td width="10%">
                    <button type="submit" class="btn btn-block btn-search">
                      <i class="glyphicon glyphicon-search"></i>
                    </button>     
                  </td>
                  <td align="right">
                   
                  </td>
                </tr>
              </table>
            <?php echo form_close(); ?>
           </div>
            <div class="table-responsive">
              <table width="100%" class="table table-striped">
                <?php if (trim($query) != '' && count($result_employees) > 0): ?>
                  <thead>
                    <tr>
                      <th>
                        DNI
                      </th>
                      <th>
                        Nombres
                      </th>
                      <th>
                        Apellidos
                      </th>
                      <th>Sexo</th>
                      <th></th>
                    </tr>
                  </thead>
                <?php endif; ?>
                <tbody>
                  <?php foreach ($result_employees as $employee): ?>  
                    <tr data-doc-number="<?php echo $employee['DNI']; ?>" 
                        data-code="<?php echo $employee['CODTRAB']; ?>" 
                        data-name="<?php echo $employee['PRIMER_NOMBRE'] . ' ' . $employee['APELLIDO_PATERNO']; ?>">
                      <td>
                        <?php echo $employee['DNI']; ?>
                      </td>
                      <td>
                        <?php echo $employee['PRIMER_NOMBRE'] . ' ' . $employee['SEGUNDO_NOMBRE']; ?>
                      </td>

                      <td>
                        <?php echo $employee['APELLIDO_PATERNO'] . ' ' . $employee['APELLIDO_MATERNO']; ?>
                      </td>
                      <td>
                        <?php echo $employee['SEXO']; ?>
                      </td>
                      <td>
                        <a href="#" class="js-employee-more-detail" data-doc-number="<?php echo $employee['DNI']; ?>">Más detalles</a>
                      </td>
                      
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="clear"></div>

            <?php if (trim($query) != '' && count($result_employees) == 0): ?>
                <div align="center" class="text-red" style="padding: 20px 10px;">
                    <h4>Ningún resultado</h4>
                </div>              
            <?php endif; ?>
        </div>
      </div>
      <!--/Job Detail-->
      <!--Pagination-->
    </div>
  </div>
</div>
  <?php $this->load->view('common/bottom_ads');?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <!-- Profile Popups -->
  <?php $this->load->view('employer/common/employers_popup_forms'); ?>
  <?php $this->load->view('common/before_body_close'); ?>

  <div id="modal-employee-more-detail" class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Más detalle del trabajador</h4>
        </div>
        <div class="modal-body">

          <div>
            <h5 class="title-detail"><b>DATOS DEL EMPLEADO</b></h5>
            <table class="tadble" width="100%">
              <tr>
                <td width="30%"><b>DNI</b></td>
                <td id="employee-dni"></td>
              </tr>
              <tr>
                <td width="30%"><b>Trabajador</b></td>
                <td id="employee-name"></td>
              </tr>
              <tr>
                <td width="30%"><b>Cod Trabajador</b></td>
                <td id="employee-code"></td>
              </tr>

            </table>
          </div>
          <br />
          <div id="content-detail-employee">
          </div>
        
        </div>
      </div>
      
    </div>
  </div>
  <script src="<?php echo base_url('public/js/lib/dataTable/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/lib/dataTable/bootstrap-datatables.js'); ?>" type="text/javascript"></script>
  <script type="text/javascript">
    
    $(function() {

      function loadMoreDetailEmployee(docNumber) {

        var url = "<?php echo site_url('employer/overall_employees/search_detail_employee/'); ?>" + docNumber;

        $( "#content-detail-employee" ).html("Buscando...");

        $( "#content-detail-employee" ).load(url);

      }

      $( ".js-employee-more-detail" ).click(function() {

        var row = $(this).closest("tr");

        var docNumber = row.data('doc-number');

        $( "#employee-dni" ).html(docNumber);
        $( "#employee-name" ).html(row.data('name'));

        $( "#employee-code" ).html(row.data('code'));

        $( "#modal-employee-more-detail" ).modal('show');

        loadMoreDetailEmployee(docNumber);

      });

    });
  </script>

  </body>
</html>