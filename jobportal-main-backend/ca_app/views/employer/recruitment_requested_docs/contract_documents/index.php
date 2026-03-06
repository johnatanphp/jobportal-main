<style type="text/css">
    .msg-info {
      padding: 8px 5px;
      border: 1px solid #07709b;
      border-left: 3px solid #07709b;
      font-size: 15px;
    }

    .document-uploaded {
      text-align:center;
      display:block; 
      background: #eee;
      border:1px solid #ccc;
      padding: 5px 20px;
    }
</style>

  <div> 
  <!--Detail Info-->
  <div>
    <div>
      <div>
        <div>        
          <div>
              <div class="msg-info">
                  <div class="info-upload-doc">
                    <span class="info-upload-doc__info">Consideraciones al subir el archivo</span>
                    <ul class="info-upload-doc__list">
                      <li>
                        Los formatos permitidos son: <?php echo join(', ', explode(',', $document->allowed_files)); ?>
                      </li>
                      <li>
                        El documento no puede superar los <?php echo $document->max_size; ?>MB de tamaño.
                      </li>
                    </ul>
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

                <div id="preview-file" class="row" style="text-align:center;<?php echo !$file ? 'display: none;' : ''; ?>">
                    <div class="col-sm-4 col-md-offset-4">
                      <a class="document-uploaded" 
                         href="<?php echo $file ? file_url($file->file_path) : '';?>"
                         target="_blank">
                         <i class="glyphicon glyphicon-file"></i>
                        Ver documento
                      </a>
                    </div>
                    <div class="col-sm-2">
                      <button class="btn btn-primary btn-xs load-file">Cambiar</button>
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
  
  <script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url('public/js/compressor/compressor.min.js'); ?>" type="text/javascript"></script>

  <script type="text/javascript">
    $(function(){

        $( ".load-file" ).click(function() {
        showSelectorFiles();
        });
    });

    function showSelectorFiles() {
      $( "#file-document" ).remove();

      var mime_types = "<?php echo join(',', get_mime_types(explode(',', $document->allowed_files))); ?>";
      var fileDocument = $( `<input id="file-document" type="file" name="file" style="display: none;" accept="${mime_types}">`);

      $(fileDocument).fileupload({
        dataType: 'json',
        url: "<?php echo site_url('employer/recruitment_requested_docs/contract_documents/upload'); ?>",
        formData : {
            'document_id': "<?php e($document->id); ?>",
            'seeker_id': "<?php e($seeker_id); ?>",
        },
        autoUpload: true,
        add: function (e, data) {
          $( "#content-load-file" ).hide();
          $( "#content-error-load-file" ).hide();
          $( "#preview-file" ).hide();
          $( "#loader-file" ).text("Cargando...").show();

          file = data.files[0];

          //Si el archivo es una imagen se comprime
          if (file.type == 'image/png' || 
              file.type == 'image/jpg' || 
              file.type == 'image/jpeg') {
              new Compressor(file, {
                  quality: 0.7,

                  // The compression process is asynchronous,
                  // which means you have to access the `result` in the `success` hook function.
                  success(result) {
                      fileCompress = new File([result], file.name, {lastModified: file.lastModified, type: file.type});
                      data.files[0] = fileCompress;
                      data.submit();
                  },
                  error(err) {
                      console.log(err.message);
                  },
              });
              return;
          }

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
            $( "#error-info-file" ).text(data.result.message);
            return;
          }

          $( "#loader-file" ).html('').hide();
          $( '#preview-file' ).show();
          $( '.document-uploaded', $( '#preview-file' )).prop('href', data.result.file_url);
        }
      });

      $( "#content-load-file" ).append(fileDocument);
      fileDocument[0].click();
    }

  </script>