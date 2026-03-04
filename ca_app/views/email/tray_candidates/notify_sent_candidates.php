<style type="text/css">

	p {
		padding: 2px 0px;
		display: block;
		line-height: 1;
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

    .body-content {
		background:#fff;
		padding: 2px;
		text-align: left;
        margin-bottom: 15px;
	}

	.body-content span {
		padding: 0;
		display: block;
		line-height: 1.5;
	}

	.reason {
		font-size: 13px;
	}
	.pa-1 {
		font-size: 13px;
	}

	table {
		border-spacing: 1px;
        border-collapse: collapse;
        width: 100%;
	}

    table tbody tr td {
        border: 1px solid #333;
        padding: 2px;
        font-size: 12px;
    }

    table thead tr th {
        border: 1px solid #333;
        padding: 5px;
        font-size: 13px;
        background: #efefef;
        text-align: left;
    }
</style>

<p>Hola Estimados,</p>
<p>Los siguientes postulantes han sido enviados para contratación.</p>
<br>
<p class="pa-1"><b>Fecha de envio:</b> <span class=""><?php e($sent_at); ?></span></p>
<p class="pa-1"><b>Enviado por:</b> <span class=""><?php e($sent_by->first_name); ?></span></p>
<p class="pa-1"><b>Solicitud Codigo:</b> <span class=""><?php e($staff_request->ID); ?></span></p>
<p class="pa-1"><b>Puesto:</b> <span class=""><?php e($staff_request->job_title); ?></span></p>
<p class="pa-1"><b>Consultora:</b> <span class=""><?php e($staff_request->consultant_name); ?></span></p>
<p class="pa-1"><b>Empresa cliente:</b> <span class=""><?php e($staff_request->client_company_name); ?></span></p>
<p class="pa-1"><b>Centro de costo:</b> <span class=""><?php e($staff_request->cost_center); ?></span></p>
<?php if (!empty($comments)): ?>
	<p class="pa-1"><b>Comentarios:</b> </p>
    <div class="body-content">
        <span class="reason">
            <?php echo nl2br($comments); ?>
        </span>
    </div>
<?php endif; ?>
<table width="100%">
    <thead>
        <tr>
            <th align="left">Nombre y Apellido</th>
            <th align="left">Doc. Identidad</th>
            <th align="left">Doc. Solicitados</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($candidates as $candidate): ?>
			<tr>
				<td align="left">
					<?php e(trim($candidate->first_name . ' ' . $candidate->last_name)); ?>
				</td>
				<td align="left">
					<?php e(trim($candidate->document_type_abbreviation_name . ' ' . $candidate->document_number)); ?>
				</td>
				<td align="left">
					<?php e($candidate->doc_percentage_progress . '% completado'); ?>
				</td>
			</tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ir a la bandeja</a>
</p>

<div class="separator"></div>