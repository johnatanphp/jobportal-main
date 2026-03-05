<div id="modal-exam-request-results" class="modal" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Certificado
                </h4>
            </div>

            <div class="modal-body">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="btn-upload-confirm-exam-request-result" 
                                    type="button" 
                                    class="btn btn-xs btn-primary pull-right" 
                                    style="margin:3px;">
                                Adjuntar
                            </button>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="exam-request-results-list"></div>
            </div>
        </div>
    </div>
</div>

<div id="modal-confirm-upload-exam-request-result" class="modal" role="dialog">
  <style type="text/css">  
    .content-progress-bar {
      background: #ccc;
      display: inline-block;
      width: 100%;
      height: 10px;
    }

    .total-progress-bar {
      background: #52b2ef;
      display: block;
      height: 10px;
      width: 0;
    }

    .item-remove {
      position: absolute;
      right: 7px;
      top: 5px;
      background: transparent;
      border: 0;
      padding: 0;
      margin: 0;
    }
  </style>
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          Certificado 
        </h4>
      </div>
      <div class="modal-body">
        <div>
            <div class="msg-info">
              <div class="info-upload-doc">
                <span class="info-upload-doc__info">Consideraciones al subir los documentos</span>
                <ul class="info-upload-doc__list">
                  <li>
                    Los formatos permitidos son: .jpg, .png, .docx y .pdf
                  </li>
                  <li>
                    El documento no puede superar los 4MB de tamaño.
                  </li>
                </ul>
              </div>
            </div>
            <div>
              <button id="btn-upload-exam-request-result" 
                      type="button" 
                      class="btn btn-primary btn-block" style="margin-top:10px;">
                Cargar
              </button>
            </div>
            <br>
        </div>
        <div class="content-attach-view" style="text-align:center;"></div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(function(){

        function uploadRemove(fileId) { 

            var url = "<?php echo site_url('employer/recruitment/Exam_request_results/upload_remove'); ?>";
            var data = {
                file_id: fileId
            };

            $.post(url, data, function(response){
                var success = response.status;
                if (success) {
                    toastr["success"](response.message);
                    loadExamRequestResults();
                    return;
                }

                toastr["error"](response.message);

            }, 'json').fail(function(){
                toastr["error"]("Error al remover el archivo");
            });
        }

        function loadExamRequestResults() {
        
            var url = "<?php echo site_url('employer/recruitment/exam_request_results/get_files'); ?>";
            var data = {
                job_id: $( "#job_id" ).val(),
                seeker_id: $( '#modal-exam-request-results' ).data('seeker-id'),
                document: $( '#modal-exam-request-results' ).data('rs-document')
            }

            var contentResult = $( '.exam-request-results-list', '#modal-exam-request-results' );

            var url_image_loading = "<?php echo img_loading_url(); ?>";
            contentResult.html(`<img src="${url_image_loading}" style="width:24px; height:24px;"/>`);
            contentResult.css({"text-align": "center"});

            $.post(url, data, function(response){
                contentResult.html(response);
            });
        }

        function showSelectorFiles() {
            $( "#input-attach-file" ).remove();
            var mime_types = ".jpg,.png,.docx,.pdf";
            var inputFile = $(`<input id="input-attach-file" type="file" name="file" style="display:none;" accept="${mime_types}">`);
            $( ".content-attach-files" ).append(inputFile);
            
            $(inputFile).fileupload({
                dataType: 'json',
                url: "<?php echo site_url('employer/recruitment/exam_request_results/upload'); ?>",
                formData: {
                    job_id: $( "#job_id" ).val(),
                    seeker_id: $( '#modal-exam-request-results' ).data('seeker-id'),
                    document: $( '#modal-exam-request-results' ).data('rs-document'),
                },
                autoUpload: true,
                add: function (e, data) {
                    data.context = $( '.content-attach-view' );      
                    data.submit();
                },
                progress: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    data.context.html(`Cargando(${progress}%)`);
                }, 
                done: function (e, data) {
                    var item = data.context;
                    data.context.html("");

                    if (!data.result.status) {
                        toastr["error"](data.result.message);
                        item.remove();
                        return;
                    }

                    toastr["success"](data.result.message);
                    loadExamRequestResults();
                    $( '#modal-confirm-upload-exam-request-result' ).modal('hide');
                }
            });
            
            inputFile[0].click();
        }

        $(document).on("click", ".modal-open-exam-request-results", function() {
            var seekerId = $(this).closest(".wrapper-candidate").data("candidate-id");
            var document = $(this).data('rs-document');
            var documentName = $(this).data('rs-document-name');

            $( '#modal-exam-request-results .modal-title' ).html(documentName);
            $( '#modal-exam-request-results' ).modal('show');
            $( '#modal-exam-request-results' ).data('seeker-id', seekerId);
            $( '#modal-exam-request-results' ).data('rs-document', document);
            $( '#modal-exam-request-results' ).data('rs-document-name', documentName);

            loadExamRequestResults();
        });

        $(document).on('click', '.exam-request-upload-remove', function(){
            var fileId = $(this).data('file-id');
            uploadRemove(fileId);
        });

        $( '#btn-upload-confirm-exam-request-result' ).click(function(){
            documentName = $( '#modal-exam-request-results' ).data('rs-document-name');

            $( '#modal-confirm-upload-exam-request-result .modal-title' ).html(documentName);
            $( '#modal-confirm-upload-exam-request-result' ).modal('show');
        });

        $( '#btn-upload-exam-request-result' ).click(function(){
            showSelectorFiles();
        });
    });
</script>

