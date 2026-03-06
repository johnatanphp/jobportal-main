<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/template_cv.css'); ?>">
	<title> Mi currículum - <?php echo $row->first_name;?></title>
	<style type="text/css">
		* {
		margin: 0;
		padding: 0;
		border: 0;
		outline:0;
		font-size:100%;
		vertical-align:baseline;
		background:transparent;
		}

		table {
		border-collapse:collapse;
		border-spacing:0;
		}

		body {
		line-height:1;
		color: #333;
		font-family: 'rubik';
		}

		.table-header-tcv tr td {
		padding: 3px;
		}

		.table-header-tcv .description-cv {
		font-size: 14px;
		padding: 4px 0px;
		}

		#content-main-tcv .panel-main {
		margin-top: 10px;
		}

		.title-cv {

		font-weight: bold;
		font-size: 22px;
		padding: 5px 0px;

		}

		.sub-title-cv {
		font-size: 17px;
		padding: 5px 0px;
		}

		.panel-category {

		padding: 6px  5px 20px 5px;
		}

		.panel-category .panel-title {
		font-size: 16px;
		font-weight: bold;
		padding-bottom: 8px;
		border-bottom: 2px solid #333;
		margin-bottom: 3px;
		text-transform: uppercase;

		}

		.panel-content {
		padding: 5px 0px;
		}

		#content-main-tcv table {
		width: 100%;
		}	

		.info-t1 {
		font-size: 14px;
		font-weight: bold;
		}

		.info-t3 {
		color: #666;
		padding-top: 5px;
		}

		.info-date {
		font-size: 13px;
		color: #555;
		font-style: italic;
		}

		.panel-content .tbl-info-personal tr td {

		padding: 8px 4px;
		vertical-align: top;
		}

		.icon {
		display: inline-block;
		width: 15px;
		height: 15px;
		margin: 0px 3px 0 0;
		}

	</style>
