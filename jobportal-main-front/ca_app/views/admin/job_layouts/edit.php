<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
<link rel="stylesheet" href="<?php echo base_url('public/css/select2/select2.min.css'); ?>">

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

      background: #aaa;
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

  .errowbox {
    color: #f56954;
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
<body class="skin-blue">
<?php $this->load->view('admin/common/after_body_open'); ?>
<?php $this->load->view('admin/common/header'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
<?php $this->load->view('admin/common/left_side'); ?>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Gestionar Layouts de puesto
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo site_url('admin/job_layouts');?>">Layout de puesto</a></li>
      <li class="active">Editar layout de puesto</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- title row -->
    <div class="row">
      <?php if(validation_errors() != false):?>
      <div class="message-container">
      	<div class="callout callout-danger">
        <h4>¡Por favor verifica algunos datos del formulario!</h4>
      </div>
      </div>
      <?php endif; ?>
      
      <?php $error_message = $this->session->flashdata('error'); ?>
      <?php if ($error_message):?>
        <div class="message-container">
       	<div class="callout callout-danger">
            <h4><?php  echo $error_message; ?></h4>
        </div>
        </div>
      <?php endif; ?>
      
      <div class="col-md-8"> 
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title" style="display: block;float:none;">
            Editar layout de puesto
            <span class="pull-right" style="padding-right: 10px;font-size:15px;">
              SG-OD-002 Versión: 04
              <br />
              Fecha de actualización: <?php  echo date_formats($job_layout->last_update, 'd/m/Y'); ?>
            </span>
            </h3>
          </div>
          
          <!-- /.box-header --> 
          <!-- form start -->
          <?php echo form_open('admin/job_layouts/edit/' . $job_layout->id, ['id' => 'form-profile-edit']); ?>
            <div class="box-body">
              <div class="formint">
                <h3 class="sub-title-h3">Descripción del puesto <span> </span></h3>
            
                <div class="form-group">
                  <label >Código <span></span></label>
                  <div><?php echo $job_layout->code ? $job_layout->code : 'Para generar el codigo por favor guarde el Perfil'; ?></div>
                </div>

                <div class="form-group">
                  <label >Código integración<span></span></label>
                  <div><?php echo $job_layout->code_integration ? $job_layout->code_integration : 'Para generar el codigo por favor guarde el Perfil'; ?></div>
                </div>
                    
                <div class="form-group <?php echo (form_error('job_title'))?'has-error':'';?>">
                  <label >Nombre del cargo <span>*</span></label>
                  <input name="job_title" required="true" type="text" class="form-control" id="job_title" placeholder="Nombre del cargo" value="<?php echo set_value('job_title') ? set_value('job_title') : $job_layout->job_title; ?>" maxlength="100">
                  <?php echo form_error('job_title'); ?>
                </div>
                
                <?php if ($country->iso_3166_1_alpha2 == 'PE'): ?>
                <div class="form-group <?php echo (form_error('sunat_code'))?'has-error':'';?>">
                  <label >Código SUNAT <span></span></label>
                  <select name="sunat_code" class="form-control" id="sunat_code" required="true">
                    <option value="">Seleccione</option>
                    <?php foreach ($sunat_codes as $row): ?>
                      <option value="<?php echo $row->code; ?>" <?php echo $row->code == $job_layout->sunat_code ? 'selected="selected"' : '' ?>>
                        <?php echo $row->code . ' - ' . $row->description; ?>     
                      </option>     
                    <?php endforeach; ?>        
                  </select>
                  <?php echo form_error('sunat_code'); ?>
                </div>
                <?php endif; ?>

                <div class="form-group <?php echo (form_error('occupational_group'))?'has-error':'';?>">
                  <label>Grupo ocupacional <span>*</span></label>
                  <select name="occupational_group" class="form-control factor-automatic" data-factor-type-id="3" data-factor-ref-val="skills" id="occupational_group" style="width: 90%;" required="true">
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
                        data-factor-grade="<?php echo $row->valorization_grade; ?>"
                        data-factor-score="<?php echo $row->valorization_score; ?>"
                        data-factor-desc="<?php echo join(',', $array_skills); ?>"
                        <?php echo $selected; ?>>
                        <?php echo $row->charge_name; ?>    
                      </option>
                    <?php endforeach; ?>                  
                  </select>
                  <?php echo form_error('occupational_group'); ?>
                </div>

                <div class="form-group <?php echo (form_error('risk_criteria')) ? 'has-error':'';?>">
                  <label>Criterio de riesgo <span>*</span></label>
                  <select name="risk_criteria" class="form-control" id="risk_criteria" required="true">
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

                <div class="form-group <?php echo (form_error('study_grade_req'))?'has-error':'';?>">
                  <label>Grado de estudio <span>*</span></label>
                  <select name="study_grade_req" class="form-control" id="study_grade_req" required="true">
                    <option value="">Seleccione</option>
                    <?php foreach($qualifications as $row_qualification):
                      $study_grade_req = set_value('study_grade_req') ? set_value('study_grade_req') : $job_layout->study_grade_req; 
                      $selected = ($study_grade_req == $row_qualification->ID) ? 'selected="selected"' : '';
                    ?>
                      <option value="<?php echo $row_qualification->ID;?>" <?php echo $selected;?>><?php echo $row_qualification->text;?></option>
                    <?php endforeach;?>
                  </select>
                  <?php echo form_error('study_grade_req'); ?>
                </div>

                <div class="form-group <?php echo (form_error('education_req_detail'))?'has-error':'';?>">
                  <label>Más detalle <span>*</span></label>
                  <textarea name="education_req_detail" required="true" class="form-control" id="education_req_detail"><?php echo set_value('education_req_detail') ? set_value('education_req_detail') : $job_layout->education_req_detail; ?></textarea>
                  <?php echo form_error('education_req_detail'); ?>
                </div>
                
                <h3 class="sub-title-h3">Educación Mínima</h3>

                <div class="form-group <?php echo (form_error('study_grade_min'))?'has-error':'';?>">
                  <label>Grado de estudio <span>*</span></label>
                  <select name="study_grade_min" required="true" type="text" class="form-control factor-automatic" data-factor-type-id="1" data-factor-ref-val="factor-desc" id="study_grade_min" required="true">
                    <option value="">Seleccione</option>
                      <?php foreach($qualifications as $row_qualification):
                        $study_grade_min = set_value('study_grade_min') ? set_value('study_grade_min') : $job_layout->study_grade_min; 
                        $selected = ($study_grade_min == $row_qualification->ID) ? 'selected="selected"':'';
                      ?>
                      <option 
                        value="<?php echo $row_qualification->ID;?>" 
                        data-factor-grade="<?php echo $row_qualification->valorization_grade; ?>"
                        data-factor-score="<?php echo $row_qualification->valorization_score; ?>"
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
                  <textarea name="education_min_detail" required="true" class="form-control" id="education_min_detail"><?php echo set_value('education_min_detail') ? set_value('education_min_detail') : $job_layout->education_min_detail; ?></textarea>
                  <?php echo form_error('education_min_detail'); ?>
                </div>

                <h3 class="sub-title-h3">Formación</h3>

                <div class="form-group <?php echo (form_error('education'))?'has-error':'';?>">
                  <label>Formación necesaria <span>*</span></label>
                  <textarea name="education" required="true" class="form-control" id="education"><?php echo set_value('education') ? set_value('education') : $job_layout->education; ?></textarea>
                  <?php echo form_error('education'); ?>
                </div>

                <h3 class="sub-title-h3">Experiencia</h3>

                <div class="form-group <?php echo (form_error('experience'))?'has-error':'';?>">
                  <label>Experiencia <span>*</span></label>

                  <select name="experience" type="text" class="form-control factor-automatic" data-factor-type-id="2" data-factor-ref-val="factor-desc" id="experience" required="true">
                    <option value="">Seleccione</option>
                    <?php foreach($work_experiences as $row):
                      $experience = set_value('experience') ? set_value('experience') : $job_layout->experience;
                      $selected = ($experience == $row->code) ? 'selected="selected"':'';
                    ?>
                    <option value="<?php echo $row->code; ?>"
                        data-factor-grade="<?php echo $row->valorization_grade; ?>"
                        data-factor-score="<?php echo $row->valorization_score; ?>"
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
                  <textarea name="experience_detail" required="true" class="form-control" id="experience_detail"><?php echo set_value('experience_detail') ? set_value('experience_detail') : $job_layout->experience_detail; ?></textarea>
                  <?php echo form_error('experience_detail'); ?>
                </div>

                <h3 class="sub-title-h3">Habilidades</h3>

                <div class="form-group <?php echo (form_error('skills[]'))?'has-error':'';?>" style="margin-bottom: 30px;">
                  <label>Habilidades requeridas <span></span></label>
                  <table>
                    <tr>
                      <td width="85%">
                        <input id="input-skill" type="text" class="form-control" placeholder="Ingrese habilidad">
                      </td>
                      <td>
                        <button id="btn-add-skill" class="btn" type="button">Agregar</button>
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
                    <div id="wrapper-fixed-skills"  class="wrapper-skills" style="display: inline-block;">
                    </div>
                  </div>
                  <?php echo form_error('skills[]'); ?>
                </div>

                <h3 class="sub-title-h3">Responsabilidades</h3>
                <div class="row">
                  <div class="col-md-12">
                    <button id="btn-add-responsibility" type="button" class="btn btn-sm" style="margin-bottom: 15px;">Agregar</button>
                  </div>
                </div>

                <div class="row"> 
                  <div class="col-md-10 <?php echo (form_error('responsibilities[]')) ? 'has-error' : '';?>" >
                    <?php echo form_error('responsibilities[]'); ?>
                    <table id="table-responsibilities" class="tbl-base-1 tbl-style-1" width="100%">
                      <tbody>
                        <?php $responsibilities = set_value('responsibilities') ? set_value('responsibilities') : $responsibilities; ?>
                        <?php foreach ($responsibilities as $index => $responsibility): ?>
                        <?php $responsibility = is_object($responsibility) ? $responsibility->responsibility : $responsibility; ?>
                          <tr>
                            <td width="5%">
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

                    <div class="form-group">
                      <label >Estereotipo <span>*</span></label>
                      <select name="stereotype" class="form-control" id="stereotype" style="min-width: 100%;" required>
                        <option value="">Seleccione</option>
                        <option value="Masculino" <?php echo $job_layout->stereotype == 'Masculino' ? 'selected="selected"' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo $job_layout->stereotype == 'Femenino' ? 'selected="selected"' : ''; ?>>Femenino</option>
                        <option value="Neutro" <?php echo $job_layout->stereotype == 'Neutro' ? 'selected="selected"' : ''; ?>>Neutro</option>
                      </select>
                    </div>
                          
                    <div class="form-group">
                      <label >Factor diferenciador <span></span></label>
                      <select name="factor_differentiating" class="form-control" id="factor_differentiating" style="min-width: 100%;">
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

                    <div id="content_factor_differentiating_other" class="form-group">
                      <label >Factor diferenciador Otro <span>*</span></label>
                      <textarea name="factor_differentiating_other" 
                                class="form-control" 
                                id="factor_differentiating_other"
                                style="min-width: 100%;"><?php echo $job_layout->factor_differentiating_other; ?></textarea>
                    </div>

                    <div class="form-group">
                      <label>Categoría ocupacional <span></span></label>
                      <select name="occupational_category" class="form-control" id="occupational_category" style="min-width: 100%;">
                        <option value="" data-level="-">Seleccione</option>
                        <?php foreach ($occupational_categories as $row): ?>
                          <?php $selected = $row->id == $job_layout->occupational_category_id ? 'selected="selected"' : '';?>
                          <option value="<?php echo $row->id; ?>" data-level="<?php echo $row->level; ?>" <?php echo $selected; ?>>
                              <?php echo $row->name; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="form-group" style="display: none;">
                      <label>Nivel Categoría ocupacional <span></span></label>
                      <div id="occupational_category_level">-</div>
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
                                <?php echo 'PERMITIR ' . $row_theme->name; ?>
                              </h3>
                              <?php foreach ($row_theme->subthemes as $row_subtheme): ?>
                                <div class="form-group">
                                  <label><?php e($row_subtheme->name); ?> <span>*</span></label>
                                  <select name="disability[<?php echo $row_subtheme->id; ?>][allow]" 
                                          class="form-control" 
                                          style="width: 100%;" 
                                          required>
                                    <option value="">Seleccione</option>
                                    <option value="1" <?php echo $row_subtheme->jl_disability_allow == 1 ? 'selected' : '';?>>SI</option>
                                    <option value="0" <?php echo $row_subtheme->jl_disability_allow == 0 ? 'selected' : '';?>>NO</option>        
                                  </select>
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
                            
                            <?php $jesus = 0; foreach ($factor_valuations as $row_factor): ?>
                              
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
              
              </div>
            <!-- /.box-body -->
            
            <div class="box-footer" style="text-align: center;padding-top: 30px;">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          <?php echo form_close(); ?>
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
    <!-- info row --> 
    
  </section>
  <!-- /.content --> 
</aside>
<!-- /.right-side -->
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

<?php $this->load->view('admin/common/footer'); ?>

<script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
<script>
    const JOB_LAYOUT_ID = <?php echo (int) $job_layout->id; ?>;
    const COMPANY_ID   = <?php echo (int) $job_layout->company_id; ?>;
</script>
<script type="text/javascript">
$(function(){
  //let jobTitleExiste = false;

  // $("#job_title").on("blur", function () {

  //     const job_title = $.trim($(this).val());
  //     if (job_title === '') return;

  //     $.ajax({
  //         url: "<?php echo site_url('validate-job-title-edit-admin'); ?>",
  //         type: "POST",
  //         dataType: "json",
  //         data: {
  //             job_title: job_title,
  //             id: JOB_LAYOUT_ID,
  //             company_id: COMPANY_ID
  //         },
  //         success: function (res) {
  //             if (res.existe) {
  //                 jobTitleExiste = true;
  //                 toastr.error("Este cargo ya existe en esta empresa.");
  //                 $("#job_title").addClass("is-invalid");
  //             } else {
  //                 jobTitleExiste = false;
  //                 $("#job_title").removeClass("is-invalid");
  //             }
  //         }
  //     });
  // });

  $("#form-profile-edit").on("submit", function () {
    // if (jobTitleExiste) {
    //     toastr.error("No puede guardar: el cargo ya existe en esta empresa.");
    //     return false;
    // }
  });

});
</script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<?php $this->load->view('admin/job_layouts/scripts/edit_job_layout_js'); ?>