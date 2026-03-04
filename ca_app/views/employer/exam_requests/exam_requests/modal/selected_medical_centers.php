<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Seleccionar Centro Médico</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <label>Centro Médico</label>
                    <select id="medical-center"
                            class="form-control medical-center" 
                            required="true" 
                            style="width: 100%;">
                        <option value="">Seleccione</option>

                        <?php foreach ($medical_centers as $center): ?>
                            <option value="<?php echo $center->COD_PROVEEDOR . '|' . $center->RAZON_SOCIAL . '|' . $center->CIUDAD; ?>">
                                <?php echo $center->RAZON_SOCIAL; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="wrapper-medical-center-location">
                        <br />
                        <label>Sede</label>
                        <select id="medical-center-location"
                                class="form-control" 
                                required="true" 
                                style="width: 100%;">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
            </div>        
        </div>
        <div class="modal-footer">
            <button id="selected-medical-center" type="button" class="btn btn-primary">
                Aceptar
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( ".medical-center").select2({
        placeholder: 'Seleccione'
    });
</script>