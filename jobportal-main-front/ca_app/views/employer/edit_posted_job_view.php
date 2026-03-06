<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo base_url('public/autocomplete/demo.css'); ?>">
<style>
.ui-button {
  margin-left: -1px;
}
.ui-button-icon-only .ui-button-text {
  padding: 0.35em;
}
.ui-autocomplete-input {
  margin: 0;
  padding: 0.48em 0 0.47em 0.45em;
}

.ui-widget-header .ui-icon {
  background-image: url("../../public/images/ui-icons_444444_256x240.png");
}

.ui-datepicker .ui-datepicker-next-hover,
.ui-datepicker .ui-datepicker-prev-hover {
  top: 2px;
}
.label-text {
  color: #333 !important;
}

#info-required-skill {
  color: #333;
  font-size: 15px;
  font-style: italic;
}

#content-form-questions .error {
  border: 1px solid #a94442;
}

.msg-error-questions {
  padding: 8px;
  color: #a94442;
  background: #f2dede;
  margin-bottom: 10px;
  border-radius: 5px;
  border:1px solid #a94442;
}

.msg-error-questions p {
  font-size: 13px;
}

.msg-info-request {
  font-style:italic;
  background: #fff;
  font-size:15px;
  margin-bottom:20px;
  padding: 15px 10px;
  border: 1px solid #0d7daa;
  border-left:4px solid #0d7daa;
}

.item-benefit {
  background:#e0e0e0;
  padding:4px;
  margin:2px;
  display:inline-block;
  border-radius: 5px;
}

