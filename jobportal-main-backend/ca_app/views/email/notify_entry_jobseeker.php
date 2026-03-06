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

<div style="padding: 10px 10px;">
	Hola <?php e($jobseeker->first_name); ?>,
	<br>
	<br>
	Tienes una cuenta en el portal de empleo, con esta podrás comenzar a postularte en la gran variedad de empleos que tenemos en Overall.
  <br>
  <br>
  <table width="100%">
	<tr>
		<th align="left" width="150">Correo:</th>
		<td align="left"><?php e($jobseeker->email); ?></td>
	</tr>
	<tr>
		<th align="left" width="150">Contraseña:</th>
		<td align="left"><?php e($password); ?></td>
	</tr>
  </table>
</div>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ingresar</a>
</p>
<div class="separator"></div>
