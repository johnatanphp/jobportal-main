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
	Es necesario la verificación de Record migratorio vigente.
	<br>
	<ul>
		<li>
			RyS id: <?php echo $job->ID; ?>
		</li>
		<li>
			Puesto: <?php echo $job->job_title; ?> 
		</li>
	</ul>
	
	<br>
	Por favor ingresa a tu cuenta para hacer la verificación de los postulantes.

<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ingresar</a>
</p>

<div class="separator"></div>
