<?php
    $exam_types = [$document->id];

    if ($document->id == 4) {
        $exam_types = [2, 1];
    }
?>
<?php if (!empty($seekers)): ?>
    <div style="padding: 8px 10px;">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <input type="submit" 
                       class="btn btn-xs btn-primary pull-right" 
                       value="Crear solicitud">
                <button id="open-medical-centers"
                        type="button" 
                        class="btn btn-xs pull-right btn-primary" 
                        style="margin-right: 5px;">
                    Centro Médicos
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="text-align: center;" width="10">
                <input type="checkbox" id="check-seekers">
            </th>
            <th align="left" width="8%">Doc.</th>
            <th align="left" width="20%">Nombre</th>
            <th>Ubicación</th>
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
        <?php 
        foreach ($seekers as $seeker_index => $seeker): ?>
            <tr data-key="<?php echo $seeker_index; ?>">
              <input type="hidden" 
                     name="seekers[<?php echo $seeker_index; ?>][scheduled_exam_id]" 
                     value="<?php echo $seeker->scheduled_exam_id; ?>">
                <td align="center">
                    <input type="checkbox" 
                           name="seekers[<?php echo $seeker_index; ?>][seeker_id]" 
                           class="check-seeker" 
                           value="<?php echo $seeker->seeker_id; ?>">
                </td>
                <td align="left">
                    <?php echo document_type_abbr($seeker->document_type) . ' ' . $seeker->document_number; ?>
                </td>
                <td align="left">
                    <?php echo $seeker->first_name . ' ' . $seeker->last_name; ?>
                </td>
                <td>
                    <?php echo $seeker->ubigeo ? $seeker->ubigeo : $seeker->city; ?>
                </td>
                <td width="30">
                    <input type="date" 
                           class="form-control seeker-exam-date" 
                           name="seekers[<?php echo $seeker_index; ?>][exam_date]" 
                           value="<?php echo $seeker->exam_date; ?>">
                </td>
                <td>
                    -
                </td>

                <?php foreach ($exam_types as $row_exam_type_id): ?>
                    <?php if ($row_exam_type_id == 2): ?>
                        <td>
                            <?php 
                                $row_exam_type = $this->Exam_request_seeker_exam->get_exam_type($seeker->scheduled_exam_id, $row_exam_type_id);

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
                                    <?php $emo_selected = $option == $resource_emo->resource_value;?>
                                    <option value="<?php echo $option; ?>" <?php echo $emo_selected ? 'selected="selected"' : ''; ?>>
                                        <?php echo $option; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>

                        <td>
                            <?php
                                $protocol_extra_data = [];

                                if (!empty(@$resource_exams_complementary->resource_value) && @$resource_exams_complementary->resource_value != 'No aplica') {
                                    $protocol_extra_data[] = @$resource_exams_complementary->resource_value; 
                                }
                                
                                if (!empty(@$resource_emo->protocol_detail)) {
                                    $protocol_extra_data[] = $resource_emo->protocol_detail;
                                }

                                if (!empty($seeker->comment)) {
                                    $protocol_extra_data[] = $seeker->comment;
                                }

                                $protocol_extra = join(' - ', $protocol_extra_data);
                            ?>
                            <input type="text" 
                                name="seekers[<?php echo $seeker_index; ?>][protocol_extra]" 
                                class="form-control" 
                                placeholder="Protocolo Extra (Opcional)" 
                                value="<?php echo $protocol_extra; ?>">
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
                    ¡No hay postulantes programados!
               </td> 
            </tr>
        <?php endif; ?>
    </tbody>
</table>