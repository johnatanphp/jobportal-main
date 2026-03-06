<?php 
  $applied_id = isset($applied_id) ? $applied_id : null;
?>
<div class="detailinfo">
  <div class="row">
    <div class="col-md-12">
    <div id="msg"></div>
      <div class="row"> 
        <!--Company Info-->
        <div class="col-md-12">
        
          <div class="userinfoWrp">
            <div class="col-md-4 uploadPhoto" style="text-align: center;padding-top: 10px;">
            <div>
            	<img src="<?php echo img_pic_candidate($photo); ?>" width="130px;" />
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
				<?php if (!empty($row->dob) || !empty($row->gender)): ?>
					<div class="usercel">
						<i class="fa fa-icon-cv icon-profile"></i>
            <?php if (!empty($row->dob)): ?>
						  <?php echo _date_locale_format(strtotime($row->dob), 'dd/MM/y'); ?>  <?php echo "(" . get_age($row->dob) . " años)"; ?>
            <?php endif; ?>

            <?php if (!empty($row->gender)): ?>
              <?php echo gender_text($row->gender); ?>
            <?php endif; ?>
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

              <?php 
                $data_cv_params = [
                    'seeker_id' => $row->ID
                ];
                
                $url_cv_pdf = $this->url_signer_lib->sign(site_url('candidate/export_cv_pdf/' . $this->custom_encryption->encrypt_data($data_cv_params, 1)));
              ?>
             	<a href="<?php echo $url_cv_pdf; ?>" target="_blank" class="editLink" style="display: inline-block;" title="Exportar CV a Pdf"><i class="fa fa-file-pdf-o">&nbsp;</i> Exportar a pdf</a>
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="clear"></div>
      </div>

      <?php if ($this->session->userdata('is_employer') == TRUE && @$seeker_additional_info && @$seeker_additional_info->emergency_contact_mobile): ?>
        <div class="innerbox2">
          <div class="titlebar">
            <div class="row">
              <div class="col-md-7"><h4 style="font-size:13px;"><b>CONTACTO DE EMERGENCIA</b></h4></div>
              <div class="col-md-5 text-right"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="userinfoWrp" style="border:none;">
                <div class="usercel" style="padding-left:10px;">
                  Parentesco: <?php e($seeker_additional_info->emergency_contact_kinship); ?>
                </div>
                <div class="usercel" style="padding-left:10px;border-bottom:none;">
                  Teléfono: <?php e($seeker_additional_info->emergency_contact_mobile); ?>
                </div>
                <div class="usercel" style="padding-left:10px;">
                  Nombre: <?php e($seeker_additional_info->emergency_contact_name ? $seeker_additional_info->emergency_contact_name : 'Sin especificar'); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <?php if (!empty($result_answers_applicant)): ?>
		<!-- Questions -->
		<div class="innerbox2">
			<div class="titlebar" style="margin-bottom: 7px;">
			  <div class="row">
			    <div class="col-md-9"><b>Preguntas del empleo</b></div>
			  </div>
			</div>
			<div class="experiance">
				<?php 
				$i = 0;
				foreach($result_answers_applicant as $row_answer):
				?>
					<div class="row" style="padding-bottom: 10px;">
						<div class="col-md-12">

						  <label style="display: block;"><?php echo (++$i) . ") " . $row_answer->question; ?></label>
						  <div style="padding-left: 15px;">
						  	<?php
						  		$answer = "";
						  		$answer_data = get_answers_to_question($row_answer->question_ID, $row_answer->applied_ID);

						  		if ($row_answer->type_question != 'checkbox' ||
						  	        $row_answer->type_question != 'multiple_choice_grid'):

						  			$answer = isset($answer_data[0]->answer_value) ? $answer_data[0]->answer_value : "";	

						  	    endif;

						  		if ($row_answer->type_question == "checkbox"):
						  		
									$answer = join(", ", array_map(function($item) {
										return $item->answer_value;
									}, $answer_data));

						  		endif;

						  		if ($row_answer->type_question == "multiple_choice_grid"):
						  		
						  			$answer = join("<br />", array_map(function($item) {
										return $item->answer_value_row . ' - ' . $item->answer_value_column;
									}, $answer_data));

						  		endif;

						  		echo !empty($answer) ? $answer : "Sin respuesta";
						  	?>
						  </div>
						  <div class="action"></div>
						</div>
					</div>
				<?php 
				endforeach; 
				?>
			</div>
		</div>
	<?php endif; ?>

      <!--My CV-->
      <?php if($result_resume):?>
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-7"><b>Mi CV</b></div>
            <div class="col-md-5 text-right"></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
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
              <?php if ($row_resume->is_uploaded_resume): ?>
              <?php $url_download_resume = str_replace('${resume_id}', $row_resume->ID, $url_download_resume); ?>
              	<a href="<?php echo $url_download_resume; ?>">Mi CV <small>(Descargar)</small></a>
              <?php else: ?>
			  	<a href="#">Mi CV</a>
			  <?php endif;?>
              </div>
              <div class="col-md-8"><?php echo _date_locale_format(strtotime($row_resume->dated), "dd MMM, y");?></div>
            </li>
            <?php 	endforeach; 
		  		else:?>
            ¡Ningún currículum cargado todavía!
            <?php endif;?>
          </ul>
        </div>
      </div>
      <?php endif;?>
      
      <!--Job Detail-->
      <?php if($row_additional && $row_additional->summary):?>
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Resumen profesional</b></div>
            <div class="col-md-3 text-right"></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="companydescription">
          <div class="row">
            <div class="col-md-12">
              <p><?php echo ($row_additional->summary) ? character_limiter($row_additional->summary, 500) : '';?></p>
            </div>
          </div>
        </div>
      </div>
      <?php endif;?>
      <!--Experiance-->
      <?php if($result_experience):?>
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Experiencia</b></div>
            <div class="col-md-3 text-right"></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
          <?php 
			if($result_experience):
				foreach($result_experience as $row_experience):
        $start_date = ucwords(_date_locale_format(strtotime($row_experience->start_date), 'MMM y'));
				$end_date = ($row_experience->end_date) ? ucwords(_date_locale_format(strtotime($row_experience->end_date), 'MMM y')) : 'Presente';
		  ?>
          <div class="row expbox">
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
              <div class="action"> </div>
            </div>
          </div>
          <?php endforeach; endif;?>
          <div class="clear"></div>
        </div>
      </div>
      <?php endif;?>
      
      <?php if($result_qualification):?>
      <!--Education-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Educación</b></div>
            <div class="col-md-3 text-right"></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
          <?php 
			if($result_qualification):
				foreach($result_qualification as $row_qualification):
			?>
          <div class="row expbox">
            <div class="col-md-12">
				<?php
				$start_date = ucwords(_date_locale_format(strtotime($row_qualification->start_date), "MMM y"));
        $end_date = ($row_qualification->end_date != null && $row_qualification->end_date != '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_qualification->end_date), 'MMM y')) : 'Presente'; 
				?>

				<div class="title-education">
				<h4><?php echo $row_qualification->institude;?></h4>
				<span> (<?php echo $start_date . " - " . $end_date; ?>)</span>
				</div>
				<ul class="useradon">
				<li><?php echo $row_qualification->degree_title;?> - <?php echo $row_qualification->major;?></li>
				</ul>
              <div class="action"></div>
            </div>
          </div>
          <?php endforeach; endif;?>
          <div class="clear"></div>
        </div>
      </div>
       <?php endif;?>

     <?php if ($result_other_studies):?>
      <!--Education-->
      <div class="innerbox2">
        <div class="titlebar">
          <div class="row">
            <div class="col-md-9"><b>Otros estudios</b></div>
            <div class="col-md-3 text-right"></div>
          </div>
        </div>
        
        <!--Job Description-->
        <div class="experiance">
          <?php 
			if ($result_other_studies):
				foreach($result_other_studies as $row_other_studies):
			?>
          <div class="row expbox">
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
              <div class="action"></div>
            </div>
          </div>
          <?php endforeach; endif;?>
          <div class="clear"></div>
        </div>
      </div>
      <?php endif;?>			        
    </div>
    <!--/Job Detail-->
  </div>
</div>

<script type="text/javascript">
  
  $(document).ready(function(){

    $( '#export-application-pdf' ).off().click(function(e){
      var confirmShowQuestions = $(this).data('confirm-show-questions');
      
      if (confirmShowQuestions == 'yes') {
        e.preventDefault();

        $( '#modal-show-questions' ).modal('show');
        $( '#modal-show-questions' ).data('export-to', 'pdf');   
      }
    });

    $( '#export-application-word' ).off().click(function(e){
      var confirmShowQuestions = $(this).data('confirm-show-questions');
      
      if (confirmShowQuestions == 'yes') {
        e.preventDefault();

        $( '#modal-show-questions' ).modal('show');
        $( '#modal-show-questions' ).data('export-to', 'word');   
      }
    });

    $( '#close-modal-show-questions' ).off().click(function(){
      $( '#modal-show-questions' ).modal('hide');
    });
    
    $( '#show-questions-btn-no').off().click(function(){
      
      var exportTo = $( '#modal-show-questions' ).data('export-to');
      exportCvApplication(exportTo);
      $( '#modal-show-questions' ).modal('hide');

    });

    $( '#show-questions-btn-si').off().click(function(){
      
      var exportTo = $( '#modal-show-questions' ).data('export-to');
      exportCvApplication(exportTo, '?show_questions=yes');
      $( '#modal-show-questions' ).modal('hide');

    });

    function exportCvApplication(exportTo, paramShowQuestions)
    {
      var paramShowQuestions = paramShowQuestions || '';
      
      if (exportTo == 'pdf') {
        window.open("<?php echo base_url('candidate/export_cv_application_pdf/' . $applied_id);?>" + paramShowQuestions);
      } 
      
      if (exportTo == 'word') {
        window.open("<?php echo base_url('candidate/export_cv_application_word/' . $applied_id);?>" + paramShowQuestions);
      }
    }
  });
</script>
