<!DOCTYPE html>
<html lang="en">
  <head>
  <?php $this->load->view('common/meta_tags'); ?>
  <title><?php echo $title;?></title>
  <?php $this->load->view('common/before_head_close'); ?>

  <style>
    .container-message ul {
        list-style-type: none;
    }

    .container-message small {
        font-weight: bold;
        padding-bottom: 5px;
    }
    
    .container-message ul {
        padding-top: 5px;
    }

    .container-message ul li {
        font-size: 13px;
        padding: 2px;
    }

    .container-info {
        font-size: 14px;
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
        <?php echo $this->session->flashdata('msg');?>
        <div class="formwraper">
          <div class="titlehead">Documentos solicitados</div>        
          <div class="formint">
            <div class="message">

                <h5><b>Usted no tiene acceso a la carga de documentos solicitados</b></h5>
                <br>
                <div class="container-message">
                    <small>Posibles causas:</small>
                    <ul class="list-group">
                        <li>- El proceso de reclutamiento y selección ya no esta activo.</li>
                        <li>- Usted no está en ningún proceso de reclutamiento y selección.</li>
                        <li>- Usted ya está contratado para el puesto que está intentando cargar los documentos.</li>
                    </ul>
                </div>
                <div class="container-info">
                    Le invitamos a ponerse en contacto con el reclutador que le solicito los documentos.
                </div>
            </div>
          </div>
        </div>
      </div>
      <!--/Job Detail--> 
    </div>
  </div>

  <?php $this->load->view('common/bottom_ads');?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <?php $this->load->view('common/before_body_close'); ?>  
</body>
</html>