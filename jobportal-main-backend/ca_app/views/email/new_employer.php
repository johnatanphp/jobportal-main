
<style type="text/css">
	.body-content {
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}
</style>

<p>Hola <?php echo $employer_name; ?>: </p>
<p>Tu cuenta en el Portal de empleo de Overall ha sido creada exitosamente, estas son tus credenciales:</p>

<div class="body-content">
	<table border="0" cellspacing="0" cellpadding="0" width="600" class="txt">
	<tr>
		<td height="25" align="left" width="25%"><strong>Página de ingreso</strong></td>
		<td align="left">
			<a href="<?php echo $url_jp_login;?>">
			<?php echo $url_jp_login;?>
			</a>
		</td>
	</tr>
	<tr>
		<td height="25" align="left" width="25%"><strong>Usuario:&nbsp;&nbsp;</strong></td>
		<td align="left"><?php echo $username; ?></td>
	</tr>
	<tr>
		<td height="25" align="left" width="25%"><strong>Contraseña:&nbsp;&nbsp;</strong></td>
		<td align="left"><?php echo $password; ?></td>
	</tr>
	</table>

</div>

<div class="separator"></div>