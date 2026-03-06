<style>
  .attach-file-item {
    min-height: 90px;
    background: white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.12);
    border-radius: 8px;
    border: 1px solid #dfdfdf;
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 14px;
  }

  .attach-file-item .attach-file-item-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 17px;
  }

  .attach-file-item-body {
    display: flex;
    align-items: center;
    gap: 17px;
  }

  .attach-body-icon {
    width: 47px;
    height: 47px;
    background: rgba(13, 110, 253, 0.13);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .attach-file-item-body .attach-body-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .attach-file-item-trash button {
    border-radius: 8px;
    border: 1px #dfdfdf solid;
    padding: 6px;
  }

  .attach-body-content .file-link {
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    line-height: 16px;
    word-wrap: break-word;
  }

  .attach-body-content .file-date{
    color: #777777;
    font-size: 13px;
    text-align: left;
  }

  .file-link a {
    text-decoration: none;
    color: inherit;
    display: inline-block;
    color: #0d6efd;
  }

  .attach-content-load {
    min-height: 150px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #dfdfdf;
    position: relative;
    padding: 20px;
    text-align: center;
  }

  .content-load-title {
    color: #333333;
    font-size: 16px;
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    margin: 10px 56px;
  }

  .content-load-description {
    color: #333333;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    font-weight: 400;
    margin: 20px 34px;
  }

  .btn-file-recruitment-select {
    color: #0d6efd;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    border: 1.5px solid #0d6efd;
    border-radius: 6px;
    display: block;
    cursor: pointer;
    background: white;
    width: 100%;
  }

  .document-section-title {
    color: #333333;
    font-size: 18px;
    font-weight: 700;
    word-wrap: break-word;
    text-align: center;
    margin: 10px 0 15px 0;
  }

  .file-document-title {
    padding: 10px 0;
    font-size: 15px;
    font-weight: bold;
  }
</style>

<div style="padding: 10px 0;">
	<div class="document-section-title">
	  <?php echo $document->name . ' (' . count($attached_files). ')'; ?>
	</div> 
	<div>

    <?php if ($tray_candidate->status_id != 3): ?>
      <div class="attach-content-load">
        <div>
          <img src="<?php echo base_url('public/images/attach_file.svg'); ?>">
        </div>
        <div class="content-load-title">
          Adjunta el documento
        </div>
        <div class="content-load-description">
          Considera que tengan formato jpg, jpeg y pdf. No superior a los 4MB.
        </div>
        <div>
          <?php echo form_open_multipart('employer/recruitment_tray/recruitment_documents/upload', ['id' => 'form-recruitment-load-documents']); ?>
            <input type="file" name="file" id="file-recruitment-documents" style="display: none;">
            <input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>">
            <input type="hidden" name="process_id" value="<?php echo $process_id; ?>">
            <input type="hidden" name="document" value="<?php echo $document->key; ?>">
            <div style="max-width: 400px;margin: 0 auto;">
              <?php if ($document->key == 'other_documents'): ?>
                <input type="text" 
                      name="document_title" 
                      class="form-control" 
                      placeholder="Nombre del documento"
                      maxlength="45" 
                      style="margin-bottom: 10px;" required>
              <?php endif; ?>
              <button type="button" class="btn btn-file-recruitment-select">Seleccionar Archivo</button>
            </div>
            <div class="container-progress"></div>
          <?php echo form_close(); ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($attached_files): ?>
      <div style="padding: 8px 0;">
        <?php foreach ($attached_files as $index => $file): ?>
          <div class="attach-file-item"> 
            <div class="attach-file-item-content">
              <div class="attach-file-item-body">
                <div class="attach-body-icon">
                  <img src="<?php echo base_url('public/images/file-check.svg'); ?>">
                </div>
                <div class="attach-body-content">                
                  <div class="file-link">  
                    <?php if ($file->document_key == 'other_documents'): ?>
                      <div class="file-document-title"><?php echo $file->document_title; ?></div>
                    <?php endif; ?>
                    <a href="<?php echo file_url($file->file_path); ?>" target="_blank">
                      Ver documento
                    </a>
                  </div>
                  <div class="file-date"><?php echo date('d M Y', strtotime($file->created_at));?></div>
                </div>
              </div>
              <div class="attach-file-item-trash">
                <button class="btn btn-default btn-trash-document" 
                        data-file-id="<?php echo $file->file_id; ?>"
                        <?php echo $tray_candidate->status_id == 3 ? 'disabled' : ''; ?>>
                  <img src="<?php echo base_url('public/images/trash.svg'); ?>">
                </button>
                
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>

    <?php if (!$attached_files): ?>
      <div style="text-align: center;margin: 6em 0 4em 0;opacity: 0.3;">
        <svg width="36px" height="36px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18.5 20C18.5 20.275 18.276 20.5 18 20.5H12.2678C11.9806 21.051 11.6168 21.5557 11.1904 22H18C19.104 22 20 21.104 20 20V9.828C20 9.298 19.789 8.789 19.414 8.414L13.585 2.586C13.57 2.57105 13.5531 2.55808 13.5363 2.5452C13.5238 2.53567 13.5115 2.5262 13.5 2.516C13.429 2.452 13.359 2.389 13.281 2.336C13.2557 2.31894 13.2281 2.30548 13.2005 2.29207C13.1845 2.28426 13.1685 2.27647 13.153 2.268C13.1363 2.25859 13.1197 2.24897 13.103 2.23933C13.0488 2.20797 12.9944 2.17648 12.937 2.152C12.74 2.07 12.528 2.029 12.313 2.014C12.2933 2.01274 12.2738 2.01008 12.2542 2.00741C12.2271 2.00371 12.1999 2 12.172 2H6C4.896 2 4 2.896 4 4V11.4982C4.47417 11.3004 4.97679 11.1572 5.5 11.0764V4C5.5 3.725 5.724 3.5 6 3.5H12V8C12 9.104 12.896 10 14 10H18.5V20ZM13.5 4.621L17.378 8.5H14C13.724 8.5 13.5 8.275 13.5 8V4.621Z" fill="#212121"/>
        <path d="M12 17.5C12 20.5376 9.53757 23 6.5 23C3.46243 23 1 20.5376 1 17.5C1 14.4624 3.46243 12 6.5 12C9.53757 12 12 14.4624 12 17.5ZM2.5 17.5C2.5 18.3335 2.75495 19.1075 3.19112 19.7482L8.74822 14.1911C8.10751 13.755 7.33353 13.5 6.5 13.5C4.29086 13.5 2.5 15.2909 2.5 17.5ZM6.5 21.5C8.70914 21.5 10.5 19.7091 10.5 17.5C10.5 16.6665 10.245 15.8925 9.80888 15.2518L4.25178 20.8089C4.89249 21.245 5.66647 21.5 6.5 21.5Z" fill="#212121"/>
        </svg>
        <div style="padding: 0.5em 0;font-size: 14px;color: #000;">No hay documentos</div>
      </div>
    <?php endif; ?>

	</div>
</div>

<script>

  $( '.btn-file-recruitment-select' ).click(function(){
    $( '#file-recruitment-documents' ).click();
  });

  $( '#file-recruitment-documents' ).change(function(e){
    
    if (e.target.files.length == 0) {
      return;
    } 

    const formUpload = $( '#form-recruitment-load-documents' );

    const formData = new FormData(formUpload[0]);
    const url = $(formUpload).prop('action');
    const uploadInput = $(this);
    const file = e.target.files[0];
    const fileName = file.name;
    const fileSizeMb = (file.size / (1024 * 1024)).toFixed(2);

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(res) {
        uploadInput.val('');

        const message = res.message;

        $( '.btn-file-recruitment-select' ).show();
        $( '.container-progress' ).html(``);

        if (res.status) {
          loadContentRecruitmentDocument("<?php echo $process_id; ?>", "<?php echo $candidate_id; ?>", "<?php echo $document->key; ?>");
          toastr["success"]('Documento cargado!');
          return;
        }

        if (!res.status) {
          toastr["error"](message);
        }
      },

      xhr: function() {
        const xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(e){

          if (e.lengthComputable) {
            let uploadPercent = e.loaded / e.total;
            uploadPercent =  Math.round((uploadPercent * 100));

            $( '.btn-file-recruitment-select' ).hide();
            $( '.container-progress' ).html(`${fileSizeMb} MB - ${uploadPercent}%`);
           
            if (uploadPercent == 100) {
              $( '.container-progress' ).html(`${fileSizeMb} MB - Validando...`);
            }
          }
        }, false);

        return xhr;
      }
    });
  });

  $( '.btn-trash-document' ).click(function(){
  
    const confirm = window.confirm('Esta seguro de eliminar el documento');

    if (!confirm) {
      return;
    }
    
    $( '.content-main-detail' ).addClass('load load-image');
    const url = "<?php echo site_url('employer/recruitment_tray/recruitment_documents/delete'); ?>";
    const fileId = $(this).data('file-id');
    const data = {
      'file_id': fileId
    };

    $.post(url, data, function(response) {
    
      if (response.status) {
        loadContentRecruitmentDocument("<?php echo $process_id; ?>", "<?php echo $candidate_id; ?>", "<?php echo $document->key; ?>");
        toastr["success"](response.message);
        return;
      }
      
      $( '.content-main-detail' ).removeClass('load load-image');
      toastr["error"](response.message);

    }, 'json')
    .error(function() {
      $( '.content-main-detail' ).removeClass('load load-image');
      toastr["error"]('Ha ocurrido un error!');
    })
    .always(function(){});
  });

</script>
