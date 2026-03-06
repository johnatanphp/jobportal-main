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
            <a href="#" style="color:#fff;" class="_link-back">
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            <b>Enviar foto carnet</b>
          </div>        
          <div class="formint">

              <div style="text-align: right;padding: 10px 0;">
                <a href="#" class="show-rs-doc-comments" data-document="candidate_photo">
                  Ver comentarios recibidos
                </a>  
              </div>
              <div class="msg-info">
                Por favor carga una foto tipo carnet.
                  <div class="info-upload-doc">
                    <span class="info-upload-doc__info">Consideraciones al subir la foto</span>
                    <ul class="info-upload-doc__list">
                      <li>
                        Buena resolución.
                      </li>
                      <li>
                        Los formatos permitidos son: jpg y jpeg.
                      </li>
                      <li>
                        La foto no puede superar los 4MB de tamaño.
                      </li>
                    </ul>
                  </div>
              </div>
              <br />
              <div>
                <div id="content-load-file" class="row" style="text-align: center; <?php echo $file ? 'display: none;' : ''; ?>">
                  <button type="button" class="btn btn-primary load-file">Cargar foto</button>
                </div>

                <div class="row" style="text-align: center;">
                  <span id="loader-file" style="display: none;">Cargando...</span>
                </div>

                <div id="preview-file" class="row" style="<?php echo !$file ? 'display: none;' : ''; ?>">
                    <div class="col-sm-4 col-md-offset-4">
                      <img id="img-preview" 
                           width="150" 
                           src="<?php echo $file ? file_url($file->file_name, 'public/uploads/candidate/photos') : '';?>" />
                    </div>
                    <div class="col-sm-2">
                      <button class="btn btn-primary load-file">Cambiar</button>
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
      $( "#file-document" ).remove();
      var fileDocument = $( '<input id="file-document" type="file" name="file" style="display: none;">');

      $(fileDocument).fileupload({
        dataType: 'json',
        url: "<?php echo base_url('jobseeker/requested_documents/upload_photo/'); ?>",
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