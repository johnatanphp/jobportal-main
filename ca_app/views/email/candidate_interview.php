
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
		
<p><b>Hola <?php echo $name ?>:</b></p>
<p>Tienes una entrevista programada para el puesto de trabajo <b><?php echo $job->job_title;?></b>.</p>

<div class="body-content">
	<span class="reason">
		"<?php echo $msg_interview; ?>"
	</span>
</div>

<div class="separator"></div>