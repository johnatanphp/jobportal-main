<div class="modal fade" id="modal-job-charges-skills-form">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Habilidades</h4>
        </div>
        <div class="modal-body"> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <table width="100%">
                    <?php echo form_open('admin/job_charges/skill_add', ['id' => 'form-job-charge-skill-add']); ?>
                      <tr>
                        <td>
                            <input type="text"
                                   name="skill_name"
                                   class="form-control"
                                   placeholder="Nombre habilidad">
                            <input type="hidden" name="job_charge_id" value="">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-xs pull-right"
                                    type="submit">
                                Agregar
                            </button>
                        </td>
                      </tr>
                    <?php echo form_close(); ?>
                </table>               
              </div>
            </div>

            <div style="margin-top:10px;">
                <div id="wrapper-skills" class="wrapper-skills">

                </div>
            </div>      
          <!-- /.box-body -->           
        </div>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
