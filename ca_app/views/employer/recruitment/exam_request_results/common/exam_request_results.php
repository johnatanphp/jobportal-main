<style>
    #tbl-exam-request-results tr td {
        text-align: left;
    }

    .exam-request-upload-remove {
        background: transparent;
        border: 0;
        padding: 0;
        margin: 0;
        color: red;
    }
</style>
<div class="table-responsive">
    <table id="tbl-exam-request-results" class="table table-striped" width="100%">
        <thead>
            <tr>
                <th>
                    Fecha
                </th>
                <th>
                    Tipo
                </th>
                <th>Certificado</th>
                <th>Resultado</th>
                <th width="20"></th>
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
                    <td>
                        <?php if ($result->result_origin == 'Adjunto'): ?>
                            <button class="exam-request-upload-remove" data-file-id="<?php echo $result->id; ?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $( '#tbl-exam-request-results' ).DataTable({
        "language": {
            "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
        },
        "iDisplayLength": 25,
        "bLengthChange": false,
        "paging": true,
        "ordering": true,
        "scrollCollapse": true,
        "searching": true,
        "bInfo": true,
        'columnDefs': [{
            'targets': [1, 2, 3, 4],
            'orderable': false,
        }],
        "order": [[0, 'desc']],
    });
</script>