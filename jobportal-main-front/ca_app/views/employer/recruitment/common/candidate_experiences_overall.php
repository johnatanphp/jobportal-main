<style type="text/css">
	.title-experience-overall {
		margin-bottom: 5px; 
		background: #bbd1de;
		padding: 5px 8px;
		font-weight: bold;
	}
</style>
<div style="padding: 10px 0;">
	<div class="candidate-section-content">
		<h4 class="candidate-section-content-title">
			Experiencia laboral en Overall
		</h4>
	</div>
	<div>
		<?php foreach ($experiences as $row_experience): ?>
			<div style="padding: 7px 0;">
				<h5 class="title-experience-overall">
					<?php echo $row_experience->client_name; ?>
				</h5>
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
						<label>Fecha de ingreso: </label>
					</div>
					<div class="col-md-8">
						<?php echo $row_experience->date_admission; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Fecha de salida: </label>
					</div>
					<div class="col-md-8">
						<?php echo $row_experience->date_termination; ?>
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