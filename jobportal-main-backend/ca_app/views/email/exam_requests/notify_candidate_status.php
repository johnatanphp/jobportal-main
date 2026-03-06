<style type="text/css">
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

	.reason {
		font-style: italic;
	}

    .table-style-1 {
        border-spacing: 1px;
        border-collapse: collapse;
        width: 100%;
    }
    
    .table-style-1 tr td {
        border: 1px solid #aaa;
        padding: 4px 2px;
        font-size: 12px;
    }

    .table-style-1 tr th {
        border: 1px solid #aaa;
        padding: 5px;
        font-size: 13px;
        background: #f5f5f5;
        text-align: left;
    }
</style>
		
<div>
    <div style="text-align: center;padding: 8px 5px;">
        <h4 style="font-size: 1.45em;color: #444;">¡Hay <?php echo $exam_candidate->candidate_total;  ?> <?php echo $exam_candidate->candidate_total > 1 ? 'actualizaciones de estado!' : 'actualización de estado!'; ?></h4>
    </div>
    <div style="text-align: center;padding: 8px 5px;">
        <img style="width: 130px;height: 130px;" src="<?php echo base_url('public/images/exam_logo.png'); ?>">
    </div>

    <div style="padding: 30px 10px 5px;">
        <table class="table-style-1" width="100%">
            <tr>
                <td width="120" style="background: #f5f5f5;font-weight: bold;">Solicitud Código</td>
                <td><?php echo $exam_candidate->request_id; ?></td>
            </tr>
            <tr>
                <td width="120" style="background: #f5f5f5;font-weight: bold;">Solicitud Nombre</td>
                <td><?php echo $exam_candidate->request_job_title; ?></td>
            </tr>
            <tr>
                <td width="120" style="background: #f5f5f5;font-weight: bold;">Postulantes actualizados</td>
                <td><?php echo $exam_candidate->candidate_total; ?></td>
            </tr>
           
        </table>
    </div>
</div>

<div class="separator"></div>