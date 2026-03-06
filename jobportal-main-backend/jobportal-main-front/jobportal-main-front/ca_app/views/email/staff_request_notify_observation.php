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
		
<p>
    Hola <?php e($employer_recruiter->first_name); ?>, la solicitud que creaste tiene una observación.
    <br>
    <br>
    <b>Datos solicitud</b>
    <div>Solicitud Código: <?php echo $staff_request->ID; ?></div>
    <div>Solicitud Nombre: <?php echo $staff_request->job_title; ?></div>
</p>

<div class="body-content">
	<span class="reason">
		<?php e(nl2br($staff_request->observation)); ?>
	</span>
</div>

<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ver solicitud</a>
</p>

<div class="separator"></div>