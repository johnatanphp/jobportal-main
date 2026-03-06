<?php 
	$is_session_p3 = $this->session->userdata('current_profile_id') == 3;
	$is_session_p5 = $this->session->userdata('current_profile_id') == 5;
	$current_profile_id = $this->session->userdata('current_profile_id');

	$evicertia_status = [
	    1 => 'SIN FIRMAR',
	    2 => 'EN ESPERA POR FIRMA',
	    3 => 'FIRMADO',
	    4 => 'RECHAZADO'
	];                 
?>
<style type="text/css">
	.list-documents {
	  padding: 1px 20px;
	}

	.list-documents-item {
		position: relative;
		padding: 0 4px;
	}

	.list-documents-item .label {
		right: 0;
		position: absolute;
	}

	#tbl-rys-seeker-document .row-item .col-item {
		padding: 10px 5px;
		border-top: 1px solid #ccc;
	}

	#tbl-rys-seeker-document .row-item:hover {
		background: #eee;
	}

	.document-ok {
	  margin: 0;
	  color: green;
	}

	.list-documents-item-toggle {
		cursor: pointer;
		display: inline-block;
	}

	.detail-document {
		display: none;
		padding-top: 5px;
	}

	.no-document {
		background: #ccc;
		padding: 4px 5px;
		color: #666;
		font-style: italic;
	}

	.btn-rs-options {
		background: #ffffff; 
		font-size: 10px;
		border: none; 
		padding: 0;
	}

	.attach-file-name {
		padding: 8px 5px;
		border: 1px solid #aaa;
		background: #ddd;
		text-align: center;
	}

	.attach-file-name a {
		color: #16548a;
	}

	.detail-document-item {
		padding: 5px 0;
	}

	.detail-document-item-img {
		margin: 0 auto;
		display: block;
	}

	.document-info-item {
		padding: 2px 0;
	}

	.document-info-item p {
		padding: 2px;
	}

	.document-info-item label {
		margin: 0;
	}

	.item-inactive, 
	.item-inactive:hover, 
	.item-inactive:visited,
	.item-inactive:active,
	.item-inactive:focus {
		color: #555555;
		text-decoration: none;
		cursor: default;
	}

	.label {
		font-size: 11px;
	}

	.signal-approved {
		margin-right: 3px;
	}

	.col-item span {
		font-size: 11px;
	}

	.btn-option {
		background: #ffffff;
		padding: 1px 5px;
		border: 1px solid #ccc;
		border-radius: 4px;
	}
