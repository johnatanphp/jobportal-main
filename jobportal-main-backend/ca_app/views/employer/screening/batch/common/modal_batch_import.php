<div id="modal-batch-import" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Crear lote</h4>
      </div>
      <div class="modal-body">

        <div class="container-import-file-select" style="text-align: center;display:block;">
          <div>
            <?php echo form_open_multipart('employer/screening/batch/register_file_validate', [ 'id' => 'form-screening-import']); ?>
              <input id="file-import" type="file" name="file_import" required value="" style="display: none;">
            <?php echo form_close(); ?>
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="43" viewBox="0 0 48 43" fill="none">
                <path d="M37.4808 10.5826H36.8965C35.6002 4.44922 30.2298 0 23.8113 0C17.3912 0 12.0226 4.44911 10.7261 10.5826H10.1418C4.5507 10.5826 0 15.1332 0 20.7245C0 26.1679 4.31241 30.6106 9.70103 30.8443V27.3388C9.70103 21.9902 14.0531 17.6378 19.4021 17.6378H28.2209C33.5696 17.6378 37.922 21.9899 37.922 27.3388V30.8443C43.3103 30.6106 47.623 26.1681 47.623 20.7245C47.623 15.1333 43.0721 10.5826 37.4808 10.5826Z" fill="#BCBCBC"/>
                <path d="M28.2206 21.1653H19.4018C15.9932 21.1653 13.2283 23.93 13.2283 27.3388V36.1576C13.2283 39.5662 15.993 42.3311 19.4018 42.3311H28.2206C31.6291 42.3311 34.3941 39.5664 34.3941 36.1576V27.3388C34.3941 23.9303 31.6294 21.1653 28.2206 21.1653ZM28.8754 32.9958C28.5315 33.3398 28.0795 33.5117 27.6276 33.5117C27.1757 33.5117 26.7236 33.3398 26.3798 32.9958L25.5728 32.1889V37.0393C25.5728 38.0138 24.7836 38.8031 23.809 38.8031C22.8345 38.8031 22.0453 38.0138 22.0453 37.0393V32.1889L21.2383 32.9958C20.5504 33.6837 19.4327 33.6837 18.7448 32.9958C18.0569 32.3079 18.0569 31.1902 18.7448 30.5023L22.5633 26.6837C23.2512 25.9958 24.369 25.9958 25.0569 26.6837L28.8754 30.5023C29.5633 31.1902 29.5633 32.3079 28.8754 32.9958Z" fill="#BCBCBC"/>
              </svg>
            </div>
            <div>
              <label>Adjunta un documento .xlsx</label>
            </div>
            <div>El archivo no puede ser superior a los 2MB</div>
            <div style="padding: 5px 0 0;">
              <a href="<?php echo site_url('public/documents/templates/screening/template_import_batch.xlsx'); ?>" style="color:#0f6d1b;">
                Descargar plantilla
              </a>
            </div>
            <div style="padding-top: 10px;">
              <button class="btn btn-primary btn-select-import-file">Seleccionar archivo</button>
            </div>
            <div id="import-screening-info" style="padding-top: 10px;display:none;">Cargando (80%)</div>
          </div>
        </div>

        <div class="container-form-data" style="display: none;">
          <div style="text-align:right;">
            <label><span>*</span> Campos obligatorios</label>
          </div>
          <?php echo form_open('employer/screening/batch/create', ['id'=> 'form-batch-create', 'method' => 'post']); ?>
            <label for="">Archvo Cargado</label>
            <div style="background: #eeeeee; border: 1px solid #ccc;padding: 8px 6px;">
              <span id="screening-info-total-rows" style="padding-right: 15px;">Documento - 2 registros</span>
              <button class="btn btn-xs btn-default btn-change-batch-file" type="button">Cambiar</button>
            </div>
            <br>
            <label for="">Descripción Lote <span>*</span></label>
            <input type="text" name="batch_description" class="form-control" maxlength="60">
            <br>
            <label for="">Tipo <span>*</span></label>
            <select name="type" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="1">Básico</option>
                <option value="2">Integral</option>
            </select>
            <br>
            <label for="">Puesto <span>*</span></label>
            <input type="text" name="job_title" class="form-control" required maxlength="140">
            <br>
            <label>Tipo Egreso <span>*</span></label> 
            <select class="form-control"
                    name="type_expense"
                    required
                    >
                <option value="">Seleccione</option>
                <option value="EI">EI</option>
                <option value="EONF">EONF</option>
                <option value="EOF">EOF</option>
                <option value="EOFDP">EOFDP</option>
                <option value="EODT">EODT</option>
            </select>
            <br>
            <label>Centro de costo <span>*</span></label>
            <select id="cost_centers" 
                    name="cost_center" 
                    class="form-control" 
                    required
                    style="width:100%">
              <option value="">Seleccione</option>

              <?php foreach ($cost_centers as $row): ?>
                <option value="<?php echo $row->COD_CCOSTO; ?>">
                  <?php echo $row->COD_CCOSTO; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <br>
            <br>
            <label for="">Estructura de costo (código)</label>
            <input type="text" name="eecc_code" class="form-control" maxlength="20">
            <br>
            <label for="">Centro de costo cliente</label>
            <input type="text" name="cost_center_client" class="form-control" maxlength="50">
          <?php echo form_close(); ?>
        </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button id="btn-form-submit-batch" type="button" class="btn btn-primary" disabled>Crear</button>
      </div>
    </div>

  </div>
