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
  
  .wrapper-list {
    margin-bottom: 30px;
    margin-top: 30px;
  }

  .report-list {
    list-style: none;
    padding: 10px 5px;
    max-width: 600px;
    margin: 0 auto;
  }

  .report-list-item {
    padding: 15px 10px;
    border-bottom: 1px solid #cccccc;
    display: flex;
    align-items: center;
  }

  .report-list-item .fa {
    padding: 0 10px;
  }

  .report-list-item a {
    height: 15px;
  }

  .report-list-item label {
    flex: 1;
    font-weight: normal;
    margin: 0;
    font-size: 14px;
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
                <b>Reportes Operativos</b>
              </div>
            </div>
          </div>   
          <div class="row">
            <div class="col-md-12">     
              <div class="wrapper-list">
                <ul class="report-list">
                  <li class="report-list-item">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <label>Atención de requerimientos</label>
                    <a href="<?php echo site_url('employer/operational_reports/staff_requests/attention_requirements_summary'); ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="15" viewBox="0 0 10 15" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.34575 0.5L9.20872 7.52082H5.68597L0.5 2.34289L2.34575 0.5ZM2.34743 14.5417L0.501672 12.6988L5.68764 7.52083H9.21039L2.34743 14.5417Z" fill="#333333"></path>
                      </svg>
                    </a>
                  </li>
                  <li class="report-list-item">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <label>Listado de requerimientos</label>
                    <a href="<?php echo site_url('employer/operational_reports/staff_requests/requirement_list'); ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="15" viewBox="0 0 10 15" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.34575 0.5L9.20872 7.52082H5.68597L0.5 2.34289L2.34575 0.5ZM2.34743 14.5417L0.501672 12.6988L5.68764 7.52083H9.21039L2.34743 14.5417Z" fill="#333333"></path>
                      </svg>
                    </a>
                  </li>
                  <li class="report-list-item">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <label>Solicitudes screening</label>
                    <a href="<?php echo site_url('employer/operational_reports/screening/screening_list'); ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="15" viewBox="0 0 10 15" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.34575 0.5L9.20872 7.52082H5.68597L0.5 2.34289L2.34575 0.5ZM2.34743 14.5417L0.501672 12.6988L5.68764 7.52083H9.21039L2.34743 14.5417Z" fill="#333333"></path>
                      </svg>
                    </a>
                  </li>
                </ul>
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

</script>
</body>
</html>