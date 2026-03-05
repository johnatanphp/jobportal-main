
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
		
<p>Hola <?php echo $employer_name ?>: </p>
<p>Tienes un nuevo mensaje referente al empleo <b><?php echo $job_title;?></b> de la empresa <b><?php echo $company_name; ?></b>.</b></p>

<div class="body-content">
	<span><b>De:</b>&nbsp;&nbsp;<?php echo $jobseeker_name; ?> - <?php echo $jobseeker_email; ?></span>
	<span class="reason">
		"<?php echo $reason; ?>"
	</span>
</div>

<div class="separator"></div>