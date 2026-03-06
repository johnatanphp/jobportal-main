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

<div class="body-content">		
    <p>
        <b>Empleo:</b> <?php echo $job->job_title; ?>.
        <br />
        <br />
        <?php if ($profile_id == 3): ?>
            El área de RRHH 
        <?php endif; ?>

        <?php if ($profile_id == 5): ?>
            El área LEGAL 
        <?php endif; ?>
        ha aprobado los documentos del postulante <?php echo $seeker->first_name . ' ' . $seeker->last_name; ?>, Te invitamos a verificar está información para seguir con la contratación. 
    </p>
</div>

<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Más detalle</a>
</p>

<div class="separator"></div>