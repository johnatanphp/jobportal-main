<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<meta name="keywords" content="<?php echo $search;?> empleos" />
<meta name="description" content="<?php echo $search;?> empleos,encuentra los mejores empleos. Empleos en .  <?php echo SITE_NAME;?>." />
<title><?php echo $title;?></title>
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
      <div class="col-md-12">
         <?php echo form_open('job_search',array('id' => 'jsearch', 'method' => 'post'));?>  
        <div class="candidatesection">
          <div class="col-md-10">            
       		 <input type="text" name="search" class="form-control" placeholder="Puesto, empresa o palabra clave" value="<?php echo $search; ?>" />
          </div>

          <div class="col-md-2">
            <div class="input-group">
              <select class="form-control hide" name="city">
                <option value="" selected>Todos los lugares</option>
                <?php if($cities_res): foreach($cities_res as $cities):?>

                  <?php $selected = $cities->city_name == $city ? 'selected="selected"' : ''?>
                  <option value="<?php echo $cities->city_name;?>" <?php echo $selected;?> ><?php echo $cities->city_name;?></option>
                <?php endforeach; endif;?>
              </select>
              <span class="input-group-btn">
                <input type="submit" class="btn btn-block" value="Buscar" />
              </span>
            </div>
          </div>            
          <div class="clear"></div>
        </div>
        <?php echo form_close();?>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
<!--/Search Block--> 
<!--Latest Jobs Block-->
<div class="innerpageWrap">
<div class="container">
  <div class="row"> 
    
    <!--Left Col-->
    
    <?php 
  $col = '10';
	if($this->uri->segment(1)!='search'):
	if($result):
		$col = '8';
  		$this->load->view('common/left_job_search');
	endif;
	endif;
	?>
    
    <!--Mid Col-->
    
    <div class="searchjoblist col-md-<?php echo $col;?>"> 
      
      <!--Jobs List-->
      
      <div class="searchpage">
        <div class="titlebar v2">
          <div class="row">
            <div class="col-md-12">
              <h3 style="font-size: 22px;">
                <?php if ($total_rows > 0): ?>
                  Hay <b><?php echo $total_rows;?></b> empleo(s) encontrados en <b><?php echo $country->country_name; ?></b>
                  <?php if ($industry != ''): ?>
                  en el área <b><?php echo $industry; ?></b>
                  <?php endif; ?>
                  <?php if ($search != '') :?>
                  para la búsqueda: <b><?php echo $search;?></b>
                  <?php endif;?>
                  <?php if ($city != ''): ?>
                  en <b><?php echo $city; ?></b> 
                  <?php endif; ?>
                <?php endif; ?>
                </h3>
            </div>
          </div>
       </div>        
        <ul class="searchlist">
        <!--Job Row-->
          
          <?php if($result):
		$CI =& get_instance();
				  			foreach($result as $row):
								$company_logo = ($row->company_logo)?$row->company_logo:'no_pic.jpg';
								if (!file_exists(realpath(APPPATH . '../public/uploads/employer/thumb/'.$company_logo))){
									$company_logo='no_pic.jpg';
								}
								$is_already_applied = $CI->is_already_applied_for_job($this->session->userdata('user_id'), $row->ID);	

                $company_img_pic_url = img_pic_company($row->company_logo, 'thumb');	
				  ?>
          <li>
            <div class="row">
              <div class="col-xs-3 col-md-2"><a href="<?php echo base_url('jobs/'.$row->job_slug);?>" class="thumbnail" title="<?php echo $row->job_title;?>"><img src="<?php echo $company_img_pic_url; ?>" alt="<?php echo base_url('companies/'.$row->company_slug);?>" /></a></div>
              <div class="col-xs-9 col-md-10">
                <div class="col-md-7"> <a href="<?php echo base_url('jobs/'.$row->job_slug);?>" class="jobtitle" title="<?php echo $row->job_title;?>"><?php echo word_limiter(strip_tags(str_replace('-',' ',$row->job_title)),7);?></a>
                  <div class="location"><a href="<?php echo base_url('companies/'.$row->company_slug);?>" title="Jobs in <?php echo $row->company_name;?>"><?php echo $row->company_name;?></a> &nbsp;-&nbsp; <?php echo $row->city;?></div>
                  <div class="date"><?php echo ucwords(_date_locale_format(strtotime($row->dated), 'MMM dd, y'));?></div>
                </div>
                <div class="col-md-5">
                  <?php
			  	if($is_already_applied=='yes'):
			  ?>
                  <a href="javascript:;" class="applybtngray">Ver</a>
                  <?php else:?>
                  <a href="<?php echo base_url('jobs/'.$row->job_slug);?>" class="applybtn">Ver</a>
                  <?php endif;?>
                  
                </div>
                <div class="clearfix"> </div>               
              </div>              
            </div>
             <p><?php echo word_limiter(strip_tags(str_replace('&nbsp;',' ',$row->job_description)), 35);?></p>
          </li>
          <?php 
				  			endforeach;
							else: ?>
          <div class="result-not-found">
            Ningún resultado encontrado
          </div>
          <?php endif;?>
        </ul>
      </div>
      
      <!--Pagination-->
      
      <div class="paginationWrap pag-wrap-v2" style="margin-top:10px;">
        <?php echo ($result)?$links:'';?>
        </div>
    </div>
    <?php $this->load->view('common/right_ads');?>
  </div>
</div>
</div>
<!--/Latest Jobs Block-->
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
</body>
</html>