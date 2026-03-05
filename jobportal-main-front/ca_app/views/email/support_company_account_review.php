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

Se ha creado una nueva cuenta como empresa, se requiere revisión por tu parte.
<br />
<br />
Cuenta Empresa
<ul>
	<li>Empresa: <?php echo $company->company_name; ?></li>
	<li>RUC: <?php echo $company->company_ruc; ?></li>
	<li>Usuario: <?php echo $app_user->email; ?></li>
	<li>Nombre: <?php echo $app_user->first_name; ?></li>	
</ul>
<?php 
	$url_detail = site_url('admin/employers/search?email=' . $app_user->email);
?>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_detail; ?>">Más detalle</a>
</p>

<div class="separator"></div>
