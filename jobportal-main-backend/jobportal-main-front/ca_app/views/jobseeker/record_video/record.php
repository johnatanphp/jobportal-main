<!DOCTYPE html>
<html lang="es_PE">
    <head>
        <?php $this->load->view('common/meta_tags'); ?>
        <title>Grabar video</title>
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
            <div class="modal-dialog" style="max-width: 400px;">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Grabar video</h4>
                    </div>
                    <div class="modal-body">
                        <div id="section-1" class="section-record">
                            <div class="alert alert-info">
                                Hola <?php echo $seeker->first_name; ?>, por favor graba y envia el video para poder seguir con el proceso de reclutamiento, dispones de 2 minutos como máximo. 
                            </div>
                            <b style="text-align: center;display: block;">INDICACIONES</b>
                            <br />
                            <div style="background: #eee; color: #666;border: 1px solid #ccc;padding: 10px;">
                                <?php echo nl2br(html_escape($video_interview->indications)); ?>
                            </div>
                            <br />
                            <div align="center">
                                <button id="btn-record-ok" class="btn btn-primary">
                                    Entendido
                                </button>
                            </div>
                        </div>

                        <div id="section-2" class="section-record" style="display: none; margin: 0 auto;">
                            <div>
                                <video muted="muted" id="video" style="width: 100%">
                                    Lo sentimos, tu navegador no posee soporte para el elemento video de html5
                                </video>
                                <br>
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <button id="btn-change-camera" class="btn btn-xs btn-default" style="display: none;">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            <button id="btnComenzarGrabacion" 
                                                    class="btn btn-xs btn-primary">
                                                Iniciar
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <span id="duracion"></span>
                                            <button id="btnDetenerGrabacion" 
                                                    class="btn btn-xs btn-primary"
                                                    style="display: none;">
                                                Detener
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="section-3" class="section-record" style="display: none; margin: 0 auto;">
                            <div>
                                <video id="video2" controls style="width: 100%">
                                    Lo sentimos, tu navegador no posee soporte para el elemento video de html5
                                </video>
                                <div id="detail-description">
                                    <h5>
                                        ¡Grabación terminada, si está seguro de enviarla, haga clic en 'Enviar'!
                                    </h5>
                                </div>
                                <br />
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <a id="download-record"
                                                    download="video.webm" 
                                                    class="btn btn-xs btn-primary"
                                                    style="display: none;">
                                                Descargar
                                            </a>
                                            <button id="btn-record-new"
                                                    class="btn btn-xs btn-primary">
                                                Volver a grabar
                                            </button>
                                            <button id="btn-record-send"
                                                    class="btn btn-xs btn-primary">
                                                Enviar
                                            </button>
                                        </td>
                                    </tr>    
                                </table>
                            </div>
                        </div>

                        <div id="section-success"
                             class="section-record" 
                             align="center"
                             style="display: none;">
                            <h4 style="color: green;">
                                ¡Listo, video enviado con éxito!</h4>
                            <br />
                            <br />
                            <a class="btn btn-primary" 
                               href="<?php echo site_url('jobseeker/dashboard'); ?>">
                                Aceptar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->load->view('common/bottom_ads');?>
        <!--Footer-->
        <?php $this->load->view('common/footer'); ?>
        <?php $this->load->view('common/before_body_close'); ?>

        <script type="text/javascript" src="<?php echo base_url('public/js/app/record_video.js?t=10.4'); ?>"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                window.keyAccess = "<?php echo $video_interview->key_access; ?>";
                $( "#modal-record-video" ).modal('show');

                $( "#btn-record-ok" ).click(function(){
                    $( ".section-record" ).hide();
                    $( "#section-2" ).show();
                    llenarLista();
                });

                $( "#btn-record-send" ).click(function(){
                    enviar(this);
                });
            });
        </script>
    </body>
</html>
