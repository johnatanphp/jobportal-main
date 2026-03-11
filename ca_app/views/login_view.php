<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <?php $this->load->view('common/before_head_close'); ?>
    <style type="text/css">
            
        body { 
            background: #f4f5fb;
            
            background: hsla(227, 39%, 93%, 1);
            
            background: linear-gradient(90deg, hsla(227, 39%, 93%, 1) 0%, hsla(210, 17%, 98%, 1) 100%);
            
            background: -moz-linear-gradient(90deg, hsla(227, 39%, 93%, 1) 0%, hsla(210, 17%, 98%, 1) 100%);
            
            background: -webkit-linear-gradient(90deg, hsla(227, 39%, 93%, 1) 0%, hsla(210, 17%, 98%, 1) 100%);
            
            filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#E6E9F4", endColorstr="#F8F9FA", GradientType=1 );
        }

        .topheader, .siteWraper { 
            background: transparent;
        }
      
      .container-login-error {
        color: red;
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
              <span style="text-align: center;display: block;font-size:14px;font-weight:bold;color: #024880;text-transform: uppercase;opacity: 0.8;">Portal Empleo</span>

              <div class="tab-content" style="margin-top: 20px;">
                <div id="tab-login-seeker" class="tab-pane fade in active">
                 
                 <?php echo form_open('login', ['id' => 'login-form']); ?>

                  <div class="container-login-error"></div>
                  
                  <div class="row">
                    <div class="col-md-12 <?php echo form_error('email') ? 'has-error' : ''; ?>">
                      <div class="">
                        <label>Correo</label>
                        <input type="text" name="email" id="email" class="form-control form-control-login" value="<?php echo set_value('email'); ?>" placeholder="Correo" />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 <?php echo form_error('pass') ? 'has-error' : ''; ?>">
                      <label>Contraseña</label>
                      <input type="password" name="pass" id="pass" autocomplete="off" value="" class="form-control form-control-login" placeholder="Contraseña" />
                    </div>
                  </div>
                  <div class="row loginbox-footer">
                    <div class="col-md-12 column-links" align="center">
                      <table width="100%">
                        <tr>
                            <td align="left" width="50%">
                                <a href="<?php echo site_url('jobseeker-signup');?>" >Regístrate</a>
                            </td>
                            <td align="right" width="50%">
                            <a href="<?php echo site_url('forgot');?>" >¿Olvidaste tu contraseña?</a>
                            </td>
                        </tr>
                      </table>
                      <br />
                    </div>
                  
                    <div class="col-md-12">
                      <input id="login-submit" type="submit" value="Iniciar sesión" class="btn btn-primary btn-block" />
                    </div>
                  </div>
                 <?php echo form_close(); ?>
                 
                 <div style="padding: 1.2em 5px 0.4em;text-align: center;border-top: 1px solid #ddd;margin-top: 2em;">
                     <div>
                         ¿Eres empresa? 
                     </div>
                     <div>
                         <a href="<?php echo site_url('employer-login'); ?>" style="text-decoration: underline;">Ingresa como Empresa</a>
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
    -->
    <script type="text/javascript">
    </script>
  </body>
</html>    <script type="text/javascript">
        function loginSeekerSubmit(e) {         
          var url = $( "#login-form" ).prop('action');
          var data = $( "#login-form" ).serialize();

          var btnSubmit = $( "#login-submit" );
          btnSubmit.prop('disabled', true);
          btnSubmit.val('Espere...');
          $( ".container-login-error", '#login-form' ).empty();

          $.post(url, data, function(response) {
            if (response.success) {
              window.location = response.redirect;
              return;
            }

            $( ".container-login-error", '#login-form' ).html(response.message);
            btnSubmit.prop('disabled', false);
            btnSubmit.val('Iniciar sesión');
          }, 'json')
          .error(function() {
            $( ".container-login-error", '#login-form' ).html('¡No se pudo realizar la solicitud!');
            btnSubmit.prop('disabled', false);
            btnSubmit.val('Iniciar sesión');
          });
        }

        $(function(){
          $( "#login-form" ).submit(function(e){
            e.preventDefault();
            loginSeekerSubmit(e);
            return false;
          });
        });
    </script>
  </body>
</html>
