<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

        <style type="text/css">
            .list-options {
                list-style: none;
                padding-bottom: 3px;
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
                    <?php $this->load->view('employer/common/menu/sidebar');?>
                </div>
            </div>
            <div class="col-md-9"> 
                <?php echo $this->session->flashdata('msg'); ?>
                <div class="formwraper">
                    <div class="titlehead">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="_link-back" style="color:#fff;" href="#">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                Grupos RRHH
                            </div>
                        </div>
                    </div>
                    <div class="formint">
                        <div class="row">
                            <div class="col-md-12" style="text-align: right;">
                                <button id="btn-create-group" class="btn btn-xs btn-primary">Nuevo</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table width="100%" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            Id
                                        </th>
                                        <th>
                                            Grupo
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($groups as $row): ?>  
                                    <tr>
                                        <td>
                                           <?php echo $row->id; ?> 
                                        </td>
                                        <td>
                                            <?php echo $row->name . ' ( ' . $row->count_users . ' )'; ?>
                                        </td>
                                        <td>
                                            <a href="#" 
                                               class="group-edit" 
                                               data-id="<?php echo $row->id; ?>"
                                               data-name="<?php echo $row->name; ?>">Editar</a>
                                            &nbsp;
                                            &nbsp;
                                            <a href="#" class="group-users" data-id="<?php echo $row->id; ?>">Usuarios</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>


                                <?php if (count($groups) == 0): ?>
                                    <tr>
                                        <td colspan="3" align="center">
                                            Sin registros
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('employer/recruitment/rrhh_groups/partials/modal_add_group'); ?>
    <?php $this->load->view('employer/recruitment/rrhh_groups/partials/modal_edit_group'); ?>
    <?php $this->load->view('employer/recruitment/rrhh_groups/partials/modal_manage_users'); ?>

    <?php $this->load->view('common/bottom_ads'); ?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <!-- Profile Popups -->
    <?php $this->load->view('common/before_body_close'); ?>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            
            $( '#btn-create-group' ).click(function() {
                $( '#modal-add-group' ).modal('show');
            });

            $( '#form-create-group' ).submit(function(e){
                e.preventDefault();

                var form = $(this);
                var url = $(this).prop('action');
                var data = $(this).serialize();

                $.post(url, data, function(response) {

                    if (!response.success) {
                        toastr["error"]("¡No se ha podio crear el grupo!");
                        return;
                    }

                    toastr["success"]("¡Grupo creado con éxito!");
                    form[0].reset();

                    setTimeout(function(){
                        window.location.reload();
                    }, 1300);
                }, 'json')
                .fail(function(e) {
                    alert("¡Ha ocurrido un error!");
                });
                
                return false;
            });

            $( '.group-edit' ).click(function() {    
                $( '#group-edit-id' ).val($(this).data('id'));
                $( '#group-edit-name' ).val($(this).data('name'));
                
                $( '#modal-edit-group' ).modal('show');
            });

            $( '#form-edit-group' ).submit(function(e){
                e.preventDefault();

                var form = $(this);
                var url = $(this).prop('action');
                var data = $(this).serialize();

                $.post(url, data, function(response) {

                    if (!response.success) {
                        toastr["error"]("¡No se ha podio editar el grupo!");
                        return;
                    }

                    toastr["success"]("¡Grupo actualizado con éxito!");
                    form[0].reset();

                    setTimeout(function(){
                        window.location.reload();
                    }, 1300);
                    
                }, 'json')
                .fail(function(e) {
                    alert("¡Ha ocurrido un error!");
                });
                
                return false;
            });

            $( '.group-users' ).click(function() {    
                $( '#group-user-id' ).val($(this).data('id'));
                
                searchGroupUsers($(this).data('id'));

                $( '#modal-group-users' ).modal('show');
            });

            $( '#form-add-user-group' ).submit(function(e){
                e.preventDefault();

                var form = $(this);
                var url = $(this).prop('action');
                var data = $(this).serialize();

                $.post(url, data, function(response) {

                    if (!response.success) {
                        toastr["error"](response.message);
                        return;
                    }

                    toastr["success"](response.message);
                    form[0].reset();
                    $( '#tbl-group-users' ).DataTable().ajax.reload();            
                }, 'json')
                .fail(function(e) {
                    alert("¡Ha ocurrido un error!");
                });
                
                return false;
            });

            function searchGroupUsers(groupId) {
                var query = $.trim(query);

                var dtSearchCandidate = $( '#tbl-group-users' ).DataTable({
                    "language": {
                        "url": "<?php echo base_url('public/js/lib/dataTable/lang/spanish.json'); ?>"
                    },
                    "destroy": true,
                    "bAutoWidth": false,
                    "deferRender": true,
                    "iDisplayLength": 25,
                    "bProcessing": true,
                    ajax: {
                        url: "<?php echo site_url('employer/recruitment/rrhh_groups/get_users'); ?>",
                        type: 'GET',
                        data : {
                            group_id: groupId
                        }
                    },
                    columns: [
                        {
                            data: null,
                            render: function(row) {
                                return `
                                    <button class="btn btn-xs btn-danger btn-remove-user"
                                            data-id="${row.id}">
                                        Quitar
                                    </button>
                                `;
                            }
                        ,'className': 'style_td text-center'
                        },
                        {data:'first_name', 'className': 'style_td text-left'},
                        {data:'email', 'className': 'style_td text-left'},       
                    ]
                });
            }

            $(document).on('click', '.btn-remove-user', function() {    
                var url = "<?php echo site_url('employer/recruitment/rrhh_groups/remove_user'); ?>";
                var data = {
                    'id' : $(this).data('id')
                };

                $.post(url, data, function(response) {
                    if (!response.success) {
                        toastr["error"](response.message);
                        return;
                    }

                    $( '#tbl-group-users' ).DataTable().ajax.reload();
                         
                }, 'json')
                .fail(function(e) {
                    alert("¡Ha ocurrido un error!");
                });
            });
        });
    </script>
</body>
</html>