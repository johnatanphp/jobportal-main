<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title;?></title>
  <?php $this->load->view('common/before_head_close'); ?>
  <style type="text/css">

    .input-group-addon {
      display: block;
      padding-left: 0;
    }

    #preview-file img {
      border: 1px solid #ccc;
      padding: 5px;
    }

    .wrapper-document-load {
      margin-top: 10px;
    }

    .btn-back {
      color: #005da4;
      background: #ffffff;
    }
  </style>
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
      <div <?php echo $this->session->userdata('menu') != '0' ? 'class="col-md-3"' : 'class="col-md-2"'; ?>>
        <div class="dashiconwrp">
          <?php if ($this->session->userdata('menu') != '0'): ?>
            <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-9">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="formwraper">
          <div class="titlehead">
            <a href="<?php echo base_url('jobseeker/requested_documents/'); ?>" style="color:#fff;">
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            <b>Agregar Certificados de trabajos</b>
          </div>        
          <div class="formint">
            <div style="text-align: right;padding: 10px 0;">
              <a href="#" class="show-rs-doc-comments" data-document="experience_certificates">
                Ver comentarios recibidos
              </a>  
            </div>
            <div class="msg-info">
              Por favor carga el certificado de cada experiencia laboral
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
              </div>
              <div style="text-align:center; padding: 0 0 10px 0;">
                <a 
                  class="btn btn-primary btn-xs btn-back"
                  href="<?php echo site_url('jobseeker/requested_documents'); ?>"
                >
                  <i class="fa fa-arrow-left" aria-hidden="true"></i> 
                  Regresar
                </a>
              </div>
            </div>
            <?php foreach ($result_experiences as $experience): ?>
              <div style="padding: 10px 5px;border-bottom: 1px solid #ccc;">
                <div class="row">
                  <div class="col-md-6">
                    <div>
                      <h4 style="font-size: 18px;font-weight: bold;"><?php echo $experience->job_title;?></h4>
                      <span><?php echo $experience->job_level; ?></span>
                    </div>
                    <div style="font-size: 15px;">
                      <?php echo $experience->company_name; ?>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="wrapper-document-load" data-experience-id="<?php echo $experience->ID; ?>">
                      <div class="document-view" style="border: 1px solid #ccc;background: #fff;padding: 8px; <?php echo $experience->attached_certificate ? 'display: block': 'display: none'; ?>">
                        <a class="document-link" 
                           href="<?php echo $experience->attached_certificate ? file_url($experience->attached_certificate) : ''; ?>" 
                            target="_blank">
                          <i class="glyphicon glyphicon-file"></i>
                          Certificado agregado
                          <i class="glyphicon glyphicon-download-alt"></i>
                        </a>
                        <button class="btn btn-primary btn-xs pull-right load-file">Cambiar</button>
                      </div>
                      <div class="document-load" style="<?php echo $experience->attached_certificate ? 'display: none': 'display: block'; ?>">
                        <button class="btn btn-primary btn-sm load-file">Agregar certificado</button>
                      </div>
                      <div class="document-loader" style="display: none;">Cargando...</div>
                      <div class="document-error-load" style="text-align: center;display: none;">  
                        <span class="document-error-info" style="color: red;"></span>
                        <a href="#" class="load-file">Volver a intentar</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

            <?php if (empty($result_experiences)): ?>
              <h4 style="text-align: center;">Ningún resulado encontrado</h4>
            <?php endif; ?>
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

    function showSelectorFiles(elementTarget) {
      
      var mimeTypes = 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
      var wrapperDocument = elementTarget.closest('.wrapper-document-load');
      var experienceId = wrapperDocument.data('experience-id');

      var fileDocument = $( `<input type="file" name="file" style="display: none;" accept="${mimeTypes}">`);
      var url = "<?php echo base_url('jobseeker/requested_documents/upload_experience_certificate/'); ?>";
      
      $(fileDocument).fileupload({
        dataType: 'json',
        url: url,
        formData: {
          experience_id: experienceId
        },
        autoUpload: true,
        add: function (e, data) {
          var wrapperDocument = $(e.target).closest('.wrapper-document-load');
          
          $( ".document-view", wrapperDocument).hide();
          $( ".document-loader", wrapperDocument).text('Cargando...').show();
          $( ".document-load", wrapperDocument).hide();
          $( ".document-error-load", wrapperDocument).hide();

          data.context = wrapperDocument;
          data.submit();
        },
        progress: function(e, data) {
          var wrapperDocument = data.context;
          var progress = parseInt(data.loaded / data.total * 100, 10);
          $( ".document-loader", wrapperDocument).text("Cargando (" + progress + "%)" );
        }, 
        done: function (e, data) {
          var wrapperDocument = data.context;
          var error = data.result.error;
          var urlFile = data.result.url_file;
          
          $( ".document-loader", wrapperDocument).hide();

          if (error) {
            $( ".document-error-load", wrapperDocument).show();
            $( ".document-error-info", wrapperDocument).text(error);
            return;
          }

          $( ".document-link", wrapperDocument).prop('href', urlFile);
          $( ".document-view", wrapperDocument).show();
        }
      });

      wrapperDocument.append(fileDocument);
      fileDocument[0].click();
    }

    $( ".load-file" ).click(function() {
      showSelectorFiles($(this));
    });
    
  </script>
  </body>
</html>