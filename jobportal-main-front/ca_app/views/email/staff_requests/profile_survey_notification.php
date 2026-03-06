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
               
Hola <?php e($recruiter->first_name); ?>, se ha realizado un Levantamiento de perfil a una solictud que has creado.
<br>
<br>
<table width="100%">
	<tr>
		<th align="center">Solicitud Id</th>
		<th align="center">Solicitud Nombre</th>
		<th align="center">Levantamiento por</th>
		<th align="center">Fecha</th>
	</tr>
	<tr>
		<td align="center"><?php e($staff_request->ID); ?></td>
		<td align="center"><?php e($staff_request->job_title); ?></td>
		<td align="center"><?php e($employer->first_name); ?></td>
		<td align="center"><?php e($profile_survey_date); ?></td>
	</tr>
</table>

<div class="separator"></div>