</div>

<script type="module">
$(function(){
  $( '#cost_centers' ).select2({
    dropdownParent: $('#modal-batch-import .modal-body')
  });

  $( '#import-screening-batch' ).click(function(){
    $( '#modal-batch-import' ).modal('show');
  });

  $( '.btn-select-import-file' ).click(function(){
    $( '#file-import' ).click();
  });

  $( '.btn-change-batch-file' ).click(function(){
    $( '.container-import-file-select' ).show();
    $( '.container-form-data' ).hide();
    $( '#btn-form-submit-batch' ).prop('disabled', true);
    $( '.btn-select-import-file' ).click();
  });

  $( '#btn-form-submit-batch' ).click(function(){
    $( '#form-batch-create' ).submit();
  });

  $( '#file-import' ).change(function(e){
    
    if (e.target.files.length == 0) {
      return;
    } 

    const formData = new FormData($( '#form-screening-import' )[0]);
    const url = $( '#form-screening-import' ).prop('action');
    const uploadInput = $(this);
    const file = e.target.files[0];
    const fileName = file.name;
    const fileSizeMb = (file.size / (1024 * 1024)).toFixed(2);

    $( '#import-screening-info' ).show();
    $( '#import-screening-info' ).html('Cargando');

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(res) {
        uploadInput.val('');

        if (res.status) {
          $( '.container-import-file-select' ).hide();
          $( '.container-form-data' ).show();
          $( '#btn-form-submit-batch' ).prop('disabled', false);
          $( '#import-screening-info' ).hide().html(``);
          $( '#screening-info-total-rows' ).text(`Documento - ${res.data.total_rows} registro(s)`);
          return;
        }

        if (!res.status) {
          $( '#import-screening-info' ).hide().html('Cargando');
          $( '#file-import' ).val('');
          toastr['error'](res.message);
        }
      },
      error: function(res) {
        $( '#import-screening-info' ).hide().html('Cargando');
        $( '#file-import' ).val('');
        toastr['error']('Ha ocurrido un error');
      },
      xhr: function() {
        const xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(e){

          if (e.lengthComputable) {
            let uploadPercent = e.loaded / e.total;
            uploadPercent =  Math.round((uploadPercent * 100));

            $( '#import-screening-info' ).html(`Cargando (${uploadPercent}%)`);
     
            if (uploadPercent == 100) {
              $( '#import-screening-info' ).html(`Validando...`);
            }
          }
        }, false);

        return xhr;
      }
    });
  });

  $( '#form-batch-create').submit(function(e){
    e.preventDefault();

    if (!window.confirm("¿Está seguro de crear el lote?")) {
      return false;
    }

    const form = $(this);
    const btn = $('#btn-form-submit-batch');
    btn.html("Creando...");
    $( '#modal-batch-import .modal-content' ).addClass('load load-image');
    
    const url = $(this).prop('action');
    const data = $(this).serialize();

    $.post(url, data, function(response) {
      if (response.status) {
        ($( '#form-batch-create' )[0]).reset();
        window.location.href = response.data.batch_detail_url;
        return;
      }

      if (!response.status) {
        toastr["error"](response.message);
        btn.html("Crear");
        $( '#modal-batch-import .modal-content' ).removeClass('load load-image');
      }
    }, 'json')
    .fail(function(){
      toastr["error"]('Ha ocurrido un error');
      btn.html("Crear");
      $( '#modal-batch-import .modal-content' ).removeClass('load load-image');
    });
    
    return false;
  });

});
</script>