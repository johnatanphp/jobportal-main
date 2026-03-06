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

  .formwraper p{font-size:13px;}

  .report-list {
    list-style: none;
    padding: 10px 5px;
    margin: 10px 3em;
  }

  .report-list-item {
    padding: 10px 5px;
    border-bottom: 1px solid #cccccc;
    display: flex;
    align-items: center;
  }

  .report-list-item label {
    flex: 1;
    font-weight: normal;
    margin: 0;
    font-size: 14px;
  }

  .report-list-item a {
    
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
                <a class="_link-back" style="color:#fff;" href="#">
                  <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                <b>Reporte solicitudes screening</b>
              </div>
            </div>
          </div>   
          <div class="formint">
            <div>
              <div style="padding: 12px 0;">
                <?php echo form_open('employer/operational_reports/screening/screening_list/export', ['id' => 'form-report-screening-list', 'method' => 'get']); ?>
                  <div class="input-group">
                    <label class="input-group-addon">Fecha inicio <span>*</span></label>
                    <input type="date" name="start_date" class="form-control" required>
                  </div>
                  <div class="input-group">
                    <label class="input-group-addon">Fecha fin <span>*</span></label>
                    <input type="date" name="end_date" class="form-control" required>
                  </div>
                  <div style="text-align: center;">
                    <input type="submit" value="Exportar" class="btn btn-sm btn-primary">
                  </div>
                  <?php echo form_close(); ?>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script type="text/javascript">
$(function(){
  $( '#site-menu a[href="<?php echo site_url('employer/operational_reports/reports'); ?>"]' ).addClass('active');

  $( '#form-report-screening-list' ).submit(function(e){
    const startTime =  new Date($( 'input[name="start_date"]' ).val()).getTime();
    const endTime = new Date($( 'input[name="end_date"]' ).val()).getTime();

    if (startTime > endTime) {
      e.preventDefault();
      toastr["error"]('La fecha fin debe ser mayor o igual a la fecha de inicio');
      return false;
    }

    const diff = endTime - startTime;
    const diffMonth = diff / 2629800000;

    if (diffMonth > 2) {
      e.preventDefault();
      toastr["error"]('El rango de fecha seleccionada no debe superar los 2 meses');
      return false;
    }

    return true;
  });
});
</script>
</body>
</html>