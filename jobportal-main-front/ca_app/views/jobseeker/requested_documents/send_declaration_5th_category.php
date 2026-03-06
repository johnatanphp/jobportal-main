<?php 
  $evicertia_status = [
      1 => 'SIN ENVIAR',
      2 => 'ENVIADO',
      3 => 'FIRMADO',
      4 => 'RECHAZADO'
  ];                 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title;?></title>
  <?php $this->load->view('common/before_head_close'); ?>

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
      <div class="col-md-3">
        <div class="dashiconwrp">
          <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
        </div>
      </div>
      <div class="col-md-9">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="formwraper">
          <div class="titlehead">
            <a href="<?php echo base_url('jobseeker/requested_documents/'); ?>" style="color:#fff;">
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            <b>Declaración de 5ta categoría</b>
          </div>        
          <div class="formint">
            <div style="text-align: right;padding: 10px 0;">
              <a href="#" class="show-rs-doc-comments" data-document="declaration_5th_category">
                Ver comentarios recibidos
              </a>  
            </div>

              <div class="msg-info">
                Firma digital para la Declaración de 5ta categoría
                  <div class="info-upload-doc">
                    <span class="info-upload-doc__info">Indicaciones</span>
                    <ul class="info-upload-doc__list">
                      <li>
                        Envia la solicitud de firma.
                      </li>
                      <li>
                        Luego del envio, revisa tu correo y sigue las instrucciones para proceder con la firma.
                      </li>
                    </ul>
                  </div>
              </div>

              <?php if (!$record || ($record->evicertia_status != 3 && $record->evicertia_status != 2)): ?>
                <div>
                  <b>La declaración jurada se emitirá para el periodo <?php echo date('Y'); ?></b>
                  <br />
                  <br />
                  <div style="position:relative; font-size: 15px;font-style: italic;background: #eee; border: 1px solid #ccc;padding: 10px;">
                  
                    <div align="center">
                      <a href="<?php echo site_url('jobseeker/requested_documents/view_cert/2'); ?>" 
                         target="_blank"
                         class="btn btn-primary btn-xs"
                         style="display: none;">
                         <i class="glyphicon glyphicon-file"></i>
                        Ver Documento
                      </a>
                      <button class="btn btn-primary btn-xs btn-send-evicertia"
                              data-job-id="<?php echo $job->ID; ?>">
                        Enviar
                      </button>
                    </div>
                  </div>
                </div>
                <br />
              <?php endif; ?>

              <?php if ($latest && $latest->file_path != ''): ?>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th width="230">Documento</th>
                        <th width="150">Estado</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <a href="<?php echo file_url($latest->file_path); ?>" 
                             target="_blank">
                             <i class="glyphicon glyphicon-file"></i>
                            Ver Declaración
                          </a>
                        </td>
                        <td>
                          <?php echo isset($evicertia_status[$latest->evicertia_status]) ? $evicertia_status[$latest->evicertia_status] : '-'; ?>
                        </td>
                        <td>
                          <?php if ($latest->evicertia_status == 3): ?>
                            <a href="<?php echo $this->config->item('evicertia_sitio_url') . '/Evidence/EviSign/' . $latest->evicertia_unique_id; ?>"
                               target="_blank">
                              Ver firma
                            </a>

                          <?php endif; ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>

          </div>
        </div>
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

  <script type="text/javascript">

    $(document).on("click", ".btn-send-evicertia", function(e){

      if (!window.confirm('¿Está seguro de enviar la solicitud de firma de este documento?')) {
        return;
      }

      var btn = $(this);

      btn.prop('disabled', true);
      btn.html('Enviando...');

      var jobId = $(this).data('job-id');

      var url = "<?php echo site_url('jobseeker/requested_documents/declaration_5th_category_send_evicertia/'); ?>" + jobId;

      $.post(url, {}, function(response) {

        if (response.success) {
          toastr["success"]("¡Documento enviado!");
        } else {
          toastr["error"]("¡No se pudo enviar el documento!");
        }

        window.setTimeout(function(){
          window.location.reload();
        }, 500);
      }, 'json');
    });
  </script>
  </body>
</html>