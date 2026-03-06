<!DOCTYPE html>
<html lang="es_PE">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title>Video grabado</title>
        <?php $this->load->view('common/before_head_close'); ?>
        <style type="text/css">
            video {
                display: block;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <?php $this->load->view('common/header'); ?>
        <!--/Header-->
    
        <!-- Modal -->
        <div id="modal-record-video" class="modal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" style="width: 800px;">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Video grabado</h4>
                    </div>
                    <div class="modal-body">
                        <div id="section-2" class="section-record">
                            <div>
                                <div class="alert alert-info">
                                    Video grabado por <?php echo strtoupper($seeker->first_name . ' ' . $seeker->last_name); ?> 
                                </div>
                                <br />

                                <div class="row">
                                    <div class="col-md-6">
                                        <video src="<?php echo file_url($video->video_path); ?>" width="100%" controls></video>
                                    </div>
                                    <div class="col-md-6">
                                        <?php 
                                            $config_form = [
                                                'id' => 'form-save-comment'
                                            ];
                                        ?>
                                        <?php echo form_open('general/jobseeker/record_video/save_comment/' . $video->share_token, $config_form); ?>
                                            <label>Calificación</label>
                                            <br />
                                            <select name="qualification" class="form-control" required="true">
                                                <option value="">Seleccione</option>
                                                <option value="1" <?php echo $video->qualification == '1' ? 'selected="selected"' : ''; ?>>Recomendable</option>
                                                <option value="2" <?php echo $video->qualification == '2' ? 'selected="selected"' : ''; ?>>No recomendable</option>

                                                <option value="3" <?php echo $video->qualification == '3' ? 'selected="selected"' : ''; ?>>Alternativo</option>
                                            </select>
                                            <br />
                                            <label>Comentario</label>
                                            <br />
                                            <textarea class="form-control" 
                                                      name="comment"
                                                      rows="5" 
                                                      required="true"><?php echo $video->comment; ?></textarea>
                                            <br />
                                            <button class="btn btn-primary pull-right" type="submit">
                                                Enviar
                                            </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <?php $this->load->view('common/before_body_close'); ?>

        <script type="text/javascript">
            $(function() {
                window.keyAccess = "<?php echo $video->key_access; ?>";
          
                $( "#form-save-comment" ).submit(function(e){
                    e.preventDefault();

                    if (!window.confirm('¿Esta seguro enviar los comentarios sobre esta grabación?')) {
                        return;
                    }

                    var url = $(this).prop('action');
                    var data = $(this).serialize();

                    $.post(url, data, function(response) {

                        if (response.success) {
                            toastr["success"]("¡Comentario enviado!");

                            setTimeout(function(){ window.location = "<?php echo site_url('login'); ?>"; }, 300);

                        } else {
                            toastr["error"]("¡Comentario no pudo ser enviado!");
                        }
                    }, 'json');
                    return false;
                });

                $( "#modal-record-video" ).modal('show');      
            });
        </script>
    </body>
</html>
