<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="<?php echo site_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />

<style type="text/css">
  .ui-autocomplete { 
    z-index:99999999; 
  }
  
  .ui-autocomplete-input {
    width: 100%;
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
    <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
  	</div>
  	</div>
  
    <div class="col-md-9">
    
    <div id="msg"></div>
    <div><?php echo $this->session->flashdata('msg');?></div>
      
      <div class="row"> 
        <!--Company Info-->
        <div class="col-md-12">
          <div class="userinfoWrp">
            <div class="col-md-4 uploadPhoto" style="padding-top: 20px;text-align: center;">
              <div style="width: 160px;position: relative;text-align: center;margin:0 auto;">

                <img src="<?php echo img_pic_candidate($photo); ?>" width="130"/>

                <div class="stripBox">
                <form name="frm_js_up" id="frm_js_up" method="post" action="<?php echo site_url('jobseeker/edit_jobseeker/upload_photo');?>" enctype="multipart/form-data"><input type="file" name="upload_pic" id="upload_pic" accept="image/*" style="display:none;"></form>
                <a href="javascript:;" class="upload" title="Subir foto"><i class="fa fa-upload"></i></a>
                <?php if($row->photo != ''): ?>
                  <a href="javascript:;" class="remove" id="remove_pic" title="Eliminar foto"><i class="fa fa-trash-o"></i></a>
                <?php endif;?>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div>
                <h1 class="username"><?php echo $row->first_name . ' ' . $row->last_name;?></h1>
              </div>
              <?php if (!empty($row->mobile) || !empty($row->home_phone)): ?>
                <div class="usercel">
                  <?php if (!empty($row->mobile)): ?>
                    <i class='fa fa-icon-cv icon-mobile'></i> <?php echo $row->mobile; ?>
                    &nbsp;
                  <?php endif; ?>
                  <?php if (!empty($row->home_phone)): ?>
                    <i class='fa fa-icon-cv icon-home-phone'></i>  <?php echo $row->home_phone; ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
              <div class="usercel"><i class="fa fa-icon-cv icon-email"></i><?php echo $row->email;?></div>
              <?php if (!empty($row->country) && !empty($row->city)): ?>
                <div class="usercel">
                  <i class="fa fa-icon-cv icon-location"></i>
                  <?php echo country_text($row->country);?> - <?php echo $row->city;?>
                </div>
              <?php endif; ?>
              <?php if (!empty($row->dob) && !empty($row->gender)): ?>
                <div class="usercel">
                  <i class="fa fa-icon-cv icon-profile"></i>
                  <?php echo _date_locale_format(strtotime($row->dob), 'dd/MM/y'); ?>  <?php echo "(" . get_age($row->dob) . " años)";?> - <?php echo gender_text($row->gender);?>
                </div>
              <?php endif; ?>              
              <?php if (!empty($row->document_type) && !empty($row->document_number)): ?>
                <div class="usercel">
                  <i class="fa fa-icon-cv icon-dn"></i>
                  <?php echo document_type_text($row->document_type);?>: <?php echo $row->document_number;?>
                </div>
              <?php endif; ?>
              <?php if($this->session->userdata('is_employer')==TRUE):?>
                <a href="javascript:;" id="sendcandidatemsg" style="display:none;margin-top: 10px;" class="btn btn-primary btn-sm"><span>Enviar mensaje</span>
                </a>
              <?php endif;?>              
              <a href="<?php echo site_url('jobseeker/my_account');?>" id="edit_jobseeker_profileee" class="editLink" style="display: inline-block;" title="Editar perfil"><i class="fa fa-pencil">&nbsp;</i> Editar perfil</a>
              &nbsp;
              <a href="<?php echo $this->url_signer_lib->sign(site_url('candidate/export_cv_pdf/' .  $this->custom_encryption->encrypt_data(['seeker_id' => $row->ID], 1))); ?>" target="_blank" class="editLink" style="display: inline-block;" title="Exportar CV a Pdf"><i class="fa fa-file-pdf-o">&nbsp;</i> Exportar a pdf</a>
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="clear"></div>
      </div>

      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Gestionar CV</b></div>
            <div class="col-md-3 text-right">
              <a href="javascript:;" class="upload_cv editlink" title="Subir CV">
                Subir CV
              </a> 
            </div>
            
            <form name="frm_js_up_cv" id="frm_js_up_cv" method="post" action="<?php echo site_url('jobseeker/edit_jobseeker/upload_cv');?>" enctype="multipart/form-data">
              <input type="file" name="upload_resume" id="upload_resume" style="display:none;" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            </form>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="companydescription">
          
          <div class="row">
            <div class="col-md-12">
              <ul class="myjobList">
            <?php if($result_resume): 
            foreach($result_resume as $row_resume):
                $file_name = ($row_resume->is_uploaded_resume)?$row_resume->file_name:'';
                $file_array = explode('.',$file_name);
                $file_array = array_reverse($file_array);
                $icon_name = get_extension_name($file_array[0]);
                
                $data_resume_params = [
                    'resume_id' => $row_resume->ID
                ];
                $url_download_resume = $this->url_signer_lib->sign(site_url('resumes/download/' . $this->custom_encryption->encrypt_data($data_resume_params, 1)));
      ?>
            <li class="row" id="cv_<?php echo $row_resume->ID;?>">
              <div class="col-md-4">
              <i class="fa fa-file-<?php echo $icon_name;?>-o">&nbsp;</i>
              <?php if($row_resume->is_uploaded_resume): ?>
                <a href="<?php echo $url_download_resume ;?>">Mi CV</a>
              <?php else: ?>
          <a href="#">Mi CV</a>
        <?php endif;?>
              </div>
              <div class="col-md-4"><?php echo ucwords(_date_locale_format(strtotime($row_resume->dated), "dd MMM, y"));?></div>
              <div class="col-md-2"><?php //echo ($row_resume->is_default_resume=='yes')?'Default':'Mark as Default';?></div>
              <div class="col-md-2 text-right"> <a href="javascript:;" onClick="del_cv(<?php echo $row_resume->ID;?>, '<?php echo $row_resume->file_name;?>')" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
            </li>
            <?php   endforeach; 
          else:?>
            ¡Ningún currículum cargado todavía!
            <?php endif;?>
          </ul>
            </div>
          </div>
        </div>
      </div>
      
      <!--Job Detail-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Resumen profesional</b></div>
            <div class="col-md-3 text-right">
              <a href="#" class="editlink" id="edit_desc">
                <?php echo $row_additional && $row_additional->summary ? 'Cambiar' : 'Añadir'; ?>
              </a>
            </div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="companydescription">
          <div class="row">
            <div class="col-md-12">
              <p><?php echo ($row_additional && $row_additional->summary) ? character_limiter($row_additional->summary,500):'';?></p>
            </div>
          </div>
        </div>
      </div>
      
      <!--Experiance-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Experiencia</b></div>
            <div class="col-md-3 text-right">
              <a href="javascript:;" id="add_exp" class="editlink">
                Añadir
              </a> 
            </div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
      <?php 
			if($result_experience):
				foreach($result_experience as $row_experience):
        $start_date = ucwords(_date_locale_format(strtotime($row_experience->start_date), 'MMM y'));
				$end_date = ($row_experience->end_date != null || $row_experience->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_experience->end_date), 'MMM y')) : 'Presente';
		  ?>
          <div class="row expbox" id="exp_<?php echo $row_experience->ID;?>">
            <div class="col-md-12">
              <div class="title-experience">
                <h4><?php echo $row_experience->job_title;?></h4> 
                <span> (<?php echo $start_date; ?> - <?php echo $end_date;?>)</span>
              </div>
              <ul class="useradon">
                <li class="company">
                  <span>
                    <?php echo $row_experience->company_name;?>  
                  </span>
                  <span class="country">
                   (<?php echo $row_experience->country;?>) 
                  </span>
                </li>
                <li>
                  <?php echo ellipsize(strip_tags($row_experience->description), 200); ?>
                </li>
              </ul>
              <div class="action"><a href="javascript:;" onClick="load_edit_js_exp(<?php echo $row_experience->ID;?>);" title="Editar" class="edit-ico"><i class="fa fa-pencil">&nbsp;</i></a> <a href="javascript:;" onClick="del_exp(<?php echo $row_experience->ID;?>);" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
            </div>
          </div>
          <?php endforeach; endif;?>
          <div class="clear"></div>
        </div>
      </div>
      
      <!--Education-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Educación</b></div>
            <div class="col-md-3 text-right">
              <a href="javascript:;" id="add_education" class="editlink">
                Añadir
              </a> 
            </div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
          <?php 
			if($result_qualification):
				foreach($result_qualification as $row_qualification):
			?>
          <div class="row expbox" id="edu_<?php echo $row_qualification->ID;?>">
            <div class="col-md-12">
              <?php
               $start_date = ucwords(_date_locale_format(strtotime($row_qualification->start_date), 'MMM y'));
               $end_date = ($row_qualification->end_date != null || $row_qualification->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_qualification->end_date), 'MMM y')) : 'Presente';
              ?>

              <div class="title-education">
                <h4><?php echo $row_qualification->major; ?></h4>
                <span>(<?php echo $start_date; ?> - <?php echo $end_date;?>)</span>
              </div>
              <ul class="useradon">
                <li><?php echo $row_qualification->degree_title;?></li>
              </ul>
              <div class="action"><a href="javascript:;" class="btn-edit-education" data-id="<?php echo $row_qualification->ID;?>" title="Editar" class="edit-ico"><i class="fa fa-pencil">&nbsp;</i></a> <a href="javascript:;" onClick="del_edu(<?php echo $row_qualification->ID;?>);" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
            </div>
          </div>
          <?php endforeach; endif;?>
          <div class="clear"></div>
        </div>
      </div>
      <!--Other studies-->

      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Otros estudios</b></div>
            <div class="col-md-3 text-right">
              <a href="javascript:;" id="add_other_study" class="editlink">
                Añadir
              </a> 
            </div>
          </div>
        </div>
      
        <div class="experiance">
          <?php 
      if($result_other_studies):
        foreach($result_other_studies as $row_other_studies):
      ?>
          <div class="row expbox" id="otst_<?php echo $row_other_studies->ID;?>">
            <div class="col-md-12">
              <?php
                $start_date = ucwords(_date_locale_format(strtotime($row_other_studies->start_date), "MMM y"));
                $end_date = $row_other_studies->end_date != null && $row_other_studies->end_date != '0000-00-00' ? ucwords(_date_locale_format(strtotime($row_other_studies->end_date), "MMM y")) : "Presente";
              ?>
              <div class="title-other-studies">
                <h4><?php echo $row_other_studies->institute;?></h4>
                <span> (<?php echo $start_date . " - " . $end_date?>)</span>
              </div>
              <ul class="useradon">
                <li><?php echo $row_other_studies->name;?> - <?php echo $row_other_studies->type;?></li>
              </ul>
              <div class="action"><a href="javascript:;" onClick="load_edit_other_studies(<?php echo $row_other_studies->ID;?>);" title="Editar" class="edit-ico"><i class="fa fa-pencil">&nbsp;</i></a> <a href="javascript:;" onClick="del_other_studies(<?php echo $row_other_studies->ID;?>);" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
            </div>
          </div>
          <?php endforeach; endif;?>
          <div class="clear"></div>
        </div>
      </div>

      <!--Job Application-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Mis postulaciones</b></div>
            <?php if(is_array($result_applied_jobs) && count($result_applied_jobs) > 5): ?>
              <div class="col-md-3 text-right">
                <a href="<?php echo site_url('jobseeker/my_jobs');?>" class="editlink">Ver todas</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
          <ul class="myjobList">
            <?php if($result_applied_jobs): 
		  			foreach($result_applied_jobs as $row_applied_job):
		  ?>
            <li class="row" id="aplied_<?php echo $row_applied_job->applied_id;?>">
              <div class="col-md-4"><a href="<?php echo site_url('jobs/'.$row_applied_job->job_slug);?>"><?php echo $row_applied_job->job_title;?></a></div>
              <div class="col-md-4"><a href="<?php echo site_url('companies/'.$row_applied_job->company_slug);?>"><?php echo $row_applied_job->company_name;?></a></div>
              <div class="col-md-2 text-right"><?php echo ucwords(_date_locale_format(strtotime($row_applied_job->applied_date), 'MMM dd, y'));?></div>
              <div class="col-md-2 text-right"> <a href="javascript:;" onClick="del_applied_job(<?php echo $row_applied_job->applied_id;?>);" title="Eliminar" class="delete-ico"><i class="fa fa-times">&nbsp;</i></a></div>
            </li>
            <?php 	endforeach; 
		  		else:?>
            Ningún resultado encontrado
            <?php endif;?>
          </ul>
        </div>
      </div>
      
      <!--Languages-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Mi información adicional</b></div>
            
            <div class="col-md-3 text-right">
              <a href="<?php echo site_url('jobseeker/additional_info');?>" class="editlink">
                <?php echo $row_additional && $row_additional->description ? 'Cambiar': 'Añadir'; ?>
              </a>
            </div>
          
          </div>
        </div>
        
        
        <div class="experiance"> 
          <ul class="myjobList">
            <li class="row">
              <div class="col-md-2"><strong>Pretensión Salarial:</strong></div>
              <div class="col-md-10"><?php echo ($row_additional && $row_additional->salary_currency) ? $row_additional->salary_currency . ' ' . $row_additional->salary_min . ' - ' . $row_additional->salary_max : ' - '; ?></div>
            </li>
            <li class="row">
              <div class="col-md-2"><strong>Intereses:</strong></div>
              <div class="col-md-10"><?php echo ($row_additional && $row_additional->interest)?character_limiter($row_additional->interest,150):' - ';?></div>
            </li>
            <li class="row">
              <div class="col-md-2"><strong>Objetivos:</strong></div>
              <div class="col-md-10"><?php echo ($row_additional && $row_additional->description)?character_limiter($row_additional->description,500):' - ';?></div>
            </li>

            <li class="row">
              <div class="col-md-2"><strong>Logros / Premios:</strong></div>
              <div class="col-md-10"><?php echo ($row_additional && $row_additional->awards)?character_limiter($row_additional->awards,350):' - ';?></div>
            </li>
          </ul> 
        </div>
      </div>
      
      <!--My CV-->
      
    </div>
    <!--/Job Detail-->   
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<!-- Profile Popups -->
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo site_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo site_url('public/js/validate_jobseeker.js');?>" type="text/javascript"></script>
<?php $this->load->view('jobseeker/common/jobseekes_popup_forms'); ?>
<?php $this->load->view('jobseeker/common/modal_education_add'); ?>

