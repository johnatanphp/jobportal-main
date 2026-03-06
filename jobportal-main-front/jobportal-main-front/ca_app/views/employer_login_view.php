<!DOCTYPE html>
<html lang="es_pe">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <?php $this->load->view('common/before_head_close'); ?>
    <style type="text/css">
      
        body {
            /* 1. Especifica la URL de la imagen */
            background-image: url('public/images/f3.jpg'); 
            
            /* 2. Propiedades de Ajuste */
            
            /* Evita que la imagen se repita */
            background-repeat: no-repeat;
            
            /* Asegura que la imagen cubra todo el fondo sin cortarse */
            background-size: cover; 
            
            /* Fija la imagen para que no se desplace con el contenido */
            background-attachment: fixed;
            
            /* Centra la imagen en la pantalla */
            background-position: center center;            
        }
      
        body::before {
            content: ''; /* Obligatorio en pseudo-elementos */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            
            
            background-color: rgba(3, 3, 3, 0.3); 
            
            z-index: -1; 
        }
    
        .topheader, .siteWraper { 
            background: transparent;
        }
    
        .loginbox {
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 480px;
        }
        
        #login-submit, 
        #company-login-submit,
        .form-control-login {
            height: 40px;
        }
        
        .container-login-error {
            color: red;
        }       
        
        .innerpages {
            padding-top: 4em;
        }
       
        @media (max-width: 780px) {
            .innerpages {
                padding-top: 0;
            }
            
            .loginbox {
                border: 0;
                box-shadow: none; 
                padding: 10px;
                max-width: 480px;
            }
              
            body {
                background: #ffffff;
            }
            
            body::before {
                background: #ffffff;
            }
        }
        
        @media (max-height: 600px) {
              
            .innerpages {
                padding-top: 0;
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
    <div class="container innerpages">
     <?php $this->load->view('common/bottom_ads');?>
     
      <div class="row"> 
        <!--Signup-->
        <div class="col-md-12">
          
          <!--Login-->
          <div class="loginbox">
            <?php echo $this->session->flashdata('msg'); ?>
            <div style="text-align: center;padding: 1em 0 0.6em;">
                <a href="<?php echo site_url(); ?>">
                    <img src="<?php echo base_url('public/images/overall_blue.png');?>" style="height: 32px;" />
                </a>
            </div>
            <div style="padding:0px 0;">
              <span style="text-align: center;display: block;font-size:14px;font-weight:bold;color: #024880;text-transform: uppercase;opacity: 0.8;">Portal Empleo Empresa</span>               
              <div class="tab-content" style="margin-top: 20px;">                
                <div id="tab-login-company" class="tab-pane fade in active">
                  <?php echo form_open('company_login', ['id' => 'company-login-form']); ?>
                    <div>
                      <div class="container-login-error"></div>
                      <div class="row">
                        <div class="col-md-12">
                          <div>
                            <label>Correo</label>
                            <input type="text" name="company_email" id="company-email" class="form-control form-control-login" value="" placeholder="Correo" />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Contraseña</label>
                          <input type="password" name="company_pass" id="company-pass" autocomplete="off" value="" class="form-control form-control-login" placeholder="Contraseña" />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 column-links" align="center">
                          <table width="100%">
                            <tr>
                              <td width="50%" align="right" colspan="2">
                                <a href="<?php echo site_url('forgot?user_type=2');?>" >¿Olvidaste tu contraseña?</a>
                              </td>
                            </tr>
                          </table>
                          <br />
                        </div>

                        <div class="col-md-12">
                          <div class="rinput-group">
                            <div id="grecaptcha-login-company"></div>
                          </div>
                          <input id="company-login-submit" type="submit" value="Iniciar sesión" class="btn btn-primary btn-block company-login-submit" />
                        </div>
                      </div>
                    </div>
                  <?php echo form_close(); ?>
                  
                  <div style="padding: 1.2em 5px 0.4em;text-align: center;border-top: 1px solid #ddd;margin-top: 2em;">
                      <div>
                          ¿Buscas empleos? 
                      </div>
                      <div>
                          <a href="<?php echo site_url('login'); ?>" style="text-decoration: underline;">Ingresa como Postulante</a>
                      </div>
                  </div>
                  
                  <div style="padding: 0.4em 5px 0.5em;text-align: center;margin-top: 2em;">
                      <div style="color: #999;font-size: 12px;">
                          © <?php echo date('Y'); ?> Corporativo Overall
                      </div>
                  </div>
                  
                </div>
                            
              </div>
            </div>
          </div>
        
      </div>
        <!--/Login--> 

      </div>
    </div>

    <?php $this->load->view('common/bottom_ads');?>
    <!--Footer-->
    <?php $this->load->view('common/footer'); ?>
    <?php $this->load->view('common/before_body_close'); ?>
    <!--
    <script src='https://www.google.com/recaptcha/api.js'></script>
    -->
    <script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback" async defer></script>
    <script type="text/javascript">
        function loginCompanySubmit(e) {
          var url = $( "#company-login-form" ).prop('action');
          var data = $( "#company-login-form" ).serialize();

          var btnSubmit = $( "#company-login-submit" );
          btnSubmit.prop('disabled', true);
          btnSubmit.val('Espere...');
          $( ".container-login-error", '#company-login-form' ).empty();

          $.post(url, data, function(response) {
            if (response.success) {
              window.location = response.redirect;
              return;
            }

            $( ".container-login-error", '#company-login-form' ).html(response.message);
            btnSubmit.prop('disabled', false);
            btnSubmit.val('Iniciar sesión');
          }, 'json')
          .error(function() {
            $( ".container-login-error", '#company-login-form' ).html('¡No se pudo realizar la solicitud!');
            btnSubmit.prop('disabled', false);
            btnSubmit.val('Iniciar sesión');
          })
          .always(function(){
            grecaptcha.reset(0);
          });
        }

        var CaptchaCallback = function() {
          var sitekey = "<?php echo $this->config->item('google_recaptcha_api_invisible_site_key'); ?>";
          grecaptcha.render('grecaptcha-login-company', {'sitekey' : sitekey,  'callback' : loginCompanySubmit, 'size' : 'invisible'});
        };

        $(function(){
          $( "#company-login-form" ).submit(function(e){
            e.preventDefault();

            if ($.trim($( "#company-email").val()) != '' && $.trim($( "#company-pass").val()) != '') {
              grecaptcha.execute(0);
            }

            return false;
          });
        });
    </script>
  </body>
</html>