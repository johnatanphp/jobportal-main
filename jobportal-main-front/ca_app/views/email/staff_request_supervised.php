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
<p>
	Eres seleccionado para hacer el seguimiento de la solicitud de personal <b>"<?php echo $staff_request->job_title; ?>"</b> , por favor ingresa a nuestro sitio para más detalle.
</p>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ingresar y ver solicitud</a>
</p>
<div class="separator"></div>
