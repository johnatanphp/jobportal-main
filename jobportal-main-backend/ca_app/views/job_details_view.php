<!DOCTYPE html>
<?php $company_loc = urlencode($row_posted_job->company_location . ', ' . $row_posted_job->emp_city . ', ' . $row_posted_job->emp_country . ', (' . $row_posted_job->company_name . ')'); ?>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    
    <meta property="og:url" content="<?php echo $job_url;?>"/>
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $title;?>" />
    <meta property="og:description" content="<?php $pp = str_replace(chr(13),'<br />',$row_posted_job->job_description); echo strip_tags($pp,'<br>');?>" />
    <meta property="og:image" content="<?php echo base_url('public/uploads/employer/'.$company_logo);?>" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style type="text/css">
      .label-radio,
      .label-check {
        display: block;
        cursor: pointer;
      }
      
      .question-required {
        color: red;
        font-size: 16px;
      }
      
      .question-item {
        margin: 3px 0;
      }

      .question-item.error {
        background: #fcd0d0;
        border: 1px solid red;
      }

      .question-item table tr td {
        text-align: center;
        padding: 2px 1px;
        vertical-align: middle;
      }

      .label-check i,
      .label-radio i {
        color: #333;
        vertical-align:text-bottom;
      }
    </style>
    <title>
      <?php echo $title;?>
    </title>
    <?php $this->load->view('common/before_head_close'); ?>
    <?php $len=0;
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){
    	$len = 1;	
    }
    ?>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); 
$s = ($currently_opened_jobs>1)?'s':'';
?>

