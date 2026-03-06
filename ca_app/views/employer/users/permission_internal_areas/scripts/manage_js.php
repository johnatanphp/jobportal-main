<script type="text/javascript">
    $(document).ready(function() {

        function searchInternalAreas()
        {
            $( '#tbl-internal-areas' ).DataTable({
                "language": {
                    "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                },
                "destroy": true,
                "bAutoWidth": false,
                "deferRender": true,
                "iDisplayLength": 25,
                "bProcessing": true,
                ajax: {
                    url: "<?php echo site_url('employer/users/permission_internal_areas/get_areas'); ?>",
                    type: 'GET',
                    data : {
                        user_id: "<?php echo $app_user_info->ID; ?>"
                    }
                },
                columns: [
                    {data:'area_id', 'className': 'style_td text-left'}, 
                    {data:'area_name', 'className': 'style_td text-left'}, 
                ],
                columnDefs:[{
                    targets:0,
                    checkboxes:{
                        seletRow:true
                    }
                }]
            });
        }

        $( '#tbl-permission-areas' ).DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            "destroy": true,
            "bAutoWidth": false,
            "deferRender": true,
            "iDisplayLength": 25,
            "bProcessing": true,
            ajax: {
                url: "<?php echo site_url('employer/users/permission_internal_areas/get_list'); ?>",
                type: 'GET',
                data : {
                    user_id: "<?php echo $app_user_info->ID; ?>"
                }
            },
            columns: [
                {
                    data: null,
                    render: function(row) {
                    return `<a href="#" class="js-remove-area"
                                        data-id="${row.id}">
                                <label class="label label-danger">
                                    <i class="material-icons" style="font-size:10px;">clear</i>
                                </label>
                            </a>`;
                    }
                ,'className': 'style_td text-center'
                },    
                {data:'area_name', 'className': 'style_td text-left'}, 
            ]
        });

        $( '#btn-add-areas' ).click(function(){
            searchInternalAreas();
            $( '#modal-add-areas' ).modal('show');
        });

        $( '#save-internal-areas').click(function(){

            var rowSelected = $( '#tbl-internal-areas' ).DataTable().column(0).checkboxes.selected();

            if (rowSelected.length == 0) {
                toastr["error"]('Debe seleccionar al menos 1 area');
                return;
            }

            var ids = [];
            for (var i = 0; i < rowSelected.length; i++) {
                ids.push(rowSelected[i]);
            }

            var url = "<?php echo site_url('employer/users/permission_internal_areas/add'); ?>";

            var data = {
                'ids': ids,
                'user_id': "<?php echo $app_user_info->ID; ?>"
            };
            $.post(url, data, function(res) {
                if (res.status) {
                    $( '#tbl-internal-areas' ).DataTable().ajax.reload();
                    $( '#tbl-permission-areas' ).DataTable().ajax.reload();

                    toastr["success"](res.message);

                    return;
                }  

                if (!res.status) {
                    toastr["error"](e.message);
                }  
            }, 'json')
            .fail(function() {
                alert('¡Ha ocurrido al procesar la solicitud!');
            }).always(function(){});  
        });

        $(document).on('click', '.js-remove-area', function(){
            
            var url = "<?php echo site_url('employer/users/permission_internal_areas/delete'); ?>";

            var data = {
                'id': $(this).data('id')
            };
            $.post(url, data, function(res) {
                if (res.status) {
                    $( '#tbl-permission-areas' ).DataTable().ajax.reload();
                    return;
                }  

                if (!res.status) {
                    toastr["error"](e.message);
                }  
            }, 'json')
            .fail(function() {
                alert('¡Ha ocurrido al procesar la solicitud!');
            }).always(function(){});  
        });
    });
</script>