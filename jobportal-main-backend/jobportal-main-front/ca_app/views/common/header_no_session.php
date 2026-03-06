<?php if (! in_array($this->uri->segment(1), ['login', 'employer-login'])  ): ?>
    <style>
      .usertopbtn {
        display: flex;
        flex-direction: row-reverse;
        min-height: 45px;
        align-items: center;
        column-gap: 5px;
      }
    </style>
    <div class="navbar navbar-default" role="navigation">
        <div class="col-xs-5 col-sm-3 col-md-3">
          <div class="navbar-header">       
            <a class="navbar-brand" href="<?php echo site_url(); ?>">
              <img src="<?php echo base_url('public/images/overall_blue.png');?>" style="height: 30px;" />
            </a>
          </div>
        </div>
        <div class="col-xs-2 col-sm-7 col-md-5">
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                <li <?php echo active_link('search-jobs');?>><a href="<?php echo base_url('jobs.html');?>" title="Buscar empleo">Buscar empleo</a> </li>
                <li <?php echo active_link('about-us.html');?>><a href="<?php echo base_url('about-us.html');?>" title="Nosotros">Nosotros</a></li>
                <li <?php echo active_link('contact-us');?>><a href="<?php echo base_url('contact-us');?>" title="Contáctanos">Contáctanos</a>
                </li>  
            </ul>
          </div>
        </div>
        <!--/.nav-collapse -->
      
        <div class="col-xs-5 col-sm-2 col-md-4">
          <div class="usertopbtn">
            <a href="<?php echo base_url('login');?>" class="loginBtn" title="Ingresar">Ingresar</a>
            <a href="<?php echo base_url('jobseeker-signup');?>" class="hiringbtn">Registrarse</a>
        </div>
        <div class="clearfix"></div>
  </div>
</div>
<?php endif; ?>