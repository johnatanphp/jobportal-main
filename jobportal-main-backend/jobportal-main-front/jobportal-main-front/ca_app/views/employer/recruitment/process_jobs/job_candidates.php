<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<style type="text/css">
	.list-applicants {
		padding: 10px 4px;
		border-bottom: 1px solid #ddd;
	}

	.content-btn-right {
		position: absolute;
		right: 0;
		top:5px;
	}
	.content-btn-right button {
		font-size: 12px;
		padding:3px 8px;
		border-radius:8px;
		font-weight: bold;
	}

	.interesting-cv-btn {
		background:#dfdfdf;
		color: #999;
		border:0;
	}
	
	.applybtn {
		background:#1ba6df;
		color: #fff;
		border:0;
	}

	.interesting-cv-btn.selected {
		background: #f1e973;
		color: #a79e0a;
	}

	.application-seen {
		text-align: right;
		font-size: 12px;
		color:#429958;
		font-style: italic;
		padding: 0 0 8px;
	}
</style>
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
						<div class="col-md-12">
							<a class="_link-back" style="color:#fff;" href="#">
								<i class="fa fa-arrow-left" aria-hidden="true"></i>
							</a>
							<b>Solicitudes recibidas para: <a style="color: #fff;" href="#"><?php echo word_limiter(strip_tags($job->job_title), 30);?></a></b></div>
					</div>
				</div>
				<div class="companydescription">
					<div class="row">
						<div class="col-md-12">
							<!-- inicio -->
							<div class="row searchlist"> 
								<!--Job Row-->
								<?php 
								foreach ($all_candidates as $row):

									$age = 0;

									if ($row->dob) {
										$age = date_difference_in_years($row->dob, date("Y-m-d"));
									}
									
									$age = $age > 0 ? $age : ''; 
									$encrypt_id = $this->custom_encryption->encrypt_data($row->ID);
									$row_latest_exp = $this->Jobseeker_experience->get_latest_job_by_seeker_id($row->ID);

									$lastest_job_title = ($row_latest_exp)?word_limiter(strip_tags(ucwords($row_latest_exp->job_title)),15):'';
									$edu_row = $this->Jobseeker_academic->get_record_by_seeker_id($row->ID);

									$latest_education = ($edu_row)?$edu_row->degree_title.' - '.$edu_row->institude.', '.$edu_row->city:'';
									$latest_education = trim(ucwords($latest_education),', ');

									$total_experience = $this->Jobseeker_experience->get_total_experience_by_seeker_id($row->ID);
									$total_experience = number_format((float)$total_experience,'1','.','');
									$total_experience = ($total_experience>0)?$total_experience.' años':'';
									$final_exp ='';
									$total_experience_array = explode('.',$total_experience);

									if (count($total_experience_array) > 1) {

										$year = ($total_experience_array[0]>0)?$total_experience_array[0]:'';
										$year = $year.' '.get_singular_plural($year, 'Año', 'Años');

										$monthval = substr($total_experience_array[1], 0, 1);
										$month = ($monthval>0)?$monthval:'';
										$month = $month.' '.get_singular_plural($month, 'Mes', 'Meses');

										$final_exp = (trim($year)!='' && trim($month)!='')?$year.' y '.$month:$year.' '.$month;
										$final_exp = trim($final_exp);
									} else {
										$final_exp ='Sin experiencia';	
									}

									$keywords_array = explode(', ', isset($row->keywords) ? (string)$row->keywords : '');

									$gender_traslate = array(
										'1' => 'Hombre',
										'2' => 'Mujer'
									);

									$gender = isset($gender_traslate[$row->gender]) ? $gender_traslate[$row->gender] : ''; 
									?>
									
									<div class="list-applicants" >
										
										<div class="col-md-2">

											<a href="<?php echo site_url('candidate/view_application/' . $row->applied_ID);?>" 
											   target="_blank" 
											   class="thumbnail view-profile">
												<img src="<?php echo img_pic_candidate($row->photo); ?>" 
												     alt="<?php echo $row->first_name;?>" 
												     style="max-height:80px;" />
											</a>
										</div>
										<div class="col-md-10">
											<div class="col-md-12">
												<div class="content-btn-right">
													<div id="application-seen-<?php echo $row->applied_ID; ?>" class="application-seen" style="<?php echo $row->application_seen == 1 ? 'display: block;' : 'display: none;'; ?>">
														<i class="glyphicon glyphicon-ok"></i> Visto
													</div>
													<button id="btn-interesting-cv-<?php echo $row->applied_ID; ?>"
															class="interesting-cv-btn <?php echo $row->application_interest == 'yes' ? 'selected' : ''; ?>" 
														    style="<?php echo $row->application_seen != 1 ? 'display: none;' : ''; ?>"
														    data-mark-value="<?php echo $row->application_interest;?>" 
														    data-applied_id="<?php echo $row->applied_ID;?>">
														<i class="glyphicon glyphicon-pushpin"></i>
														Me interesa
													</button>
													<button data-href="<?php echo base_url('candidate/view_application/' . $row->applied_ID);?>" class="applybtn view-profile">Ver más </button>
												</div>
												<div> 
													<a href="<?php echo site_url('candidate/view_application/' . $row->applied_ID);?>" target="_blank" class="devtitle view-profile" title="<?php echo $row->first_name; ?>">
														<?php echo ellipsize(strip_tags($row->first_name), 25); ?>		
													</a>
												</div>
												<div class="aboutloc">
													<?php 
														$info_array = array(
															$gender, 
															$age . ' ' . get_singular_plural($age, 'Año', 'Años'), 
															ucwords((string)$row->city)
														);
														$candidate_info_array = array_filter($info_array, function($item) {
															return trim((string)$item) != '';
														});
														//var_dump ($candidate_info_array);
														if (count($candidate_info_array) > 0) {
															echo join(' - ', $candidate_info_array);
														}
													?>
												</div>
												<div class="devinfo"><?php echo $lastest_job_title;?></div>
												<div class="devexp"><?php echo $final_exp;?></div>
												<div class="devedu"><?php echo $latest_education;?></div>
												<?php $js_skills = $this->Jobseeker_skills->get_records_by_seeker_id($row->ID); ?>
												<?php if (count($js_skills) > 0): ?>
												<div class="devinfo"><strong>Habilidades:</strong>
													<?php 
														$i = 0;
														foreach ($js_skills as $keyword_row):
															$i++;
															if ($i < 5):
													?>
															<a href="#" class="keyword" target="_blank"><?php echo $keyword_row->skill_name;?></a>
													<?php 
															endif; 
														endforeach;
													?>
												</div>
												<?php endif;?>
											</div>
											<div class="clear"> </div>
										</div>
										<div class="clear"></div>
									</div>
								
									<?php 
								endforeach;
								?>
								<?php if (empty($all_candidates)): ?>
									<div class="err" align="center" style="padding: 20px 10px;">
										<h3>Ningún postulante todavía</h3>
									</div>
								<?php endif; ?>
							</div>
						<!-- fin -->
						</div>
					</div>
				</div>
			</div>

			<!--Pagination-->
			<div class="paginationWrap pag-wrap-v2">
				<?php echo $all_candidates ? $links : '' ; ?>       
			</div>
			
		</div>
		<!--/Job Detail--> 
	</div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<div id="modal-view-profile" class="modal" tabindex="-1" role="dialog"></div>
