
<style type="text/css">
	.body-content {
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}
</style>

Estimados,
<br />
<br />
La empresa <?php echo $staff_request->consultant_name; ?> solicita la programación de <b><?php echo $document->name; ?></b> para el día <b><?php echo $exam_date; ?></b> para el/los colaboradores que se adjunta en el Excel, le agradeceremos confirmar la cita a los correos líneas abajo.

<?php if ($comment != ''): ?>
	<br />
	<br />
	<?php echo $comment; ?>
<?php endif; ?>

<br />
<br />
<b>Responder a:</b>
<br />
Berenice Rodriguez Mayta brodriguez@overall.com.pe – Celular 987951928
<br />
Betzabeth Zelem Lazaro bzelem@overall.com.pe – Celular 983287790
<br />
Cesar Enrique Ricalde cesar.enrique@overall.com.pe
<div class="separator"></div>