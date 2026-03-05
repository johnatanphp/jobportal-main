<style type="text/css">
    .msg-info {
      padding: 8px 5px;
      border: 1px solid #07709b;
      border-left: 3px solid #07709b;
      font-size: 15px;
    }

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

    .info-upload-doc {
      padding: 10px 0;
      font-size: 13px;
    }

    .info-upload-doc__info {
      font-weight: bold;
    }
    
    .info-upload-doc__list {
      padding: 5px;
      margin-left: 10px;
    }

  </style>
  
  <div> 
  <!--Detail Info-->
  <div>
    <div>
      <div>
        <div>        
          <div class="formint">
            
            <div class="msg-info">
              Por favor carga el certificado de cada estudio realizado
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
            </div>
            <?php foreach ($result_studies as $study): ?>
              <div style="padding: 10px 5px;border-bottom: 1px solid #ccc;">
                <div class="row">
                  <div class="col-md-5">
                    <div>
                      <h4 style="font-size: 18px;font-weight: bold;"><?php echo $study->major;?></h4>
                      <span><?php echo $study->degree_title;?></span>
                    </div>
                    <div style="font-size: 15px;">
                      <?php echo $study->institude?>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="wrapper-document-load" data-study-id="<?php echo $study->ID; ?>">
                      <div class="document-view" style="border: 1px solid #ccc;background: #fff;padding: 8px; <?php echo $study->attached_certificate ? 'display: block': 'display: none'; ?>">
                        <a class="document-link" 
                           href="<?php echo $study->attached_certificate ? file_url($study->attached_certificate) : ''; ?>"
                           target="_blank">
                          <i class="glyphicon glyphicon-file"></i>
                          Certificado agregado
                          <i class="glyphicon glyphicon-download-alt"></i>
                        </a>
                        <button class="btn btn-primary btn-xs pull-right load-file">Cambiar</button>
                      </div>
                      <div class="document-load" style="<?php echo $study->attached_certificate ? 'display: none': 'display: block'; ?>"">
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

            <?php if (empty($result_studies)): ?>
              <h4 style="text-align: center;">Ningún resulado encontrado</h4>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!--/Job Detail--> 
    </div>
  </div>

  <script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
  
  <script type="text/javascript">

    function showSelectorFiles(elementTarget) {
      
      var mimeTypes = 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document';
      var wrapperDocument = elementTarget.closest('.wrapper-document-load');
      var studyId = wrapperDocument.data('study-id');

      var fileDocument = $( `<input type="file" name="file" style="display: none;" accept="${mimeTypes}">`);
      var url = "<?php echo site_url('employer/recruitment_requested_docs/send_study_certificates/upload'); ?>";
      
      $(fileDocument).fileupload({
        dataType: 'json',
        url: url,
        formData: {
          study_id: studyId,
          seeker_id: "<?php echo $seeker_id; ?>"
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
