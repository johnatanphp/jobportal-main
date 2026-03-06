<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css"> 
  .formwraper p{
    font-size:13px;
  }

  .text-info {
    color:#555;
    font-style: italic;
    display: block;
    font-size: 12px;
    margin-top: 2px;
  }

  .table thead th {
      text-align: left;
  }

  .table tbody td {
    text-align: left;
  }

  .list-options {
    list-style: none;
    padding-bottom: 3px;
  }

  .list-options__item {
    padding: 4px 0;
    border-bottom: 1px solid #cccccc;
    font-weight: normal;
  }

  .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
    background: #064185;
  }

  .modal-fullscreen {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
  }

  .modal-fullscreen .modal-content {
    height: auto;
    min-height: 100%;
    border: 0 none;
    border-radius: 0;
    box-shadow: none;
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
        <div class="formwraper">
          <div class="titlehead">
            <div class="row">
              <div class="col-md-12">
                <b>Consulta Screening</b>
              </div>
            </div>
          </div>
            
          <!-- Description -->
          <div class="table-search">
            <br>
            <table width="100%">
              <tr>
                <?php echo form_open('employer/screening/search', array('method' => 'get')); ?>    
                  <?php if ($document_number == ''): ?>
                      <td width="50%">
                        <input type="text" name="document_number" class="form-control" value="" placeholder="Ingresar DNI y presione ENTER">         
                      </td>
                      <td width="20" colspan="2">
                        <button type="submit" class="btn btn-search">
                          <i class="glyphicon glyphicon-search"></i>
                        </button>           
                      </td>
                      <td align="right">
                        <?php if ($document_number == '' && has_permission_action('screening', 'report_list')): ?>
                          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-screening-report-filter">
                            Exportar Listado
                          </button>
                          <div class="dropdown dropdown-options-job" style="display:inline;">
                            <button class="btn btn-sm dropdown-toggle btn-default" type="button" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-option-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="<?php echo site_url('employer/screening/batch'); ?>">
                                      Gestionar lotes
                                    </a>
                                </li>
                            </ul>
                          </div>
                        <?php endif; ?>
                                </td>
                  <?php endif; ?>

                  <?php if ($document_number != ''): ?>
                      <td width="5">
                        <a href="<?php echo site_url('employer/screening/search'); ?>">
                          <i class="fa fa-arrow-left"></i>
                        </a> 
                      </td>
                      <td style="padding: 5px;">
                        <h4>Resultados para <b><?php echo $document_number; ?></b></h4>         
                      </td>
                      <td align="right">
                        <?php if (has_permission_action('screening', 'create')): ?>
                          <button type="button" id="btn-create-screening" class="btn btn-sm btn-primary">Crear screening</button>
                        <?php endif; ?>
                        <div class="dropdown dropdown-options-job" style="display:inline;">
                            <button class="btn btn-sm dropdown-toggle btn-default" type="button" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-option-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="<?php echo site_url('employer/screening/batch'); ?>">
                                      Gestionar lotes
                                    </a>
                                </li>
                            </ul>
                          </div>
                      </td>
                  <?php endif; ?>
                <?php echo form_close(); ?>
              </tr>
            </table>
          </div>

          <?php if (count($results) == 0): ?>
            <div align="center" class="text-red" style="padding: 20px;">
              <h4>
                <?php echo $document_number != '' ? 'Ningún Screening encontrado para "' . htmlentities($document_number, ENT_QUOTES) . '"' : ''; ?>
              </h4>
            </div>              
          <?php else: ?>    
            <?php if ($seeker): ?>
              <div style="padding: 10px;">
                <div class="row">
                  <div class="col-xs-2">
                    <div style="text-align: center;">
                      <img width="80" src="<?php echo img_pic_candidate($seeker->photo); ?>" />
                    </div>
                  </div>
                  <div class="col-xs-10">
                    <div>
                      <h4 style="font-weight: bold;padding: 5px 0;">
                        <?php echo mb_strtoupper($seeker->first_name . ' ' . $seeker->last_name); ?>
                      </h4>
                    </div>
                    <div>
                      <ul class="list-options">
                        <li class="list-options__item">
                          <b>Doc identidad:</b> <?php echo $seeker->document_number; ?>       
                        </li>
                        <li class="list-options__item">
                          <b>Fecha Nacimiento:</b> <?php echo $seeker->dob; ?>       
                        </li>                                            
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <div class="table-responsive" style="padding: 5px 10px;">
              <table id="tbl-screening" width="100%" class="table">
                <thead>
                  <tr>
                    <th>
                      Expedición
                    </th>
                    <th></th>
                    <th>
                      Conforme
                    </th>
                    <th>Tipo</th>
                    <th>
                      Origen
                    </th>
                    <th>Vencimiento</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($results as $row): ?>
                    <tr>
                      <td data-search="<?php e($row->created_at); ?>" data-sort="<?php echo strtotime($row->created_at) ;?>">
                        <?php e($row->created_at); ?>
                      </td>
                      <td style="text-align: center;">

                        <?php if ($row->origin != 'requested'): ?>
                          <a class="btn btn-sm btn-default" href="<?php echo file_url($row->file_path); ?>" target="_blank">
                            Ver
                          </a>
                        <?php endif; ?>

                        <?php if ($row->origin == 'requested'): ?>
                          <div class="dropdown dropdown-options-job" style="display:inline;">
                            <button class="btn btn-sm dropdown-toggle btn-default" type="button" data-toggle="dropdown">
                                Ver
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                              <li>
                                <a href="<?php echo site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($row->id) . '/1'); ?>" 
                                  target="_blank">
                                  Preliminar
                                </a>
                              </li>
                              <li>
                                <a href="<?php echo site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($row->id) . '/2'); ?>" 
                                  target="_blank">
                                  Anexo
                                </a>
                              </li>
                              <li>
                                <a href="<?php echo site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($row->id) . ''); ?>"
                                  target="_blank">
                                  Completo
                                </a>
                              </li>
                            </ul>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td style="text-align: center;">
                        <?php if ($row->origin == 'requested'): ?>
                          <?php if (isset($row->its_data_prosecution) && $row->its_data_prosecution) : ?>
                            <span style="color: red; font-size: 12px;"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
                          <?php else: ?>
                            <span style="color: green; font-size: 12px;"><span class="glyphicon glyphicon-ok"></span>
                            </span>
                          <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($row->origin != 'requested'): ?>
                          -
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php e($row->type_name ? $row->type_name : 'Sin especificar'); ?>
                      </td>
                      <td>
                        <?php 
                          $screenig_origin_list = [
                            'requested' => 'Solicitado',
                            'historical' => 'Histórico',
                            'attach' => 'Adjunto'
                          ];
                        ?>
                        <?php e($screenig_origin_list[$row->origin] ? $screenig_origin_list[$row->origin] : '-'); ?>
                      </td>
                      <td>
                        <?php e($row->due_date ? $row->due_date : '-'); ?>
                      </td>
                      <td>
                        <?php if ($row->remaining_days !== null): ?>
                          <?php echo $row->remaining_days > 0 ? '<span class="label label-success">Vigente</span>' : '<span class="label label-warning">Vencido</span>'; ?>
                        <?php endif; ?>

                        <?php if ($row->remaining_days === null): ?>
                          -
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modals -->
<?php $this->load->view('employer/screening/common/modal_create_screening'); ?>
<?php $this->load->view('employer/screening/common/modal_screening_show'); ?>
<?php $this->load->view('employer/screening/common/modal_screening_report_filter'); ?>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>

<script type="text/javascript">
$(function(){
  messageSuccess = "<?php echo $this->session->flashdata('success') ?? ''; ?>";
  if (messageSuccess != '') {
    toastr["success"](messageSuccess);
  }

  messageError = "<?php echo $this->session->flashdata('error') ?? ''; ?>";
  if (messageError != '') {
    toastr["error"](messageError);
  }
});
</script> 
<script type="text/javascript">
$(function(){
  $( '#tbl-screening' ).DataTable({
    "language": {
      "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
    },
    "iDisplayLength": 25,
    "bLengthChange": false,
    "paging" : true,
    "ordering" : true,
    "scrollCollapse" : true,
    "searching" : true,
    "bInfo": true,
    "order": [[0, 'desc']],
    'columnDefs': [{
      'targets': [1, 2, 3],
      'orderable': false,
    }],
  });

  $( '#cost_centers' ).select2({
    dropdownParent: $('#modal-create-screening .modal-body')
  });
});
</script>
</body>
</html>