</head>
<body>
	<div id="content-main-tcv">
		<div>
			<img width="160" src="<?php echo base_url('public/images/overall_blue.png'); ?>">
		</div>
		<br />
		<div class="header-tcv">
			<table class="table-header-tcv">
				<tr>
					<td>
						<div class="title-cv"><?php echo $row->first_name . ' ' . $row->last_name;?></div>
						<div class="section-data-cv">
							<br />
						
							<div>
								<img class="icon" src="<?php echo base_url('public/images/png/008-email.png'); ?>" /><?php echo $row->email;?>
							</div>
											
							<?php if (!empty($row->country) && !empty($row->city)): ?>
								<div>
									<img class="icon" src="<?php echo base_url('public/images/png/002-tool-1.png'); ?>" />
									<?php echo country_text($row->country);?> - <?php echo $row->city;?>
								</div>
							<?php endif; ?>
							
							<?php if (!empty($row->dob) && !empty($row->gender)): ?>
								<div>
								<img class="icon" src="<?php echo base_url('public/images/png/004-profile.png'); ?>" />
								<?php echo _date_locale_format(strtotime($row->dob), 'dd/MM/y'); ?>  <?php echo "(" . get_age($row->dob) . " años)";?> - <?php echo gender_text($row->gender);?>
								</div>
							<?php endif; ?>
							<?php if (!empty($row->document_type) && !empty($row->document_number)): ?>
								<div>
									<img class="icon" src="<?php echo base_url('public/images/png/011-people-1.png'); ?>" />
									<?php echo document_type_text($row->document_type);?>: <?php echo $row->document_number;?>
								</div>		
							<?php endif; ?>
							<div>
								<?php if (!empty($row->mobile)): ?>
									<span>
										<img class="icon" src="<?php echo base_url('public/images/png/mobile.png'); ?>" />
										<?php echo $row->mobile; ?>
									</span>
									&nbsp;
								<?php endif; ?>

								<?php if (!empty($row->home_phone)): ?>
									<span>
										<img class="icon" src="<?php echo base_url('public/images/png/homepage.png'); ?>" />
										<i class='fa fa-icon-cv icon-home-phone'></i>  <?php echo $row->home_phone; ?>
									</span>
								<?php endif; ?>
							</div>
						</div>
					</td>
					<td style="vertical-align: middle;padding-top: 20px;">
						<img src="<?php echo img_pic_candidate($photo); ?>" width="100">
					</td>
				</tr>
				<tr>
					<td colspan="2">
					
					</td>
				</tr>
			</table>
		</div>
		
		<div class="panel-main">
			<!-- Answers job -->
			<?php if (isset($show_questions) && $show_questions == 'yes' && !empty($result_answers_applicant)): ?>
			<div class="panel-category">
				<div class="panel-title">
					Preguntas del empleo
				</div>
				<div class="panel-content">
					<?php 
					$i = 0;
					foreach ($result_answers_applicant as $row_answer):
					?>
					  <div style="font-weight: bold;"><?php echo (++$i) . ") " . $row_answer->question; ?></div>
					  
					  <div style="padding: 7px 0px 7px 15px;">
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
					<?php 
					endforeach; 
					?>					
				</div>
			</div>
			<?php endif; ?>
			<!-- End Answers job -->

			<!-- Professional Resume -->
			<?php if (!empty($row_additional->summary)): ?>
				<div class="panel-category">
					<div class="panel-title">Resumen profesional</div>
					<div class="panel-content">
						<div>
		              		<?php echo ($row_additional->summary) ? $row_additional->summary : '';?>	
						</div>
					</div>
				</div>
			<?php endif; ?>
			<!-- End Professional Resume -->

			<!-- Panel Experience -->
			<?php if (!empty($result_experience)): ?>
			<div class="panel-category">
				<div class="panel-title">Experiencia</div>
				<?php foreach($result_experience as $row_experience): 
					$start_date = ucwords(_date_locale_format(strtotime($row_experience->start_date), 'MMM y'));
					$end_date = ($row_experience->end_date != null || $row_experience->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_experience->end_date), 'MMM y')) : 'Presente';
				?>
				<div class="panel-content">
					<table>
						<tr>
							<td width="20%" class="info-date"> 
								<?php echo $start_date; ?> - <?php echo $end_date;?>
							</td>
							<td class="info-t1">
								<?php echo $row_experience->job_title;?>
							</td>
						</tr>
						<tr>
							<td width="20%"></td>
							<td class="info-t2">
								<?php echo $row_experience->company_name;?> - <?php echo $row_experience->country;?> 	
							</td>					
						 </tr>
						 <tr>
						 	<td width="20%"></td>
						 	<td class="info-t3">
								<?php echo strip_tags($row_experience->description); ?>
						 	</td>
						 </tr>
					</table>
				</div>
			<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<!-- End Panel Experience -->

			<!-- Panel Educaction -->	
			<?php if (!empty($result_qualification)): ?>
				<div class="panel-category">
					<div class="panel-title">
						Educación
					</div>
					<?php foreach ($result_qualification as $key => $row_qualification):
			
						$start_date = ucwords(_date_locale_format(strtotime($row_qualification->start_date), 'MMM y'));
						$end_date = ($row_qualification->end_date != null || $row_qualification->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_qualification->end_date), 'MMM y')) : 'Presente';               
					?>
					<div class="panel-content">
						<table>
							<tr>
								<td width="20%" class="info-date">
									<?php echo $start_date . ' - ' . $end_date;?>
								</td>
								<td class="info-t1">
									<?php echo $row_qualification->institude;?>
								</td>
							</tr>
							<tr>
								<td width="20%"></td>
								<td class="info-t2">
									<?php echo $row_qualification->degree_title;?> - <?php echo $row_qualification->major;?> 		
								</td>
							 </tr>
						</table>
					</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>		
			<!-- End Panel Educaction -->

			<!-- -Other studies -->	
			<?php if (!empty($result_other_studies)): ?>
			<div class="panel-category">
				<div class="panel-title">
					Otros estudios
				</div>
				<?php foreach($result_other_studies as $row_other_studies):

					$start_date = ucwords(_date_locale_format(strtotime($row_other_studies->start_date), "MMM y"));
	                $end_date = $row_other_studies->end_date != null && $row_other_studies->end_date != '0000-00-00' ? ucwords(_date_locale_format(strtotime($row_other_studies->end_date), "MMM y")) : "Presente"; 
				?>
				<div class="panel-content">
					<table>
						<tr>
							<td class="info-date" width="20%">
								<?php echo $start_date . ' - ' . $end_date;?>
							</td>
							<td class="info-t1">
								<?php echo $row_other_studies->institute;?>
							</td>
						</tr>
						<tr>
							<td width="20%"></td>
							<td>
								<span class="info-t2"><?php echo $row_other_studies->name;?> - <?php echo $row_other_studies->type;?></span> 		
							</td>
						 </tr>
					</table>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<!-- End -Other studies -->
			
			<!-- Skills -->
			<?php if (!empty($result_skills)): ?>
			<div class="panel-category">
				<div class="panel-title">
					Habilidades
				</div>
				<div class="panel-content">
					<div>
						<?php 
							foreach ($result_skills as $skill) {
								$skills_jobseeker[] = $skill->skill_name;
							}
							
							echo implode(', ', $skills_jobseeker);
						?>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<!-- End Skills -->
			
			<?php if (!empty($row_additional->interest) || !empty($row_additional->description) || !empty($row_additional->awards)): ?>
			<!-- Additional Information -->
			<div class="panel-category">
				<div class="panel-title">
					Información adicional
				</div>
				<div class="panel-content">
					<table class="tbl-info-personal">

						<tr>
							<td width="20%"><b>Pretensión Salarial:</b></td>
							<td><?php echo ($row_additional->salary_currency) ? $row_additional->salary_currency . ' ' . $row_additional->salary_min . ' - ' . $row_additional->salary_max : ' - '; ?></td>
						</tr>
						<tr>
							<td width="20%"><b>Intereses:</b> </td>
							<td>
								<?php echo ($row_additional->interest) ? $row_additional->interest : ' - ';?>
							</td>
						</tr>
						<tr>
							<td width="20%"><b>Objetivos:</b></td>
							<td>
								<?php echo ($row_additional->description) ? $row_additional->description : ' - ';?>
							</td>
						</tr>
						<tr>
							<td width="20%">
								<b>Logros / Premios:</b> 
							</td>
							<td>
								<?php echo ($row_additional->awards) ? $row_additional->awards : ' - ';?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		<?php endif; ?>
		<!-- End Additional Information-->
		</div>
	</div>
</body>
</html>
