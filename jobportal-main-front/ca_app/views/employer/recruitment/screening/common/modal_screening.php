<div id="modal-seeker-screening" class="modal" role="dialog">
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

    .screening-upload-remove {
      background: transparent;
      border: 0;
      padding: 0;
      margin: 0;
      color: red;
    }
  </style>
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          Certificado Screening
        </h4>
      </div>
      <div class="modal-body">
        <div>
          <div class="row">
            <div class="col-md-12">
              <button id="btn-confirm-create-screening" 
                      type="button" 
                      class="btn btn-xs btn-primary pull-right" 
                      style="margin:3px;">
                Crear Screening
              </button>
              <button id="btn-confirm-upload-screening" 
                      type="button" 
                      class="btn btn-xs btn-default pull-right" 
                      style="margin:3px;">
                Adjuntar Screening
              </button>
            </div>
          </div>
          <br>
        </div>
        <div class="screening-results"></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="modal-confirm-create-screening" class="modal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Crear Screening</h4>
      </div>
      <div class="screening-create-form" style="padding: 15px 5px;"></div>
    </div>
  </div>
</div>

<div id="modal-confirm-upload-screening" class="modal" role="dialog">
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
          Certificado screening
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
              <button id="btn-upload-screennig" 
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

<?php $this->load->view('employer/recruitment/screening/scripts/seeker_screening_js'); ?>
