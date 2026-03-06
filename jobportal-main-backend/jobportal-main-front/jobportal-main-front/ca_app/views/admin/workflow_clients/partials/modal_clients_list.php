<div class="modal fade" id="modal-clients">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Gestión Clientes</h4>
        </div>
        <div class="modal-body"> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button id="btn-clients-add" 
                        class="btn btn-primary btn-xs pull-right">
                  Agregar
                </button>
              </div>
            </div>
            <input type="hidden" name="company_id" value="">
            <div class="table-responsive" style="margin-top:10px;">
              <table id="tbl-manage-clients" class="table">
                  <thead>
                      <tr>
                          <th>
                            Cliente codigo
                          </th>
                          <th>
                              Cliente nombre
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
