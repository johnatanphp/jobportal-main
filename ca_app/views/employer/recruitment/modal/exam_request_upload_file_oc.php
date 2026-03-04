<div id="modal-upload-file-oc" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Subir archivo Orden de compra</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('employer/scheduled_exams/upload_file_oc', ['id' => 'form-upload-file-oc']); ?>
                    <input id="input-file-oc-seeker-id" 
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