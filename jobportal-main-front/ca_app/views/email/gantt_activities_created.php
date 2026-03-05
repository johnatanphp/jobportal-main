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

<p>Gantt de actividades para la solicitud de personal</p>
<div>Solicitud código: <?php echo $staff_request->ID; ?></div>
<div>Solicitud nombre: <?php echo $staff_request->job_title; ?></div>
<br>
<div style="border-top: 1px solid #333333;">
	<p><b>LINEAMIENTOS DEL PROCESO DE RECLUTAMIENTO Y SELECCIÓN</b></p>
	<p>
		1. Se hace de su conocimiento que los requerimientos que pertenezcan a los grupos ocupacionales tales como gerentes, coordinadores, Jefes, supervisores, analistas, estándar técnico y salud, el tiempo de respuesta es de 7 días hábiles. En caso de los grupos de estándar comercial y operativo, será de 5 días hábiles. 
	</p>
	<p>
		2. Los motivos que puedan generar cancelación o reinicio del requerimiento son:
	</p>
	<p>
		a. Se cancelará el requerimiento cuando se excede el tiempo de respuesta por parte del cliente después del envío de terna.
		<br />
		b. Se reiniciará el proceso Cuando se realiza un cambio en el perfil de puesto que en consecuencia podría generar deserción en los candidatos o el incumplimiento con el perfil solicitado.
	</p>
	<p class="wrapper-paragraph">
		<a href="<?php echo $url_link; ?>">Ingresar y ver solicitud</a>
	</p>
</div>
<div class="separator"></div>