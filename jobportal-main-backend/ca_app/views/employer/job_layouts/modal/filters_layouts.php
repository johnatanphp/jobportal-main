<!-- Modal -->
<div id="modal-filter-profile" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open('employer/job_layouts/my_job_layouts/search', array('method' => 'get')); ?>
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Filtrar Layouts</h4>
    </div>
    <div class="modal-body">
      <div class="panel-filter"> 
        <div class="filter-title">
          <h4>Estado</h4>
        </div>
        <select name="status" class="form-control">
          <option value="" <?php echo $filters['status'] == '' ? 'selected="selected"' : ''; ?>>
            Todos
          </option>
          <option value="1" <?php echo $filters['status'] == '1' ? 'selected="selected"' : ''; ?>>
            Activo
          </option>
          <option value="0" <?php echo $filters['status'] == '0' ? 'selected="selected"' : ''; ?>>
            Inactivo
          </option>
        </select>
      </div>				
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary" >Filtrar</button>
    </div>
    <?php echo form_close(); ?>
    </div>
  </div>
</div>
