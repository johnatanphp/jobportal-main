<div class="modal fade" id="modal-contract-documents">
  <div class="modal-dialog" style="min-width:60%;">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Gestión documentos de contratación</h4>
        </div>
        <div class="modal-body"> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button id="btn-contract-documents-add" 
                        class="btn btn-primary btn-xs pull-right">
                  Agregar
                </button>
              </div>
            </div>

            <div class="table-responsive" style="margin-top:10px;">
              <table id="tbl-manage-contract-documents" class="table">
                  <thead>
                      <tr>
                          <th>
                            ID
                          </th>
                          <th>
                              Nombre
                          </th>
                          <th>Tipo</th>
                          <th>
                            Grupo asignado
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
<?php $this->load->view('employer/recruitment/contract_documents/common/modal_documents_add'); ?>
<?php $this->load->view('employer/recruitment/contract_documents/common/modal_documents_edit'); ?>
<?php $this->load->view('employer/recruitment/contract_documents/scripts/manage_documents_js'); ?>
