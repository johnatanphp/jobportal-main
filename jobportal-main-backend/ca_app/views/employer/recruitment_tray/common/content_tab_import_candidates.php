<style>
  #container-import {
    background: #F8F9FA;
    padding: 20px;
  }

  .container-import-file-select { 
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .content-import-progress-col.col-icon {
    position: relative;
    height: 65px;
    width: 70px;
  }

  .content-import-progress-col.col-icon svg {
    position: absolute;
  }

  .content-import-progress-col  .import-progress-file-name {
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;  
  }

  .content-import-progress-col  .import-progress-desc {
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;  
  }

  .btn-select-import-file-candidates {
    margin-top: 10px;
    border-radius: 6px;
    border: 1.5px solid #0D6EFD;
    background: #FFF;
    color: #0D6EFD;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 18px;
  }
  
  .btn-select-import-file-candidates:hover {
    background: var(--Schemes-On-Primary, #FFF);
    border: 1.5px solid  #0D6EFD;
    color: #0D6EFD;
  }

  .container-import-progress {
    border-radius: 8px;
    border: 1px solid #DFDFDF;
    background: #ffffff;
    box-shadow: 0px 4px 10px 0px rgba(0, 0, 0, 0.12);
    padding: 15px 30px;
  }

  .content-import-progress-row {
    display: flex;
  }

  .content-import-progress-col {
    padding: 10px;
  }

  .content-import-progress-col.col-desc {
    flex: 1;
  }

  .btn-import-clear {
    display: flex;
    justify-content: center;
    text-align: center;
    padding: 5px 6px;
    border-radius: 8px;
    border: 1px solid #DFDFDF;
  }

  .container-import-progress .progress {
    height: 10px;
    margin: 0;
    flex: 1;
    background: #DEE2E6;
  }

  .container-import-progress .progress-bar {
    background: #0D6EFD;
  }

  #tbl-import-progress-log tbody tr td {
    color: #b90606;
  }

</style>
<div id="container-import">
  <div class="container-import-file-select">
    <div>
      <?php echo form_open_multipart('employer/recruitment_tray/process_candidates/import_candidates_validate', [ 'id' => 'form-import-candidates']); ?>
        <input type="hidden" name="client_code" value="<?php echo $client->code; ?>">
        <input id="file-import-candidates" type="file" name="file_import" required value="" style="display: none;">
        <input type="hidden" name="template" value="computrabajo">
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
        <a href="<?php echo site_url('public/documents/templates/candidate_other_site/template_computrabajo_v3.xlsx'); ?>" style="color:#0f6d1b;">
          Descargar plantilla
        </a>
      </div>
      <div>
        <button class="btn btn-primary btn-select-import-file-candidates">Seleccionar archivo</button>
      </div>
    </div>
  </div>
  
  <div class="container-import-progress" style="display: none;">
    <div class="content-import-progress-row">
      <div class="content-import-progress-col col-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="47" viewBox="0 0 48 47" fill="none">
          <circle cx="24.325" cy="23.5" r="23.5" fill="#198754" fill-opacity="0.13"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none" style="top: 21px;left: 22px;">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M3.68823 3C3.68823 2.20435 4.0043 1.44129 4.56691 0.87868C5.12952 0.316071 5.89258 0 6.68823 0L14.6277 0C15.0255 8.49561e-05 15.407 0.158176 15.6882 0.4395L21.2487 6C21.5301 6.28124 21.6881 6.66271 21.6882 7.0605V21C21.6882 21.7956 21.3722 22.5587 20.8096 23.1213C20.2469 23.6839 19.4839 24 18.6882 24H6.68823C5.89258 24 5.12952 23.6839 4.56691 23.1213C4.0043 22.5587 3.68823 21.7956 3.68823 21V3ZM14.9382 5.25V2.25L19.4382 6.75H16.4382C16.0404 6.75 15.6589 6.59196 15.3776 6.31066C15.0963 6.02936 14.9382 5.64782 14.9382 5.25ZM16.9692 11.781C17.1101 11.6402 17.1892 11.4492 17.1892 11.25C17.1892 11.0508 17.1101 10.8598 16.9692 10.719C16.8284 10.5782 16.6374 10.4991 16.4382 10.4991C16.2391 10.4991 16.0481 10.5782 15.9072 10.719L11.9382 14.6895L10.2192 12.969C10.0784 12.8282 9.8874 12.7491 9.68823 12.7491C9.48907 12.7491 9.29806 12.8282 9.15723 12.969C9.0164 13.1098 8.93728 13.3008 8.93728 13.5C8.93728 13.6992 9.0164 13.8902 9.15723 14.031L11.4072 16.281C11.4769 16.3508 11.5597 16.4063 11.6508 16.4441C11.7419 16.4819 11.8396 16.5013 11.9382 16.5013C12.0369 16.5013 12.1346 16.4819 12.2257 16.4441C12.3168 16.4063 12.3996 16.3508 12.4692 16.281L16.9692 11.781Z" fill="#198754"/>
        </svg>
      </div>
      <div class="content-import-progress-col col-desc">
        <div class="import-progress-file-name">Nuevo Documento</div>
        <div class="import-progress-desc">0 MB - 0%</div>
      </div>
      <div class="content-import-progress-col col-clear">
        <button class="btn btn-xs btn-default btn-import-clear">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M6.875 6.875C7.04076 6.875 7.19973 6.94085 7.31694 7.05806C7.43415 7.17527 7.5 7.33424 7.5 7.5V15C7.5 15.1658 7.43415 15.3247 7.31694 15.4419C7.19973 15.5592 7.04076 15.625 6.875 15.625C6.70924 15.625 6.55027 15.5592 6.43306 15.4419C6.31585 15.3247 6.25 15.1658 6.25 15V7.5C6.25 7.33424 6.31585 7.17527 6.43306 7.05806C6.55027 6.94085 6.70924 6.875 6.875 6.875ZM10 6.875C10.1658 6.875 10.3247 6.94085 10.4419 7.05806C10.5592 7.17527 10.625 7.33424 10.625 7.5V15C10.625 15.1658 10.5592 15.3247 10.4419 15.4419C10.3247 15.5592 10.1658 15.625 10 15.625C9.83424 15.625 9.67527 15.5592 9.55806 15.4419C9.44085 15.3247 9.375 15.1658 9.375 15V7.5C9.375 7.33424 9.44085 7.17527 9.55806 7.05806C9.67527 6.94085 9.83424 6.875 10 6.875ZM13.75 7.5C13.75 7.33424 13.6842 7.17527 13.5669 7.05806C13.4497 6.94085 13.2908 6.875 13.125 6.875C12.9592 6.875 12.8003 6.94085 12.6831 7.05806C12.5658 7.17527 12.5 7.33424 12.5 7.5V15C12.5 15.1658 12.5658 15.3247 12.6831 15.4419C12.8003 15.5592 12.9592 15.625 13.125 15.625C13.2908 15.625 13.4497 15.5592 13.5669 15.4419C13.6842 15.3247 13.75 15.1658 13.75 15V7.5Z" fill="#333333"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.125 3.75C18.125 4.08152 17.9933 4.39946 17.7589 4.63388C17.5245 4.8683 17.2065 5 16.875 5H16.25V16.25C16.25 16.913 15.9866 17.5489 15.5178 18.0178C15.0489 18.4866 14.413 18.75 13.75 18.75H6.25C5.58696 18.75 4.95107 18.4866 4.48223 18.0178C4.01339 17.5489 3.75 16.913 3.75 16.25V5H3.125C2.79348 5 2.47554 4.8683 2.24112 4.63388C2.0067 4.39946 1.875 4.08152 1.875 3.75V2.5C1.875 2.16848 2.0067 1.85054 2.24112 1.61612C2.47554 1.3817 2.79348 1.25 3.125 1.25H7.5C7.5 0.918479 7.6317 0.600537 7.86612 0.366117C8.10054 0.131696 8.41848 0 8.75 0L11.25 0C11.5815 0 11.8995 0.131696 12.1339 0.366117C12.3683 0.600537 12.5 0.918479 12.5 1.25H16.875C17.2065 1.25 17.5245 1.3817 17.7589 1.61612C17.9933 1.85054 18.125 2.16848 18.125 2.5V3.75ZM5.1475 5L5 5.07375V16.25C5 16.5815 5.1317 16.8995 5.36612 17.1339C5.60054 17.3683 5.91848 17.5 6.25 17.5H13.75C14.0815 17.5 14.3995 17.3683 14.6339 17.1339C14.8683 16.8995 15 16.5815 15 16.25V5.07375L14.8525 5H5.1475ZM3.125 3.75V2.5H16.875V3.75H3.125Z" fill="#333333"/>
          </svg>
        </button>
      </div>
    </div>
    <div>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="0"
             aria-valuemin="0" aria-valuemax="100" style="width:0%">
          <span class="sr-only"></span>
        </div>
      </div>
    </div>
    <br>
    <div>
      <table id="tbl-import-progress-log" class="table" style="display: none;">
        <thead>
          <tr>
            <th>Error</th>
          </tr>
        </thead>
        <tbody>
     
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="module">
$(function(){

  $( '.btn-import-clear' ).click(function(){
    $( '.container-import-file-select' ).show();
    $( '.container-import-progress' ).hide();
    $( '.progress-bar', '.container-import-progress' ).css({"width": '0%'});
    $( '.import-progress-desc', '.container-import-progress' ).html('');
    $( '#btn-import-process-candidates' ).prop('disabled', true);
  });

  $( '.btn-select-import-file-candidates' ).click(function(){
    $( '#file-import-candidates' ).click();
  });

  $( '#file-import-candidates' ).change(function(e){
    
    if (e.target.files.length == 0) {
      return;
    } 

    $( '.container-import-file-select' ).hide();
    $( '.container-import-progress' ).show();
    $( '#tbl-import-progress-log' ).hide();
    $( '#tbl-import-progress-log tbody tr' ).empty();

    const formData = new FormData($( '#form-import-candidates' )[0]);
    const url = $( '#form-import-candidates' ).prop('action');
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

        if (res.status) {
          $( '.import-progress-desc', '.container-import-progress' ).html(`${fileSizeMb} MB - 100%`);
          $( '#btn-import-process-candidates' ).prop('disabled', false);
          return;
        }

        if (!res.status) {
          $( '.import-progress-desc', '.container-import-progress' ).html(`${fileSizeMb} MB - 100%`);

          const data = res.data || [];

          if (data.length == 0) {
            $( '#tbl-import-progress-log tbody' ).html(
              `<tr>
                <td>${res.message}</td>
              </tr>`
            );
          }

          const errors = res.data.errors || [];

          if (errors.length > 0) {
            let rows = '';
            
            for (let rowIndex in errors) {
              rows += `
                <tr>
                  <td>${errors[rowIndex]}</td>
                </tr>
              `;
            }

            $( '#tbl-import-progress-log tbody' ).html(rows);
          }

          $( '#tbl-import-progress-log' ).show();
        }
      },

      xhr: function() {
        const xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(e){

          if (e.lengthComputable) {
            let uploadPercent = e.loaded / e.total;
            uploadPercent =  Math.round((uploadPercent * 100));

            $( '.progress-bar', '.container-import-progress' ).css({"width":  uploadPercent + '%'});
            $( '.import-progress-desc', '.container-import-progress' ).html(`${fileSizeMb} MB - ${uploadPercent}%`);
           
            if (uploadPercent == 100) {
              $( '.import-progress-desc', '.container-import-progress' ).html(`${fileSizeMb} MB - Validando...`);
            }
          }
        }, false);

        return xhr;
      }
    });
  });

  $( '#btn-import-process-candidates' ).click(function(e){

    const url = "<?php echo site_url('employer/recruitment_tray/process_candidates/import_candidates_save'); ?>";

    const params = {
      'client_code': "<?php echo $client->code; ?>"
    };

    const btnSubmit = $( '#btn-import-process-candidates' );
    const btnClear = $( '.btn-import-clear' );
    const contentFooterButtons = $( 'button', '#modal-add-candidates .modal-footer' );
    btnSubmit.text('Agregando...');
    btnClear.prop('disabled', true);
    contentFooterButtons.prop('disabled', true);

    $.post(url, params, function(res) {
      
      if (!res.status) {
        toastr["error"](res.message);
        return;
      }

      toastr["success"](res.message);
      $( '.btn-import-clear' ).click();
      searchCandidates(1, {});
      
    }, 'json')
    .fail(function(e) {
      toastr["error"]('Ha ocurrido un error');
    })
    .always(function() {
      contentFooterButtons.prop('disabled', false);
      btnClear.prop('disabled', false);
      btnSubmit.text('Agregar');
      btnSubmit.prop('disabled', true);
    }); 
  });
});
</script>