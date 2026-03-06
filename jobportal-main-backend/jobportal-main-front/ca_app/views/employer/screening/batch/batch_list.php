<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style type="text/css"> 
.formwraper p{font-size:13px;}

#modal-filter-jobs .modal-content {
    max-width: 370px;
    margin:0 auto;
}

#modal-filter-jobs .modal-body {
    padding: 0px 15px;
}

#modal-filter-jobs .modal-title {
    font-size: 23px;
}

.formwraper p{font-size:13px;}

.label-check i,
.label-radio i {
    color: #333;
    vertical-align:text-bottom;
    font-size: 20px;
}

.panel-filter {
    padding: 10px 0px;
}
.panel-filter label {
    display: block;
    font-size: 16px;
    font-weight: normal;
}

.panel-filter .filter-title {
    padding: 6px 0px;
    border-bottom: 2px solid #1ba6df;
    margin-bottom: 6px;
}

.panel-filter .filter-title h4 {
  font-weight: bold;
}

.dropdown-options-job .dropdown-toggle {
  background: transparent;
  padding: 1px;
}

.text-info {
  color:#555;
  font-style: italic;
  display: block;
  font-size: 12px;
  margin-top: 2px;
}

.wrapper-table table td {
  padding: 4px;
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
                            <b>Screening por lotes</b>
                        </div>
                    </div>
                </div>
                
                <!--Job Description-->
                <div class="wrapper-table"> 
                    <div class="row">
                      <div class="col-md-6"></div>
                      <div class="col-md-6">
                        <a id="import-screening-batch" 
                           class="btn btn-sm btn-primary pull-right" 
                           href="#"
                           style="margin: 5px;">
                           Crear lote
                        </a>
                      </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" style="padding:20px 10px;">
                                <table id="screening-batch" width="100%" class="table table-striped">
                                  <thead>
                                    <tr>
                                      <th>
                                        Id
                                      </th>
                                      <th>
                                        Lote
                                      </th>
                                      <th>
                                        Fecha
                                      </th>
                                      <th>
                                        Estado
                                      </th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach ($batch_results as $row): ?> 
                                      <tr class="">
                                        <td>
                                          <?php e($row->id); ?>
                                        </td>
                                        <td width="35%">
                                          <div><?php e($row->description); ?></div>
                                        </td>
                                        <td data-sort="<?php echo strtotime($row->created_at) ;?>">
                                          <?php e(date('d/m/Y h:i A', strtotime($row->created_at))); ?>
                                        </td>
                                        <td align="center">
                                          <span class="label <?php echo $row->status_label; ?>">
                                            <?php e($row->status_name); ?>
                                          </span>
                                        </td>
                                        <td>
                                          <a href="<?php echo site_url('employer/screening/batch/show/' . $row->id); ?>" class="btn btn-sm btn-default" >Ver</a>
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
    <?php $this->load->view('employer/screening/batch/common/modal_batch_import'); ?>
    <?php $this->load->view('common/bottom_ads'); ?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    <script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script>
    <script type="text/javascript">

        $( '#screening-batch ').DataTable({
          "language": {
              "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
          },
          "bLengthChange": false,
          "order": [[2, 'desc']],
          lengthMenu: [
            [25],
            [25]
          ],
          'columnDefs': [{
            'targets': [0, 1, 3, 4],
            'orderable': false,
          }],
        });
    </script>
    </body>
</html>