</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row"> 
  <div class="col-md-3">
  <div class="dashiconwrp">
    <?php $this->load->view('employer/common/menu/sidebar');?>
  </div>
  </div>
  
  <?php echo form_open_multipart('employer/edit_posted_job/'.$id,array('name' => 'post_job_form', 'id' => 'post_job_form', 'onSubmit' => 'return validate_new_post_job_form(this);'));?>
    <div class="col-md-9">
    <?php echo $this->session->flashdata('msg');?>
      <div class="formwraper post-edit-job">
        <div class="titlehead">
          <a href="#" style="color:#fff;" class="_link-back">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Editar empleo
        </div>
        <div class="formint">
          <?php if ($request): ?>
            <div class="msg-info-request">
            Este empleo se creó a partir de la solicitud de empleo <a href="<?php echo site_url('employer/staff_requests/show/' . $request->ID); ?>" target="_blank"><?php echo $row->job_title; ?></a>
            </div>
          <?php endif; ?>
          <div class="info-required">
            <span>*</span> Campos obligatorios 
          </div>
          <div class="input-group <?php echo (form_error('industry_id'))?'has-error':'';?>">
            <label class="input-group-addon">Área del empleo <span>*</span></label>
            <?php if ($request && $request->request_type == 'external'): ?>
            <?php 
              $industry_id = $row->industry_ID;
              echo $this->Industry->get_industries_by_id($industry_id)->industry_name;
              echo form_hidden('industry_id', $industry_id);
            ?>
            <?php else: ?>
              <select name="industry_id" id="industry_id" class="form-control">
                <option value="" selected>Seleccione</option>
                  <?php foreach($result_industries as $row_industry):
                  $selected = ($row->industry_ID==$row_industry->ID)?'selected="selected"':'';
                  ?>
                  <option value="<?php echo $row_industry->ID;?>" <?php echo $selected;?>><?php echo $row_industry->industry_name;?></option>
                <?php endforeach;?>
              </select>
              <?php echo form_error('industry_id'); ?>
            <?php endif; ?>
          </div>
          
          <div class="input-group <?php echo (form_error('job_title'))?'has-error':'';?>">
            <label class="input-group-addon">Nombre del empleo <span>*</span></label>
              <input name="job_title" type="text" class="form-control" id="job_title" placeholder="Nombre del empleo" value="<?php echo $row->job_title; ?>" maxlength="150">
              <?php echo form_error('job_title'); ?>
          </div>
          <div class="input-group <?php echo (form_error('vacancies'))?'has-error':'';?>">
            <label class="input-group-addon">N° de vacantes <span>*</span></label>
            <?php if ($request): ?>
            <?php 
              $vacancies = $row->vacancies;
              echo $vacancies;
              echo form_hidden('vacancies', $vacancies);
            ?>
            <?php else: ?>
              <select name="vacancies" id="vacancies" class="form-control">
                <?php for ($i=1; $i <= 50; $i++):
                  $selected = ($row->vacancies == $i) ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
                <?php endfor;?>
              </select>
              <?php echo form_error('vacancies'); ?>
            <?php endif; ?>
          </div>
          <div class="input-group <?php echo (form_error('experience'))?'has-error':'';?>">
            <label class="input-group-addon">Experiencia requerida <span>*</span></label>
            <?php if ($request && $row->experience): ?>
            <?php
              echo @$this->Work_experience->find(['code' => $row->experience])->name;
              echo form_hidden('experience', $row->experience);
            ?>
            <?php else: ?>
              <select name="experience" id="experience" class="form-control">
                <option value="">Seleccione</option>
                <?php foreach ($result_experiences as $exp): ?>
                  <option value="<?php echo $exp->code; ?>" <?php echo ($exp->code == $row->experience)?'selected="selected"':'';?>>
                      <?php echo $exp->name; ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php echo form_error('experience'); ?>
            <?php endif; ?>
          </div>
          <div class="input-group <?php echo (form_error('job_mode'))?'has-error':'';?>">
            <label class="input-group-addon">Jornada laboral <span>*</span></label>
            <?php if ($request && $request->request_type == 'external'): ?>
            <?php
              echo job_mode_text($row->job_mode);
              echo form_hidden('job_mode', $row->job_mode);
            ?>
            <?php else: ?>
              <select name="job_mode" id="job_mode" class="form-control">
                <option value="full_time" <?php echo ($row->job_mode =='full_time')?'selected="selected"':'';?>>Full-Time</option>
                <option value="part_time" <?php echo ($row->job_mode =='part_time')?'selected="selected"':'';?>>Part-Time</option>
                <option value="per_hours" <?php echo ($row->job_mode =='per_hours')?'selected="selected"':'';?>>Por Horas</option>
                <option value="weekends" <?php echo ($row->job_mode =='weekends')?'selected="selected"':'';?>>Fines de Semana</option>
                <option value="telecommuting" <?php echo ($row->job_mode=='telecommuting')?'selected="selected"':'';?>>Teletrabajo</option>
              </select>
              <?php echo form_error('job_mode'); ?>
            <?php endif; ?>
          </div>

          <div class="input-group <?php echo (form_error('allow_people_disability')) ? 'has-error' : '';?>">
            <label class="input-group-addon">
              <span></span>Permitir Personas con Discapacidad
            </label>

             <input id="allow_people_disability" type="checkbox" name="allow_people_disability" value="yes" <?php echo ($row->allow_people_disability == 'yes' ? 'checked' : ''); ?>>
             <?php echo form_error('allow_people_disability'); ?>
          </div>
            
          <div class="input-group <?php echo (form_error('currency_pay') || form_error('min_pay') || form_error('max_pay')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Rango salarial<span></span></label>
            <?php if ($request && $row->minimum_payment && $row->maximum_payment): ?>    
            <?php
              $min_pay = !is_null($row->minimum_payment) ? $row->minimum_payment : '';
              $max_pay = !is_null($row->maximum_payment) ? $row->maximum_payment : '';

              if (!empty($min_pay)):
                echo $row->payment_currency . ' ' . $min_pay . ' a '. $max_pay;  
              else:
                echo 'No especificado';
              endif; 

              echo form_hidden('currency_pay', $country->currency_code);
              echo form_hidden('min_pay', $min_pay);
              echo form_hidden('max_pay', $max_pay);
            ?>
            <?php else: ?>  
              <div class="salary-range-wrapper">
                <table>
                  <tr>
                    <td width="15%" class="currency">
                      <input id="currency_pay" type="text" name="currency_pay" class="form-control" placeholder="Moneda" value="<?php echo set_value('currency_pay') ? set_value('currency_pay') : $row->payment_currency; ?>">
                    </td>
                    <td width="35%" class="salary-min">
                      <input id="min_pay" type="text" name="min_pay" class="form-control" placeholder="Mínimo" value="<?php echo set_value('min_pay') ? set_value('min_pay') : $row->minimum_payment; ?>">
                    </td>
                    <td width="5%" class="separator">a</td>
                    <td width="35%" class="salary-max">
                      <input id="max_pay" type="text" name="max_pay" class="form-control" placeholder="Máximo" value="<?php echo set_value('max_pay') ? set_value('max_pay') : $row->maximum_payment; ?>">
                    </td>
                  </tr>
                </table>
              </div>
              <?php echo form_error('currency_pay'); ?>
              <?php echo form_error('min_pay'); ?>
              <?php echo form_error('max_pay'); ?>
            <?php endif; ?>
            <label style="font-size: 13px;font-weight: normal;display: block;">
              <?php 
                $show_salary_in_ad = set_value('show_salary_in_ad') ? set_value('show_salary_in_ad') : $row->show_salary_in_ad;
              ?>
              <input type="checkbox" name="show_salary_in_ad" value="yes" <?php echo $show_salary_in_ad == 'yes' ? 'checked' : ''; ?>>
              Mostrar en el anuncio
            </label>
          </div>

          <div class="input-group <?php echo (form_error('last_date'))?'has-error':'';?>">
            <label class="input-group-addon">Finaliza<span> *</span></label>
            <?php
              $finished_job = strtotime($row->last_date) > strtotime(date('Y-m-d')) ? 'no' : 'yes';
            ?>
            <div>
              <?php if ($finished_job == 'yes'): ?>
                <span style="font-size: 13px;font-style: italic;"><i class="glyphicon glyphicon-warning-sign" style="color: #d4a813;"></i> Este empleo ha finalizado el día <?php echo _date_locale_format(strtotime($row->last_date), "dd 'de' MMMM 'del' y"); ?>.</span>
                <br />
                <label  style="font-size: 13px;font-weight: normal;">
                <input id="update_last_date" type="checkbox" name="update_last_date" value="yes">
                Actualizar la fecha de finalización
                </label>     
              <?php endif; ?>
              <input name="last_date" type="text" readonly class="form-control" id="last_date" placeholder="Finaliza" value="<?php echo _date_locale_format(strtotime($row->last_date),'dd/MM/y'); ?>" maxlength="40" style="<?php echo $finished_job == 'yes' ? 'display: none;' : ''; ?>">
              <input type="hidden" name="finished_job" value="<?php echo $finished_job; ?>">   
            </div>
            <?php echo form_error('last_date'); ?> </div>     
          <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
            <label class="input-group-addon">País <span>*</span></label>
              <?php 
                echo $country->country_name;
              ?>
              <input type="hidden" name="country" id="country", value="<?php echo $country->country_name; ?>">
            <?php echo form_error('country'); ?>
          </div>

          <div class="input-group <?php echo (form_error('city')) ? 'has-error':'';?>">
            <label id="city_label" class="input-group-addon"><span class="label-text">Ubicación </span><span>*</span></label>
            <select id="city_dropdown" name="city" class="form-control" style="width: 70%;">
              <option value="">Seleccione</option>
              <?php foreach ($result_ubigeos as $row_ubigeo): ?>
                <?php 
                  $city = (set_value("city") != '') ? set_value("city") : $row->city;
                  $selected = ($city == $row_ubigeo->ubigeo) ? 'selected="selected"' : ''; 
                ?>
                <option value="<?php echo $row_ubigeo->ubigeo; ?>" <?php echo $selected;?> ><?php echo $row_ubigeo->ubigeo; ?></option>
              <?php endforeach; ?>
            </select>  
            <input id="city_text" type="text" class="form-control" value="<?php echo (set_value("city")!='')?set_value("city"): $row->city; ?>" maxlength="50" style="display: none;">
            <?php echo form_error('city'); ?>
          </div>

          <div class="input-group <?php echo (form_error('qualification'))?'has-error':'';?>">
            <label class="input-group-addon">Grado de estudio<span></span></label>
            <?php if ($request && $row->qualification): ?>
            <?php
              echo $row->qualification;
              echo form_hidden('qualification', $row->qualification);
            ?>  
            <?php else: ?>
              <select name="qualification" id="qualification" class="form-control" style="width:50%">
                <option value="">Seleccione</option>
                <?php 
                foreach ($result_qualification as $row_qualification):
                $selected = ($row->qualification==$row_qualification->text)?'selected="selected"':'';
                ?>
                <option value="<?php echo $row_qualification->text;?>" <?php echo $selected;?>><?php echo $row_qualification->text;?></option>
                <?php endforeach;?>
              </select>
              <?php echo form_error('qualification'); ?>
            <?php endif; ?>
          </div>
          <div class="input-group <?php echo (form_error('job_description'))?'has-error':'';?>">
            <label class="input-group-addon">Descripción del empleo</label>
            <textarea name="editor1" id="editor1" cols="60" rows="10" ><?php echo $row->job_description; ?></textarea>
          </div>
          
          <div class="input-group <?php echo (form_error('laboral_benefits'))?'has-error':'';?>">
            <label class="input-group-addon">Beneficios laborales</label>
            <?php if ($request): ?>
            <?php 

              $laboral_benefits = trim((string)$row->laboral_benefits);

              if (empty($laboral_benefits)) {
                echo 'No especificado';
              }
              
              if (!empty($laboral_benefits)) {
                $job_benefits = explode(',', $laboral_benefits);

                foreach ($job_benefits as $benefit_name) {
                  echo '<span class="item-benefit">' . $benefit_name . '</span>';
                  echo form_hidden('laboral_benefits[]', $benefit_name);
                }
              }
            ?>
            <?php else: ?>
            <select id="laboral_benefits" name="laboral_benefits[]" class="form-control" style="width: 100%;" multiple>
                <?php $job_benefits_array = explode(',', $row->laboral_benefits); ?>
                <?php foreach ($result_laboral_benefits AS $labor_benefit): ?>
                  <option value="<?php echo $labor_benefit->benefit_name;?>" <?php echo in_array($labor_benefit->benefit_name, $job_benefits_array) ? 'selected="selected"' : '';?>><?php echo $labor_benefit->benefit_name;?></option>
                <?php endforeach; ?>
            </select>
            <?php endif;?>
          </div>
        </div>
      </div>
      
      <!--Required Skills-->
      <div class="formwraper">
        <div class="titlehead">Habilidades requeridas para el empleo</div>
        <div class="formint">
          <div class="jobdescription" style="border-top:0px;">
            <div class="row">
              <div class="col-md-12">
                 <div class="input-group">
                   <div class="skillBox">
                    <?php $s_val = form_error('s_val'); ?>
                    <span id="info-required-skill" style="<?php echo empty($s_val) ? 'display: none;' : ''; ?>">Ingrese al menos 1 habilidad requerida</span>
                    
                    <?php if (!empty($s_val)): ?>
                       <?php echo form_error('s_val'); ?>
                    <?php endif; ?>

                    <ul class="skillDetail" id="myskills">
                      <?php 
                        if ($row->required_skills):
                          $selected_skills = explode(', ', $row->required_skills);
                          foreach ($selected_skills as $each_skill):
                            if(trim($each_skill) != ''): ?>
                              <li>
                                <?php echo trim($each_skill);?> 
                                <a href="javascript:remove_job_skill('<?php echo trim($each_skill);?>');" class="delete"><i class="fa fa-times-circle"></i></a>
                              </li>
                          <?php 
                            endif;
                         endforeach;
                       endif;
                      ?>
                    </ul>
                    <div class="clear"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="clear"></div>
          </div>
          <div class="input-group">
            <label class="input-group-addon">Agregar habilidad <span>*</span></label>
            <div class="row">
              <div class="col-md-8">
              <div class="ui-widget">
                <input type="text" name="skill" id="skill" value="" autocomplete="off" class="form-control" />
                <input type="hidden" name="s_val" id="s_val" value="<?php echo (set_value('s_val')) ? set_value('s_val') : ', ' . $row->required_skills; ?>" class="form-control" />
              </div>
              </div>
              <div class="col-md-2">
                <input type="button" name="js_skill_add" id="js_skill_add" value="Agregar" class="btn btn-primary" />
              </div>
            </div>    
          </div>
        </div>
      </div>
      <!-- Questions form -->
      <div class="formwraper">
        <div class="titlehead">
          Formulario de preguntas
        </div>
        <div class="formint">
          <div class="jobdescription" style="border-top:0px;">
            <div class="row">
              <div class="col-md-12">
                <div>
                  <label class="label-check">
                    <input type="checkbox" name="has_questions" id="has-questions" value="yes" <?php echo $row->has_questions == "yes" ? "checked='checked'" : "";?>>
                    Este empleo tiene preguntas
                  </label>
                  <br />
                  <br />
                  <input id="add-question" type="button" name="" value="Agregar pregunta" class="btn btn-primary">
                </div>
                <br />
                <div id="content-form-questions"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Professional info-->
      <div class="formwraper">
        <div class="formint">
          <div align="center">
            <input type="submit" name="submit_button" id="submit_button" value="Guardar" class="btn btn-primary" />
          </div>
        </div>
      </div>
    </div>
    <!--/Job Detail-->    
    <?php echo form_close();?> </div>
