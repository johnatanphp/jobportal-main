<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css">
  .page-msg {
    font-size: 18px;
  }

  .page-msg .glyphicon-remove-sign {
    color: #d43a3a;
    font-size: 20px;
  }

  .page-msg-body {
    padding: 5px 0;
  }

  .page-msg-footer {
    text-align: center;
    padding: 5px 0;
  }
</style>
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
    </div>
    <div class="col-md-9">
      <div class="formwraper">
        <div class="titlehead">
          Error al procesar la solicitud
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="formint">
              <div class="row">
                <div class="col-md-12">
                  <div class="page-msg">
                    <div class="page-msg-body">
                      <span>
                        <i class="glyphicon glyphicon-remove-sign"></i>
                        <?php echo $msg; ?>
                      </span>
                    </div>  
                    <div class="page-msg-footer">
                      <button onclick="history.go(-1);" class="btn btn-primary">Regresar</button>
                    </div>
                  </div>
                </div>          
              </div>
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
<?php $this->load->view('common/before_body_close'); ?>
</body>
</html>
