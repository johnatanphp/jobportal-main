<style>
    #tbl-screening-results tr td {
        text-align: left;
    }
</style>
<div class="table-responsive">
    <table id="tbl-screening-results" class="table" width="100%">
        <thead>
            <tr>
                <th>
                    Fecha
                </th>
                <th>Screening</th>
                <th>
                    Tipo
                </th>
                <th>
                    Origen
                </th>
                <th>Empleo</th>
                <th>Fecha vencimiento</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($screening_results as $row): ?>
                <tr>
                    <td data-search="<?php e($row->created_at); ?>" data-sort="<?php echo strtotime($row->created_at) ;?>">
                        <?php e($row->created_at); ?>
                    </td>

                    <td>
                        <?php if ($row->origin == 'requested'): ?>
                            <a href="<?php echo site_url('candidate/screening_show_pdf/' .  $this->custom_encryption->encrypt_data($row->id)); ?>" target="_blank">
                                <i class="glyphicon glyphicon-file"></i> 
                                Ver
                            </a>
                        <?php endif; ?>
                        <?php if ($row->origin != 'requested'): ?>
                            <a href="<?php echo file_url($row->file_path); ?>" target="_blank">
                                <i class="glyphicon glyphicon-file"></i>
                                Ver
                            </a>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php e($row->type_name ? $row->type_name : 'No especificado'); ?>
                    </td>
                    <td>
                        <?php 
                            $screenig_origin_list = [
                                'requested' => 'Solicitado',
                                'historical' => 'Histórico',
                                'attach' => 'Adjunto'
                            ];
                        ?>
                        <?php e($screenig_origin_list[$row->origin] ? $screenig_origin_list[$row->origin] : '-'); ?>
                    </td>
                    <td align="left">
                        <?php if (isset($row->job_id)): ?>
                            <a href="<?php echo site_url('employer/recruitment_processes/show_process/' . $row->job_id);?>"
                                target="_blank">
                                <?php echo $row->job_title; ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!isset($row->job_id) || !$row->job_id): ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php e($row->due_date ? $row->due_date : '-'); ?>
                    </td>
                    <td>
                        <?php $remaining_days = trim((string)$row->remaining_days); ?>
                        <?php if ($remaining_days != ''): ?>
                            <?php echo $row->remaining_days > 0 ? '<span class="label label-success">Vigente</span>' : '<span class="label label-warning">Vencido</span>'; ?>
                        <?php endif; ?>

                        <?php if ($remaining_days == ''): ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row->origin == 'attach'): ?>
                            <button class="screening-upload-remove" data-file-id="<?php echo $row->id; ?>">
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
    $( '#tbl-screening-results' ).DataTable({
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
        "order": [[0, 'desc']],
        'columnDefs': [{
            'targets': [1, 2, 3, 4, 5],
            'orderable': false,
        }],
    });
</script>