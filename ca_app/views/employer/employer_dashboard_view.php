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
<div class="container detailinfo">
  <div class="row">
  <div class="col-md-3">
  <div class="dashiconwrp">
    <?php $this->load->view('employer/common/menu/sidebar');?>
  </div>
  </div>
    <div class="col-md-9">
      <div class="row"> 
        <!--Company Info-->
        <div class="col-md-12">
          <div class="userinfoWrp">
            <?php 
              $company_logo = img_pic_company($row->company_logo);
            ?>
            <div class="col-md-2 uploadPhoto">
            	<img src="<?php echo $company_logo; ?>"  />
                <?php if ($row->is_admin == 'yes'): ?>
                <div class="stripBox">
                <form name="frm_emp_up" id="frm_emp_up" method="post" action="<?php echo base_url('employer/edit_company/upload_logo');?>" enctype="multipart/form-data"><input type="file" name="upload_logo" id="upload_logo" accept="image/*" style="display:none;">
                </form>
                
                <a href="javascript:;" class="upload" title="Cargar logo"><i class="fa fa-upload"></i></a>
                  <?php if($row->company_logo!=''):?>
                  <a href="javascript:;" class="remove" id="remove_logo" title="Eliminar logo"><i class="fa fa-trash-o"></i></a>
                  <?php endif;?>
                </div>
              <?php endif; ?>
            </div>
            <div class="col-md-6">
              <h1 class="username"><a href="" target="_blank"><?php echo $row->company_name;?></a></h1>
              <div class="comtxt">Aperturas actuales: <?php echo $total_opened_jobs;?></div>
              <div class="comtxt">Miembros: <?php echo $row->no_of_employees;?></div>
              <div class="comtxt">
                <a href="http://<?php echo $row->company_website;?>" rel="nofollow" target="_blank">
                  <?php echo $row->company_website; ?>      
                </a>
              </div>
            </div>
            <div class="col-md-4">
              <div class="usercel"><?php echo $row->mobile_phone;?></div>
              <?php if ($row->company_email):?>
              <div class="usercel"><?php echo $row->company_email;?></div>
              <?php endif;?>
              <div class="usercel"><?php echo $row->city;?>, <?php echo $row->country;?></div>
              <?php if ($row->is_admin == 'yes'): ?>
                <a href="<?php echo base_url('employer/edit_company');?>" id="edit_company_profileee" class="editLink"><i class="fa fa-pencil">&nbsp;</i> Editar perfil de la empresa</a> 
              <?php endif; ?>
              </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      
      <!--Job Detail-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Acerca de la empresa</b></div>
            <div class="col-md-3 text-right"><a href="javascript:;" id="edit_company_desc" class="editlink" title="Edit"><i class="fa fa-pencil">&nbsp;</i></a></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="companydescription">
          <div class="row">
            <div class="col-md-12">
              <p><?php echo $row->company_description;?></p>
            </div>
          </div>
        </div>
      </div>
      
      <!--Job Application-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Solicitudes de empleo recibidas</b></div>
            <div class="col-md-3 text-right"><a href="<?php echo base_url('employer/job_applications');?>">Ver todas</a></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
          <ul class="myjobList">
          <?php if($result_applied_jobs): 
		  			foreach($result_applied_jobs as $row_applied_job):
		  ?>
            <li class="row">
              <div class="col-md-4"><a href="<?php echo base_url('candidate/view_profile/'.$this->custom_encryption->encrypt_data($row_applied_job->job_seeker_ID));?>" class="view-profile"><?php echo $row_applied_job->first_name.' '.$row_applied_job->last_name;?></a></div>
              <div class="col-md-4"><a href="<?php echo base_url('jobs/'.$row_applied_job->job_slug);?>"><?php echo $row_applied_job->job_title;?></a></div>
              <div class="col-md-2 text-right"><?php echo ucwords(_date_locale_format(strtotime($row_applied_job->applied_date), 'MMM dd, y'));?></div>
              <div class="col-md-2 text-right"> &nbsp;</div>
            </li>
          <?php 	endforeach; 
		  		else:?>
                Ningún resultado encontrado
          <?php endif;?>
          </ul>
        </div>
      </div>
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Mis Empleos publicados para <?php echo $row->company_name;?></b></div>
            <div class="col-md-3 text-right"><a href="<?php echo base_url('employer/post_new_job');?>">Nuevo empleo</a>&nbsp;<a href="<?php echo base_url('employer/my_posted_jobs');?>">Ver todos</a></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="row searchlist"> 
          <!--Job Row-->
          <?php 
				 if($result_posted_jobs):
					foreach($result_posted_jobs as $row_jobs):
					?>
          <div class="col-md-12" id="pj_<?php echo $row_jobs->ID;?>">
            <div class="intlist">
              <div class="col-md-12">
                <div class="col-md-8"> <a href="<?php echo base_url('jobs/'.$row_jobs->job_slug);?>" class="jobtitle"><?php echo word_limiter(strip_tags($row_jobs->job_title),9);?></a>
                  <div class="location">
                    <a href="<?php echo base_url('companies/'.$row->company_slug);?>"><?php echo $row->company_name;?></a> &nbsp;-&nbsp; <?php echo $row_jobs->city;?>
                  </div>
                  <div class="info">
                    <span>
                        <i class="glyphicon glyphicon-calendar"></i>
                      Publicado:
                      <?php echo _date_locale_format(strtotime($row_jobs->dated), 'dd MMM y');?>
                      </span>
                      &nbsp;&nbsp;
                      <span>
                      <i class="glyphicon glyphicon-user"></i>Responsable:
                      <?php echo $row_jobs->employer_name;?>
                    </span>
                  </div>
                </div>

                <div class="clear"> </div>
                <p><?php echo word_limiter(strip_tags($row_jobs->job_description),35);?></p>
              </div>
              <div class="wrap-right-actions">  
                  <a href="<?php echo base_url('employer/edit_posted_job/'.$row_jobs->ID);?>" title="Editar" class="edit-ico" style="display: block;text-align: center;"><i class="fa fa-pencil">&nbsp;</i></a>
                  <?php if($row_jobs->sts=='pending'):?>
                    <span class="label label-warning">Pendiente</span>
                  <?php else:?>
                  <a href="javascript:;" style="text-decoration:none;" id="sts_<?php echo $row_jobs->ID;?>" onClick="update_posted_job_status_employer(<?php echo $row_jobs->ID;?>);" title="<?php echo ($row_jobs->sts=='active')?'Deactivate':'Activate';?> This Job"><span class="label label-<?php echo ($row_jobs->sts=='active')?'success':'danger';?>"><?php echo $row_jobs->sts == "active" ? "Activo" : "Desactivo";?></span></a> 
                  <?php endif;?>    
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <?php 
					endforeach;
				 else:					
				?>
          <div align="center" class="text-red">
            Ningún empleo publicado
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>
    <!--/Job Detail-->
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('employer/common/employers_popup_forms'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<div id="modal-view-profile" class="modal" tabindex="-1" role="dialog"></div>
<script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  $(".fa-upload").click(function(){
	  $("#upload_logo").click();
  });
  $("#upload_logo").change(function(e){

    if (this.files && this.files[0]) {

      var maxAllowedSize = 2 * 1024 * 1024;
      if (this.files[0].size > maxAllowedSize) {
        toastr["error"]("El logo debe ser máximo 2MB");
        this.value = ''
        return false;
      }
  
  	  ext_array = ['png','jpg','jpeg','gif'];	
  	  var ext = $('#upload_logo').val().split('.').pop().toLowerCase();
  	  if($.inArray(ext, ext_array) == -1) {
  		  toastr["error"]('¡Aerchivo no es válido!');
  		  return false;
  	  }
  	 this.form.submit();

    }

  });
});
</script>
</body>
</html>