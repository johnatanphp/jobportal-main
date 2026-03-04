<div class="modal fade" id="modal-cost-centers">
  <div class="modal-dialog" style="max-width: 1080px; width: 100%;">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Gestión Centros de costos</h4>
        </div>
        <div class="modal-body"> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button id="btn-cost-centers-add" 
                        class="btn btn-primary btn-xs pull-right">
                  Agregar
                </button>
              </div>
            </div>
            <input type="hidden" name="company_id" value="">
            <div class="table-responsive" style="margin-top:10px;">
              <table id="tbl-manage-cost-centers" class="table">
                  <thead>
                      <tr>
                          <th>
                            Consultora
                          </th>
                          <th>
                              Unidad de negocio
                          </th>
                          <th>
                              Cliente nombre
                          </th>
                          <th width="120">
                            Centro de costo
                          </th>
                          <th width="120">
                            Penalización
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
