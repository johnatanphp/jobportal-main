<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Cargar documento</h4>
        </div>
        <div class="modal-body">
            
            <div class="formint">
                <?php echo form_open('employer/exam_requests/request_results/upload', ['id' => 'form-upload-exam-result']); ?>
                    <input type="hidden" name="result_id" value="<?php echo $result_id; ?>">

                    <div class="input-group">
                        <label class="input-group-addon">Documento</label>
                        <input type="file" name="doc_file" required="true">
                    </div>
                    <div class="input-group">
                        <label class="input-group-addon">Resultado</label>
                        
                        <select name="approved" class="form-control" required="true">
                            <option value="">Seleccione</option>
                            <?php foreach ($result_options as $row_result): ?>
                                <option value="<?php echo $row_result->id;?>">
                                    <?php echo $row_result->result_name; ?>        
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <br />
                    <div id="container-form-buttons" style="text-align: center;">
                        <input type="submit" value="Guardar" class="btn btn-primary">
                        <input type="button" value="Cancelar" class="btn btn-secundary form-cancel" data-dismiss="modal">      
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
