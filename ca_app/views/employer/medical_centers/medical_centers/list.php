<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title>
            <?php echo $title; ?>        
        </title>
        <?php $this->load->view('common/before_head_close'); ?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

        <style type="text/css">
            .dropdown-menu li {
                margin: 0;
                padding: 1px;
                border: none;
            }
            
            #modal-request {
                padding: 10px !important;
            }

            #content-medical-centers .dataTables_length {
                display: none;
            }

            #tbl-medical-centers tr td  a {
                margin-left: 12px;
            }
        </style>
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
                        <?php echo $this->session->flashdata('msg'); ?>
                        <!--Job Application-->
                        <div class="formwraper">
                            <div class="titlehead">
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>
                                            Centros médicos
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div id="content-medical-centers" style="padding: 8px;">
                                <br />
                                <table id="tbl-medical-centers" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th width="400" >Nombre</th>
                                            <th align="center">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Load modal -->

        <?php $this->load->view('employer/medical_centers/locations/modal/list_locations'); ?>
        <?php $this->load->view('employer/medical_centers/locations/modal/add_location'); ?>
        <?php $this->load->view('employer/medical_centers/locations/modal/edit_location'); ?>
        <?php $this->load->view('employer/medical_centers/medical_centers/modal/list_emails'); ?>
       
        <!-- End load modal -->
        
        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <?php $this->load->view('common/before_body_close'); ?>
        
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
                
        <script type="text/javascript">

            function searchMedicalCenters() {
                
                var dtSearchCandidate = $( '#tbl-medical-centers' ).DataTable({
                    "language": {
                        "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                    },
                    "destroy": true,
                    "bAutoWidth": false,
                    "deferRender": true,
                    "iDisplayLength": 25,
                    "bProcessing": true,
                    ajax: {
                        url: "<?php echo site_url('employer/medical_centers/medical_centers'); ?>",
                        type: 'GET'
                    },
                    columns: [
                        {data:'medical_center_code', 'className': 'style_td text-left'},
                        {data:'medical_center_name', 'className': 'style_td text-left'},
                        {data: null, render:function(data){
                            return `
                                <a herf="#"
                                   class="show-locations" 
                                   data-mc-code="${data.medical_center_code}" 
                                   data-mc-name="${data.medical_center_name}"
                                   style="cursor:pointer;">
                                    Sedes
                                </a>
                                <a href="#" 
                                   class="show-emails"
                                   data-mc-code="${data.medical_center_code}"
                                   data-mc-name="${data.medical_center_name}"
                                   style="cursor:pointer;">
                                    Notificaciones
                                </a>
                            `;
                        }, 'className': 'style_td text-left'},      
                    ]
                });
            }

            function searchLocations(mcCode) {
    
                var dtSearchCandidate = $( '#tbl-medical-center-locations' ).DataTable({
                    "language": {
                        "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                    },
                    "destroy": true,
                    "bAutoWidth": false,
                    "deferRender": true,
                    "iDisplayLength": 25,
                    "bProcessing": true,
                    ajax: {
                        url: "<?php echo site_url('employer/medical_centers/locations'); ?>",
                        type: 'GET',
                        data : {
                            mc_code: mcCode
                        }
                    },
                    columns: [
                        {data:'id', 'className': 'style_td text-left'},
                        {data:'location', 'className': 'style_td text-left'},
                        {data:'ubication', 'className': 'style_td text-left'},
                        {data:'direction', 'className': 'style_td text-left'},
                        {data: null, render: function(data){
                            return data.active == 1 ? 'ACTIVO' : 'INACTIVO';   
                        },
                        'className': 'style_td text-left'},
                        {data: null, render:function(data){
                            return `
                                <a herf="#"
                                   class="edit-location" 
                                   data-location-id="${data.id}" 
                                   data-location-name="${data.location}" 
                                   data-location-city="${data.ubication}"
                                   data-location-address="${data.direction}"
                                   data-location-status="${data.active}"
                                   style="cursor:pointer;">
                                    Editar
                                </a>
                            `;
                        }, 'className': 'style_td text-left'},            
                    ]
                });
            }

            function searchNotificationsEmails(mcCode) {

                var url = "<?php echo site_url('employer/medical_centers/notification_emails/get_emails'); ?>";
                var data = {
                    mc_code: mcCode
                };

                $.get(url, data, function(response) {
                    $( "#modal-medical-center-list-emails .modal-body" ).html(response);
                })
                .fail(function (){
                    toastr["error"]('¡Ha ocurrido un error!');
                });
            }

            $(document).on("click", ".show-locations", function(){
                $("#modal-medical-center-locations" ).modal('show'); 

                $( ".mc-code" ).val($(this).data('mc-code'));
                $( ".mc-name" ).text($(this).data('mc-name'));

                searchLocations($(this).data('mc-code'));
            });

            $(document).on("click", ".edit-location", function(){
                $("#form-mc-edit-location input[name='id']").val($(this).data('location-id'));
                $("#form-mc-edit-location input[name='name']").val($(this).data('location-name'));
                $("#form-mc-edit-location input[name='city']").val($(this).data('location-city'));
                $("#form-mc-edit-location textarea[name='address']").val($(this).data('location-address'));
                $("#form-mc-edit-location select[name='status']").val($(this).data('location-status'));

                $( "#modal-medical-center-edit-locations" ).modal('show');
            });

            $( "#mc-add-location" ).click(function(){
                $( "#modal-medical-center-add-locations" ).modal('show');
            });

            $( "#form-mc-add-location" ).submit(function(e){
                e.preventDefault();
                var url = $(this).prop('action');
                var data = $(this).serialize();

                $.post(url, data, function(response) {

                    if (!response.success) {
                        toastr["error"](response.error);
                        return;
                    }

                    toastr["success"]("¡Sede Agregada!");
                    $( "#modal-medical-center-add-locations" ).modal('hide');
                    ($( "#modal-medical-center-add-locations form" )[0]).reset();
                    $( "#tbl-medical-center-locations" ).DataTable().ajax.reload();
             
                }, 'json')
                .fail(function (){
                    toastr["error"]('¡Ha ocurrido un error al agregar la sede!');
                });

                return false;
            });

            $( "#form-mc-edit-location" ).submit(function(e){
                e.preventDefault();
                var url = $(this).prop('action');
                var data = $(this).serialize();

                $.post(url, data, function(response) {

                    if (!response.success) {
                        toastr["error"](response.error);
                        return;
                    }

                    toastr["success"]("¡Sede actualizada!");
                    $( "#modal-medical-center-edit-locations" ).modal('hide');
                    $( "#tbl-medical-center-locations" ).DataTable().ajax.reload();
             
                }, 'json')
                .fail(function (){
                    toastr["error"]('¡Ha ocurrido un error al actualizar la sede!');
                });

                return false;
            });

            $( document ).on("click", ".show-emails", function(){
                $( ".mc-name" ).text($(this).data('mc-name'));
                $( "#modal-medical-center-list-emails" ).modal("show");

                searchNotificationsEmails($(this).data('mc-code'));
            });

            searchMedicalCenters();

        </script>
    </body>
</html>