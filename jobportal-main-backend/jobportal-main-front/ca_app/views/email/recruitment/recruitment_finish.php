<style type="text/css">
	
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
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}

	.body-content span {
		padding: 5px 0px;
		display: block;
		line-height: 1.5;
	}

    .table-style-1 {
        border-spacing: 1px;
        border-collapse: collapse;
        width: 100%;
    }
    
    .table-style-1 tr td {
        border: 1px solid #999;
        padding: 4px 6px;
        font-size: 12px;
    }

    .table-style-1 tr th {
        border: 1px solid #999;
        padding: 5px;
        font-size: 13px;
        background: #eee;
        text-align: left;
    }
</style>
		
<p style="font-size: 14px;padding-bottom: 15px;">
    Estimados,
</p>
<p style="font-size: 14px;">
    Nos dirigimos a ustedes para informarles que el proceso de reclutamiento y selección para el empleo <b>"<?php e($process->job_title); ?>"</b> ha expirado.
</p>
<br>
<table width="100%" class="table-style-1">
    <thead> 
        <tr>
            <th colspan="2">Proceso de reclutamiento</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="150">Empleo</td>
            <td><?php e($process->job_title); ?></td>
        </tr>
        <tr>
            <td width="150">Fecha de expiración</td>
            <td><?php echo _date_locale_format(strtotime($process->process_expiration_date), 'dd MMM y'); ?></td>
        </tr>
        <tr>    
            <td width="150">Postutantes en el proceso</td>
            <td><?php e($count_candidates == 0 ? 'Ninguno' : $count_candidates); ?></td>
        </tr>
    </tbody>
</table>

<br>
<br>
<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Ver proceso</a>
</p>
<div class="separator"></div>