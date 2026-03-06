<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        <link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('public/css/app/pages/recruitment/selection.css?t=3');?>" rel="stylesheet" type="text/css" />

        <style type="text/css">
            @media (min-width: 800px) {
                #modal-view-profile .modal-dialog {
                    width: 800px;
                }            
		    }

            .companydescription {
                padding: 0;
            }

            .sys-banner {
                border-bottom-left-radius: 5px; 
                border-bottom-right-radius: 5px; 
            }

            .content-main {
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
            }

            .sys-content-list ul li {
                padding: 5px 4px;
            }

        </style>
    </head>
    <body>
        <?php $this->load->view('common/after_body_open'); ?>
        <div class="siteWraper">
            <!--Header-->
            <?php $this->load->view('common/header'); ?>
            <!--/Header--> 
            <!--Detail Info-->
            <div class="container detailinfo">
                <div class="row">
                    <div class="col-md-3">
                        <div class="dashiconwrp">
                            <?php $this->load->view('employer/common/menu/sidebar'); ?>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="formwraper">
                            <div id="input-hidden">
                                <input id="job_id" type="hidden" value="<?php echo $job->ID; ?>">
                                <input id="global_process_id" type="hidden" value="<?php echo @$rs_process->id; ?>">
                                <input id="current_stage" type="hidden" value="<?php echo $current_stage; ?>">
                            </div>
                            <div class="titlehead">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a class="_link-back" style="color:#fff;" href="#">
                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                        </a>
                                        <b>Proceso de reclutamiento y selección</b>
                                    </div>
                                </div>
                            </div>
                            <?php if (!isset($rs_process)): ?>
                                <style>                                   
                                    .companydescription {
                                        padding: 5em 10px;
                                    }
                                </style>
                                <div class="companydescription">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="padding: 10px; font-size: 16px;">
                                                <div style="font-size: 15px;padding: 20px; text-align:center;">
                                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                                        width="55.000000pt" height="55.000000pt" viewBox="0 0 224.000000 225.000000"
                                                        preserveAspectRatio="xMidYMid meet" style="opacity: 0.7;">

                                                        <g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)"
                                                        fill="#000000" stroke="none">
                                                        <path d="M380 2158 c-25 -13 -55 -38 -67 -57 -21 -31 -23 -45 -23 -177 0 -151
                                                        7 -174 50 -174 42 0 50 24 50 153 0 95 4 128 16 145 l15 22 719 0 719 0 15
                                                        -22 c14 -20 16 -107 16 -745 l0 -723 -221 0 c-248 0 -275 -6 -303 -65 -9 -19
                                                        -16 -59 -16 -94 l0 -61 -210 0 -210 0 0 61 c0 35 -7 75 -16 94 -28 59 -55 65
                                                        -303 65 l-221 0 0 518 c0 390 -3 521 -12 530 -7 7 -24 12 -38 12 -14 0 -31 -5
                                                        -38 -12 -9 -9 -12 -140 -12 -530 l0 -518 -35 0 c-43 0 -88 -22 -109 -52 -23
                                                        -33 -23 -403 0 -436 38 -54 -2 -52 994 -52 996 0 956 -2 994 52 23 33 23 403
                                                        0 436 -21 30 -66 52 -109 52 l-35 0 0 744 0 743 -23 34 c-12 19 -42 44 -67 57
                                                        l-44 22 -716 0 -716 0 -44 -22z m440 -1754 c0 -73 18 -115 57 -133 32 -15 494
                                                        -15 526 0 39 18 57 60 57 133 l0 66 295 0 295 0 0 -160 0 -160 -910 0 -910 0
                                                        0 160 0 160 295 0 295 0 0 -66z"/>
                                                        <path d="M1090 1400 l0 -240 50 0 50 0 0 240 0 240 -50 0 -50 0 0 -240z"/>
                                                        <path d="M1090 980 l0 -80 50 0 50 0 0 80 0 80 -50 0 -50 0 0 -80z"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div style="text-align: center;">
                                                    Este proceso no esta abierto
                                                </div>
                                                <div style="text-align: center;padding-top: 20px;">
                                                    <button id="open-rs-confirm-open-process" class="btn btn-primary">Abrir proceso</button>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <style>                                   
                                    .formwraper {
                                        border: none;
                                    }
                                </style>
                                <div class="companydescription">
                                    <div class="sys-banner">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h1>
                                                    <?php echo word_limiter(strip_tags($job->job_title), 40);?>
                                                </h1>
                                            
                                                <div class="sys-content-list">
                                                    <ul class="sys-list">
                                                        <li>
                                                            <span style="font-weight: 600;">Proceso ID: </span> <?php echo $rs_process->id; ?>        
                                                        </li>
                                                        
                                                        <?php if (user_belong_to_company_internal()): ?>
                                                            <?php if ($rs_process->created_at): ?>
                                                                <li>
                                                                    <span style="font-weight: 600;">Abierto el: </span> 
                                                                    <?php echo _date_locale_format(strtotime($rs_process->created_at), 'dd MMM y'); ?>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($rs_process_created_by): ?>
                                                                <li>
                                                                    <span style="font-weight: 600;">Abierto por: </span> 
                                                                    <?php e($rs_process_created_by->first_name); ?>
                                                                </li>
                                                            <?php endif; ?>
                                                            <li>
                                                                <span style="font-weight: 600;">Solicitud Id: </span> 
                                                                    <?php if ($job->request_ID): ?>
                                                                        <?php echo $job->request_ID . ' '; ?>
                                                                        <a style="margin-left: 5px;"href="<?php echo site_url('employer/staff_requests/show/' . $job->request_ID); ?>"
                                                                           target="__blank">
                                                                            Ver solicitud
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    <?php if (!$job->request_ID): ?>
                                                                        Empleo sin solicitud
                                                                    <?php endif; ?>
                                                                </span>   
                                                            </li> 

                                                            <?php if ($rs_process->expiration_date): ?>
                                                                <li>
                                                                    <?php if ($rs_process->expired): ?>
                                                                        <span style="font-weight: 600;">Expirado el: </span>
                                                                    <?php else:  ?>
                                                                        <span style="font-weight: 600;">Expira el: </span>
                                                                    <?php endif;  ?>

                                                                    <?php echo _date_locale_format(strtotime($rs_process->expiration_date), 'dd MMM y'); ?>

                                                                    <div style="display: inline-block;float: right;">
                                                                        <?php if (!$rs_process->expired && $rs_process->sts != 'finished'): ?>
                                                                            <?php 
                                                                                $days_remaining = count_days(date('Y-m-d'), $rs_process->expiration_date);

                                                                                $class_traffic_ligh_days = 'label-success';

                                                                                if ($days_remaining <= 1) {
                                                                                    $class_traffic_ligh_days = 'label-danger';
                                                                                }

                                                                                if ($days_remaining > 1 && $days_remaining < 5) {
                                                                                    $class_traffic_ligh_days = 'label-warning';
                                                                                }
                                                                            ?>
                                                                            <span class="label <?php echo $class_traffic_ligh_days; ?>"><?php echo $days_remaining > 1 ? $days_remaining . ' dias restantes' : $days_remaining . ' día restante'; ?></span>
                                                                        <?php endif; ?>
                                                                        <?php if ($rs_process->expired && $rs_process->sts == 'finished'): ?>
                                                                            <button class="btn btn-xs btn-default btn-resume-process-expired" 
                                                                                    style="background: #efefef;border: 0;"
                                                                                    data-job-id="<?php echo $rs_process->job_ID; ?>">Reanudar</button>
                                                                        <?php endif;  ?>
                                                                    </div>
                                                                </li>
                                                            <?php endif; ?>

                                                        <?php endif; ?>

                                                        <?php if ($rs_process->note != ''): ?>
                                                             <li>
                                                                <span style="font-weight: 600;">Nota: </span>
                                                                <?php echo $rs_process->note; ?> 
                                                            </li>
                                                        <?php endif; ?>
                                                        
                                                    </ul>
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-5 content-action">
                                                <div class="content-action-item">
                                                    <table width="100%">
                                                        <tr>
                                                            <td style="text-align: right;">
                                                                <span class="info-process-sts">
                                                                    <b>
                                                                        Estado: 
                                                                        <?php echo rs_process_status_text($rs_process->sts); ?> 
                                                                    </b>       
                                                                </span>
                                                                
                                                                <div class="dropdown" style="display: inline-block;">
																	<button class="dropdown-toggle" type="button" data-toggle="dropdown" style="background: transparent;">
																		<span class="glyphicon glyphicon-option-vertical"></span>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
                                                                        <?php if ($rs_process->sts == 'active'): ?>
                                                                            <li><a id="modal-open-suspend-process" href="#">Suspender proceso</a></li>
                                                                            <li><a id="modal-open-finish-process" href="#">Terminar proceso</a> </li> 
                                                                        <?php endif; ?>

                                                                        <?php if ($rs_process->sts == 'suspended'): ?>
                                                                            <li><a class="btn-resume-process" href="#">Reanudar proceso</a> </li>
                                                                        <?php endif; ?>

                                                                        <?php if (user_belong_to_company_internal()): ?>
                                                                            <li class="divider"></li>
                                                                            <li>
                                                                                <a href="#" 
                                                                                   class="btn-save-rrhh-assignment">
                                                                                    Ver gestores de nómina
                                                                                </a>
                                                                            </li>
                                                                            <?php if (get_session_company_id() == 1): ?>
                                                                                <li>
                                                                                    <a href="#" 
                                                                                    data-toggle="modal" 
                                                                                    data-target="#modal-exam-request-notify-employers">
                                                                                        Notificar gestión de programaciones
                                                                                    </a> 
                                                                                </li>
                                                                            <?php endif; ?>
                                                                            <li>
                                                                                <a href="#" 
                                                                                   data-toggle="modal" 
                                                                                   data-target="#modal-alert-periods">
                                                                                    Alertas periodos
                                                                                </a> 
                                                                            </li> 
                                                                            <li>
                                                                                <a id="btn-rys-stages-config" 
                                                                                   href="#" 
                                                                                   data-job-id="<?php echo $rs_process->job_ID; ?>">
                                                                                    Etapas del proceso
                                                                                </a>                                                                           
                                                                            </li>
                                                                            <li>
                                                                                <a id="btn-rys-document-config" 
                                                                                   href="#" 
                                                                                   data-job-id="<?php echo $rs_process->job_ID; ?>">
                                                                                    Documentos del reclutamiento
                                                                                </a>                                                                           
                                                                            </li>
                                                                            <li>
                                                                                <a id="btn-recruitment-contract-document-config" 
                                                                                   href="#" 
                                                                                   data-job-id="<?php echo $rs_process->job_ID; ?>">
                                                                                    Documentos de contratación 
                                                                                </a>                                                                           
                                                                            </li>
                                                                        <?php endif; ?>  
                                                                          
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>                  
                                    </div>
                                    <br />
                                    <div style="margin-bottom: 20px;display: none;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div>
                                                    <form id="rys-form-search-candidates"  
                                                          method="get">
                                                        <input type="text" name="query" placeholder="Buscar candidatos por nombre o correo electrónico" autocomplete="off" style="border: 1px solid #bbb;padding: 5px;margin:0;box-sizing: border-box;display: table-cell;width: 84%">
                                                        <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">
                                                        <input type="submit" value="Buscar" class="btn btn-primary" style="margin:0;background: #e0e0e0; border:1px solid #bbb; color: #333; box-sizing: border-box;display: table-cell;width: 15%;">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="wrapper-content-candidates-stages">
                                        <div class="content-main">
                                            <div class="content-main-load">
                                                <img src="<?php echo img_loading_url(); ?>" 
                                                     style="width:24px; height:24px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    <!--/Job Detail--> 
                </div>
            </div>
            <?php $this->load->view('common/bottom_ads');?>
            <!--Footer-->
            <?php $this->load->view('common/footer'); ?>
            <?php $this->load->view('common/before_body_close'); ?>

            <script type="text/javascript">
                $(document).on('focus', '.datepicker', function() {

                    if ($(this).hasClass('hasDatepicker') === true) {
                        return;
                    }

                    $(this).datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: "dd/mm/yy",
                        dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
                        dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
                        monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
                        monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec" ],
                        beforeShow: function() {
                            setTimeout(function(){
                            $('.ui-datepicker').css('z-index', 99999999999);
                            }, 0);
                        }
                    });
                });
            </script>
            
            <script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>
        
            <!-- Modal -->
            <?php $this->load->view('employer/recruitment/modal/seeker_profile_view'); ?>
            <?php $this->load->view('employer/recruitment/modal/add_candidate_process'); ?>
            <?php $this->load->view('employer/recruitment/modal/move_candidates_hired'); ?>
            <?php $this->load->view('employer/recruitment/modal/save_rrhh_assignments'); ?>
            <?php $this->load->view('employer/recruitment/modal/finish_process'); ?>
            <?php $this->load->view('employer/recruitment/modal/suspend_process'); ?>
            <?php $this->load->view('employer/recruitment/modal/alert_periods'); ?>
            <?php $this->load->view('employer/recruitment/modal/result_overall_experiences'); ?>
            <?php $this->load->view('employer/recruitment/modal/recruitment_search_candidates'); ?>
            <?php $this->load->view('employer/recruitment/modal/exam_request_upload_file_high'); ?>
            <?php $this->load->view('employer/recruitment/modal/exam_request_upload_file_oc'); ?>
            <?php $this->load->view('employer/recruitment/process_document_stages/common/modal_config'); ?>
            <?php $this->load->view('employer/recruitment/modal/recruitment_contract_document_config'); ?>
            <?php $this->load->view('employer/recruitment/modal/update_email_candidate'); ?>
            <?php $this->load->view('employer/recruitment/modal/update_phone_candidate'); ?>
            <?php $this->load->view('employer/recruitment/modal/update_birthdate_candidate'); ?>
            <?php $this->load->view('employer/recruitment/modal/modal_update_city'); ?>
            <?php $this->load->view('employer/recruitment/screening/common/modal_screening'); ?>
            <?php $this->load->view('employer/recruitment/exam_request_results/common/modal_exam_request_results'); ?>
            <?php $this->load->view('employer/recruitment_scheduled_exams/common/modal_exam_request_notify_employers'); ?>
            <?php $this->load->view('employer/recruitment/modal/short_list_send_email'); ?>
            <?php $this->load->view('employer/recruitment/modal/update_data_seeker'); ?>
            <?php $this->load->view('employer/recruitment/process_stages/common/modal_process_stages'); ?>
            <?php $this->load->view('employer/recruitment/modal/open_process'); ?>
            <?php $this->load->view('employer/recruitment/modal/resume_process_expired'); ?>
            <?php $this->load->view('employer/recruitment/modal/modal_move_candidate_stage'); ?>
            

            <div id="modal-schedule-exam" class="modal" role="dialog"></div>
            <div id="modal-stop-tracking" class="modal" role="dialog"></div>
            <div id="modal-active-process-candidate" class="modal" role="dialog"></div>
            <div id="modal-upload-document" class="modal" role="dialog"></div>
            <div id="modal-list-scheduled-exams" class="modal" role="dialog"></div>
            <div id="modal-seeker-video" class="modal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
            <div id="modal-share-video-email" class="modal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
            <div id="modal-interview-video-indications" class="modal" role="dialog" ></div>
            <div id="modal-show-form-detail" class="modal" role="dialog"></div>
            
            <?php $this->load->view('employer/recruitment/scripts/rys_process'); ?>
            <?php $this->load->view('employer/recruitment/recruitment_candidates/scripts/search_list_candidates'); ?>
            <?php $this->load->view('employer/recruitment/recruitment_candidates/scripts/search_list_blocks'); ?>
            <?php $this->load->view('employer/recruitment/scripts/rys_load_documents'); ?>
            <?php $this->load->view('employer/recruitment/scripts/rys_interview'); ?>
            <?php $this->load->view('employer/recruitment/scripts/rys_forms'); ?>
            <?php $this->load->view('employer/recruitment/scripts/period_rules'); ?>
            <?php $this->load->view('employer/recruitment/modal/modal_exam_request_comments'); ?>
            
        </div>
    </body>
</html>

