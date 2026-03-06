<?php 
	$rs_process_is_active = $rs_process->sts == 'active';
	$total_stage = count($stage_items);
	
	$stage_idx = false;
	foreach ($stage_items as $stage_index => $stage_row) {
		if ($stage_row->id == $current_stage) {
			$stage_idx = $stage_index;
			break;
		}
	}

	$stage_offset = 0;

	if ($stage_idx > 1) {
		$stage_offset = $stage_idx - 1;
	}

	if (($stage_idx + 1) == $total_stage) {
		$stage_offset = $stage_idx - 2;
	}

	$stage_lists = array_slice($stage_items, $stage_offset, 3);
?>
<div>
	<div class="content-tab-steps">
		<ul id="tab-steps" class="nav nav-tabs nav-justified">
			<?php if ($total_stage > 3 && $stage_idx >= 2 ): ?>
				<li class="nav-tabs-item-left" style="width: 0.003%;border-bottom: 1px solid #ddd;">	
					<button class="btn btn-sm dropdown-toggle" type="button" data-toggle="modal" data-target="#modal-prev-next-steps">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</button>
				</li>
			<?php endif;?>
			<?php foreach ($stage_lists as $stage_index => $stage_row): ?>
				<li <?php echo $current_stage == $stage_row->id ? 'class="active"' : ''; ?>>
					<a class="js-load-data" data-stage="<?php echo $stage_row->id; ?>" href="#">
						<?php echo $stage_row->name; ?>
						<br />
						(<?php echo $stage_row->count_candidates; ?>)
					</a>
				</li>
			<?php endforeach; ?>
			<?php if ($total_stage > 3 && $stage_idx + 2 < $total_stage): ?>
				<li class="nav-tabs-item-right" style="width: 0.003%;border-bottom: 1px solid #ddd;">
					<button class="btn btn-sm dropdown-toggle" type="button" data-toggle="modal" data-target="#modal-prev-next-steps">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</button>
				</li>
			<?php endif; ?>
		</ul>
	</div>

	<div class="content-main">
		<div class="content-main-load">

			<?php if (!empty($label)): ?>
				<div style="text-align:center;">
					<div style="background:#ffffff;padding:2px;border:1px solid #ccc;display:inline-block;border-radius:5px;">
						<table>
							<tr>
								<td style="padding:3px;" >
									<a href="#" class="btn-show-blocks-view" style="color:#333;">
										<span class="glyphicon glyphicon-chevron-left"></span>
									</a>
								</td>
								<td style="padding:5px;"><b><?php e($label); ?></b></td>
								<td style="padding:3px;" >
									<a href="#" class="btn-show-candidates-view" style="font-weight:bold;font-size:20px;color:#e35f5f;">×</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
			<?php endif; ?>

			<div class="wrapper-stage-header" style="padding-top: 10px;">
				<div class="row">
					<div class="col-sm-5">
						<?php if (count($all_candidates) > 0 && $rs_process_is_active && $current_stage <= 6 && $current_stage > -1): ?>
							<div class="wrapper-menu-options">
								<ul class="menu-options" style="display: inline-block;background: #fff;height: 32px;border-radius: 5px;padding: 0 5px;">
									
										<li class="menu-options-item">
											<input type="checkbox" name="candidate" class="custom-check" id="check-all">
										</li>
										<li class="menu-options-item"></li>

										<?php if (($user_belong_to_company_internal || 
												(!$user_belong_to_company_internal && $current_stage <= 5)) && $stage_idx > 1): ?>
											<li class="menu-options-item" style="vertical-align: top;">
												<div class="dropdown">
													<button type="button" 
															data-toggle="dropdown" 
															title="Mover candidatos" 
															style="background: transparent; border: 0;padding: 3px 4px; font-size: 12px;">
														<span class="glyphicon glyphicon-share-alt rotate-left" style="transform: rotate(180deg);"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-left">

														<?php foreach ($stage_items as $stage_row): ?>
															<?php if ($stage_row->id > -1 && $stage_row->id < $current_stage && $stage_row->id < 6): ?>	
																<li>
																	<a href="#"
																	   data-stage="<?php echo $stage_row->id; ?>"
																	   class="move-candidates-selected">
																		<?php echo $stage_row->name; ?>
																	</a>
																</li>
															<?php endif; ?>
														<?php endforeach;?>
													</ul>
												</div> 
											</li>
										<?php endif; ?>

										<?php if ($user_belong_to_company_internal || 
												(!$user_belong_to_company_internal && $current_stage <= 5)): ?>
											<li class="menu-options-item" style="vertical-align: top;">
												<div class="dropdown">
													<button type="button" 
															data-toggle="dropdown" 
															title="Mover candidatos" 
															style="background: transparent; border: 0;padding: 3px 4px; font-size: 12px;">
														<span class="glyphicon glyphicon-share-alt"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-left">
														<?php foreach ($stage_items as $stage_row): ?>
															<?php if ($stage_row->id > 0 && $stage_row->id > $current_stage && $stage_row->id <= 6): ?>
																<li>
																	<a href="#" 
																	data-stage="<?php echo $stage_row->id; ?>" 
																	class="move-candidates-selected">
																	<?php echo $stage_row->name; ?>    	
																	</a>
																</li>
															<?php endif; ?>

															<?php if ($current_stage == 6 && $stage_row->id == 7): ?>
																<li>
																	<a href="#" class="move-candidates-hired">
																		<?php echo $stage_row->name; ?> 
																	</a>
																</li>
															<?php endif; ?>
														<?php endforeach; ?>
													</ul>
												</div> 
											</li>
										<?php endif; ?>
									
									<?php if ($current_stage < 7): ?>
										<li class="menu-options-item" style="vertical-align: top;">
											<button id="remove-candidates-selected" type="button" title="Remover candidatos" style="background: transparent; border: 0;padding: 3px 6px;font-size: 12px;">
												<span class="glyphicon glyphicon-trash"></span>
											</button>
										</li>
									<?php endif; ?>

								</ul>
							</div>
						<?php endif; ?>
					</div>
					<div class="col-sm-7">

						<div id="wrapper-container-rc-stage" style="display: flex;height: 30px;width: 100%;">
			
							<div id="container-rc-stage-options" style="width:100%;text-align:right;">
										
								<button id="btn-rc-search-active"type="submit" class="btn btn-xs btn-default" style="border: 1px solid #ccc; height: 28px;border-radius: 15px; padding: 4px 10px;">
									<i class="glyphicon glyphicon-search"></i>	
									&nbsp;
									Buscar
								</button>

								<button id="btn-modal-filter-candidate" type="button" class="btn btn-xs btn-default" style="">
									<i class="glyphicon glyphicon-filter"></i>
								</button>	

								<?php if ($rs_process_is_active && $current_stage > -1): ?>

									<span style="color:#aaa;">|</span>
							
									<?php if ($user_belong_to_company_internal && get_session_company_id() == 1): ?>
										
											<button class="btn btn-xs btn-default open-schedule-exam" 
													type="button"
													data-document-id="0"  
													>
												Exámenes
											</button>
									
									<?php endif; ?>

									<?php if ($current_stage == 2):  ?>
										
										<div class="dropdown" style="display: inline-block;border: 1px solid #ccc;">
											<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown" style="padding:1px 5px;">
												Video
											</button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li>
													<a  href="#" 
														id="modal-open-interview-video-indications"
														data-job-id="<?php echo $job->ID; ?>">
														Indicaciones
													</a>
												</li>
												<li>
													<a href="#" id="modal-open-request-massive-video">
														Solicitar videos
													</a>
												</li>
												<li>
													<a href="#" id="modal-open-share-massive-video-email">
														Compartir videos
													</a>
												</li>
											</ul>
										</div>
									<?php endif; ?>
										
									<?php if ($user_belong_to_company_internal): ?>
										<?php if (count($stage_forms) > 0): ?>
											<button class="btn btn-xs btn-default" data-toggle="modal" data-target="#modal-rys-form-list">
												Encuestas
											</button>
										<?php endif; ?>
									<?php endif; ?>

									<?php if ($user_belong_to_company_internal): ?>
										<?php 
											//Etapa de Terna o short list y si el 
											//empleo fue creado por una solicitud de personal
											//Muestra el botón para Enviar la terna
											if ($current_stage == 5 && $job->request_ID):
										?>
												<li class="menu-options-item">
													<button id="btn-send-terna-shortlist" class="btn btn-xs btn-default">
														Enviar Short list
													</button>
												</li>
										<?php endif; ?>
									<?php endif; ?>
	
									<button id="btn-add-candidate" 
									        class="btn btn-xs btn-primary" 
											<?php echo $current_stage >= 7 ? 'disabled' : ''; ?>>
										Agregar
									</button>
									
								<?php endif; ?>
								
								<div class="dropdown" style="display: inline-block;border: 0;">
									<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown" style="border:0;background: transparent;">
										<i class="glyphicon glyphicon-option-vertical"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-right">
										<?php if ($current_stage < 7 && $current_stage > -1): ?>
											<li>
												<a href="#" class="open-notification-ws" 
														data-document-id="0">
													Notificar por WhatsApp
												</a>
											</li>

										<?php endif; ?>
										<?php if ($user_belong_to_company_internal): ?>
											<li>
												<a href="<?php echo site_url('general/rys/Report_seekers_requested_documents/download/' . $job->ID); ?>">
													Reporte Doc. solicitados
												</a>
											</li>
										<?php endif; ?>
									</ul>
								</div>
					
							</div>

							<div id="container-rc-stage-search" style="display: flex;visibility:hidden;width:0;">
								<button id="btn-rc-search-return" type="button" style="border:0;background:transparent;color: #666;">
									<i class="fa fa-arrow-left"></i>
								</button>	
								<?php echo form_open('', ['id' => 'recruitment-candidate-stage-search', 'style' => 'display:flex;height: 30px;border: 1px solid #ccc;border-radius: 20px;padding: 2px 10px;background: #ffffff;width:100%;']); ?>
									<input type="text" 
									       name="search" 
										   style="border: 0;outline:none;padding: 3px;width:100%;" 
										   autocomplete="off" 
										   placeholder="Buscar por nombre, correo o documento de identidad"
										   value="<?php e($filters['search'])?>">
									<button type="submit" style="border:0;background:#fff;color: #666;">
										<i class="glyphicon glyphicon-search"></i>
									</button>
								<?php echo form_close(); ?>		
							</div>



						</div>
										
					
					</div>
				</div>

				<?php if (count($all_candidates) > 0  || ($filters['search'] != '' ||  $filters['is_fit'] != '')): ?>
					<div class="row" style="padding-top: 20px;">
						<div class="col-xs-6">
							<span>
								<span style="font-size: 14px;display: flex;align-items: center;">

								<?php if (count($all_candidates) > 0 && ($filters['search'] != '' ||  $filters['is_fit'] != '')): ?>
										<label class="badge" style="background: #005da4;margin: 0;"><?php echo count($all_candidates) ?></label>
										<span style="padding: 0 6px;font-style: italic;">
											<?php echo (count($all_candidates)  > 1 || count($all_candidates) == 0 ? 'Candidatos encontrados' : 'Candidato encontrado'); ?>
										</span>
								<?php endif; ?>
								</span>
							</span>
						</div>

						<div  class="col-xs-6" style="text-align: right;">
						<?php if ($filters['search'] != '' || $filters['is_fit'] != ''): ?>
						<div style="padding-top: 0px; padding-bottom: 10px;text-align: right;">
							
								<?php if ($filters['search'] != ''): ?>
									<span style="display: inline-block;border: 0x solid #ccc;padding: 0px 5px;border-radius: 6px;background: #f6f6f6;box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">
										<div style="display: flex;align-items: center;">
											<span style="font-style: italic;font-weight: bold;font-size: 11px;">
												Búsqueda: <?php echo ellipsize(_e($filters['search']), 30); ?>
											</span>
											<button id="recruitment-candidate-stage-clear" style="background: transparent;border:0;padding: 0 5px;font-size: 18px;">
												×
											</button>
										</div>
									</span> 
								<?php endif; ?>
								<?php if ($filters['is_fit'] != ''): ?>
									<span style="display: inline-block;border: 0x solid #ccc;padding: 0px 5px;border-radius: 6px;background: #f6f6f6;box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">
										<div style="display: flex;align-items: center;">
											<span style="font-style: italic;font-weight: bold;font-size: 11px;">
												Aptitud estado: <?php echo $filters['is_fit'] == 1 ? 'Apto' : 'Con riesgo'; ?>
											</span>
											<button id="recruitment-candidate-stage-clear" style="background: transparent;border:0;padding: 0 5px;font-size: 18px;">
												×
											</button>
										</div>

									</span> 
								<?php endif; ?>
							
						</div>
					<?php endif; ?>
						</div>

						<div class="col-xs-4 hide">
							<div style="text-align: right; display: none;">
								<button id="btn-modal-filter-candidateS" type="button" class="btn btn-xs btn-default" style="border: 0;background: transparent;text-decoration: underline;">
									Filtros
									<i class="glyphicon glyphicon-filter"></i>
								</button>	
							</div>
						</div>
					</div>


				<?php endif; ?>

			</div>

			<div id="wrapper-candidates" style="padding-top: 5px;">
				<div class="row">
					<!--Job Row-->
					<?php foreach ($all_candidates as $row): ?>
						<?php 
							$count_candidate_process = $this->Recruitment_candidate->count_active_process($rs_process->id, $row->ID); 
						?>
						<div class="col-md-6">
							<div class="wrapper-candidate" data-candidate-id="<?php echo $row->ID; ?>"  >
								<table width="100%">
									<tr>
										<td width="5%">
											<?php if ($rs_process_is_active && $current_stage <= 6 && $current_stage > -1): ?>
												<label class="label-check">
													<input type="checkbox" name="candidate_ids[]" value="<?php echo $row->ID; ?>" data-phone="<?php echo $row->mobile; ?>" data-name="<?php e(mb_strtoupper($row->first_name . ' ' . $row->last_name)); ?>" class="custom-check check-candidate">
													<!-- <input type="hidden" name="candidate_names[]" value="<?php echo e(mb_strtoupper($row->first_name . ' ' . $row->last_name)); ?>" class="custom-check check-candidate">
													<input type="hidden" name="candidate_phone[]" value="<?php echo $row->mobile; ?>" class="custom-check check-candidate"> -->
												</label>
											<?php endif; ?>
										</td>
										<td style="width: 25%; text-align:center;">
											<a href="#" 
											target="_blank" 
											class="seeker-view-profile"
											data-url-detail="<?php echo site_url('employer/recruitment_candidates/detail_process_job/' . $rs_process->id . '/' . $row->ID);?>"
											data-name="<?php e(mb_strtoupper($row->first_name . ' ' . $row->last_name)); ?>"
											data-url-pic="<?php echo img_pic_candidate($row->photo); ?>">
												<img src="<?php echo img_pic_candidate($row->photo); ?>" 
													alt="<?php echo $row->first_name;?>" style="max-height:80px;border-radius: 5px;" 
											data-phone="<?php echo $row->mobile; ?>" />
											</a>
											
											<div>
												<?php 
													$candidate_is_fit = $row->is_fit;
												?>
												<?php if ($current_stage == 7 || $current_stage == -1): ?>
													<?php if (!$row->contracted): ?>
														<span style="border-radius: 3px;text-align: center;display: block;font-size: 9px;font-weight: 600;padding: 2px;color: #555;background: #e7e7e7;max-width: 100px;margin: 4px auto;">
															SIN CONTRATAR
														</span>
													<?php endif; ?>

													<?php if ($row->contracted): ?>
														<span style="border-radius: 3px;text-align: center;display: block;font-size: 9px;font-weight: 600;padding: 2px;color: #25750b;background: #e0ebdc;max-width: 95px;margin: 4px auto;">
															CONTRATADO
														</span>
													<?php endif; ?>
												<?php endif; ?>
											</div>
										</td>
										<td style="vertical-align: top;">
											<div style="padding: 5px 6px;">
												<?php if ($rs_process_is_active): ?>
													<div style="position: relative;text-align: right;">
														<ul class="menu-item">

															<li class="menu-item-item">
																<div class="dropdown">
																	<button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
																		<span class="glyphicon glyphicon-pencil"></span>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a class="emailModal" 
																			   title="Actualizar Correo" 
																			   href="#" 
																			   data-candidate-id="<?php echo $row->seeker_ID; ?>" data-candidate-email="<?php echo $row->email; ?>" >
																				Actualizar correo
																			</a>
																			<a class="phoneModal" title="Actualizar número de celular" href="#" data-candidate-id="<?php echo $row->seeker_ID; ?>" data-candidate-mobile="<?php echo phone_number_format($row->mobile); ?>">
																				Actualizar número de celular
																			</a>
																			<a class="calendarModal" href="#" title="Actualizar fecha de nacimiento" data-candidate-id="<?php echo $row->seeker_ID; ?>" data-candidate-birthdate="<?php echo $row->dob; ?>">
																				Actualizar fecha de nacimiento
																			</a>
																			<a class="btn-seeker-update-city" href="#"title="Actualizar ubicación" data-candidate-id="<?php echo $row->seeker_ID; ?>" data-candidate-city="<?php echo $row->city; ?>">
																				Actualizar ubicación
																			</a>
																			<a class="btn-seeker-update" href="#" title="Actualizar datos" data-candidate-id="<?php echo $row->seeker_ID; ?>">
																				Actualizar datos
																			</a>
																		</li>
																	</ul>
																</div>
															</li>

															<?php if (!$row->discarded): ?>
																<li class="menu-item-item">
																	<div class="dropdown">
																		<button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
																			<span class="glyphicon glyphicon-option-vertical"></span>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			
																			<?php $forms_assigned = $this->Rys_form_seeker->get_forms_assigned_by_seeker($row->ID); ?>
																			
																			<?php if ($current_stage < 7): ?>
																				<li>
																					<a class="stop-tracking-candidate" href="#">Descartar candidato</a>
																				</li>
																				<li class="dropdown-divider"></li>
																			<?php endif; ?>

																			<?php if ($user_belong_to_company_internal): ?>
																				<?php if ($current_stage == 7 && $row->contracted == 0): ?>
																					<li>
																						<a class="remove-hiring" href="#">
																							Quitar de contratación
																						</a>
																					</li>
																					<li class="dropdown-divider"></li>
																				<?php endif; ?>
																			<?php endif; ?>

																			<?php if ($user_belong_to_company_internal): ?>
																				<?php foreach ($forms_assigned as $form): ?>
																					<li>
																						<a class="modal-open-sworn-declaration" 
																						   href="#"
																						   data-form-name="<?php echo $form->name; ?>" 
																						   data-form-id="<?php echo $form->form_id; ?>">
																							<?php echo $form->name; ?>
																						</a>
																					</li>
																				<?php endforeach; ?>
																				<?php if (count($forms_assigned) > 0): ?>
																					<li class="dropdown-divider"></li>
																				<?php endif; ?>
																			<?php endif; ?>
																			
																			<?php if ($current_stage == 2): ?>
																				<li>
																					<a class="modal-open-seeker-video" href="#">
																						Video
																					</a>
																				</li>
																				<li class="dropdown-divider"></li>
																			<?php endif; ?>

																			<?php if ($current_stage == 3): ?>
																				<li>
																					<a class="modal-open-interview" href="#">
																						Programar entrevista
																					</a>
																				</li>
																			
																				<?php
																					$interview = $this->Candidate_interview->get_scheduled_interview(
																						$job->ID, 
																						$row->ID
																					);
																				?>
																				<?php if ($interview): ?>
																					<li>
																						<a class="modal-open-interview-comments" href="#">
																							Comentarios de la entrevista
																						</a>
																					</li>
																				<?php endif; ?>
																				<li class="dropdown-divider"></li>
																			<?php endif; ?>

																			<?php if ($current_stage == 4): ?>
																				<li>
																					<a class="modal-open-schedule-evaluation" href="#">Programar evaluación</a>
																				</li>
																				<li class="dropdown-divider"></li>
																			<?php endif; ?>

																			<?php if ($user_belong_to_company_internal): ?>
																				<?php foreach ($rys_documents as $row_doc): ?>
																					<?php if (!$row_doc->document_id) { 
																							continue; 
																						}	
																					?>

																					<?php if ($row_doc->option_type_id == 1): ?>
																						<li>
																							<a class="modal-open-rs-documents" 
																							href="#" 
																							data-rs-document="<?php echo $row_doc->key; ?>">
																								<?php e($row_doc->name); ?>
																							</a>
																						</li>
																					<?php endif; ?>

																					<?php if ($row_doc->option_type_id == 2 && $row_doc->id == 3): ?>
																						<li>
																							<a class="modal-open-seeker-screening" 
																							   href="#" 
																							   data-rs-document="<?php echo $row_doc->key; ?>">
																								<?php e($row_doc->name); ?>
																							</a>
																						</li>
																					<?php endif; ?>

																					<?php if ($row_doc->option_type_id == 2 && in_array($row_doc->id, [1, 10])): ?>
																						<li>
																							<a class="modal-open-exam-request-results" 
																							   href="#" 
																							   data-rs-document="<?php echo $row_doc->key; ?>"
																							   data-rs-document-name="<?php e($row_doc->name); ?>">
																								<?php e($row_doc->name); ?>
																							</a>
																						</li>
																					<?php endif; ?>

																					<?php if ($row_doc->option_type_id == 2 && $row_doc->id == 13): ?>
																						<li>
																							<a class="modal-open-rs-other-documents" 
																							href="#" 
																							data-rs-document="other_documents">
																								<?php e($row_doc->name); ?>
																							</a>
																						</li>
																					<?php endif; ?>
																				<?php endforeach; ?>
																			<?php endif; ?>															
																		</ul>
																	</div> 
																</li>
															<?php endif;?>

														</ul> 
													</div>
												<?php endif; ?>

												<div>
													<a href="#" 
													target="_blank" 
													class="seeker-view-profile" 
													data-url-detail="<?php echo site_url('employer/recruitment_candidates/detail_process_job/' . $rs_process->id . '/' . $row->ID); ?>"
													data-name="<?php e(mb_strtoupper($row->first_name . ' ' . $row->last_name)); ?>"
													data-url-pic="<?php echo img_pic_candidate($row->photo); ?>"
													title="<?php echo $row->first_name; ?>">
														<?php echo ellipsize(trim(strip_tags($row->first_name . ' ' . $row->last_name)), 22); ?>		
													</a>

													<div style="font-size: 12px;color:#666;">
														<?php echo $row->email; ?>
													</div>
													
													<?php if ($row->discarded): ?>
														<div class="container-alerts" style="position: relative;padding-top: 3px;">
															<button class="btn btn-xs modal-open-detail-candidate-discarded" 
																	type="button" data-note-discarded="<?php echo $row->comments; ?>" 
																	style="border-radius: 4px;display:inline-block;background: #ccc;font-size: 12px;font-style: italic;">
																Descartado
															</button>
														</div>
													<?php endif; ?>
														
													<?php if (!$row->discarded): ?>
														<div class="container-alerts" style="position: relative;padding-top: 3px;">
															<?php if ($user_belong_to_company_internal): ?>
																<?php if ($candidate_is_fit !== null && $rs_process_is_active && $row->contracted == 0): ?>
																		<span style="display:inline-block;font-size: 9px;padding: 0 1px;background:<?php echo $candidate_is_fit ? '#c9f98e' : '#f6fd86'; ?> ;">
																			<?php echo $candidate_is_fit ? 'Apto' : 'Con riesgo'; ?>
																		</span>
																<?php endif; ?>

																<?php if ($row->form_dj_is_expired && $row->contracted == 0): ?>
																	<span style="background:#f6fd86;font-size:9px;margin-left:4px;">
																		DJ Vencido
																	</span>
																<?php endif; ?>
																	
																<?php if (!empty($row->alert_work_exp_text)) : ?>
																	<span class="alert-work-experiences-overall" 
																		data-document-number="<?php e($row->document_number); ?>"
																		style="cursor:pointer;background:<?php echo $row->alert_work_exp_color; ?>;font-size:9px;margin-left:4px;">
																		<?php e($row->alert_work_exp_text); ?>
																	</span>
																<?php endif; ?>
																<br />
																<?php if ($row->its_data_prosecution) : ?>
																	<span style="color: red; font-size: 12px;">Con observación</span>
																<?php else: ?>
																	<span style="color: green; font-size: 12px;">Sin observación</span>
																<?php endif; ?>
																<?php 
																	echo rys_label_video_qualificacion($job->ID, $row->ID, $current_stage);
																?>

																<?php if ($count_candidate_process > 0 && $row->stage != 7): ?>
																	<span style="background:#f6fd86;padding: 0 1px;font-size:9px;margin-left:4px;display:inline-block;">
																		<a href="#" class="modal-open-active-process-candidate" style="color:#333;text-decoration:none;">
																			En otro proceso
																		</a>
																	</span>
																<?php endif; ?>		
															
															<?php endif; ?>
														</div>
													<?php endif; ?>

												</div>
												<?php if ($current_stage == -1): ?>
													<div style="text-align: right;">
														<span style="font-size: 11px; background: #e9f5ff; padding: 2px 5px;color: #0964b1;border-radius: 3px;"><?php echo $row->stage_name; ?></span>
													</div>
												<?php endif;?>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					<?php endforeach; ?>
					<?php if (count($all_candidates) == 0): ?>
						<div style="text-align:center;padding:35px 0 55px 0;">
							
							<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
								width="50.000000pt" height="50.000000pt" viewBox="0 0 128.000000 128.000000"
								preserveAspectRatio="xMidYMid meet"
								style="opacity: 0.7;">

								<g transform="translate(0.000000,128.000000) scale(0.100000,-0.100000)"
								fill="#000000" stroke="none">
								<path d="M161 1109 l-131 -141 0 -389 c0 -217 4 -399 9 -412 6 -14 20 -35 32
								-46 22 -21 31 -21 392 -21 l369 0 29 29 c23 23 29 38 29 73 l0 43 98 -97 c103
								-102 126 -118 166 -118 31 0 82 42 90 74 11 44 -14 80 -155 222 l-140 141 20
								61 c38 113 23 224 -46 328 l-33 51 0 137 c0 76 -5 146 -10 157 -24 45 -53 49
								-329 49 l-259 0 -131 -141z m671 84 c15 -14 18 -32 18 -134 l0 -118 -67 32
								c-60 28 -79 32 -153 32 -75 0 -93 -4 -150 -31 -78 -37 -141 -99 -178 -174 -24
								-49 -27 -66 -27 -155 0 -122 20 -171 105 -256 96 -97 239 -128 372 -81 l56 20
								21 -22 c16 -17 21 -35 21 -78 0 -93 22 -88 -390 -88 l-359 0 -15 22 c-14 19
								-16 74 -16 399 l0 377 96 4 c94 3 96 4 125 36 28 31 29 36 29 132 l0 100 247
								0 c215 0 249 -2 265 -17z m-557 -170 c-8 -33 -38 -44 -109 -41 l-64 3 86 94
								87 94 3 -64 c2 -35 1 -74 -3 -86z m508 -96 c196 -107 225 -369 57 -520 -85
								-76 -206 -100 -322 -61 -74 24 -167 118 -191 193 -38 120 -10 249 72 331 75
								75 147 102 254 95 54 -3 81 -11 130 -38z m427 -800 c0 -24 -36 -57 -62 -57
								-14 0 -68 47 -163 141 l-142 141 41 39 41 39 143 -143 c78 -79 142 -151 142
								-160z"/>
								<path d="M550 911 c-50 -16 -87 -40 -123 -80 -51 -57 -70 -105 -70 -182 -2
								-155 112 -272 268 -273 118 -1 216 62 260 168 21 50 19 161 -4 211 -46 102
								-126 156 -235 161 -39 2 -82 0 -96 -5z m168 -47 c86 -36 141 -120 142 -217 0
								-137 -93 -231 -230 -231 -110 0 -194 62 -225 166 -20 68 -12 123 29 190 56 94
								182 135 284 92z"/>
								<path d="M500 766 c0 -21 23 -26 120 -26 97 0 120 5 120 26 0 11 -24 14 -120
								14 -96 0 -120 -3 -120 -14z"/>
								<path d="M445 679 c-16 -25 18 -29 196 -27 156 3 184 5 184 18 0 13 -29 15
								-187 18 -128 2 -189 -1 -193 -9z"/>
								<path d="M467 584 c-4 -4 -7 -13 -7 -21 0 -10 33 -13 161 -13 147 0 160 1 157
								18 -3 15 -20 17 -154 20 -82 1 -153 -1 -157 -4z"/>
								</g>
							</svg>
								<h2 style="font-size: 16px;margin-top: 10px;">
									<?php if ($filters['search'] != '' || $filters['is_fit'] != ''): ?>
										¡Ningún candidato encontrado!
									<?php else: ?>
										Ningún candidato
									<?php endif; ?>	
								
								</h2>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<!-- end -->	
		</div>					
	</div>
</div>

<div class="modals">
	<div id="modal-sworn-declaration" class="modal" role="dialog"></div>
	<?php $this->load->view('employer/recruitment/modal/filter_stage_candidates'); ?>
	<?php $this->load->view('employer/recruitment/modal/discard_candidate_process'); ?>
	<?php $this->load->view('employer/recruitment/modal/detail_candidate_discarded'); ?>
	<?php $this->load->view('employer/recruitment/modal/rys_list_form_by_stage'); ?>
	<?php $this->load->view('employer/recruitment/modal/rys_steps'); ?>
	<?php $this->load->view('employer/recruitment/modal/notification_whatsapp'); ?>
</div>

