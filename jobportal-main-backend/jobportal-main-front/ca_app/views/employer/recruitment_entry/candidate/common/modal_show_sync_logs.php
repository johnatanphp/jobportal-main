<div id="modal-show-sync-logs" class="modal" role="dialog">
    <div class="modal-dialog" style="width: 60%; min-width:400px;">
        <!-- Modal content-->
        <div class="modal-content"  >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sincronización Sistema Nómina</h4>
            </div>
            <div class="modal-body">                        
                <div class="table-responsive">
                    <table id="tbl-list-sync-logs" class="table">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Fecha envio</th>
                                <th>Estado</th>
                                <th>Log</th>
                            </tr>
                        </thead>
                        <tbody></tbody>             
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $( '.show-sync-logs' ).click(function(){
            $( '#modal-show-sync-logs' ).modal('show');

            $( '#tbl-list-sync-logs' ).DataTable({
                "language": {
                    "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                },
                "destroy": true,
                "bAutoWidth": false,
                "deferRender": true,
                "iDisplayLength": 10,
                "bProcessing": true,
                "ordering": false,
                "paging": false,
                ajax: {
                    url: "<?php echo site_url('employer/recruitment_entry/recruitment_candidates/get_sync_logs'); ?>",
                    type: 'GET',
                    data: {
                        process_id: "<?php echo $process->id; ?>",
                        seeker_id: "<?php echo $candidate->ID; ?>"
                    }
                },
                columns: [
                    {
                        data: null, render: function(row) {
                            return $.trim(row.sync_description) != '' ? row.sync_name + ' - ' + $.trim(row.sync_description) : row.sync_name;
                        }
                        ,'className': 'style_td text-left'
                    },
                    {data:'sync_created_at', 'className': 'style_td text-left'},
                    {
                        data: null, render: function(row) {
                            return row.sync_success == '1' ? 'OK' : 'Fallido';
                        }
                        ,'className': 'style_td text-center'
                    },
                    {
                        data: null, render: function(row) {
                            return `<a href="#" 
                                       data-id="${row.sync_id}"
                                       class="show-modal-sync-log-detail">
                                        Ver
                                    </a>`;
                        }
                        ,'className': 'style_td text-center'
                    },
                ]
            });
        });

        $(document).on('click', '.show-modal-sync-log-detail', function(){
            if ($( '#modal-show-sync-logs-detail' ).length > 0) {
                $( '#modal-show-sync-logs-detail' ).remove();
            }

            $( `<div id="modal-show-sync-logs-detail" class="modal"></div>`).appendTo($('body'));

            const url = "<?php echo site_url('employer/recruitment_entry/recruitment_candidates/get_sync_log_detail'); ?>";
            const data = {
                'id' : $(this).data('id')
            };
            $.get(url, data, function(res){
                $( '#modal-show-sync-logs-detail' ).html(res);

                try {
                    $( '#sync-parameters pre' ).html(JSON.stringify(JSON.parse($( '#sync-parameters pre' ).html()), null, 2));
                } catch (e) {

                }
                
                try {
                    $( '#sync-response pre' ).html(JSON.stringify(JSON.parse($( '#sync-response pre' ).html()), null, 2));
                } catch (e) {
                    
                }
                
                $( '#modal-show-sync-logs-detail' ).modal('show');
            });
        });
    </script>
</div>