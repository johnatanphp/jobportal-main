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

	.content-main {
		background: #e5e5e5;
		padding: 0 10px;
		border: 1px solid #ddd; 
		border-top: 0;
	}

	.wrapper-candidate {
		padding: 6px 4px;
		height: 105px;
		border-bottom: 1px solid #ccc;
		margin: 5px 0px;
		background: #fff;
		border-radius: 6px;
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

	.menu-stage {
		margin-bottom: 15px;
		width: 100%;
	}

	.menu-stage-item {
		background: #ccc;
		padding: 7px 8px;
		display: inline-block;
		border-radius: 10px;
		text-align: center;
		box-sizing: border-box;
		min-width: 100px;
	}
	
	.wrapper-menu-options {
		text-align: right;
	}

	.menu-options-item {
		display: inline-block;
		padding: 6px 2px;
	}

	.menu-item {
		position: absolute;
		right: 0;
	
	}

	.menu-item-item {
		display: block;
		padding: 0 2px;
	}

	.dropdown .dropdown-toggle {
		padding: 5px;
		border: 0;
		background: #fff;
	}

	.menu-item-item .dropdown .dropdown-toggle {
		padding: 0;
		border: 0;
		background: #fff;
		margin: 0;	
	}

	.label-check {
		color: #357ec6;
		font-size: 13px;
	}

	.ontent-tab-steps {
		position: relative;
	}
	.nav-tabs-item-left,
	.nav-tabs-item-right {
		position: absolute;
		top: 0;
	}

	.nav-tabs-item-left {
		left: 0;
	}

	.nav-tabs-item-right {
		right: 0;
	}

	.stop-tracking-info {
		padding: 10px 3px;
		font-size: 14px;
	}

	.content-action-item {
		padding: 6px 0;
	}

	.info-process-sts {
		color: #013295;
		border: 1px solid #013295;
		background: #fff;
		padding: 2px 3px;
		border-radius: 4px;
		font-size: 13px;
		margin-left: 5px;
	}

	.label-check i,
	.label-radio i {
		color: #333;
		vertical-align:text-bottom;
		font-size: 20px;
	}
	
	.panel-filter label {
		display: block;
		font-size: 16px;
		font-weight: normal;
		margin-bottom: 10px;
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
		<div class="col-md-9">
			<div class="formwraper">
				<div id="input-hidden">
					<input id="job_id" type="hidden" value="<?php echo $job->ID; ?>">
				</div>
				<div class="titlehead">
					<div class="row">
						<div class="col-md-12">
							<a href="<?php echo site_url('employer/recruitment_processes/' . $job->ID); ?>" style="color:#fff;">
								<i class="fa fa-arrow-left" aria-hidden="true"></i>
							</a>
							<b>
								Búsqueda de candidatos - Proceso de reclutamiento y selección
							</b>
						</div>
					</div>
				</div>
				<div class="companydescription">
					<div style="background: #eee; padding: 10px 5px;margin-bottom: 20px;">
						<div class="row">
							<div class="col-md-8">
								<h4 style="display: block;margin-bottom: 15px;">
									<?php echo word_limiter(strip_tags($job->job_title), 40);?>
								</h4>
								<div>
									<?php if ($rs_process->sts == 'suspended'): ?>
										<span style="font-size: 12px;font-style: italic;">
											<b><span class="glyphicon glyphicon-file"></span> Nota:</b> <br />
											<?php echo $rs_process->note; ?> 
										</span>
									<?php endif; ?>
									<?php if ($rs_process->sts == 'finished'): ?>
										<span style="font-size: 12px;font-style: italic;">
											<b><span class="glyphicon glyphicon-file"></span> Nota:</b> <br />
											<?php echo $rs_process->note; ?> 
										</span>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-md-4 content-action" style="text-align: right;">
								<div class="content-action-item">
									Estado del proceso: <span class="info-process-sts"><?php echo rs_process_status_text($rs_process->sts); ?></span>
								</div>
							</div>
						</div>					
					</div>
					<div style="margin-bottom: 20px;">
						<div class="row">
							<div class="col-md-12">
								<div>
									<form action="<?php echo site_url('employer/recruitment_processes/search_candidates/' . $job->ID); ?>" method="get">
										<input type="text" name="query" placeholder="Buscar candidatos por nombre o correo electrónico" autocomplete="off" value="<?php echo $search_query; ?>" style="border: 1px solid #bbb;padding: 5px;margin:0;box-sizing: border-box;display: table-cell;width: 84%">
										<input type="submit" value="Buscar" class="btn btn-primary" style="margin:0;background: #e0e0e0; border:1px solid #bbb; color: #333; box-sizing: border-box;display: table-cell;width: 15%;">
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- start main -->
					<div class="content-main">
						<div style="position: relative;">
							<span style="display:block; padding: 8px 0;font-size: 15px;">
								<?php echo count($all_candidates); ?> candidatos encontrados
							</span>
							<span style="position:absolute;right: 0;top: 10px;">
								<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-filter-candidates">
									<i class="glyphicon glyphicon-filter"></i>
									Filtrar
								</button>
							</span>
						</div>
						<div id="wrapper-candidates">
							<div class="row">
							<!--Job Row-->
							<?php foreach ($all_candidates as $row): ?>
								<?php 
									$candidate_logo = $row->photo ? $row->photo : 'no_pic.jpg';
									
									if (!file_exists(realpath(APPPATH . '../public/uploads/candidate/thumb/' . $candidate_logo))){
										$candidate_logo = 'no_pic.jpg';
									}
									
									$encrypt_id = $this->custom_encryption->encrypt_data($row->ID);
									$count_candidate_process = $this->Recruitment_candidate->count_candidate_active_process($row->ID, $job->ID);
								?>
								<div class="col-md-6">
									<div class="wrapper-candidate" data-candidate-id="<?php echo $row->ID; ?>" data-stage="<?php echo $row->stage; ?>">
										<table width="100%">
											<tr>
												<td width="8%">
												</td>
												<td style="width: 20%">
													<a href="<?php echo site_url('employer/recruitment_candidates/detail_process_job/' . $row->ID . '/' . $job->ID);?>" target="_blank" class="view-profile">
														<img src="<?php echo site_url('public/uploads/candidate/thumb/' . $candidate_logo);?>" alt="<?php echo $row->first_name;?>" style="max-height:80px;" />
													</a>
												</td>
												<td style="vertical-align: top;">
													<div style="padding: 5px 6px;">
														<a href="<?php echo site_url('employer/recruitment_candidates/detail_process_job/' . $row->ID . '/' . $job->ID);?>" target="_blank" class="view-profile" title="<?php echo $row->first_name; ?>">
															<?php echo ellipsize(strip_tags($row->first_name), 25); ?>		
														</a>
									
														<div style="padding-top: 4px;">
															<div style="position: relative;">
																<span style="font-size: 11px;display: block;padding-bottom: 2px;">
																	Etapa actual: 
																	<a href="<?php echo site_url('employer/recruitment_processes/' . $job->ID . '/' . $row->stage); ?>">
																		<?php echo $stage_items[$row->stage]; ?>		
																	</a>
																</span>
																
																<?php if ($row->discarded): ?>
																	<div class="dropdown" style="display: inline-block;">
																		<button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="border-radius: 4px;padding: 2px;display:inline-block;background: #ccc;font-size: 12px;color:#333;font-style: italic;">
																			Descartado
																			<span class="glyphicon glyphicon-chevron-down"></span>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li><a class="modal-open-detail-candidate-discarded" href="#" data-note-discarded="<?php echo $row->comments; ?>">Más detalle</a></li>
																		</ul>
																	</div>
																<?php endif; ?>
					
																<?php if ($count_candidate_process > 0): ?>
																	<div style="float: right;">
																		<i style="color: #ed7d00" title="Este candidato está activo en otro proceso de reclutamiento y selección" class="glyphicon glyphicon-warning-sign"></i>
																	</div>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										</table>
									</div>
								</div>
							<?php endforeach; ?>
							<?php if (empty($all_candidates)): ?>
								<div class="err" align="center" style="padding: 20px 10px;">
									<h4>Ningún candidato</h4>
								</div>
							<?php endif; ?>
						</div>
					</div>
						<!-- end -->						
					</div>
					<!-- end main -->
				</div>
			</div>
		</div>
		<!--/Job Detail--> 
		
		<!--Pagination-->
		<div class="paginationWrap"></div>
	</div>
</div>
<!-- Modal -->
<div id="modal-filter-candidates" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open('', array('method' => 'get')); ?>
		<input type="hidden" name="search_query" autocomplete="off" value="<?php echo $search_query; ?>" >

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal">&times;</button>
		    <h4 class="modal-title">Filtrar candidatos</h4>
		  </div>
		  <div class="modal-body">
		    <div class="panel-filter">
		      <div class="filter-title"><h4>Por etapas</h4></div>
		      <label>
		    	<select name="stage" class="form-control">
		    		<option value="all">Todas</option>
		    		<?php foreach ($stage_items as $stage_index => $stage_name): ?>
		    			<?php $stage_selected = $filters['stage'] == $stage_index ? 'selected="selected"' : ''; ?>
		    			<option value="<?php echo $stage_index; ?>" <?php echo $stage_selected; ?>><?php echo $stage_name; ?></option>
		    		<?php endforeach; ?>
		    	</select>    
		      </label>
		    </div>
		    <div class="panel-filter">
		      <div class="filter-title"><h4>Estado</h4></div>
		      <label>
		        <input class="filter-radio" type="radio" name="sts_discarded" value="all" <?php echo $filters['sts_discarded'] == 'all' ? 'checked="checked"' : ''; ?>>Todos
		      </label>
		      <label>
		        <input class="filter-radio" type="radio" name="sts_discarded" value="1" <?php echo $filters['sts_discarded'] == '1' ? 'checked="checked"' : ''; ?>>Descartados
		      </label>
		      <label>
		        <input class="filter-radio" type="radio" name="sts_discarded" value="0" <?php echo $filters['sts_discarded'] == '0' ? 'checked="checked"' : ''; ?>>Activos
		      </label>
		    </div>
		  </div>
		  <div class="modal-footer">
		    <button type="submit" class="btn btn-default" >Filtrar</button>
		  </div>
		  <?php echo form_close(); ?>
		</div>
	</div>
</div>

<div id="modal-view-profile" class="modal" tabindex="-1" role="dialog"></div>
<div id="modal-stop-tracking" class="modal fade" role="dialog"></div>
<div id="modal-work-references" class="modal fade" role="dialog"></div>
<div id="modal-screnning" class="modal fade" role="dialog"></div>
<div id="modal-evaluations" class="modal fade" role="dialog"></div>

<div id="modal-active-process-candidate" class="modal fade" role="dialog"></div>

<?php $this->load->view('employer/recruitment/modal/finish_process'); ?>
<?php $this->load->view('employer/recruitment/modal/suspend_process'); ?>
<?php $this->load->view('employer/recruitment/modal/add_candidate_process'); ?>
<?php $this->load->view('employer/recruitment/modal/discard_candidate_process'); ?>
<?php $this->load->view('employer/recruitment/modal/detail_candidate_discarded'); ?>

<!-- End Modal -->
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 

<script type="text/javascript">
	$(document).ready(function() {

		function moveCandidatesStage(data) 
	 	{	
	 		var url = "<?php echo base_url('employer/recruitment_candidates/move_candidates_stage'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				toastr["success"]("¡Los candidatos han sido movidos!");
	 				window.location.reload();
	 			} else {
	 				toastr["error"]("¡No se pudo mover los candidatos!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		function removeCandidatesStage(data) 
	 	{	
	 		var url = "<?php echo base_url('employer/recruitment_candidates/remove_candidates_stage'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				toastr["success"]("¡Los candidatos han sido eliminados!");
	 				window.location.reload();
	 			} else {
	 				alert("¡Ha ocurrido un error!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		function stopTrakingCandidate(data)
		{
	 		var url = "<?php echo base_url('employer/recruitment_candidates/stop_tracking_candidate'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				toastr["success"]("¡El candidato ha sido descartado!");
	 				$( "#modal-stop-tracking" ).modal('hide');
	 				window.location.reload();
	 			} else {
	 				alert("¡Ha ocurrido un error!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		function followUpCandidate(data)
		{
	 		var url = "<?php echo base_url('employer/recruitment_candidates/follow_up_candidate'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				toastr["success"]("¡El candidato está en seguimiento!");
	 				window.location.reload();
	 			} else {
	 				alert("¡Ha ocurrido un error!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		function suspendProcess()
		{
			var data = {
				job_id: $( "#job_id" ).val(),
				note: $( "#suspend-process-note" ).val()
			};

	 		var url = "<?php echo base_url('employer/recruitment_processes/suspend_process'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				toastr["success"]("¡El proceso ha sido suspendido!");
	 				window.location.reload();
	 			} else {
	 				alert("¡Ha ocurrido un error!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		function resumeProcess()
		{
			var data = {
				job_id: $( "#job_id" ).val(),
			};

	 		var url = "<?php echo base_url('employer/recruitment_processes/resume_process'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				toastr["success"]("¡El proceso ha sido reanudado!");
	 				window.location.reload();
	 			} else {
	 				alert("¡Ha ocurrido un error!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		function finishProcess()
		{
			var data = {
				job_id: $( "#job_id" ).val(),
				note: $( "#finish-process-note" ).val()
			};

	 		var url = "<?php echo base_url('employer/recruitment_processes/finish_process'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				toastr["success"]("¡El proceso ha sido terminado!");
	 				window.location.reload();
	 			} else {
	 				alert("¡Ha ocurrido un error!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		function saveCandidateWorkReferences()
		{
			var data = {
				candidate_id: $( "#modal-work-references").data('candidate-id'),
				job_id: $( "#job_id" ).val(),
				work_references: $( "#work-references" ).val()
			};

	 		var url = "<?php echo base_url('employer/recruitment_selection/save_candidate_work_references'); ?>";
	 		$.post(url, data, function(response) {
	 			var status = response.success;
	 			if (status) {
	 				window.location.reload();
	 			} else {
	 				alert("¡Ha ocurrido un error!");
	 			}
	 		}, 'json')
	 		.fail(function(){
	 			alert("¡Ha ocurrido un error!");
	 		});
		}

		// $( ".wrapper-candidate" ).mouseover(function() {
		// 	$(this).find('.menu-item').show();
		// }).mouseout(function() {
  //   		$(this).find('.menu-item').hide();
  // 		});

  		// $( "#btn-add-candidate" ).click(function() {
  		// 	$( "#modal-add-candidate" ).modal("show");
  		// });

  		$( "#remove-candidates-selected" ).click(function(e) {

  			var candidatesSelected = $( "input[name='candidate_ids[]']:checked" ).length;
  
  			if (!candidatesSelected) {
  				return;
  			}

  			if (window.confirm("¿Está seguro de remover los candidatos seleccionados?")) {
  				var data = $( "#wrapper-candidates :input" ).serialize() + "&job_id=" + $( "#job_id" ).val();
  				removeCandidatesStage(data);
  			}
  		});

  		$(document).on("click", ".remove-candidate", function(e) {
  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
  			
  			if (window.confirm("¿Está seguro de remover el candidato?")) {
  				var data = "candidate_ids[]=" + candidateId + "&job_id=" + $( "#job_id" ).val();
  				removeCandidatesStage(data);
  			}
  		});

  		$( ".move-candidates-selected" ).click(function(e) {

  			var candidatesSelected = $( "input[name='candidate_ids[]']:checked" ).length;
  
  			if (!candidatesSelected) {
  				return;
  			}

  			if (window.confirm("¿Está seguro de mover los candidatos seleccionados de etapa?")) {
  	
  				var data = $( "#wrapper-candidates :input" ).serialize() + 
  				           "&job_id=" + $( "#job_id" ).val() + 
  				           "&stage=" + $(this).data('stage');
 
  				moveCandidatesStage(data);
  			}
  		});

  		$(document).on("click", ".move-candidate", function(e) {
  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");

  			if (window.confirm("¿Está seguro de mover el candidato de etapa?")) {
  				var data = "candidate_ids[]=" + candidateId + 
  				           "&job_id=" + $( "#job_id" ).val() +
  				           "&stage=" + $(this).data('stage');

  				moveCandidatesStage(data);
  			}
  		});

  		$(document).on("click", ".stop-tracking-candidate", function() {

  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
  			var stage = $(this).closest(".wrapper-candidate").data("stage");

  			$( "#modal-stop-tracking" ).data('candidate-id', candidateId);  			
  			$( "#modal-stop-tracking" ).data('stage', stage);
  			$( "#modal-stop-tracking" ).modal('show');
  		});

  		$( "#btn-stop-tracking" ).click(function() {
  			var candidateId = $( "#modal-stop-tracking" ).data('candidate-id');
  			var stage = $( "#modal-stop-tracking" ).data('stage');
  			var job_id = $( "#job_id" ).val();
  			var comments = $( "#stop-tracking-comments" ).val();

			var data = "jobseeker_id=" + candidateId + 
			           "&job_id=" + job_id +
			           "&stage=" + stage + 
			           "&comments=" + comments
  			stopTrakingCandidate(data);
  		});

  		$( ".follow-up-candidate" ).click(function(){
  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
  			var job_id = $( "#job_id" ).val();

  			var data = "jobseeker_id=" + candidateId + 
			           "&job_id=" + job_id;
			followUpCandidate(data);
  		});

  		$( "#modal-open-work-references" ).click(function(e){
  			e.preventDefault();
  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
  			$( "#modal-work-references").data('candidate-id', candidateId);
  			$( "#modal-work-references").modal('show');
  		});

  		$( "#btn-save-work-references" ).click(function(e){
  			e.preventDefault();
  			saveCandidateWorkReferences();
  		});

  		$( ".modal-open-detail-candidate-discarded" ).click(function(){
  			var note = $(this).data('note-discarded');
  			$( "#detail-candidate-discarded" ).html(note != '' ? note : 'Ningún detalle');
  			$( "#modal-detail-candidate-discarded" ).modal('show');
  		});

  		$( "#modal-open-work-references" ).click(function(e){
  			e.preventDefault();
  		
  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
  			var jobId = $( "#job_id" ).val();

  			var url = "employer/_recruitment_selection/candidate_work_references/modal_work_references/" + jobId + '/' + candidateId;
  			$( "#modal-work-references" ).load("<?php echo base_url('" + url + "'); ?>", function(response){
  				$(this).html(response).modal('show');
  			});
  		});

  		$( "#modal-open-scrennig" ).click(function(e){
  			e.preventDefault();
  		
  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
  			var jobId = $( "#job_id" ).val();

  			var url = "employer/_recruitment_selection/candidate_screnning/modal_screnning/" + jobId + '/' + candidateId;
  			$( "#modal-screnning" ).load("<?php echo base_url('" + url + "'); ?>", function(response){
  				$(this).html(response).modal('show');
  			});
  		});

  		$( "#modal-open-evaluations" ).click(function(e){
			e.preventDefault();
  		
  			var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");
  			var jobId = $( "#job_id" ).val();

  			var url = "employer/_recruitment_selection/candidate_evaluations/modal_evaluations/" + jobId + '/' + candidateId;
  			$( "#modal-evaluations" ).load("<?php echo base_url('" + url + "'); ?>", function(response){
  				$(this).html(response).modal('show');
  			});
  		});

  		$( "input[type='checkbox']" ).check();
  		$( ".filter-radio" ).radio();
	});
</script>
</body>
</html>