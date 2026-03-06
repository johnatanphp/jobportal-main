<div class="modal fade" id="modal-job-charges">
  <div class="modal-dialog" style="min-width:60%;">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Gestión Grupo Ocupacional</h4>
        </div>
        <div class="modal-body"> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button id="btn-job-charges-add" 
                        class="btn btn-primary btn-xs pull-right">
                  Agregar
                </button>
              </div>
            </div>

            <div class="table-responsive" style="margin-top:10px;">
              <table id="tbl-manage-job-charges" class="table">
                  <thead>
                      <tr>
                          <th>
                              Nombre
                          </th>
                          <th>Valorización puntaje</th>
                          <th>Valorización grado</th>
                          <th>
                              Estado
                          </th>
                          <th>
                          </th>
                      </tr>
                  </thead>
                  <tbody></tbody>
              </table>  
            </div>      
          <!-- /.box-body -->           
        </div>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<?php $this->load->view('admin/job_charges/partials/modal_job_charges_add'); ?>
<?php $this->load->view('admin/job_charges/partials/modal_job_charges_edit'); ?>
<?php $this->load->view('admin/job_charges/partials/modal_skills_form'); ?>
<?php $this->load->view('admin/job_charges/scripts/manage_job_charges'); ?>