
<div class="formwraper">
    <div class="formint">
        <h3 style="margin-top: 11px;">Datos personales</h3>
         
        <div style="font-size: 11px;"><b>CLIENTE:</b> <?php echo $staff_request->client_company_name; ?></div>
        
        <div style="font-size: 11px;"><b>CONSULTORA:</b> <?php echo $staff_request->consultant_name; ?></div>

        <div style="font-size: 11px;"><b>PUESTO DE TRABAJO:</b> <?php echo $job->job_title; ?></div>

        <div style="font-size: 11px;"><b>NOMBRE COMPLETO:</b> <?php echo strtoupper($jobseeker->last_name . ' ' . $jobseeker->first_name); ?></div>

        <div style="font-size: 11px;"><b><?php echo mb_strtoupper(document_type_text($jobseeker->document_type)); ?>:</b> <?php echo $jobseeker->document_number; ?></div>
      
        <?php $section_id = null; ?>
        <?php foreach ($form_questions as $question): ?>

            <?php if ($question->section_id != $section_id): ?>
                <?php $section_id = $question->section_id; ?>
                <h3 style="margin-top: 15px;">
                    <?php echo $question->section_name; ?>        
                </h3>
            <?php endif; ?>
            <?php 
                $answer = $this->Form_question->get_answer($assignment_form->assignment_id, $question->question_id);
            ?>
            <?php if ($question->question_type == 'text'): ?>
                <div class="form-group">
                    <div> <?php echo $question->question_name; ?></div>

                    <div class="answer"><?php echo $answer && $answer->answer != '' ? $answer->answer : '-'; ?></div>
                </div>
            <?php endif;?>

            <?php if ($question->question_type == 'textarea'): ?>
                <div class="form-group">
                    <div> <?php echo $question->question_name; ?>: <?php echo $answer && $answer->answer != '' ? $answer->answer : 'Sin respuesta'; ?></div>
                </div>
            <?php endif;?>

            <?php if ($question->question_type == 'select'): ?>
                <div class="form-group">
                    <label> <?php echo $question->question_name; ?> <span></span></label>
                    <span><?php echo $answer ? $answer->answer : 'Sin responder'; ?></span>
                </div>
            <?php endif; ?>

            <?php if ($question->question_type == 'checkbox'): ?>
                <div class="form-group">
                    <div> <?php echo $question->question_name; ?></div>
                    <?php 
                        $answer_options = $answer ? json_decode($answer->answer) : array();    
                    ?>
                    <div class="answer"><?php echo count($answer_options) > 0 ? join(', ', $answer_options) : '-'; ?></div>
                </div>
            <?php endif;?>

            <?php if ($question->question_type == 'radio'): ?>
                <div class="form-group">
                    <?php echo $question->question_name; ?>: <?php echo $answer && $answer->answer != '' ? $answer->answer : '-'; ?>
                </div>
            <?php endif;?>
            <?php if ($question->question_type == 'number'): ?>
                <div class="form-group">
                    <div> <?php echo $question->question_name; ?>: <?php echo $answer && $answer->answer != '' ? $answer->answer : '-'; ?></div>
                </div>
            <?php endif;?>

            <?php if ($question->question_type == 'file'): ?>
                <div class="form-group">
                    <label> <?php echo $question->question_name; ?>: <span></span></label>
                    <?php if ($answer && $answer->answer): ?>
                        <div>
                            <a href="<?php echo $answer->answer; ?>" target="_blank" style="font-size:12px">
                                <?php echo $answer->answer; ?>
                            </a>
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