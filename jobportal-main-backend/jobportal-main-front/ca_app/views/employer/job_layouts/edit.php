<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('common/meta_tags'); ?>
    <title><?php echo $title;?></title>
    <?php $this->load->view('common/before_head_close'); ?>
    <style type="text/css">
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

      .input-group .select2-container {
        display: table-cell;
        width: 65% !important;
        position: absolute;
      }

      @media (max-width: 768px) {
        .formwraper .input-group {
          display: block;
        }

        .input-group .select2-container {
          display: block;
          width: 100% !important;
          position: relative;
        }
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
      <?php echo form_open('employer/job_layouts/job_layouts/edit/' . $job_layout->id, ['id' => 'form-job-layout-edit']); ?>
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
            Editar layout de puesto   
          </div>
          <div class="formint">
            <div class="info-required">
              <span>*</span> Campos obligatorios 
            </div>
              <h3 class="sub-title-h3">Descripción del puesto <span> </span></h3>
              
              <div class="input-group <?php echo (form_error('job_title'))?'has-error':'';?>">
                <label class="input-group-addon" >Nombre del cargo <span>*</span></label>
                <input name="job_title" required type="text" class="form-control" id="job_title" placeholder="Nombre del cargo" value="<?php echo set_value('job_title') ? set_value('job_title') : $job_layout->job_title; ?>" maxlength="100">
                <?php echo form_error('job_title'); ?>
              </div>

              <?php if ($country->iso_3166_1_alpha2 == 'PE'): ?>
                <div class="input-group <?php echo (form_error('sunat_code'))?'has-error':'';?>">
                  <label class="input-group-addon">Código SUNAT <span>*</span></label>
                  <select name="sunat_code" class="form-control" id="sunat_codes" style="width: 100%;" required="true">
                    <option value="">Seleccione</option>
                    <?php foreach ($sunat_codes as $sunat_code_row): ?>
                      <option value="<?php echo $sunat_code_row->code; ?>" 
                              <?php echo $job_layout->sunat_code == $sunat_code_row->code ? 'selected' : ''; ?>>
                        <?php e($sunat_code_row->code . ' - ' . $sunat_code_row->description); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('sunat_code'); ?>
                </div>
              <?php endif; ?>
              
              <div class="input-group <?php echo (form_error('occupational_group'))?'has-error':'';?>">
                  <label class="input-group-addon">Grupo ocupacional <span>*</span></label>
                  <select name="occupational_group" class="form-control factor-automatic" data-factor-type-id="3" data-factor-ref-val="skills" id="occupational_group" style="width: 100%;" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($job_charges as $row): ?>
                      <?php 
                        $selected = ($row->ID == $job_layout->job_charge_id) ? 'selected="selected"' : ''; 
                        $og_skills = $this->db->get_where('tbl_job_charge_skills', [
                          'job_charge_id' => $row->ID
                        ])->result();
                
                        $array_skills = [];
                
                        foreach ($og_skills as $skill) {
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

                <div class="input-group <?php echo (form_error('risk_criteria')) ? 'has-error':'';?>">
                  <label class="input-group-addon">Criterio de riesgo <span>*</span></label>
                  <select name="risk_criteria" required class="form-control" id="risk_criteria" style="width: 100%;">
                    <option value="">Seleccione</option>
                    <?php foreach ($risk_criteria as $criteria): ?>  
                      <option value="<?php echo $criteria->name; ?>" <?php echo $criteria->name == $job_layout->risk_criteria ? 'selected="selected"' : '' ?>>
                        <?php echo $criteria->name; ?>    
                      </option>
                    <?php endforeach; ?>        
                  </select>
                  <?php echo form_error('risk_criteria'); ?>
                </div>

              <h3 class="sub-title-h3">Educación Deseable</h3>

              <div class="input-group <?php echo (form_error('study_grade_req'))?'has-error':'';?>">
                <label class="input-group-addon">Grado de estudio <span>*</span></label>
                <select name="study_grade_req" required class="form-control" id="study_grade_req" style="width: 100%;">
                  <option value="">Seleccione</option>
                  <?php foreach ($qualifications as $row_qualification): ?>
                    <?php
                      $study_grade_req = set_value('study_grade_req') ? set_value('study_grade_req') : $job_layout->study_grade_req; 
                      $selected = ($study_grade_req == $row_qualification->ID) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $row_qualification->ID;?>" <?php echo $selected;?>>
                      <?php echo $row_qualification->text; ?>    
                    </option>
                  <?php endforeach;?>
                </select>
                <?php echo form_error('study_grade_req'); ?>
              </div>

              <div class="input-group <?php echo (form_error('education_req_detail'))?'has-error':'';?>">
                <label class="input-group-addon">Más detalle <span>*</span></label>
                <textarea name="education_req_detail" required class="form-control" id="education_req_detail"><?php echo set_value('education_req_detail') ? set_value('education_req_detail') : $job_layout->education_req_detail; ?></textarea>
                <?php echo form_error('education_req_detail'); ?>
              </div>
              
              <h3 class="sub-title-h3">Educación Mínima</h3>

              <div class="input-group <?php echo (form_error('study_grade_min'))?'has-error':'';?>">
                <label class="input-group-addon">Grado de estudio <span>*</span></label>
                <select name="study_grade_min" required type="text" class="form-control factor-automatic" data-factor-type-id="1" data-factor-ref-val="factor-desc" id="study_grade_min" required style="width: 100%;">
                  <option value="">Seleccione</option>
                    <?php foreach($qualifications as $row_qualification):
                      $study_grade_min = set_value('study_grade_min') ? set_value('study_grade_min') : $job_layout->study_grade_min; 
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

              <div class="input-group <?php echo (form_error('education_min_detail'))?'has-error':'';?>">
                <label class="input-group-addon">Más detalle <span>*</span></label>
                <textarea name="education_min_detail" required class="form-control" id="education_min_detail"><?php echo set_value('education_min_detail') ? set_value('education_min_detail') : $job_layout->education_min_detail; ?></textarea>
                <?php echo form_error('education_min_detail'); ?>
              </div>

              <h3 class="sub-title-h3">Formación</h3>

              <div class="input-group <?php echo (form_error('education'))?'has-error':'';?>">
                <label class="input-group-addon">Formación necesaria <span>*</span></label>
                <textarea name="education" required class="form-control" id="education"><?php echo set_value('education') ? set_value('education') : $job_layout->education; ?></textarea>
                <?php echo form_error('education'); ?>
              </div>

              <h3 class="sub-title-h3">Experiencia</h3>

              <div class="input-group <?php echo (form_error('experience'))?'has-error':'';?>">
                <label class="input-group-addon">Experiencia <span>*</span></label>

                <select name="experience" type="text" class="form-control factor-automatic" data-factor-type-id="2" data-factor-ref-val="factor-desc" id="experience" required style="width: 100%;">
                  <option value="">Seleccione</option>       
                  <?php foreach($work_experiences as $row):
                      $experience = set_value('experience') ? set_value('experience') : $job_layout->experience;
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

              <div class="input-group <?php echo (form_error('experience_detail'))?'has-error':'';?>">
                <label class="input-group-addon">Más detalle <span>*</span></label>
                <textarea name="experience_detail" required class="form-control" id="experience_detail"><?php echo set_value('experience_detail') ? set_value('experience_detail') : $job_layout->experience_detail; ?></textarea>
                <?php echo form_error('experience_detail'); ?>
              </div>

              <h3 class="sub-title-h3">Habilidades</h3>

              <div class="input-group <?php echo (form_error('skills[]'))?'has-error':'';?>" style="margin-bottom: 30px;">
                <label class="input-group-addon">Habilidades requeridas <span>*</span></label>
                <table>
                  <tr>
                    <td width="85%">
                      <input id="input-skill" type="text" class="form-control" placeholder="Ingrese habilidad">
                    </td>
                    <td>
                      <a id="btn-add-skill" href="#" style="padding-left: 10px;">Agregar</a>
                    </td>
                  </tr>
                </table>

                <div id="wrapper-skills">
                  <?php foreach ($skills as $skill): ?>
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
                      <?php $responsibilities = set_value('responsibilities') ? set_value('responsibilities') : $responsibilities; ?>
                      <?php foreach ($responsibilities as $index => $responsibility): ?>
                      <?php $responsibility = is_object($responsibility) ? $responsibility->responsibility : $responsibility; ?>
                        <tr>
                          <td width="5%" style="display: none;;">
                            <span class="counter"><?php echo ($index + 1); ?></span>
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
                                  value="<?php echo $job_layout->basic_minimum; ?>">
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
                                value="<?php echo $job_layout->basic_maximum; ?>">
                        </td>
                    </tr>
                  </table>

                  <div class="input-group">
                    <label class="input-group-addon">Estereotipo <span>*</span></label>
                    <select name="stereotype" class="form-control" id="stereotype" style="width: 100%;" required>
                      <option value="">Seleccione</option>
                      <option value="Masculino" <?php echo $job_layout->stereotype == 'Masculino' ? 'selected="selected"' : ''; ?>>Masculino</option>
                      <option value="Femenino" <?php echo $job_layout->stereotype == 'Femenino' ? 'selected="selected"' : ''; ?>>Femenino</option>
                      <option value="Neutro" <?php echo $job_layout->stereotype == 'Neutro' ? 'selected="selected"' : ''; ?>>Neutro</option>
                    </select>
                  </div>
                        
                  <div class="input-group">
                    <label class="input-group-addon">Factor diferenciador <span></span></label>
                    <select name="factor_differentiating" class="form-control" id="factor_differentiating" style="width: 100%;">
                      <option value="">Seleccione</option>
                      <option value="Antigüedad en el puesto o en la empresa" <?php echo $job_layout->factor_differentiating == 'Antigüedad en el puesto o en la empresa' ? 'selected="selected"' : ''; ?> >Antigüedad en el puesto o en la empresa</option>
                      <option value="Desempeño" <?php echo $job_layout->factor_differentiating == 'Desempeño' ? 'selected="selected"' : ''; ?>>Desempeño</option>
                      <option value="Apoyo en proyectos específicos" <?php echo $job_layout->factor_differentiating == 'Apoyo en proyectos específicos' ? 'selected="selected"' : ''; ?>>Apoyo en proyectos específicos</option>
                      <option value="Escasez de oferta en el mercado" <?php echo $job_layout->factor_differentiating == 'Escasez de oferta en el mercado' ? 'selected="selected"' : ''; ?>>Escasez de oferta en el mercado</option>
                      <option value="Costo de vida" <?php echo $job_layout->factor_differentiating == 'Costo de vida' ? 'selected="selected"' : ''; ?>>Costo de vida</option>
                      <option value="Experiencia laboral" <?php echo $job_layout->factor_differentiating == 'Experiencia laboral' ? 'selected="selected"' : ''; ?>>Experiencia laboral</option>
                      <option value="Perfil académico o educativo" <?php echo $job_layout->factor_differentiating == 'Perfil académico o educativo' ? 'selected="selected"' : ''; ?>>Perfil académico o educativo</option>
                      <option value="Lugar de trabajo" <?php echo $job_layout->factor_differentiating == 'Lugar de trabajo' ? 'selected="selected"' : ''; ?>>Lugar de trabajo</option>
                      <option value="Jornadas reducidas, atípicas o parciales" <?php echo $job_layout->factor_differentiating == 'Jornadas reducidas, atípicas o parciales' ? 'selected="selected"' : ''; ?>>Jornadas reducidas, atípicas o parciales</option>
                      <option value="Otro" <?php echo $job_layout->factor_differentiating == 'Otro' ? 'selected="selected"' : ''; ?>>Otro</option>
                    </select>
                  </div>

                  <div id="content_factor_differentiating_other" class="input-group">
                    <label class="input-group-addon">Factor diferenciador Otro <span>*</span></label>
                    <textarea name="factor_differentiating_other" class="form-control" id="factor_differentiating_other"><?php echo $job_layout->factor_differentiating_other; ?></textarea>
                  </div>
                </div>
              </div>

              <div class="panel-group" id="accordion">                                        
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
                        $job_layout_disability = set_value('disability') ? set_value('disability') : [];
                      ?>

                      <?php foreach ($disability_options as $row_theme): ?>
                        <div class="row">
                          <div class="col-md-12">
                            <h3 class="sub-title-h3">
                              <?php echo $row_theme->name; ?>
                            </h3>
                            <?php foreach ($row_theme->subthemes as $row_subtheme): ?>
                              <div class="col-md-12"style="padding: 5px;">
                                <div class="input-group">
                                  <label class="input-group-addon"><?php e($row_subtheme->name); ?> <span>*</span></label>
                                  <select name="disability[<?php echo $row_subtheme->id; ?>][allow]" 
                                          class="form-control" 
                                          style="width: 80%;" 
                                          required>
                                    <option value="">Seleccione</option>
                                    <option value="1" <?php echo $row_subtheme->jl_disability_allow == 1 ? 'selected' : '';?>>SI</option>
                                    <option value="0" <?php echo $row_subtheme->jl_disability_allow == 0 ? 'selected' : '';?>>NO</option>        
                                  </select>
                                </div>
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
                        VALORIZACIÓN
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
                          
                          <?php foreach ($factor_valuations as $row_factor): ?>
                            
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
                                    <input type="hidden" name="factor[]" value="<?php echo $row_factor['value_factor_name'] ? '0' : ''; ?>">
                                    <div class="factor-value">
                                      <?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?>
                                    </div>
                                  </td>
                                  <?php else: ?>
                                    <td width="40%">
                                      <select name="factor[]" class="form-control factor-manual">
                                        <option value="">Seleccione</option>
                                        <?php foreach ($row_factor['factors'] as $key => $factor): ?>
                                          <?php $selected = $factor['id'] == $row_factor['value_factor_id'] ? 'selected="selected"' : ''; ?>
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
                                    <td width="40%"><?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?></td>
                                  <?php endif; ?>
                                  <td width="10%"><?php echo $row_factor['value_factor_grade'] ? $row_factor['value_factor_grade'] : '-'; ?></td>
                                  <td width="10%"><?php echo $row_factor['value_factor_score'] ? $row_factor['value_factor_score'] : '-'; ?></td>
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

  <script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script>
  <script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>

  <script type="text/javascript">
  $(function(){
    
    let jobTitleExiste = false;
    
    $("#job_title").on("blur", function () {

        const job_title = $.trim($(this).val());
        if (job_title === '') return;

        $.ajax({
            url: "<?php echo site_url('validate-job-title-edit/' . $job_layout->id); ?>",
            type: "POST",
            dataType: "json",
            data: {
                job_title: job_title
            },
            success: function (res) {
                if (res.existe) {
                    jobTitleExiste = true;
                    toastr.error("Este cargo ya existe en esta empresa.");
                    $("#job_title").addClass("is-invalid");
                } else {
                    jobTitleExiste = false;
                    $("#job_title").removeClass("is-invalid");
                }
            }
        });
    });

    if (jobTitleExiste) {
      toastr.error("No puede guardar: el cargo ya existe en esta empresa.");
      return false;
    }


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

    function is_valid_factors() { 
      error = false;
      $("*[name='factor[]']").each(function(e, i){

        if ($.trim($(i).val()) == '') {
          error = true;
        }
      });

      return !error;
    }

    $( "#form-job-layout-edit" ).submit(function(e){
      e.preventDefault();
      
      if (!is_valid_responsibilities()) {
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

      const url = $(this).prop('action');
      const data = $(this).serialize();

      $.post(url, data, function(res){
        if (res.status) {
          window.location = res.redirect_url;
          return;
        }

        $( "#submit_button" ).prop('disabled', false);
        toastr["error"](res.message);

      }, 'json')
      .fail(function(e) {
        $( "#submit_button" ).prop('disabled', false);
        toastr["error"]('Ha ocurrido un error');
      });

      return false;
    });

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

      var url = "<?php echo site_url('employer/job_layouts/job_layouts/get_skills/'); ?>" + jobChargeId;

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

    $( "#factor_differentiating" ).change();
    $( ".factor-automatic" ).change();
    $( '#sunat_codes' ).select2();
  });
  </script>
  </body>
</html>
