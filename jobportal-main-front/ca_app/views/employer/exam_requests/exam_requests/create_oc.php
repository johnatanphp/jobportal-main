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
                                Solicitar OC - Solicitud Examen
                            </div>
                        </div>
                    </div>
                    <div class="formint">
                        <div style="border-bottom: 1px solid #ccc;padding: 5px 3px;">
                            <div class="row">
                                <div class="col-xs-10">
                                    <div>
                                        <h4 style="font-weight: bold;"></h4>
                                    </div>
                                    <div>
                                        <ul class="list-options">
                                            <li class="list-options__item">
                                                <b>RYS Código:</b> <?php echo $job->ID; ?>        
                                            </li>
                                            <li class="list-options__item">
                                                <b>Empleo:</b> <?php echo $job->job_title; ?>        
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div>
                            <?php echo form_open('employer/exam_requests/requests/do_create_oc', ['id' => 'form-create-oc']); ?>
                                <input type="hidden" name="request_id" value="<?php echo $exam_request->request_id; ?>">
                                <div style="padding: 12px 0;">
                                    <div class="input-group">
                                        <label class="input-group-addon">Documento <span></span></label>
                                        <span><?php echo $document->name; ?></span>
                                    </div>

                                    <?php if ($document->send_to_medical_center): ?>
                                        <div class="input-group">
                                            <label class="input-group-addon">Centro Médico <span></span></label>
                                            <?php echo $medical_center->name; ?>
                                        </div>
                                        <br />
                                    <?php endif; ?>

                                    <div style="background: #fff;padding: 15px 10px;border-top: 1px solid #ccc;">
                                        <div class="row">
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="submit" 
                                                       class="btn btn-sm btn-primary pull-right" 
                                                       value="Solicitar OC">
                                            </div>
                                        </div>
                                    </div>

                                    <h4 style="background: #eee;text-align: center;border-bottom: 1px solid #ccc;padding: 12px 0;">
                                        <b>Postulantes para "<?php echo $document->name; ?>"</b>
                                    </h4>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <?php if ($document->id == 1 || $document->id == 4): ?>
                                                    <th colspan="2" align="center" style="text-align:center">Asistencia EMPO</th>
                                                <?php endif; ?>
                                                <?php if ($document->id == 2 || $document->id == 4): ?>
                                                    <th colspan="2" align="center" style="text-align:center">Asistencia COVID-19</th>
                                                <?php endif; ?>
                                                <th align="left">DOC</th>
                                                <th align="left">NOMBRE</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($seekers as $seeker_index => $seeker): ?>
                                                
                                                <input type="hidden" 
                                                        name="seekers[<?php echo $seeker_index; ?>][request_seeker_id]" 
                                                        value="<?php echo $seeker->id; ?>">
                                               
                                                <tr> 
                                                    <?php if ($document->id == 1 || $document->id == 4): ?>
                                                        <td width="65"> 
                                                            <select class="form-control select-realized" 
                                                                    name="seekers[<?php echo $seeker_index; ?>][1][realized]"
                                                                    width="100%">
                                                                <option value="">-</option>
                                                                <option value="1">Sí</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </td>
                                                        <td width="120">
                                                            <input type="number" 
                                                                    name="seekers[<?php echo $seeker_index; ?>][1][price]" 
                                                                    class="form-control" 
                                                                    placeholder="Precio" 
                                                                    min="0.00"
                                                                    step="0.01" 
                                                                    value="">
                                                        </td>
                                                    <?php endif; ?>
                                                    <?php if ($document->id == 2 || $document->id == 4): ?>
                                                        <td width="65"> 
                                                            <select class="form-control select-realized" 
                                                                    name="seekers[<?php echo $seeker_index; ?>][2][realized]"
                                                                    width="100%">
                                                                <option value="">-</option>
                                                                <option value="1">Sí</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </td>
                                                        <td width="120">
                                                            <input type="number" 
                                                                    name="seekers[<?php echo $seeker_index; ?>][2][price]" 
                                                                    class="form-control" 
                                                                    placeholder="Precio"
                                                                    min="0.00"
                                                                    step="0.01" 
                                                                    value="">
                                                        </td>
                                                    <?php endif; ?>
                                                    <td align="left" width="120">
                                                        <?php echo document_type_abbr($seeker->document_type) . ' ' . $seeker->document_number; ?>
                                                    </td>
                                                    
                                                    <td align="left">
                                                        <?php echo $seeker->first_name . ' ' . $seeker->last_name; ?>
                                                    </td>

                                                    <td width="80">
                                                        <?php echo date('d/m/Y', strtotime($seeker->exam_date)); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($seekers)): ?>
                                                <tr>
                                                   <td colspan="20" align="center">
                                                        Sin resultados
                                                   </td> 
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php echo form_close(); ?>
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

    <script type="text/javascript">
        $(document).ready(function(){
            var jobId = "<?php echo $job->ID; ?>";

            function updateCounter() {
                $( '#count-seeker-selected' ).text($( '.check-seeker:checked' ).length);
            }

            $( '#form-create-oc' ).submit(function(e){
                
                e.preventDefault();

                if ($( ".select-realized option:selected[value='']" ).length > 0) {
                    toastr["error"]("¡Debe seleccionar todas las asistencias de las pruebas!");
                    $($( ".select-realized option:selected[value='']" ).eq(0).closest('select')).select2('open');
                
                    return false;
                }

                if (!window.confirm('¿Esta seguro de solicitar la OC para los exámenes?')) {
                    return;
                }

                var action = $(this).prop('action');
                var data = $(this).serialize();

                $.post(action, data, function(response) {

                    if (!response.success) {
                        toastr["error"](response.error);
                        return;
                    }

                    var requestId = response.request_id;
                    window.location = "<?php echo site_url('employer/exam_requests/requests/show/'); ?>" + requestId;

                }, 'json')
                .fail(function (){
                    toastr["error"]('¡Ha ocurrido un error al crear la OC!');
                });

                return false;
            });

            $(document).on('change', "#check-seekers", function() {
                $( ".check-seeker" ).prop('checked', $(this).is(':checked'));
                updateCounter();
            });

            $(document).on('change', '.check-seeker', function() {
               updateGuiCheckbox();
            });

            function updateGuiCheckbox() {
                var checkAll = $( '.check-seeker' ).length == $( '.check-seeker:checked' ).length;

                $( '#check-seekers' ).prop('checked', checkAll);
                updateCounter();
            }
/*
            function formatState (state) {
                icons = {
                    0 : 'No', //'<i class="fa fa-thumbs-down" style="color:#9f1010"></i>',
                    1 : 'Si' //'<i class="fa fa-thumbs-up" style="color:green"></i>'
                };

                if (state.id == '') {
                    return state.text;
                }

                return icons[state.id];
            };

            $(".select-realized").select2({
                templateResult: formatState,
                templateSelection: function (option) {
                
                    icons = {
                    0 : 'No', //'<i class="fa fa-thumbs-down" style="color:#9f1010"></i>',
                    1 : 'Si' //'<i class="fa fa-thumbs-up" style="color:green"></i>'
                };

                    if (option.id == '') {
                        return option.text;
                    }

                    return icons[option.id];
	            },
                escapeMarkup: function (m) {
				    return m;
			    }
            });
*/
            $(".select-realized").select2();
            updateGuiCheckbox();
        });
    </script>
</body>
</html>