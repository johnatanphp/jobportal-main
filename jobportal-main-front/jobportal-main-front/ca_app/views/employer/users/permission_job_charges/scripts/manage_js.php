<script type="text/javascript">
    $(document).ready(function() {

        function searchJobCharges()
        {
            $( '#tbl-job-charges' ).DataTable({
                "language": {
                    "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                },
                "destroy": true,
                "bAutoWidth": false,
                "deferRender": true,
                "iDisplayLength": 25,
                "bProcessing": true,
                ajax: {
                    url: "<?php echo site_url('employer/users/permission_job_charges/get_job_charges'); ?>",
                    type: 'GET',
                    data : {
                        user_id: "<?php echo $app_user_info->ID; ?>"
                    }
                },
                columns: [
                    {data:'charge_id', 'className': 'style_td text-left'}, 
                    {data:'charge_name', 'className': 'style_td text-left'}, 
                ],
                columnDefs:[{
                    targets:0,
                    checkboxes:{
                        seletRow:true
                    }
                }]
            });
        }

        $( '#tbl-permission-job-charges' ).DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            "destroy": true,
            "bAutoWidth": false,
            "deferRender": true,
            "iDisplayLength": 25,
            "bProcessing": true,
            ajax: {
                url: "<?php echo site_url('employer/users/permission_job_charges/get_list'); ?>",
                type: 'GET',
                data : {
                    user_id: "<?php echo $app_user_info->ID; ?>"
                }
            },
            columns: [
                {
                    data: null,
                    render: function(row) {
                    return `<a href="#" class="js-remove-job-charges"
                                        data-id="${row.id}">
                                <label class="label label-danger">
                                    <i class="material-icons" style="font-size:10px;">clear</i>
                                </label>
                            </a>`;
                    }
                ,'className': 'style_td text-center'
                },    
                {data:'charge_name', 'className': 'style_td text-left'}, 
            ]
        });

        $( '#btn-add-job-charges' ).click(function(){
            searchJobCharges();
            $( '#modal-add-job-charges' ).modal('show');
        });

        $( '#save-job-charges').click(function(){

            var rowSelected = $( '#tbl-job-charges' ).DataTable().column(0).checkboxes.selected();

            if (rowSelected.length == 0) {
                toastr["error"]('Debe seleccionar al menos 1 fila');
                return;
            }

            var ids = [];
            for (var i = 0; i < rowSelected.length; i++) {
                ids.push(rowSelected[i]);
            }

            var url = "<?php echo site_url('employer/users/permission_job_charges/add'); ?>";

            var data = {
                'ids': ids,
                'user_id': "<?php echo $app_user_info->ID; ?>"
            };
            $.post(url, data, function(res) {
                if (res.status) {
                    $( '#tbl-job-charges' ).DataTable().ajax.reload();
                    $( '#tbl-permission-job-charges' ).DataTable().ajax.reload();

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

        $(document).on('click', '.js-remove-job-charges', function(){
            
            var url = "<?php echo site_url('employer/users/permission_job_charges/delete'); ?>";

            var data = {
                'id': $(this).data('id')
            };
            $.post(url, data, function(res) {
                if (res.status) {
                    $( '#tbl-permission-job-charges' ).DataTable().ajax.reload();
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