<?php
    $exam_types = [$document->id];

    if ($document->id == 4) {
        $exam_types = [2, 1];
    }
?>
<style type="text/css">
    .table .row-selected td {
        background: #d8f1fd;
    }
</style>
<div class="modal-dialog" style="width: 99%">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Editar Solicitud - <?php echo $job->job_title; ?></h4>
        </div>
        <div class="modal-body">
            <div class="formwraper">
                <div class="formint">
                    <?php echo form_open('employer/exam_requests/requests/do_edit', ['id' => 'form-edit-request']); ?>
                        <input type="hidden" name="request_id" value="<?php echo $exam_request->request_id; ?>">
                        <div style="padding: 12px 0;">
                            <div class="input-group">
                                <label class="input-group-addon">
                                    <h5><b>Programación para <?php echo $document->name; ?></b></h5>
                                </label>
                            </div>

                            <div">
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="submit" 
                                               class="btn btn-xs btn-primary pull-right" 
                                               value="Editar solicitud">
                                         <button id="open-medical-centers"
                                            type="button" 
                                            class="btn btn-xs btn-primary pull-right" 
                                            style="margin-right: 5px;">
                                            Centro Médicos
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <table id="tbl-request-edit" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" width="10">
                                            <input type="checkbox" id="check-seekers">
                                        </th>
                                        <th align="left" width="8%">Doc.</th>
                                        <th align="left" width="20%">Nombre</th>
                                        <th align="left">Ubicación</th>
                                        <th width="5%">Fecha Exam.</th>
                                        <th>Centro médico</th>
                                        <?php foreach ($exam_types as $row_exam_type_id): ?>
                                            <?php if ($row_exam_type_id == 1): ?>
                                                <th width="20%">
                                                    Tipo EMO
                                                </th>
                                                <th width="20%">
                                                    Protocolo Extra
                                                </th>  
                                            <?php endif; ?>

                                            <?php if ($row_exam_type_id == 2): ?>
                                                <th>Exam. Tipo</th>
                                            <?php endif; ?>

                                            <?php if ($row_exam_type_id == 3): ?>
                                                <th>Screening/th>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($seekers as $seeker_index => $seeker): ?>

                                        <?php 
                                            if ($seeker->status == 5 || $seeker->status == 6) {
                                                continue;
                                            }
                                        ?>

                                        <tr data-key="<?php echo $seeker_index; ?>">
                                            <input type="hidden" 
                                                   name="seekers[<?php echo $seeker_index; ?>][scheduled_exam_id]" 
                                                   value="<?php echo $seeker->id; ?>">
                                            <td align="center">
                
                                                <input type="checkbox" 
                                                       name="seekers[<?php echo $seeker_index; ?>][seeker_id]" 
                                                       class="check-seeker-edit" 
                                                       value="<?php echo $seeker->seeker_id; ?>">
                                            </td>
                                            <td align="left">
                                                <?php echo document_type_abbr($seeker->document_type) . ' ' . $seeker->document_number; ?>
                                            </td>
                                            
                                            <td align="left">
                                                <?php echo $seeker->first_name . ' ' . $seeker->last_name; ?>
                                            </td>
                                            <td>
                                                <?php echo $seeker->city; ?>
                                            </td>
                                            <td>
                                                <input type="date" 
                                                       name="seekers[<?php echo $seeker_index; ?>][exam_date]" 
                                                       value="<?php echo $seeker->exam_date; ?>"
                                                       min="<?php echo date('Y-m-d'); ?>">
                                            </td>
                                            <td>
                                                <input type="hidden" 
                                                       class="input-medical-center"
                                                       name="seekers[<?php echo $seeker_index; ?>][medical_center]" 
                                                       value="<?php echo $seeker->medical_center_code . '|' . $seeker->medical_center_name . '|' . $seeker->medical_center_city; ?>">
                                                
                                                <input type="hidden" 
                                                       class="input-medical-center-location"
                                                       name="seekers[<?php echo $seeker_index; ?>][medical_center_location]" 
                                                       value="<?php echo $seeker->medical_center_location_id ? $seeker->medical_center_location_id : ""; ?>">

                                                <?php echo $seeker->medical_center_name . ($seeker->medical_center_location != $seeker->medical_center_name ? ' - ' . $seeker->medical_center_location : ''); ?>
                                            </td>

                                            <?php foreach ($exam_types as $row_exam_type_id): ?>
                                                <?php if ($row_exam_type_id == 2): ?>
                                                    <td>
                                                        <?php 
                                                            $row_exam_type = $this->Exam_request_seeker_exam->get_exam_type($seeker->id, $row_exam_type_id);

                                                            if (!$row_exam_type) {
                                                                continue;
                                                            }

                                                            $list_type = get_options_exam_type_covid();
                                                            $covid19_options = explode(',', $row_exam_type->exam_doc_type);

                                                            foreach ($covid19_options as $val) {
                                                                echo isset($list_type[$val]) ? $list_type[$val] . '<br />' : '-';
                                                            }
                                                        ?>
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ($row_exam_type_id == 1): ?>
                                                    <?php $row_exam_type = $this->Exam_request_seeker_exam->get_exam_type($seeker->id, $row_exam_type_id); ?>

                                                    <td>
                                                        <?php 
                                                            $emo_options = [
                                                                'PROTOCOLO 1. ADMINISTRATIVO',
                                                                'PROTOCOLO 2. MANIPULADORES DE ALIMENTOS',
                                                                'PROTOCOLO 3. MERCADERISTAS',
                                                                'PROTOCOLO 4. CONDUCTOR DE VEHÍCULOS',
                                                                'PROTOCOLO 5. JARDINERO',
                                                                'PROTOCOLO 6. LIMPIEZA',
                                                                'PROTOCOLO 7. MANTENIMIENTO Y SERVICIOS GENERALES',
                                                                'PROTOCOLO 8. PERSONAL DE SALUD/ASISTENCIAL',
                                                                'PROTOCOLO 9. OPERARIO SOLDADOR',
                                                                'PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE'
                                                            ];
                                                        ?>
                                                        <select name="seekers[<?php echo $seeker_index; ?>][emo_type]" class="form-control">
                                                            <option value="">-</option>
                                                            <?php foreach ($emo_options as $option): ?>
                                                                <?php 
                                                                    if ($row_exam_type->exam_doc_type) {
                                                                        $emo_selected = $option == $row_exam_type->exam_doc_type;
                                                                    } else {
                                                                        $emo_selected = $option == $resource_emo->resource_value;
                                                                    }
                                                                ?>
                                                                <option value="<?php echo $option; ?>" <?php echo $emo_selected ? 'selected="selected"' : ''; ?>>
                                                                    <?php echo $option; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="text" 
                                                            name="seekers[<?php echo $seeker_index; ?>][protocol_extra]" 
                                                            class="form-control" 
                                                            placeholder="Protocolo Extra (Opcional)" 
                                                            value="<?php echo $row_exam_type->protocol_extra; ?>">
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ($row_exam_type_id == 3): ?>
                                                    <input type="hidden" name="seekers[<?php echo $seeker_index; ?>][screening_type]" value="<?php echo $resource_screening->resource_value; ?>">
                                                    
                                                    <td>
                                                        <?php echo $resource_screening->resource_value; ?>        
                                                    </td>
                                                <?php endif; ?>
                                            <?php endforeach; ?>


                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($seekers)): ?>
                                        <tr>
                                           <td colspan="20" align="center">
                                                Sin resultados
                                           </td> 
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //var jobId = "<?php echo $job->ID; ?>";
        var scheduleId = "<?php echo $exam_request->schedule_id; ?>";

        function updateRow(check) {
            
            var row = $(check).closest('#tbl-request-edit tr');

            //Pintar fila seleccionada
            row.removeClass('row-selected');
        
            if ($(check).is(':checked')) {
                row.addClass('row-selected');
            }

            //Marcar check principal si
            //todos los check estan seleccionados
            var checkAll = $( '.check-seeker-edit' ).length == $( '.check-seeker-edit:checked' ).length;

            $( '#check-seekers' ).prop('checked', checkAll);
        }

        $( '#form-edit-request' ).submit(function(e){

            e.preventDefault();

            if ($( '#tbl-request-edit .check-seeker-edit' ).length != $( "#tbl-request-edit .input-medical-center" ).length) {
                toastr["error"]('¡Hay postulantes sin Centro médico!');
                return;
            }

            if (!window.confirm('¿Esta seguro de editar la solicitud?')) {
                return;
            }

            var action = $(this).prop('action');
            var data = $(this).serialize();

            $.post(action, data, function(response) {

                if (!response.success) {
                    toastr["error"](response.error);
                    return;
                }

                window.location = "<?php echo site_url('employer/exam_requests/requests/list/'); ?>" + scheduleId;

            }, 'json')
            .fail(function (){
                toastr["error"]('¡Ha ocurrido un error al enviar la solicitud!');
            });

            return false;
        });

        $(document).on('change', "#check-seekers", function() {
            $( ".check-seeker-edit" ).prop('checked', $(this).is(':checked')).change();
        });

        $(document).on('change', '.check-seeker-edit', function() {
            updateRow(this);
        });

        $(document).off('click', "#open-medical-centers");
        $(document).on('click', "#open-medical-centers", function(){

            if ($( '.check-seeker-edit:checked' ).length == 0) {
                toastr["error"]('¡Debe seleccionar al menos 1 postulante!');
                return;
            }

            $( "#modal-selected-medical-center" ).modal('show');
        });

        $(document).off("click", "#selected-medical-center");
        $(document).on("click", "#selected-medical-center", function(){

            var selectValue = $( "#medical-center" ).val();
            var selectText = $.trim($( "#medical-center" ).find('option:selected').text());

            if (selectValue == '') {
                toastr["error"]('¡Debe seleccionar un Centro médico!');
                return;
            }

            var medicalCenterLocationValue = $( "#medical-center-location" ).val();
            var medicalCenterLocationText = $.trim($( "#medical-center-location" ).find('option:selected').text());

            if (medicalCenterLocationValue == '') {
                toastr["error"]('¡Debe seleccionar una sede!');
                return;
            }

            if (selectText !== medicalCenterLocationText) {
                selectText = selectText + ' - ' + medicalCenterLocationText;
            }

            $( '.check-seeker-edit:checked' ).each(function(i, check){
                var row = $(check).closest('tr');
                var key = row.data('key');

                  medicalCenterHtml = `
                    <input type="hidden" 
                           class="input-medical-center"
                           name="seekers[${key}][medical_center]" 
                           value="${selectValue}">

                    <input type="hidden" 
                           class="input-medical-center-location"
                           name="seekers[${key}][medical_center_location]" 
                           value="${medicalCenterLocationValue}">
                    ${selectText}
                `;

                row.find('td:eq(5)').html(medicalCenterHtml);
            });

            $( "#check-seekers" ).prop('checked', false).change(); 
            $( "#modal-selected-medical-center" ).modal('hide');
        });

        $(document).off("change", "#medical-center");
        $(document).on("change", "#medical-center", function(){
            
            $( "#medical-center-location" ).prop('disabled', false).empty();

            var centerMedicalData = $(this).val().split("|");
            var centerMedicalCode = centerMedicalData[0];

            var url = "<?php echo site_url('employer/exam_requests/requests/get_medical_center_locations/'); ?>" + centerMedicalCode;
            $.post(url, {}, function(response){
                var locations = response.locations;
                var options = `<option value="">Seleccione</option>`;
            
                for (index in locations) {
                    var location = locations[index];
                    options+=`
                        <option value="${location.id}" >${location.location}</option>
                    `;
                }

                $( "#medical-center-location" ).html(options);

                if (locations.length == 1) {
                    $( "#medical-center-location option" ).eq(1).prop('selected', true);
                    $( "#medical-center-location" ).prop('disabled', true);
                }

            }, 'json')
            .always(function(){
               
            });
        });
    });
</script>
