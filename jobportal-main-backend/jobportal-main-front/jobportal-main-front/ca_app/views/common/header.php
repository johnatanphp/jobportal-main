<div class="topheader">

  <?php if ($this->session->userdata('is_user_login') === true): ?>
    <?php $this->load->view('common/header_session'); ?>
  <?php else: ?>
    <?php $this->load->view('common/header_no_session'); ?>
  <?php endif; ?>

  <?php if ($this->config->item('site_terms') == 1 && 
            $this->config->item('site_privacy_policy') == 1 && 
            $this->session->userdata('user_id') && 
            $this->session->userdata('is_job_seeker') && 
            $this->uri->segment(1) == 'jobseeker' && 
            jobseeker_accepted_legal_terms() === false): ?>
            
    <div id="content-notice-terms-policy" style="background: red;padding: 10px;text-align: center;background: #fcff89;position: relative;">
      Hola <b><?php echo $this->session->userdata('first_name'); ?></b>, hemos agregado los siguientes terminos y condiciones legales para este sitio:

      <br />
      <br />
      - <a href="<?php echo site_url('terms.html'); ?>" target="_blank">Terminos y condiciones de uso</a>
      <br />
      - <a href="<?php echo site_url('privacy-policy.html'); ?>" target="_blank">Política de privacidad de datos</a>
      <br />
      <br />
      Al usar este sitio estas de acuerdo con estos terminos y condiciones.
      <br />
      <br />
      <button id="accept-term-and-policy" class="btn btn-default">Estoy de acuerdo</button>
    </div>

  <?php endif; ?>
