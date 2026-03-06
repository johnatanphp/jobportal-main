<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header--> 
<!--Detail Info-->
<div class="container detailinfo">
  <div class="row">
  <div class="col-md-3">
  <div class="dashiconwrp">
    <?php $this->load->view('employer/common/menu/sidebar');?>
  </div>
  </div>
  
    <div class="col-md-9"><!--Job Detail-->
      <div class="formwraper">
        <div class="titlehead">
          <div class="row">
            <div class="col-md-12"><b>Solicitudes de empleos recibidas</b></div>
          </div>
        </div>
        <!--Job Description-->
        <div class="companydescription">
          <div class="row">
            <div class="col-md-12">
              <ul class="myjobList">
                <li class="row">
                  <div class="col-md-4"><strong>Candidato</strong></div>
                  <div class="col-md-4"><strong>Trabajo</strong></div>
                  <div class="col-md-4"><strong>Fecha de postulación</strong></div>
                </li>
                <?php if($result_applied_jobs): 
		  			foreach($result_applied_jobs as $row_applied_job):
		  ?>
                <li class="row">
                  <div class="col-md-4"><a class="view-profile" href="<?php echo base_url('candidate/view_profile/'.$this->custom_encryption->encrypt_data($row_applied_job->job_seeker_ID));?>"><?php echo $row_applied_job->first_name.' '.$row_applied_job->last_name;?></a></div>
                  <div class="col-md-4"><a href="<?php echo base_url('jobs/'.$row_applied_job->job_slug);?>"><?php echo $row_applied_job->job_title;?></a></div>
                  <div class="col-md-4"><?php echo ucwords(_date_locale_format(strtotime($row_applied_job->applied_date), 'MMM dd, y'));?></div>
                </li>
                <?php 	endforeach; 
		  		else:?>
                Ningún resultado encontrado.
                <?php endif;?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/Job Detail--> 
    
    <!--Pagination-->
    <div class="paginationWrap"> <?php echo ($result_applied_jobs)?$links:'';?> </div>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<div id="modal-view-profile" class="modal" tabindex="-1" role="dialog"></div>
</body>
</html>