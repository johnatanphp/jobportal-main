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
  <div class="siteWraper">
  <!--Detail Info-->
  <div class="container detailinfo">
    <div class="row">
      <div class="col-md-9">
        <div class="formwraper">   
          <div class="formint">
            <div class="message">
              <h5><b>Usted no tiene acceso a la carga de documentos solicitados</b></h5>
              <br>
              <div class="container-message">
                  <small>Posibles causas:</small>
                  <ul class="list-group">
                    <li>- El postulante ya está contratado para el puesto.</li>
                    <li>- El proceso de reclutamiento y selección ya no esta activo.</li>
                  </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/Job Detail--> 
    </div>
  </div>
  <?php $this->load->view('common/before_body_close'); ?>  
</body>
</html>
