<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<meta name="keywords" content="empleo, empleos, overall empleos, trabajo, trabajos en overall, trabajos">
<title><?php echo $title;?></title>
<style type="text/css">
  #see-more-jobs {
    background: #555;
    color: #fff;
    border-radius: 0;
    font-size: 16px;
    padding: 12px 16px;
    font-weight: bold;
  }
  
  .job-title-info {
    text-align: left;
    font-size: 24px;
  }

  #jsearch {
    padding: 10px;
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
<!--Search Block-->
<div class="top-colSection">
  <div class="container">
    <div class="row">
      <?php $this->load->view('common/home_search');?>
      <div class="clear"></div>
    </div>
  </div>
</div>
<!--/Search Block--> 

<!--Latest Jobs Block-->
<div class="latestjobs">
<div class="container">
       
  <div class="titlebar">      
    <?php 
      $s = $total_posted_jobs > 1 ? "s": "";
      $country_name = isset($country->country_name) ? $country->country_name : (isset($country->countryName) ? $country->countryName : 'Peru');
      $total_info = "Hay <b>". $total_posted_jobs . "</b> empleo". $s . " abierto" . $s . " en <b>" . $country_name . "</b>";
    ?>     
    <h3 class="job-title-info"><?php echo $total_info; ?></h3>
  
  </div>
  <div class="row joblist">
    <?php       
      if($latest_jobs_result):
        $i = 0;
        foreach($latest_jobs_result as $row_latest_jobs):

          $job_title = ellipsize(humanize($row_latest_jobs->job_title), 45, 1);

          $image_name = ($row_latest_jobs->company_logo) ? $row_latest_jobs->company_logo : 'no_pic.jpg';
          
          if (!file_exists(realpath(APPPATH . '../public/uploads/employer/'.$image_name))) {
            $image_name='no_pic.jpg';
          }
          
          $company_img_pic_url = img_pic_company($row_latest_jobs->company_logo, 'thumb');
          ?>
          <?php if (($i % 2) == 0): ?>
          <div class="row">
          <?php endif; ?>
            <div class="col-md-6">
              <div class="intlist">
                <div class="row">
                  <div class="col-xs-2">
                    <a href="<?php echo base_url('companies/'.$row_latest_jobs->company_slug);?>" title="Empleo en <?php echo $row_latest_jobs->company_name;?>" class="thumbnail">
                      <img src="<?php echo $company_img_pic_url; ?>" alt="<?php echo base_url('companies/'.$row_latest_jobs->company_slug);?>" />
                    </a>
                  </div>
                  <div class="col-xs-6">
                    <a href="<?php echo base_url('jobs/'.$row_latest_jobs->job_slug);?>" class="jobtitle" title="<?php echo $row_latest_jobs->job_title;?>"><?php echo $job_title;?></a> <span><a href="<?php echo base_url('companies/'.$row_latest_jobs->company_slug);?>" title="Empleo en <?php echo $row_latest_jobs->company_name;?>">
                      <?php echo $row_latest_jobs->company_name;?>
                    </a> 
                      <?php if ($row_latest_jobs->city): ?>
                        &nbsp;-&nbsp; <?php echo $row_latest_jobs->city;?>
                      <?php endif; ?>
                      </span>
                  </div>
                  <div class="col-xs-4">
                    <a href="<?php echo base_url('jobs/'.$row_latest_jobs->job_slug);?>" class="applybtn" title="<?php echo $row_latest_jobs->industry_name.' Empleo en '.$row_latest_jobs->city;?>">Ver más</a>
                  </div>
                </div>
                    
                <div class="row">
                  <div class="col-xs-8 col-xs-offset-2 job-description">
                    <?php
                      echo ellipsize(strip_tags(str_replace('&nbsp;', ' ', $row_latest_jobs->job_description)), 150);
                    ?>
                  </div>
                </div>
              </div>
            </div>
          <?php if ((++$i % 2) == 0): ?>
            </div>

          <?php endif; ?>
        
        <?php
        endforeach;
      endif;
    ?>
  </div>
  
  <?php if ($total_posted_jobs > 30): ?>
    <div class="row">
      <div class="col-md-12" style="text-align: center;padding-top: 20px;">
        <a id="see-more-jobs" href="<?php echo base_url('jobs.html'); ?>" class="btn">Ver más Empleos</a>
      </div>
    </div>
  <?php endif; ?>

</div>
</div>

<?php if ($featured_job_result): ?>
<!--/Latest Jobs Block--> 
<!--Featured Jobs-->      
<div class="featuredWrap">
<div class="container">
    <div class="titlebar"> <h2>Empleos destacados</h2></div>
        <ul class="featureJobs row">
          <?php
                                        foreach($featured_job_result as $row_featured_job):
                        ?>
          <li class="col-md-6">
                <div class="intbox">
            <div class="compnyinfo">
            <a href="<?php echo base_url('jobs/'.$row_featured_job->job_slug);?>" title="<?php echo $row_featured_job->job_title;?>"><?php echo $row_featured_job->job_title;?></a> <span><a href="<?php echo base_url('companies/'.$row_featured_job->company_slug);?>" title="Jobs in <?php echo $row_featured_job->company_name;?>"><?php echo $row_featured_job->company_name;?></a> &nbsp;-&nbsp; <?php echo $row_featured_job->city;?></span> </div>
            <div class="date">Apply by <br />
              <?php echo ucwords(_date_locale_format(strtotime($row_latest_jobs->last_date), 'MMM dd, y'));?></div>
            <div class="clear"></div>
            </div>
          </li>
          <?php endforeach;?>
        </ul>
</div>
</div>
<!--Featured Jobs End-->
<?php endif; ?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<!-- FlexSlider --> 
<script src="<?php echo base_url('public/js/jquery.flexslider.js');?>" type="text/javascript"></script> 
<script>
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide",
    animationLoop: false,
    itemWidth: 250,
    minItems: 1,
    maxItems: 1
  });
});
</script>
</body>
</html>