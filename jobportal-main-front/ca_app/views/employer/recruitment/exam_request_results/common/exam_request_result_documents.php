<style>
    #tbl-exam-request-results-docs-<?php echo $document->id; ?> tr th {
        text-align: left;
    }
    
    #tbl-exam-request-results-docs-<?php echo $document->id; ?> tr td {
        text-align: left;
    }
</style>
<div style="padding: 10px 0;">
	<div class="candidate-section-content">
		<h4 class="candidate-section-content-title">
			<?php echo $document->name; ?>
		</h4>
	</div> 
    <div class="table-responsive">
        <table id="tbl-exam-request-results-docs-<?php echo $document->id; ?>" class="table" width="100%">
            <thead>
                <tr>
                    <th width="25%">
                        Fecha
                    </th>
                    <th width="25%">
                        Tipo
                    </th width="25%">
                    <th width="25%">Certificado</th>
                    <th width="25%">Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exam_request_results as $result): ?>
                    <tr>
                        <td>
                            <?php e($result->result_date); ?>
                        </td>
                        <td>
                            <?php e($result->result_origin); ?>
                        </td>
                        <td>
                            <?php if (empty($result->result_file)): ?>
                                Sin certificado
                            <?php endif; ?>
                            
                            <?php if (!empty($result->result_file)): ?>
                                <a href="<?php echo file_url($result->result_file); ?>" target="_blank">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Ver
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php e($result->result_name ? $result->result_name : 'No disponible'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $( "#tbl-exam-request-results-docs-<?php echo $document->id; ?>" ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "iDisplayLength": 25,
        "bLengthChange": false,
        "paging" : true,
        "ordering" : true,
        "scrollCollapse" : true,
        "searching" : true,
        "bInfo": true,
        'columnDefs': [{
            'targets': [1, 2, 3],
            'orderable': false,
        }],
        "order": [[0, 'desc']],
    });
</script>