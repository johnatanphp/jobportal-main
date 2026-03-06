<script type="text/javascript">
    $(document).ready(function() {

        $( '#tbl-companies' ).DataTable({
            "language": {
                "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
            },
            "destroy": true,
            "bAutoWidth": false,
            "deferRender": true,
            "iDisplayLength": 25,
            "bProcessing": true,
            ajax: {
                url: "<?php echo site_url('employer/users/permission_clients/list'); ?>",
                type: 'GET',
                data : {
                user_id: "<?php echo $app_user_info->ID; ?>"
                }
            },
            columns: [
            {
                data: null,
                render: function(row) {
                return `<a href="#" class="js-remove-client-company"
                                    data-id="${row.__id__}">
                            <label class="label label-danger">
                                <i class="material-icons" style="font-size:10px;">clear</i>
                            </label>
                        </a>`;
                }
            ,'className': 'style_td text-center'
            },    
            {data:'consultant_name', 'className': 'style_td text-left'},
            {data:'business_unit_name', 'className': 'style_td text-left'},
            {data:'client_company_name', 'className': 'style_td text-left'},
            {data:'cost_center', 'className': 'style_td text-left'}, 
            ]
        });

        function addClientComany() {

            var consultant = $( "#consultant" ).val();
            var businessUnit = $( "#business-unit" ).val();
            var clientCompany = $( "#client-company" ).val();
            var costCenter = $( "#cost-center" ).val();

            if (consultant == '' || 
                businessUnit == '' ||
                clientCompany == '' ||
                costCenter == ''
            ) {
            alert("¡Seleccione todos las opciones!")
            return;
            }
        
            var url = "<?php echo site_url('employer/users/permission_clients/add'); ?>";

            var data = {
                'user_id': "<?php echo $app_user_info->ID; ?>",
                'consultant': consultant,
                'client_company': clientCompany,
                'business_unit': businessUnit,
                'cost_center': costCenter
            };

            $.post(url, data, function(e){
            if (e.status) {
                $( '#tbl-companies' ).DataTable().ajax.reload();
                $( "#consultant" ).val("");
                $( "#business-unit" ).val("");
                $( "#client-company" ).val("");
                $( "#cost-center" ).val("");
                
                toastr["success"](e.message);

                return;
            }

            if (!e.status) {
                toastr["error"](e.message);
            }
            }, 'json');
        }

        function getConsultants() {
            var url = "<?php echo site_url('general/overall_web_services/get_consultants'); ?>";
            $( "#consultant" ).html('<option value="">Cargando...</option>').prop('disabled', true);

            $.post(url, {}, function(data) {
            
            var consultants =  data.MESSAGE == 'OK' ? data.CONSULTORA : [];      
            $.each(consultants, function(i, row) {
                var consultantValue = row.NO_CIA + '|' + row.CONSULTORA; 
                $( "#consultant" ).append(
                '<option data-no_cia="' + row.NO_CIA + '" value="' + consultantValue + '">' + row.CONSULTORA + '</option>'
                );
            });      
            }, 'json')
            .fail(function() {
            alert('¡Ha ocurrido un error al tratar de listar las consultoras!');
            }).always(function() {
            $( "#consultant" ).find("option:eq(0)").text("Seleccione");
            $( "#consultant" ).prop('disabled', false);
            }); 
        }

        function getClientsCompany()
        {
            var url = "<?php echo site_url('general/overall_web_services/get_clients_company'); ?>";
            var data = {
            no_cia: $( "#consultant option:selected" ).data('no_cia'),
            uni_neg: $( "#business-unit option:selected" ).data('uni_neg')
            }

            $( "#client-company" ).html('<option value="">Cargando...</option>').prop('disabled', true);
        
            $.post(url, data, function(data) {
            
                var clients =  data.MESSAGE == 'OK' ? data.CLIENTE : [];
                
                $.each(clients, function(i, row) {
                    var clientValue = row.COD_CLIE + '|' + row.CLIENTE; 
                    $( "#client-company" ).append(
                    '<option data-cod_clie="' + row.COD_CLIE + '" value="' + clientValue + '">' + row.CLIENTE + '</option>'
                    );
                });      
            }, 'json')
            .fail(function() {
                alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
            }).always(function() {
                $( "#client-company" ).find("option:eq(0)").text("Seleccione");
                $( "#client-company" ).prop('disabled', false);
            }); 
        }
        
        function getCostCenters()
        {
            var data  = {
                no_cia: $( "#consultant option:selected" ).data('no_cia'),
                uni_neg: $( "#business-unit option:selected" ).data('uni_neg'),
                cod_clie: $( "#client-company option:selected" ).data('cod_clie'),
            }

            var url = "<?php echo site_url('general/overall_web_services/get_cost_centers'); ?>";
            $( "#cost-center" ).html('<option value="">Cargando...</option>').prop('disabled', true); 

            $.post(url, data, function(data) {

            var cost_centers = data.MESSAGE == 'OK' ? data.CENTROCOSTO : [];

            $.each(cost_centers, function(i, row) {
                $( "#cost-center" ).append('<option value="' + row.COD_CCOSTO + '">' + row.COD_CCOSTO + '</option>');
            });      
            }, 'json')
            .fail(function() {
                alert('¡Ha ocurrido un error al tratar de listar los centro de costo!');
            }).always(function() {
                $( "#cost-center" ).find("option:eq(0)").text("Seleccione");
                $( "#cost-center" ).prop('disabled', false);
            });      
        }

        $( "#consultant" ).change(function() {
            $( "#business-unit" ).val("");
        });

        $( "#client-company" ).change(function() {
            getCostCenters()
        });

        $( "#business-unit" ).change(function() {
            getClientsCompany();
        });  

        $( "#add-company" ).click(function(){
            addClientComany();
        });

        $( document ).on('click', '.js-remove-client-company', function(){
            
            var url = "<?php echo site_url('employer/users/permission_clients/delete'); ?>";

            var data = {
            'id' : $(this).data('id')
            };
            $.post(url, data, function(res) {

            if (res.status) {
                $( '#tbl-companies' ).DataTable().ajax.reload();
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
        getConsultants();
    });
</script>