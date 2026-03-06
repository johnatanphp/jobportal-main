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

            .aboutloc {
                font-style: italic;
                color: #888888;
            }
            .text-info {
                color:#555;
                font-style: italic;
                display: block;
                font-size: 12px;
                margin-top: 2px;
            }

            .table thead th {
                text-align: center;
            }

            .table tbody td {
                text-align: center;
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
                                            Solicitud de Programaciones
                                        </b>
                                    </div>
                                </div>
                            </div>

                            <div class="table-search">
                                <?php echo form_open('employer/exam_requests/requests/list_rys/', ['method' => 'get']); ?>  
                                    <table width="100%">
                                        <tr>
                                            <td width="90%">
                                                <input type="text" name="query" class="form-control" value="<?php echo $filters['query']; ?>" placeholder="Buscar">     
                                            </td>
                                            <td width="10%">
                                                <button type="submit" class="btn btn-block btn-search">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>     
                                            </td>
                                            <td align="right">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm dropdown-toggle" style="text-decoration: underline;border:1px solid #ccc;background: #fff; font-weight: bold;" type="button" data-toggle="dropdown">
                                                        <i class="glyphicon glyphicon-option-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a href="#" data-toggle="modal" data-target="#modal-filter">
                                                                Filtrar
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="#" class="show-overall-emails">
                                                                Notificaciones
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                <?php echo form_close(); ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Examen</th>
                                            <th>Empleo</th>
                                            <th>Reclutador</th>
                                            <th>Consultora</th>
                                            <th>Cliente</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rys_processes as $rys_process): ?>
                                            <tr>
                                                <td>
                                                    <div class="dropdown dropdown-options-job">
                                                        <button style="border:0; background: transparent;padding: 0;"class="dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <span class="glyphicon glyphicon-option-vertical"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-left">
                                                            <li>
                                                                <a style="display:none;" href="<?php echo site_url('employer/exam_requests/requests/list/' . $rys_process->id . '?action=create_request' );?>">
                                                                    Crear solicitud
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo site_url('employer/exam_requests/requests/list/' . $rys_process->id );?>">
                                                                    Ver Solicitudes
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>                                    
                                                </td>
                                                <td style="text-align: left;"><?php echo $rys_process->exam_type_name; ?> 
                                                    <div class="text-info">Creada: <?php echo date('d M Y', strtotime($rys_process->created_at)); ?></div>
                                                </td>
                                                <td style="text-align: left;">
                                                   <?php echo $rys_process->job_title; ?> 
                                                   <div class="text-info">RyS Id: <?php echo $rys_process->ID; ?></div>                   
                                                </td>
                                                <td><?php echo $rys_process->employer_first_name; ?></td>
                                                <td>
                                                    <?php echo $rys_process->consultant_name; ?>
                                                </td>
                                                <td>
                                                    <?php echo $rys_process->client_company_name; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $data_status = [
                                                            '1' => ['Pendiente', 'label-warning'],
                                                            '2' => ['Conforme',  'label-success'],
                                                            '3' => ['Programado', 'label-primary'],
                                                            '4' => ['Cancelado', 'label-default']
                                                        ];
                                                    ?>
                                                    <span class="label <?php echo $data_status[$rys_process->status][1]; ?>">
                                                        <?php echo $data_status[$rys_process->status][0]; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if (count($rys_processes) == 0): ?>
                                <div align="center" class="text-red" style="padding: 20px;">
                                    <h4>Sin resultados</h4>
                                </div>              
                            <?php endif; ?>
                        </div>
                        
                        <div class="paginationWrap pag-wrap-v2">
                            <?php echo ($rys_processes) ? $links : ''; ?>        
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal filter-->
        <?php $this->load->view('employer/exam_requests/exam_requests/modal/list_rys_filters'); ?>                       
        <?php $this->load->view('employer/exam_requests/overall_emails/modal/list_emails'); ?>
        <!-- End Modals-->

        <?php $this->load->view('common/bottom_ads');?>
        <?php $this->load->view('common/footer'); ?>
        <?php $this->load->view('common/before_body_close'); ?>

        <script>
            $(function(){

                function searchNotificationsEmails(mcCode) {

                    var url = "<?php echo site_url('employer/exam_requests/overall_emails/get_emails'); ?>";
                
                    $.get(url, null, function(response) {
                        $( "#modal-exam-request-overall-emails .modal-body" ).html(response);
                    })
                    .fail(function (){
                        toastr["error"]('¡Ha ocurrido un error!');
                    });
                }

                $( ".show-overall-emails" ).click(function(){
                    $( "#modal-exam-request-overall-emails" ).modal("show");

                    searchNotificationsEmails();
                });
            });

        </script>
    </body>
</html>