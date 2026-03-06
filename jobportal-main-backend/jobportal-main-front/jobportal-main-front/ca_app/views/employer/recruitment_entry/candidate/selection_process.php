<?php
$is_session_p3 = $this->session->userdata('current_profile_id') == 3;
$is_session_p5 = $this->session->userdata('current_profile_id') == 5;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('common/meta_tags'); ?>
		<title><?php echo $title;?></title>
		<?php $this->load->view('common/before_head_close'); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/app/styles/staff_request/staff_request_detail.css?t=1595698816'); ?>">

        <style type="text/css"> 

            .candidate-section-content {
                border-bottom: 1px solid #ccc;
                margin-bottom: 8px;
            }

            .candidate-section-content-title {
                padding: 8px 15px;
                font-size: 13px;
                text-transform: uppercase;
                background: #eee;
                color: #444;
                display: inline-block;
                border: 1px solid #ccc;
                border-bottom: none;
                font-weight: bold; 
            }
            
            .attach-file-item {
                border: 1px solid #888;
                padding: 15px 5px;
                margin-top: 5px;
                position: relative;
                text-align: center;
                font-size: 16px;
                background: #eee;
            }

            .btn-menu-candidate {
                background: #337ab7;
                border: 1px solid #ccc;
                color: #fff;
                border-radius: 4px;
                padding: 1px 6px;
            }

            .btn-option {
                background: #eee;
                padding: 3px 5px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            .dropdown ul li a {
                cursor: pointer;
            }

            .list-options {
                list-style: none;
                padding-bottom: 3px;
            }

            .list-options__item {
                padding: 4px 0;
                border-bottom: 1px solid #cccccc;
                font-weight: normal;
            }

			.document-ok {
				color: green;
			}

            .dropdown-options-more .dropdown-toggle {
                background: transparent;
                padding: 1px;
                border: 0;
            }

            .dropdown-options .dropdown-toggle {
                background: #fff;
            }

            .userinfoWrp .username {
                display: none;
            }

            .uploadPhoto {
                display: none;
            }

            .userinfoWrp {
                border: 1px solid #ccc;
                border-radius: 0;
                padding: 10px 30px;
            }

            .userinfoWrp .col-md-8 {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
            }

            .userinfoWrp .usercel {
                border-bottom: 1px solid #ccc;
            }
            
            .btn-show-options {
                background: #fff;
                border: 1px solid #ccc;
                padding: 4px 5px;
            }

            .show-sync-logs:hover {
                cursor: pointer;
            }

            .glyphicon-exclamation-sign {
                color: #FFC107;
            }

            .glyphicon-check {
                color: #28A745;
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
                                <a class="_link-back" style="color:#fff;" href="#">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                Proceso de ingreso a overall
                            </div>
                            <div class="formint">
                                <input id="candidate-id" type="hidden" value="<?php echo $candidate->ID; ?>">
                                <input id="global-process-id" type="hidden" value="<?php echo $process->id; ?>">
                                <input id="job-id" type="hidden" value="<?php echo $job->ID; ?>">
                                <div style="border: 1px solid #ccc;margin-bottom: 10px;">
                                    <div style="border-bottom: 1px solid #ccc;padding: 10px;">
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <div style="text-align: center;">
                                                    <a href="#" 
                                                    class="js-btn-load-content" 
                                                    data-url="<?php echo site_url('candidate/cv_data/' . $candidate->ID); ?>">
                                                        
                                                        <img width="100" src="<?php echo img_pic_candidate($candidate->photo); ?>" />
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-xs-10">
                                                <div>
                                                    <h4 style="font-weight: bold;padding: 5px 0;">
                                                        <a href="#"
                                                        id="seeker-full-name"
                                                        style="color: #333;text-decoration: underline;" 
                                                        class="js-btn-load-content" 
                                                        data-url="<?php echo site_url('candidate/cv_data/' . $candidate->ID); ?>">
                                                            <?php echo mb_strtoupper($candidate->first_name . ' ' . $candidate->last_name); ?>
                                                        </a>
                                                    </h4>
                                                    <?php if ($process_country->has_operation_overall == 1 && $process_company->system_internal == 1): ?>
                                                        <div style="position: absolute;right: 10px;top: 0;">
                                                            <?php 
                                                                $label_contracted = $rs_process_candidate->contracted ? 'label-success' : 'label-warning';
                                                            ?>
                                                            <?php if ($rs_process_candidate->contracted == 1): ?>
                                                                <?php if (count($sync_log_errors) > 0): ?>
                                                                    <span class="label label-warning pull-right show-sync-logs" 
                                                                        style="margin-left:5px;"
                                                                        title="Advertencia">
                                                                        <i class="glyphicon glyphicon-exclamation-sign"></i>
                                                                    </span>
                                                                    &nbsp;
                                                                <?php endif; ?>

                                                                <span class="label <?php echo $label_contracted; ?> pull-right show-sync-logs">
                                                                    Contratado
                                                                </span>
                                                            <?php endif; ?> 

                                                            <?php if ($rs_process_candidate->contracted == 0): ?>
                                                                <?php if (count($sync_log_errors) > 0): ?>
                                                                    <span class="label label-danger pull-right show-sync-logs" 
                                                                        style="margin-left:5px;"
                                                                        title="Errores al contratar candidato">
                                                                        <i class="glyphicon glyphicon-exclamation-sign"></i>
                                                                    </span>
                                                                    &nbsp;
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <?php if ($rs_process_candidate->contracted == 0): ?>
                                                                <?php if ($is_session_p3): ?>
                                                                    <button id="btn-confirm-hire-candidate"
                                                                            class="btn btn-xs btn-primary-dark pull-right">
                                                                        Contratar
                                                                    </button>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <ul class="list-options">
                                                        <li class="list-options__item">
                                                            <?php echo '<b>' . document_type_text($candidate->document_type) . ':</b> ' . $candidate->document_number; ?>
                                                            &nbsp;
                                                            <?php if ($process_company->ID == 1): ?>
                                                                <?php if ($candidate->its_reniec): ?>
                                                                    <i class="glyphicon glyphicon-check"></i>
                                                                    Validación Reniec
                                                                <?php else: ?>
                                                                    <i class="glyphicon glyphicon-exclamation-sign"></i>
                                                                    <a href="#" 
                                                                        style="text-decoration: underline;" 
                                                                        class="btn-xs js-validate-reniec"
                                                                    >
                                                                        Pendiente Validación Reniec
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </li>
                                                        <li class="list-options__item">
                                                            <b>Email:</b> <?php e($candidate->email); ?>      
                                                        </li>

                                                        <li class="list-options__item">
                                                            <b>Proceso ID:</b> #<?php echo $process->id; ?>       
                                                        </li>
                                                        <li class="list-options__item">
                                                            <b>Empleo:</b> <?php echo $job->job_title; ?>		
                                                        </li>
                                                        <li class="list-options__item">
                                                            <b>Solicitud Empleo:</b> 
                                                            <?php if ($job->request_ID): ?>
                                                                <?php echo $job->request_ID; ?>
                                                                <a href="#" 
                                                                style="text-decoration: underline;" 
                                                                class="btn-xs js-btn-load-content"
                                                                data-url="<?php echo site_url('employer/recruitment_entry/staff_requests/show/' . $job->request_ID); ?>">
                                                                    Ver
                                                                </a>  
                                                            <?php else: ?>
                                                            Sin solicitud          
                                                            <?php endif; ?>
                                                        </li>

                                                        <?php if ($rs_process_candidate->contracted): ?>
                                                            <?php if ($candidate->employee_code): ?>
                                                                <li class="list-options__item">
                                                                    <b>Trabajador Código:</b> 
                                                                    <?php echo $candidate->employee_code; ?> 
                                                                </li>
                                                            <?php endif; ?>

                                                            <?php if ($rs_contract && $rs_contract->hired_at): ?>
                                                                <li class="list-options__item">
                                                                    <b>Contratado el:</b>  
                                                                    <?php 
                                                                        $contract_date = date('d/m/Y', strtotime($rs_contract->hired_at));
                                                                    ?>
                                                                    <?php 
                                                                        echo $contract_date; 
                                                                    ?>       
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($rs_contract && $rs_contract->contract_start_date && $rs_contract->contract_end_date): ?>
                                                                <li class="list-options__item">
                                                                    <b>Contrato Desde:</b>
                                                                    <?php e(date('d/m/Y', strtotime($rs_contract->contract_start_date))); ?>
                                                                    <b>Hasta: </b> <?php e(date('d/m/Y', strtotime($rs_contract->contract_end_date))); ?>
                                                                </li>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ($rs_contract && $rs_contract->contract_type_model_code): ?>
                                                                <li class="list-options__item">
                                                                    <b>Modelo: </b> 
                                                                    <?php e($rs_contract->contract_type_model_code . ' - ' . $rs_contract->contract_type_model_name); ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="padding: 8px 5px; text-align: right; background: #eee;">

                                        <?php if ($is_session_p3): ?>
                                            <div class="dropdown dropdown-options" style="text-align: right;display: inline-block;">
                                                <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                                    Documentos del reclutamiento
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">

                                                    <?php foreach ($rys_documents as $row_doc): ?>
                                                
                                                        <?php if ($row_doc->option_type_id == 1): ?>
                                                            <li>
                                                                <a class="menu-rs-option-more-detail js-btn-load-content"
                                                                   data-url="<?php echo site_url('candidate/load_rs_documents/' . $job->ID . '/' . $candidate->ID . '/' . $row_doc->key); ?>">
                            
                                                                    <?php e($row_doc->name); ?>
                                                                    <?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
                                                                        <i class="glyphicon glyphicon-ok document-ok"></i>
                                                                    <?php endif; ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if ($row_doc->option_type_id == 2 && $row_doc->id == 3): ?>
                                                            <li>
                                                                <a class="menu-rs-option-more-detail js-btn-load-content"
                                                                   data-url="<?php echo site_url('candidate/load_document_screening/' . $job->ID . '/' . $candidate->ID); ?>">
                                                                    <?php e($row_doc->name); ?>
                                                                    <?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
                                                                        <i class="glyphicon glyphicon-ok document-ok"></i>
                                                                    <?php endif; ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if ($row_doc->option_type_id == 2 && $row_doc->id == 13): ?>
                                                            <li>
                                                                <a class="menu-rs-option-more-detail js-btn-load-content"
                                                                   data-url="<?php echo site_url('candidate/load_rs_other_documents/' . $job->ID . '/' . $candidate->ID); ?>">
                                                                    <?php e($row_doc->name); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if ($row_doc->option_type_id == 2 && in_array($row_doc->id, [1, 10])): ?>
                                                            <li>
                                                                <a class="menu-rs-option-more-detail js-btn-load-content"
                                                                   data-url="<?php echo site_url('candidate/load_document_exam_request_results/' . $job->ID . '/' . $candidate->ID . '/' . $row_doc->key); ?>">
                                                                    <?php e($row_doc->name); ?>
                                                                    <?php if (isset($rs_document_counter[$row_doc->key]) && $rs_document_counter[$row_doc->key] > 0): ?>
                                                                        <i class="glyphicon glyphicon-ok document-ok"></i>
                                                                    <?php endif; ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?> 

                                        <button class="btn btn-sm btn-default js-btn-load-content" 
                                                type="button" 
                                                id="btn-list-rd-seeker-documents" 
                                                data-content-id="list-rd-seeker-documents"
                                                data-url="<?php echo site_url('candidate/load_detail_requested_documents/' . $process->id . '/' . $candidate->ID); ?>">
                                            Documentos de contratación
                                        </button>

                                        <?php if ($is_session_p3 && $job->company_ID == 1): ?>
                                            <div class="dropdown dropdown-options-more" style="display: inline-block;">
                                                <button class="dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <span class="glyphicon glyphicon-option-vertical"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a href="#" 
                                                        class="js-btn-load-content"
                                                        data-url="<?php echo site_url('candidate/search_experience_overall/' . $candidate->ID); ?>">
                                                            Experiencia en overall
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="content-main-detail">
                                </div>
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
        <?php $this->load->view('common/before_body_close'); ?>

        <div id="modal-rd-evicertia-status" class="modal fade" role="dialog"></div>
        <?php $this->load->view('employer/recruitment_entry/candidate/common/modal_hire_candidate'); ?>
        <?php $this->load->view('employer/recruitment_entry/candidate/common/modal_ignore_rightful_claimants'); ?>        
        <?php $this->load->view('employer/recruitment_entry/candidate/common/modal_show_sync_logs'); ?>
                                            
        <script type="text/javascript">
            $(function(){
                $( "#seeker-full-name" ).click();
            });
        </script>
        <script type="text/javascript">
            var documentType = "<?php echo $candidate->document_type; ?>";
            var documentNumber = "<?php echo $candidate->document_number; ?>";
            var id = "<?php echo $candidate->ID; ?>";

            $( ".js-btn-load-content" ).click(function(e){
                e.preventDefault();
                $( ".menu-option-candidate-item.selected" ).removeClass('selected');
                $( ".content-detail" ).hide();
            
                var contentId = $(this).data('content-id');
                $(this).addClass('selected');

                if (!contentId) {
                    contentId = 'rs-content-' + Date.now();
                    $(this).data('content-id', contentId);      
                }
            
                var contentLoad = $( "#" + contentId); 
            
                if (contentLoad.length == 0) {
                    $( "#list-rd-seeker-documents" ).remove();
                    contentLoad = $( "<div id='" + contentId + "' class='content-detail'/>");
                    var url = $(this).data('url');
                    contentLoad.html("<div style='text-align:center;'>Cargando...</div>");
                    contentLoad.load(url, function(response) {
                        $(this).html(response);
                    });

                    $( ".content-main-detail" ).append(contentLoad);
                }       

                contentLoad.show();
            });
            $(document).on('click', '.js-validate-reniec', function(e) {
                e.preventDefault();
                
                var data = {
                    document_type: documentType,
                    document_number: documentNumber,
                    id: id
                };

                var url = '<?php echo site_url('employer/recruitment/jobseeker_register/check_document_reniec'); ?>';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            toastr["success"]('Candidato validado correctamente');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr["error"]('No se puedo validar el Candidato.');
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr["error"]('Ocurrió un error al verificar el documento.');
                    }
                });
            });
        </script>
    </body>
</html>