<!-- Modal -->
<div id="modal-show-questions" class="modal fade" role="dialog" style="z-index: 999999999999999;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button id="close-modal-show-questions" type="button" class="close">&times;</button>
        <h4 class="modal-title">Confirme</h4>
      </div>
      <div class="modal-body">
        <p>¿Desea exportar también las preguntas del empleo?</p>
      </div>
      <div class="modal-footer">
        <button id="show-questions-btn-si" type="button" class="btn btn-default btn-primary" >Sí</button>
        <button id="show-questions-btn-no" type="button" class="btn btn-default btn-primary" >No</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	
	$(document).ready(function(){

		$( '.interesting-cv-btn' ).click(function(e) {
			e.preventDefault();
			var interestBtn = $(this);
			var markValue = interestBtn.data('mark-value') == 'yes' ? 'no' : 'yes';
			var appliedId = interestBtn.data('applied_id');
			var url = "<?php echo base_url('employer/job_applications/im_interested_cv/');?>"; 
			var data = {
				applied_id: appliedId,
				mark_value: markValue
			};

			interestBtn.removeClass('selected').html('Enviando...');

			$.ajax({
				data: data,
				type: "POST",
				dataType: "json",
				url: url,
			})
			.done(function( data, textStatus, jqXHR ) {
				var response = data;

				if (response.error) {
					alert(response.error);
					return;
				}

				if (!response.success) {
					alert('¡No se pudo realizar la solicitud!');
					return;
				}

				if (markValue == 'yes') {
					interestBtn.addClass('selected');
				} else {
					interestBtn.removeClass('selected');
				}

				interestBtn.data('mark-value', markValue);
			})
			.fail(function( jqXHR, textStatus, errorThrown ) {
				alert('¡Hubo un error al procesar la solicitud!');
			}).always(function(){
				interestBtn.html('<i class="glyphicon glyphicon-pushpin"></i> Me interesa');
			});
		});
	});
</script>
</body>
</html>