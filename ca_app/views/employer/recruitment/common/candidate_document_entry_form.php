<?php $index = 1; ?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<style type="text/css">

			body {
				font-size: 14px;
				font-family: verdana;
				color: #222;
			}

			.tbl-entry-form-head {
				border-collapse: collapse;
				border: 1px solid #333;
				text-align: center;
				height: 70px;
				margin-bottom: 15px;
			}

			.tbl-entry-form-head td {
				font-size: 16px;
				border: 1px solid #333;
			}

			.tbl-entry-form-data {
				padding: 4px;
			}

			.tbl-entry-form-data td {
				font-weight: bold;
				padding: 3px 2px;
				font-size: 13px;
				line-height: 1.5;
			}

			.tbl-entry-form-data-item {
				font-weight: normal;
			}

			.tbl-entry-form-data-item-check {
				vertical-align: top;
				float: right;
			}

			.document-requested-title {
				font-weight: bold;
				padding: 15px 0;
			}

			.tbl-documents-requested {
				border-collapse: collapse;
			}

			.tbl-documents-requested th {
				background: #1f4e79;
				color: #fff;
				padding: 20px 6px;
			}

			.tbl-documents-requested td {
				padding: 5px;
			}

			.content-footer {
				font-size: 13px;
				max-width: 700px;
				padding: 15px 0;
			}
		</style>
	</head>
	<body>
		<table class="tbl-entry-form-head" width="700">
			<tr>
				<td width="35%">
					<img height="40" src="<?php echo base_url('public/images/overall_blue.png'); ?>">
				</td>
				<td width="45%">
					FICHA DE INGRESO
				</td>
				<td>
					RH-FO-002 Versión: 10
				</td>
			</tr>
		</table>

		<table class="tbl-entry-form-data" width="700">
			<tr>
				<td>
					PERSONAL PARA SERVICIOS
					<img class="tbl-entry-form-data-item-check" width="20" src="<?php echo base_url('public/images/img-unchecked.png'); ?>">
				</td>
				<td>
					PERSONAL BACK OFFICE
					<img class="tbl-entry-form-data-item-check" width="20" src="<?php echo base_url('public/images/img-unchecked.png'); ?>">
				</td>
				<td>
					PERSONAL STAFF 
					<img class="tbl-entry-form-data-item-check" width="20" src="<?php echo base_url('public/images/img-unchecked.png'); ?>">
				</td>
			</tr>
		</table>

		<table class="tbl-entry-form-data" width="700">
			<tr>
				<td width="50%">
					RAZON SOCIAL:
					<div class="tbl-entry-form-data-item" >
						<?php e($staff_request->consultant_name); ?>
					</div>
				</td>
				<td width="50%">
					AREA:
					<div class="tbl-entry-form-data-item" >
						<?php if ($staff_request->request_type == 'external'): ?>
							<?php 
								$industry = $this->Industry->get_industries_by_id(
									$staff_request->industry_ID
								);
								e($industry ? $industry->industry_name : '');
							?>
						<?php endif; ?>

						<?php if ($staff_request->request_type == 'internal'): ?>
							<?php 
								$belonging_areas = $this->Mof->get_belonging_areas_by_mof_id(
									$staff_request->mof_ID
								);
								
								$array_areas = array_map(function($area) {
									return $area->area_name;
								}, $belonging_areas);

								e(implode(', ', $array_areas));
							?>
						<?php endif; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					NOMBRES:
					<div class="tbl-entry-form-data-item" >
						<?php e($candidate->first_name); ?>
					</div>
				</td>
				<td>
					TIPO DE DOCUMENTO y N°:
					<div class="tbl-entry-form-data-item" >
						<?php e(document_type_text($candidate->document_type) . ' - ' . $candidate->document_number); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					FECHA DE INGRESO:
					<?php if ($staff_request->start_date_work): ?>	
						<div class="tbl-entry-form-data-item">
							<?php e($staff_request->start_date_work); ?>
						</div>
					<?php endif; ?>
				</td>
				<td>
					VIGENCIA DEL CONTRATO:
					<div class="tbl-entry-form-data-item">
						<?php
							e($staff_request->contract_time_qty . ' ' . $staff_request->contract_time_duration);
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					CARGO
					<div class="tbl-entry-form-data-item">
						<?php e($staff_request->job_title); ?>
					</div>
				</td>
				<td>
					SUELDO
					<div class="tbl-entry-form-data-item">
						<?php e($staff_request->salary_range); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					
					<table width="700">
						<tr>
							<td colspan="4">
								MOTIVO DE CONTRATACIÓN
							</td>
						</tr>
						<tr>
							<td width="25%">
								Remplazo
							</td>
							<td width="25%">
								<?php if ($staff_request->reason_request == 'replacement'): ?>
									<img width="20" src="<?php echo base_url('public/images/img-checked.png'); ?>">
								<?php else: ?>
									<img width="20" src="<?php echo base_url('public/images/img-unchecked.png'); ?>">
								<?php endif; ?>
							</td>
							<td width="25%">
								Vacaciones
							</td>
							<td width="25%">
								<?php if ($staff_request->reason_request == 'vacations'): ?>
									<img width="20" src="<?php echo base_url('public/images/img-checked.png'); ?>">
								<?php else: ?>
									<img width="20" src="<?php echo base_url('public/images/img-unchecked.png'); ?>">
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td>
								Puesto nuevo
							</td>
							<td>
								<?php if ($staff_request->reason_request == 'new'): ?>
									<img width="20" src="<?php echo base_url('public/images/img-checked.png'); ?>">
								<?php else: ?>
									<img width="20" src="<?php echo base_url('public/images/img-unchecked.png'); ?>">
								<?php endif; ?>
							</td>
							<td>
								Licencia Médica
							</td>
							<td>
								<?php if ($staff_request->reason_request == 'license'): ?>
									<img width="20" src="<?php echo base_url('public/images/img-checked.png'); ?>">
								<?php else: ?>
									<img width="20" src="<?php echo base_url('public/images/img-unchecked.png'); ?>">
								<?php endif; ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<div class="document-requested-title">
			DOCUMENTOS PARA LA CONTRATACIÓN DE PERSONAL:
		</div>

		<table class="tbl-documents-requested" width="700" border="1">
			<tr>
				<th width="5%" style="font-size:12px;">N°</th>
				<th width="80" style="font-size:12px;">DOCUMENTO</th>
				<th width="15%" style="font-size:12px;">X / NA*</th>
				<th width="15%" style="font-size:12px;">NOMBRE DEL RESPONSABLE</th>
				<th width="15%" style="font-size:12px;">FIRMA DEL RESPONSABLE</th>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Solicitud del Cliente </td>
				<td align="center"><?php echo $staff_request ? 'X' : 'NA'; ?></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>RH-FO-004 Requerimiento de Personal</td>
				<td align="center"><?php echo $staff_request ? 'X' : 'NA'; ?></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Currículum Vitae</td>
				<td align="center">X</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Informe Psicolaboral (Staff) </td>
				<td align="center">
					<?php
						$document_psycholabor = $this->Recruitment_attached_document->get_files(
							$job->ID,
							$candidate->ID,
							'report_psycholabor'
						);
						echo $document_psycholabor ? 'X' : 'NA';					
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>RH-FO-007 Informe por Competencias</td>
				<td align="center">
					<?php
						$document_competences = $this->Recruitment_attached_document->get_files(
							$job->ID,
							$candidate->ID,
							'report_competence'
						);
						echo $document_competences ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>
					RH-FO-008 Verificación Laboral
				</td>
				<td align="center">
					<?php
						$work_references = $this->Recruitment_attached_document->get_files(
							$job->ID,
							$candidate->ID,
							'work_reference'
						);
						echo $work_references ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>
					Screening (en caso aplique)
				</td>
				<td align="center">
					<?php
						$screnning = $this->Recruitment_attached_document->get_files(
							$job->ID,
							$candidate->ID,
							'screnning'
						);
						echo $screnning ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Verificación Domiciliaria (en caso aplique)</td>
				<td align="center">
						
					<?php
						$home_verification = $this->Recruitment_attached_document->get_files(
							$job->ID,
							$candidate->ID,
							'home_verification'
						);
						echo $home_verification ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Certificado de Aptitud (EMO)</td>
				<td align="center">
					<?php
						$certificate_emo = $this->Recruitment_attached_document->get_files(
							$job->ID,
							$candidate->ID,
							'certificate_emo'
						);
						echo $certificate_emo ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Copia Documento de identidad (DNI, Carnet de extranjería, PTP, otros)</td>
				<td align="center">
					<?php
						$identification_document = $this->Seeker_identification_document->find([
							'seeker_ID' => $candidate->ID
						]);

						echo $identification_document ? 'X' : 'NA'; 
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Fotos</td>
				<td align="center">
					<?php
						$photo = $this->Seeker_document_photo->find([
							'seeker_ID' => $candidate->ID
						]);

						echo $photo ? 'X' : 'NA'; 
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>GP-FO-004 Declaración jurada de información personal del trabajador</td>
				<td align="center">
					<?php
						$form_rtps = $this->Jobseeker_form_rtps->get_form_rtps_by_jobseeker_id(
							$candidate->ID
						);

						echo $form_rtps ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
		
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Certificado 5ta Categoría (en caso aplique)</td>
				<td align="center">
					<?php 
						$certificate_5th_category = $this->Seeker_certificate_5th_category->find([
							'seeker_ID' => $candidate->ID
						]);
						echo $certificate_5th_category ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Copia DNI Cónyuge (en caso aplique)</td>
				<td align="center">
					<?php
						$candidate_spouse = $this->Jobseeker_form_rtps->get_rightful_claimant_spouse_by_form_id(
							@$form_rtps->form_ID
						);

						echo $candidate_spouse ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Certificado de Estudios (en caso aplique)</td>
				<td align="center">
					<?php 
						$candidate_studies = $this->Job_seeker->get_qualification_by_jobseeker_id(
							$candidate->ID
						);

						echo $candidate_studies ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Copia DNI Hijos Menores de Edad (en caso aplique)</td>
				<td align="center">
					<?php
						$candidate_children = $this->Jobseeker_form_rtps->get_rightful_claimant_children_by_form_id(
							@$form_rtps->form_ID
						);

						echo $candidate_children ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Recibo de Agua o Luz o Teléfono (en caso aplique)</td>
				<td align="center">
					<?php 
						$receipt_services = $this->Recruitment_contract_document->all([
							'seeker_id' => $candidate->ID,
							'document_id' => 11
						]);
						echo count($receipt_services) > 0 ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Antecedentes policiales (Caso aplique) o OCN Interpol Lima (Caso aplique)</td>
				<td align="center">
					<?php 
					
						$police_records = $this->Recruitment_contract_document->all([
							'seeker_id' => $candidate->ID,
							'document_id' => 12
						]);
						echo count($police_records) > 0 ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td align="center"><?php echo $index++; ?></td>
				<td>Certificados de trabajo (en caso aplique)</td>
				<td align="center">
					<?php 
						$candidate_experiences = $this->Job_seeker->get_experience_by_jobseeker_id(
							$candidate->ID
						);
						echo $candidate_experiences ? 'X' : 'NA';
					?>
				</td>
				<td></td>
				<td></td>
			</tr>
		</table>
		<div class="content-footer">
			*Considerar: “X” como Documento Recopilado que Aplica al proceso y “NA” como documento que No Aplica al proceso.
		</div>
	</body>
</html>