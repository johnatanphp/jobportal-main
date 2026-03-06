<div class="modal-dialog" style="min-width: 50%;">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">

            <?php if ($rs_process->sts == 'active' && 
                      $assignment_form && 
                      $assignment_form->active): ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                            $form_seeker_is_expired = $this->rys_seeker_fit_helper->form_is_expired(
                                $assignment_form->form_id,
                                $assignment_form->seeker_id
                            );
                        ?>
                        <?php if ($form_seeker_is_expired): ?>
                            <h4>¡Las respuestas del formulario ha expirado, este formulario debe responderse de nuevo!</h4>    
                        <?php endif; ?>

                        <?php 
                            $enable_notify = $assignment_form->edit_answers == 1 || $assignment_form->answered == 0;
                        ?>
                        <button id="btn-notify-form-answer" class="btn btn-primary btn-xs pull-right" data-id="<?php echo $assignment_form->assignment_id; ?>" style="display: <?php echo ($enable_notify ? 'inline-block;' : 'none;');?>">
                            Volver a notificar
                        </button>
   
                        <?php if ($assignment_form->edit_answers == 0 && ($assignment_form->answered || $form_seeker_is_expired)): ?>
                            <button id="btn-change-form-answer" class="btn btn-primary btn-xs pull-right" data-id="<?php echo $assignment_form->assignment_id; ?>" style="margin-right: 10px;">
                                Habilitar edición
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <br />
        
            <?php $this->load->view('jobseeker/question_forms/partials/form_answer.php'); ?>
    
        </div>
    </div>
</div>
<script type="text/javascript">
    
    $( "#btn-change-form-answer" ).click(function() {
        
        var url = "<?php echo site_url('employer/rys_form_seekers/edit_answers'); ?>"
        var data = {
            id : $(this).data('id'),
            job_id: "<?php echo $job_id; ?>"
        };
        var btn = $(this);

        $.post(url, data, function(response) {

            if (response.success) {
                toastr["success"]("¡Edición habilitada!");  
                btn.remove();
                $( "#btn-notify-form-answer" ).show();
            } else {
                toastr["error"]("¡Error al habilitar la edición de respuestas!");
            }

        }, 'json');
    });

    $( "#btn-notify-form-answer" ).click(function(){

        var url = "<?php echo site_url('employer/rys_form_seekers/notify_to_seeker'); ?>"
        var data = {
            id : $(this).data('id'),
            job_id: "<?php echo $job_id; ?>"
        };
        var btn = $(this);

        $.post(url, data, function(response) {

            if (response.success) {
                toastr["success"]("¡Notificación enviada!");  
            } else {
                toastr["error"]("¡Error al enviar la notificación!");
            }

        }, 'json');
    });

</script>
