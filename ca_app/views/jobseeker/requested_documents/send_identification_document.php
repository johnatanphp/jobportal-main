<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <?php $this->load->view('common/before_head_close'); ?>
    <link rel="stylesheet" href="<?php echo base_url('public/css/custom.css'); ?>">
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

          <div style="color: #0D6EFD; font-size: 14px; font-family: Inter; font-weight: 600; line-height: 16px; word-wrap: break-word">
            <a href="#" class="_link-back">
              <span class="glyphicon glyphicon-menu-left"></span><i class="bi bi-chevron-left"></i>
              Volver
            </a>
          </div>
          <div class="new-titlehead">Enviar documento de identidad</div>
          
          <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="custom-card">
              <div class="custom-card-icon">
                <img src="<?php echo base_url('public/images/attach_file.svg');?>" />
              </div>
              <div class="custom-card-title">Adjunta las dos caras de tu documento</div>
              <div class="custom-card-text">Considera que tengan una buena resolución en formato jpg, jpeg y pdf. No superior a los 4MB.</div>
            
              <button type="button" class="custom-card-button" data-toggle="modal" data-target="#file-upload-modal">
                Seleccionar archivo
              </button>
            </div>
          </div>
          <div id="upload-cards-container">
            <?php foreach ($documents as $doc): ?>
                <?php if (!empty($doc->path)): ?>
                    <?php 
                        $safeFileName = preg_replace("/[^a-zA-Z0-9]/", "_", pathinfo($doc->name, PATHINFO_FILENAME));
                    ?>
                    <div class="upload-card" id="<?php echo $safeFileName; ?>-card">
                        <div class="upload-card-header">
                            <div class="icon-wrapper">
                                <div class="upload-icon-container">
                                    <img src="<?php echo base_url('public/images/file-check.svg');?>" />
                                </div>
                                <div class="upload-card-body">
                                    <div class="upload-title"><?php echo substr($doc->name, 0, 20); ?></div>
                                    <div class="upload-subtitle">4 MB</div>
                                    <div class="view-document">
                                        <a class="document-uploaded" href="<?php echo file_url($doc->path); ?>" target="_blank">
                                            Ver documento <i class="glyphicon glyphicon-menu-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="trash-icon" data-toggle="modal" data-target="#file-delete-modal" onclick="setCardIdToDelete('<?php echo $safeFileName; ?>-card')">
                                <img src="<?php echo base_url('public/images/trash.svg');?>" />
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <div class="btn-container-actions">
            <button type="button" class="btn btn-cancel" data-toggle="modal" data-target="#confirm-cancel-upload">Cancelar</button>
            <button type="button" class="btn btn-primary" id="send-document" disabled>Enviar</button>
          </div>

          <!-- Modals -->
          <div class="modal fade" id="file-upload-modal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Seleccionar archivo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Desde donde deseas cargar tu archivo?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link load-file" data-type="<?php echo $doc->doc_id; ?>">Mis documentos</button>
                        <button type="button" class="btn btn-link load-camera" data-type="<?php echo $doc->doc_id; ?>">Cámara</button>
                    </div>
                </div>
            </div>
          </div>

          <div class="modal fade" id="file-delete-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar archivo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro que quieres eliminar este archivo adjunto?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="$('#file-delete-modal').modal('hide')">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="confirm-delete">Si, eliminar</button>
                    </div>
                </div>
            </div>
          </div>

          <div class="modal fade" id="confirm-cancel-upload" tabindex="-1" aria-labelledby="cancel-upload" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Cancelar envío</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro que quieres cancelar este envío?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="$('#confirm-cancel-upload').modal('hide')">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="window.history.back()">Si, cancelar</button>
                    </div>
                </div>
            </div>
          </div>

          <!-- Hidden file inputs -->
          <!-- <input type="file" id="file-input-doc" class="custom-file-input" accept="image/*,application/pdf">
          <input type="file" id="file-input-camera" class="custom-file-input" accept="image/*" capture="camera"> -->

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
    <script src="<?php echo base_url('public/js/compressor/compressor.min.js'); ?>" type="text/javascript"></script>

    <script type="text/javascript">
      let cardIdToDelete;
      var cardCounter = 0;

      function showSelectorFiles(element, typeSelectDocument) {
          var mimeTypes = 'image/jpeg,image/jpg,application/pdf,image/png';
          var fileDocument = (typeSelectDocument === 1) ? $( `<input id="file-document" type="file" name="file" style="display: none;" accept="${mimeTypes}">`) :
            $( `<input id="file-document" type="file" name="file" style="display: none;" capture="camera" accept="${mimeTypes}">`);
          var type = element.data('type');

          fileDocument.fileupload({
              dataType: 'json',
              url: "<?php echo site_url('jobseeker/requested_documents/upload_identification_document/'); ?>" + type,
              autoUpload: true,
              add: function (e, data) {
                  var file = data.files[0];
                  cardCounter++;
                  // Crear identificador
                  var safeFileName = sanitizeFileName(data.files[0].name);
                  safeFileName = 'd' + cardCounter + safeFileName;

                  // Crear y añadir tarjeta
                  var cardHTML = `
                      <div id="${safeFileName}-card" class="upload-card">
                          <div class="upload-card-header">
                              <div class="icon-wrapper">
                                  <div class="upload-icon-container">
                                      <img src="<?php echo base_url('public/images/file-check.svg'); ?>" />
                                  </div>
                                  <div class="upload-card-body">
                                      <div class="upload-title">${file.name.substring(0, 20)}</div>
                                      <div class="upload-subtitle">Cargando...</div>
                                  </div>
                              </div>
                              <div class="trash-icon" data-toggle="modal" data-target="#file-delete-modal">
                                  <img src="<?php echo base_url('public/images/trash.svg'); ?>" />
                              </div>
                          </div>
                          <div class="progress-container">
                              <div id="${safeFileName}-progress" class="progress-bar"></div>
                          </div>
                      </div>`;
                  $('#upload-cards-container').append(cardHTML);

                  // Asignar el evento onclick dinámicamente
                  document.querySelector(`#${safeFileName}-card .trash-icon`).setAttribute('onclick', `setCardIdToDelete('${safeFileName}-card')`);

                  // Cerrar el modal
                  $('#file-upload-modal').modal('hide');

                  if (file.type == 'image/png' || 
                      file.type == 'image/jpg' || 
                      file.type == 'image/jpeg') {
                      new Compressor(file, {
                          quality: 0.7,
                          success(result) {
                              var fileCompress = new File([result], file.name, {lastModified: file.lastModified, type: file.type});
                              data.files[0] = fileCompress;
                              data.submit();

                              document.getElementById('confirm-delete').removeAttribute('disabled');
                          },
                          error(err) {
                              console.log(err.message);
                          },
                      });
                      return;
                  }
                  
                  data.submit();
              },
              progress: function (e, data) {
                  var progress = parseInt(data.loaded / data.total * 100, 10);
                  var safeFileName = sanitizeFileName(data.files[0].name);
                  safeFileName = 'd' + cardCounter + safeFileName;
                  var progressBar = $('#' + safeFileName + '-progress');
                  progressBar.css('width', progress + '%');
                  // Mostrar el porcentaje de carga
                  var card = $('#' + safeFileName + '-card');
                  card.find('.upload-subtitle').text('Cargando... ' + progress + '%');
              },
              done: function (e, data) {
                  if (!data.result.success) {
                      alert(data.result.message);
                      return;
                  }
                  var safeFileName = sanitizeFileName(data.files[0].name);
                  safeFileName = 'd' + cardCounter + safeFileName;
                  var card = $('#' + safeFileName + '-card');

                  // Eliminar barra de progreso
                  card.find('.progress-container').remove();

                  // Actualizar subtítulo con tamaño de documento
                  var fileSize = (data.files[0].size / (1024 * 1024)).toFixed(2) + ' MB';
                  card.find('.upload-subtitle').text(fileSize);

                  // Agregar enlace de documento
                  var viewDocumentHTML = `
                      <div class="view-document">
                          <a class="document-uploaded" href="${data.result.url_file}" target="_blank">
                              Ver documento <i class="glyphicon glyphicon-menu-right"></i>
                          </a>
                      </div>`;
                  card.find('.upload-card-body').append(viewDocumentHTML);
                  document.getElementById('send-document').removeAttribute('disabled');
              }
          });

          $('#upload-cards-container').append(fileDocument);
          fileDocument[0].click();
      }

      function sanitizeFileName(fileName) {
        // Reemplazar dos o más espacios seguidos por un solo espacio
        var sanitized = fileName.replace(/\s{2,}/g, ' ');
        
        // Reemplazar caracteres no alfanuméricos por guion bajo
        sanitized = sanitized.replace(/[^a-zA-Z0-9-]/g, '-');

        // Reemplazar múltiples guiones bajos consecutivos por un solo guion bajo
        sanitized = sanitized.replace(/-+/g, '-');
        
        // Eliminar guiones bajos al principio o al final del nombre
        sanitized = sanitized.replace(/^-+|-+$/g, '');

        // Verificar si el primer carácter es un número
        if (/^\d/.test(sanitized)) {
          sanitized = 'doc-' + sanitized;
        }

        return sanitized;
      }

      function setCardIdToDelete(cardId) {
          cardIdToDelete = cardId;
      }

      $('#confirm-delete').click(function() {
          deleteFileDocument();
          if (cardIdToDelete) {
              $('#' + cardIdToDelete).remove();
              $('#file-delete-modal').modal('hide');
          }
      });
    
      function showSelectorFilesSign(element) {
        var fileDocument = $( '<input type="file" name="file" style="display: none;">');

        $(fileDocument).fileupload({
          dataType: 'json',
          url: "<?php echo base_url('jobseeker/requested_documents/upload_permission_signs_contracts'); ?>",
          autoUpload: true,
          add: function (e, data) {

            var row = element.closest('tr');
            $( "td:eq(1)", row).html('Espere...');

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
            var row = element.closest('tr');

            var progress = parseInt(data.loaded / data.total * 100, 10);
            $( "td:eq(1)", row).html("Cargando (" + progress + "%)" );
          }, 
          done: function (e, data) {

            var row = element.closest('tr');

            var success = data.result.success;

            if (!success) {        
              $( ".load-file-sign", row).text('Intentar de nuevo').show();
              $( "td:eq(1)", row).html(data.result.message);

              return;
            }

            $( "td:eq(1)", row).html(`
              <a class="document-uploaded" 
                href="${data.result.url_file}"
                target="_blank">
                <i class="glyphicon glyphicon-file"></i>
                Ver
              </a>`
            );

            $( ".load-file-sign", row).text('Cambiar').show();
          }
        });

        $( "#content-load-file-sign" ).append(fileDocument);
        fileDocument[0].click();
      }

      function deleteFileDocument() {
        
        $.ajax({
            url: "<?php echo base_url('jobseeker/requested_documents/delete_identification_document'); ?>",
            type: 'POST',
            dataType: "json",
            data: {
                doc_type: 1
            },
            success: function(response) {
                if (response.success) {
                  toastr["success"]("Documento eliminado exitosamente");
                  var documentsCount = <?php echo count($documents); ?>;
                  var newDocumentCount = documentsCount - 1;

                  // Deshabilitar el botón si el conteo de documentos es cero
                  if (newDocumentCount === 0) {
                      document.getElementById('send-document').setAttribute('disabled', 'disabled');
                  }
                } else {
                  toastr["error"](response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              toastr["error"]('Error al intentar eliminar el documento');
            }
        });
      }

      $( ".load-file" ).click(function() {
        showSelectorFiles($(this), 1);
      });

      $( ".load-camera" ).click(function() {
        showSelectorFiles($(this), 2);
      });

      $( ".load-file-sign" ).click(function() {
        showSelectorFilesSign($(this));
      });

      $( "#send-document" ).click(function() {
        toastr["success"]("Documento enviado con éxito");
        document.querySelector('.btn-container-actions').style.display = 'none';
        setTimeout(() => {
          window.history.back();
        }, 2000);
      });

    </script>
  </body>
</html>