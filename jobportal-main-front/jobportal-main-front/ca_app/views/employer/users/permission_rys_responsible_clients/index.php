<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<style>
.ui-button {
  margin-left: -1px;
}
.ui-button-icon-only .ui-button-text {
  padding: 0.35em;
}
.ui-autocomplete-input {
  margin: 0;
  padding: 0.48em 0 0.47em 0.45em;
}
</style>
<?php $this->load->view('common/before_head_close'); ?>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row">
  <div class="col-md-3">
  <div class="dashiconwrp">
    <?php $this->load->view('employer/common/menu/sidebar'); ?>
  </div>
  </div>
  
    <div class="col-md-9"> 
      <div class="formwraper">
        <div class="titlehead">
          <a href="<?php echo site_url('employer/users/profiles/index/' . $user->ID); ?>" 
             style="color:#fff;">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Gestionar Clientes - RyS Responsables - <?php echo $user->first_name; ?>
        </div>
        <div class="row"> 
          <div class="col-md-12">
            <div class="formint">
              <input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
              <div id="content-other-info">
                <div class="row">
                  <div class="col-md-6">
                    <h4>Clientes asignados</h4> 
                  </div>
                  <div class="col-md-6">

                    <button id="add-permission-clients"
                            type="button" 
                            class="btn btn-sm btn-primary pull-right">
                      Agregar
                    </button>
                    <button id="btn-remove-permisison-clients"
                            type="button" 
                            class="btn btn-sm btn-default pull-right"
                            style="margin-right: 10px;">
                      Quitar
                    </button>
                  </div>
                </div>
                <div style="padding-top:15px;">
                  <table id="tbl-list-permissions" class="table table-striped">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Consultora</th>
                        <th>Cliente</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('common/bottom_ads'); ?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>

<?php $this->load->view('employer/users/permission_rys_responsible_clients/common/modal_list_permission_clients'); ?>

<?php $this->load->view('common/before_body_close'); ?>

<script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.2.11/js/dataTables.checkboxes.min.js"></script>
<script type="text/javascript">
    $(function() {

      $( '#tbl-list-permissions' ).DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            "destroy": true,
            "bAutoWidth": false,
            "deferRender": true,
            "iDisplayLength": 10,
            "bProcessing": true,
            "order":[[0, 'DESC']],
            ajax: {
                url: "<?php echo site_url('employer/users/Permission_rys_responsible_clients/permissions/' . $user->ID); ?>",
                type: 'GET'
            },
            columns: [
              {data:'permission_id', 'className': 'style_td text-left'},
              {
                data: null,
                render: function(row) {
                    return `${row.consultant_name}
                            <span style="display:block;font-size:12px;color: #888;font-style:italic;">${row.consultant_code}</span>`;
                }
              ,'className': 'style_td text-left'
              },
              {
                data: null,
                render: function(row) {
                    return `${row.client_name}
                            <span style="display:block;font-size:12px;color: #888;font-style:italic;">${row.client_code}</span>`;
                }
              ,'className': 'style_td text-left'
              },
            ],
            columnDefs:[{
            targets:0,
            className: 'select-checkbox',
            checkboxes:{
                'selectRow': true,
                selector: 'td:first-child'
            },
            },
            {
                targets:[0, 1, 2],
                orderable: false,
            }]
        });


      $( '#add-permission-clients' ).click(function(){

        $( '#tbl-list-permission-clients' ).DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            "destroy": true,
            "bAutoWidth": false,
            "deferRender": true,
            "iDisplayLength": 10,
            "bProcessing": true,
            "order":[[0, 'DESC']],
            ajax: {
                url: "<?php echo site_url('employer/users/Permission_rys_responsible_clients/clients/' . $user->ID); ?>",
                type: 'GET'
            },
            columns: [
              {data:'id', 'className': 'style_td text-left'},
              {
                data: null,
                render: function(row) {
                    return `${row.consultant_name}
                            <span style="display:block;font-size:12px;color: #888;font-style:italic;">${row.consultant_code}</span>`;
                }
              ,'className': 'style_td text-left'
              },
              {
                data: null,
                render: function(row) {
                    return `${row.client_name}
                            <span style="display:block;font-size:12px;color: #888;font-style:italic;">${row.client_code}</span>`;
                }
              ,'className': 'style_td text-left'
              },
            ],
            columnDefs:[{
            targets:0,
            className: 'select-checkbox',
            checkboxes:{
                'selectRow': true,
                selector: 'td:first-child'
            },
            },
            {
                targets:[0, 1, 2],
                orderable: false,
            }]
        });

        $( '#modal-list-permission-clients' ).modal('show');

      });

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
                'employer_id': "<?php echo $user->ID; ?>" 
            };

            const url = "<?php echo site_url('employer/users/Permission_rys_responsible_clients/remove_clients'); ?>";
            $.post(url, data, function(response) {
                if (response.status) {
                    $( '#tbl-list-permissions' ).DataTable().ajax.reload();

                    if ($.fn.dataTable.isDataTable('#tbl-list-permission-clients')) {
                        $( '#tbl-list-permission-clients' ).DataTable().ajax.reload();
                    }
                    
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
</body>
</html>