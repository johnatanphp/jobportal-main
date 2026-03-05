<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title><?php echo $title;?></title>
        <?php $this->load->view('common/before_head_close'); ?>
        <style>

            .formwraper .formint .btn-rc-action {
                background: #e0e0e0;
                border: 1px solid #bbb;
            }
            
            input:focus {
                outline: none !important;
                outline-width: 0 !important;
                box-shadow: none;
                -moz-box-shadow: none;
                -webkit-box-shadow: none;
            }            

            input:invalid, textarea:invalid, select:invalid {
                _border: 1px solid red;
            }

            .form-radio label, 
            .form-checkbox label {
                font-weight: normal;
            } 

            .tbl-input-radio td {
                vertical-align: top;
                padding: 1.5px 0;
            }

            .tbl-attach-file tr td {
                padding: 4px;
            }
        </style>
    </head>
    <body>
        <?php $this->load->view('common/after_body_open'); ?>
        <!--<div class="siteWraper">-->
        <!--Header-->
        <?php $this->load->view('common/header'); ?>
        <!--/Header-->
        <div class="container detailinfo">
            <div class="row"> 
                <?php echo form_open_multipart('jobseeker/forms/do_answer/', []); ?>
                    <input type="hidden" name="assignment_id" value="<?php echo $assignment_form->assignment_id; ?>">
                    <div class="col-md-3">
                        <div class="dashiconwrp wrap_enabled">
                            <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
                        </div>
                    </div>
                    <div class="col-md-9">  
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="formwraper">
                            <div class="titlehead">
                                <a href="#" style="color:#fff;" class="_link-back">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                                <?php e($form->name); ?>
                            </div>
                            <div class="formint">  
                                <?php if ($assignment_form->answered == 0 || $assignment_form->edit_answers  == 1): ?>       
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 style="text-align:center;text-transform: uppercase;font-size: 15px;">
                                                <b>¡Por favor responde las preguntas del siguiente formulario!</b></h4>
                                        </div>
                                    </div>
                                    <br />
                                    <br />
                                <?php endif; ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span style="font-size: 12px;">
                                            <?php echo $form->header; ?>
                                        </span>
                                    </div>
                                </div>
                                <?php if ($form->form_id == 1): ?>
                                    <hr />
                                    <h3 class="sub-title-h3" style="margin-top: 25px;">Datos personales</h3>
                                    <div class="input-group">
                                        <label class="input-group-addon">Nombres</label>
                                        <span><?php echo e($jobseeker->first_name); ?></span>
                                    </div>
                                    <div class="input-group">
                                        <label class="input-group-addon">Apellidos</label>
                                        <span><?php echo e($jobseeker->last_name); ?></span>
                                    </div>
                                    <div class="input-group">
                                        <label class="input-group-addon"><?php echo e(document_type_text($jobseeker->document_type)); ?></label>
                                        <span><?php echo e($jobseeker->document_number); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($form->form_id == 2): ?>
                                    <hr />
                                    <div style="text-align: center">
                                        Hola <b><?php echo e($jobseeker->first_name); ?></b>, necesitamos hacerte una evaluación corta, por favor ingresa a ver el siguiente <a href="https://youtu.be/HVh5qW1ghcE" target="_blank">video</a>  y luego responde las
                                        preguntas de abajo.
                                        <br />
                                        <br />

                                        <a href="https://youtu.be/HVh5qW1ghcE" target="_blank" class="btn btn-primary">Ver video</a>    
                                    </div>
                                <?php endif; ?>
                                <?php $section_id = null; ?>
                                <?php foreach ($form_questions as $question): ?>
                                    <?php if ($question->section_id != $section_id): ?>
                                        <?php $section_id = $question->section_id; ?>
                                        <h3 class="sub-title-h3" style="margin-top: 25px;">
                                            <?php echo e($question->section_name); ?>        
                                        </h3>
                                    <?php endif; ?>
                                    <?php 
                                        $answer = $this->Form_question->get_answer($assignment_form->assignment_id, $question->question_id);
                                    ?>
                                    <?php if ($question->question_type == 'text'): ?>
                                        <div class="form-group">
                                            <label> <?php echo e($question->question_name); ?> <span></span></label>
                                            <input 
                                                name="form_question[<?php echo $question->question_id; ?>]" 
                                                type="text" 
                                                class="form-control"  
                                                value="<?php echo $answer ? e($answer->answer) : ''; ; ?>" 
                                                <?php echo $question->required ? 'required' : ''; ?>
                                            >
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($question->question_type == 'textarea'): ?>
                                        <div class="form-group">
                                            <label> <?php echo e($question->question_name); ?> <span></span></label>
                                            <textarea 
                                                name="form_question[<?php echo $question->question_id; ?>]" 
                                                class="form-control" 
                                                <?php echo $question->required ? 'required' : ''; ?>
                                            ><?php echo $answer ? e($answer->answer) : ''; ?></textarea>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($question->question_type == 'number'): ?>
                                        <div class="form-group">
                                            <label> <?php echo $question->question_name; ?> <span></span></label>
                                            <?php 
                                                $json_options = $question->options ? json_decode($question->options) : new stdClass;

                                                if ($question->question_id == 3) {

                                                    $json_options->step = "1";
                                                    $json_options->min = "30";
                                                    $json_options->max = "150";
                                                } 

                                                if ($question->question_id == 4) {

                                                    $json_options->step = "0.01";
                                                    $json_options->min = "1";
                                                    $json_options->max = "2.10";
                                                }   
                                            ?>
                                            <input 
                                                name="form_question[<?php echo $question->question_id; ?>]" 
                                                type="number" 
                                                class="form-control"
                                                value="<?php e($answer ? $answer->answer : '');  ?>" 
                                                <?php echo $question->required ? 'required' : ''; ?>
                                                step="<?php e(isset($json_options->step) ? $json_options->step : ""); ?>"
                                                min="<?php e(isset($json_options->min) ? $json_options->min : ""); ?>"
                                                max="<?php e(isset($json_options->max) ? $json_options->max : ""); ?>"
                                            >
                                        </div>
                                    <?php endif; ?>
            
                                    <?php if ($question->question_type == 'select'): ?>
                                        <div class="form-group">
                                            <label> <?php e($question->question_name); ?> <span></span></label>

                                            <select 
                                                name="form_question[<?php echo $question->question_id; ?>]" 
                                                class="form-control" 
                                                <?php echo $question->required ? 'required' : ''; ?>>

                                                <option value="">Seleccione</option>
                                                <?php 
                                                    $json_options = json_decode($question->options);
                                                    $options = $json_options->options;
                                                ?>
                                                <?php foreach ($options as $key => $option): ?>
                                                    <option value="<?php e($option->value); ?>" <?php echo ($answer ? $answer->answer : '') == $option->value ? 'selected="selected"' : ''; ?>>
                                                        <?php echo $option->value; ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($question->question_type == 'checkbox'): ?>
                                        <div class="form-group">
                                            <label> <?php echo $question->question_name; ?> <span></span></label>
                                            <?php 
                                                $json_options = json_decode($question->options);
                                                $options = $json_options->options;
                                                $answer_options = $answer && json_decode($answer->answer) ? json_decode($answer->answer) : [];  
                                            ?>
                                            <div class="form-checkbox">
                                                <?php foreach ($options as $key => $option): ?>
                                                    <input 
                                                        type="checkbox" 
                                                        name="form_question[<?php echo $question->question_id; ?>][]" 
                                                        value="<?php e($option->value); ?>"  
                                                        <?php echo in_array($option->value, $answer_options) ? 'checked="checked"' : '';?>
                                                        class="<?php echo $question->required ? 'checkbox-required' : ''; ?>"
                                                    >
                                                    <label><?php e($option->value); ?></label>&nbsp;
                                                    <br />
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    <?php endif;?>

                                    <?php if ($question->question_type == 'radio'): ?>
                                        <div class="form-group">
                                            <label> <?php e($question->question_name); ?> <span></span></label>
                                            
                                            <?php 
                                                $json_options = json_decode($question->options);
                                                $options = $json_options->options;
                                            ?>
                                            <div class="form-radio">
                                                <?php foreach ($options as $key => $option): ?>

                                                    <table class="tbl-input-radio" width="100%">
                                                        <tr>
                                                            <td width="20">
                                                                <input
                                                                    id="<?php echo $question->question_id . $key; ?>" 
                                                                    type="radio" 
                                                                    name="form_question[<?php echo $question->question_id; ?>]" 
                                                                    value="<?php e($option->value); ?>" 
                                                                    <?php echo ($answer ? $answer->answer : '') == $option->value ? 'checked="checked"' : '';?>
                                                                    <?php echo $question->required ? 'required' : ''; ?>
                                                                >
                                                            </td>
                                                            <td>
                                                                <label for="<?php echo $question->question_id . $key; ?>">
                                                                    <?php echo e($option->value); ?>        
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($question->question_type == 'file'): ?>
                                        <div class="form-group">
                                            <label> <?php e($question->question_name); ?> <span></span></label>

                                            <table width="100%" class="tbl-attach-file">
                                                <?php if ($answer && $answer->answer): ?>
                                                    <tr>
                                                        <td>
                                                            <div style="text-align:center; border: 1px solid #ccc;padding: 10px;">
                                                                <a href="<?php e($answer->answer) ?>" target="_blank">Ver archivo</a>
                                                            </div>
                                                        </td>
                                                        <td width="60">
                                                            <button type="button" class="edit-attach-file">
                                                                <i class="glyphicon glyphicon-pencil"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr style="display: none;">
                                                        <td colspan="2">
                                                            <div class="row">
                                                                <div class="col-xs-7">
                                                                    <div>- Cargue un archivo si desea reemplazar la actual carga.</div> 
                                                                    <?php if (!$question->required): ?>
                                                                        <div>- Para quitar el actual archivo no suba ningún archivo.</div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-xs-5" style="text-align: right;">
                                                                    <a href="#" class="cancel-edit-attach-file" style="display: inline-block;">
                                                                        Cancelar Edición
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                                
                                                <tr style="display: <?php echo $answer && $answer->answer ? 'none' : 'table-row';?>">
                                                    <td>
                                                        <input 
                                                            name="form_question[<?php echo $question->question_id; ?>]" 
                                                            type="file" 
                                                            class="form-control attach-file-input"
                                                            data-required="<?php echo $question->required; ?>" 
                                                            <?php echo $question->required && (!$answer || !$answer->answer) ? 'required' : ''; ?>
                                                        >
                                                        <input 
                                                            name="form_question[<?php echo $question->question_id; ?>]" 
                                                            type="hidden" 
                                                            class="form-control"
                                                            value="<?php e($answer ? $answer->answer : ''); ?>"
                                                        >
                                                    </td>
                                                    <?php if (!$question->required): ?>
                                                        <td width="60" style="display: none;">
                                                            <button type="button" class="remove-attach-file" title="Quitar adjunto">
                                                                <i class="glyphicon glyphicon-remove"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <span style="font-size: 12px;">
                                            <?php echo $form->footer; ?>
                                        </span>
                                    </div>
                                </div>
                                <div align="center" style="margin-top: 20px;">
                                    <input type="submit" name="submit_button" id="submit_button" value="Enviar" class="btn btn-primary" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/Job Detail--> 
                <?php echo form_close();?>
            </div>
        </div>
        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <?php $this->load->view('common/before_body_close'); ?>
        <script type="text/javascript">
            
            $( '.remove-attach-file' ).click(function(){
                tr = $(this).closest('tr');
                file = tr.find('.attach-file-input');
                $(file).val('');
                $(this).closest('td').hide();
            });

            $( '.attach-file-input' ).change(function(){
                if ((this.files[0].size / 1024 / 1024) > 2) {
                    $(this).val('');
                    toastr["error"]("No puede subir archivos mayor a 2MB");
                    return;
                }
                tr = $(this).closest('tr');
                tr.find('td:eq(1)').show();
            });

            $( '.edit-attach-file' ).click(function(){
                table = $(this).closest('table');
                table.find('tr:eq(0)').hide();
                table.find('tr:eq(1)').show();
                table.find('tr:eq(2)').show();

                file = table.find('.attach-file-input');
                $(file).prop('required', $(file).data('required') ? true : false);
            });

            $( '.cancel-edit-attach-file' ).click(function(){
                table = $(this).closest('table');
                table.find('tr:eq(0)').show();
                table.find('tr:eq(1)').hide();
                table.find('tr:eq(2)').hide();

                table.find('.remove-attach-file').click();

                file = table.find('.attach-file-input');
                $(file).val('');
                $(file).removeAttr('required');
            });

            $( '.checkbox-required' ).change(function() {
                var check_selected = $( '.checkbox-required:checked' ).length;
                
                if (check_selected == 0) {
                    $( '.checkbox-required' ).prop('required', true);
                } else {
                    $( '.checkbox-required' ).removeAttr("required");
                }
            });

            $( '.checkbox-required' ).change();
        </script>
    </body>
</html>
