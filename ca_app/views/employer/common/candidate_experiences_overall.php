<style type="text/css">
	.content-experience-item {
		padding: 7px 0;
		border-bottom: 1px solid #999;
	}
</style>
<div style="padding: 10px 0;">
	<div class="candidate-section-content">
		<h4 class="candidate-section-content-title">
			Experiencia laboral en Overall
		</h4>
	</div>
	<div class="content-experiences">
		<?php foreach ($experiences as $row_experience): ?>
			<div class="content-experience-item">
				<div class="row">
					<div class="col-md-3">
						<label>Consultora: </label>
					</div>
					<div class="col-md-8">
						<?php echo $row_experience->consultant_name; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Cliente: </label>
					</div>
					<div class="col-md-8">
						<?php echo $row_experience->client_name; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Tipo de planilla: </label>
					</div>
					<div class="col-md-8">
						<?php echo $row_experience->sheet_name; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Fecha de ingreso: </label>
					</div>
					<div class="col-md-8">
						<?php echo format_date($row_experience->date_admission, 'd/m/Y'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Fecha de salida: </label>
					</div>
					<div class="col-md-8">
						<?php echo !empty($row_experience->date_termination) ?  format_date($row_experience->date_termination, 'd/m/Y'): '-'; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Estado actual: </label>
					</div>
					<div class="col-md-8">
						<?php echo $row_experience->employee_status; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		<?php if (empty($experiences)): ?>
			Ningún resultado encontrado
		<?php endif; ?>
	</div>
</div>