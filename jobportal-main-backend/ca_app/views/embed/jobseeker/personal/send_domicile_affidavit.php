<?php 
                       
  $evicertia_status = [
      1 => 'SIN ENVIAR',
      2 => 'ENVIADO',
      3 => 'FIRMADO',
      4 => 'RECHAZADO',
      5 => 'FALLIDO',
      6 => 'EXPIRADO'
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
  <!--Detail Info-->
  <div class="containe detailinf">
    <div class="row">
      <div class="col-md-12">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="formwraper">
          <div class="titlehead">
            <b>Declaración Jurada Domicilio</b>
          </div>        
          <div class="formint">
              <div class="msg-info">
                Firma digital para la declaración jurada de domicilio
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

              <?php if (!$record || 
                        in_array($record->evicertia_status, [1, 5, 6]) || 
                        ($record->evicertia_status == 3 && 
                        $present_address != $seeker->present_address)): ?>
                <div>
                  <?php if (empty($present_address)): ?>
                    <div class="alert alert-warning" style="text-align: center;">
                        ¡Por favor ingresa una dirección de domicilio para poder enviar la declaración jurada!
                    </div>

                  <?php endif; ?>
                  <?php if (!empty($present_address)): ?>

                    <b>La declaración jurada se emitirá a la siguiente Dirección:</b>
                    <br />
                    <br />
                    <div style="position:relative; font-size: 15px;font-style: italic;background: #eee; border: 1px solid #ccc;padding: 10px;">
                    
                      <?php 
                        echo nl2br($present_address);
                      ?>

                      <div align="center">
                        <a href="<?php echo site_url('jobseeker/requested_documents/view_cert/1'); ?>" 
                           target="_blank"
                           class="btn btn-primary btn-xs"
                           style="display: none;">
                           <i class="glyphicon glyphicon-file"></i>
                          Ver Documento
                        </a>
                        <button class="btn btn-primary btn-xs btn-send-evicertia"
                                data-seeker-id="<?php echo $seeker->ID; ?>"
                                data-present-address="<?php echo $present_address; ?>"
                                data-cia-name="<?php echo $cia_name; ?>"
                                data-cost-center="<?php echo $cost_center; ?>"
                                data-cia-code="<?php echo $cia_code; ?>">
                          Enviar
                        </button>
                      </div>
                    </div>  
                  <?php endif; ?>

                </div>
                <br />
              <?php endif; ?>

              <?php if ($record && $record->evicertia_status != 1): ?>
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
                          <a href="<?php echo file_url($record->file_path); ?>" 
                             target="_blank">
                             <i class="glyphicon glyphicon-file"></i>
                            Ver Declaración
                          </a>
                        </td>
                        <td>
                          <?php echo isset($evicertia_status[$record->evicertia_status]) ? $evicertia_status[$record->evicertia_status] : '-'; ?>
                        </td>
                        <td>
                          <?php if ($record->evicertia_status == 3): ?>  
                            <a href="<?php echo $this->config->item('evicertia_sitio_url') . '/Evidence/EviSign/' . $record->evicertia_unique_id; ?>"
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

  <?php $this->load->view('common/before_body_close'); ?>
    
  <script type="text/javascript">

    $(document).on("click", ".btn-send-evicertia", function(e){

      if (!window.confirm('¿Está seguro de enviar la solicitud de firma de este documento?')) {
        return;
      }
      
      var btn = $(this);

      btn.prop('disabled', true);
      btn.html('Enviando...');
      
      var url = "<?php echo site_url('embed/jobseeker/personal/domicile_affidavit/send_signature'); ?>" ;
      var data = {
        "<?php echo embed_token_name(); ?>" : "<?php echo embed_token(); ?>",
        'seeker_id': $(this).data('seeker-id'),
        'present_address': $(this).data('present-address'),
        'cia_name': $(this).data('cia-name'),
        'cost_center': $(this).data('cost-center'),
        'cia_code': $(this).data('cia-code')
      };

      $.post(url, data, function(response) {

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