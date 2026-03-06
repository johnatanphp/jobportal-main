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

<h3>Hola <?php echo $name; ?>,</h3>
<p>Se ha creado una solicitud de personal interna y se necesita tu autorización para seguir con el proceso de reclutamiento.</p>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ver solicitud</a>
</p>

<div class="separator"></div>