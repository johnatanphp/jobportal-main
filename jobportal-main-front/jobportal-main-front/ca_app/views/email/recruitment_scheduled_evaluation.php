
<style type="text/css">
	.body-content {
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}

	.body-content span {
		padding: 5px 0px;
		display: block;
		line-height: 1.5;
	}

	.reason {
		font-style: italic;
	}
</style>
		
<p><b>Hola <?php echo $name ?></b></p>
<p>Tienes una evaluación programada.</p>

<ul>
	<li>
		<b>Empresa: </b>
		<?php echo $responsible_employer->company_name; ?>
	</li>
	<li>
		<b>Puesto: </b>
		<?php echo $job->job_title; ?>
	</li>
	<li>
		<b>Empleador responsable: </b>
		<?php echo $responsible_employer->first_name; ?>
	</li>
	<li>
		<b>Fecha: </b>
		<?php echo format_date($scheduled_evaluation->date, 'd/m/Y'); ?>
	</li>
	<li>
		<b>Hora: </b>
		<?php echo format_date($scheduled_evaluation->hour, 'h:i a'); ?>
	</li>
	<li>
		<b>Lugar: </b>
		<?php echo $scheduled_evaluation->place; ?>
	</li>
</ul>
<br />
<?php if (!empty($scheduled_evaluation->more_details)): ?>
	<div class="body-content">
		<span class="reason">
			"<?php echo $scheduled_evaluation->more_details; ?>"
		</span>
	</div>
<?php endif; ?>

<div class="separator"></div>