<script type="text/javascript">
  $(document).ready(function() {

    validate_range_dates('#exp_start_year', '#exp_start_month', '#exp_completion_year', '#exp_completion_month', '#exp_working');
    validate_range_dates('#otst_start_year', '#otst_start_month', '#otst_completion_year', '#otst_completion_month', '#otst_studying');
    
    $( ".institute-search-suggestions" ).autocomplete({
      source: baseUrl + "institutes/search_suggestions",
      minLength: 0,
      select: function( event, ui ) {
        console.log( "Selected: " + ui.item.value);
      }
    });

    $( "#studying" ).change(function(){

        var isSelected = $(this).is(':checked');

        $( "#month_end_date" ).attr({'disabled': isSelected});
        $( "#year_end_date" ).attr({'disabled': isSelected});

        if (isSelected) {
          $( "#month_end_date" ).closest('div').removeClass( "has-error" ); 
          $( '.month_end_date_err').remove();
          $( "#year_end_date" ).closest('div').removeClass( "has-error" ); 
          $( '.year_end_date_err').remove();
        }
    });

    $( "#ed_studying" ).change(function(){

        var isSelected = $(this).is(':checked');

        $( "#ed_completion_year" ).attr({'disabled': isSelected});
        $( "#ed_completion_month" ).attr({'disabled': isSelected});

        if (isSelected) {
          $( "#ed_completion_month" ).closest('div').removeClass( "has-error" ); 
          $( '.month_end_date_err').remove();
          $( "#ed_completion_year" ).closest('div').removeClass( "has-error" ); 
          $( '.year_end_date_err').remove();
        }
    });

    $( "#exp_working" ).change(function(){

      var isSelected = $(this).is(':checked');

      $( "#exp_completion_year" ).attr({'disabled': isSelected});
      $( "#exp_completion_month" ).attr({'disabled': isSelected});

      if (isSelected) {
        $( "#exp_completion_month" ).closest('div').removeClass( "has-error" ); 
        $( '.exp_completion_month_err').remove();
        $( "#exp_completion_year" ).closest('div').removeClass( "has-error" ); 
        $( '.exp_completion_year_err').remove();
      }
    });

    $( "#ed_exp_working" ).change(function(){

      var isSelected = $(this).is(':checked');

      $( "#ed_exp_completion_year" ).attr({'disabled': isSelected});
      $( "#ed_exp_completion_month" ).attr({'disabled': isSelected});

      if (isSelected) {
        $( "#ed_exp_completion_month" ).closest('div').removeClass( "has-error" ); 
        $( '.ed_exp_completion_month_err').remove();
        $( "#ed_exp_completion_year" ).closest('div').removeClass( "has-error" ); 
        $( '.ed_exp_completion_year_err').remove();
      }
    });

    $( "#otst_studying" ).change(function(){

      var isSelected = $(this).is(':checked');

      $( "#otst_completion_year" ).attr({'disabled': isSelected});
      $( "#otst_completion_month" ).attr({'disabled': isSelected});

      if (isSelected) {
        $( "#otst_completion_month" ).closest('div').removeClass( "has-error" ); 
        $( '.completion_month_err').remove();
        $( "#otst_completion_year" ).closest('div').removeClass( "has-error" ); 
        $( '.completion_year_err').remove();
      }
    });

    $( "#ed_otst_studying" ).change(function(){

      var isSelected = $(this).is(':checked');

      $( "#ed_otst_completion_year" ).attr({'disabled': isSelected});
      $( "#ed_otst_completion_month" ).attr({'disabled': isSelected});

      if (isSelected) {
        $( "#ed_otst_completion_month" ).closest('div').removeClass( "has-error" ); 
        $( '.completion_month_err').remove();
        $( "#ed_otst_completion_year" ).closest('div').removeClass( "has-error" ); 
        $( '.completion_year_err').remove();
      }
    });

    $( '.btn-edit-education' ).click(function(){

      $( '#edit_education_modal' ).remove();
      $( `<div class="modal fade" id="edit_education_modal"></div>` ).appendTo('body');

      const url = "<?php echo site_url('jobseeker/education/edit_form'); ?>";
      const data = {
        'id': $(this).data('id')
      };
      $.post(url, data, function(view){
        $( '#edit_education_modal' ).html(view).modal('show');
      });
    });

  });
</script>
</body>
</html>