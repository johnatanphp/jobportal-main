<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <form id="form-exam-schedule-create" action="<?php echo site_url('employer/scheduled_exams/save_schedule'); ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Programar exámenes
                </h4>
            </div>
            <div class="modal-body">
                <div class="formwraper">
                    <div class="input-group">
                        <label class="input-group-addon">Fecha de examen <span>*</span></label>
                        <input id="schedule-date" 
                                type="date" 
                                name="exam_date" 
                                class="form-control" 
                                required="true"
                                min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <?php if (in_array(1, $exam_type_ids)): ?>
                        <?php if (!$resource_emo->resource_value): ?>
                            <div class="input-group">
                                <label class="input-group-addon">Tipo EMPO <span>*</span></label>
                                <select id="select-type-emo"
                                        name="emo_type" 
                                        class="form-control"
                                        required="true" 
                                        style="width:100%;">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($emo_exam_types as $key => $row): ?>
                                        <option value="<?php echo $row->exam_type_name; ?>">
                                            <?php echo $row->exam_type_name; ?>        
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?> 
                        
                        <?php if ($resource_emo->resource_value): ?>
                            <div class="input-group">
                                <label class="input-group-addon">Tipo EMPO <span>*</span></label>
                                <label><?php echo $resource_emo->resource_value; ?></label>
                            </div>
                        <?php endif; ?>   
                    <?php endif; ?>   

                    <?php if (in_array(2, $exam_type_ids)): ?>
                        <div class="input-group">
                            <label class="input-group-addon">Tipo COVID-19 <span>*</span></label>
                            <select id="select-covid19"
                                    name="covid19_type[]" 
                                    class="form-control"
                                    required="true" 
                                    style="width:100%;">

                                <?php if (count($covid19_options) > 0): ?>
                                    <?php foreach ($covid19_options as $key => $row): ?>
                                        <option value="<?php echo $row->name; ?>">
                                            <?php echo $row->name; ?>        
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?> 

                                <?php if (count($covid19_options) == 0): ?>
                                    <?php foreach ($covid_exam_types as $key => $row): ?>
                                        <option value="<?php echo $row->exam_type_name; ?>">
                                            <?php echo $row->exam_type_name; ?>        
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?> 

                            </select>
                        </div>
                    <?php endif; ?>                        
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button id="assing-date" type="submit" class="btn btn-primary">Asignar</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $( "#select-covid19" ).select2();
    $( "#select-type-emo" ).select2();
});
</script>
