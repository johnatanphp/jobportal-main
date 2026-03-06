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
        <?php echo $document->name; ?>
      </h4>
    </div>
    <div class="modal-body">
      <div>
          <div class="msg-info">
            <div class="info-upload-doc">
              <span class="info-upload-doc__info">Consideraciones al subir los documentos</span>
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
          <div>
            <button id="btn-attach-files" type="button" class="btn btn-primary btn-block" style="margin-top:10px;">
              Cargar documentos
            </button>
          </div>
          <br>
      </div>
      <div class="content-attach-files">
        <?php foreach ($attached_files as $index => $file): ?>
          <div class="attach-file-item"> 
            <div class="row">
              <div class="col-md-12">
                <?php if ($file->file_source): ?>
                  <a href="<?php echo file_url($file->file_source); ?>" target="_blank">
                    <?php echo $file->name; ?>
                  </a>
                <?php endif; ?>
              </div> 
              <div class="col-md-12"></div>
            </div>
            <?php if ($file->loaded_by_area == 'employer'): ?>
              <button class="item-remove" data-file-id="<?php echo $file->ID; ?>">
                <i class="glyphicon glyphicon-remove"></i>
              </button>
            <?php endif; ?>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  
  function showSelectorFiles() {
    $( "#input-attach-file" ).remove();
    var mime_types = "<?php echo join(',', get_mime_types(explode(',', $document->allowed_files))); ?>";
    var inputFile = $(`<input id="input-attach-file" type="file" name="file" multiple style="display:none;" accept="${mime_types}">`);
    $( ".content-attach-files" ).append(inputFile);
  
    $(inputFile).fileupload({
      dataType: 'json',
      url: "<?php echo site_url('employer/recruitment_attached_documents/upload_document'); ?>",
      formData: {
        job_id: "<?php echo $job_id; ?>",
        candidate_id: "<?php echo $candidate_id; ?>",
        document: "<?php echo $document->key; ?>"
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

        var originalFileName = data.result.original_file_name;

        var fileId = data.result.file_id;
        var urlFile = data.result.url_file;

        var linkFile = '<a href="' + urlFile + '" target="_blank" >' + originalFileName + '</a>';
        $( ".content-filename", item).html(linkFile);

        $( ".content-progress-bar", item).remove();
        $( ".item-remove", item).data('file-id', fileId).show();
      }
    });
    
    inputFile[0].click();
  }

  function removeFile(fileId, item) { 

    if (!fileId) {
      item.remove();
      return;
    }

    var url = "<?php echo base_url('employer/recruitment_attached_documents/remove'); ?>";
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

  $( "#btn-attach-files" ).click(function(){
    showSelectorFiles();
  });

  $(document).off('click', '.item-remove').on('click', '.item-remove', function(){
    var item = $(this).closest('.attach-file-item');
    var fileId = $(this).data('file-id');
    removeFile(fileId, item);
  });
</script>
