<div class="modal-body"> 
    <!-- /.box-header --> 
    <!-- form start -->
    <div class="box-body">
        <input type="hidden" name="id" value="<?php echo $center->id; ?>">

        <div class="form-group">
            <label style="text-align: left;">
                Consultora
            </label>
            <select name="cia_code" class="form-control" required>
                <option value="">Consultora</option>
                <?php foreach($consultants as $row): ?>
                    <option value="<?php echo $row->code; ?>" <?php echo $row->code == $center->cia_code ? 'selected' : ''; ?>>
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
                    <option value="<?php echo $row->business_unit_code; ?>" <?php echo $row->business_unit_code == $center->business_unit_code ? 'selected' : ''; ?>>
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
                    <option value="<?php echo $row->code; ?>" <?php echo $row->code == $center->client_code ? 'selected' : ''; ?>>
                        <?php echo $row->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label style="text-align: left;">
                CECO
            </label>
            <input type="text" class="form-control"  name="code" placeholder="Centro de costo" required value="<?php echo $center->code; ?>">
        </div>

        <div class="form-group">
            <label style="text-align: left;">
                Solicitudes para el CECO
            </label>
            <select name="has_penalty" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="0" <?php echo $center->has_penalty == '0' ? 'selected' : ''; ?>>Sin penalización</option>
                <option value="1" <?php echo $center->has_penalty == '1' ? 'selected' : ''; ?>>Con penalización</option>
            </select>
        </div>
        
        <div class="form-group">
            <label style="text-align: left;">
                Estado
            </label>
            <select name="active" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="1" <?php echo $center->active == '1' ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo $center->active == '0' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
    </div>
    <!-- /.box-body --> 
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    <button type="submit" name="submitter" class="btn btn-primary">Guardar</button>
</div>