<!--/Header--> 
<!--Detail Info-->
<div class="container detailinfo">
  <input type="hidden" id="jid" name="jid" value="<?php echo $row_posted_job->ID;?>"/>
  <div class="row">
    <div class="col-md-10">
      <div id="msg"></div>
      <?php if($is_already_applied=='yes'):?>
      <div class="alert alert-info"> <a href="#" class="close" data-dismiss="alert">&times;</a><strong>¡Aviso!</strong> Ya te postulaste para este empleo.</div>
      <?php endif;?>
      <div class="row"> 
        
        <!--Company Info-->
        
        <div class="col-md-4">
          <div class="companyinfoWrp">
            <?php
              $job_title = word_limiter(strip_tags(str_replace('-',' ',$row_posted_job->job_title)),7);
              $company_img_pic_url = img_pic_company($row_posted_job->company_logo);  

            ?>
            <h1 class="jobname"><?php echo humanize($job_title);?></h1>
            <div class="jobthumb"><img src="<?php echo $company_img_pic_url; ?>" alt="<?php echo base_url('companies/'.$row_posted_job->company_slug);?>" /></div>
            <div class="jobloc"> <a href="<?php echo base_url('companies/'.$row_posted_job->company_slug);?>" class="companyname" title="<?php echo $row_posted_job->company_name;?>"><?php echo $row_posted_job->company_name;?></a>
              <div class="location"><?php echo $row_posted_job->emp_city;?> &nbsp;-&nbsp; <?php echo $row_posted_job->emp_country;?></div>
              <a href="<?php echo base_url('companies/'.$row_posted_job->company_slug);?>" class="currentopen" title="<?php echo $currently_opened_jobs.' Trabajo'.$s.' en '.$row_posted_job->company_name;?>"><?php echo $currently_opened_jobs;?> empleo<?php echo $s;?> abierto<?php echo $s; ?> actualmente</a> </div>
            <div class="clear"></div>
          </div>
          
          <!--Apply-->
          
          <div class="actionBox">
            <p></p>
              <a href="#" class="<?php echo ($is_already_applied=='yes')?'applyjobgray':'applyjob';?>">
                <span>Aplicar</span>
              </a> <!--<a href="#" class="refferbtn"><span>Email to Friend</span></a>--> 
          </div>
          <div class="mapbox">
            <iframe src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $company_loc;?>&amp;ie=UTF8&amp;hq=&amp;hnear=<?php echo $company_loc;?>&amp;t=m&amp;z=14&amp;iwloc=near&amp;output=embed" width="100%" height="250" frameborder="0" style="border:0"></iframe>
          </div>
          <div style="text-align:center;">
            <?php echo $row_posted_job->company_location . ', ' . $row_posted_job->emp_country;?>
          </div>
        </div>
        <div class="col-md-8"> 
          
          <!--Job Detail-->
          
          <div class="boxwraper">
            <div class="titlebar">
              <div class="row">
                <div class="col-sm-6">Detalles del empleo</div>
                <?php if($len){?>
                <div class="col-sm-6 text-right"><a href="javascript:;" onClick="window.history.back(-1);">Volver a buscar</a></div>
                <?php }?>
              </div>
            </div>
            
            <!--Job Detail-->
            
            <div class="row"> 
              
              <!--Requirements-->
              
              <div class="col-md-12">
                <ul class="reqlist">
                  <li>
                    <div class="col-sm-6">Área del empleo:</div>
                    <div class="col-sm-6"><?php echo $row_posted_job->industry_name;?></div>
                    <div class="clear"></div>
                  </li>
                  <li>
                    <div class="col-sm-6">Vacantes:</div>
                    <div class="col-sm-6"><?php echo $row_posted_job->vacancies;?></div>
                    <div class="clear"></div>
                  </li>
                  <li>
                    <div class="col-sm-6">Jornada laboral:</div>
                      <div class="col-sm-6">
                        <?php echo job_mode_text($row_posted_job->job_mode);?>
                      </div>
                    <div class="clear"></div>
                  </li>
                  <li>
                    <div class="col-sm-6">Permitir personas con discapacidad:</div>
                      <div class="col-sm-6">
                        <?php echo $row_posted_job->allow_people_disability == 'yes' ? 'Sí' : 'No'; ?>
                      </div>
                    <div class="clear"></div>
                  </li>
                  <?php if ($row_posted_job->show_salary_in_ad == 'yes'): ?>
                    <li>
                      <div class="col-sm-6">Salario:</div>
                      <div class="col-sm-6">
                        <?php if ($row_posted_job->minimum_payment && $row_posted_job->maximum_payment):
                            echo $row_posted_job->payment_currency . ' ' . $row_posted_job->minimum_payment . ' - ' . $row_posted_job->maximum_payment;
                          else:
                            echo "No asignado";
                          endif;
                        ?>  
                        </div>
                      <div class="clear"></div>
                    </li>
                  <?php endif; ?>
                  <li>
                    <div class="col-sm-6">Ubicación del empleo:</div>
                    <div class="col-sm-6"><?php echo ($row_posted_job->city ? $row_posted_job->city : '') .  ', ' . $row_posted_job->country;?></div>
                    <div class="clear"></div>
                  </li>
                  <?php if (!empty($row_posted_job->qualification)): ?>
                  <li>
                    <div class="col-sm-6">Educación:</div>
                    <div class="col-sm-6"><?php echo $row_posted_job->qualification;?></div>
                    <div class="clear"></div>
                  </li>
                  <?php endif; ?>

                  <?php if (!empty($row_posted_job->experience)): ?>
                    <li>
                      <div class="col-sm-6">Experiencia:</div>
                      <div class="col-sm-6">
                        <?php  echo @$this->Work_experience->find(['code' => $row_posted_job->experience])->name; ?>
                      </div>
                      <div class="clear"></div>
                    </li>
                  <?php endif; ?>
                  
                  <?php if($row_posted_job->age_required):?>
                  <li>
                    <div class="col-sm-6">Edad requerida:</div>
                    <div class="col-sm-6"><?php echo $row_posted_job->age_required;?> Years</div>
                    <div class="clear"></div>
                  </li>
                  <?php endif;?>
                  <li>
                    <div class="col-sm-6">Fecha de publicación:</div>
                    <div class="col-sm-6"><?php echo ucwords(_date_locale_format(strtotime($row_posted_job->dated), 'MMM dd, y'));?></div>
                    <div class="clear"></div>
                  </li>
                  <li>
                    <div class="col-sm-6">Responsable:</div>
                    <div class="col-sm-6">
                      <?php echo $row_posted_job->first_name; ?>    
                    </div>
                    <div class="clear"></div>
                  </li>
                </ul>
              </div>
              
              <div class="clear"></div>
            </div>
            
            <?php if ($company->system_internal && strtotime($row_posted_job->dated) >= strtotime('2022-04-07 00:00:00')): ?>
              <!--Job Description-->
              <div class="jobdescription" style="padding-bottom: 0;">
                <br />
                <div class="row">
                  <div class="col-md-12" style="text-align: justify;">
                    <h2 class="normal-details">                      
                      <?php if ($row_posted_job->city): ?>
                        En Overall, ¡Creemos que tu talento merece la mejor Oportunidad! actualmente nos encontramos en la búsqueda del mejor talento para cubrir la posición de <b><?php echo $row_posted_job->job_title; ?></b>,  para la Localidad de <b><?php echo $row_posted_job->city; ?></b>.
                      <?php endif; ?>
                      
                      <?php if (!$row_posted_job->city): ?>
                        En Overall, ¡Creemos que tu talento merece la mejor Oportunidad! actualmente nos encontramos en la búsqueda del mejor talento para cubrir la posición de <b><?php echo $row_posted_job->job_title; ?></b>.
                      <?php endif; ?>
                      ¡Ven postula con Nosotros!
                    </h2>
                  </div>
                </div>
              </div>
            <?php endif; ?>
      
            <div class="jobdescription">
              <div class="row">
                <div class="col-md-12">
                  <div class="subtitlebar">Descripción del empleo</div>
                  <p>
                  <h2 class="normal-details">
                    <?php
                      echo str_replace('&nbsp;', ' ', $row_posted_job->job_description);
                    ?>
                    <?php $job_benefits_array = explode(',', $row_posted_job->laboral_benefits); ?>
                    <?php if ($job_benefits_array && $job_benefits_array[0] != ''): ?>
                    <div style="padding-top: 10px;">
                      <b>Beneficios adicionales</b>
                      <div class="benefits-box">
                        <?php 
                        foreach ($job_benefits_array AS $labor_benefit):
                        ?>
                          <ul>
                            <li><i class="glyphicon glyphicon-ok"></i><?php echo $labor_benefit?></li>
                          </ul>
                        <?php
                        endforeach;
                        ?>
                      </div>
                    </div>
                    <?php endif; ?>
                  </h2>
                  </p>
                </div>
                
                <?php if($required_skills && $required_skills[0]!=''):?>
                <div class="col-md-12">
                  <div class="subtitlebar">
                    Habilidades requeridas
                    
                  </div>
                  <div class="skillBox">
                    <ul class="skillDetail">
                      <?php foreach($required_skills as $skill):?>
                      <li><?php echo $skill;?></li>
                      <?php endforeach;?>
                      <div class="clear"></div>
                    </ul>
                  </div>
                </div>
                <?php endif;?>
                <div class="col-md-12">
                  <div class="box-share-job" style="display:none;">
                    <span class="info-share-job">Compartir en: </span>
                    <a class="link-share-job" href="#" data-href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $job_url; ?>">
                      <img src="<?php echo base_url('public/images/facebook-32.png'); ?>" width="20" >
                    </a>
                    <a class="link-share-job" href="javascript:void(0);" data-href="https://www.linkedin.com/shareArticle?url=<?php echo $job_url; ?>">
                      <img src="<?php echo base_url('public/images/linkedin-32.png'); ?>" width="20">
                    </a>
                  </div>
                </div>
              </div>
              <div class="actionBox footeraction">
                <a href="#" class="<?php echo ($is_already_applied=='yes')?'applyjobgray':'applyjob';?>"><span>Aplicar</span></a> </div>
              <div class="clear">&nbsp;</div>
            
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    
    <!--/Job Detail-->
    
    <?php $this->load->view('common/right_ads');?>
  </div>
