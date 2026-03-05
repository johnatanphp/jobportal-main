<div class="modal fade" id="add_page_form">
  <div class="modal-dialog">
    <form name="frm_countries" id="frm_countries" role="form" method="post" action="<?php echo base_url('admin/countries/add');?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Agregar</h4>
        </div>
        <div class="modal-body"> 
          <!-- /.box-header --> 
          <!-- form start -->
          
          <div class="box-body">
            <div class="form-group">
              <input type="text" class="form-control"  id="country_name" name="country_name" value="<?php echo set_value('country_name');?>" placeholder="Country Name" required>
              </div>
              <div class="form-group">
              <input type="text" class="form-control"  id="country_citizen" name="country_citizen" value="<?php echo set_value('country_citizen');?>" placeholder="Nationality" required>
              <?php echo form_error('country_citizen'); ?> </div>
          </div>
          <!-- /.box-body -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" name="submitter" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>