</style>
<div style="padding: 10px 0;">
	<div class="candidate-section-content hide">
		<h4 class="candidate-section-content-title">
			Documentos de contratación
		</h4>
	</div> 
	<div class="wrapper-menu-documents">
		<?php if (in_array($current_profile_id, [1, 3]) ): ?>
			<div style="text-align: right;">
				<a href="<?php echo site_url('candidate/download_requested_documents/' . $job_id . '/' . $candidate_id);?>" 
				   class="btn btn-sm btn-default hide">
					Descargar
				</a>
	
				<?php if ($rs_process_candidate && !$rs_process_candidate->contracted): ?>
					<a  href="<?php echo site_url('general/rys/Report_seekers_requested_documents/download/' . $job_id . '/' .  $candidate_id); ?>"
						class="btn btn-sm btn-default hide">
						Exportar Ficha
					</a>
					<a href="#" 
						class="btn btn-sm btn-default hide"
						id="btn-request-documents-notify"
						>
						Solicitar documentos
					</a>

					<div class="dropdown" style="text-align: right;display: inline-block;">
						<button class="btn btn-sm btn-default" type="button" data-toggle="dropdown">
							Link de ingreso
							<span class="glyphicon glyphicon-chevron-down"></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li>
								<a id="btn-document-request-copy-link" href="#">
								    Copiar link de ingreso
								</a>
							</li>
							<li>
								<a id="btn-document-request-send-link-whatsapp" href="#">
									Enviar por WhatsApp
								</a>
							</li>
						</ul>
					</div>
				<?php endif; ?>
			</div>
			<br>
		<?php endif; ?>

		<table id="tbl-rys-seeker-document" width="100%">

			<?php foreach ($contract_documents as $doc): ?>

				<?php 
					if (!$doc->document_id) {
						continue;
					}	
				?>

				<?php if ($doc->option_type_id == 1): ?>

					<?php 
						$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
							$candidate_id, 
							$doc->id,
							null,
							$job_id
						);

						$attachments = $this->Recruitment_contract_document->all([
							'seeker_id' => $candidate_id,
							'document_id' => $doc->id
						]);
					?>
					<tr class="row-item">
						<td width="20" class="col-item" style="vertical-align: top;">

							<div class="list-documents-item-options">

								<?php if (in_array($current_profile_id, [3, 5])): ?>
									<div class="dropdown">
										<button type="button" data-toggle="dropdown" class="btn-rs-options">
											<span class="glyphicon glyphicon-option-vertical"></span>
										</button>
										<ul class="dropdown-menu">
											<li>
												<a href="#" 
											       class="js-manage-contract-documents" 
												   data-title="<?php e($doc->name); ?>"
												   data-seeker-id="<?php e($candidate_id); ?>"
												   data-document-id="<?php e($doc->id); ?>">
													Gestión
												</a>
											</li>
											<?php if (count($attachments) > 0): ?>
												<li>
													<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
														<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
													</a>
												</li>
												<li>
													<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
														Comentarios
													</a>
												</li>	
											<?php endif; ?>
										</ul>
									</div> 
								<?php endif; ?>	
							</div>
						</td>
						<td class="col-item">
							<div class="list-documents-item">
								<a href="#" class="list-documents-item-toggle">
									<?php e($doc->name); ?>
								</a>
							</div>

							<?php if (count($attachments) > 0): ?>
								<?php echo seeker_doc_item_approval($rs_document); ?>
								<div class="detail-document">
									<?php foreach ($attachments as $doc_attach): ?>
										<div class="detail-document-item">
											<div class="row">
												<div class="col-md-12">
													<div class="attach-file-name">
														<a href="<?php echo file_url($doc_attach->file_path); ?>"
														   target="_blank">
															<i class="glyphicon glyphicon-file"></i>
															Ver documento
														</a>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>

								</div>
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>


				<?php if ($doc->option_type_id == 2): ?>

					<?php if (is_enabled_rys_seeker_identification_document($jobseeker) && $doc->id == 1): ?>
						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>

						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">
								<?php if ($is_session_p3 || $is_session_p5): ?>
									<div class="list-documents-item-options">
										<div class="dropdown">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="#" 
														class="js-manage-identification-document"
														data-seeker-id="<?php e($candidate_id); ?>" 
														data-title="<?php e($doc->name); ?>"
														data-document-id="<?php e($doc->id); ?>">
														Gestión
													</a>
												</li>

												<?php if (!empty($identity_documents) ): ?>
													<li>
														<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
															<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
														</a>
													</li>
													<li>
														<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
															Comentarios
														</a>
													</li>
												<?php endif; ?>		
											</ul>
										</div> 
									</div>
								<?php endif; ?>
							</td>
							<td class="col-item">
								
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?>
									</a>
								</div>
								<?php if (empty($identity_documents) != true): ?>

									<?php echo seeker_doc_item_approval($rs_document); ?>
									
									<div class="detail-document">
										<table class="table" width="100%" style="margin-top: 5px;">
											<tr>
												<th>Nombre</th>
												<th>Documento</th>
											</tr>
											<?php foreach ($identity_documents as $identity_doc): ?>
												<?php $url_file = file_url($identity_doc->path); ?>
												<tr>
													<td style="vertical-align: middle;"><?php e($identity_doc->name); ?></td>
													<td>
														<div class="attach-file-name">
															<a href="<?php echo $url_file; ?>" target="_blank">
																<i class="glyphicon glyphicon-file"></i>
																Ver 	
															</a>
														</div>
													</td>
												</tr>
											<?php endforeach; ?>
										</table>
										
										<?php if ($jobseeker->document_type != '1'): ?>
											<b>Permiso para firma de contrato</b>
											
											<?php if ($sign_contract): ?>
												<div class="attach-file-name">
													<a href="<?php echo file_url($sign_contract->file_path); ?>" target="_blank">
														<i class="glyphicon glyphicon-file"></i>
														Ver Permiso	
													</a>
												</div>
											<?php else: ?>
												<div>No cargado</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

					<?php // FICHA DE INGRESO ?>
					<?php if ($doc->group_id == 1): ?>
						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">
								<?php if ($is_session_p3 && ($entry_form || $form_rtps)): ?>
									<div class="list-documents-item-options">
										<div class="dropdown" style="display: inline-block;">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu">
												<?php if ($entry_form || $form_rtps): ?>
													<li>
														<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
															<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
														</a>
													</li>
													<li>
														<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
															Comentarios
														</a>
													</li>
												<?php endif; ?> 		
											</ul>
										</div>
									</div>
								<?php endif; ?> 
							</td>
							
							<td class="col-item">
								<div class="list-documents-item">
									<?php if ($doc->id != 3): ?>
										<a href="#" 
										class="list-documents-item-toggle load-entry-form-view"
										data-url-load="<?php echo site_url('general/jobseeker/entry_forms/load_view/' . @$entry_form->id); ?>"
										data-id="<?php echo @$entry_form->id; ?>">
											<?php e($doc->name); ?> 
										</a>
									<?php endif; ?>		

									<?php if ($doc->id == 3): ?>
										<a href="#" class="list-documents-item-toggle">
											<?php e($doc->name); ?> 
										</a>
									<?php endif; ?>

									<?php if ($form_rtps && $form_rtps->job_id): ?>
										<span class="label <?php echo $form_rtps->evicertia_status == 3 ? 'label-success' : 'label-default'; ?>"  style="display: inline-block;cursor:pointer; float:right;">
											<?php echo isset($evicertia_status[$form_rtps->evicertia_status]) ? $evicertia_status[$form_rtps->evicertia_status] : 'SIN FIRMAR'; ?>
										</span>
									<?php endif; ?>
								</div>
								
								<?php if ($entry_form || $form_rtps): ?>
    								<?php echo seeker_doc_item_approval($rs_document); ?>
    								<div class="detail-document">

										<?php if ($form_rtps && $form_rtps->evicertia_status == 3): ?>
											<div class="attach-file-name">
												<?php 
													$url_file = $this->config->item('evicertia_sitio_url') . '/Evidence/EviSign/' . $form_rtps->evicertia_unique_id;
												?>
												<a href="<?php echo $url_file; ?>" target="_blank">
													<i class="glyphicon glyphicon-file"></i>
													Ver             	
												</a>
											</div>
										<?php endif; ?>

										<?php if ($form_rtps && $form_rtps->evicertia_status != 3): ?>
											<?php $this->load->view('employer/recruitment/common/candidate_form_rtps'); ?>
										<?php endif; ?>

									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

					<?php if ((!$is_session_p5 && $domicile_affidavit) && $doc->id == 4): ?>
						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">
								<?php if ($is_session_p3 && $domicile_affidavit): ?>
									<div class="dropdown" style="display: inline-block;">
										<button type="button" data-toggle="dropdown" class="btn-rs-options">
											<span class="glyphicon glyphicon-option-vertical"></span>
										</button>
										<ul class="dropdown-menu">
											<li>
												<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
													<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
												</a>
											</li>
											<li>
												<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
													Comentarios
												</a>
											</li>		
										</ul>
									</div>
								<?php endif; ?> 
							</td>
							
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?> 
									</a>
									
									<?php if ($domicile_affidavit): ?>
										<span class="label <?php echo $domicile_affidavit->evicertia_status == 3 ? 'label-success' : 'label-default'; ?>"  style="display: inline-block;cursor:pointer; float:right;">
											<?php echo isset($evicertia_status[$domicile_affidavit->evicertia_status]) ? $evicertia_status[$domicile_affidavit->evicertia_status] : 'SIN FIRMAR'; ?>
										</span>
									<?php endif; ?>
									
								</div>
								<?php if ($domicile_affidavit): ?>
									
									<?php echo seeker_doc_item_approval($rs_document); ?>

									<div class="detail-document">
										<div class="attach-file-name">
											<?php 
											if ($domicile_affidavit->evicertia_status == 3) {
												$url_file = $this->config->item('evicertia_sitio_url') . '/Evidence/EviSign/' . $domicile_affidavit->evicertia_unique_id;
											} else {
												$url_file = file_url($domicile_affidavit->attach_file_name);
											}
											?>
											<a href="<?php echo $url_file; ?>" target="_blank">
												<i class="glyphicon glyphicon-file"></i>
												Ver archivo
												<i class="glyphicon glyphicon-download-alt"></i>             	
											</a>
										</div>
									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

					<?php if ((!$is_session_p5 && $declaration_5th_category) && $doc->id == 5): ?>
						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">
								<?php if ($is_session_p3 && $declaration_5th_category): ?>
									<div class="dropdown" style="display: inline-block;">
										<button type="button" data-toggle="dropdown" class="btn-rs-options">
											<span class="glyphicon glyphicon-option-vertical"></span>
										</button>
										<ul class="dropdown-menu">
											<li>
												<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
													<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
												</a>
											</li>
											<li>
												<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
													Comentarios
												</a>
											</li>		
										</ul>
									</div>
								<?php endif; ?> 
							</td>
							
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?> 
									</a>
									
									<?php if ($declaration_5th_category): ?>
										<span class="label <?php echo $declaration_5th_category->evicertia_status == 3 ? 'label-success' : 'label-default'; ?>"  style="display: inline-block;cursor:pointer; float:right;">
											<?php echo isset($evicertia_status[$declaration_5th_category->evicertia_status]) ? $evicertia_status[$declaration_5th_category->evicertia_status] : 'SIN FIRMAR'; ?>
										</span>
									<?php endif; ?>
									
								</div>
								<?php if ($declaration_5th_category): ?>
									
									<?php echo seeker_doc_item_approval($rs_document); ?>

									<div class="detail-document">
										<div class="attach-file-name">
											<?php 
											if ($declaration_5th_category->evicertia_status == 3) {
												$url_file = $this->config->item('evicertia_sitio_url') . '/Evidence/EviSign/' . $declaration_5th_category->evicertia_unique_id;
											} else {
												$url_file = file_url($declaration_5th_category->attach_file_name);
											}
											?>
											<a href="<?php echo $url_file; ?>" target="_blank">
												<i class="glyphicon glyphicon-file"></i>
												Declaración jurada de 5ta categoría
												<i class="glyphicon glyphicon-download-alt"></i>             	
											</a>
										</div>
									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

					<?php if (is_enabled_rys_seeker_spouse_identification_document($jobseeker) && $doc->id == 7): ?>

						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">

								<?php if (($is_session_p3 || $is_session_p5) && $candidate_spouse): ?>
									<div class="list-documents-item-options">
										<div class="dropdown">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
														<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
													</a>
												</li>
												<li>
													<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
														Comentarios
													</a>
												</li>		
											</ul>
										</div> 
									</div>					
								<?php endif; ?>
							</td>
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?> 
									</a>
								</div>

								<?php if ($candidate_spouse): ?>
									<?php echo seeker_doc_item_approval($rs_document); ?>
									<div class="detail-document">
										<div class="row">
											<div class="col-md-6">
												<div class="document-info-item">
													<label>Nombres y apellidos</label>
													<p><?php echo $candidate_spouse->first_name . ' ' . $candidate_spouse->last_name; ?></p>		
												</div>
												<div class="document-info-item">
													<label>Documento de identidad</label>
													<p><?php echo document_type_text($candidate_spouse->document_type) . ' - ' . $candidate_spouse->document_number; ?></p>
												</div>
												<div class="document-info-item">
													<label>Fecha de nacimiento</label>
													<p><?php echo format_date($candidate_spouse->birthdate, 'd/m/Y'); ?></p>		
												</div>
												<?php if ($candidate_spouse->document_type): ?>
													<div class="document-info-item">
														<label>Es Peruano</label>
														<p><?php echo is_peruvian($candidate_spouse->document_type) ? 'SI' : 'NO'; ?></p>		
													</div>
												<?php endif; ?>
											</div>
											<div class="col-md-6">
												<?php $url_file = file_url($candidate_spouse->attached_document_number); ?>
												<div class="attach-file-name">
													<a href="<?php echo $url_file; ?>" target="_blank">
														<i class="glyphicon glyphicon-file"></i>
														Documento identidad       	
													</a>
												</div>
											</div>
										</div>	
									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

					<?php if (is_enabled_rys_seeker_doc_spouse_act($jobseeker, $candidate_spouse) && $doc->id == 8): ?>

						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">
								<?php if (($is_session_p3 || $is_session_p5) && $candidate_spouse): ?>
									<div class="list-documents-item-options">
										<div class="dropdown">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li>
													<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
														<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
													</a>
												</li>
												<li>
													<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
														Comentarios
													</a>
												</li>		
											</ul>
										</div> 
									</div>							
								<?php endif; ?>		
								
							</td>
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?>
									</a>		
								</div>

								<?php if ($candidate_spouse): ?>
							
									<?php echo seeker_doc_item_approval($rs_document); ?>

									<div class="detail-document">
										<div class="row">
											<div class="col-md-6">
												<div class="document-info-item">
													<label>Nombres y apellidos</label>
													<p><?php echo $candidate_spouse->first_name . ' ' . $candidate_spouse->last_name; ?></p>		
												</div>
												<div class="document-info-item">
													<label>Documento de identidad</label>
													<p><?php echo document_type_text($candidate_spouse->document_type) . ' - ' . $candidate_spouse->document_number; ?></p>
												</div>
												<div class="document-info-item">
													<label>Fecha de nacimiento</label>
													<p><?php echo format_date($candidate_spouse->birthdate, 'd/m/Y'); ?></p>		
												</div>
												<?php if ($candidate_spouse->document_type): ?>
													<div class="document-info-item">
														<label>Es Peruano</label>
														<p><?php echo is_peruvian($candidate_spouse->document_type) ? 'SI' : 'NO'; ?></p>		
													</div>
												<?php endif; ?>
											</div>
											<div class="col-md-6">
												<?php if ($candidate_spouse->kinship_cert_attached): ?>
													<div class="attach-file-name">
														<a href="<?php echo file_url($candidate_spouse->kinship_cert_attached); ?>" target="_blank">
															<i class="glyphicon glyphicon-file"></i>
															Acta matrimonio o certificado de convivencia             	
														</a>
													</div>
												<?php endif; ?>
											</div>
										</div>	
									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

					<?php if (is_enabled_rys_seeker_study_certificates($jobseeker) && $doc->id == 9): ?>

						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">

								<?php if ($is_session_p3): ?>
									<div class="list-documents-item-options">
										<div class="dropdown">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="#" 
													   class="js-manage-seeker-study-certificates" 
													   data-seeker-id="<?php e($candidate_id); ?>"
													   data-title="<?php e($doc->name); ?>"
													   data-document-id="<?php e($doc->id); ?>">
														Gestión
													</a>
												</li>	

												<?php if (count($candidate_studies) > 0): ?>
													<li>
														<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
															<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
														</a>
													</li>
													<li>
														<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
															Comentarios
														</a>
													</li>		
												<?php endif; ?>
											</ul>
										</div> 
									</div>
								<?php endif; ?>
							</td>
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?>
									</a>		
								</div>

								<?php if (!empty($candidate_studies)): ?>
									
									<?php echo seeker_doc_item_approval($rs_document); ?>

									<div class="detail-document">
										<?php foreach ($candidate_studies as $study): ?>
											<div class="detail-document-item">
												<div class="row">
													<div class="col-md-6">
														<div>
															<h4 style="font-size: 18px;font-weight: bold;"><?php echo $study->major;?></h4>
															<span><?php echo $study->degree_title;?></span>
														</div>
														<div style="font-size: 15px;">
															<?php echo $study->institude?>
														</div>
													</div>
													<div class="col-md-6">
														<?php if (!empty($study->attached_certificate)): ?>
															<div class="attach-file-name">
																<a href="<?php echo file_url($study->attached_certificate); ?>"
																target="_blank">
																	<i class="glyphicon glyphicon-file"></i>
																	Certificado agregado
																</a>
															</div>
														<?php else: ?>
															<span>
																<i class="glyphicon glyphicon-ban-circle"></i>
																Certificado sin agregar
															</span>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

							</td>
						</tr>
					<?php endif; ?>

					<?php if (is_enabled_rys_seeker_doc_childrens($jobseeker, $candidate_children) && $doc->id == 10): ?>

						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>

						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">

								<?php if (($is_session_p3 || $is_session_p5) && count($candidate_children) > 0): ?>	
									<div class="list-documents-item-options">
										<div class="dropdown">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
														<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
													</a>
												</li>
												<li>
													<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
														Comentarios
													</a>
												</li>		
											</ul>
										</div> 
									</div>
								<?php endif; ?>	
							</td>
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?>
									</a>

									<?php if (!empty($candidate_children)): ?>
										
										<?php echo seeker_doc_item_approval($rs_document); ?>

										<div class="detail-document">
											<?php foreach ($candidate_children as $children): ?>
												<div class="detail-document-item">
													<div class="row">
														<div class="col-md-6">
															<div class="document-info-item">
																<label>Nombres y apellidos</label>
																<p><?php echo $children->first_name . ' ' . $children->last_name; ?></p>		
															</div>
															<div class="document-info-item">
																<label>Documento de identidad</label>
																<p><?php echo document_type_text($children->document_type) . ' - ' . $children->document_number; ?></p>
															</div>
															<div class="document-info-item">
																<label>Fecha de nacimiento</label>
																<p><?php echo format_date($children->birthdate, 'd/m/Y'); ?></p>		
															</div>
															<?php if ($children->document_type): ?>
																<div class="document-info-item">
																	<label>Nació en Perú</label>
																	<p><?php echo born_in_peru($children->document_type) ? 'SI' : 'NO'; ?></p>		
																</div>
															<?php endif; ?>
														</div>
														<div class="col-md-6">
															<?php $url_file = file_url($children->attached_document_number); ?>
															<div class="attach-file-name">
																<a href="<?php echo $url_file; ?>" target="_blank">
																	<i class="glyphicon glyphicon-file"></i>
																	Documento identidad       	
																</a>
															</div>
														</div>
													</div>
												</div>	
											<?php endforeach ?>
										</div>
									<?php endif; ?>
								</div>
							</td>
						</tr>
					<?php endif; ?>

					<?php if (is_enabled_rys_seeker_experience_certificates($jobseeker) && $doc->id == 13): ?>

						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								null,
								$job_id
							);
						?>
						
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">

								<?php if ($is_session_p3): ?>
									<div class="list-documents-item-options">
										<div class="dropdown">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="#" 
													   class="js-manage-seeker-experience-certificates" 
													   data-title="<?php e($doc->name); ?>"
													   data-seeker-id="<?php e($candidate_id); ?>"
													   data-document-id="<?php e($doc->id); ?>">
														Gestión
													</a>
												</li>	
												<?php if (count($candidate_experiences) > 0): ?>
													<li>
														<a href="#" class="js-approve-reject" data-document="<?php echo $doc->id; ?>">
															<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
														</a>
													</li>
													<li>
														<a href="#" class="js-modal-rs-comments" data-document="<?php echo $doc->id; ?>">
															Comentarios
														</a>
													</li>		
												<?php endif; ?>
											</ul>
										</div> 
									</div>
								<?php endif; ?>
							</td>
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?>
									</a>
								</div>

								<?php if (!empty($candidate_experiences)): ?>

									<?php echo seeker_doc_item_approval($rs_document); ?>

									<div class="detail-document">
										<?php foreach ($candidate_experiences as $experience): ?>
											<div class="detail-document-item">
												<div class="row">
													<div class="col-md-6">
														<div>
															<h4 style="font-size: 18px;font-weight: bold;">
																<?php echo $experience->job_title; ?>
															</h4>
															<span><?php echo $experience->job_level; ?></span>
														</div>
														<div style="font-size: 15px;">
															<?php echo $experience->company_name; ?>
														</div>
													</div>
													<div class="col-md-6">
														<?php if (!empty($experience->attached_certificate)): ?>
															<div class="attach-file-name">
																<a href="<?php echo file_url($experience->attached_certificate); ?>"
																	target="_blank">
																	<i class="glyphicon glyphicon-file"></i>
																	Certificado agregado
																	<i class="glyphicon glyphicon-download-alt"></i>
																</a>
															</div>
														<?php else: ?>
															<span>
																<i class="glyphicon glyphicon-ban-circle"></i>
																Certificado sin agregar
															</span>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>

				<?php endif; ?>

				<?php if ($doc->id == 16): ?>
					<?php if (is_enabled_rys_seeker_rys_form_affidavit($jobseeker) && $assignment_form): ?>
						
						<?php 
							$rs_document = $this->Jobseeker_required_document->get_recruitment_document(
								$candidate_id, 
								$doc->id,
								$assignment_form->assignment_id,
								$job_id
							);
						?>
						
						<tr class="row-item">
							<td width="20" class="col-item" style="vertical-align: top;">
								<?php if (($is_session_p3 || $is_session_p5) && $assignment_form): ?>
									
									<div class="list-documents-item-options">
										<div class="dropdown">
											<button type="button" data-toggle="dropdown" class="btn-rs-options">
												<span class="glyphicon glyphicon-option-vertical"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="#" 
													class="js-approve-reject" 
													data-document="<?php echo $doc->id; ?>"
													data-ref-id="<?php echo $assignment_form->assignment_id; ?>"
													>
														<?php echo seeker_doc_item_is_approved($rs_document) ? 'Quitar aprobación' : 'Aprobar'; ?>
													</a>
												</li>
												<li>
													<a href="#" 
													class="js-modal-rs-comments" 
													data-document="<?php echo $doc->id; ?>"
													data-ref-id="<?php echo $assignment_form->assignment_id; ?>"
													>
														Comentarios
													</a>
												</li>		
											</ul>
										</div> 
									</div>
								<?php endif; ?>
							</td>
							<td class="col-item">
								<div class="list-documents-item">
									<a href="#" class="list-documents-item-toggle">
										<?php e($doc->name); ?>
									</a>
								</div>

								<?php if ($assignment_form): ?>
									<?php echo seeker_doc_item_approval($rs_document); ?>
									<div class="detail-document">
										<?php $this->load->view('jobseeker/question_forms/partials/form_answer'); ?>
									</div>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
	</div>
