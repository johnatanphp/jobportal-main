<style type="text/css">
	/** **/

		#table-person-requirement-1,
		#table-person-requirement-2,
		#table-person-requirement-header {
			border: 1px solid #333;
			width: 100%;
			border-collapse: collapse;
		}

		#table-person-requirement-1 tr td,
		#table-person-requirement-2 tr td,
		#table-person-requirement-header tr td {
			padding: 4px !important;
			font-size: 13px;
		}

		.header-column {
			font-weight: bold;
		}

		#table-person-requirement-2 td {
			text-align: center;
		}

		.wrapper-attached-item {
			position: relative;
		}

		.attached-item {
			position: absolute;
			top:0;
			right:0;
		}

		.attached-item__link {
			font-size: 10px;
			color:#fff;
			background: #777;
			padding: 1px 5px;
			border-radius: 5px;
		}

		.attached-item__link:hover {
			color: #fff;
		}

	/** **/
</style>
<div>

	<table id="table-person-requirement-header" width="100%" border="1">
		<tr>
			<td>
				<img src="<?php echo base_url('public/images/overall_blue.png'); ?>">
			</td>
			<td class="header-column">REQUERIMIENTO DE PERSONAL</td>
			<td class="header-column">
				RH-FO-004
				<br />
				Versión: 02
			</td>
		</tr>
	</table>
	<br />
	<table id="table-person-requirement-1" border="1">
		<tr>
			<td class="header-column" width="30%">
				Fecha de trámite
			</td>
			<td style="text-align: center;" width="15%" colspan="2">
				<?php echo _date_locale_format(strtotime($request->creation_date), 'dd MMM y'); ?>
			</td>
			<td class="header-column">
				REQ. NÚMERO <?php echo $request->ID; ?>
			</td>
		</tr>
		<tr>
			<td class="header-column" >GERENCIA USUARIA:</td>
			<td colspan="3"><?php echo $request->user_management; ?></td>
		</tr>
		<tr>
			<td class="header-column">JEFATURA SOLICITANTE:</td>
			<td colspan="3"><?php echo $request->applicant_headquarter; ?></td>
		</tr>
		<tr>
			<td class="header-column" >NOMBRE DEL PUESTO:</td>
			<td colspan="3">
				<?php echo $request->job_title; ?>
			</td>
		</tr>
		<tr>
			<td class="header-column">CANTIDAD DE PLAZAS:</td>
			<td colspan="3">
				<?php echo $request->vacancies; ?>
			</td>
		</tr>
		<tr>
			<td class="header-column">PERFIL DEL CANDIDATO:
			</td>
			<td colspan="3">
				<?php if ($request->mof_ID): ?>
					<?php echo $this->Mof->find($request->mof_ID)->code; ?>
				<?php endif; ?>
				<?php if ($request->job_layout_id): ?>
					<?php echo $this->Job_layout->find($request->job_layout_id)->code; ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="header-column" style="text-align: center;">CONTRATACIÓN</td>
		</tr>

		<tr>
			<td>
				<b>TEMPORAL:</b>
				<span><?php echo $request->type_contracting == 'temporary' ? 'SI' : 'NO'; ?></span>
			</td>
			<td colspan="3">
				<b>Tiempo:</b> <?php echo $request->contract_time_qty . ' ' . $request->contract_time_duration; ?>
			</td>
		</tr>
		<tr>
			<td>
				<b>PLAZO FIJO:</b> <?php echo $request->type_contracting == 'fixed_term' ? 'SI' : 'NO'; ?>
			</td>
			<td colspan="3">
				<b>RENOVABLES</b> SI  ( <?php echo $request->renovable == 'yes' ? 'X' : ''; ?> )  NO ( <?php echo $request->renovable == 'no' ? 'X' : ''; ?> )
			</td>
		</tr>
		<tr>
			<td colspan="4" class="header-column" style="text-align: center;">MOTIVO DE CONTRATACIÓN</td>
		</tr>
		<tr>
			<td>
				<b>Reemplazo:</b>
				<span><?php echo $request->reason_request == 'replacement' ? 'SI' : 'NO'; ?></span> 
				<?php if ($request->reason_request == 'replacement'): ?>
					<div style="padding-top: 4px;">
						<?php echo  $request->employee_replaced_dni . ' - ' .  $request->employee_replaced_name; ?>		
					</div>
				<?php endif; ?>
			</td>
			<td colspan="3">
				<b>Vacaciones:</b>
				<span><?php echo $request->reason_request == 'vacations' ? 'SI' : 'NO'; ?></span>
				<?php if ($request->reason_request == 'vacations'): ?>
					<div style="padding-top: 4px;">
						<?php echo  $request->employee_replaced_dni . ' - ' .  $request->employee_replaced_name; ?>	
					</div>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				<b>Puesto nuevo:</b> <?php echo $request->reason_request == 'new' || $request->reason_request == '01' ? 'Si' : 'NO'; ?>
			</td>
			<td colspan="3" >
				<b>Licencia:</b>
				<span><?php echo $request->reason_request == 'license' ? 'SI' : 'NO'; ?></span>
				<?php if ($request->reason_request == 'license'): ?>
					<div style="padding-top: 4px;">
						<?php echo  $request->employee_replaced_dni . ' - ' .  $request->employee_replaced_name; ?>	
					</div>
				<?php endif; ?>
			</td>
		</tr>
		<?php if (!isset($export_to_pdf) && $request->employee_change_file_path): ?>
			<tr>
				<td><b>ADJUNTO DOCUMENTO</b></td>
				<td colspan="3">
					
					<a class="attached-item__link" href="<?php echo file_url($request->employee_change_file_path, 'public/uploads/staff_request/documents/' . $request->employee_change_file_path); ?>" target="_blank">
						<i class="glyphicon glyphicon-download-alt"></i>&nbsp;DOCUMENTO
					</a>
						
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td class="header-column">JEFE INMEDIATO:</td>
			<td colspan="3">
				<?php echo $request->name_immediate_boss; ?>
			</td>
		</tr>

		<tr>
			<td class="header-column">COMPENSACIÓN MENSUAL:</td>
			<td colspan="3">
				<?php echo !empty($request->monthly_gross_salary) ? 'S/ ' . $request->monthly_gross_salary : ''; ?>
				<div>
					<?php 
						$list_benefit = implode(', ', array_map(function($row) {
							return $row->benefit_name . ($row->detail ? ': ' . $row->detail : '');
						}, $additional_benefits));

						echo !empty($list_benefit) ? $list_benefit . '.' : '';
					?>
				</div>
			</td>
		</tr>

		<?php if ($request->type_remuneration): ?>
			<tr>
				<td class="header-column">TIPO DE REMUNERACIÓN:</td>
				<td colspan="3">
					<table>
						<tr>
							<td>Fija (<?php echo $request->type_remuneration == 1 ? 'X' : ' &nbsp;'; ?>)</td>
							<td>Variable (<?php echo $request->type_remuneration == 2 ? 'X' : ' &nbsp;'; ?>)</td>
							<td>Mixta (<?php echo $request->type_remuneration == 3 ? 'X' : ' &nbsp;'; ?>)</td>
						</tr>
					</table>
				</td>
			</tr>
		<?php endif; ?>
	
		<tr>
			<td class="header-column" style="vertical-align: top;">HORARIOS</td>
			<td colspan="3">
				<?php
					if ($request->start_hour_work != null):
						echo date('h:i a', strtotime($request->start_hour_work)) . ' a ' . date('h:i a', strtotime($request->end_hour_work));
					endif; 
				?>

				<?php if ($request->start_hour_work != null && $request->working_hours): ?>
					<br />
					<br />
					<b>Más detalles:</b>
					<br />
				<?php endif; ?>

			    <?php if ($request->working_hours): ?>
	            	<?php echo nl2br($request->working_hours); ?>
			    <?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="header-column">SEDE</td>
			<td colspan="3">
				<?php echo $request->job_address; ?>
			</td>
		</tr>

		<tr>
			<td colspan="4" class="header-column">OBSERVACIONES: <span style="font-weight: normal; "><?php echo $request->observations; ?></span></td>
		</tr>

		<tr>
			<td class="header-column">FECHA DE INICIO DE LABORES:</td>
			<td colspan="3">
				<?php
					if ($request->start_date_work != null && $request->start_date_work != '0000-00-00'):
						echo _date_locale_format(strtotime($request->start_date_work), 'dd/MM/y');
					endif;
				?>
			</td>
		</tr>

		<tr>
			<td class="header-column">NOMBRE DE LA RAZÓN SOCIAL:</td>
			<td colspan="3"><?php echo $request->client_company_name; ?></td>
		</tr>

		<tr>
			<td class="header-column">CENTRO DE COSTO:</td>
			<td colspan="3"><?php echo $request->cost_center; ?></td>
		</tr>
	</table>
	
	<br />
	<br />
	<br />
	
	<table id="table-person-requirement-2" width="100%" border="1">
		<?php
			$authorization1 = $this->Staff_request_authoritation->get_request_authorization(1, $request->ID); 
			$authorizationNull1 = $this->Staff_request_authoritation->get_request_authorization_no_null(1, $request->ID); 
			$authorization2 = $this->Staff_request_authoritation->get_request_authorization(2, $request->ID); 
			$authorization3 = $this->Staff_request_authoritation->get_request_authorization(3, $request->ID); 
			$authorization4 = $this->Staff_request_authoritation->get_request_authorization(4, $request->ID); 
		?>
		<tr>
			<td colspan="4">
				<b>Aprobador de Unidad de Negocio</b>
				<br />
				<?php echo $authorizationNull1 ? $authorizationNull1->personal_name : ''; ?>
				<?php if (!isset($export_to_pdf) && !$authorization1): ?>
					<br>
					<button class="btn btn-xs btn-primary btn-resend-email" data-type-authority-id="1" data-request-id="<?php echo $request->ID; ?>">
						Avisar de nuevo
					</button>
				<?php endif; ?>
			</td>
			<td colspan="4">
				<b>Gerente Administrativo</b>
				<br />
				<?php echo $authorization2->personal_name; ?>
				<br />
				<?php if (!isset($export_to_pdf) && $authorization2 && $authorization2->is_authorized == null): ?>
					<button class="btn btn-xs btn-primary btn-resend-email" data-type-authority-id="2" data-request-id="<?php echo $request->ID; ?>">
						Avisar de nuevo
					</button>	
				<?php endif; ?>
			</td>
		</tr>
		<tr style="height: 60px;">
			<td colspan="2" width="25%">
				<?php
					if ($authorization1 && $authorization1->is_authorized === '1'):
						echo 'Autorizado';
					endif; 

					if ($authorization1 && $authorization1->is_authorized === '0'):
						echo 'Denegado';
					endif; 
				?>
			</td>
			<td colspan="2" width="25%">
				<?php
					if ($authorization1 && $authorization1->is_authorized !== null):
						echo $authorization1->date;
					endif; 
				?>
			</td>
			<td colspan="2" width="25%">
				<?php
					if ($authorization2->is_authorized === '1'):
						echo 'Autorizado';
					endif; 

					if ($authorization2->is_authorized === '0'):
						echo 'Denegado';
					endif; 
				?>
			</td>
			<td colspan="2" width="25%">
				<?php
					if ($authorization2->is_authorized !== null):
						echo $authorization2->date;
					endif; 
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" width="25%" class="header-column" >Firma</td>
			<td colspan="2" width="25%" class="header-column" >Fecha</td>
			<td colspan="2" width="25%" class="header-column" >Firma</td>
			<td colspan="2" width="25%" class="header-column" >Fecha</td>
		</tr>

		<tr>
			<td colspan="4">
				<b>Gerente / Jefe de Area</b>
				<br />
				<?php echo $authorization3->personal_name; ?>
				<br />
				<?php if (!isset($export_to_pdf) && $authorization3 && $authorization3->is_authorized == null): ?>
					<button class="btn btn-xs btn-primary btn-resend-email" data-type-authority-id="3" data-request-id="<?php echo $request->ID; ?>">
						Avisar de nuevo
					</button>	
				<?php endif; ?>
			</td>
			<?php if ($authorization4): ?>
				<td colspan="4">
					<b>Gerente de Gestión de Desarrollo Humano</b>
					<br />
					<?php echo $authorization4->personal_name; ?>
					<br />
					<?php if (!isset($export_to_pdf) && $authorization4 && $authorization4->is_authorized == null): ?>
						<button class="btn btn-xs btn-primary btn-resend-email" data-type-authority-id="4" data-request-id="<?php echo $request->ID; ?>">
							Avisar de nuevo
						</button>	
					<?php endif; ?>
				</td>
			<?php endif; ?>
		</tr>
		<tr style="height: 60px;">
		
			<td colspan="2" width="25%">
				<?php
					if ($authorization3->is_authorized === '1'):
						echo 'Autorizado';
					endif; 

					if ($authorization3->is_authorized === '0'):
						echo 'Denegado';
					endif; 
				?>
			</td>
			<td colspan="2" width="25%">
				<?php
					if ($authorization3->is_authorized !== null):
						echo $authorization3->date;
					endif; 
				?>
			</td>
			<?php if ($authorization4): ?>
				<td colspan="2" width="25%">
					<?php
						if ($authorization4->is_authorized === '1'):
							echo 'Autorizado';
						endif; 

						if ($authorization4->is_authorized === '0'):
							echo 'Denegado';
						endif; 
					?>
				</td>
				<td colspan="2" width="25%">
					<?php
						if ($authorization4->is_authorized !== null):
							echo $authorization4->date;
						endif; 
					?>
				</td>
			<?php endif;  ?>
		</tr>
		<tr>
			<td colspan="2" width="25%" class="header-column">Firma</td>
			<td colspan="2" width="25%" class="header-column">Fecha</td>
			<?php if ($authorization4): ?>
				<td colspan="2" width="25%" class="header-column">Firma</td>
				<td colspan="2" width="25%" class="header-column">Fecha</td>
			<?php endif;  ?>
		</tr>
	</table>
</div>
<script type="text/javascript">
	window.onload = function() {
		$( ".btn-resend-email" ).click(function(){
			var btnResend = $(this);
			var data = {
				request_id: btnResend.data('requestId'),
				type_authority_id: btnResend.data('typeAuthorityId')
			};
			btnResend.prop('disabled', true);

			$.post('<?php echo site_url('general/authorities/staff_requests/notify_request_to_authority'); ?>', data, function(response){
				if (response.success) {
					alert("¡Se avisó de nuevo por correo a la autoridad!");
				} else {
					alert("¡Error al avisar por correo a la autoridad!");
				}
			}, 'json')
			.fail(function(){
				alert('Ha ocurrido un error!');
			})
			.always(function(){
				btnResend.prop('disabled', false);
			});
		});
	}
</script>
