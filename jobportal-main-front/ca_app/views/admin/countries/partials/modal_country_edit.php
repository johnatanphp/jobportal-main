<!-- Edit Model-->
<div class="modal fade" id="edit_page_form">
  <div class="modal-dialog">
    <form name="frm_countries" id="frm_countries" role="form" method="post" action="<?php echo base_url('admin/countries/update');?>" onSubmit="return validate_edit_countries_form(this)">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Editar País</h4>
        </div>
        <div class="modal-body"> 
          <!-- /.box-header --> 
          <!-- form start -->
          
          <div class="box-body">
            <div class="form-group">
              <input type="text" class="form-control"  id="edit_country_name" name="edit_country_name" value="<?php echo set_value('country_name');?>" placeholder="Country Name">
              <?php echo form_error('edit_country_name'); ?> </div>
          </div>
          <div class="form-group">
          <input type="text" class="form-control"  id="edit_country_citizen" name="edit_country_citizen" value="<?php echo set_value('country_citizen');?>" placeholder="Nationality" required>
         <?php echo form_error('edit_country_citizen'); ?>
          </div>
              <input type="hidden" name="countries_id" id="countries_id" />
          <!-- /.box-body --> 
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" name="submitter" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 