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
        Otros documentos
      </h4>
    </div>
    <div class="modal-body">
      <div>
        <div class="msg-info">
          <div class="info-upload-doc">
            <span class="info-upload-doc__info">Consideraciones al subir los documentos</span>
            <ul class="info-upload-doc__list">
              <li>
                El documento no puede superar los 4MB de tamaño.
              </li>
            </ul>
          </div>
        </div>
        <div>
          <label>Título del documento</label>
          <input id="title-doc" type="text" name="title_doc" class="form-control" placeholder="Título del documento">
        </div>
        <div>
          <button id="btn-attach-other-files" type="button" class="btn btn-primary btn-block" style="margin-top:10px;">
            Cargar documentos
          </button>
        </div>
        <label style="padding: 10px 0;">
          Documentos cargados
        </label>
      </div>
      <div class="content-attach-files">
        <?php foreach ($attached_files as $index => $file): ?>
          <div class="attach-file-item"> 
            <div class="row">
              <div class="col-md-12">
                <a href="<?php echo file_url($file->file_source, 'public/uploads/employer/recruitment_selection_documents'); ?>" target="_blank">
                  <?php echo $file->document_title . ' - ' . $file->name; ?>
                </a>
              </div> 
              <div class="col-md-12"></div>
            </div>
            <button class="item-remove" data-file-id="<?php echo $file->file_ID; ?>">
              <i class="glyphicon glyphicon-remove"></i>
            </button>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  
  function showSelectorFiles() {
    
    var document_title = $.trim($( "#title-doc" ).val());

    if (document_title == '') {
      alert("¡Por favor ingresa un título!");
      return;
    }

    $( "#input-attach-file" ).remove();
    var inputFile = $('<input id="input-attach-file" type="file" name="file" multiple style="display:none;">');
    $( ".content-attach-files" ).append(inputFile);
    
    $(inputFile).fileupload({
      dataType: 'json',
      url: "<?php echo base_url('employer/recruitment_attached_documents/upload_other_document'); ?>",
      formData: {
        job_id: "<?php echo $job_id; ?>",
        candidate_id: "<?php echo $candidate_id; ?>",
        stage: "<?php echo $stage; ?>",
        document_title: document_title
      },
      autoUpload: true,
      add: function (e, data) {
        var fileName = data.files[0].name;
        var item = $('<div class="attach-file-item">' + 
                       '<div class="row">' + 
                            '<div class="col-md-12 content-filename">Cargando...</div>' + 
                            '<div class="col-md-12">' + 
                                '<span class="content-progress-bar">' +
                                    '<span class="total-progress-bar"></span>' +
                                '</span>' +
                            '</div>' +
                        '</div>' +
                        '<button class="item-remove">' +
                            '<i class="glyphicon glyphicon-remove"></i>' +
                        '</button>' +
                    '</div>');

        $( ".content-attach-files" ).append(item);
        data.context = item;      
        data.submit();
      },
      progress: function(e, data) {
        var progressBar = $( ".total-progress-bar", data.context);
        var progress = parseInt(data.loaded / data.total * 100, 10);
        progressBar.width(progress + "%");
      }, 
      done: function (e, data) {

        var item = data.context;

        if (data.result.error) {
          toastr["error"](data.result.error);
          item.remove();
          return;
        }

        var doc_title = data.result.document_title;
        var originalFileName = data.result.original_file_name;
        var fileId = data.result.file_id;
        var urlFile = data.result.url_file;

        var linkFile = '<a href="' + urlFile + '" target="_blank" >' + doc_title + ' - ' + originalFileName + '</a>';
        $( ".content-filename", item).html(linkFile);

        $( ".content-progress-bar", item).remove();
        $( ".item-remove", item).data('file-id', fileId).show();
      }
    });
    
    inputFile[0].click();
  }

  function removeFile(fileId, item) { 
    var url = "<?php echo base_url('employer/recruitment_attached_documents/remove_other_file'); ?>";
    var data = {
      file_id: fileId
    };

    $.post(url, data, function(response){
      var success = response.success;
      if (success) {
        item.remove();
      } else {
        alert("Error al remover el archivo");  
      }
    }, 'json').fail(function(){
      alert("Error al remover el archivo");
    });
  }

  $( "#btn-attach-other-files" ).click(function(){
    showSelectorFiles();
  });

  $(document).off('click', '.item-remove').on('click', '.item-remove', function(){
    var item = $(this).closest('.attach-file-item');
    var fileId = $(this).data('file-id');
    removeFile(fileId, item);
  });
</script>
