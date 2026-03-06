<!-- Modal -->
<div id="modal-list-permissions" class="modal fade" role="dialog">
  <div class="modal-dialog" style="min-width: 70%;">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Listado de permisos</h4>
      </div>
      <div class="modal-body">
        <div style="padding: 10px 0;">
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-list-permission-clients">Agregar</button>
          <button id="btn-remove-permisison-clients" class="btn btn-sm btn-danger">Remover</button>
        </div>
        <table id="tbl-list-permissions" class="table" width="100%">
          <thead>
              <tr>
                <th style="text-align: center">
                </th>
                <th>Consultora código</th>
                <th>Consultora</th>
                <th>Cliente código</th>
                <th>Cliente</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
      </div>
    </div>
  </div>
</div>
<script type="module">
$(function(){

  $( '#btn-remove-permisison-clients' ).click(function(){
    const rows = $( '#tbl-list-permissions' ).DataTable().column(0).checkboxes.selected();

    if (rows.length == 0) { 
      toastr["warning"]('Debe seleccionar al menos un registro');
      return;
    }
    const permissionIds = [];
    $.each(rows, function(index, id){        
      permissionIds.push(id);
    }); 

    const data = {
      'ids': permissionIds,
      'job_layout_id': "<?php echo $job_layout->id?>" 
    };

    const url = "<?php echo site_url('admin/job_layout_permissions/remove_clients'); ?>";
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

  $( '#tbl-list-permissions' ).DataTable({
    "language": {
        "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
    },
    "destroy": true,
    "bAutoWidth": false,
    "deferRender": true,
    "iDisplayLength": 25,
    "bProcessing": true,
    ajax: {
      url: "<?php echo site_url('admin/job_layout_permissions/permission_clients'); ?>",
      type: 'GET',
      data: {
        job_layout_id: "<?php echo $job_layout->id; ?>"
      }
    },
    columns: [
      {data:'permission_id', 'className': 'style_td text-left'},
      {data:'consultant_code', 'className': 'style_td text-left'},
      {data:'consultant_name', 'className': 'style_td text-left'},
      {data:'client_code', 'className': 'style_td text-left'},
      {data:'client_name', 'className': 'style_td text-left'},
    ],
    columnDefs:[{
      targets:0,
      className: 'select-checkbox',
      checkboxes:{
        'selectRow': true,
        selector: 'td:first-child'
      },
    }]
  });
});
</script>