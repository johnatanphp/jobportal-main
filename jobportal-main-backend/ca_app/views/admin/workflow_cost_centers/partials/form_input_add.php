<div class="modal-body"> 
    <!-- /.box-header --> 
    <!-- form start -->
    <div class="box-body">
        <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">

        <div class="form-group">
            <label style="text-align: left;">
                Consultora
            </label>
            <select name="cia_code" class="form-control" required>
                <option value="">Consultora</option>
                <?php foreach($consultants as $row): ?>
                    <option value="<?php echo $row->code; ?>">
                        <?php echo $row->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label style="text-align: left;">
                Unidad de Negocio
            </label>
            <select name="business_unit_code" class="form-control" required>
                <option value="">Unidad de Negocio</option>
                <?php foreach($business_units as $row): ?>
                    <option value="<?php echo $row->business_unit_code; ?>">
                        <?php echo $row->business_unit_name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label style="text-align: left;">
                Cliente
            </label>
            <select name="client_code" class="form-control" required>
                <option value="">Cliente</option>

                <?php foreach($clients as $row): ?>
                    <option value="<?php echo $row->code; ?>">
                        <?php echo $row->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label style="text-align: left;">
                CECO
            </label>
            <input type="text" class="form-control"  name="code" placeholder="Centro de costo" required>
        </div>

        <div class="form-group">
            <label style="text-align: left;">
                Solicitudes para el CECO
            </label>
            <select name="has_penalty" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="0" selected>Sin penalización</option>
                <option value="1">Con penalización</option>
            </select>
        </div>

        <div class="form-group">
            <label style="text-align: left;">
                Estado
            </label>
            <select name="active" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
    <!-- /.box-body --> 
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    <button type="submit" name="submitter" class="btn btn-primary">Agregar</button>
</div>