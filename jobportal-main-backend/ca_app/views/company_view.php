<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css">
  .intlist {
    padding:20px 6px;
  }
</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=2141839581672924&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header--> 
<!--Detail Info-->
<div class="container detailinfo">
  
      <div class="row"> 
        <div class="col-md-4 col-sm-6">
        
        <!--Company Info-->       
          <div class="companyinfoWrp">            
            <h1 class="jobname"><?php echo $row_company->company_name;?></h1>
            <?php
              //echo "T".file_exists($real_path = realpath(APPPATH . '../public/uploads/employer/'.$company_logo));
              $company_img_pic_url = img_pic_company($row_company->company_logo);
            ?>
            <div class="companylogo"><img src="<?php echo $company_img_pic_url; ?>" width="100%" alt="<?php echo base_url('companies/'.$row_company->company_slug);?>" /></div>
            <div class="companyInfo">
              <div class="location">
                <?php echo ($row_company->company_city)?$row_company->company_city.',':'';?> <?php echo ($row_company->company_country)?$row_company->company_country:'';?>    
              </div>
              <div class="comtxt"><span>Empleos abiertos :</span> <strong><?php echo $total_opened_jobs;?></strong> </div>
              <?php if($row_company->no_of_employees):?>
              <div class="comtxt"><span>Personal :</span> <strong><?php echo $row_company->no_of_employees;?> trabajadores</strong></div>
              <?php endif;?>
              <?php if($company_website):?>
              <div class="comtxt"><span>Sitio de la empresa:</span> <strong><a href="<?php echo $company_website;?>" target="_blank" rel="nofollow"><?php echo $company_website;?></a></strong></div>
              <?php endif;?>
            </div>    
            <div class="clear"></div>
          </div>
        
        <!--Apply-->        
        <?php if($this->session->userdata('is_user_login')!=TRUE): ?>
        <div class="actionBox">
          <h4>No eres miembro</h4>
          <p>Haga clic en Inicio de sesión si usted ya es miembro.</p>
          <a href="<?php echo base_url('jobseeker-signup');?>" class="applyjob"><span>Registrarse</span></a> <a href="<?php echo base_url('login');?>" class="refferbtn"><span>Iniciar sesión</span></a> </div>
        <?php endif;?>        
        </div>
        
        
        <div class="col-md-8 col-sm-6">        
        <!--Job Detail-->      
		  <?php if($row_company->company_description && $page == 0): ?>
          <div class="boxwraper">
            <div class="titlebar">Acerca de '<?php echo $row_company->company_name;?>'</div>
            
            <!--Job Description-->
            
            <div class="companydescription">
              <div class="row">
                <div class="col-md-12">
                  <h2 class="normal-details">
                    <?php 
    
                    echo strip_tags($row_company->company_description);
    
                  ?>
                  </h2>
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>
          <div class="searchjoblist">
            <!--Jobs List-->        
            <div class="boxwraper">
              <div class="titlebar">
                <div class="row">
                  <div class="col-md-6"><b>Empleos abiertos actualmente</b></div>
                </div>
              </div>
              <div class="row searchlist"> 
                
                <!--Job Row-->
                
                <?php 
    
                     if($result_posted_jobs):
    
                        $CI =& get_instance();
    
                        foreach($result_posted_jobs as $row_jobs):
    
                        $is_already_applied = $CI->is_already_applied_for_job($this->session->userdata('user_id'), $row_jobs->ID);		
    
                        ?>
                <div id="container-list-jobs" class="col-md-12">
                  <div class="intlist">
                    <div class="col-md-12">
                      <div class="col-md-8"> <a href="<?php echo base_url('jobs/'.$row_jobs->job_slug);?>" class="jobtitle" title="<?php echo $row_jobs->job_title;?>"><?php echo word_limiter(strip_tags(str_replace('-',' ',$row_jobs->job_title)),9);?></a>
                        <div class="location"><a href="<?php echo base_url('companies/'.$row_company->company_slug);?>" title="<?php echo $row_jobs->company_name;?>"><?php echo $row_company->company_name;?></a> &nbsp;-&nbsp; <?php echo $row_jobs->city;?></div>
                        <div class="info">
                          <span>
                            <i class="glyphicon glyphicon-calendar"></i>
                            Publicado:
                            <?php echo ucwords(_date_locale_format(strtotime($row_jobs->dated), 'dd MMM y'));?>
                          </span>
                          &nbsp;&nbsp;
                          <span>
                            <i class="glyphicon glyphicon-user"></i>Responsable:
                            <?php echo $row_jobs->employer_name;?>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <?php
    
                    if($is_already_applied=='yes'):
    
                  ?>
                        <a href="javascript:;" class="applybtngray">Ver más</a>
                        <?php else:?>
                        <a href="<?php echo base_url('jobs/'.$row_jobs->job_slug);?>" class="applybtn">Ver más</a>
                        <?php endif;?>
                      </div>
                      <div class="clear"> </div>
                      <p><?php echo word_limiter(strip_tags(str_replace('-',' ',$row_jobs->job_description)),40);?></p>
                    </div>
                    <div class="clear"></div>
                  </div>
                </div>
                <?php 
    
                        endforeach;
    
                     else:					
    
                    ?>
                <div align="center" class="text-red">Ningún empleo abierto.</div>
                <?php endif;?>
              </div>

              <div class="paginationWrap pag-wrap-v2">
                  <?php echo ($result_posted_jobs) ? $links : '' ; ?>       
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