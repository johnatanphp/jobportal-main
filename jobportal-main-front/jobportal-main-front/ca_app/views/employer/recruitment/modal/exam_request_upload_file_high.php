<div id="modal-upload-file-high" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Subir archivo de alta</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('employer/scheduled_exams/upload_file_high', ['id' => 'form-upload-file-high']); ?>
                    <input id="input-file-high-seeker-id" 
                           type="hidden" 
                           name="seeker_id" 
                           value="">
                    <label>Archivo</label>
                    <input type="file" name="file">
                    <br />
                    <button class="btn btn-primary form-submit" type="submit">Guardar</button>
                    <button class="btn btn-primary form-cancel" data-dismiss="modal" type="button">
                        Cancelar
                    </button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>