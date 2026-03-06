
<style type="text/css">
	.body-content {
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}
</style>

<p>Hola <?php echo $user; ?>: </p>

<p>
    Tu código para restablecer tu contraseña es: <b><?php echo $verification_code; ?></b>.
</p>
<p>
    Este código de verificación caduca luego de 2 horas.
</p>
<div class="separator"></div>