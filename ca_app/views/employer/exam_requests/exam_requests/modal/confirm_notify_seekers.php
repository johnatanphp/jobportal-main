<!-- Modal -->
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Confirmar envio de notificaciones</h4>
        </div>
        
        <?php echo form_open('employer/exam_requests/requests/notify_candidate', ['id' => 'form-notify-candidate']); ?>
            <input type="hidden" name="notification_type" value="<?php echo $notification_type; ?>">
            <?php foreach ($exam_seekers as $exam_seeker_id): ?>
                <input type="hidden" name="exam_seekers[]" value="<?php echo $exam_seeker_id; ?>">
            <?php endforeach; ?>
                
            <div class="modal-body">
                <?php if ($count_notify == 0): ?>
                    <div class="alert alert-warning">
                        ¡No se encontró ningún candidato disponible para enviarle notificación!
                        <br />
                        <br />
                        <b>Observaciones:</b>
                        <br />
                        - Revise que los postulantes seleccionados tengan una fecha y hora definida.
                        <?php if ($notification_type == 2): ?>
                            <br />
                            - Revise que los postulantes seleccionados tengan el Estado de Reprogramación. 
                        <?php endif; ?>
                    </div>    
                <?php else: ?>
                    <div class="alert alert-info">
                        Se enviaran un total de <?php echo $count_notify; ?> notificaciones.
                    </div>
                    ¿Está seguro de enviar las notificaciones?
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <?php if ($count_notify > 0): ?>
                    <button id="do-send-request" type="submit" class="btn btn-primary">
                        Sí
                    </button>
                <?php endif; ?>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cancelar
                </button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
