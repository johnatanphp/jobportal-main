
<style type="text/css">
	.body-content {
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}

	.body-content span {
		padding: 2px 0px;
		display: block;
		line-height: 1.3;
		font-size: 14px;
	}

	.reason {
		padding: 8px 0px;
		display: block;
		line-height: 1.5;
		font-style: italic;
	}
</style>

<p>
Hay un nuevo mensaje de contacto con los siguientes datos:
</p>

<div class="body-content">
	<!--<span><b>Fecha:&nbsp;</b> <?php echo $dated; ?></span>-->
	<span><b>Dirección IP de origen:&nbsp;</b> <?php echo $ip_address; ?></span>
	<span><b>Nombre:&nbsp;</b> <?php echo $full_name; ?></span>
	<span><b>Correo electrónico:&nbsp;</b> <?php echo $email; ?></span>
	<span><b>Teléfono:&nbsp;</b> <?php echo $phone; ?></span>
	<span><b>Ciudad:&nbsp;</b> <?php echo $city; ?></span>
	<div class="reason">"<?php echo $message; ?>"</div>
</div>

<div class="separator"></div>