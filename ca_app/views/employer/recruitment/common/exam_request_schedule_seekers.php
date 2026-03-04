<style>
    #table-exam-request-seekers-list tr td {
        font-size: 13px;
    }

    #table-exam-request-seekers-list thead th,
    #table-exam-request-seekers-list tbody td
    {
        padding: 6px;
    }

    .exam-comments-content {
        position: relative;
        display: inline-block;
        width: 26px;
        white-space: normal !important;
        cursor: pointer;
        border: 0 !important;
    }

    .exam-comments-content .exam-comments-icon {
        font-size: 24px;
        color: #e8e9e9;
    }

    .exam-comments-content .exam-comments-counter {
        position: absolute;
        top: -1px;
        right: -4px;
        background: red;
        padding: 1px 6px;
        border-radius: 100%;
        color: #fff;
        font-size: 11px;
    }

    .label-exam-status {
        width: 100px;
        margin-right: 15px;
        vertical-align: middle;
        display: inline-block;
        padding: 5px 8px;
    }
</style>
<div class="table-responsive">
    <table id="table-exam-request-seekers-list" class="table table-hover">
        <thead>
            <tr>
                <th width="10"></th>
                <th width="150"></th>
                <th width="110">Documento</th>
                <th width="210">Nombre</th>
                <th width="220">Ubigeo</th>
                <th width="100">Fecha</th>
                <th width="60">Examen</th>
                <th width="230">Tipo</th>
                <th width="250">Centro Médico</th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($seekers as $key => $seeker): ?>
                <?php
                    $exam_types_ids = $seeker->exam_type_ids ? explode(',', trim($seeker->exam_type_ids, ',')) : $exam_types;
                    $schedule_id = $seeker->seeker_id . '-' . join(',', $exam_types_ids); 
                ?>
                <tr>
                    <td>
                        <?php if (in_array($seeker->exam_request_seeker_status, [1, 8, 6, 7])): ?>     
                            
                           
                            <input class="check_schedule_seeker" type="checkbox" name="schedule[<?php echo $key; ?>][seeker_id]" value="<?php echo $schedule_id; ?>">
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="label label-default label-exam-status" title="<?php echo $seeker->status_name; ?>" style="background: <?php echo $seeker->status_color; ?>">
                            <?php echo $seeker->status_name; ?>
                        </span>
                    
                        <?php 
                            $sso_notification_color = $seeker->notified_sso || $seeker->schedule_id ? 'green' : '#bbbbbb';
                            $sso_notification_title = $seeker->notified_sso || $seeker->schedule_id ? 'Notificado a SSO' : 'Sin notificar a SSO';
                        ?>
                        <div title="<?php echo $sso_notification_title; ?>" style="vertical-align: middle;display: inline-block;color: <?php echo $sso_notification_color; ?>;">
                            <span style="display: block;width: 100%;text-align: center;">
                                <i class="glyphicon glyphicon-bell"></i>
                            </span>
                            <span style="display: block;width: 100%;text-align: center;">
                                SSO
                            </span>
                        </div>
                    </td>
                    <td>
                        <?php echo document_type_abbr($seeker->document_type) . ' '. $seeker->document_number; ?>  
                    </td>
                    <td>
                        <?php echo $seeker->first_name; ?> <br />
                        <span style="font-size: 12px;color: #999;font-style: italic;">
                            <?php echo $seeker->email; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (in_array($seeker->exam_request_seeker_status, [1, 8]) && 
                                (!$seeker->notified_sso && !$seeker->schedule_id)): ?>
                                <select class="form-control field-value ubigeo-select"
                                    data-job-id="<?php echo $job_id; ?>"
                                    data-schedule-id="<?php echo $schedule_id ?>"
                                    data-field="ubigeo" 
                                    style="width: 220px;">
                                <option value="">-</option>
                                <?php foreach ($ubigeos as $row): ?>
                                    <?php 
                                        $ubigeo_value = trim($row->order_administrative1) . ', ' . trim($row->order_administrative2) . ', ' . trim($row->order_administrative3);
                                        $seeker_ubigeo = $seeker->ubigeo;
                                    ?>
                                    <option value="<?php echo $ubigeo_value; ?>"
                                            <?php echo $ubigeo_value == $seeker_ubigeo ? 'selected="selected"' : ''; ?>>
                                        <?php echo $ubigeo_value; ?>        
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        
                        <?php else: ?>
                            <?php echo $seeker->ubigeo; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $seeker->exam_request_date ? $seeker->exam_request_date : ($seeker->schedule_exam_date ? $seeker->schedule_exam_date : "<span style='color: #777;font-style:italic;'>No establecido</span>"); ?>
                    </td>
                    <td>
                        <?php echo join(' + ', explode(',' , (string)$seeker->exam_type_names)); ?>
                    </td>
                    <td>
                        <?php echo join('<br>', explode(',' , (string)$seeker->exam_doc_types)); ?>
                    </td>
                    <td>
                        <?php echo $seeker->medical_center_name ? $seeker->medical_center_name : '<span style="color: #777;font-style:italic;">No establecido</span>'; ?>
                    </td>
                    <td>
                        <?php $comment_editable = in_array($seeker->exam_request_seeker_status, [1, 8]) && (!$seeker->notified_sso && !$seeker->schedule_id); ?>

                        <div class="exam-comments-content <?php echo $comment_editable ? 'exam-comments-editable' : 'exam-comments-view'; ?>" 
                             data-pk="1" 
                             data-job-id="<?php echo $job_id; ?>"
                             data-schedule-id="<?php echo $schedule_id ?>"
                             data-value="<?php echo $seeker->comment; ?>">
                            <span class="exam-comments-icon" >
                                <img src="<?php echo base_url('public/images/png/comments.png'); ?>" width="20" hight="20">
                            </span>
                            <?php if ($seeker->comment != ''): ?>
                                <div class="exam-comments-counter">1</div>
                            <?php endif; ?>
                        </div>
                    </td>                
                </tr>
            <?php endforeach; ?>

            <?php if (count($seekers) == 0): ?>
                <tr>
                    <td colspan="12" align="center">
                        Sin resultados
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function(){
        $( ".ubigeo-select" ).select2();
        $( document ).on('click', '.exam-comments-view', function(){
            $( '#modal-exam-request-comments .modal-body p' ).html($.trim($(this).data('value')) != '' ? $.trim($(this).data('value')) : 'Sin comentarios');
            $( '#modal-exam-request-comments' ).modal('show');
        });
    });
