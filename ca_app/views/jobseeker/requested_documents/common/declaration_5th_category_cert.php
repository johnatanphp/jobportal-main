<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body {
			font-size: 14px;
		}
	</style>
</head>
<body>
	<table width="100%" style="border:1px solid #000;">
		<tr>
			<td width="35%" align="center"><img height="40" src="<?php echo base_url('public/images/overall_blue.png'); ?>"></td>
			<td align="center">DECLARACIÓN JURADA RENTA DE 5TA CATEGORÍA</td>
			<td>
				RH-FO-006
				<br />
				Versión: 02
			</td>
		</tr>
	</table>
	<br />
	<br />

	<div style="text-align: justify;">
		Yo, <span class="text-bold"><?php echo mb_strtoupper($seeker->last_name . ' ' . $seeker->first_name); ?></span> identificado con el tipo de documento <span class="text-bold"><?php echo document_type_abbr($seeker->document_type); ?></span> <span class="text-bold">Nº <?php echo $seeker->document_number; ?></span>  en pleno de ejercicio de mis derechos ciudadanos.

		<br />
		<br />
		<b>DECLARO BAJO JURAMENTO:</b> No haber percibido ingresos de 5ta categoría correspondientes al periodo  <?php echo date('Y'); ?> hasta la fecha.

		<br />
		<br />
		Manifiesto que la información proporcionada es verdadera y autorizo la verificación de lo declarado.

		<br />
		<br />
		En caso de falsedad declaro haber incurrido en el delito Contra La Fe Pública, falsificación de documentos, (Artículo 427º del Código Penal, en concordancia con el Artículo IV inciso 1.7) de la Ley de Procedimiento Administrativo General, Ley Nº 27444.
	</div>
</body>
</html>