</div>
<?php if ($row_posted_job->has_questions == 'yes'): ?>
<div class="modal fade" id="japply">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form-questions-apply" onsubmit="return submitFormQuestionApply(this);">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Responda las siguiente preguntas antes de aplicar</h4>
        </div>
        <div class="modal-body">
            
            <div id="emsg"></div>
        
            <?php $i = 0; ?>
            <?php foreach($job_questions as $key => $row_question): ?>
            <div class="row">
                <div class="col-md-12">
                    
                    <div class="question-item" style="padding:10px 3px;">
                      <label style="padding-bottom: 3px;"><?php echo (++$i) . ") " . $row_question->question;?> <span class="question-required"><?php echo $row_question->required == 'yes' ? '*' : '';?></span></label>
                      <br/>
                      <?php
  
                        if ($row_question->type_question == 'multiple_choice'):
                          if ($row_question->value != null) {
                            $value = json_decode($row_question->value, true);
                            $question_options = $value['options'];
                          }

                          foreach ($question_options as $option_val => $option_text):
                            
                            $data = array(
                              'name' => 'answers[' . $row_question->ID . '][value]',
                              'value' => $option_text,
                              'class' => 'answer-radio ' . ($row_question->required == 'yes' ? 'required' : '')
                            );

                            echo form_label(form_radio($data) . $option_text, '', array('class' => 'label-radio'));
                           
                          endforeach;
                        endif;

                        if ($row_question->type_question == 'dropdown'):
                          if ($row_question->value != null):
                            $value = json_decode($row_question->value, true);
                            $question_options = array();

                            foreach ($value['options'] as $key => $value):
                              $question_options[$value] = $value;
                            endforeach;
                          endif;

                          echo form_dropdown('answers[' . $row_question->ID . '][value]', (array('' => 'Seleccione') + $question_options), "", "class='form-control " . ($row_question->required == 'yes' ? 'required' : '') . "'");
                        endif; 
                        
                        if ($row_question->type_question == 'checkbox'):
                          if ($row_question->value != null):
                            $value = json_decode($row_question->value, true);
                            $question_options = $value['options'];
                          endif;

                          foreach ($question_options as $option_val => $option_text):
                            $data = array(
                              'name' => 'answers[' . $row_question->ID . '][value][]',
                              'value' => $option_text,
                              'class' => 'answer-check ' . ($row_question->required == 'yes' ? 'required' : '')
                            );

                            echo form_label(form_checkbox($data) . $option_text, '', array('class' => 'label-check'));
                          endforeach;
                        endif;

                        if ($row_question->type_question == 'simple_answer'):
                      
                          $attributes = array(
                            'name' => 'answers[' . $row_question->ID . '][value]',
                            'class' => 'form-control ' . ($row_question->required == 'yes' ? 'required' : '')
                          );
                          echo form_input($attributes);
                        endif;

                        if ($row_question->type_question == 'long_text'):
                      
                          $attributes = array(
                            'name' => 'answers[' . $row_question->ID . '][value]',
                            'class' => 'form-control ' . ($row_question->required == 'yes' ? 'required' : '')
                          );

                          echo form_textarea($attributes);
                       
                        endif;

                        if ($row_question->type_question == 'multiple_choice_grid'):
                          if ($row_question->value != null):
                              $value = json_decode($row_question->value, true);
                              $rows = $value['rows'];
                              $columns = $value['columns'];
                              
                              echo "<table width='100%'>";
                              echo "<tr>";
                              echo "<td></td>";

                              foreach ($columns as $key => $column) {
                                echo "<td>" . $column . "</td>";
                              }

                              echo "</tr>";

                              foreach ($rows as $row_key => $row):
                                
                                echo "<tr>";
                                echo "<td>" . $row . "</td>";
                              
                                foreach ($columns as $col_key => $column): 
                                  
                                  $data = array(
                                    'name' => 'answers[' . $row_question->ID . '][value][' . $row_key. ']',
                                    'value' => $row . '-' . $column,
                                    'class' => 'answer-radio ' . ($row_question->required == 'yes' ? 'required' : '')
                                  );

                                  echo "<td>" . form_label(form_radio($data)) . "</td>"; 
                                
                                endforeach;

                                echo "</tr>";
                              
                              endforeach;
                              
                              echo "</table>";  
                          endif;

                        endif;
                      ?>
                    </div>
                </div>
            </div>
          <?php endforeach; ?>
          
        </div>

        <div class="modal-footer">
          
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" name="submitter" class="btn btn-primary">Aplicar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<!--Scam-->
<div class="modal fade" id="scam">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Contactar al empleador</h4>
      </div>
      <div class="modal-body">
        <div id="scam_emsg"></div>
        <div class="box-body">
          <div class="form-group">
            <label>Nombre de la empresa: <span style="font-weight:normal;"><?php echo $row_posted_job->company_name;?></span></label>
          </div>
          <div class="form-group">
            <label>Trabajo: <span style="font-weight:normal;"><?php echo $row_posted_job->job_title;?></span></label>
          </div>
          <div class="form-group">
            <label>Mensaje:</label>
            <textarea id="reason" name="reason"  class="form-control" rows="4"><?php echo set_value('reason');?></textarea>
            <?php echo form_error('reason'); ?>
          </div>
          <div class="form-group">
            <div class="input-group <?php echo (form_error('g-recaptcha-response'))?'has-error':'';?>" style="width:100%;">
              <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('google_recaptcha_api_site_key'); ?>"></div>
              <?php echo form_error('g-recaptcha-response'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="scjid" name="scjid" value="<?php echo $row_posted_job->ID;?>"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" name="scam_submit" id="scam_submit" class="btn btn-primary">Enviar mensaje</button>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
  $(document).ready(function() {

    $( ".link-share-job" ).click(function(){
      var url = $(this).data('href');
      window.open(url , "Compartir Trabajo" , "width=350,height=500,scrollbars=NO");
    });

    $( '.answer-check' ).check();
    $( '.answer-radio' ).radio();

  });
</script>

<script type="text/javascript">
$( document ).ready(function() {
  var is_already_applied = '<?php echo $is_already_applied;?>';
  var has_questions = '<?php echo $row_posted_job->has_questions; ?>';
  var is_session_jobseeker = '<?php echo $this->session->userdata('is_job_seeker'); ?>';
  var is_user_login = '<?php echo $this->session->userdata('is_user_login'); ?>';

  $( ".applyjob" ).click(function() {

    if (!is_user_login) {
      window.location = "<?php echo site_url($this->uri->uri_string . '?apply=yes'); ?>";
      return;
    }

    if (!is_session_jobseeker) {
      return;
    }

    if (is_already_applied == 'yes') {
      bootbox.alert("Ya has solicitado este empleo!");
      return;
    }

    if (has_questions == 'yes') {
      $( "#japply" ).modal('show');  
      return;
    } 

    apply_job();
  });
    
  $("#scammer").click(function(e){
    e.preventDefault();
    $('#scam').modal('show');
  });

  <?php 
  if (@$_GET['sc']=='yes'){?>
    $('#scam').modal('show');
  <?php } ?>
});
</script>
</body>
</html>