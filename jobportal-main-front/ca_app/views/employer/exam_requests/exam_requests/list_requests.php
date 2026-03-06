<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title>
            <?php echo $title; ?>        
        </title>
        <?php $this->load->view('common/before_head_close'); ?>
        <style type="text/css">
            .dropdown-menu li {
                margin: 0;
                padding: 1px;
                border: none;
            }
            
            #modal-request {
                padding: 10px !important;
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
                                            <a class="_link-back" style="color:#fff;" href="#">
                                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                            </a>
                                            Bandeja de programaciones
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div style="padding: 8px;">
                                <div class="sys-banner">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <div class="sys-content-list">
                                                <ul class="sys-list">
                                                    <li>
                                                        <b>RYS ID:</b> 
                                                        <?php echo $job->ID; ?>
                                                    </li>
                                                    <li>
                                                        <b>Empleo:</b>
                                                        <?php echo $job->job_title; ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xs-4" style="text-align: right;">
                                            <a style="display:none;" class="btn btn-xs btn-primary-dark open-create-request" 
                                               href="#"
                                            >
                                                Crear solicitud
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Solicitudes</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (!is_field_empty($filters)): ?>
                                            <a class="btn btn-xs btn-default pull-right" 
                                               style="margin-left: 5px;"
                                               href="<?php echo site_url('employer/exam_requests/requests/list/' . $exam_schedule->id); ?>">
                                                
                                                Mostrar todo
                                            </a>
                                        <?php endif; ?>
                                        
                                        <button class="btn btn-xs btn-primary-dark pull-right"
                                                data-toggle="modal" 
                                                data-target="#modal-filter">
                                            Filtrar
                                        </button>
                                    </div>
                                </div>

                                <br />
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th width="3"></th>
                                            <th width="3"></th>
                                            <th>Id</th>
                                            <th>Examen</th>
                                            <th width="30%">Centro médico</th>
                                            <th>Sede</th>
                                            <th align="center">Estado OC</th>
                                            <th align="center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($requests as $request): ?>
                                            <tr>
                                                <td>
                                                    <div class="dropdown dropdown-options-job" style="display: inline-block;">
                                                        <button style="border:0; background: transparent;padding: 0;"class="dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <span class="glyphicon glyphicon-option-vertical"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-left">
                                                            <li>
                                                                <a href="<?php echo site_url('employer/exam_requests/requests/export/' . $request->request_id); ?>">
                                                                    Exportar
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="checkbox" 
                                                           class="check-exam-requests" 
                                                           value="<?php echo $request->request_id; ?>"
                                                           style="margin-left: 0px;display: none;">
                                                </td>
                                                <td>
                                                    <?php 
                                                        $color_icon_notification = $request->candidates_to_notify == 0 ? '#ffb645' : '#bbbbbb';
                                                    ?>
                                                    <i class="glyphicon glyphicon-bell" style="color: <?php echo $color_icon_notification; ?>;"></i>
                                                </td>
                                                <td>
                                                    <a href="<?php echo site_url('employer/exam_requests/requests/show/' . $request->request_id ); ?>">
                                                        <?php echo $request->request_id; ?>        
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo $request->document_name; ?> 
                                                    <br />
                                                    <span style="font-size: 12px; color: #555;">
                                                        <?php echo 'Creada ' . date('d/m/Y H:i', strtotime($request->creation_date)); ?>        
                                                    </span>
                                                </td>
                                                <td>
                                                   <?php echo $request->medical_center_name; ?>  
                                                </td>
                                                <td>
                                                   <?php echo $request->medical_center_location ? $request->medical_center_location : '-'; ?>  
                                                </td>

                                                <td align="center">
                                                    <?php if ($request->oc_status == 1): ?>
                                                        <span class="label label-warning">
                                                            SIN SOLICITAR
                                                        </span>
                                                    <?php elseif ($request->oc_status == 2): ?>
                                                        <span class="label label-primary">
                                                            SOLICITADA
                                                        </span>
                                                    <?php elseif ($request->oc_status == 3): ?>
                                                        <span class="label label-success">
                                                            CREADA
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td align="center">

                                                    <?php if ($request->status == 1): ?>
                                                        <span class="label label-warning">
                                                           SIN ENVIAR
                                                        </span>
                                                    <?php elseif ($request->status == 2): ?>
                                                        <span class="label label-success">
                                                            ENVIADA
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (empty($requests)): ?>
                                            <tr>
                                                <td colspan="10">
                                                    <div align="center" class="text-red" style="padding: 25px 5px;">
                                                        Sin resultados encontrados.
                                                    </div>   
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

        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <!-- Profile Popups -->
        <?php $this->load->view('employer/common/employers_popup_forms'); ?>
        <?php $this->load->view('common/before_body_close'); ?>

        <div id="modal-request" class="modal fade" role="dialog"></div>
        <div id="modal-selected-medical-center" class="modal fade" role="dialog"></div>

        <!-- Modal -->
        <div id="modal-confirm-send-request" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmar envios</h4>
                    </div>
                    <div class="modal-body">
                        ¡Se enviaran todas las solicitudes seleccionadas sin enviar a los centros médicos!
                        <br />
                        <br />
                        ¿Está seguro de enviar las solicitudes?
                    </div>
                    <div class="modal-footer">
                        <button id="do-send-request" type="button" class="btn btn-primary">
                            Si
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="modal-confirm-resend-request" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmar reenvios</h4>
                    </div>
                    <div class="modal-body">
                        ¡Se enviaran todas las solicitudes seleccionadas de nuevo a los centros médicos!
                        <br />
                        Nota: ¡Las solicitudes sin enviar se ignorarán!
                        <br />
                        <br />
                        ¿Está seguro de reenviar las solicitudes?
                    </div>
                    <div class="modal-footer">
                        <button id="do-resend-request" type="button" class="btn btn-primary">
                            Si
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal filter-->
        <div id="modal-filter" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <?php echo form_open('', ['method' => 'get']); ?>
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Filtrar</h4>
                        </div>

                        <div class="modal-body">
                            <div class="panel-filter"> 
                                <div class="filter-title">
                                    <h4>Por Tipo Examen</h4>
                                </div>
                                <select name="exam_type_id" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="1" 
                                            <?php echo 1 == $filters['exam_type_id'] ? 'selected="selected"' : ''; ?>
                                    >
                                        EMO
                                    </option>
                                    <option value="2" 
                                            <?php echo 2 == $filters['exam_type_id'] ? 'selected="selected"' : ''; ?>>
                                        COVID-19
                                    </option>
                                    <option value="3" <?php echo 3 == $filters['exam_type_id'] ? 'selected="selected"' : ''; ?>>
                                        Screening
                                    </option>
                                </select>
                            </div>
                            <div class="panel-filter"> 
                                <div class="filter-title">
                                    <h4>Centro médico</h4>
                                </div>
                                <select name="medical_center" class="form-control">
                                    <option value="">Todos</option>
                                    <?php foreach($medical_centers as $medical_center): ?>
                                        <option value="<?php echo $medical_center->code; ?>"
                                                <?php echo $medical_center->code == $filters['medical_center'] ? 'selected="selected"' : ''; ?>>
                                            <?php echo $medical_center->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="panel-filter"> 
                                <div class="filter-title">
                                    <h4>Estado</h4>
                                </div>
                                <select name="status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="1" 
                                            <?php echo 1 == $filters['status'] ? 'selected="selected"' : ''; ?>>
                                        SIN ENVIAR
                                    </option> 
                                    <option value="2"
                                            <?php echo 2 == $filters['status'] ? 'selected="selected"' : ''; ?>>
                                        ENVIADO
                                    </option> 
                                </select>
                            </div>
                            <div class="panel-filter"> 
                                <div class="filter-title">
                                    <h4>Estado OC</h4>
                                </div>
                                <select name="status_oc" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="1"
                                            <?php echo 1 == $filters['status_oc'] ? 'selected="selected"' : ''; ?>>
                                        SIN SOLICITAR
                                    </option> 
                                    <option value="2"
                                            <?php echo 2 == $filters['status_oc'] ? 'selected="selected"' : ''; ?>>
                                        SOLICITADA
                                    </option>
                                    <!--
                                    <option value="3" 
                                            <?php echo 3 == $filters['status_oc'] ? 'selected="selected"' : ''; ?>>
                                        CREADA
                                    </option> 
                                    --> 
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" >Filtrar</button>
                            <input style="display: none;"id="form-filter-reset" type="reset" class="btn btn-default" value="Limpiar"/>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <script type="text/javascript">
            $(function(){

                var scheduleId = "<?php echo $exam_schedule->id; ?>";
                $( ".open-create-request" ).click(function(){
                    var url = "<?php echo site_url('employer/exam_requests/requests/create/'); ?>" + scheduleId;
                    $( "#modal-request" ).load(url, {}, function(response){
                        $(this).html(response).modal('show');
                    });
                });

                $( "#send-request" ).click(function(){

                    if ($( ".check-exam-requests:checked" ).length == 0) {
                        toastr["error"]("¡Debe seleccionar al menos 1 solicitud!");
                        return;
                    }

                    $( "#modal-confirm-send-request" ).modal('show');
                });

                $( "#do-send-request" ).click(function(){

                    var url = "<?php echo site_url('employer/exam_requests/requests/send'); ?>";
                    
                    var btnSend = $(this);

                    btnSend.text("Enviando...")
                           .prop('disabled', true);

                    var examRequestIds = [];

                    $( ".check-exam-requests:checked" ).each(function(i, element){
                        examRequestIds.push($(element).val());
                    });

                    var data = {
                        'exam_requests': examRequestIds
                    }; 

                    $.post(url, data, function(response) {

                        if (!response.success) {
                            toastr["error"]("¡Error al enviar las solicitudes!");
                            btnSend.text("Enviar solicitud")
                                   .prop('disabled', false);
                            return;
                        }

                        toastr["success"]("¡Solicitudes enviadas!");
                        btnSend.remove();
                        window.location.reload();
                    }, 'json')
                    .fail(function(){

                        toastr["error"]("¡Error al realizar la transacción!");
                        btnSend.text("Si")
                               .prop('disabled', false);
                    });
                });

                $( "#resend-request" ).click(function(){

                    if ($( ".check-exam-requests:checked" ).length == 0) {
                        toastr["error"]("¡Debe seleccionar al menos 1 solicitud!");
                        return;
                    }

                    $( "#modal-confirm-resend-request" ).modal('show');
                });

                $( "#do-resend-request" ).click(function(){

                    var url = "<?php echo site_url('employer/exam_requests/requests/send'); ?>";
                    
                    var btnSend = $(this);

                    btnSend.text("Enviando...")
                           .prop('disabled', true);

                    var examRequestIds = [];

                    $( ".check-exam-requests:checked" ).each(function(i, element){
                        examRequestIds.push($(element).val());
                    });

                    var data = {
                        'exam_requests': examRequestIds
                    }; 

                    $.post(url, data, function(response) {

                        if (!response.success) {
                            toastr["error"]("¡Error al reenviar las solicitudes!");
                            btnSend.text("Enviar solicitud")
                                   .prop('disabled', false);
                            return;
                        }

                        toastr["success"]("¡Solicitudes reenviadas!");
                        btnSend.remove();
                        window.location.reload();
                    }, 'json')
                    .fail(function(){

                        toastr["error"]("¡Error al realizar la transacción!");
                        btnSend.text("Si")
                               .prop('disabled', false);
                    });
                });

                $( "#form-filter-reset" ).click(function(){
                    $('select').val("");
                })
            });
        </script>

        <script type="text/javascript">
            $(function(){
                <?php if ($this->input->get('action') == 'create_request'): ?>
                    $( ".open-create-request" ).click();
                <?php endif; ?>
            }); 
        </script>
    </body>
</html>