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
<p>
	Hola <?php e($name); ?>,
    <br>
    <br>
    Se te envia el enlace del reporte de los candidatos que tienen documentos de contratación por subir al portal.

<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Descargar reporte</a>
</p>

<div class="separator"></div>
