<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL . 'public/css/google-fonts.css'; ?>" />
	<link rel="shortcut icon" href="<?php echo SITE_URL . 'public/images/favicon.ico'; ?>">

	<title>Sitio en mantenimiento - <?php echo SITE_NAME; ?></title>
	
	<style type="text/css">
		* {
			padding: 0;
			margin: 0;
			border:0;
			color: #222;
			font-family: 'Open Sans', 'sans-serif';
		}

		a {
			color: #1e3f71;
			display: inline-block;
			text-decoration: underline;
		}

		.wrapper-error {
			max-width: 650px;
			margin: 5% auto;
			padding: 2em 1.5em;
		}

		h1 {
			font-size: 26px;
			padding: 8px 0px;
		}

		.message-error {
			font-size: 18px;
			padding: 25px 0px;
			line-height: 1.5;
			display: block;
		}
	</style>
</head>
<body>

	<div class="wrapper-error">
		<img src="<?php echo base_url('public/images/overall_blue.png'); ?>">
	
		<h1>Sitio en mantenimiento </h1>
		<p><b><?php echo SITE_NAME; ?></b></p>
	
		<p class="message-error">
			Actualmente este sitio está en mantenimiento, estamos trabajando para estar lo antes posible de vuelta, te pedimos disculpas por las molestias causadas.
		</p>
	</div>
</body>
</html>