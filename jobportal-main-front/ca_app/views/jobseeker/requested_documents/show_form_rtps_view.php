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
    .btn-style-2 {
      color: #005da4;
      background: #ffffff;
    }

    .title-container {
      text-align: center;
      margin: 20px 0;
    }

    .title-container h1 {
      font-family: 'Inter', sans-serif;
      font-weight: 700;
      font-size: 20px;
      line-height: 24.2px;
      margin: 0;
    }
  </style>
  <link rel="stylesheet" href="<?php echo base_url('public/css/custom.css'); ?>">
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php 
  $this->load->view('common/header');
?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row">
    <div <?php echo $this->session->userdata('menu') != '0' ? 'class="col-md-3"' : 'class="col-md-2"'; ?>>
      <div class="dashiconwrp">
        <?php if ($this->session->userdata('menu') != '0'): ?>
          <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-md-9">  
      <?php echo $this->session->flashdata('msg'); ?>
      <div style="color: #0D6EFD; font-size: 14px; font-family: Inter; font-weight: 600; line-height: 16px; word-wrap: break-word">
        <a href="<?php echo base_url('jobseeker/requested_documents'); ?>">
          <span class="glyphicon glyphicon-menu-left"></span><i class="bi bi-chevron-left"></i>
          Volver
        </a>
      </div>
      <div class="title-container">
        <h1><?php e($contract_document_type->name); ?></h1>
      </div>
      
      <?php if ((!$form_rtps->evicertia_status || $form_rtps->evicertia_status > 0) && $form_rtps->evicertia_status != 3): ?>
        <div class="d-flex justify-content-center align-items-center vh-100"  style="margin-bottom: 20px;">
          <div class="custom-card-list">
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
                        <button id="sign-form-rtps" class="btn btn-primary  btn-sm" data-form-rtps-id="<?php echo $form_rtps->ID; ?>">
                          Reenviar solicitud de firma
                        </button>   
                      </td>
                    </tr>
                  <?php endif; ?>
                  <?php if ($form_rtps->evicertia_send_date) : ?>
                    <tr>
                      <td style="text-align: center; padding-top: 20px;" colspan="2">
                        <?php
                          $date = new DateTime($form_rtps->evicertia_send_date);

                          $formattedDate = $date->format('d/m/Y H:i:s');
                        ?>
                        Fecha y hora de envio: <?php echo $formattedDate ?>
                      </td>
                    </tr>
                  <?php endif; ?>
                </table>
              </div>
              <br>
            <?php endif; ?>
              <div class="info-upload-doc">
                <h5 class="info-upload-doc__info">Indicaciones</h5>
                <ul class="info-upload-doc__list">
                  <li style="margin-bottom: 8px; margin-top: 10px;">
                    Leer cuidadosamente la información ingresada y mostrada en la ficha de abajo, si algo no es confiorme a la realidad por favor cambiarlo o preguntar a los reclutadores para más información.
                  </li>
                  <li style="margin-bottom: 8px;">
                    Envia la solicitud de firma.
                  </li>
                  <li style="margin-bottom: 8px;">
                    Luego del envio, revisa tu correo y sigue las instrucciones para proceder con la firma.
                  </li>
                </ul>

                <div align="center" style="margin-top: 10px">
                  <?php if ($form_rtps->evicertia_status == 1) : ?>
                    <button id="sign-form-rtps" class="custom-card-edit-button" data-form-rtps-id="<?php echo $form_rtps->ID; ?>">
                      Firmar declaración jurada
                    </button>   
                  <?php endif; ?>
                  <?php if (!$form_rtps->evicertia_status || $form_rtps->evicertia_status != 3): ?>
                    <button id="edit-form" style="width: 120px;" class="btn btn-primary" data-href="<?php echo base_url('jobseeker/form_rtps/index/' . $contract_document_type->id . '/' . $process->id); ?>">
                      Editar
                    </button>            
                  <?php endif; ?>
                </div>
              </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="formwraper">
        <div class="formint">
            <a href="#" class="show-rs-doc-comments" data-document="form_rtps">
              Ver comentarios recibidos
            </a>  
          <?php $this->load->view('employer/recruitment/common/candidate_form_rtps'); ?>
        </div>
      </div>

      <?php if ($staff_request && $staff_request->cod_business_unit == 'MK'): ?>               
        <div class="formwraper">
          <div class="formint">
            <?php $this->load->view('employer/recruitment/common/candidate_send_form_rtps'); ?>
          </div>
        </div>
        <div class="formwraper">
          <div class="formint">
            <?php $this->load->view('employer/recruitment/common/letter_engagement_form_rtps'); ?>
          </div>
        </div>
        <div class="formwraper">
          <div class="formint">
            <?php $this->load->view('employer/recruitment/common/loan_application_form_rtps'); ?>
          </div>
        </div>
      <?php endif; ?>
      
    </div> 
  </div>
</div>
<div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>

<script>
  $(function(){

    $('#edit-form').click(function() {
        var href = $(this).data('href');
        window.location.href = href;
    });

    $( '#sign-form-rtps' ).click(function(){
      if (!window.confirm('¿Está seguro de enviar la solicitud de firma?')) {
        return;
      }
      
      var btn = $(this);

      btn.prop('disabled', true);
      btn.html('Enviando...');


      $('#edit-form').hide();
      
      var data = {
        'id' : $(this).data('form-rtps-id')      
      };

      var url = "<?php echo site_url('jobseeker/form_rtps/sign_affidavit'); ?>";

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
