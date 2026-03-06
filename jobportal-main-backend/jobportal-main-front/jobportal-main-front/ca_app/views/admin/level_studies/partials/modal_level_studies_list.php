<div class="modal fade" id="modal-level-studies">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Gestión Grado de estudios</h4>
        </div>
        <div class="modal-body"> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button id="btn-level-studies-add" 
                        class="btn btn-primary btn-xs pull-right">
                  Agregar
                </button>
              </div>
            </div>

            <div class="table-responsive" style="margin-top:10px;">
              <table id="tbl-manage-level-studies" class="table">
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
<?php $this->load->view('admin/level_studies/partials/modal_level_studies_add'); ?>
<?php $this->load->view('admin/level_studies/partials/modal_level_studies_edit'); ?>
<?php $this->load->view('admin/level_studies/scripts/manage_level_studies'); ?>