</script>

<?php if (count($seekers) > 0): ?>
    <script type="text/javascript">
        $( '#table-exam-request-seekers-list' ).DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            "lengthChange": false,
            "pageLength": 10,
            ordering:  false,
            "fnInitComplete": function (oSettings, json) {
                const height = $( '#modal-schedule-exam .modal-content' ).height() + 60;
                $( '#modal-schedule-exam .modal-content' ).css('height', `${height}px`);

                $( '.exam-comments-editable' ).editable({
                    type: 'textarea',
                    title: 'Comentarios',
                    rows: 10,
                    display: function(value, sourceData) {
                       $(this).html(value != '' ? `<span class="exam-comments-icon" >
                                                        <img src="<?php echo base_url('public/images/png/comments.png'); ?>" width="20" hight="20">
                                                    </span>
                                                    <div class="exam-comments-counter" style="position: absolute;">1</div>` : 
                                                    `<span class="exam-comments-icon" >
                                                        <img src="<?php echo base_url('public/images/png/comments.png'); ?>" width="20" hight="20">
                                                    </span>`);
                    },
                    url: "<?php echo site_url('employer/scheduled_exams/save_field_values'); ?>",
                    params: function(params) {
                        params.field = 'comment';
                        params.schedule_id = $(this).data('schedule-id');
                        params.job_id = $(this).data('job-id');
                        return params;
                    }
                });
            }
        });
    </script>
<?php endif; ?>