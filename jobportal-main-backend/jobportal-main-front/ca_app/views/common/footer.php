
<style>
  .social a {
    font-size: 24px;
  }

  .footerWrap {
    visibility: visible;
    background: #064271;
    padding: 20px 0 15px 0;
  }

  .show-rs-doc-comments {
    display: none;
  }

  @media (max-width: 991px) {
    .footerWrap {
      height: auto;
      position: relative;
    }

    .footerWrap .footer-extra {
      display: none;
    }

    .footerWrap .logo {
      text-align: center;
    }
  }

  .footer-extra h5 {
    padding-bottom: 12px;
  }

  .container-country-selector .select2-container .select2-selection--single {
    height: auto;
    padding: 3px 2px;
    border: 1px solid #ccc;
    border-radius: 3px;
    background: #eee;
  }

  .container-country-selector .select2-container .select2-selection--single .select2-selection__arrow {
    height: 38px;
    top: 50%;
    transform: translateY(-50%);
    right: 5px;
  }

  .container-country-selector .select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 1px;
  }

  .flag-wrapper {
    display: flex;
    align-items: center;
  }

  .flag-wrapper span {
    font-size: 15px;
  }
  
  .flag-emoji {
    margin-right: 10px;
  }

  #select2-country-selector-results li {
    padding: 3px 10px;
  }

</style>  
<?php if (!in_array($this->uri->segment(1), ['login', 'employer-login'])  ): ?>
<div class="footerWrap">
  <div class="container" style="padding-top: 15px;">
  <div class="logo col-md-3">
      <div><img src="<?php echo base_url('public/images/overall_white.png');?>" alt="Portal Empleo" height="26"/></div>
      <?php $countries_operations = $this->Country->all(['has_operation_overall' => 1]); ?>
      <?php if (count($countries_operations) > 1): ?>
        <div class="container-country-selector" style="padding: 25px 0 0 0;">
          <select id="country-selector" style="width: 160px;">
            <?php $visitor = $this->session->userdata('visitor'); ?>
            <?php foreach ($countries_operations as $country_operation): ?>
              <option value="<?php echo $country_operation->iso_3166_1_alpha2; ?>" 
                      <?php echo $visitor['selected_country_id'] == $country_operation->ID ? 'selected' : ''; ?>
                      data-flag="<?php echo $country_operation->flag_icon; ?>">
                      <?php e($country_operation->country_name); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php endif; ?>
      <br />
      <!--Social-->  
      <div class="social hide">
        <a href="https://www.facebook.com/corporativooverall/" target="_blank"> <i class="fa fa-facebook-square" aria-hidden="true"></i></a>
        <a href="https://twitter.com/overallbusiness" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
        <a href="https://www.youtube.com/user/CorpOverallBusiness/" target="_blank"><i class="fa fa-youtube-square" aria-hidden="true"></i></a>
        <a href="https://pe.linkedin.com/in/overallbusiness" target="_blank"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>
      </div>
    
    </div>
    <div class="col-md-3 footer-extra">
      <h5>Enlaces rápidos</h5>
      <ul class="quicklinks">
        <li><a href="<?php echo base_url('about-us.html');?>" title="Nosotros">Nosotros</a></li>
        <li><a href="<?php echo base_url('how-to-get-job.html');?>" title="Recomendaciones">Recomendaciones</a></li>
        <li><a href="<?php echo base_url('contact-us');?>" title="Contáctanos">Contáctanos</a></li>
        <li>
          <a href="<?php echo site_url('cookies-policy.html'); ?>">Política de cookies</a>
        </li>
        <li>
          <a href="<?php echo site_url('privacy-policy.html '); ?>">Política de privacidad</a>
        </li>
        <li>
          <a href="<?php echo site_url('terms.html'); ?>">Condiciones de uso</a>
        </li>
      </ul>
    </div>
    
    <div class="col-md-3 footer-extra">
      <h5>Industrias populares</h5>
      <ul class="quicklinks">
        <?php
      $res_inds = $this->Industry->get_top_industries();
      if($res_inds):
        foreach($res_inds as $row_inds):
    ?>
        <li><a href="<?php echo base_url('jobs-industry-'.$row_inds->slug);?>.html" title="<?php echo $row_inds->industry_name;?>"><?php echo $row_inds->industry_name;?></a></li>
        <?php 

          endforeach;

        endif;

      ?>
      </ul>
    </div>
    <div class="col-md-3 footer-extra">
      <h5>Redes sociales</h5>
      <ul class="quicklinks">
        <li><a href="https://www.facebook.com/corporativooverall/" target="_blank" >Facebook</a></li>
        <li><a href="https://twitter.com/overallbusiness/" target="_blank">Twitter</a></li>
        <li><a href="https://www.youtube.com/user/CorpOverallBusiness/" target="_blank">Youtube</a></li>
        <li><a href="https://pe.linkedin.com/in/overallbusiness/" target="_blank">Linkedin</a></li>
      </ul>
      <div class="clear"></div>
    </div>
    
    <div class="clear"></div>

  </div>
  <div style="border-top: 1px solid #49637e;text-align: center;color: #ccc;margin-top: 10px;padding: 10px 0 3px 0;">
    &copy; <?php echo date('Y'); ?> Corporativo Overall
  </div>
</div>
</div>

<div id="modal-load-user-profiles" class="modal fade" role="dialog"></div>
<div id="modal-menu-mobile" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<?php 
  $text_policy_site = get_text_policy_site();
?> 
<?php if ((!isset($_COOKIE["cookies_accepted"]) || $_COOKIE["cookies_accepted"] !== 'yes')): ?>
  <div id="wrapper-cookie-policy" >
    <div class="content-cookie-policy">
      <div class="remove-cookie-policy">
        <a id="btn-remove-cookie-policy" class="js-del-cookies-notification"  data-cookie="remove" href="#">
          <i class="glyphicon glyphicon-remove"></i>
        </a>
      </div>
      <div style="font-size: 14px;max-width: 800px;margin: 0 auto;">
        Utilizamos cookies propias y de terceros para el buen funcionamiento de este sitio, para más información revisa nuestra <a href="<?php echo site_url('cookies-policy.html'); ?>" target="_blank" >Política de cookies</a>.
        <br />
        <br />
        <button class="btn btn-accept-cookie js-del-cookies-notification" data-cookie="accept">Aceptar</button>
      </div>        
    </div>
  </div>
<?php endif; ?>

<script type="module">
  function formatState(state) {
    
    if (!state.id) {
      return state.text;
    }
  
    const element = state.element;
    const flagBase64 = element.dataset.flag;
    
    const $state = $(
      `<span class="flag-wrapper">
          <span class="flag-emoji">
            <img src="${flagBase64}" width="30" height="30">
          </span>
          <span>${state.text}</span>
      </span>`
    );
    
    return $state;
  }

  $( "#country-selector" ).select2({
    minimumResultsForSearch: 10,
    templateSelection: formatState,   
    templateResult: formatState
  });

  $( "#country-selector" ).change(function(){
    window.location = "<?php echo site_url(); ?>?country=" + this.value;
  });
</script>

<?php endif; ?>