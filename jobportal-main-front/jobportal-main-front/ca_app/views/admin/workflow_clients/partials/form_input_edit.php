<div class="modal-body"> 
    <!-- /.box-header --> 
    <!-- form start -->
    <div class="box-body">
        <input type="hidden" name="id" value="<?php echo $client->id; ?>">

        <div class="form-group">
            <input type="text" class="form-control"  name="code" placeholder="Codigo Cliente" required value="<?php echo $client->code; ?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control"  name="name" placeholder="Nombre Cliente" required value="<?php echo $client->name; ?>">
        </div>

        <div class="form-group">
            <select name="active" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="1" <?php echo $client->active == '1' ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo $client->active == '0' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
    </div>
    <!-- /.box-body --> 
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    <button type="submit" name="submitter" class="btn btn-primary">Guardar</button>
</div>