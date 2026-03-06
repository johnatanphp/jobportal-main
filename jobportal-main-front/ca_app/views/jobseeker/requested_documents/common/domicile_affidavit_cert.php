<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<style type="text/css">
			.text-bold {
				font-style: bold;
			}

			body {
				font-size: 14px;
			}
		</style>
	</head>
	<body>

		<table width="100%" style="border:1px solid #000;">
			<tr>
				<td width="35%" align="center"><img height="40" src="<?php echo base_url('public/images/overall_blue.png'); ?>"></td>
				<td align="center">DECLARACIÓN JURADA DE DOMICILIO</td>
				<td>
					RH-FO-005
					<br />
					Versión: 02
				</td>
			</tr>
		</table>

		<br />

		<h3 style="display: block;text-align: center;">
			<b>(Ley Nº 28882, Ley de Simplificación de la Certificación Domiciliaria)</b>
		</h3>
		
		<br />
		<div style="text-align: justify;">
			Yo, <span class="text-bold"><?php echo mb_strtoupper($seeker->last_name . ' ' . $seeker->first_name); ?></span> identificado con el tipo de documento <span class="text-bold"><?php echo document_type_abbr($seeker->document_type); ?></span> <span class="text-bold">Nº <?php echo $seeker->document_number; ?></span> en pleno de ejercicio de mis Derechos Ciudadanos y de conformidad con lo Dispuesto en la Ley Nº 28882 de Simplificación de la Certificación Domiciliaria, en su Artículo 1º.

			<br />
			<br />
			<b>DECLARO BAJO JURAMENTO:</b> Que mi domicilio actual se encuentra ubicado en: <span class="text-bold"><?php echo $seeker->present_address; ?>.</span>

			<br />
			<br />
			Realizo la presente declaración jurada manifestando que la información proporcionada es verdadera y autorizo la verificación de lo declarado.

			<br />
			<br />
			En caso de falsedad declaro haber incurrido en el delito Contra La Fe Pública, falsificación de Documentos, (Artículo 427º del Código Penal, en concordancia con el Artículo IV inciso 1.7) “Principio de Presunción de Veracidad” del Título Preliminar de la Ley de Procedimiento Administrativo General, Ley Nº 27444.
		</div>
	</body>
</html>