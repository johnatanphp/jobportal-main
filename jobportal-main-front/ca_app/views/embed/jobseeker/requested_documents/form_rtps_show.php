<?php                    
  $evicertia_status = [
    1 => 'SIN ENVIAR',
    2 => 'PENDIENTE POR FIRMAR',
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
<style>
    .formwraper {
        border: 0;
    }
</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<div>
  <div>
    <div class="col-md-12">  
      <?php echo $this->session->flashdata('msg'); ?>
      <div class="formwraper">
        <div class="formint">
          <?php if (!$form_rtps->evicertia_status || $form_rtps->evicertia_status == 1): ?>
            <div class="msg-info">
              <div class="info-upload-doc">
                <span class="info-upload-doc__info">Indicaciones</span>
                <ul class="info-upload-doc__list">
                  <li>
                    Leer cuidadosamente la información ingresada y mostrada en la ficha de abajo, si algo no es confiorme a la realidad por favor cambiarlo o preguntar a los reclutadores para más información.
                  </li>
                  <li>
                    Envia la solicitud de firma.
                  </li>
                  <li>
                    Luego del envio, revisa tu correo y sigue las instrucciones para proceder con la firma.
                  </li>
                </ul>

                <div align="center" style="margin-top: 10px">
                  <button id="sign-form-rtps" class="btn btn-primary" data-form-rtps-id="<?php echo $form_rtps->ID; ?>">
                    Firmar declaración jurada
                  </button>            
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if ($form_rtps->evicertia_status > 1): ?>
            <div>
              <table width="100%">
                <tr>
                  <th style="text-align: center;" width="50%" colspan="2">
                    Declaración Jurada
                  </th>
                </tr>
                <tr>
                  <td align="center">
                    
                    <?php if ($form_rtps->evicertia_status == 3): ?> 
                      <i style="color:green;" class="glyphicon glyphicon-ok"></i>  
                    <?php endif; ?>

                    <?php echo $evicertia_status[$form_rtps->evicertia_status]; ?>
                  </td>
                </tr>
                <?php if (in_array($form_rtps->evicertia_status, [4, 5, 6])): ?>
                  <tr>
                    <td align="center" style="padding-top: 10px;">
                      <button id="sign-form-rtps" class="btn btn-primary" data-form-rtps-id="<?php echo $form_rtps->ID; ?>">
                        Reenviar solicitud de firma
                      </button>    
                    </td>
                  </tr>
                <?php endif; ?>
              </table>
            </div>
            <br>`
          <?php endif; ?>

          <div class="form-rtps-action">
            <?php if (!$form_rtps->evicertia_status || $form_rtps->evicertia_status != 3): ?>
              <a style="margin:8px 4px;" 
                 class="btn btn-primary btn-xs pull-right" 
                 href="<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/index/' . $form_rtps->seeker_ID); ?>">
                Editar
              </a>
            <?php endif; ?>

            <?php if ($this->session->userdata('is_job_seeker')): ?>
              <a href="#" class="show-rs-doc-comments" data-document="form_rtps">
                Ver comentarios recibidos
              </a>  
            <?php endif; ?>
          </div>

          <?php $this->load->view('employer/recruitment/common/candidate_form_rtps'); ?>
        </div>
      </div>
    </div> 
  </div>
</div>
<div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>
<?php $this->load->view('common/bottom_ads');?>
<?php $this->load->view('common/before_body_close'); ?>

<script>
  $(function(){

    $( '#sign-form-rtps' ).click(function(){
      if (!window.confirm('¿Está seguro de enviar la solicitud de firma?')) {
        return;
      }
      
      var btn = $(this);

      btn.prop('disabled', true);
      btn.html('Enviando...');
      
      var data = {
        'id' : $(this).data('form-rtps-id')      
      };

      var url = "<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/sign_affidavit'); ?>";

      $.post(url, data, function(response) {

        if (response.success) {
          toastr["success"]("¡Solicitud de firma enviada!");
        } else {
          toastr["error"]("¡No se pudo enviar el documento!");
        }

        window.setTimeout(function(){
          window.location.reload();
        }, 500);
      }, 'json');
    });

  });
</script>
</body>
</html>
