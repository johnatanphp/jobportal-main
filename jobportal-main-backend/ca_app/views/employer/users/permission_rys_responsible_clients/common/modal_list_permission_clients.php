<!-- Modal -->
<div id="modal-list-permission-clients" class="modal" role="dialog" data-employer-id="<?php echo $user->ID; ?>">
  <div class="modal-dialog" style="width: 100%;max-width: 850px;" >
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Clientes disponibles</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <button id="btn-add-permisison-clients" class="btn btn-sm btn-primary pull-right" type="button">
                Agregar
              </button>
            </div>
          </div>
          <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
              <table id="tbl-list-permission-clients" width="100%" class="table table-striped">
                <thead>
                  <tr>
                    <th>
                    </th>
                    <th>Consultora</th>
                    <th>Cliente</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<script type="module">
$(function(){
  $( '#btn-add-permisison-clients' ).click(function(){
    const rows = $( '#tbl-list-permission-clients' ).DataTable().column(0).checkboxes.selected();

    if (rows.length == 0) { 
      toastr["warning"]('Debe seleccionar al menos un registro');
      return;
    }

    const permissionClientsIds = [];
    $.each(rows, function(index, id){        
      permissionClientsIds.push(id);
    }); 

    const data = {
      'clients': permissionClientsIds,
      'employer_id': $( '#modal-list-permission-clients' ).data('employer-id')
    };

    const url = "<?php echo site_url('employer/users/Permission_rys_responsible_clients/add_clients'); ?>";
    $.post(url, data, function(response) {

      if (response.status) {
        $( '#tbl-list-permissions' ).DataTable().ajax.reload();
        $( '#tbl-list-permission-clients' ).DataTable().ajax.reload();
        toastr["success"](response.message);
        return;
      }

      if (!response.status) {
        toastr["error"](response.message);
        return;
      }
    }, 'json');
  });
});
</script>