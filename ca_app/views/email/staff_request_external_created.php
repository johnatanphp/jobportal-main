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

<p>Se ha creado una solicitud de personal y se necesita que asignes a un empleador para seguir con el proceso de reclutamiento.</p>
<br>
<br>
<h4 style="text-align:center;text-transform: uppercase;">Código solicitud</h4>
<div style="padding: 20px 10px;background:#eeeeee;text-align:center;font-size:25px;color:#005da4;font-weight:bold;">
	<?php echo $staff_request->ID; ?>
</div>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ver solicitud</a>
</p>

<div class="separator"></div>