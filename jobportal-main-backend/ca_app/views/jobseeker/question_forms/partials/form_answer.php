<style type="text/css">
    .form-group span {
        display: block;
    }

    .incorrect {
        color: red;
    }

    .correct {
        color: green;
    }
</style>
<div class="formwraper">
    <div class="formint">
        <?php if($this->session->userdata('is_employer') === true && ($form->form_id == 1 || $form->form_id == 3) && @$assignment_form->active && @$assignment_form->answered): ?>
            <a href="<?php echo site_url('employer/rys_form_affidavit_seekers/export_pdf/' .  $assignment_form->assignment_id . '/' . $job_id); ?>"
               target="_blank">
                <span class="glyphicon glyphicon-download-alt"></span>
                Descargar Declaración jurada
            </a>
        <?php endif; ?>
        <?php if (!$assignment_form || $assignment_form->active == 0): ?>
            <h4 style="text-align: center;">¡Formulario no ha sido asignado a este postulante!</h4>
        <?php elseif ($assignment_form->answered == 0): ?>
            <h4 style="text-align: center;">¡Formulario no respondido todavía!</h4>
        <?php else: ?>

            <table width="100%">
                <tr>
                    <?php if($this->session->userdata('is_job_seeker') !== true && $form->form_id == 1): ?>
                        
                        <td align="right"><b>Candidato: </b> <?php echo $this->Form_question->candidate_is_fit($assignment_form->assignment_id) ? 'Apto' : 'Con riesgo'; ?></td>
                          
                    <?php endif; ?>

                    <?php if($this->session->userdata('is_job_seeker') !== true && $form->form_id == 2): ?>
                        <td align="right"><b>Puntaje: </b> 
                            <?php 
                                $score = $this->Form_question->total_score($assignment_form->assignment_id);
                            ?>
                            <?php echo $score . '/20'; ?>
                        </td>
                    <?php endif; ?>


                    <?php if($this->session->userdata('is_job_seeker') !== true && $form->form_id == 3): ?>
                        <td align="right"><b>Resultado: </b> 
                            <?php 
                                $seeker_id_good = $this->Form_question->candidate_is_good($assignment_form->assignment_id);
                            ?>
                            <?php echo $seeker_id_good ? 'Apto' : 'Observada'; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            </table>
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
                        <label> <?php e($question->question_name); ?> <span></span></label>

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
                        <?php 
                            $class_question = "";
                            if ($this->session->userdata('is_job_seeker') !== true && $question->answer != null) {
                                $is_correct = $this->Form_question->is_correct($assignment_form->assignment_id, $question->question_id); 
                                $class_question = $is_correct ? 'correct' : 'incorrect';
                            }
                        ?>
                        <span class="<?php echo $class_question; ?>"><?php e($answer ? $answer->answer : 'Sin responder'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($question->question_type == 'checkbox'): ?>
                    <div class="form-group">
                        <label> <?php e($question->question_name); ?> <span></span></label>
                        <?php 
                            $answer_options = $answer ? json_decode($answer->answer) : array();    
                        ?>
                        <span ><?php echo count($answer_options) > 0 ? join(', ', $answer_options) : 'Sin responder'; ?></span>
                    </div>
                <?php endif;?>

                <?php if ($question->question_type == 'radio'): ?>
                    <div class="form-group">
                        <label> <?php e($question->question_name); ?> <span></span></label>
                        <?php 
                            $class_question = "";
                            if ($this->session->userdata('is_job_seeker') !== true && $question->answer != null) {
                                $is_correct = $this->Form_question->is_correct($assignment_form->assignment_id, $question->question_id); 
                                $class_question = $is_correct ? 'correct' : 'incorrect';
                            }
                        ?>
                        <span class="<?php echo $class_question;?>"><?php e($answer && $answer->answer != '' ? $answer->answer : 'Sin responder'); ?></span>
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
                                <a href="<?php echo $answer->answer; ?>" target="_blank">
                                    Ver Archivo
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (!$answer || empty($answer->answer)): ?>
                            <span>No cargado</span>
                        <?php endif; ?>
                    </div>
                <?php endif;?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>