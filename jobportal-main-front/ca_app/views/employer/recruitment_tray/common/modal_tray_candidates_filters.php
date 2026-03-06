<div id="modal-tray-candidates-filters" class="modal fade modal-style-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open('', ['class' => 'tray-candidates-filters']); ?>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Aplicar filtro</h4>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label>Origen</label>
            <select name="origin_type" class="form-control" style="width: 100%;">
                <option value="-1">
                  Todos
                </option>
                <option value="0">
                  Sin solicitud
                </option>
                <option value="1">
                  Por solicitud
                </option>
            </select>
          </div>

          <div class="form-group">
            <label>Estado</label>
            <select name="status_ids" class="form-control" multiple style="width: 100%;">
              <?php foreach ($tray_recruitment_status as $row_status): ?>
                <option value="<?php echo $row_status->id; ?>" selected>
                  <?php e($row_status->name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-style-1" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary btn-style-1">Filtrar</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script type="module">
  $( 'select[name="status_ids"]', '#modal-tray-candidates-filters' ).select2({
    closeOnSelect: false
  });
  
  $( 'select[name="origin_type"]', '#modal-tray-candidates-filters' ).select2();
</script>