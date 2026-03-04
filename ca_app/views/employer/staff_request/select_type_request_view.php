<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<style>
  #select-type-request a { 
    text-align: center;
    display: block;
    padding: 15px 8px;
    font-size: 18px;
    background: #f4f4f4;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-bottom: 10px;
    color: #333;
    font-weight: bold;
  }

  #section-request-type {
    padding: 40px 10px;
  }

</style>
<?php $this->load->view('common/before_head_close'); ?>
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
  
    <div class="col-md-9"> <?php echo $this->session->flashdata('msg');?> 
      <div class="formwraper">
        <div class="titlehead">Crear Solicitud</div>
        <div class="row"> 
          <div class="col-md-12">
            <div class="formint">
            
              <!-- Start section select request type -->
              <div id="section-request-type">
                <div class="row">
                  <div class="col-md-12">
                    <div id="select-type-request">
                      <div class="row">
                        
                        <?php if (has_permission_action('staff_requests', 'create_internal')): ?>
                          <div class="col-md-12">
                            <a href="<?php echo site_url('employer/staff_request/create_internal_staff_request/'); ?>">
                              Solicitud Interna
                            </a>
                          </div>
                        <?php endif; ?>
                        <?php if (has_permission_action('staff_requests', 'create_external')): ?>
                            <div class="col-md-12">
                                <a href="<?php echo site_url('employer/staff_request/create_external_staff_request'); ?>">
                                    Solicitud Externa
                                </a>
                            </div>
                        <?php endif; ?>
                      </div>  
                    </div>
                  </div>
                </div>
              </div>
              <!-- End section select request type -->
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

<script src="<?php echo base_url('public/js/tooltip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>  

<script type="text/javascript">

$(document).ready(function() {

  function addEvents() {
    $( "#prev-step-request" ).click(function(){
      
        var prevStep = $( ".content-step:visible").index();
        
        showStep(prevStep);
    });

    $( "#next-step-request" ).click(function(){

        var currentStep = $( ".content-step:visible").index() + 1;

        validateStep(currentStep);
    });

    $( "#request-type" ).change(function(){

      $( "#client_company_name input" ).val('');
      $( "#client_company_industry input" ).val('');
      $( "#client_company_name" ).hide();
      $( "#client_company_industry" ).hide();

      if ($(this).val() == 'external') {
        $( "#client_company_name" ).show();
        $( "#client_company_industry" ).show();
      }
    });
  }
   //Add events
  addEvents();
});
</script>
</body>
</html>