</div>
<div id="cont-modal"></div>

<div id="modal-add-edit-documents" class="modal modal-add-edit-seeker-requested-docs" role="dialog">
  <div class="modal-dialog modal-lg" style="height:95%;">
    <!-- Modal content-->
    <div class="modal-content" style="height:90%">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" style="height:90%;">
        <iframe src="" frameborder="0" style="width:100%;height:100%;"></iframe>
      </div>
    </div>
  </div>
</div>

<div id="modal-add-edit-contract-documents" class="modal modal-add-edit-seeker-requested-docs" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<div id="modal-add-edit-identification-document" class="modal modal-add-edit-seeker-requested-docs" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal modal-add-edit-seeker-requested-docs">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<div id="modal-add-edit-seeker-study-certificates" class="modal modal-add-edit-seeker-requested-docs" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal modal-add-edit-seeker-requested-docs">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<div id="modal-add-edit-seeker-experience-certificates" class="modal modal-add-edit-seeker-requested-docs" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<script type="text/javascript">
	
	$( '#btn-request-documents-options' ).click(function(){
		$( '#modal-request-documents' ).modal('show');
	});

	$( '#btn-request-documents-notify' ).click(function(){
		var url = "<?php echo site_url('employer/recruitment_entry/recruitment_candidates/request_documents'); ?>";
		var data = {
			candidate_id: "<?php echo $candidate_id; ?>",
			job_id: "<?php echo $job_id; ?>"
		}

		$( "#btn-request-documents-notify" ).attr({'disabled': true });

		$.post(url, data, function(response) {
			var status = response.success;
			if (status) {
				toastr["success"]("¡Solicitud de documentos enviada al candidato!");
			} else {
				toastr["error"]("¡No se pudo enviar la solicitud de documentos!");
			}
		}, 'json')
		.fail(function(){
			toastr["error"]("¡Ha ocurrido un error!");
		})
		.always(function(){
			$( "#btn-request-documents-notify" ).attr({'disabled': false });
		});
	});

	$( '#btn-document-request-copy-link' ).click(function(){

		$( '#btn-document-request-copy-link' ).attr({'disabled': true });

		var url = "<?php echo site_url('employer/recruitment_entry/recruitment_document_requests/copy_link'); ?>";
		var data = {
			seeker_id: "<?php echo $candidate_id; ?>",
			job_id: "<?php echo $job_id; ?>"
		}
		
		$.post(url, data, function(response) {
			var status = response.success;
			
			if (status) {
				navigator.clipboard.writeText(response.link_url);
				toastr["success"]("¡Link copiado en portapapeles!");
				return;
			} 
			
			toastr["error"](response.message);
			
		}, 'json')
		.fail(function(){
			toastr["error"]("¡Ha ocurrido un error!");
		})
		.always(function(){
			$( '#btn-document-request-copy-link' ).attr({'disabled': false });
		});
	});

	$( '#btn-document-request-send-link-whatsapp' ).click(function(){

		$( '#btn-document-request-send-link-whatsapp' ).attr({'disabled': true });

			var url = "<?php echo site_url('employer/recruitment_entry/recruitment_document_requests/send_link_by_whatsapp'); ?>";
			var data = {
				seeker_id: "<?php echo $candidate_id; ?>",
				job_id: "<?php echo $job_id; ?>"
			}

			$.post(url, data, function(response) {
			var status = response.success;

			if (status) {
				toastr["success"](response.message);
				return;
			} 

			toastr["error"](response.message);

		}, 'json')
		.fail(function(){
			toastr["error"]("¡Ha ocurrido un error!");
		})
		.always(function(){
			$( '#btn-document-request-copy-link' ).attr({'disabled': false });
		});
	});

	$( ".list-documents-item-toggle" ).click(function(){

		var listDocument = $(this).closest('td');
		var detailDocument = listDocument.find('.detail-document');
		var itemNotVisible = detailDocument.is(':not(:visible)');
		
		$( ".detail-document" ).hide();

		if (itemNotVisible) {
			detailDocument.fadeIn();
		}
	});

	$( ".js-approve-reject" ).click(function(){
		var buttonLink = $(this);
		var signalApproved = buttonLink.closest('tr').find('td:eq(1)').find('.signal-approved');

		var url = "<?php echo site_url('employer/recruitment_entry/recruitment_candidates/document_approve_reject'); ?>";
		var data = {
			seeker_id: $( "#candidate-id" ).val(),
			document: $(this).data('document'),
			job_id: "<?php echo $job_id; ?>"
		};

		var refId = $(this).data('ref-id');

		if (refId) {
			data['ref_id'] = refId;
		}

		$.post(url, data, function(result) {
			if (result.success) {
				var isApproved = result.approve == 1;
				buttonLink.html(isApproved ? 'Quitar aprobación' : 'Aprobar');
				signalApproved.css('display', (isApproved ? 'inline' : 'none'));

				return;
			}
		
			alert("Error al Aprobar / Rechazar");
			
		}, 'json');
	});

	$( ".js-modal-rs-comments" ).click(function() {
		var data = {
			seeker_id: $( "#candidate-id" ).val(),
			document: $(this).data('document'),
			job_id: "<?php echo $job_id; ?>"
		};

		var refId = $(this).data('ref-id');

		if (refId) {
			data['ref_id'] = refId;
		}

		var url = "<?php echo site_url('employer/recruitment_entry/recruitment_candidates/modal_rs_document_comment'); ?>";

		$( "#cont-modal" ).load(url, data, function(modal) {
			$( "#cont-modal" ).html(modal).find('.modal').modal('show');
		});
	});

	$( '.list-documents-item' ).each(function(e, i){
		if ($(i).closest('td').find('.detail-document').length == 0) {
			$(i).find('a').addClass('item-inactive');
		}
	});

	$( '.js-embed-manage-documents' ).click(function(e){
		e.preventDefault();
		$( '#modal-add-edit-documents iframe' ).prop('src', $(this).data('url'));
		$( '#modal-add-edit-documents .modal-title' ).html($(this).data('title'));
		$( '#modal-add-edit-documents' ).modal('show');
		return false;
	});

	$( '.modal-add-edit-seeker-requested-docs' ).on('hidden.bs.modal', function(){
		var contentId = $( "#btn-list-rd-seeker-documents" ).data('content-id');
		$( '#' + contentId ).remove();
		$( "#btn-list-rd-seeker-documents" ).click();
	});

	$( '.js-manage-contract-documents' ).click(function(e){
		e.preventDefault();

		$( '#modal-add-edit-contract-documents .modal-title' ).html($(this).data('title'));
		$( '#modal-add-edit-contract-documents .modal-body' ).html('<div style="text-align:center;">Cargando...<div>');
		$( '#modal-add-edit-contract-documents' ).modal('show');
		
		var url = "<?php echo site_url('employer/recruitment_requested_docs/contract_documents/index'); ?>";
		var data = {
			'seeker_id': $(this).data('seeker-id'),
			'document_id': $(this).data('document-id')
		};

		$.get(url, data, function(view) {
			$( '#modal-add-edit-contract-documents .modal-body' ).html(view);
		});

		return false;
	});

	$( '.js-manage-identification-document' ).click(function(e){
		e.preventDefault();

		$( '#modal-add-edit-identification-document .modal-title' ).html($(this).data('title'));
		$( '#modal-add-edit-identification-document .modal-body' ).html('<div style="text-align:center;">Cargando...<div>');
		$( '#modal-add-edit-identification-document' ).modal('show');
		
		var url = "<?php echo site_url('employer/recruitment_requested_docs/send_identification_document/index'); ?>";
		var data = {
			'seeker_id': $(this).data('seeker-id')
		};

		$.get(url, data, function(view) {
			$( '#modal-add-edit-identification-document .modal-body' ).html(view);
		});

		return false;
	});

	$( '.js-manage-seeker-study-certificates' ).click(function(e){
		e.preventDefault();

		$( '#modal-add-edit-seeker-study-certificates .modal-title' ).html($(this).data('title'));
		$( '#modal-add-edit-seeker-study-certificates .modal-body' ).html('<div style="text-align:center;">Cargando...<div>');
		$( '#modal-add-edit-seeker-study-certificates' ).modal('show');
		
		var url = "<?php echo site_url('employer/recruitment_requested_docs/send_study_certificates/index'); ?>";
		var data = {
			'seeker_id': $(this).data('seeker-id')
		};

		$.get(url, data, function(view) {
			$( '#modal-add-edit-seeker-study-certificates .modal-body' ).html(view);
		});

		return false;
	});

	$( '.js-manage-seeker-experience-certificates' ).click(function(e){
		e.preventDefault();

		$( '#modal-add-edit-seeker-experience-certificates .modal-title' ).html($(this).data('title'));
		$( '#modal-add-edit-seeker-experience-certificates .modal-body' ).html('<div style="text-align:center;">Cargando...<div>');
		$( '#modal-add-edit-seeker-experience-certificates' ).modal('show');
		
		var url = "<?php echo site_url('employer/recruitment_requested_docs/send_experience_certificates/index'); ?>";
		var data = {
			'seeker_id': $(this).data('seeker-id')
		};

		$.get(url, data, function(view) {
			$( '#modal-add-edit-seeker-experience-certificates .modal-body' ).html(view);
		});

		return false;
	});

	$( '.load-entry-form-view' ).click(function(){
		const url = $(this).data('url-load');
		$( ".detail-document", $(this).closest('.col-item')).load(url, {});
	});

</script>
