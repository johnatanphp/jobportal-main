<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<style type="text/css">
	.ui-autocomplete { 
		z-index:99999999; 
	}
	
	.sys-banner {
        background: #fdfdfd;
        border: 1px solid #e0e0e0;
        border-bottom-left-radius: 10px; 
        border-bottom-right-radius: 10px; 
    }
        
    .container-process-resume {
        background: #fdfdfd;
        padding: 3px;   
    }
        
    .container-process-resume .label-process-detail {
        background: #fff;
        color: #333;
        padding: 6px 10px;
        margin: 0 2px;
        border-radius: 30px;
        font-weight: normal;
        font-size: 14px;
        border: 1px solid #e0e0e0;
    }
        
    .container-process-resume .label-process-status {
        background: #005da4;
        color: #ffffff;
        padding: 6px 10px;
        border-radius: 30px;
    }
	
	.companydescription {
	    padding: 0;
	}
	
	.formwraper {
	    border: 0;
	}
	
	.content-menu-steps {
	    padding-top: 0px;
	}
	
	.content-menu-steps ul {
	    list-style: none;
	}
	
	.content-menu-steps ul li {
	    list-style: none;
		margin: 10px 0;			
	}
	
	.content-menu-steps .btn-sl-filter {		
		border-radius: 8px;
		padding: 5px 10px;
		background: #ffffff;
		color: #333333;
		margin: 0px 5px;
		display: block;
		border: 1px solid #e0e0e0;
	}
	
	.btn-sl-filter.btn-sl-filter-active {
    	color: #065da4;
        background: #fff;
        border: 1px solid #d5d6d7;
        border-left-color: 065da4;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        border-left: 3px solid #085c9b;
	}
		
	.content-main {
		background: #fdfdfd;
		padding: 20px 10px;
		border: 0;
		border-top: 0;
		border-radius: 10px;
		margin-top: 20px;
		border: 1px solid #e0e0e0;
	}

	.wrapper-candidate {
    	padding: 6px 10px;
        height: 100px;
        border: 1px solid #e0e0e0;
        margin: 5px 0;
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
		display: none;
	}

	.menu-item-item {
		display: block;
		padding: 0 2px;
	}

	.menu-item-item .dropdown .dropdown-toggle {
		padding: 0;
		border: 0;
		background: #fff;
		margin: 0;	
	}

	.nav-tabs.nav-justified > li > a {
		border-bottom: 0;
	}

	.label-check {
		color: #357ec6;
		font-size: 13px;
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

	.content-action-item {
		padding: 3px 0;
	}

	.info-process-sts {
		color: #005da4;
		border: 1px solid #005da4;
		background: #fff;
		padding: 2px 3px;
		border-radius: 4px;
		font-size: 13px;
		margin-left: 5px;
	}

	.list-info-process {
    	list-style-type: none;
  	}

	.list-info-process li {
		display: block;
		border-bottom: 1px solid #444;
		padding: 3px 2px;
		margin-bottom: 2px;
		color: #444;
		font-size: 13px;
	}

	.unselect-candidate {
		color: #83221e;
		border: 1px solid #83221e;
	}

	.select-candidate {
		color: #005da4;
		border: 1px solid #005da4;
	}
	
    .candidate-img-pic {
        width: 65px;
        height: 65px;
        border-radius: 50%; /* Esto hace el círculo */
        object-fit: cover;   /* Esto evita que se estire */
        object-position: center; /* Centra la parte visible de la foto */
    }
    
    .candidate-empty {
        padding: 20px 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        min-height: 150px;
    }
    
    .label-default-no-contract {
        padding: 5px 10px;
        background: #eeeeee;
        color: #333;
        font-weight: normal;
    }
    
    #notify-selection {
        border-radius: 10px;
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
			    <?php if ($this->session->userdata('user_id')): ?>
    				<div class="col-md-3">
    					<div class="dashiconwrp">
         				    <?php $this->load->view('employer/common/menu/sidebar'); ?>
    					</div>
    				</div>
				<?php endif; ?>
				<div class="<?php echo $this->session->userdata('user_id') ? 'col-md-9' : 'col-md-9 col-md-offset-2'; ?>">
					<div class="formwraper">
						<div id="input-hidden">
							<input id="job_id" type="hidden" value="<?php echo $job->ID; ?>" >
							<input id="process_id" type="hidden" value="<?php echo $rs_process->id; ?>" >
						</div>
						<div class="titlehead">
							<div class="row">
								<div class="col-md-12">
								    <?php if ($this->session->userdata('user_id')): ?>
    									<a href="javascript:void(0)" style="color:#fff;" onclick="history.go(-1);">
    										<i class="fa fa-arrow-left" aria-hidden="true"></i>
    									</a>
									<?php endif; ?>
									<b>Terna o Short list</b>
								</div>
							</div>
						</div>
						<div class="companydescription">
    			            <div class="sys-banner">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-3" style="text-align: center;">
                                        <img style="max-height: 70px;max-width: 120px;opacity: 1;"src="https://overall-portal-de-empleo.s3.amazonaws.com/company/logo/b06082f4f423246426819fe4c992ea96.png">
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-9">
                                        
                                        <div class="container-process-resume">
                                            <ul style="list-style-type: none;">
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-10 col-sm-10 col-xs-9">
                                                            <h1>
                                                                <a href="#" style="color: #083c63;" class="process-job-title">
                                                                    <?php echo word_limiter(strip_tags($job->job_title), 40); ?>
                                                                </a>
                                                            </h1>
                                                        </div>
                                                        <div class="col-md-2 col-sm-2 col-xs-3" style="text-align: center;">
                                                            <span class="label label-success label-process-status" style="font-size: 14px; margin: 8px 2px;display: inline-block;">
                                                                <?php echo rs_process_status_text($rs_process->sts); ?> 
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                <li>
                                                <li style="padding: 8px 0;">
                                                    <span class="label label-default label-process-detail"><i class="glyphicon glyphicon-tag"></i> Proceso ID: <?php echo $rs_process->id; ?></span>
                                                    <span class="label label-default label-process-detail"><i class="glyphicon glyphicon-calendar"></i> Abierto el <?php echo _date_locale_format(strtotime($rs_process->created_at), 'dd MMM y'); ?></span>
                                                    <span class="label label-default label-process-detail"><i class="glyphicon glyphicon-user"></i> Postulantes: <?php echo $rs_process->count_candidates; ?></span>
                                                <li>        
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<!-- start main -->
							<div class="content-main">
							    <div class="row">
									<div class="col-md-5">

    									<div class="row">
                                            <div class="col-md-12">
                                               
               									<div class="content-menu-steps">
              										<ul class="">
                           	                            <li>
            												<a href="<?php echo site_url('employer/recruitment_short_list/candidates/show_process/' . $rs_process->id . '/7' . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>" class="btn btn-sl-filter <?php echo $filters['stage'] == '7' ? 'btn-sl-filter-active' : ''; ?>">
               													Contratación <br /> ( <?php echo $count_candidates_stage[7]; ?> )
            												</a>
                                                        </li>
                           	                            <li>
            												<a href="<?php echo site_url('employer/recruitment_short_list/candidates/show_process/' . $rs_process->id . '/6' . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>" class="btn btn-sl-filter <?php echo $filters['stage'] == '6' ? 'btn-sl-filter-active' : ''; ?>">
               													Selección personal <br /> ( <?php echo $count_candidates_stage[6]; ?> )
            												</a>
                                                        </li>
             											<li>
            												<a href="<?php echo site_url('employer/recruitment_short_list/candidates/show_process/' . $rs_process->id . '/5' . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>" class="btn btn-sl-filter <?php echo $filters['stage'] == '5' ? 'btn-sl-filter-active' : ''; ?>">
           													Terna o short list <br /> ( <?php echo $count_candidates_stage[5]; ?> )
            												</a>
             											</li>
              										</ul>
               									</div>
                                            </div>
                                        </div>

									</div>
									<div class="col-md-7">
									
									<div id="wrapper-candidates">
									    <div class="row">
											<div class="col-md-6">	
											    <h3 class="stage-title" style="color: #555;font-weight: bold;text-transform: uppercase;text-align: left;font-size: 15px;padding: 10px 2px;">
													<?php e($stage->name . ' (' . $count_candidates_stage[5] . ')'); ?>
												</h3>
											</div>	
											
                                            <?php if ($rs_process->sts == 'active'): ?>
               									<div class="col-md-6" style="text-align: right;">
    												<button id="notify-selection" class="btn btn-sm btn-default" <?php echo !in_array($stage->id, [5, 6]) ? 'disabled' : ''; ?>>
       													<i class="glyphicon glyphicon-bell"></i> Notificar al Empleador
    												</button>		
               									</div>
                                            <?php endif; ?>
										</div>			
										<div class="row">
											<!--Job Row-->
											<?php  foreach ($all_candidates as $row): ?>
												<?php		
													$encrypt_id = $this->custom_encryption->encrypt_data($row->ID);
												?>
												<div class="col-md-12">
													<div class="wrapper-candidate" 
													     data-candidate-id="<?php echo $row->ID; ?>" 
													     data-candidate-selected="<?php echo $row->stage > 5 ? 'true' : 'false'; ?>">
														<table width="100%">
															<tr>
																<td style="width: 20%" align="center">
																	<a href="<?php echo site_url('employer/recruitment_short_list/candidates/detail_rs_process/' . $rs_process->id . '/'. $row->ID . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>" target="_blank" class="candidate-view-profile">
																		<img class="candidate-img-pic" src="<?php echo img_pic_candidate($row->photo); ?>" alt="<?php echo $row->first_name;?>" style="max-height:80px;" />
																	</a>
																</td>
																<td style="vertical-align: top;">
																	<div style="padding: 5px 6px;">
														
																		<a href="<?php echo site_url('employer/recruitment_short_list/candidates/detail_rs_process/' . $rs_process->id . '/'. $row->ID . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>" target="_blank" class="candidate-view-profile" title="<?php echo $row->first_name; ?>">
																			<?php echo ellipsize(strip_tags($row->first_name . ' ' . $row->last_name), 25); ?>		
																		</a>
																		<div style="font-size: 12px;color:#666;">
																			<?php echo $row->email; ?>
																		</div>
																		<div style="text-align: right; padding-top: 8px;display: flex;align-items: baseline;">

																			<?php if ($row->contracted && $row->stage == 7): ?>
																				<span class="label label-success" style="padding: 5px 10px;">
																					Contratado
																				</span>
																			<?php endif; ?>
																			<?php if (!$row->contracted && $row->stage == 7): ?>
																				<span class="label label-default label-default-no-contract">
																				    Sin contratar
																				</span>
																			<?php endif; ?>

																			<?php if ($row->stage < 7 /*&& $rs_process->sts == 'active'*/): ?>
																				<?php if ($row->stage == 6): ?>
																					<button class="btn btn-sm btn-default btn-block unselect-candidate" >
																						<i class="glyphicon glyphicon-arrow-down"></i> Quitar de la selección
																					</button>
																				<?php else: ?>
																					<button class="btn btn-sm btn-default btn-block select-candidate">
																					    <i class="glyphicon glyphicon-arrow-up"></i> Mover a selección
																					</button>
																				<?php endif; ?>
																			<?php endif; ?>
																			<button style="margin-left: 8px;"
																			        class="btn btn-sm btn-default js-download-rs-doc" 
																					title="Descargar Informe por competencias y Referencias laborales" 
																					data-download-url="<?php echo site_url('employer/recruitment_short_list/candidates/download_rs_documents/' . $job->ID . '/' . $row->ID . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>"
																					data-check-download-url="<?php echo site_url('employer/recruitment_short_list/candidates/check_download_rs_documents/' . $job->ID . '/' . $row->ID . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>">
																				<i class="glyphicon glyphicon-download-alt"></i>
																			</button>
																		</div>
																	</div>
																</td>
															</tr>
														</table>
													</div>
												</div>
											<?php endforeach; ?>

											<?php if (empty($all_candidates)): ?>
												<div class="candidate-empty">
													<h4>Ningún candidato</h4>
												</div>
											<?php endif; ?>
										</div>
										<!-- end -->						
									</div>
									
									</div>
								</div>

							<!-- end main -->
							</div>
						</div>
					</div>
				<!--/Job Detail--> 
				</div>
			</div>
			<!-- Modal -->
			<div id="modal-view-profile" class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" style="width: 100%; max-width: 720px">
					<div class="modal-content">
					</div>
				</div>    
			</div>
			<div id="modal-shortlist-candidate" class="modal" tabindex="-1" role="dialog"></div>
			<div id="modal-show-form-detail" class="modal" role="dialog"></div>
		</div>
	</div>

	<!-- End Modal -->
	<?php $this->load->view('common/bottom_ads');?>
	<!--Footer-->
	<?php $this->load->view('common/footer'); ?>
	<?php $this->load->view('common/before_body_close'); ?>
	<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 

	<script type="text/javascript">
	$(function() {

		function selectCandidate(job_seeker_id) {
			var url = "<?php echo site_url('employer/recruitment_short_list/candidates/select_candidate' . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>";
			var data = {
				process_id: $( "#process_id" ).val(),
				job_seeker_id: job_seeker_id,
			}

			$.post(url, data, function(response) {
				var status = response.success;
				if (status) {
					window.location.reload();
					toastr["success"]("¡El candidato ha sido seleccionado!");
				} else {
					toastr["error"]("¡Ha ocurrido un error!");
				}
			}, 'json')
			.fail(function(){
				alert("¡Ha ocurrido un error!");
			});
		}

		function unselectCandidate(job_seeker_id) {
			var url = "<?php echo site_url('employer/recruitment_short_list/candidates/unselect_candidate'. (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>";
			var data = {
			    process_id: $( "#process_id" ).val(),
				job_seeker_id: job_seeker_id,
			}

			$.post(url, data, function(response) {
				var status = response.success;
				if (status) {
					window.location.reload();
					toastr["success"]("¡El candidato ya no está seleccionado!");
				} else {
					toastr["error"]("¡Ha ocurrido un error!");
				}
			}, 'json')
			.fail(function(){
				alert("¡Ha ocurrido un error!");
			});
		}

		function notifySelection() {
			
			var url = "<?php echo site_url('employer/recruitment_short_list/candidates/notify_selection' . (isset($_GET['t']) ? '?t=' . $_GET['t'] : '')); ?>";
			var data = {
				process_id: $( "#process_id" ).val()
			}

			$( "#notify-selection" ).attr({'disabled': true});

			$.post(url, data, function(response) {
				var status = response.success;
				if (status) {
					toastr["success"]("¡Se ha notificado tu selección de candidatos al empleador!");
				} else {
					toastr["error"]("¡Ha ocurrido un error!");
				}
			}, 'json')
			.fail(function(){
				alert("¡Ha ocurrido un error!");
			}).always(function(){
				$( "#notify-selection" ).attr({'disabled': false});
			})
		}

		$( ".select-candidate" ).click(function(){
			var job_seeker_id = $(this).closest(".wrapper-candidate").data("candidate-id");
			selectCandidate(job_seeker_id);
		});

		$( ".unselect-candidate" ).click(function(){
			var job_seeker_id = $(this).closest(".wrapper-candidate").data("candidate-id");
			unselectCandidate(job_seeker_id);
		});

		$( "#notify-selection" ).click(function(){
			// var candidateSelected = $( 'div[data-candidate-selected="true"]' ).length;

			// if (candidateSelected == 0) {
			// 	toastr["error"]("¡No hay candidatos seleccionados!");
			// 	return;
			// }

			notifySelection();
		});

		$( ".wrapper-candidate" ).mouseover(function() {
			$(this).find('.menu-item').show();
		}).mouseout(function() {
			$(this).find('.menu-item').hide();
		});

		$( ".js-download-rs-doc" ).click(function(e) {
			e.preventDefault();

			const urlDownload = $(this).data('download-url');
			const urlCheckDownload = $(this).data('check-download-url');
			const btnDownload = $(this);
			btnDownload.prop('disabled', true);

			$.get(urlCheckDownload, {}, function(response) {

				if (response.check_download == false) {
					toastr["error"]("¡No hay documentos para descargar!");
				} else if (response.check_download == true) {
					window.location = urlDownload;
				}
			}, 'json').always(function() {
				btnDownload.prop('disabled', false);
			});
		});

		$(document).on("click", ".candidate-view-profile", function(e) {
            e.preventDefault();

            const url = $(this).prop('href');
            const image_loading_url = "<?php echo img_loading_url(); ?>";

            $( '#modal-view-profile .modal-content' ).html(`
                <div class="modal-body">
                    <div style="padding:20px 7px;border:1px solid #cccccc;">
                        <div class="row">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-10">
                                <h4 style="font-weight: bold;padding: 5px 0;text-decoration:underline;display:inline;">Buscando...</h4>
                                <img src="${image_loading_url}" style="width:24px; height:24px;display:inline;margin: 0 5px;">
                            </div>               
                        </div>                    
                    </div>
                </div> 
            `);
            $( '#modal-view-profile' ).modal('show');

            $( '#modal-view-profile .modal-content' ).load(url, function(response) {
                $(this).html(response);
            });
            
            return false;
        });
	});
	</script>
</body>
</html>