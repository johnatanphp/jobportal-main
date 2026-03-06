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
</style>

<h3>Hola <?php echo $app_user->first_name; ?></h3>

Tu cuenta para la empresa <?php echo $company->company_name; ?>, se ha creado con éxito!
<br />

Para comenzar a usar tu cuenta es necesario activarla desde el siguiente <a href="<?php echo site_url('verification_company/activate/' . $app_user->verification_code); ?>">enlace</a>.

<br />
<br />
Usuario: <?php echo $app_user->email; ?>
<br />
<br />
Saludos

<div class="separator"></div>