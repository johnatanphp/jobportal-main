<!-- Modal -->
<div id="modal-edit-profile-resources" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Recursos</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('employer/job_profiles/profiles/edit_resources', ['id' => 'form-profile-edit-resources']); ?>
                    <input type="hidden" name="job_profile_id" value="<?php echo $job_profile->ID;?>">
                    <table  class="table table-striped table-resources" width="100%">              
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $job_profile_resources = set_value('resources') ? set_value('resources') : [];
                            ?>

                            <?php 
                                $resource_emo = $this->Job_profile->get_resource_by_name('type_emo', $job_profile->ID);
                            ?>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <label>Tipo EMO</label>
                                    <select name="resources[type_emo][value][]" class="form-control" id="type_emo" style="width: 100%;">
                                    <?php 
                                        $type_emo_selected = isset($job_profile_resources['type_emo']['value']) ? (array)$job_profile_resources['type_emo']['value'] : array_map('trim', explode(',', @$resource_emo->resource_value));
                                    ?>
                                    <?php foreach (get_options_type_emo() as $option): ?>
                                        <option value="<?php echo $option; ?>" <?php echo in_array($option, $type_emo_selected) ? 'selected="selected"' : '';?>>
                                        <?php echo $option; ?>
                                        </option>
                                    <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('resources[type_emo][value]'); ?>
                                    <div id="emo-detail" style="display: none;margin-top:5px;">
                                        <label>Detallar</label>
                                        <input id="protocol-detail" type="text" name="resources[type_emo][protocol_detail]" class="form-control" placeholder="Detallar Protocolo" value="<?php echo $resource_emo->protocol_detail; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <label>Examen COVID</label>
                                    <select name="resources[exam_type_covid][value][]" 
                                            class="form-control validate-input" 
                                            id="exam_type_covid"
                                            multiple="multiple" 
                                            style="width: 100%;" >
                                    <?php 
                                        $resource_covid = $this->Job_profile->get_resource_by_name('exam_type_covid', $job_profile->ID);

                                        $exam_type_covid_selected = isset($job_profile_resources['exam_type_covid']['value']) ? (array)$job_profile_resources['exam_type_covid']['value'] : array_map('trim', explode(',', @$resource_covid->resource_value));
                                    ?>
                                    <?php foreach (get_options_exam_type_covid() as $key => $option): ?>
                                        <option value="<?php echo $key; ?>" <?php echo in_array($key, $exam_type_covid_selected) ? 'selected="selected"' : '';?>>
                                        <?php echo $option; ?>
                                        </option>  
                                    <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('resources[exam_type_covid][value]'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <button id="edit-profile-resources" type="submit" class="btn btn-primary">
                                        Guardar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>