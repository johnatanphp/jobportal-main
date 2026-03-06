<div id="modal-medical-center-edit-locations" class="modal fade" role="dialog">
    <!-- Modal -->
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Sede - <span class="mc-name"></span></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('employer/medical_centers/locations/edit', ['id' => 'form-mc-edit-location']); ?>
                    <div>
                        <input type="hidden" name="id" value="">
                    </div>
                    <div>
                        <label>Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <br />
                    <div>
                        <label>Ciudad / Ubicación</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <br />
                    <div>
                        <label>Dirección</label>
                        <textarea name="address" rows="3" class="form-control" required></textarea>
                    </div>
                    <br />
                    <div>
                        <label>Estado</label>
                        <select name="status" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <br />
                    <div align="right">
                        <input type="submit" value="Guardar" class="btn btn-primary">
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
