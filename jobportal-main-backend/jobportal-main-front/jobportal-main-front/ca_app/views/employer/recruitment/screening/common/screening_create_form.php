<?php echo form_open('employer/recruitment/screening/create', ['id' => 'form-create-screening']); ?>
    <div class="modal-body">
        <p>Está apunto de crear una solicitud de screening. El proceso de creación puede tardar unos segundos.</p>
        <br>
        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
        <input type="hidden" name="seeker_id" value="<?php echo $seeker_id; ?>">

        <?php if (count($screening_types) == 1): ?>
            <input type="hidden" name="type" value="<?php echo key($screening_types); ?>">
        <?php endif; ?>
        
        <?php if (count($screening_types) > 1): ?>
            <div>
                <label for="">Tipo</label>
                <select name="type" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($screening_types as $type_key => $type_value): ?>
                        <option value="<?php echo $type_key; ?>">
                            <?php echo $type_value; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <button type="submit" 
                class="btn btn-primary btn-submit">Crear</button>
        <button type="button" 
            class="btn btn-default" 
            data-dismiss="modal">Cancelar</button>
    </div>
<?php echo form_close(); ?>