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

            .input-group-addon {
                font-weight: bold;
            }

            .form-group span {
                display: block;
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
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if ($assignment_form->edit_answers): ?>
                                        <div style="text-align: right;">
                                            <a href="<?php echo site_url('jobseeker/forms/answer/' . $assignment_form->assignment_id); ?>">Cambiar</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <h3 class="sub-title-h3" style="margin-top: 25px;">Datos personales</h3>
                            <div class="input-group">
                                <label class="input-group-addon">Nombres</label>
                                <span><?php e($jobseeker->first_name); ?></span>
                            </div>
                            <div class="input-group">
                                <label class="input-group-addon">Apellidos</label>
                                <span><?php e($jobseeker->last_name); ?></span>
                            </div>
                            <div class="input-group">
                                <label class="input-group-addon"><?php e(document_type_text($jobseeker->document_type)); ?></label>
                                <span><?php e($jobseeker->document_number); ?></span>
                            </div>

                            <?php $section_id = null; ?>
                            <?php foreach ($form_questions as $question): ?>

                                <?php if ($question->section_id != $section_id): ?>
                                    <?php $section_id = $question->section_id; ?>
                                    <h3 class="sub-title-h3" style="margin-top: 25px;">
                                        <?php e($question->section_name); ?>        
                                    </h3>
                                <?php endif; ?>
                                <?php 
                                    $answer = $this->Form_question->get_answer($assignment_form->assignment_id, $question->question_id);
                                ?>
                                <?php if ($question->question_type == 'text'): ?>
                                    <div class="form-group">
                                        <label> <?php echo $question->question_name; ?> <span></span></label>
                                        <span><?php e($answer && $answer->answer != '' ? $answer->answer : 'Sin responder'); ?></span>
                                    </div>
                                <?php endif;?>

                                <?php if ($question->question_type == 'textarea'): ?>
                                    <div class="form-group">
                                        <label> <?php e($question->question_name); ?> <span></span></label>
                                        <span><?php e($answer && $answer->answer != '' ? $answer->answer : 'Sin responder'); ?></span>
                                    </div>
                                <?php endif;?>

                                <?php if ($question->question_type == 'select'): ?>
                                    <div class="form-group">
                                        <label> <?php e($question->question_name); ?> <span></span></label>
                                        <span><?php e($answer ? $answer->answer : 'Sin responder'); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($question->question_type == 'checkbox'): ?>
                                    <div class="form-group">
                                        <label> <?php e($question->question_name); ?> <span></span></label>
                                        <?php 
                                            $answer_options = $answer ? json_decode($answer->answer) : array();    
                                        ?>
                                        <span ><?php e(count($answer_options) > 0 ? join(', ', $answer_options) : 'Sin responder'); ?></span>
                                    </div>
                                <?php endif;?>

                                <?php if ($question->question_type == 'radio'): ?>
                                    <div class="form-group">
                                        <label> <?php e($question->question_name); ?> <span></span></label>
                                        <span><?php e($answer && $answer->answer != '' ? $answer->answer : 'Sin responder'); ?></span>
                                    </div>
                                <?php endif;?>
                                <?php if ($question->question_type == 'number'): ?>
                                    <div class="form-group">
                                        <label> <?php e($question->question_name); ?> <span></span></label>
                                        <span><?php e($answer && $answer->answer != '' ? $answer->answer : 'Sin responder'); ?></span>
                                    </div>
                                <?php endif;?>
                                <?php if ($question->question_type == 'file'): ?>
                                    <div class="form-group">
                                        <label> <?php e($question->question_name); ?> <span></span></label>
                                        <?php if ($answer && $answer->answer): ?>
                                            <div class="form-control" style="text-align: center;">
                                                <a href="<?php e($answer->answer); ?>" target="_blank">Ver archivo</a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!$answer || empty($answer->answer)): ?>
                                            <span>No cargado</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif;?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <?php $this->load->view('common/before_body_close'); ?>
    </body>
</html>
