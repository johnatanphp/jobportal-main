<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<meta name="keywords" content="<?php echo $param;?> Empleos" />
<meta name="description" content="<?php echo $param;?> Empleos ,Busca el mejor empleo. Emlpleos en <?php echo SITE_NAME;?>." />
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
        <div class="employersection">
          
          <div class="col-md-9">            
            <?php echo form_open('resume_search',array('name' => 'rsearch', 'id' => 'rsearch', 'method' => 'get'));?>
            
            <div class="input-group">      
       		<input type="text" name="search" id="resume_params" class="form-control" placeholder="Habilidad o palabra clave" value="<?php echo $param;?>" />
              <span class="input-group-btn">
                 <input type="submit" class="btn" id="resume_submit" value="Buscar" />
              </span>
            </div> 
            
            <?php echo form_close();?> </div>
            
            <div class="col-md-3">            
            
          </div>
          <div class="clear"></div>
        </div>
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
	/*if($this->uri->segment(1)!='search'):
	if($result):
  		$this->load->view('common/left_job_search');
	endif;
	endif;*/
	?>
    
    <!--Mid Col-->
    
    <div class="searchjoblist col-md-<?php echo ($result)?'10':'10';?>"> 
      
      <!--Jobs List-->
      <?php if ($result): ?>
      <div class="boxwraper">
        <div class="titlebar v2">
          <div class="row">
            <div class="col-md-9">
              <?php if ($total_rows > 0): ?>
                <b><?php echo $total_rows ?></b> currículums encontrados
                <?php if ($param != ''): ?>
                  para la búsqueda <b><?php echo $param; ?></b>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            <div class="col-md-3 text-right">
            
            </div>
          </div>
        </div>
        <div class="row searchlist"> 
          
          <!--Job Row-->
          
      <?php 
			foreach($result as $row):
				
        $url_seeker_picture = $row->photo ? file_url($row->photo, 'public/uploads/candidate/thumb') : base_url('public/uploads/candidate/thumb/no_pic.jpg');
              
				$age = date_difference_in_years($row->dob, date("Y-m-d"));
				$encrypt_id = $this->custom_encryption->encrypt_data($row->ID);
				$row_latest_exp = $this->Jobseeker_experience->get_latest_job_by_seeker_id($row->ID);
				
				$lastest_job_title = ($row_latest_exp)?word_limiter(strip_tags(ucwords($row_latest_exp->job_title)),15):'';
				$edu_row = $this->Jobseeker_academic->get_record_by_seeker_id($row->ID);
				
				$latest_education = ($edu_row)?$edu_row->degree_title.' - '.$edu_row->institude.', '.$edu_row->city:'';
				$latest_education = trim(ucwords($latest_education),', ');
				
				$total_experience = $this->Jobseeker_experience->get_total_experience_by_seeker_id($row->ID) ?? 0;
				$total_experience = number_format($total_experience,'1','.','');
				$total_experience = ($total_experience>0)?$total_experience.' años':'';
				$final_exp ='';
				$total_experience_array = explode('.',$total_experience);
				
				if(count($total_experience_array)>1){
					
					$year = ($total_experience_array[0]>0)?$total_experience_array[0]:'';
					$year = $year.' '.get_singular_plural($year, 'Año', 'Años');
					
					$monthval = substr($total_experience_array[1],0,1);
					$month = ($monthval>0)?$monthval:'';
					$month = $month.' '.get_singular_plural($month, 'Mes', 'Meses');
					
					$final_exp = (trim($year)!='' && trim($month)!='')?$year.' y '.$month:$year.' '.$month;
					$final_exp = trim($final_exp);
				}
				else{
					$final_exp ='Sin experiencia';	
				}
				
				$keywords_array = explode(', ',@$row->keywords ?? '');

        $gender = gender_text($row->gender);  
		?>
          <div class="col-md-12">
            <div class="intlist">
              <div class="col-md-2">
                <a href="<?php echo base_url('candidate/view_profile/' . $encrypt_id);?>" target="_blank" class="thumbnail view-profile"><img src="<?php echo $url_seeker_picture; ?>" alt="<?php echo $row->first_name;?>" style="max-height:80px;" /></a>
              </div>
              <div class="col-md-10">
                <div class="col-md-7">
                  <div> <a href="<?php echo base_url('candidate/view_profile/' . $encrypt_id);?>" target="_blank" class="devtitle view-profile"><?php echo word_limiter(strip_tags($row->first_name),7);?></a> <span class="aboutloc">[ <?php echo ucwords($gender);?>, <?php echo $age;?>, <?php echo ucwords($row->city);?> ]</span> </div>
                  <div class="devinfo"><?php echo $lastest_job_title;?></div>
                  <div class="devexp"><?php echo $final_exp;?></div>
                  <div class="devedu"><?php echo $latest_education;?></div>
                  <?php if($keywords_array):?>
                  <div class="devinfo"><strong>Habilidades:</strong>
                    <?php 
				  		$i=0;
				  		foreach($this->Jobseeker_skills->get_records_by_seeker_id($row->ID) as $keyword_row):
				  		$i++;
						if($i<5):
				  ?>
                    <a href="<?php echo base_url('search-resume?search='.$keyword_row->skill_name);?>" class="keyword" target="_blank"><?php echo $keyword_row->skill_name;?></a>
                    <?php endif; endforeach;?>
                  </div>
                  <?php endif;?>
                </div>
                <div class="col-md-5"> <a href="<?php echo base_url('candidate/view_profile/'.$encrypt_id);?>" target="_blank" class="applybtn view-profile">Ver perfil</a>
                  <div class="date"></div>
                </div>
                <div class="clear"> </div>
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <?php 
				  			endforeach;?>
      
        </div>
      </div>
      <?php else: ?>
      <div class="result-not-found">
        Ningún registro encontrado
      </div>
      <?php endif; ?>
      <!--Pagination-->
      
      <div class="paginationWrap pag-wrap-v2">
        <?php echo ($result) ? $links_pagination : '';?>
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
<div id="modal-view-profile" class="modal" tabindex="-1" role="dialog"></div>
</body>
</html>