</div>

<script id="tpl-question" type="text/template">        
  <div id="formQuestion-{{questionId}}" data-question-id="{{questionId}}" class="form-question" >
    <div class="row">
      <div class="col-md-6">
        <input type="text" name="question[{{questionId}}][name]" class="form-control question" value="{{questionName}}" placeholder="Pregunta" autocomplete="off">
      </div>
      <div class="col-md-4">
        <select name="question[{{questionId}}][type_question]" class="form-control type-question">

          <option value="simple_answer" {{#simple_answer}} selected="selected" {{/simple_answer}}>Respuesta simple</option>
          <option value="long_text" {{#long_text}} selected="selected" {{/long_text}}>Parrafo</option>
          <option value="multiple_choice" {{#multiple_choice}} selected="selected" {{/multiple_choice}}>Opción multiple</option>
          <option value="checkbox" {{#checkbox }} selected="selected" {{/checkbox}}>Casilla de verificación</option>
          <option value="dropdown" {{#dropdown}} selected="selected" {{/dropdown}}>Lista desplegable</option>
          
          <option value="multiple_choice_grid" {{#multiple_choice_grid}} selected="selected" {{/multiple_choice_grid}}>Cuadrícula de opción múltiple</option>
        
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="question-items" style="margin-top: 25px;">
          <div class="items-options"></div>
          <div class="multiple-choice-grid-options"> </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 question-footer">
        <div class="pull-right">
          <label style="padding-right: 4px;">
            <a href="#" class="del-question" onclick="event.preventDefault();$(this).closest('.form-question').remove();">Eliminar pregunta</a>
          </label>
          |
          <label class="label-check label-required">
            <input type="checkbox" name="question[{{questionId}}][required]" class="required-question" value="yes" {{#checkRequired}} checked="checked" {{/checkRequired}}>Obligatorio  
          </label>
        </div>      
      </div>
    </div>
  </div>
</script>
<script id="manager-items-options" type="text/template">
  <table class="table-items-options">
  </table>
  <div class="control-add-option">
    <a href="#" class="add-option" data-question-id="{{questionId}}" >Agregar opción</a>
  </div>
</script>

<script id="manager-multiple-choice-grid" type="text/template">
  <div class="row">
    <div class="col-md-6">
      <table class="table-rows">
      </table>
      <div class="control-add-option">
        <a href="#" class="add-row-option" data-question-id="{{questionId}}" >Agregar fila</a>
      </div>  
    </div>
    <div class="col-md-6">
      <table class="table-columns">
      </table>
      <div class="control-add-option">
        <a href="#" class="add-column-option" data-question-id="{{questionId}}" >Agregar columna</a>
      </div>
    </div>
  </div>      
</script>
<script id="new-item-option" type="text/template">
  <tr>
    <td>
      <input type='text' class="input-option simple-option" name="question[{{questionId}}][option][]" placeholder="Opción" autocomplete="off" value="{{optionValue}}">
    </td>
    <td>
      <a class="del-option" onclick="$(this).closest('tr').remove();">
        <img width="14" height="14" src="<?php echo base_url('public/images/delete-1-icon.png')?>">
      </a>
    </td>
  </tr>
</script>

<script id="new-row-option" type="text/template">
  <tr>
    <td>
      <input type='text' class="input-option row-option" name="question[{{questionId}}][rows][]" placeholder="Fila" autocomplete="off" value="{{rowValue}}">
    </td>
    <td>
      <a class="del-option" onclick="$(this).closest('tr').remove();">
        <img width="14" height="14" src="<?php echo base_url('public/images/delete-1-icon.png')?>">
      </a>
    </td>
  </tr>
</script>

<script id="new-column-option" type="text/template">
  <tr>
    <td>
      <input type='text' class="input-option col-option" name="question[{{questionId}}][columns][]" placeholder="Columna" autocomplete="off" value="{{columnValue}}">
    </td>
    <td>
      <a class="del-option" onclick="$(this).closest('tr').remove();">
        <img width="14" height="14" src="<?php echo base_url('public/images/delete-1-icon.png')?>">
      </a>
    </td>
  </tr>
</script>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/admin/plugins/ckeditor/ckeditor.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('public/js/validate_employer.js');?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
<script type="text/javascript">
  $(function() {
   var editor = CKEDITOR.replace( 'editor1', {
    enterMode : CKEDITOR.ENTER_BR,    
    toolbar: [
     { name: 'document', items: [ 'Source', '-', 'Nueva página', 'Vista previa', '-', 'Plantillas' ] },
     [ 'Cortar', 'Copiar', 'Pegar', 'Pegar texto', 'Pegar desde Word', '-', 'Undo', 'Redo' ],
     '/',                   
     { name: 'basicstyles', items: [ 'Bold', 'Italic' ] }
    ]
   });
    });
  //$.noConflict(); 
  $(document).ready(function($) {
    
    var today  = "<?php echo date('Y-m-d'); ?>";
    var datePart = today.split('-');
    var minDate = new Date(datePart[0], datePart[1] - 1, datePart[2]);
    minDate.setDate(minDate.getDate() + 1);

    $( "#last_date" ).datepicker({
      minDate: minDate,
      dateFormat: "dd/mm/yy",
      firstDay: 1,
      dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
      dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
      monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
      monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec" ],
      onSelect: function(dateText) { 
        $('#last_date').val(dateText);
      }
    });

    $( '#update_last_date' ).change(function(){

      if ($(this).is(':checked')) {
        $( '#last_date' ).show();
      } else {
        $( '#last_date' ).hide();
      }
    });
  });
   </script>
<script type="text/javascript"> var cy = '<?php echo set_value('country');?>'; </script>
<script type="text/javascript">
$(document).ready(function(){

  if(cy!='USA' && cy!='')
    $(".ui-autocomplete-input.ui-widget.ui-widget-content.ui-corner-left").css('display','none');
});
$(function() {
    var availableSkills = <?php echo $available_skills;?>;
    $( "#skill" ).autocomplete({
      source: availableSkills,
      select: function( event, ui ) {
        $( "#skill" ).val(ui.item.value);
        add_job_skill();
        $( "#skill" ).val('');
        return false;
      }
    });
  });
</script>
<script>
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          /*.appendTo( this.wrapper )*/
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":hidden" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  </script>
  <script type="text/javascript">
    
    $(document).ready(function(){
        
      $( "#country" ).change(function(){
        var country = $(this).val();

        $( "#city_dropdown" ).closest('div').removeClass( 'has-error' ); 
        $( ".city_dropdown_err" ).remove();
        $( "#city_text" ).closest('div').removeClass( 'has-error' ); 
        $( ".city_text_err").remove();

        if (country == 'Perú') {
          //$( '#city_label span' ).eq(0).text('Departamento / Provincia / Distrito');
          $( '#city_dropdown' ).attr({'name': 'city'});
          $('#city_dropdown').next('.select2-container').show();
          $( '#city_text').attr({'name': ''});
          $( '#city_text').hide();
  
        } else {
          //$( '#city_label span' ).eq(0).text('Ciudad');
          $( '#city_dropdown' ).attr({'name': ''});
          $( '#city_dropdown' ).next('.select2-container').hide();
          $( '#city_text').attr({'name':'city'});
          $( '#city_text').show();
        }
      });

      $( "#city_dropdown" ).change(function(){
        $( "#city_dropdown" ).closest('div').removeClass( 'has-error' ); 
        $( ".city_dropdown_err" ).remove();
      });


      window.questionId = -1;

      function addFormQuestion()
      {
        var questionId = window.questionId--;

        var template = $( "#tpl-question" ).html();
        var html = Mustache.render(template, {questionId: questionId, multiple_choice: true});

        $( "#content-form-questions" ).append(html);
        $( ".type-question", $("#formQuestion-" + questionId)).trigger("change");
      }

      function getJobQuestions()
      {
        $.getJSON("<?php echo site_url('employer/recruitment/process_jobs/get_job_questions/' . $id); ?>", function(data) {
          var questions = data.result_job_questions;
          
          for (index in questions) {

            var row = questions[index];
            
            var questionId = row.ID;     
            var questionName = row.question;
            var typeQuestion = row.type_question;
        
            var checkRequired = row.required == 'yes';
          
            var variables = {
              questionId: questionId,
              questionName: questionName,
              checkRequired: checkRequired 
            };

            variables[typeQuestion] = true;

            var template = $( "#tpl-question" ).html();
            var html = Mustache.render(template, variables);

            $( "#content-form-questions" ).append(html);

            var formQuestion = $("#formQuestion-" + questionId);

            if (/^(checkbox|multiple_choice|dropdown)$/.test(typeQuestion)) {
              
              var template = $( "#manager-items-options" ).html();
              var html = Mustache.render(template, {questionId: questionId});

              $( ".items-options", formQuestion).html(html);

              var questionOptions = JSON.parse(row.value);
              var options = questionOptions.options;

              for (var index in options) {
                
                var optionValue = options[index];
                var template = $( "#new-item-option" ).html();
                var html = Mustache.render(template, {questionId: questionId, optionValue: optionValue});

                $( ".table-items-options", formQuestion).append(html);
              }
            }

            if (/^(multiple_choice_grid)$/.test(typeQuestion)) {
            
              var template = $("#manager-multiple-choice-grid" ).html();
              var html = Mustache.render(template, {questionId: questionId});

              $( ".multiple-choice-grid-options", formQuestion).html(html);

              var questionGrid = JSON.parse(row.value);
              
              var rows = questionGrid.rows;
            
              for (var index in rows) {
                
                var rowValue = rows[index];
                var table = $( ".table-rows", formQuestion);
                var template = $( "#new-row-option" ).html();
                var html = Mustache.render(template, {questionId: questionId, rowValue: rowValue});

                table.append(html);
              }

              var columns = questionGrid.columns;

              for (var index in columns) {
                
                var columnValue = columns[index];
                var table = $( ".table-columns", formQuestion);
                var template = $( "#new-column-option" ).html();
                var html = Mustache.render(template, {questionId: questionId, columnValue: columnValue});
                
                table.append(html);
              }
            }
          }
        });
      }

      $( "#add-question" ).click(function(){
        addFormQuestion();
      });

      $(document).on('change', '.type-question', function(){

        var typeQuestion = $(this).val();
        var formQuestion = $(this).closest(".form-question");
        var questionId = $(formQuestion).data('question-id');
        
        $( ".error-message-q", formQuestion).remove();
        $( ".items-options", formQuestion).html("");
        $( ".multiple-choice-grid-options", formQuestion).html("");

        if (typeQuestion == "checkbox" || typeQuestion == "multiple_choice" || typeQuestion == "dropdown") {
                    
          var template = $( "#manager-items-options" ).html();
          var html = Mustache.render(template, {questionId: questionId});

          $( ".items-options", formQuestion).html(html);
        }

        if (typeQuestion == "multiple_choice_grid") {
          
          var template = $("#manager-multiple-choice-grid" ).html();
          var html = Mustache.render(template, {questionId: questionId});

          $( ".multiple-choice-grid-options", formQuestion).html(html);
        }
      });

      $(document).on('click', '.add-option', function(e){

        e.preventDefault();

        var questionId = $(this).data('question-id');;
        var table = $( ".table-items-options", $( "#formQuestion-" + questionId));
        
        var template = $( "#new-item-option" ).html();
        var newItemOption = Mustache.render(template, {questionId: questionId});
        table.append(newItemOption);
      });

      $(document).on('click', '.add-row-option', function(e){
        e.preventDefault();

        var questionId = $(this).data('question-id');
        var table = $( ".table-rows", $("#formQuestion-" + questionId));

        var template = $( "#new-row-option" ).html();
        var newOption = Mustache.render(template, {questionId: questionId});
        table.append(newOption);
      });

      $(document).on('click', '.add-column-option', function(e){
        e.preventDefault();

        var questionId = $(this).data('question-id');
        var table = $( ".table-columns", $( "#formQuestion-" + questionId));

        var template = $( "#new-column-option" ).html();
        var newOption = Mustache.render(template, {questionId: questionId});
        table.append(newOption);
      });

      $( "#has-questions" ).change(function(){
        
        $( "#error-has-questions").remove();

        if (!$(this).is(':checked')) {
          $( "#content-form-questions" ).hide();
          $( "#add-question" ).hide();
        } else {
          $( "#add-question" ).show();
          $( "#content-form-questions" ).show();
        }
      });

      $(window).scroll(function() {
        var scrollTop = $(this).scrollTop();
        var offset = $( "#has-questions" ).offset();

        if (scrollTop >= offset.top + 30) {
          $( "#add-question" ).addClass("btn-float");
        } else {
          $( "#add-question" ).removeClass("btn-float");
        }
      });

      $( '#city_dropdown' ).select2();
      $( '#laboral_benefits').select2({
        closeOnSelect: false
      });

      $( "#skill" ).keydown(function (e) {
       var keyCode = e.which;
       if (keyCode == 13) {
         event.preventDefault();
         return false;
       }
      });

      $( '#country' ).change();
      $( '#has-questions' ).change();
      
      getJobQuestions();
    });

  </script>
</body>
</html>