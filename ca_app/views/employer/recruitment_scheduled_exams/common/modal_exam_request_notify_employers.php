<div id="modal-exam-request-notify-employers" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <?php echo form_open('employer/scheduled_exams/notify_exam_request_employers', ['method' => 'post']); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Notificar gestión de programaciones</h4>
            </div>
            <div class="modal-body">
                <span>
                    Para notificarle al gestor de programaciones que programe las solicitud de exámenes, haga clic en 'Notificar'.
                </span>
                <input type="hidden" name="job_id" value="<?php echo $job->ID; ?>">

                   
            </div>
            <div class="modal-footer">
                <button 
                    type="submit" 
                    class="btn btn-sm btn-primary">Notificar</button>
                <button 
                    type="button" 
                    class="btn btn-sm btn-default" 
                    data-dismiss="modal">Cancelar</button>
            </div>
        <?php echo form_close(); ?>
    </div>
  </div>
  <script>
    $(function(){

        $( 'form', '#modal-exam-request-notify-employers').submit(function(e){
            e.preventDefault();

            const url = $(this).prop('action');
            const data = $(this).serialize();

            $( '#modal-exam-request-notify-employers .modal-footer button' ).prop('disabled', true);

            $.post(url, data, function(response){

                if (response.success) {
                    toastr["success"](response.message);
                    $( '#modal-exam-request-notify-employers' ).modal('hide');
                    return;
                }

                toastr["error"](response.message);
            }, 'json')
            .fail(function(e){
                toastr["error"]('Ha ocurrido un error, por favor volver a intentar.');
            })
            .always(function(){
                $( '#modal-exam-request-notify-employers .modal-footer button' ).prop('disabled', false);
            });
    
            return false;
        });
    });
  </script>
</div>