<div class="modal fade" id="modal-seeker-update-city" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Actualizar ubicación</h4>
            </div>
            <?php echo form_open('employer/recruitment_processes/update_city'); ?>
            <div class="modal-body">
                <input type="hidden" name="id">

                <select name="city" class="form-control" required width="100%">
                    <option value="">Seleccione</option>
                    <?php foreach ($ubigeos as $row): ?>
                        <option value="<?php echo $row->ubigeo; ?>">
                            <?php echo $row->ubigeo; ?>
                        </option>
                    <?php endforeach; ?>
                </select>     
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
