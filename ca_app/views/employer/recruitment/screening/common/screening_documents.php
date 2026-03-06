<div style="padding: 10px 0;">
	<div class="candidate-section-content">
		<h4 class="candidate-section-content-title">
			<?php echo $document->name; ?>
		</h4>
	</div> 
    <div>
        <table id="tbl-screening-results-docs" class="table" width="100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Screening</th>
                    <th>Tipo</th>
                    <th>Origen</th>
                    <th>Fecha vencimiento</th>
                    <th>Estado</th>
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
                                <?php $screening_url = $this->url_signer_lib->sign(site_url('general/jobseeker/screening/view/' .  $this->custom_encryption->encrypt_data($row->id, 1))); ?>
                                <a href="<?php echo $screening_url; ?>" target="_blank">
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $( '#tbl-screening-results-docs' ).DataTable({
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
            'targets': [1, 2, 3, 4],
            'orderable': false,
        }],
    });
</script>