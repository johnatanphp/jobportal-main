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
    
    .subtitle {
      font-family: 'Inter', sans-serif;
      font-weight: 400;
      font-size: 14px;
      line-height: 18px;
      color: #666;
      margin: 0;
      text-align: center;
    }

    .subtitle-country {
      color: #007bff;
      margin-top: 1em;
      text-transform: uppercase;
    }

    .emoji-country {
      font-size: 20px;
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
        <p class="subtitle subtitle-country">
          <span class="emoji-country">
            <?php if ($process_country->flag_icon): ?>
              <img width="22" height="22" src="<?php echo $process_country->flag_icon; ?>" />
            <?php endif; ?>
          </span>
          OVERALL <?php e($process_country->country_name); ?> &#8226; <?php e($contract_document_type->company_name); ?>
        </p>
      </div>
      <div class="formwraper">
        <div class="formint">
          <div class="row">
            <div class="col-md-12" style="text-align:right;">
              <a href="<?php echo site_url('jobseeker/entry_form/ec/form_ec/index/' . $contract_document_type->id . '/' . $process_id . ''); ?>" 
                 class="btn btn-sm btn-default">
                Editar
              </a>
              <a href="<?php echo site_url('general/jobseeker/entry_forms/download/' . $entry_form->entry_form_id); ?>"
                 target="_blank" 
                 class="btn btn-sm btn-default">
                Descargar
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <?php $this->load->view('jobseeker/entry_form/ec/common/entry_form_view'); ?>
            </div>
          </div>
  
        </div>
      </div>

    </div> 
  </div>
</div>
<div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>

<script>
  $(function(){});
</script>
</body>
</html>
