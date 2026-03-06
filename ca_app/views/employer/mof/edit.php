<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <?php $this->load->view('common/before_head_close'); ?>
    <style type="text/css">
      .form-control {
        max-width: 550px;
      }

      .sub-title-h3 {
        font-size: 16px;
        padding: 8px 2px;
        text-transform: uppercase;
        border-bottom: 1px solid #888;
        margin: 0px 0 20px 0px;
        display: block;
      }

      #wrapper-skills {
        padding: 5px;
        margin-top: 5px;
        position: relative;
      }

      #wrapper-skills span {
        padding: 4px 6px;
        background: #eee;
        display: inline-block;
        margin: 6px 3px;
      }

      #wrapper-skills span button {
        background: transparent;
        border: none;
        color: #f63e3e;
        padding: 3px;
        margin: 0;
      }

      #table-responsibilities tr .counter {
        color: #000;
        border-radius: 50%;
        width: 15px;
        height: 15px;
        padding: 2px 7px;
      }

      .btn-remove-item {
        margin:0;
        padding: 0;
        border:0;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        background: #e76767;
        color: #fff;
        font-size: 10px;
      }

      .text-resposibility {
        box-sizing: border-box;
        resize: none;
        overflow-y:hidden;
        min-height: 35px;
      }

      .tbl-base-1 th,
      .tbl-base-1 td {
        padding: 4px;
      }
      .tbl-style-1 tbody tr:nth-child(even) {
        background-color: #fff;
      }

      .tbl-style-1 tbody tr:nth-child(odd) {
        background-color: #eee;
      }

      .table-resources tbody td {
        vertical-align: bottom !important;
      }

      .js-resource-error {
        color: red;
        display: block;
      }
    </style>
  </head>
  <body>
  <?php $this->load->view('common/after_body_open'); ?>
  <div class="siteWraper">
  <!--Header-->
  <?php $this->load->view('common/header'); ?>
  <style type="text/css">
  .errowbox {
    display: block;
    position: relative;
    background: #fff;
    top: 0;
    right: 0;
    z-index: 0;
    padding: 0;
  }

  .errowbox .erormsg {

    background: #fff;
    color: red;
  }
  </style>
  <!--/Header-->
  <div class="container detailinfo">
    <div class="row"> 
      <?php echo form_open('employer/mofs/mofs/edit/' . $mof->ID, ['id' => 'form-mof-edit']); ?>
      <div class="col-md-3">
        <div class="dashiconwrp">
          <?php $this->load->view('employer/common/menu/sidebar'); ?>
        </div>
      </div>
      
      <div class="col-md-9">
        <?php echo $this->session->flashdata('msg');?> 
        <div class="formwraper">
          <div class="titlehead">
            <a href="#" style="color:#fff;" class="_link-back">
              <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            Editar MOF   
          </div>
          <div class="formint">
            <div class="info-required">
              <span>*</span> Campos obligatorios 
            </div>

              <h3 class="sub-title-h3">Descripción del puesto <span> </span></h3>

              <div class="form-group <?php echo (form_error('sunat_code'))?'has-error':'';?>">
                <label>Código SUNAT <span></span></label>
                <input name="sunat_code" type="text" class="form-control" id="sunat_code" placeholder="Código SUNAT" value="<?php echo set_value('sunat_code') ? set_value('sunat_code') : $mof->sunat_code; ?>" maxlength="180">
                <?php echo form_error('sunat_code'); ?>
              </div>
              <div class="form-group <?php echo (form_error('job_title'))?'has-error':'';?>">
                <label >Nombre del cargo <span>*</span></label>
                <input name="job_title" required type="text" class="form-control" id="job_title" placeholder="Nombre del cargo" value="<?php echo set_value('job_title') ? set_value('job_title') : $mof->job_title; ?>" maxlength="100">
                <?php echo form_error('job_title'); ?>
              </div>

              <div class="form-group <?php echo (form_error('occupational_group'))?'has-error':'';?>">
                  <label>Grupo ocupacional <span>*</span></label>
                  <select name="occupational_group" class="form-control factor-automatic" data-factor-type-id="3" data-factor-ref-val="skills" id="occupational_group" style="width: 90%;" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($job_charges as $row): ?>
                      <?php 
                        $selected = ($row->ID == $mof->job_charge_id) ? 'selected="selected"' : ''; 
                        $skills = $this->db->get_where('tbl_job_charge_skills', [
                          'job_charge_id' => $row->ID
                        ])->result();
                
                        $array_skills = [];
                
                        foreach ($skills as $skill) {
                            $array_skills[] = mb_strtoupper($skill->skill_name);
                        }
                        sort($array_skills);
                      ?>

                      <option value="<?php echo $row->ID; ?>" 
                        data-factor-score="<?php echo $row->valorization_score; ?>"
                        data-factor-grade="<?php echo $row->valorization_grade; ?>"
                        data-factor-desc="<?php echo join(',', $array_skills); ?>"
                        <?php echo $selected; ?>>
                        <?php echo $row->charge_name; ?>    
                      </option>
                    <?php endforeach; ?>                  
                  </select>
                  <?php echo form_error('occupational_group'); ?>
                </div>

                <div class="form-group <?php echo (form_error('belonging_areas[]')) ? 'has-error':'';?>">
                  <label>Áreas perteneciente <span>*</span></label>
                  <br />
                  <select name="belonging_areas[]" required type="text" class="form-control" id="belonging_areas" multiple>
                    <?php 
                      $array_mof_areas = array_map(function($row_area) {
                        return $row_area->belonging_area_ID;
                      }, $mof_belonging_areas); 
                    ?>
                    <?php foreach ($belonging_areas as $row_area): ?> 
                      <?php $array_mof_areas = (array)(set_value('belonging_areas') ? set_value('belonging_areas') : $array_mof_areas) ?> 
                      <option value="<?php echo $row_area->ID;?>" <?php echo in_array($row_area->ID, $array_mof_areas) ? 'selected="selected"' : '' ?>><?php echo $row_area->area_name;?></option>
                    <?php endforeach; ?>        
                  </select>
                  <?php echo form_error('belonging_areas[]'); ?>
                </div>

                <div class="form-group <?php echo (form_error('risk_criteria')) ? 'has-error':'';?>">
                  <label>Criterio de riesgo <span>*</span></label>
                  <select name="risk_criteria" class="form-control" id="risk_criteria" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($risk_criteria as $criteria): ?>  
                      <option value="<?php echo $criteria->name; ?>" <?php echo $criteria->name == $mof->risk_criteria ? 'selected="selected"' : '' ?>>
                        <?php echo $criteria->name; ?>    
                      </option>
                    <?php endforeach; ?>        
                  </select>
                  <?php echo form_error('risk_criteria'); ?>
                </div>

              <h3 class="sub-title-h3">Educación Deseable</h3>

              <div class="form-group <?php echo (form_error('study_grade_req'))?'has-error':'';?>">
                <label>Grado de estudio <span>*</span></label>
                <select name="study_grade_req" class="form-control" id="study_grade_req" required>
                  <option value="">Seleccione</option>
                  <?php foreach ($qualifications as $row_qualification): ?>
                    <?php
                      $study_grade_req = set_value('study_grade_req') ? set_value('study_grade_req') : $mof->study_grade_req; 
                      $selected = ($study_grade_req == $row_qualification->ID) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $row_qualification->ID;?>" <?php echo $selected;?>>
                      <?php echo $row_qualification->text; ?>    
                    </option>
                  <?php endforeach;?>
                </select>
                <?php echo form_error('study_grade_req'); ?>
              </div>

              <div class="form-group <?php echo (form_error('education_req_detail'))?'has-error':'';?>">
                <label>Más detalle <span>*</span></label>
                <textarea name="education_req_detail" required class="form-control" id="education_req_detail"><?php echo set_value('education_req_detail') ? set_value('education_req_detail') : $mof->education_req_detail; ?></textarea>
                <?php echo form_error('education_req_detail'); ?>
              </div>
              
              <h3 class="sub-title-h3">Educación Mínima</h3>

              <div class="form-group <?php echo (form_error('study_grade_min'))?'has-error':'';?>">
                <label>Grado de estudio <span>*</span></label>
                <select name="study_grade_min" type="text" class="form-control factor-automatic" data-factor-type-id="1" data-factor-ref-val="factor-desc" id="study_grade_min" required>
                  <option value="">Seleccione</option>
                    <?php foreach($qualifications as $row_qualification):
                      $study_grade_min = set_value('study_grade_min') ? set_value('study_grade_min') : $mof->study_grade_min; 
                      $selected = ($study_grade_min == $row_qualification->ID) ? 'selected="selected"':'';
                    ?>
                    <option 
                       value="<?php echo $row_qualification->ID;?>" 
                        data-factor-score="<?php echo $row_qualification->valorization_score; ?>"
                        data-factor-grade="<?php echo $row_qualification->valorization_grade; ?>"
                       data-factor-desc="<?php echo mb_strtoupper($row_qualification->text); ?>"
                      <?php echo $selected;?>>
                      <?php echo $row_qualification->text;?>
                    </option>
                    <?php endforeach;?>
                </select>
                <?php echo form_error('study_grade_min'); ?>
              </div>

              <div class="form-group <?php echo (form_error('education_min_detail'))?'has-error':'';?>">
                <label>Más detalle <span>*</span></label>
                <textarea name="education_min_detail" required class="form-control" id="education_min_detail"><?php echo set_value('education_min_detail') ? set_value('education_min_detail') : $mof->education_min_detail; ?></textarea>
                <?php echo form_error('education_min_detail'); ?>
              </div>

              <h3 class="sub-title-h3">Formación</h3>

              <div class="form-group <?php echo (form_error('education'))?'has-error':'';?>">
                <label>Formación necesaria <span>*</span></label>
                <textarea name="education" required class="form-control" id="education"><?php echo set_value('education') ? set_value('education') : $mof->education; ?></textarea>
                <?php echo form_error('education'); ?>
              </div>

              <h3 class="sub-title-h3">Experiencia</h3>

              <div class="form-group <?php echo (form_error('experience'))?'has-error':'';?>">
                <label>Experiencia <span>*</span></label>
                <select name="experience" type="text" class="form-control factor-automatic" data-factor-type-id="2" data-factor-ref-val="factor-desc" id="experience" style="width: 100%;" required>
                  <option value="">Seleccione</option>
                    <?php foreach($work_experiences as $row):
                      $experience = set_value('experience') ? set_value('experience') : $mof->experience;
                      $selected = ($experience == $row->code) ? 'selected="selected"':'';
                    ?>
                    <option value="<?php echo $row->code; ?>"
                        data-factor-score="<?php echo $row->valorization_score; ?>"
                        data-factor-grade="<?php echo $row->valorization_grade; ?>"
                        data-factor-desc="<?php echo mb_strtoupper($row->name); ?>"
                        <?php echo $selected;?>>
                        <?php echo $row->name;?>
                    </option>
                    <?php endforeach;?>
                </select>
                <?php echo form_error('experience'); ?>
              </div>
           
              <div class="form-group <?php echo (form_error('experience_detail'))?'has-error':'';?>">
                <label>Más detalle <span>*</span></label>
                <textarea name="experience_detail" required class="form-control" id="experience_detail"><?php echo set_value('experience_detail') ? set_value('experience_detail') : $mof->experience_detail; ?></textarea>
                <?php echo form_error('experience_detail'); ?>
              </div>

              <h3 class="sub-title-h3">Habilidades</h3>

              <div class="form-group <?php echo (form_error('skills[]'))?'has-error':'';?>" style="margin-bottom: 30px;">
                <label>Habilidades requeridas <span>*</span></label>
                <table>
                  <tr>
                    <td width="85%">
                      <input id="input-skill" type="text" class="form-control" placeholder="Ingrese habilidad">
                    </td>
                    <td style="padding: 5px 10px;">
                      <a id="btn-add-skill" href="#">Agregar</a>  
                    </td>
                  </tr>
                </table>

                <div id="wrapper-skills">

                  <?php $mof_skills = set_value('skills') ? set_value('skills') : $mof_skills; ?>
                  <?php foreach ($mof_skills as $skill): ?>
                  <?php $skill_name = is_object($skill) ? $skill->skill_name : $skill; ?>

                    <span>
                      <input type="hidden" name="skills[]" value="<?php echo $skill_name; ?>" >
                      <?php echo $skill_name; ?>
                      <button type="button" onclick="$(this).closest('span').remove();">
                      <i class="glyphicon glyphicon-remove"></i>
                      </button>
                    </span>
                  <?php endforeach; ?>
                  <div id="wrapper-fixed-skills"  class="wrapper-skills" style="display: inline-block;"></div>
                </div>
                <?php echo form_error('skills[]'); ?>
              </div>

              <h3 class="sub-title-h3">Responsabilidades</h3>
    
              <div class="row"> 
                <div class="col-md-12 <?php echo (form_error('responsibilities[]')) ? 'has-error' : '';?>" >
                  <?php echo form_error('responsibilities[]'); ?>
                  <table id="table-responsibilities" class="table table-striped" width="100%">
                    <thead>
                      <tr>
                        <td colspan="3" align="right">
                          <a id="btn-add-responsibility" href="#" style="margin-bottom: 15px;">Agregar</a>
                        </td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $mof_responsibilities = set_value('responsibilities') ? set_value('responsibilities') : $mof_responsibilities; ?>
                      <?php foreach ($mof_responsibilities as $index => $responsibility): ?>
                      <?php $responsibility = is_object($responsibility) ? $responsibility->responsibility : $responsibility; ?>
                        <tr>
                          <td width="5%">
                            <span class="_counter" style="display: none;"><?php echo ($index + 1); ?></span>
                          </td>
                          <td>
                            <textarea name="responsibilities[]" class="form-control text-resposibility" style="height: 35px;"><?php echo $responsibility; ?></textarea>
                          </td>
                          <td width="10%">
                            <button type="button" class="btn-remove-item" onclick="$(this).closest('tr').remove();"><i class="glyphicon glyphicon-remove"></i></button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>    
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <h3 class="sub-title-h3">
                    Estructura Salarial
                  </h3>

                  <table id="tbl-struct-salary" width="100%" class="table table-striped">
                    <tr>
                      <th></th>
                      <th>Mínimo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?></th>
                      <th>Máximo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?></th>
                    </tr>
                    <tr>
                        <td><b>Básico</b></td>
                        <td>
                          <input id="basic_minimum" 
                                  name="basic_minimum" 
                                  type="text" required 
                                  class="form-control number-format" 
                                  pattern="(^[0-9]{1,8}$)|(^[0-9]{1,8}\.[0-9]{0,2}$)" 
                                  placeholder="Básico Mínimo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?>" 
                                  value="<?php echo $mof->basic_minimum; ?>">
                        </td>
                        <td>
                        <input id="basic_maximum" 
                                name="basic_maximum" 
                                required 
                                type="text" 
                                pattern="(^[0-9]{1,8}$)|(^[0-9]{1,8}\.[0-9]{0,2}$)" 
                                class="form-control number-format" 
                                id="basic_maximum" 
                                placeholder="Básico Máximo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?>" 
                                value="<?php echo $mof->basic_maximum; ?>">
                        </td>
                    </tr>

                    <?php foreach ($mof_benefits as $row): ?>
                      <tr>
                        <td><b><?php echo $row->benefit_name; ?></b></td>
                        <td>
                          <input name="benefits[<?php echo $row->benefit_id; ?>][minimum]" 
                                  type="text" 
                                  required 
                                  class="form-control number-format" 
                                  pattern="(^[0-9]{1,8}$)|(^[0-9]{1,8}\.[0-9]{0,2}$)" 
                                  placeholder="<?php echo $row->benefit_name; ?> Mínimo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?>" 
                                  value="<?php echo $row->minimum; ?>">
                        </td>
                        <td>
                        <input name="benefits[<?php echo $row->benefit_id; ?>][maximum]" 
                                required 
                                type="text" 
                                pattern="(^[0-9]{1,8}$)|(^[0-9]{1,8}\.[0-9]{0,2}$)" 
                                class="form-control number-format"  
                                placeholder="<?php echo $row->benefit_name; ?> Máximo <?php echo '(' . ($country ? $country->currency_code : '') . ')'; ?>" 
                                value="<?php echo $row->maximum; ?>">
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </table>
                  <br>
                  
                  <div class="input-group">
                    <label class="input-group-addon">Estereotipo <span>*</span></label>
                    <select name="stereotype" class="form-control" id="stereotype" style="width: 100%;" required>
                      <option value="">Seleccione</option>
                      <option value="Masculino" <?php echo $mof->stereotype == 'Masculino' ? 'selected="selected"' : ''; ?>>Masculino</option>
                      <option value="Femenino" <?php echo $mof->stereotype == 'Femenino' ? 'selected="selected"' : ''; ?>>Femenino</option>
                      <option value="Neutro" <?php echo $mof->stereotype == 'Neutro' ? 'selected="selected"' : ''; ?>>Neutro</option>
                    </select>
                  </div>
                        
                  <div class="input-group">
                    <label class="input-group-addon">Factor diferenciador <span></span></label>
                    <select name="factor_differentiating" class="form-control" id="factor_differentiating" style="width: 100%;">
                      <option value="">Seleccione</option>
                      <option value="Antigüedad en el puesto o en la empresa" <?php echo $mof->factor_differentiating == 'Antigüedad en el puesto o en la empresa' ? 'selected="selected"' : ''; ?> >Antigüedad en el puesto o en la empresa</option>
                      <option value="Desempeño" <?php echo $mof->factor_differentiating == 'Desempeño' ? 'selected="selected"' : ''; ?>>Desempeño</option>
                      <option value="Apoyo en proyectos específicos" <?php echo $mof->factor_differentiating == 'Apoyo en proyectos específicos' ? 'selected="selected"' : ''; ?>>Apoyo en proyectos específicos</option>
                      <option value="Escasez de oferta en el mercado" <?php echo $mof->factor_differentiating == 'Escasez de oferta en el mercado' ? 'selected="selected"' : ''; ?>>Escasez de oferta en el mercado</option>
                      <option value="Costo de vida" <?php echo $mof->factor_differentiating == 'Costo de vida' ? 'selected="selected"' : ''; ?>>Costo de vida</option>
                      <option value="Experiencia laboral" <?php echo $mof->factor_differentiating == 'Experiencia laboral' ? 'selected="selected"' : ''; ?>>Experiencia laboral</option>
                      <option value="Perfil académico o educativo" <?php echo $mof->factor_differentiating == 'Perfil académico o educativo' ? 'selected="selected"' : ''; ?>>Perfil académico o educativo</option>
                      <option value="Lugar de trabajo" <?php echo $mof->factor_differentiating == 'Lugar de trabajo' ? 'selected="selected"' : ''; ?>>Lugar de trabajo</option>
                      <option value="Jornadas reducidas, atípicas o parciales" <?php echo $mof->factor_differentiating == 'Jornadas reducidas, atípicas o parciales' ? 'selected="selected"' : ''; ?>>Jornadas reducidas, atípicas o parciales</option>
                      <option value="Otro" <?php echo $mof->factor_differentiating == 'Otro' ? 'selected="selected"' : ''; ?>>Otro</option>
                    </select>
                  </div>

                  <div id="content_factor_differentiating_other" class="input-group">
                    <label class="input-group-addon">Factor diferenciador Otro <span>*</span></label>
                    <textarea name="factor_differentiating_other" class="form-control" id="factor_differentiating_other"><?php echo $mof->factor_differentiating_other; ?></textarea>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <h3 class="sub-title-h3">
                    Indicadores de productividad
                  </h3>
                  <a id="btn-add-indicator" href="#" class="pull-right">Agregar</a>
                  <br />
                  <table id="table-indicators" class="tbl-base-1 tbl-style-1" width="100%">
                    <thead>
                      <tr>
                        <th width="30%">Indicador</th>
                        <th width="40%">Fórmula</th>
                        <th width="30%">Fuente de información</th>
                        <th width="5%"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $mof_indicators = set_value('indicators') ? set_value('indicators') : $mof_indicators; ?>
                      <?php foreach($mof_indicators as $key => $row_indicator): ?>
                        <tr>
                          <td width="30%">
                            <input type="text" name="indicators[<?php echo $key; ?>][indicator]" class="form-control" value="<?php echo is_object($row_indicator) ? $row_indicator->indicator : $row_indicator['indicator']; ?>">
                          </td>
                          <td width="40%">
                            <input type="text" name="indicators[<?php echo $key; ?>][formula]" class="form-control" value="<?php echo is_object($row_indicator) ? $row_indicator->formula : $row_indicator['formula']; ?>">
                          </td>
                          <td width="30%">
                            <input type="text" name="indicators[<?php echo $key; ?>][source_info]" class="form-control" value="<?php echo is_object($row_indicator) ? $row_indicator->source_info : $row_indicator['source_info'] ?>">
                          </td>
                          <td>
                            <button type="button" class="btn-remove-item"><i class="glyphicon glyphicon-remove"></i></button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <br>

              <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <input type="hidden" name="check_validate_resources" value="1">
                      <a data-toggle="collapse" data-parent="#accordion" href="#resources">
                        RECURSOS
                      </a>
                    </h4>
                  </div>

                  <div id="resources" class="panel-collapse collapse in">
                    <div class="panel-body">
                    
                      <div class="row">
                        <div class="col-md-12">
                          <h3 class="sub-title-h3">
                            Recursos
                          </h3>
                          <table  class="table table-striped table-resources" width="100%">              
                            <thead>
                              <tr>
                                <th></th>
                                <th></th>
                                <th>
                                  Realizar a
                                </th>
                                <th>
                                  Encargado
                                </th>
                              </tr>
                            </thead>
                            
                            <tbody>
                              <?php
                                $mof_resources = set_value('resources') ? set_value('resources') : [];
                              ?>
                              
                              <?php 
                              $resource_screening = $this->Mof->get_resource_by_name('type_screening', $mof->ID);
                              ?>
                              <tr>
                                <td>
                                </td>
                                <td>
                                  <label>Screening</label>
                                  <select name="resources[type_screening][value][]" class="form-control js-resource-input" id="type_screening" style="width: 100%;">
                                    <?php 
                                      $type_screening_selected = isset($mof_resources['type_screening']['value']) ? (array)$mof_resources['type_screening']['value'] : array_map('trim', explode(',', @$resource_screening->resource_value));
                                    ?>
                                
                                    <?php foreach (get_options_type_screening() as $option): ?>
                                      <option value="<?php echo $option; ?>" <?php echo in_array($option,  $type_screening_selected) ? 'selected="selected"' : '';?>>
                                        <?php echo $option; ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                  <?php echo form_error('resources[type_screening][value]'); ?>
                                  <div style="margin-top:5px;">
                                    <label>Tipo Egreso</label> 
                                    <select class="form-control js-resource-type-expense"
                                            name="resources[type_screening][type_expense]"
                                            >
                                    
                                      <?php $type_expense = @$resource_screening->type_expense; ?>
                                        <option value="">Seleccione</option>
                                        <option value="EI" <?php echo $type_expense == 'EI' ? 'selected="selected"' : ''; ?>>EI</option>
                                        <option value="EONF" <?php echo $type_expense == 'EONF' ? 'selected="selected"' : ''; ?>>EONF</option>
                                        <option value="EOF" <?php echo $type_expense == 'EOF' ? 'selected="selected"' : ''; ?>>EOF</option>
                                        <option value="EOFDP" <?php echo $type_expense == 'EOFDP' ? 'selected="selected"' : ''; ?>>EOFDP</option>
                                    </select>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control js-resource-stage"
                                          name="resources[type_screening][perform_on_stage]">
                                    
                                    <?php $perform_on_stage = isset($mof_resources['type_screening']['perform_on_stage']) ? $mof_resources['type_screening']['perform_on_stage'] : @$resource_screening->perform_on_stage; ?>

                                    <option value="">Seleccione</option>
                                      <?php foreach ($rys_stages as $index => $stage): ?>
                                        
                                        <option value="<?php echo $index; ?>" 
                                                <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                                          <?php echo $stage; ?>
                                        </option>
                                      <?php endforeach; ?>
                                  </select>
                                </td>
                                <td>
                                  <input type="text" name="resources[type_screening][staff_charge]" class="form-control" value="<?php echo isset($mof_resources['type_screening']['staff_charge']) ? $mof_resources['type_screening']['staff_charge'] : @$resource_screening->staff_in_charge; ?>">
                                </td>
                              </tr>
                                <?php 
                                  $resource_emo = $this->Mof->get_resource_by_name('type_emo', $mof->ID);
                                ?>
                              <tr>
                                <td>
                                </td>
                                <td>
                                  <label>Tipo EMO</label>
                                  <select name="resources[type_emo][value][]" class="form-control js-resource-input" id="type_emo" style="width: 100%;">
                                    <?php 
                                      $type_emo_selected = isset($mof_resources['type_emo']['value']) ? (array)$mof_resources['type_emo']['value'] : array_map('trim', explode(',', @$resource_emo->resource_value));
                                    ?>
                                    <?php foreach (get_options_type_emo() as $option): ?>
                                      <option value="<?php echo $option; ?>" <?php echo in_array($option, $type_emo_selected) ? 'selected="selected"' : '';?>>
                                        <?php echo $option; ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                  <?php echo form_error('resources[type_emo][value]'); ?>

                                  <div style="margin-top:5px;">
                                    <label>Tipo Egreso</label> 
                                    <select class="form-control js-resource-type-expense"
                                            name="resources[type_emo][type_expense]">
                                    
                                      <?php $type_expense = @$resource_emo->type_expense; ?>
                                        <option value="">Seleccione</option>
                                        <option value="EI" <?php echo $type_expense == 'EI' ? 'selected="selected"' : ''; ?>>EI</option>
                                        <option value="EONF" <?php echo $type_expense == 'EONF' ? 'selected="selected"' : ''; ?>>EONF</option>
                                        <option value="EOF" <?php echo $type_expense == 'EOF' ? 'selected="selected"' : ''; ?>>EOF</option>
                                        <option value="EOFDP" <?php echo $type_expense == 'EOFDP' ? 'selected="selected"' : ''; ?>>EOFDP</option>
                                    </select>
                                  </div>

                                  <div id="emo-detail" style="display: none;">
                                    <label>Detallar</label>
                                    <input id="protocol-detail" 
                                            type="text" 
                                            name="resources[type_emo][protocol_detail]" 
                                            class="form-control" 
                                            placeholder="Detallar Protocolo" 
                                            value="<?php echo  @$resource_emo->protocol_detail; ?>">
                                  </div>
                                </td>
                                <td>-</td>
                                <td>
                                  <input type="text" name="resources[type_emo][staff_charge]" class="form-control" value="<?php echo isset($mof_resources['type_emo']['staff_charge']) ? $mof_resources['type_emo']['staff_charge'] : @$resource_emo->staff_in_charge; ?>">
                                </td>
                              </tr>
                              <tr>
                                <?php 
                                  $resource_exam_complementary = $this->Mof->get_resource_by_name('exams_complementary', $mof->ID);
                                ?>
                                <td>

                                </td>
                                <td>
                                  <label>Exámenes complementarios *</label>
                                  <select name="resources[exams_complementary][value]" class="form-control js-resource-input"  style="width: 100%;">
                                    <?php 
                                      $type_exam_complementary_selected = isset($mof_resources['exams_complementary']['value']) ? $mof_resources['exams_complementary']['value'] : @$resource_exam_complementary->resource_value; ?>
                                    ?>
                                    <?php foreach (options_exams_complementary() as $option): ?>
                                      <option value="<?php echo $option; ?>" <?php echo $option == $type_exam_complementary_selected ? 'selected="selected"' : '';?>>
                                        <?php echo $option; ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                  <?php echo form_error('resources[exams_complementary][value]'); ?>
                                </td>
                                <td>
                                  -
                                </td>
                                <td>
                                  <input type="text" name="resources[exams_complementary][staff_charge]" class="form-control" value="<?php echo @$mof_resources['exams_complementary']['staff_charge']; ?>">
                                </td>
                              </tr>
                              <tr>
                                <td>
                                </td>
                                <td>
                                  <label>Examen COVID</label>
                                  <select name="resources[exam_type_covid][value][]" 
                                          class="form-control js-resource-input" 
                                          id="exam_type_covid"
                                          multiple="multiple" 
                                          style="width: 100%;" >
                                    <?php 
                                      $resource_covid = $this->Mof->get_resource_by_name('exam_type_covid', $mof->ID);

                                        $exam_type_covid_selected = isset($mof_resources['exam_type_covid']['value']) ? (array)$mof_resources['exam_type_covid']['value'] : array_map('trim', explode(',', @$resource_covid->resource_value));
                                    ?>
                                    <?php foreach (get_options_exam_type_covid() as $key => $option): ?>
                                      <option value="<?php echo $key; ?>" <?php echo in_array($key, $exam_type_covid_selected) ? 'selected="selected"' : '';?>>
                                        <?php echo $option; ?>
                                      </option>  
                                    <?php endforeach; ?>
                                  </select>
                                  <?php echo form_error('resources[exam_type_covid][value]'); ?>
                                  <div style="margin-top:5px;">
                                    <label>Tipo Egreso</label> 
                                    <select class="form-control js-resource-type-expense"
                                            name="resources[exam_type_covid][type_expense]">
                                    
                                      <?php $type_expense = @$resource_covid->type_expense; ?>
                                        <option value="">Seleccione</option>
                                        <option value="EI" <?php echo $type_expense == 'EI' ? 'selected="selected"' : ''; ?>>EI</option>
                                        <option value="EONF" <?php echo $type_expense == 'EONF' ? 'selected="selected"' : ''; ?>>EONF</option>
                                        <option value="EOF" <?php echo $type_expense == 'EOF' ? 'selected="selected"' : ''; ?>>EOF</option>
                                        <option value="EOFDP" <?php echo $type_expense == 'EOFDP' ? 'selected="selected"' : ''; ?>>EOFDP</option>
                                    </select>
                                  </div>
                                </td>
                                <td>
                                  <select class="form-control js-resource-stage"
                                          name="resources[exam_type_covid][perform_on_stage]">
                                    
                                    <?php $perform_on_stage = isset($mof_resources['exam_type_covid']['perform_on_stage']) ? $mof_resources['exam_type_covid']['perform_on_stage'] : @$resource_covid->perform_on_stage; ?>

                                    <option value="">Seleccione</option>
                                      <?php foreach ($rys_stages as $index => $stage): ?>
                                        
                                        <option value="<?php echo $index; ?>" 
                                                <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                                          <?php echo $stage; ?>
                                        </option>
                                      <?php endforeach; ?>
                                  </select>
                                </td>
                                <td>
                                  <input type="text" 
                                          name="resources[exam_type_covid][staff_charge]" 
                                          class="form-control" 
                                          value="<?php echo isset($mof_resources['exam_type_covid']['staff_charge']) ? $mof_resources['exam_type_covid']['staff_charge'] : @$resource_covid->staff_in_charge; ?>">
                                </td>
                              </tr>
                              <?php 
                                $resource_home_verification = $this->Mof->get_resource_by_name('home_verification', $mof->ID);
                              ?>
                              <tr>
                                <td>
                                </td>
                                <td>
                                  <label>Verficación domiciliaria </label>
                                  <select name="resources[home_verification][value]" type="text" class="form-control js-resource-input" id="home_verification">
                                    <?php $home_verification = isset($mof_resources['home_verification']['value']) ? $mof_resources['home_verification']['value'] : @$resource_home_verification->resource_value; ?>
                                    <option value="0" <?php echo $home_verification == '0' ? 'selected="selected"' : '';?>>
                                      NO
                                    </option>
                                    <option value="1" <?php echo $home_verification == '1' ? 'selected="selected"' : '';?>>
                                      SÍ
                                    </option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control js-resource-stage"
                                          name="resources[home_verification][perform_on_stage]">
                                    
                                    <?php $perform_on_stage = isset($mof_resources['home_verification']['perform_on_stage']) ? $mof_resources['home_verification']['perform_on_stage'] : @$resource_home_verification->perform_on_stage; ?>

                                    <option value="">Seleccione</option>
                                      <?php foreach ($rys_stages as $index => $stage): ?>
                                        
                                        <option value="<?php echo $index; ?>" 
                                                <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                                          <?php echo $stage; ?>
                                        </option>
                                      <?php endforeach; ?>
                                  </select>
                                </td>
                                <td>
                                  <input type="text" 
                                          name="resources[home_verification][staff_charge]" 
                                          class="form-control" 
                                          value="<?php echo isset($mof_resources['home_verification']['staff_charge']) ? $mof_resources['home_verification']['staff_charge'] : @$resource_home_verification->staff_in_charge;?>">
                                </td>
                              </tr>
                              <?php 
                                $resource_credit_check = $this->Mof->get_resource_by_name('credit_verification', $mof->ID);
                              ?>
                              <tr>
                                <td>
                                </td>
                                <td>
                                  <label>Verificación crediticia</label>
                                  <select name="resources[credit_verification][value]" 
                                          type="text" 
                                          class="form-control js-resource-input" 
                                          id="credit_verification">
                                    <?php $credit_verification = isset($mof_resources['credit_verification']['value']) ? $mof_resources['credit_verification']['value'] : @$resource_credit_check->resource_value; ?>
                                    <option value="0" <?php echo $credit_verification == '0' ? 'selected="selected"' : '';?>>
                                      NO
                                    </option>
                                    <option value="1" <?php echo $credit_verification == '1' ? 'selected="selected"' : '';?>>
                                      SÍ
                                    </option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control js-resource-stage"
                                          name="resources[credit_verification][perform_on_stage]">
                                    
                                    <?php $perform_on_stage = isset($mof_resources['credit_verification']['perform_on_stage']) ? $mof_resources['credit_verification']['perform_on_stage'] : @$resource_credit_check->perform_on_stage; ?>

                                    <option value="">Seleccione</option>
                                      <?php foreach ($rys_stages as $index => $stage): ?>
                                        
                                        <option value="<?php echo $index; ?>" 
                                                <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                                          <?php echo $stage; ?>
                                        </option>
                                      <?php endforeach; ?>
                                  </select>
                                </td>
                                <td>
                                  <input type="text" 
                                          name="resources[credit_verification][staff_charge]" 
                                          class="form-control" 
                                          value="<?php echo isset($mof_resources['credit_verification']['staff_charge']) ? $mof_resources['credit_verification']['staff_charge'] : @$resource_credit_check->staff_in_charge;?>">
                                </td>
                              </tr>

                              <?php 
                                $resource_labor_check = $this->Mof->get_resource_by_name('labor_verification', $mof->ID);
                              ?>
                                
                              <tr>
                                <td>
                                </td>
                                <td>
                                  <label>Verificación laboral</label>
                                  <select name="resources[labor_verification][value]" 
                                          type="text" 
                                          class="form-control js-resource-input" 
                                          id="labor_verification">
                                    <?php $labor_verification = isset($mof_resources['labor_verification']['value']) ? $mof_resources['labor_verification']['value'] : @$resource_labor_check->resource_value; ?>
                                    <option value="0" <?php echo $labor_verification == '0' ? 'selected="selected"' : '';?>>
                                      NO
                                    </option>
                                    <option value="1" <?php echo $labor_verification == '1' ? 'selected="selected"' : '';?>>
                                      SÍ
                                    </option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control js-resource-stage"
                                          name="resources[labor_verification][perform_on_stage]">
                                    
                                    <?php $perform_on_stage = isset($mof_resources['labor_verification']['perform_on_stage']) ? $mof_resources['labor_verification']['perform_on_stage'] : @$resource_labor_check->perform_on_stage; ?>

                                    <option value="">Seleccione</option>
                                      <?php foreach ($rys_stages as $index => $stage): ?>
                                        
                                        <option value="<?php echo $index; ?>" 
                                                <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                                          <?php echo $stage; ?>
                                        </option>
                                      <?php endforeach; ?>
                                  </select>
                                </td>
                                <td>
                                  <input type="text" 
                                          name="resources[labor_verification][staff_charge]" 
                                          class="form-control" 
                                          value="<?php echo isset($mof_resources['labor_verification']['staff_charge']) ? $mof_resources['labor_verification']['staff_charge'] : @$resource_labor_check->staff_in_charge;?>">
                                </td>
                              </tr>

                              <?php 
                                $resource_degrees_titles_check = $this->Mof->get_resource_by_name('degrees_titles_verification', $mof->ID);
                              ?>
                              
                              <tr>
                                <td>
                                </td>
                                <td>
                                    <label>Verificación grados y títulos</label>
                                  <select name="resources[degrees_titles_verification][value]" 
                                          type="text" 
                                          class="form-control js-resource-input" 
                                          id="degrees_titles_verification">
                                    <?php $degrees_titles_check = isset($mof_resources['degrees_titles_verification']['value']) ? $mof_resources['degrees_titles_verification']['value'] : @$resource_degrees_titles_check->resource_value; ?>
                                    <option value="0" <?php echo $degrees_titles_check == '0' ? 'selected="selected"' : '';?>>
                                      NO
                                    </option>
                                    <option value="1" <?php echo $degrees_titles_check == '1' ? 'selected="selected"' : '';?>>
                                      SÍ
                                    </option>
                                  </select>
                                </td>

                                <td>
                        
                                  <select class="form-control js-resource-stage"
                                          name="resources[degrees_titles_verification][perform_on_stage]">
                                    
                                    <?php $perform_on_stage = isset($mof_resources['degrees_titles_verification']['perform_on_stage']) ? $mof_resources['degrees_titles_verification']['perform_on_stage'] : @$resource_degrees_titles_check->perform_on_stage; ?>

                                    <option value="">Seleccione</option>
                                      <?php foreach ($rys_stages as $index => $stage): ?>
                                        
                                        <option value="<?php echo $index; ?>" 
                                                <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                                          <?php echo $stage; ?>
                                        </option>
                                      <?php endforeach; ?>
                                  </select>
                                </td>

                                <td>
                                  <input type="text" 
                                          name="resources[degrees_titles_verification][staff_charge]" 
                                          class="form-control" 
                                          value="<?php echo isset($mof_resources['degrees_titles_verification']['staff_charge']) ? $mof_resources['degrees_titles_verification']['staff_charge'] : @$resource_degrees_titles_check->staff_in_charge;?>">
                                </td>
                              </tr>
                                <?php 
                                  $resource_degrees_titles_person_check = $this->Mof->get_resource_by_name('degrees_titles_person_verification', $mof->ID);
                                ?>
                              <tr>
                                <td>
                                </td>
                                <td>
                                  <label>Verificación grados y títulos presencial</label>
                                  <select name="resources[degrees_titles_person_verification][value]" 
                                          type="text" 
                                          class="form-control js-resource-input" 
                                          id="degrees_titles_person_verification">
                                    <?php $degrees_titles_person_check = isset($mof_resources['degrees_titles_person_verification']['value']) ? $mof_resources['degrees_titles_person_verification']['value'] : @$resource_degrees_titles_person_check->resource_value; ?>
                                    <option value="0" <?php echo $degrees_titles_person_check == '0' ? 'selected="selected"' : '';?>>
                                      NO
                                    </option>
                                    <option value="1" <?php echo $degrees_titles_person_check == '1' ? 'selected="selected"' : '';?>>
                                      SÍ
                                    </option>
                                  </select>
                                </td>
                                <td>
                                  
                                  <select class="form-control js-resource-stage"
                                          name="resources[degrees_titles_person_verification][perform_on_stage]">
                                    
                                    <?php $perform_on_stage = isset($mof_resources['degrees_titles_person_verification']['perform_on_stage']) ? $mof_resources['degrees_titles_person_verification']['perform_on_stage'] : @$resource_degrees_titles_check->perform_on_stage; ?>

                                    <option value="">Seleccione</option>
                                      <?php foreach ($rys_stages as $index => $stage): ?>
                                        
                                        <option value="<?php echo $index; ?>" 
                                                <?php echo $perform_on_stage == $index ? 'selected="selected"' : ''?>>
                                          <?php echo $stage; ?>
                                        </option>
                                      <?php endforeach; ?>
                                  </select>
                                </td>
                                <td>
                                  <input type="text" 
                                          name="resources[degrees_titles_person_verification][staff_charge]" 
                                          class="form-control" 
                                          value="<?php echo isset($mof_resources['degrees_titles_person_verification']['staff_charge']) ? $mof_resources['degrees_titles_person_verification']['staff_charge'] : @$resource_degrees_titles_person_check->staff_in_charge;?>">
                                </td>
                              </tr>

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <input type="hidden" name="check_validate_disability" value="1">
                      <a data-toggle="collapse" data-parent="#accordion" href="#disability">
                        DISCAPACIDADES
                      </a>
                    </h4>
                  </div>

                  <div id="disability" class="panel-collapse collapse in">
                    <div class="panel-body">

                      <?php 
                        $mof_disability = set_value('disability') ? set_value('disability') : [];
                      ?>

                      <?php foreach ($results_disability as $row_theme): ?>
                        <div class="row">
                          <div class="col-md-12">
                            <h3 class="sub-title-h3">
                              <?php echo $row_theme->name; ?>
                            </h3>
                            <?php foreach ($row_theme->subthemes as $row_subtheme): ?>
                              <div class="col-md-6"style="padding: 5px;">
                                <h5 style="padding: 5px;background:#f9f9f9;"><b><?php echo $row_subtheme->name; ?></b></h5>
                                <?php foreach ($row_subtheme->sections as $row_section): ?>
                                  <div style="padding: 10px;">
                                    <table class="table" idth="100%">
                                      <tr>
                                        <th>
                                          <?php echo $row_section->name; ?>
                                        </th>
                                        <th>Grado</th>
                                      </tr>
                                      <?php foreach ($row_section->items as $key => $row_item): ?>
                                        <tr>
                                          <td width="70%"><?php echo $row_item->name; ?></td>
                                          <td>
                                            <?php $grade_info = json_decode($row_item->grade_info, true); ?>
                                            <input type="hidden" name="disability[<?php echo $row_item->item_id; ?>][item_id]" value="<?php echo $row_item->item_id; ?>">
                                            <select name="disability[<?php echo $row_item->item_id; ?>][grade]" class="grade">
                                              <?php 
                                                $mof_disability_grade = isset($mof_disability[$row_item->item_id]['grade']) ? $mof_disability[$row_item->item_id]['grade'] : $row_item->grade; 
                                              ?>
                                              <option value="">Grado</option>
                                              <option value="1" data-grade-info="<?php echo htmlentities($grade_info[1], ENT_QUOTES, "UTF-8"); ?>" <?php echo $mof_disability_grade == 1 ? 'selected="selected"' : ''; ?>>1</option>
                                              <option value="2" data-grade-info="<?php echo htmlentities($grade_info[2], ENT_QUOTES, "UTF-8"); ?>" <?php echo $mof_disability_grade == 2 ? 'selected="selected"' : ''; ?>>2</option>
                                              <option value="3" data-grade-info="<?php echo htmlentities($grade_info[3], ENT_QUOTES, "UTF-8"); ?>" <?php echo $mof_disability_grade == 3 ? 'selected="selected"' : ''; ?>>3</option>
                                            </select>
                                          </td>
                                        </tr>
                                      <?php endforeach; ?>
                                    </table>
                                  </div>
                                <?php endforeach; ?>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
        
                    </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <input type="hidden" name="check_validate_factor" value="1">
                      <a data-toggle="collapse" data-parent="#accordion" href="#valorization">
                        VALORIZACIÓN DE PUESTO
                      </a>
                    </h4>
                  </div>

                  <div id="valorization" class="panel-collapse collapse in">
                    <div class="panel-body">

                      <div class="row">
                        <div class="col-md-12">
                          <h3 class="sub-title-h3">
                              Valorización del puesto
                          </h3>
                          
                          <?php foreach ($mof_factor_valuations as $row_factor): ?>
                            
                            <div class="col-md-12"style="padding: 5px;">
                              <h5 style="padding: 5px;background:#f9f9f9;"><b><?php echo $row_factor['name']; ?></b></h5>

                              <table id="tbl-factor-type-<?php echo $row_factor['id']; ?>" class="table" width="100%" style="margin: 10px;">
                                <tr>
                                  <?php if ($row_factor['automatic']): ?>
                                    <td width="40%" colspan="2">Factor</td>
                                  <?php else: ?>
                                    <td width="40%">Factor</td>
                                    <th width="40%"></th>
                                  <?php endif; ?>   
                                  <th width="10%">Grado</th>
                                  <th width="10%">Puntaje</th>
                                </tr>
                                <tr>

                                <?php if ($row_factor['automatic']): ?>
                                  <td colspan="2">
                                    <input type="hidden" name="factor[]" value="<?php echo $row_factor['mof_factor_name'] ? '0' : ''; ?>">
                                    <div class="factor-value">
                                      <?php echo $row_factor['mof_factor_name'] ? $row_factor['mof_factor_name'] : '-'; ?>
                                    </div>
                                  </td>
                                  <?php else: ?>
                                    <td width="40%">
                                      <select name="factor[]" class="form-control factor-manual">
                                        <option value="">Seleccione</option>
                                        <?php foreach ($row_factor['factors'] as $key => $factor): ?>
                                          <?php $selected = $factor['id'] == $row_factor['mof_factor_id'] ? 'selected="selected"' : ''; ?>
                                          <option 
                                              value="<?php echo $factor['id']; ?>" 
                                              data-factor-desc="<?php echo $factor['name']; ?>" 
                                              data-factor-grade="<?php echo $factor['grade']; ?>"
                                              data-factor-score="<?php echo $factor['score']; ?>"
                                              <?php echo $selected; ?>
                                            >
                                            <?php echo $factor['level']; ?>
                                          </option>
                                        <?php endforeach; ?>
                                      </select>
                                    </td>
                                    <td width="40%"><?php echo $row_factor['mof_factor_name'] ? $row_factor['mof_factor_name'] : '-'; ?></td>
                                  <?php endif; ?>
                                  <td width="10%"><?php echo $row_factor['mof_factor_grade'] ? $row_factor['mof_factor_grade'] : '-'; ?></td>
                                  <td width="10%"><?php echo $row_factor['mof_factor_score'] ? $row_factor['mof_factor_score'] : '-'; ?></td>
                                </tr>
                              </table>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

              </div>


              <div style="text-align: center;padding-top: 10px;">
                <button id="submit_button" type="submit" class="btn btn-primary">
                  Guardar
                </button>
              </div>
          </div>
        </div>
      </div>
      <?php echo form_close();?>
    </div>
  </div>
  <?php $this->load->view('common/bottom_ads'); ?>
  <!--Footer-->
  <?php $this->load->view('common/footer'); ?>
  <?php $this->load->view('common/before_body_close'); ?>
  <script id="tpl-add-skill" type="text/template">
    <span>
      <input type="hidden" name="skills[]" value="{{skill}}" >{{skill}}
      <button type="button" onclick="$(this).closest('span').remove();">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
    </span>
  </script>

  <script id="tpl-add-responsibility" type="text/template">
    <tr>
      <td width="5%" style="text-align: center;"><span class="counter"></span></td>
      <td>
        <textarea name="responsibilities[]" class="form-control text-resposibility" style="height: 35px;"></textarea>
      </td>
      <td width="10%">
        <button type="button" class="btn-remove-item"><i class="glyphicon glyphicon-remove"></i></button>
      </td>
    </tr>
  </script>

  <script id="tpl-add-indicators" type="text/template">
    <tr>
        <td width="30%">
        <input type="text" name="indicators[{{index}}][indicator]" class="form-control">
        </td>
        <td width="40%">
        <input type="text" name="indicators[{{index}}][formula]" class="form-control">
        </td>
        <td width="30%">
        <input type="text" name="indicators[{{index}}][source_info]" class="form-control">
        </td>
        <td width="5%">
        <button type="button" class="btn-remove-item"><i class="glyphicon glyphicon-remove"></i></button>
        </td>
    </tr>
  </script>

  <script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script>
  <script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>

  <script type="text/javascript">
    
    $(document).ready(function(){
      
      tippy('.grade', {
          trigger: 'change',
          onTrigger(instance, event) {

            if (event.target.selectedIndex == 0) {
              instance.disabled(); 
              return;
            }
                        
            grade_info = $(event.target).find('option:selected').data('grade-info');
            instance.setProps({
              content: grade_info,
              allowHTML: true,
            });
          },
        });

      function is_valid_responsibilities() { 
        error = false;
        inputs = $( 'textarea[name="responsibilities[]"]');

        if (inputs.length == 0)  {
          toastr["error"]('¡Debe ingresar al menos 1 responsabilidad!');
          return false;
        }

        inputs.each(function(e, i){
          if ($.trim($(i).val()) == '') {
            error = true;
          }
        });

        if (error) {
          toastr["error"]('¡Las responsabilidades agregadas no deben quedar vacías!');
        }

        return !error;
      }

      function is_valid_grade() {
        grade_error = false;

        $( ".grade" ).each(function(){
          if ($.trim($(this).val()) == '') {
            grade_error = true;
          }
        });

        return !grade_error;
        }

      function is_valid_resources() {
        error = false;

        $( '.js-resource-error' ).remove();

        $( '.js-resource-input' ).each(function(e, i){
      
          if ($.trim($(i).val()) == '') {
            $(i).closest('tr').find('td:eq(1)').prepend('<span class="js-resource-error">Seleccione recurso</span>');
            error = true;
          }
        });

        if (error) {
          toastr["error"]('¡SECCIÓN RECURSOS: hay datos sin seleccionar!');
          return !error;
        }


        $( '.js-resource-type-expense' ).each(function(e, i){

          value = $(i).closest('tr').find('.js-resource-input').val();

          if (value != null && value != 0 && value != '' && value != 'No aplica' && value[0] != 0 && $.trim($(i).val()) == '') {
            error = true;
            $(i).closest('tr').find('td:eq(1)').prepend('<span class="js-resource-error">Seleccione Tipo de egreso</span>');
          }
        });

        if (error) {
          toastr["error"]('¡SECCIÓN RECURSOS: debe seleccionar un tipo de egreso!');
          return !error;
        }

        $( '.js-resource-stage' ).each(function(e, i){

          value = $(i).closest('tr').find('.js-resource-input').val();

          if (value != null && value != 0 && value != '' && value != 'No aplica' && value[0] != 0 && $.trim($(i).val()) == '') {
            error = true;
            $(i).closest('tr').find('td:eq(2)').prepend('<span class="js-resource-error">Seleccione etapa</span>');
          }
        });

        if (error) {
          toastr["error"]('¡SECCIÓN RECURSOS: debe seleccionar la etapa a realizar!');
          return !error;
        }

        return true;
      }

      function is_valid_factors() { 
        error = false;
        $("*[name='factor[]']").each(function(e, i){

          if ($.trim($(i).val()) == '') {
            error = true;
          }
        });

        return !error;
      }

      $( "#form-mof-edit" ).submit(function(e){

        if (!is_valid_responsibilities()) {
          return false;
        }

        if (!is_valid_resources()) {
          return false;
        }

        if (!is_valid_grade()) {
          toastr["error"]('¡SECCIÓN DISCAPACIDADES: Todos los grados deben ser seleccionados!');
          return false;
        }

        if (!is_valid_factors()) {
          toastr["error"]('¡SECCIÓN VALORIZACIÓN DEL PUESTO: Factores no pueden quedar vacios!');
          return false;
        }

        $( "#submit_button" ).prop('disabled', true);
      });
    function addIndicator() {
        var template = $( "#tpl-add-indicators" ).html();
        var index = $( "#table-indicators" ).generateSequence();
        row = Mustache.render(template, {index: index});
        $( "#table-indicators tbody" ).prepend(row);
    }

      function updateCounter() {
         $( "#table-responsibilities tbody tr" ).each(function(){
          var counter = $(this).find("td:eq(0) span");
          //counter.text($(this).index() + 1);
        });
      }

      function addSkill() {
        var val = $.trim($( "#input-skill" ).val());
        
        if (val == '') {
          return;
        }

        var template = $( "#tpl-add-skill" ).html();
        skill_html = Mustache.render(template, {skill: val});
        $( "#wrapper-skills" ).prepend(skill_html);
        $( "#input-skill" ).val("");
      }

      $( "form" ).keypress(function(e){ 
        if(e.which  == 13){
            return e.target.id != 'input-skill';
        }
      });

      $( "#btn-add-responsibility" ).click(function(){
        var template = $( "#tpl-add-responsibility" ).html();
        row = Mustache.render(template);
        $( "#table-responsibilities tbody" ).prepend(row);
        updateCounter();
      });

      $( "#input-skill" ).keyup(function(e) {
        var val = $.trim($(this).val());

        if (e.keyCode == 13 && val != '') {
          addSkill();
        }

        e.preventDefault();
        return false;
      });

      $(document).on("click", ".btn-remove-item", function(){
        $(this).closest("tr").remove();
        updateCounter();
      });

      $( "#btn-add-skill" ).click(function(){
        addSkill();
      });

      $( "#occupational_group" ).change(function(){
        var jobChargeId = $(this).val();

        var url = "<?php echo site_url('employer/job_profiles/profiles/get_skills/'); ?>" + jobChargeId;

        $( "#wrapper-fixed-skills" ).empty();
        
        $.post(url, {}, function(response) {

          var skills = response.skills;

          for (skill in skills) {
            $( "#wrapper-fixed-skills" ).append(
              `<span>
                ${skills[skill]}
              </span>`
            );
          }
        }, 'json');
      });

      $( ".text-resposibility" ).each(function () {
        this.style.height = '0px';
        this.style.height = (this.scrollHeight + 5) + 'px';
      });

      $(document).on("input", ".text-resposibility", function(){
        this.style.height = '0px';
        this.style.height = (this.scrollHeight + 5) + 'px';
      });

        $( "#belonging_areas" ).select2({
            closeOnSelect: false
        });

      $( "#type_screening" ).select2({
         closeOnSelect: false
      });

      $( "#type_emo" ).select2({
         closeOnSelect: false
      });

      $( "#exam_type_covid" ).select2({
         closeOnSelect: false
      });

      $( "#type_emo" ).change(function(){

        var emo = $(this).val();

        if (emo == 'PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE') {
          $( "#emo-detail" ).show();
          $( "#protocol-detail" ).prop('required', true);
        } else {
          $( "#emo-detail" ).hide();
          $( "#protocol-detail" ).prop('required', false);
        }
      });

      $( "#exams-complementary" ).select2({
        closeOnSelect: false
      });

      $( "#exam_type_covid" ).change(function(){
        var arr = $(this).val();
        var index = arr.indexOf('0');
        if (index > -1 && arr.length > 1) {

          arr.splice(index, 1);
          $(this).val(['No aplica']);
          $(this).select2({
            closeOnSelect: false
          });
        }
      });

        $( "#btn-add-indicator" ).click(function(){
            addIndicator();
        }); 

      $( ".factor-manual" ).change(function(){
        var tr = $(this).closest('tr');
        
        var desc = '-';
        var grade = '-';
        var score = '-';

        if ($(this).val() != '') {
          desc = $(this).find('option:selected').data('factor-desc');
          grade = $(this).find('option:selected').data('factor-grade');
          score = $(this).find('option:selected').data('factor-score');
        }

        tr.find('td:eq(1)').html(desc);
        tr.find('td:eq(2)').html(grade);
        tr.find('td:eq(3)').html(score);
      });

      $( ".factor-automatic" ).change(function(){
        var desc = '-';
        var grade = '-';
        var score = '-';
        var id = '';

        var factorTypeId = $(this).data('factor-type-id');
        var factorContent = $('#tbl-factor-type-' + factorTypeId);

        if ($(this).val() != '') {
          var id = 0;
          var desc = $(this).find('option:selected').data('factor-desc');
          var grade = $(this).find('option:selected').data('factor-grade');
          var score = $(this).find('option:selected').data('factor-score');  
        }

        var tr = factorContent.find('tr:eq(1)');

        tr.find('td:eq(0)').find('.factor-value').html(desc);
        tr.find('td:eq(1)').html(grade);
        tr.find('td:eq(2)').html(score);
        tr.find('input[name="factor[]"]').val(id);
      });

      $('.number-format' ).each(function(index, input){

        input.oninvalid = function(e) {
            if (!e.target.validity.valid && e.target.validity.patternMismatch) {
              e.target.setCustomValidity("El formato de ingreso es icorrecto, máximo 8 números y 2 decimales. Ej: 12534388.32");
            }
        };
        input.oninput = function(e) {
            e.target.setCustomValidity("");
        };
      });

      $( '.number-format' ).bind('keyup blur',function(){ 
        $(this).val( $(this).val().replace(/[^0-9\.]/g, '') ); 
      });

      $("#basic_maximum, #basic_minimum").keyup(function(){

        var basicMinimum = parseFloat($( "#basic_minimum" ).val());
        var basicMaximum = parseFloat($( "#basic_maximum" ).val());

        var diff = basicMaximum - basicMinimum;

        $( "#factor_differentiating" ).removeAttr('required');
        $( "#factor_differentiating" ).closest('.input-group').find('span').html("");

        if (diff >= 50) {
          $( "#factor_differentiating" ).closest('.input-group').find('span').html("*");
          $( "#factor_differentiating" ).prop('required');
        }        
      });

      $( "#factor_differentiating" ).change(function(e){
        $( "#content_factor_differentiating_other" ).hide();
        $( "#factor_differentiating_other" ).removeAttr('required');

        if ($(this).val() == 'Otro') {
          $( "#factor_differentiating_other" ).prop('required', true);
          $( "#content_factor_differentiating_other" ).show();
        }
      });

      var resource_sc = "<?php echo $resource_screening ? true : false ; ?>";

      if (resource_sc) {
        $( 'input[name="check_validate_resources"]').prop('checked', true);
      }

      $( "#type_emo" ).change();
      $( ".factor-automatic" ).change();
      $( "#factor_differentiating" ).change();
    });
  </script>
  </body>
</html>
