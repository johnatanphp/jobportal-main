<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Video</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">     
                            <?php if ($massive == 0): ?>
                                <?php if (!$video_interview): ?>
                                    <div class="alert alert-info">
                                        <strong>Solicitud sin enviar</strong> ¡Por favor envie la solicitud de grabación al postulante!
                                    </div>
                                <?php endif; ?>

                                <?php if ($video_interview && !$video_interview->video_path): ?>
                                    <div class="alert alert-info">
                                        <strong>Solicitud enviada</strong> ¡Video no ha sido grabado por el postulante!
                                    </div>
                                <?php endif; ?>

                                <?php if (!$video_interview  || !@$video_interview->video_path): ?>
                                    <?php echo form_open(site_url('employer/recruitment_interviews/request_video'), [
                                            'id' => 'form-request-seeker-video'
                                        ]); 
                                    ?>
                                        <?php foreach ($seeker_ids as $seeker_id): ?>
                                            <input type="hidden" name="seeker_ids[]" value="<?php echo $seeker_id; ?>">
                                        <?php endforeach; ?>

                                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
            
                                        <label>Indicaciones</label>
                                        <?php 
                                            $rv_indications = $rv_indications->video_indications ?? '';
                                            $video_indications = $video_interview ? $video_interview->indications : $rv_indications;
                                        ?>
                                        <textarea id="text-indications" class="form-control" rows="8" name="indications"><?php echo $video_indications; ?></textarea>
                                        <br >
                                        <button id="btn-request-video" 
                                                class="btn btn-primary pull-right">
                                            Solicitar video
                                        </button>
                                    <?php echo form_close(); ?>
                                <?php endif; ?>

                                 <?php if ($video_interview && $video_interview->video_path): ?>
                                    <button id="btn-share-video-by-email"
                                            class="btn btn-xs btn-primary pull-right modal-open-share-video-email"
                                            data-seeker-id="<?php echo $video_interview->seeker_id; ?>">
                                        Compartir por correo
                                    </button>
                                    <br />
                                    <div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="attach-file-item">
                                                    <a href="<?php echo file_url($video_interview->video_path); ?>" target="_blank">
                                                        <i class="glyphicon glyphicon-play-circle"></i>
                                                        Video subido
                                                    </a>
                                                    <a href="#" 
                                                   class="remove-interview-video" 
                                                   data-id="<?php echo $video_interview->id; ?>"
                                                   style="position: absolute;right: 5px;padding: 0 5px;color: red;background: transparent;border: none;">x</a>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Calificación</label>
                                                        <br />

                                                        <?php 
                                                            $qualification_status = [
                                                                1 => 'Recomendable',
                                                                2 => 'No recomendable',
                                                                3 => 'Alternativo'
                                                            ];
                                                        ?>

                                                        <?php 

                                                            echo isset($qualification_status[$video_interview->qualification]) ? $qualification_status[$video_interview->qualification] : ' - ';
                                                        ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Comentarios</label>
                                                        <br />
                                                        <?php if ($video_interview->comment != null): ?>
                                                            <?php echo nl2br($video_interview->comment); ?>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($massive == 1): ?>
                                <?php echo form_open(site_url('employer/recruitment_interviews/request_video'), [
                                    'id' => 'form-request-seeker-video'
                                    ]); ?>

                                    <?php foreach ($seeker_ids as $seeker_id): ?>
                                        <input type="hidden" name="seeker_ids[]" value="<?php echo $seeker_id; ?>">
                                    <?php endforeach; ?>

                                    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                                    <div class="alert alert-info">
                                        ¡Por favor envie la solicitud de grabación a los postulantes seleccionados!
                                    </div>
                                    <label>Indicaciones</label>
                                    <textarea id="text-indications" class="form-control" rows="8" name="indications"><?php echo @$rv_indications->video_indications; ?></textarea>
                                    <br >
                                    <button id="btn-request-video" 
                                            class="btn btn-primary pull-right">
                                        Solicitar video
                                    </button>
                                <?php echo form_close(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $( "#form-request-seeker-video" ).submit(function(e) {
            
            e.preventDefault();

            if ($.trim($( "#text-indications" ).val()) == '') {
                toastr["error"]("¡Por favor agregue una indicación!");
                return;
            }

            if (!window.confirm("¿Desea enviar la notificación?")) {
                return;
            }

            var url = $(this).prop('action');
            var data = $(this).serialize();

            var btnRequest = $(this);
            btnRequest.prop('disabled', true);
 
            $.post(url, data, function(response) {
                var status = response.success;
                if (status) {
                    toastr["success"]("Solicitud de video enviada!");
                    $( "#modal-seeker-video" ).modal('hide');
                } else {
                    toastr["error"]("¡Ocurrio un error al enviar la solicitud!");
                }
            }, 'json')
            .fail(function(){
                alert("¡Ha ocurrido un error!");
            }).always(function(){
                btnRequest.prop('disabled', false);
            });

            return false;
        });
    })
</script>