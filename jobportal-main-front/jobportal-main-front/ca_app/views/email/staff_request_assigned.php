<style type="text/css">

	p {
		padding: 4px 0px;
		display: block;
		line-height: 1.5;
		font-size: 15px;
		color: #222;
	}

	h3 {
		color: #222;
		font-size: 17px;
	}

	.wrapper-paragraph {
		text-align: center;
		padding-top: 10px;
	}

	.wrapper-paragraph a {
		padding: 10px 15px;
		text-align: center;
		color:  #fff;
		background: #005da4;
		border-radius: 5px;
		font-weight: bold;
		text-decoration: none;
		font-size: 15px;
	}
</style>
<p>
	<?php if ($email_for == 'employer'): ?>
		Se te asignó la solicitud de personal <b>"<?php echo $staff_request->job_title; ?>"</b>, por favor ingresa a nuestro sitio para seguir con el proceso de reclutamiento.
	<?php elseif ($email_for == 'staff_recruiter'): ?>
		Se asignó la solicitud de personal <b>"<?php echo $staff_request->job_title; ?>"</b> a un empleador, para más detalle ingresa en nuestro sitio.
	<?php endif; ?>	
</p>

<br>
<h4 style="text-align:center;text-transform: uppercase;">Código solicitud</h4>
<div style="padding: 20px 10px;background:#eeeeee;text-align:center;font-size:25px;color:#005da4;font-weight:bold;">
	<?php echo $staff_request->ID; ?>
</div>

<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ingresar y ver solicitud</a>
</p>

<div class="separator"></div>
