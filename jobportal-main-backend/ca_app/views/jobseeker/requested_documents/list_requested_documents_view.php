<?php 
  $evicertia_status = [
    1 => 'SIN ENVIAR',
    2 => 'PENDIENTE',
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
  <style type="text/css">
    .requested-img {
        text-align: center;
        margin-top: 26px;
    }

    .requested-img img {
        width: 56px;
        height: 56px;
    }

    .requested-container {
        width: 100%;
        height: 100%;
        position: relative;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0px 20px;
    }

    .requested-main-box {
        width: 100%;
        max-width: 600px;
        background: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.12);
        border-radius: 12px;
        border: 1px #bcbcbc solid;
        margin-bottom: 16px;
    }

    .requested-inner-box {
        width: 100%;
        height: 70px;
        padding: 11px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        border-bottom: 1px solid #dfdfdf;
    }

    .requested-inner-box:last-child {
        border-bottom: none;
    }

    .requested-inner-box img {
        width: 24px;
        height: 24px;
    }

    .requested-inner-box-text {
        flex: 1;
        color: #333333;
        font-size: 14px;
        font-family: Inter, sans-serif;
        font-weight: 600;
        word-wrap: break-word;
    }

    .requested-small-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .requested-small-icon img {
        width: 8.71px;
        height: 14.04px;
    }

    .requested-success-message {
        width: 100%;
        max-width: 600px;
        padding: 16px;
        background: rgba(209, 231, 221, 0.65);
        border-radius: 6px;
        border: 1px #75b798 solid;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .requested-success-message img {
        width: 16px;
        height: 16px;
    }

    .requested-success-message-text {
        color: #198754;
        font-size: 14px;
        font-family: Inter, sans-serif;
        font-weight: 500;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
      .requested-main-box, .requested-success-message {
          max-width: 328px;
      }

      .requested-inner-box {
          height: 91px;
      }

      .requested-img {
          display: none;
      }
    }
  </style>
  </head>
  <body>
  <?php $this->load->view('common/after_body_open'); ?>
  <div class="siteWraper">
  <!--Header-->
  <?php $this->load->view('common/header'); ?>
  <!--/Header--> 
  <!--Detail Info-->
  <div class="container">
    <div class="row">
      <div <?php echo $this->session->userdata('menu') != '0' ? 'class="col-md-3"' : 'class="col-md-2"'; ?>>
        <div class="dashiconwrp">
          <?php if ($this->session->userdata('menu') != '0'): ?>
            <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-9">
        <?php echo $this->session->flashdata('msg');?>
        <div class="requested-img">
          <img src="<?php echo base_url('public/images/documents.svg');?>" />
        </div>
        <div class="new-titlehead">Documentos solicitados</div>
        
        <div class="requested-container">
          <div class="requested-main-box">
            <?php foreach ($documents as $doc): ?>
              <?php 
                if (!$doc->document_id) {
                  continue;
                }
              ?>

              <?php if ($doc->option_type_id == 1): ?>
                <div class="requested-inner-box">
                  <?php
                    $imgSrc = (count($this->Recruitment_contract_document->all(['document_id' => $doc->id, 'seeker_id' => $seeker->ID])) == 0) 
                        ? base_url('public/images/warning.svg') 
                        : base_url('public/images/success.svg');
                  ?>
                  <img src="<?php echo $imgSrc; ?>" />
                  <div class="requested-inner-box-text"><?php e($doc->name); ?></div>
                  <div class="requested-small-icon">
                    <?php 
                    $count = count($this->Recruitment_contract_document->all(['document_id' => $doc->id, 'seeker_id' => $seeker->ID]));
                    if ($count >= 0): ?>
                      <a href="<?php echo site_url('jobseeker/contract_documents/index/' . $doc->id); ?>"
                        class="btn btn-xs  show-load-document">
                        <img src="<?php echo base_url('public/images/group.svg');?>" />  
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($doc->option_type_id == 2): ?>
                <?php if ($doc->id == 1): ?>
                  <div class="requested-inner-box">
                    <?php
                      $imgSrc = !$identification_document 
                        ? base_url('public/images/warning.svg') 
                        : base_url('public/images/success.svg');
                    ?>
                    <img src="<?php echo $imgSrc; ?>" />
                    <div class="requested-inner-box-text"><?php e($doc->name); ?></div>
                    <div class="requested-small-icon">
                      <a href="<?php echo site_url('jobseeker/requested_documents/send_identification_document/'); ?>"
                          class="btn btn-xs  show-load-document">
                          <img src="<?php echo base_url('public/images/group.svg');?>" />
                      </a>
                    </div>
                  </div>
                <?php endif; ?>
      
                <?php // FICHA DE INGRESO ?>
                <?php if ($doc->group_id == 1): ?>
                  <div class="requested-inner-box">
                    <?php
                      if ($entry_form != null || ($form_rtps && $form_rtps->evicertia_status == 3)) {
                        $imgSrc = base_url('public/images/success.svg');
                      } else {
                        $imgSrc = base_url('public/images/warning.svg');
                      }
                    ?>
                    <img src="<?php echo $imgSrc; ?>" />
                    <div class="requested-inner-box-text"><?php e($doc->name); ?></div>
                    <div class="requested-small-icon">
                      <a href="<?php echo site_url('jobseeker/entry_form/entry_form_base/show/' . $doc->id . '/' . $rs_process->process_id); ?>"
                          class="btn btn-xs  show-load-document">
                          <img src="<?php echo base_url('public/images/group.svg');?>" />
                      </a>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if ($doc->id == 9): ?>
                  <div class="requested-inner-box">
                    <?php
                      $imgSrc = !$studies || count($studies) != $count_study_certificates
                        ? base_url('public/images/warning.svg') 
                        : base_url('public/images/success.svg');
                    ?>
                    <img src="<?php echo $imgSrc; ?>" />
                    <div class="requested-inner-box-text"><?php e($doc->name); ?></div>
                    <div class="requested-small-icon">
                      <a href="<?php echo site_url('jobseeker/requested_documents/send_study_certificate'); ?>"
                          class="btn btn-xs  show-load-document">
                          <img src="<?php echo base_url('public/images/group.svg');?>" />
                      </a>
                    </div>
                  </div>
                <?php endif; ?>
                <?php if ($doc->id == 13): ?>
                  <div class="requested-inner-box">
                    <?php
                      $imgSrc = !$experiences || count($experiences) != $count_experience_certificates
                        ? base_url('public/images/warning.svg') 
                        : base_url('public/images/success.svg');
                    ?>
                    <img src="<?php echo $imgSrc; ?>" />
                    <div class="requested-inner-box-text"><?php e($doc->name); ?></div>
                    <div class="requested-small-icon">
                      <a href="<?php echo site_url('jobseeker/requested_documents/send_experience_certificate/'); ?>"
                          class="btn btn-xs  show-load-document">
                          <img src="<?php echo base_url('public/images/group.svg');?>" />
                      </a>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endif; ?>

              <?php if ($doc->id == 16): ?>
                <?php if ($form_affidavit && $form_affidavit->answered): ?>
                  <div class="requested-inner-box">
                    <?php
                      $imgSrc = !$form_affidavit->affidavit_accept
                        ? base_url('public/images/warning.svg') 
                        : base_url('public/images/success.svg');
                    ?>
                    <img src="<?php echo $imgSrc; ?>" />
                    <div class="requested-inner-box-text"><?php echo $form_affidavit->form_name; ?></div>
                    <div class="requested-small-icon">
                      <a href="<?php echo site_url('jobseeker/forms/accept_affidavit/' . $form_affidavit->assignment_id); ?>"
                          class="btn btn-xs  show-load-document">
                          <img src="<?php echo base_url('public/images/group.svg');?>" />
                      </a>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
      </div>
      </div>
      <!--/Job Detail--> 
    </div>
  </div>

  <style>
    .container-iframe-load-documents {
      position: relative;
      padding-top: 56.25%;
      overflow-y:hidden;
    }

    .container-iframe-load-documents iframe{
      position: absolute;
      width:100%;
      height: 100%;
      left: 0;
      top: 0;
      overflow-y:hidden;
    }
  </style>
  <div id="modal-load-doc-view" class="modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cargar</h4>
        </div>
        <div class="modal-body">
                            
          <div class="container-iframe-load-documents">
            <iframe id="frame-doc" 
                  frameborder="0"
                  allowfullscreen></iframe> 
          </div>
            
        </div>
      </div>
    </div>
  </div>

  <?php $this->load->view('common/bottom_ads');?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <?php $this->load->view('common/before_body_close'); ?>

  </body>
</html>