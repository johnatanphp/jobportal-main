<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title;?></title>
  <?php $this->load->view('common/before_head_close'); ?>
  </head>
  <body>
  <?php $this->load->view('common/after_body_open'); ?>
  <div class="siteWraper">
  <!--Header-->
  <?php $this->load->view('common/header'); ?>
  <!--/Header--> 
  <!--Detail Info-->
  <div class="container detailinfo">
    <div class="row">
      <div class="col-md-3">
        <div class="dashiconwrp">
          <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
        </div>
      </div>
      <div class="col-md-9">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="formwraper">
          <div class="titlehead">
            <a href="<?php echo base_url('jobseeker/requested_documents/'); ?>" style="color:#fff;">
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            <b>Verificación de residencia (Solo para extranjeros)</b>
          </div>        
          <div class="formint">

              <div style="text-align: right;padding: 10px 0;">
                <a href="#" class="show-rs-doc-comments" data-document="residency_verifications">
                  Ver comentarios recibidos
                </a>  
              </div>
              <div class="msg-info">
                Por favor carga la verificación de residencia vigente
                  <div class="info-upload-doc">
                    <span class="info-upload-doc__info">Consideraciones al subir el documento</span>
                    <ul class="info-upload-doc__list">
                      <li>
                        Los formatos permitidos son: .doc, .docx y .pdf
                      </li>
                      <li>
                        El documento no puede superar los 4MB de tamaño.
                      </li>
                    </ul>

                    <div style="margin-top: 5px;">
                      <b>Descarga tu constancia de residencia aquí</b>
                      <br />
                      <a href="https://sel.migraciones.gob.pe/servmig-valreg/VerificarCE" target="_blank">https://sel.migraciones.gob.pe/servmig-valreg/VerificarCE</a>
                    </div>
                    <br />
                    <b>Nota: </b>En caso la constancia de residencia esté por vencer, adjuntar tu constancia de renovación de residencia y la verificación del estado de tu trámite.
                  </div>
              </div>
              <br />
              <div>
                <div id="content-load-file" class="row" style="text-align: center; <?php echo $file ? 'display: none;' : ''; ?>">
                  <button type="button" class="btn btn-primary load-file">Cargar documento</button>
                </div>

                <div class="row" style="text-align: center;">
                  <span id="loader-file" style="display: none;">Cargando...</span>
                </div>

                <div id="preview-file" class="row" style="<?php echo !$file ? 'display: none;' : ''; ?>">
                    <div class="col-sm-12">
                      <div style="background: #efefef;border: 1px solid #ccc;padding: 12px 8px;font-size: 20px;">
                        <div style="font-style: italic; border-bottom: 1px solid #ccc; margin-bottom: 10px;color: #444;font-size: 14px;padding: 5px;">
                          <i style="color:green;" class="glyphicon glyphicon-ok"></i>
                          Verificación de residencia ha sido subido
                          <span style="font-size: 13px;margin-left: 8px;">
                            <a href="#" class="load-file">
                              <i class="glyphicon glyphicon-pencil"></i>
                              Cambiar
                            </a>
                          </span>                          
                        </div>

                        <div style="display: inline-block;border: 1px solid #ccc;background: #fff;padding: 8px;">
                          <a href="<?php echo file_url($file->file_path, 'public/uploads/candidate/residency_verifications'); ?>" target="_blank">
                            <i class="glyphicon glyphicon-file"></i>
                            Verificación de residencia
                            <i class="glyphicon glyphicon-download-alt"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="content-error-load-file" class="row" style="text-align: center;display: none;">  
                    <span id="error-info-file" style="color: red;"></span>
                    <a href="#" class="load-file">Volver a intentar</a>
                  </div>
              </div>
          </div>
        </div>
      </div>
      <!--/Job Detail--> 
    </div>
  </div>
  
  <div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>

  <?php $this->load->view('common/bottom_ads');?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <?php $this->load->view('common/before_body_close'); ?>
  <script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
  
  <script type="text/javascript">

    function showSelectorFiles() {
      var fileDocument = $( '<input type="file" name="file" style="display: none;">');
      var url = "<?php echo site_url('jobseeker/requested_documents/upload_residency_verifications'); ?>";
      
      $(fileDocument).fileupload({
        dataType: 'json',
        url: url,
        autoUpload: true,
        add: function (e, data) {
          $( "#content-load-file" ).hide();
          $( "#content-error-load-file" ).hide();
          $( "#preview-file" ).hide();
          $( "#loader-file" ).text("Cargando...").show();

          data.submit();
        },
        progress: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
           $( "#loader-file" ).text("Cargando (" + progress + "%)" );
        }, 
        done: function (e, data) {
          var success = data.result.success;

          if (!success) {
            $( "#loader-file" ).hide();
            $( "#content-error-load-file" ).show();
            $( "#error-info-file" ).text(data.result.error);
            return;
          }

          window.location.reload();
        }
      });

      $( "#content-load-file" ).append(fileDocument);
      fileDocument[0].click();
    }

    $( ".load-file" ).click(function() {
      showSelectorFiles();
    });
    
  </script>
  </body>
</html>