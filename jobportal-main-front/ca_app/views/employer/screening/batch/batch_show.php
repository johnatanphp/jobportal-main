<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style type="text/css"> 
  .formwraper p{font-size:13px;}

  .wrapper-table table td {
    padding: 4px;
  }

  .step-title {
    color: #333;
    font-size: 17px;
    text-transform: uppercase;
    padding: 8px 4px;
    border-bottom: 1px solid #999;
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
            <?php echo $this->session->flashdata('msg'); ?>
            <!--Job Application-->
            <div class="formwraper">
                <div class="titlehead">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" style="color:#fff;" class="_link-back">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                            <b>Detalle del Lote</b>
                        </div>
                    </div>
                </div>
                
                <!--Job Description-->
                <div class="wrapper-table" style="padding: 8px;"> 

                    <div class="row">
                      <div class="col-md-12">
                        <div class="step-title">Lote</div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div style="padding: 8px 0;">
                          <div class="input-group">
                            <label class="input-group-addon">Id</label>
                            <span><?php e($batch->id); ?></span>
                          </div>
                          <div class="input-group">
                            <label class="input-group-addon">Descripción</label>
                            <span><?php e($batch->description ? $batch->description : 'Sin descripción'); ?></span>
                          </div>
                          <div class="input-group">
                            <label class="input-group-addon">Creado</label>
                            <span><?php e(date('d/m/Y h:i A', strtotime($batch->created_at))); ?></span>
                          </div>
                        </div>
                      </div>  
                      <div class="col-md-6">
                        <div class="input-group">
                          <label class="input-group-addon">Estado </label>
                          <?php 
                            $status_data = [
                              '1' => 'PROCESANDO',
                              '2' => 'PROCESADO'
                            ];
                            $status_label_color = [
                              '1' => 'label-warning',
                              '2' => 'label-success'
                            ];
                          ?>
                          <span class="label <?php echo $status_label_color[$batch->status_id] ?? 'label-default'; ?>">
                            <?php echo $status_data[$batch->status_id] ?? '-';?>
                          </span>
                        </div>
                      </div>  
                    </div> 
                    <br>
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="step-title">Listado</div>
                            <br>
                            <div class="table-responsive">
                                <table id="screening-batch-items" width="100%" class="table">
                                  <thead>
                                    <tr>
                                      <th>
                                        DNI
                                      </th>
                                      <th>Tipo</th>
                                      <th>Expedición</th>
                                      <th>Vencimiento</th>
                                      <th style="text-align: center;">Conforme</th>
                                      <th style="width: 40px;"></th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach ($batch_items as $row): ?> 
                                      <tr class="">
                                        <td>
                                          <?php e($row->batch_item_document_number); ?>
                                        </td>
                                        <td>
                                          <?php e($row->batch_items_type_name); ?>
                                        </td>
                                        <td>
                                          <?php e($row->screening_created_at && $row->screening_response_code == 1 ? _date_locale_format(strtotime($row->screening_created_at), 'dd MMM y') : '-'); ?>
                                        </td>
                                        <td>
                                          <?php e($row->screening_due_date && $row->screening_response_code == 1 ? _date_locale_format(strtotime($row->screening_due_date), 'dd MMM y') : '-'); ?>
                                        </td>
                                        <td style="text-align: center;">
                                          <?php if ($row->screening_id && $row->screening_response_code == 1): ?>
                                            <?php if ($row->screening_its_data_prosecution == 0): ?>
                                              <span style="color: green; font-size: 12px;">
                                                <span class="glyphicon glyphicon-ok"></span>
                                              </span>
                                            <?php endif; ?>

                                            <?php if ($row->screening_its_data_prosecution == 1): ?>
                                              <span style="color: #c21414; font-size: 12px;">
                                                <span class="glyphicon glyphicon-exclamation-sign"></span>
                                              </span>
                                            <?php endif; ?>
                                          <?php else: ?>
                                            -
                                          <?php endif; ?>
                                        </td>
                                        <td>
                                          <?php if ($row->screening_remaining_days !== null && $row->screening_response_code == 1): ?>
                                            <?php echo $row->screening_remaining_days > 0 ? '<span class="label label-success">Vigente</span>' : '<span class="label label-warning">Vencido</span>'; ?>
                                          <?php endif; ?>
                                        </td>
                                        <td align="center">
                                          <?php if ($row->screening_id && $row->screening_response_code == 1): ?>
                                            <div class="dropdown dropdown-options-job" style="display:inline;">
                                              <button class="btn btn-sm dropdown-toggle btn-default" type="button" data-toggle="dropdown">
                                                  Ver
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                  <a href="<?php echo site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($row->screening_id) . '/1'); ?>" 
                                                    target="_blank">
                                                    Preliminar
                                                  </a>
                                                </li>
                                                <li>
                                                  <a href="<?php echo site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($row->screening_id) . '/2'); ?>" 
                                                    target="_blank">
                                                    Anexo
                                                  </a>
                                                </li>
                                                <li>
                                                  <a href="<?php echo site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($row->screening_id) . ''); ?>"
                                                    target="_blank">
                                                    Completo
                                                  </a>
                                                </li>
                                              </ul>
                                            </div>
                                          <?php endif; ?>
                                          
                                          <?php if ($row->screening_id && $row->screening_response_code == 0): ?>
                                            <a class="btn btn-sm btn-default btn-show-error"
                                               data-id="<?php echo $row->batch_item_id; ?>" 
                                               style="color: #c21414;">
                                              <span class="glyphicon glyphicon-exclamation-sign"></span> Error
                                            </a>
                                          <?php endif; ?>

                                          <?php if (!$row->screening_id): ?>
                                            <a class="btn btn-sm btn-default" disabled>Generando</a>
                                          <?php endif; ?>
                                        </td>
                                      </tr>
                                    <?php endforeach; ?>
                                  </tbody>
                                </table>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/Job Detail-->
        <!--Pagination-->
        </div>
    </div>
    <?php $this->load->view('employer/screening/batch/common/modal_screening_show_error'); ?>
    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    <script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script>
    <script type="text/javascript">
      $( '#screening-batch-items').DataTable({
        "language": {
          "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "bLengthChange": false,
        "ordering" : true,
        "order": [[2, 'desc']],
        lengthMenu: [
          [25],
          [25]
        ],
        'columnDefs': [{
          'targets': [0, 1, 4, 5, 6],
          'orderable': false,
        }],
      });
    </script>
    </body>
</html>