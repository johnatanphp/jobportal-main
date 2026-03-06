<?php
    $exam_types = [$document->id];

    if ($document->id == 4) {
        $exam_types = [2, 1];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>

        <style type="text/css">
            
            .list-options {
                list-style: none;
                padding-bottom: 3px;
            }

            #modal-request {
                padding: 10px !important;
            }

            .content-table {
                overflow-y: hidden;
            }

            .content-table table {
                max-width: none;
                width: 1500px;
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
                                Detalle de solicitud
                            </div>
                        </div>
                    </div>
                    <div style="padding: 10px;">
                        <div class="sys-banner">
                            <div class="row">
                                <div class="col-xs-8">
                                    <div class="sys-content-list">
                                        <ul class="sys-list">
                                            <li>
                                                <b>Solicitud Id:</b>
                                                <?php echo $request->request_id; ?>
                                            </li>
                                            <li>
                                                <b>RYS Id:</b> 
                                                <?php echo $job->ID; ?>
                                            </li>
                                            <li>
                                                <b>Empleo:</b>
                                                <?php echo $job->job_title; ?>
                                            </li>
                                            <li>
                                                <b>Creado por:</b> <?php echo $user->first_name . ' ' . $user->last_name; ?>
                                            </li>
                                            <li>
                                                <b>Fecha de creación:</b>
                                                <?php echo $request->creation_date; ?>
                                            </li>
                                            <li>
                                                <b>Documento:</b>
                                                <?php echo $document->name; ?>
                                            </li>
                                            
                                            <?php if ($request->medical_center_code != null): ?>
                                                 <li>
                                                    <b>Solicitud a:</b>
                                                    <?php echo $medical_center->name; ?>
                                                </li>

                                                <li>
                                                    <b>Sede:</b> <?php echo $medical_center_location ? $medical_center_location->location : '-'; ?>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <b>Estado OC:</b>
                                                <?php if ($request->oc_status == 1): ?>
                                                    SIN SOLICITAR
                                                <?php elseif ($request->oc_status == 2): ?>
                                                    SOLICITADO
                                                <?php endif; ?>

                                                <?php
                                                    $array_oc_ids = [];
                                                    
                                                    foreach ($request_oc_ids as $row) {
                                                        $array_oc_ids[] = $row->oc_id;
                                                    }
                                                    echo count($array_oc_ids) > 0 ? ' - ' . join(', ', $array_oc_ids) : '';
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-xs-4" style="text-align: right;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table width="100%">
                                                <tr>
                                                    <td>
                                                        <span class="btn-status">
                                                            <?php if ($request->status == 1): ?>
                                                                Estado: SIN ENVIAR
                                                            <?php elseif ($request->status == 2): ?>
                                                                Estado: ENVIADO
                                                            <?php endif; ?>                            
                                                        </span>
                                                    </td>
                                                    <td>
                                                       <div class="dropdown dropdown-options-job">
                                                            <button class="btn btn-sm dropdown-toggle" 
                                                                    style="border:none;text-decoration: underline;background: transparent; font-weight: bold;" 
                                                                    type="button" 
                                                                    data-toggle="dropdown">
                                                                <i class="glyphicon glyphicon-option-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                <?php if ($request->oc_status == 1): ?>
                                                                    <li style="display:none;" >
                                                                        <a class="edit-request" 
                                                                           href="#"
                                                                           data-request-id="<?php echo $request->request_id;?>">
                                                                            Editar
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>

                                                                <?php if ($request->status == 2): ?>
                                                                    <li>
                                                                        <a href="#"
                                                                           class="send-exam-request">
                                                                            Reenviar solicitud
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>    

                                                                <?php if ($request->oc_status == 1): ?>
                                                                    <li>
                                                                        <a href="<?php echo site_url('employer/exam_requests/requests/create_oc/' . $request->request_id); ?>">
                                                                            Solicitar OC
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>

                                                                <li>
                                                                    <a href="<?php echo site_url('employer/exam_requests/requests/export/' . $request->request_id); ?>">
                                                                        Exportar solicitud
                                                                    </a>
                                                                </li>                    
                                                            </ul>
                                                        </div>                                      
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <br />
                                        <div class="col-md-12">
                                            <?php if ($request->status == 1): ?>
                                                <button class="btn btn-xs btn-primary send-exam-request">
                                                    Enviar solicitud
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
             
                        <br />
                       <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>
                                        Postulantes (<?php echo count($request_seekers); ?>)
                                    </h4>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($request->status == 2): ?>
                                    
                                        <div class="pull-right dropdown" style="margin-left: 5px;display: inline-block;">
                                            <button class="btn btn-xs btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                                Notificar
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="#"
                                                       class="notify-candidate"
                                                       data-notification-type="1">
                                                        Cita
                                                    </a>
                                                    <a href="#"
                                                       class="notify-candidate"
                                                       data-notification-type="2">
                                                        Reprogramación
                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                        <button id="reschedule-seekers"
                                                class="btn btn-xs btn-primary-dark pull-right">
                                            Reprogramar
                                        </button>
                                        <button id="select-time"
                                                class="btn btn-xs btn-primary-dark pull-right"
                                                style="margin-right: 5px;">
                                            Asignar hora
                                        </button> 
                                    <?php endif; ?>
                                </div>
                            </div>
                            <br />
                            <div class="content-table">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"></th>
                                            <th></th>
                                            <th align="left" width="150">Doc</th>
                                            <th align="left">Nombre</th>
                                            <th align="left" width="80">Fecha</th>
                                            <th align="left" width="80">Hora</th>
                                            <th>Estado</th>

                                            <?php foreach ($exam_types as $row_exam_type_id): ?>
                                                <?php if ($row_exam_type_id == 1): ?>
                                                    <th width="20%">
                                                        Tipo EMO
                                                    </th>
                                                    <th width="20%">
                                                        Protocolo Extra
                                                    </th>  
                                                <?php endif; ?>

                                                <?php if ($row_exam_type_id == 2): ?>
                                                    <th>Exam. Tipo</th>
                                                <?php endif; ?>

                                                <?php if ($row_exam_type_id == 3): ?>
                                                    <th>Screening</th>
                                                <?php endif; ?>

                                            <?php endforeach; ?>
                                            <th>
                                                Archivo OC
                                            </th>
                                            <th>
                                                Archivo Alta
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($request_seekers as $seeker_index => $seeker): ?>
                                            <tr>
                                                <td align="center">
                                                    <?php if ($seeker->active == 1 && 
                                                            $request->status == 2 && 
                                                            ($seeker->status == 3 || $seeker->status == 4)): ?>
                                                        <input type="checkbox" 
                                                            name="seekers[<?php echo $seeker_index; ?>][]" 
                                                            class="check-seeker" 
                                                            value="<?php echo $seeker->id; ?>">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $color_icon_notification = $seeker->candidate_notified ? '#ffb645' : '#bbbbbb';
                                                    ?>
                                                    <i class="glyphicon glyphicon-bell" style="color: <?php echo $color_icon_notification; ?>;"></i>
                                                </td>
                                                <td align="left">
                                                    <?php echo document_type_abbr($seeker->document_type) . ' ' . $seeker->document_number; ?>
                                                </td>
                                                
                                                <td align="left">
                                                    <?php echo $seeker->first_name . ' ' . $seeker->last_name; ?>
                                                </td>
                                                <td>
                                                    <?php echo date('d/m/Y', strtotime($seeker->exam_date)); ?>
                                                </td>
                                                <td>
                                                    <?php echo $seeker->exam_time ? date('h:i A', strtotime($seeker->exam_time)) : '-'; ?>
                                                </td>
                                                <td>
                                                    <?php if ($seeker->status == 1): ?> 
                                                        <span class="label label-primary">Programada RyS</span>
                                                    <?php elseif ($seeker->status == 2): ?>     
                                                        <span class="label label-primary">Asignada SSO</span>
                                                    <?php elseif ($seeker->status == 3): ?>     
                                                        <span class="label label-primary">Enviada</span>
                                                    <?php elseif ($seeker->status == 4): ?>  
                                                        <span class="label label-primary">Reprogramada SSO</span>
                                                    <?php elseif ($seeker->status == 5): ?>     
                                                        <span class="label label-success">Realizada</span>
                                                    <?php elseif ($seeker->status == 6): ?>     
                                                        <span class="label label-danger">No asistió</span>
                                                    <?php endif; ?>
                                                </td>

                                                <?php foreach ($exam_types as $row_exam_type_id): ?>
                                                    <?php 
                                                        $row_exam_type = $this->Exam_request_seeker_exam->get_exam_type($seeker->id, $row_exam_type_id);                                                        
                                                        $exam_doc_type_value = $row_exam_type ? $row_exam_type->exam_doc_type : null;

                                                        if ($row_exam_type_id == 1) { //EMO
                                                            echo "<td>" . ($exam_doc_type_value ? $exam_doc_type_value : '-') . "</td>";
                                                            echo "<td>" . ($row_exam_type->protocol_extra) . "</td>";
                                                        }

                                                        if ($row_exam_type_id == 2) { //COVID-19
                                                            $list_type = get_options_exam_type_covid();
                                                            $exam_doc_type_value = $exam_doc_type_value ? explode(',', $exam_doc_type_value) : [];
                                                            echo "<td>";
                                                            foreach ($exam_doc_type_value as $val) {
                                                                echo isset($list_type[$val]) ? $list_type[$val] . '<br />' : '-';
                                                            }
                                                            echo "</td>";
                                                        }

                                                        if ($row_exam_type_id == 3) {//Screening
                                                            echo "<td>" . ($exam_doc_type_value ? $exam_doc_type_value : '-') . "</td>";
                                                        }
                                                    ?>
                                                <?php endforeach; ?>
                                                <td>
                                                    <?php if ($seeker->file_oc_path): ?>
                                                        <a href="<?php echo file_url($seeker->file_oc_path); ?>"
                                                        target="_blank">
                                                            <i class="fa fa-file-o" aria-hidden="true"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($seeker->file_high_path): ?>
                                                        <a href="<?php echo file_url($seeker->file_high_path); ?>"
                                                        target="_blank">
                                                            <i class="fa fa-file-o" aria-hidden="true"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-confirm-notify-seekers" class="modal fade" role="dialog"></div>

    <div id="modal-reschedule" class="modal fade" role="dialog">
        <!-- Modals -->
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Reprogramar citas
                    </h4>
                </div>
                <div>
                    <form id="form-exam-reschedule" action="<?php echo site_url('employer/scheduled_exams/save_schedule'); ?>">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Fecha examen</label>
                                    <input id="schedule-date" 
                                           type="date" 
                                           name="exam_date" 
                                           class="form-control" 
                                           required="true"
                                           min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="text-align: center;">
                                        <input id="assing-date" type="submit" class="btn btn-xs btn-primary" value="Aceptar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-select-time" class="modal fade" role="dialog">
        <!-- Modals -->
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        Seleccionar hora
                    </h4>
                </div>
                <div id="rs-search-candidates">
                    <!--  -->
                    <form id="form-exam-save-time" action="<?php echo site_url('employer/exam_requests/requests/load_times'); ?>">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="tbl-input-time" align="center">
                                        <tr>
                                            <th>Hora</th>
                                            <th>Minutos</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select id="hours" class="form-control">
                                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                                        <option value="<?php printf('%02d', $i); ?>">
                                                            <?php printf('%02d', $i); ?>
                                                        </option>       
                                                    <?php endfor; ?>
                                                </select>
                                           </td>
                                           <td>
                                            <select id="minutes" class="form-control">
                                                <?php for ($i = 0; $i <= 55; ($i = $i + 5)): ?>
                                                    <option value="<?php printf('%02d', $i); ?>">
                                                        <?php printf('%02d', $i); ?>
                                                    </option>       
                                                <?php endfor; ?>
                                            </select>
                                               
                                           </td>
                                            <td>
                                                <select id="time-type" class="form-control">
                                                    <option value="AM">AM</option>
                                                    <option value="PM">PM</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="text-align: center;">
                                        <input id="assing-time" type="submit" class="btn btn-xs btn-primary" value="Aceptar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <!-- Profile Popups -->
    <?php $this->load->view('common/before_body_close'); ?>
    <div id="modal-request" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
    <div id="modal-selected-medical-center" class="modal fade" role="dialog"></div>

    <script type="text/javascript">
        
        $(document).ready(function() {
            var requestId = "<?php echo $request->request_id; ?>";
            $( "#select-time" ).click(function(){

                length = $( '.check-seeker:checked' ).length;

                if (length == 0) {
                    alert('¡Debe selecionar al menos 1 postulante!');
                    return;
                }

                $( "#modal-select-time" ).modal('show');
            });

            $(document).on('click', '.notify-candidate', function(e){
                
                e.preventDefault();
            
                if ($( ".check-seeker:checked" ).length == 0) {
                    toastr["error"]("¡Debe seleccionar al menos 1 postulante!");
                    return;
                }

                var url = "<?php echo site_url('employer/exam_requests/requests/notify_candidate'); ?>";

                var examRequestIds = [];

                $( ".check-seeker:checked" ).each(function(i, element){
                    examRequestIds.push($(element).val());
                });

                var notificationType = $(this).data('notification-type');

                var data = {
                    'exam_seekers[]': examRequestIds,
                    'notification_type': notificationType
                };

                $.get(url, data, function(response) {
                    $( "#modal-confirm-notify-seekers" ).html(response).modal('show');
                });

                return false;
            });

            $(document).on('submit', '#form-notify-candidate', function(e){
                
                e.preventDefault();

                var url = $(this).prop('action');

                var data = $(this).serialize();

                $.post(url, data, function(response) {
                    if (!response.success) {
                        toastr["error"]('¡No se pudo notificar a los candidatos!');
                    }

                    toastr["success"]("¡Notificación enviada!");

                    window.location.reload();

                }, 'json');

                return false;
            });

            $(document).on('submit', '#form-exam-save-time', function(e){
                
                e.preventDefault();
        
                if (!confirm("¿Está seguro guardar los horarios a los candidatos seleccionados?")) {
                    return;
                }

                var examRequestIds = [];

                $( ".check-seeker:checked" ).each(function(i, element){
                    examRequestIds.push($(element).val());
                });
                var time = $( "#hours" ).val() + ':' + $( "#minutes" ).val() + " " + $( "#time-type" ).val();

                var url = app.siteUrl('employer/exam_requests/requests/do_load_time');

                data = {
                    'exam_seekers[]': examRequestIds,
                    'time': time
                };

                $.post(url, data, function(response) {
                    if (!response.success) {
                        toastr["error"]('¡No se pudo guardar los horarios!');
                    }

                    window.location.reload();

                }, 'json');

                return false;
            });

            $( "#reschedule-seekers" ).click(function(){

                length = $( '.check-seeker:checked' ).length;

                if (length == 0) {
                    alert('¡Debe selecionar al menos 1 postulante!');
                    return;
                }

                $( "#modal-reschedule" ).modal('show');
            });

            $(document).on('submit', '#form-exam-reschedule', function(e){
                
                e.preventDefault();

                if (!confirm("¿Está seguro de reprogramar la cita?")) {
                    return;
                }

                inputs = [];

                $( '.check-seeker:checked' ).each(function(index, input) {
                    inputs.push($(input).val());
                });     

                var path = 'employer/exam_requests/requests/do_reschedule/';
                var url = app.siteUrl(path);
                
                var data = {
                    'request_id': requestId,
                    'seekers': inputs
                };

                data = $(this).serialize() + '&' + $.param(data);

                $.post(url, data, function(response) {
                    if (!response.success) {
                        toastr["error"]('¡No se pudo reprogramar las citas!');
                    }

                    window.location.reload();

                }, 'json');

                return false;
            });

            $( ".send-exam-request" ).click(function(){
                var url = "<?php echo site_url('employer/exam_requests/requests/confirm_send/'); ?>" + requestId;
                $( "#modal-request" ).load(url, {}, function(response){
                    $(this).html(response).modal('show');
                });
            });

            $(document).on('submit', '#form-send-request', function(e){
                
                e.preventDefault();

                if ($( ".input-request-send-emails" ).length == 0) {
                    toastr["error"]("¡Debe agregar al menos 1 correo!");
                    return;
                }

                if (!window.confirm('¿Esta seguro de enviar la solicitud?')) {
                    return;
                }

                var url = "<?php echo site_url('employer/exam_requests/requests/send'); ?>";
                
                var data = $(this).serialize(); 
                btnSend = $(this).find('.form-submit');
                btnSend.val("Enviando...").prop('disabled', true);

                btnCancel = $(this).find('.form-cancel');
                btnCancel.prop('disabled', true);

                $.post(url, data, function(response) {

                    if (!response.success) {
                        toastr["error"]("¡Error al enviar la solicitud de examen!");
                        return;
                    }

                    toastr["success"]("¡Solicitud de examen enviada!");
                    window.location.reload();
                }, 'json')
                .fail(function(){
                    toastr["error"]("¡Error al realizar la transacción!");
                })
                .always(function(){
                    btnSend.val("Enviar").prop('disabled', false);
                    btnCancel.prop('disabled', false);
                });

                return false;
            });

            $( ".edit-request" ).click(function() {
                var requestId = $(this).data('request-id');
                var url = "<?php echo site_url('employer/exam_requests/requests/edit/'); ?>" + requestId;

                $.get(url, {}, function(response) {
                    
                    $( "#modal-request" ).html(response.list_seekers).modal('show');
                    $( "#modal-selected-medical-center" ).html(response.medical_centers);
                }, 'json');
            });

            $( "#resend-request" ).click(function(){

                if (!window.confirm("¿Desea reenviar la solicitud?")) {
                    return;
                }

                var url = "<?php echo site_url('employer/exam_requests/requests/resend'); ?>";
                var data = {
                    'exam_requests[]': $(this).data('request-id')
                }; 

                $.post(url, data, function(response) {

                    if (!response.success) {
                        toastr["error"]("¡Error al reenviar la solicitud!");
                        btnSend.text("Enviar solicitud")
                               .prop('disabled', false);
                        return;
                    }

                    toastr["success"]("¡Solicitud reenviada!");
                   
                }, 'json')
                .fail(function(){
                    toastr["error"]("¡Error al realizar la transacción!");
                });
            });
        });
    </script>
</body>
</html>