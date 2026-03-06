<div class="modal fade" id="modal-risk-criteria">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Gestión Criterio de Riesgo</h4>
        </div>
        <div class="modal-body"> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button id="btn-risk-criteria-add" 
                        class="btn btn-primary btn-xs pull-right">
                  Agregar
                </button>
              </div>
            </div>

            <div class="table-responsive" style="margin-top:10px;">
              <table id="tbl-manage-risk-criteria" class="table">
                  <thead>
                      <tr>
                          <th>
                              Nombre
                          </th>
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
<?php $this->load->view('admin/risk_criteria/partials/modal_risk_criteria_add'); ?>
<?php $this->load->view('admin/risk_criteria/partials/modal_risk_criteria_edit'); ?>
<?php $this->load->view('admin/risk_criteria/scripts/manage_risk_criteria'); ?>