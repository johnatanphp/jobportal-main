<div id="modal-medical-center-add-locations" class="modal fade" role="dialog">
    <!-- Modal -->
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Sede - <span class="mc-name"></span></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('employer/medical_centers/locations/create', ['id' => 'form-mc-add-location']); ?>
                    <div>
                        <input type="hidden" name="code" value="" class="mc-code">
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
                    <div align="right">
                        <input type="submit" value="Agregar" class="btn btn-primary">
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
