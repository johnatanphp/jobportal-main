<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        
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
                                Carga de documentos
                            </div>
                        </div>
                    </div>
                    <div class="formint">
                        <input id="candidate-id" type="hidden" value="<?php echo $candidate->ID; ?>">
                        <input id="job-id" type="hidden" value="<?php echo $job->ID; ?>">
                            
                        <div style="border-bottom: 1px solid #ccc;padding: 5px 3px;">
                            <div class="row">
                                <div class="col-xs-2">
                                    <div style="text-align: center;">
                                        <img width="80" 
                                             src="<?php echo img_pic_candidate($candidate->photo); ?>" />
                                    </div>
                                </div>
                                <div class="col-xs-10">
                                    <div>
                                        <h4 style="font-weight: bold;"><?php echo $candidate->first_name . ' ' . $candidate->last_name; ?></h4>
                                    </div>
                                    <div>
                                        <ul class="list-options">
                                            <li class="list-options__item">
                                                Ingreso para: <?php echo $job->job_title; ?>        
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="content-main-detail">
                            <div id="rs-detail-cv-candidate" class="content-detail">
                                <div class="candidate-section-content">
                                    <h4 class="candidate-section-content-title">
                                        Lista de Documentos
                                    </h4>
                                </div>
                                <br />
                                <div id="container-mo-documents"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-upload-documents" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" ></div>

    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <!-- Profile Popups -->
    <?php $this->load->view('common/before_body_close'); ?>

    <script type="text/javascript">
        
        var seekerId = "<?php echo $candidate->ID ?>";
        var jobId = "<?php echo $job->ID ?>";

        $(document).on('click', '.open-modal-upload-result', function(e){

            var url = "<?php echo site_url('employer/exam_requests/request_results/modal_upload'); ?>";
            var data = {
                result_id: $(this).data('result-id')
            };

            $( "#modal-upload-documents" ).load(url, data, function(response){
                $(this).html(response).modal('show');
            });
        });

        $(document).on('submit','#form-upload-exam-result', function(e) {

            e.preventDefault();

            var url = $(this).prop('action');
            var data = new FormData(this);

            var btnSubmit = $(this).find('input[type="submit"]');
            var btnCancel = $(this).find('.form-cancel');

            btnSubmit.prop('disabled', true);
            btnSubmit.val('Guardando...');
            btnCancel.prop('disabled', true);

            $.ajax({
                url: url,
                data: data,
                processData: false,
                contentType: false,
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    var status = response.success;

                    if (status == false) {
                        toastr["error"](response.error);
                        btnSubmit.prop('disabled', false);
                        btnCancel.prop('disabled', false);

                        btnSubmit.val('Guardar');
                        return;
                    }
                
                    toastr["success"]("¡Documento cargado con éxito!");
                    $( '#modal-upload-documents' ).modal('hide');
                    list_exam_results(jobId, seekerId);
                }
            })
            .fail(function() {
                toastr["error"]("¡Ha ocurrido un error!");
                btnSubmit.val('Guardar');
                btnSubmit.prop('disabled', false);
                btnCancel.prop('disabled', false);
            });

            return false;
        });

        $(document).on('click', '.delete-result', function() {
            
            if(!confirm("¿Está seguro de quitar el documento?")) {
                return;
            }

            var url = "<?php echo site_url('employer/exam_requests/request_results/delete'); ?>";
            var data = {
                id: $(this).data('result-id') 
            };
            
            $.post(url, data, function(e) {
                if (e.success == false) {
                    alert('Ha ocurrido un error');
                    return;
                }

                toastr["success"]("Resultado eliminado!");
                list_exam_results(jobId, seekerId);
                
            }, 'json');
        });

        function list_exam_results($jobId, $seekerId) {
        
            var url = "<?php echo site_url('employer/exam_requests/request_results/get_exam_results'); ?>/" + jobId + '/' + seekerId;

            $.get(url, {}, function(response) {
                $( '#container-mo-documents' ).html(response);
            });
        }

        list_exam_results(jobId, seekerId);
    </script>
</body>
</html>