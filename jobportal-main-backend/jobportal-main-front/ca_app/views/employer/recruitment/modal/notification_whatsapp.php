<div id="modal-notification-ws" class="modal" role="dialog">
    <div class="modal-dialog" style="margin: 0 auto;margin-top: 5%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enviar Notificación</h4>
            </div>
            <div class="modal-body">
                <ul style="list-style: none;">
                    <li style="padding:7px 9px;">
                            <label class="label-check" style="vertical-align: top;">
                            <input type="radio" name="notification_type" class="custom-check" value="document_request">Solicitud de documentos
                        </label>

                    </li>
                    <li style="padding:7px 9px;">
                            <label class="label-check" style="vertical-align: top;">
                            <input type="radio" name="notification_type" class="custom-check" value="gratitude">Agradecimiento
                        </label>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_notification">Enviar</button>
            </div>
        </div>
    </div>
</div>
<script>

    $(function(){
        $( '.open-notification-ws' ) .click(function(){
            if (!$( 'input[name="candidate_ids[]"]:checked' ).length) {
                toastr["error"]('Debe selecionar al menos 1 postulante');
                return;
            }

            $( '#modal-notification-ws' ).modal('show');
        });

        $( "#btn_notification" ).click(function() {
            $( '#btn_notification' ).prop('disabled', true);
            $( '#btn_notification' ).html('Enviando...');

            seekerIds = [];
            inputs = $( 'input[name="candidate_ids[]"]:checked' ).each(function(i, e){
                seekerIds.push($(e).val());
            });

            data = {
                seeker_ids: seekerIds,
                process_id: $( '#global_process_id' ).val(),
                notification_type: $( 'input[name="notification_type"]:checked' ).val()
            };  

            url = "<?php echo site_url('employer/recruitment/jobseeker_notifications/send'); ?>";
            $.post(url, data, function(response){

                if (response.success) {
                    toastr["success"](response.message);
                    $("#modal-notification-ws").modal('hide');
                    return;
                }
                
                toastr["error"](response.message);
            }, 'json')
            .fail(function(){
                toastr["error"]('Error al enviar la notificación')
            }).always(function(){
                $( '#btn_notification' ).prop('disabled', false);
                $( '#btn_notification' ).html('Enviar');
            });
        });
    });
</script>