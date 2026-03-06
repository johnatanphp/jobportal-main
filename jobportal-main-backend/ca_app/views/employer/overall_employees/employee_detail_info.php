<?php if (isset($employee_blacklist)): ?>
	<div>
		<h5 class="title-detail"><b>EMPLEADO EN LISTA NEGRA</b></h5>
		<div><?php echo $employee_blacklist && $employee_blacklist->blacklist == 1 ? 'SI' : 'NO'?></div>
	</div>
	<br />
<?php endif; ?>
<div>
	<h5 class="title-detail">
		<b>EXPERIENCIA LABORAL EN OVERALL</b>
		<span>
			<?php echo count($employee_experiences) > 0 ? ' - ' . count($employee_experiences) . ' resultado(s)' : ''; ?>		
		</span>
	</h5>

	<table id="tbl-employee-experiences" width="100%">
		<thead>
			<tr>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 0; ?>
			<?php foreach ($employee_experiences as $row_experience): ?>
				<tr>
					<td width="5%" style="padding-top: 5px; vertical-align: top;">
						<?php echo (++$i); ?>)
					</td>
					<td>
						<div style="padding: 7px 0;">
							<h5 style="padding: 5px 0;">
								<b><?php echo $row_experience->client_name; ?></b>
							</h5>
							<div class="row">
								<div class="col-md-4">
									<label>Consultora: </label>
								</div>
								<div class="col-md-8">
									<?php echo $row_experience->consultant_name; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Fecha de ingreso: </label>
								</div>
								<div class="col-md-8">
									<?php echo date('d/m/Y', strtotime(str_replace('/', '-', $row_experience->date_admission))); ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Fecha de salida: </label>
								</div>
								<div class="col-md-8">

									<?php if (!empty($row_experience->date_termination)): ?>
										<?php echo date('d/m/Y', strtotime(str_replace('/', '-', $row_experience->date_termination))); ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Estado actual: </label>
								</div>
								<div class="col-md-8">
									<?php echo $row_experience->employee_status; ?>
								</div>
							</div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>

			<?php if (empty($employee_experiences)): ?>
				<tr><td colspan="2">Sin resultados</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	/*
	$( "#tbl-employee-experiences" ).dataTable({
		"oLanguage": {
			"sEmptyTable": "No hay registros disponibles",
			"sInfo": "Hay _TOTAL_ registros. Mostrando de (_START_ a _END_)",
			"sLoadingRecords": "Por favor espera - Cargando...",
			"sSearch": "Buscar:",
			"sLengthMenu": "Mostrar _MENU_",
			"oPaginate": {
				"sLast": "Última página",
				"sFirst": "Primera",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"
			}
		},
		"aLengthMenu": [[3]],
         "iDisplayLength": 3,   
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
		"bAutoWidth": false,
	});
	*/
</script>