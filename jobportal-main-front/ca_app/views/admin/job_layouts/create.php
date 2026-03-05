<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo base_url('public/css/select2/select2.min.css'); ?>">
</head>
<body class="skin-blue">
<?php $this->load->view('admin/common/after_body_open'); ?>
<?php $this->load->view('admin/common/header'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
<?php $this->load->view('admin/common/left_side'); ?>

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
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Gestionar Layouts de puesto</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('admin/job_layouts');?>">Layouts de puesto</a></li>
      <li class="active">Crear</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- title row -->
    <div class="row">
      <?php if (validation_errors() != false): ?>
      <div class="message-container">
        <div class="callout callout-danger">
        <h4>¡Por favor verifica algunos datos del formulario!</h4>
      </div>
      </div>
      <?php endif; ?>
      
      <div class="col-md-8"> 
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title" style="display: block;float:none;">
            Crear layout de puesto
            <span class="pull-right" style="padding-right: 10px;font-size:15px;">SG-OD-002 Versión: 04</span>
            </h3>
          </div>
          
          <!-- /.box-header --> 
          <!-- form start -->
          <?php echo form_open('admin/job_layouts/create', ['id' => 'form-job-layout-create']); ?>
          <form role="form" method="post" action="<?php echo site_url('admin/job_layouts/create/');?>">
            <div class="box-body">
              <div class="formint">

                <h3 class="sub-title-h3">Descripción Empresa</h3>
                <div class="form-group <?php echo form_error('company_id') ? 'has-error' : ''; ?>">
                  <label>Empresa <span>*</span></label>
                  <br />
                  <select name="company_id" class="form-control" id="company_id" required="true">
                    <option value="">Seleccione</option>
                    <?php foreach ($companies as $row): ?>      
                      <option value="<?php echo $row->ID;?>" <?php echo $row->ID == $company_id ? 'selected="selected"' : ''; ?>>
                        <?php echo $row->company_ruc . ' - ' . $row->company_name; ?>    
                      </option>
                    <?php endforeach; ?>        
                  </select>
                  <?php echo form_error('company__id'); ?>
                </div>

                <h3 class="sub-title-h3">Descripción del puesto</h3>
                
                <div class="form-group <?php echo (form_error('job_title'))?'has-error':'';?>">
                  <label >Nombre del cargo <span>*</span></label>
                  <input name="job_title" type="text" class="form-control" id="job_title" placeholder="Nombre del cargo" value="<?php echo set_value('job_title'); ?>" maxlength="100" required="true">
                  <?php echo form_error('job_title'); ?>
                </div>
                
                <?php if ($country->iso_3166_1_alpha2 == 'PE'): ?>
                  <div class="form-group <?php echo (form_error('sunat_code'))?'has-error':'';?>">
                    <label >Código SUNAT <span>*</span></label>
                    <br />
                    <select name="sunat_code" class="form-control" id="sunat_code" required="true">
                      <option value="">Seleccione</option>
                      <?php foreach ($sunat_codes as $row): ?>      
                        <option value="<?php echo $row->code;?>">
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
                    <?php foreach ($job_charges as $row_area): ?>
                      <?php 
                      $selected = (set_value('occupational_group') == $row_area->ID) ? 'selected="selected"' : ''; 

                      $skills = $this->db->get_where('tbl_job_charge_skills', [
                          'job_charge_id' => $row_area->ID
                      ])->result();
              
                      $array_skills = [];
              
                      foreach ($skills as $skill) {
                          $array_skills[] = mb_strtoupper($skill->skill_name);
                      }
                      sort($array_skills);
                    ?>
                      <option value="<?php echo $row_area->ID; ?>" 
                              data-factor-score="<?php echo $row_area->valorization_score; ?>"
                              data-factor-grade="<?php echo $row_area->valorization_grade; ?>"
                              data-factor-desc="<?php echo join(',', $array_skills); ?>"
                              <?php echo $selected; ?>>
                        <?php echo $row_area->charge_name; ?>    
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
                      <option value="<?php echo $criteria->name; ?>" <?php echo $criteria->name == set_value('risk_criteria') ? 'selected="selected"' : '' ?>>
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
                        $selected = (set_value('study_grade_req') == $row_qualification->ID) ? 'selected="selected"' : '';
                      ?>
                      <option value="<?php echo $row_qualification->ID;?>" <?php echo $selected;?>><?php echo $row_qualification->text;?></option>
                      <?php endforeach;?>
                  </select>
                  <?php echo form_error('study_grade_req'); ?>
                </div>

                <div class="form-group <?php echo (form_error('education_req_detail'))?'has-error':'';?>">
                  <label>Más detalle <span>*</span></label>
                  <textarea name="education_req_detail" required="true" class="form-control" id="education_req_detail"><?php echo set_value('education_req_detail'); ?></textarea>
                  <?php echo form_error('education_req_detail'); ?>
                </div>
                
                <h3 class="sub-title-h3">Educación Mínima</h3>

                <div class="form-group <?php echo (form_error('study_grade_min'))?'has-error':'';?>">
                  <label>Grado de estudio <span>*</span></label>
                  <select name="study_grade_min" type="text" class="form-control factor-automatic" data-factor-type-id="1" data-factor-ref-val="factor-desc" id="study_grade_min" required="true">
                    <option value="">Seleccione</option>
                      <?php foreach($qualifications as $row_qualification):
                        $selected = (set_value('study_grade_min') == $row_qualification->ID) ? 'selected="selected"':'';
                      ?>
                      <option value="<?php echo $row_qualification->ID;?>" 
                              data-factor-score="<?php echo $row_qualification->valorization_score; ?>"
                              data-factor-grade="<?php echo $row_qualification->valorization_grade; ?>"
                              data-factor-desc="<?php echo mb_strtoupper($row_qualification->text); ?>"
                              <?php echo $selected;?>>
                              <?php echo $row_qualification->text; ?>
                      </option>
                      <?php endforeach;?>
                  </select>
                  <?php echo form_error('study_grade_min'); ?>
                </div>

                <div class="form-group <?php echo (form_error('education_min_detail'))?'has-error':'';?>">
                  <label>Más detalle <span>*</span></label>
                  <textarea name="education_min_detail" required="true" class="form-control" id="education_min_detail"><?php echo set_value('education_min_detail'); ?></textarea>
                  <?php echo form_error('education_min_detail'); ?>
                </div>

                <h3 class="sub-title-h3">Formación</h3>

                <div class="form-group <?php echo (form_error('education'))?'has-error':'';?>">
                  <label>Formación necesaria <span>*</span></label>
                  <textarea name="education" required="true" class="form-control" id="education"><?php echo set_value('education'); ?></textarea>
                  <?php echo form_error('education'); ?>
                </div>

                <h3 class="sub-title-h3">Experiencia</h3>

                <div class="form-group <?php echo (form_error('experience'))?'has-error':'';?>">
                  <label>Experiencia <span>*</span></label>

                  <select name="experience" type="text" class="form-control factor-automatic" data-factor-type-id="2" data-factor-ref-val="factor-desc" id="experience" required="true">
                    <option value="">Seleccione</option>       
                    <?php foreach($work_experiences as $row):
                          $selected = (set_value('experience') == $row->code) ? 'selected="selected"':'';
                        ?>
                        <option value="<?php echo $row->code; ?>"
                                data-factor-score="<?php echo $row->valorization_score; ?>"
                                data-factor-grade="<?php echo $row->valorization_grade; ?>"
                                data-factor-desc="<?php echo mb_strtoupper($row->name); ?>" 
                            <?php echo $selected;?>>
                            <?php echo $row->name;?>
                        </option>
                    <?php endforeach; ?> 
                  
                  </select>
                  <?php echo form_error('experience'); ?>
                </div>

                <div class="form-group <?php echo (form_error('experience_detail'))?'has-error':'';?>">
                  <label>Más detalle <span>*</span></label>
                  <textarea name="experience_detail" required="true" class="form-control" id="experience_detail"><?php echo set_value('experience_detail'); ?></textarea>
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
                    <?php $job_layout_skills = set_value('skills') ? set_value('skills') : array(); ?>
                    <?php foreach ($job_layout_skills as $skill_name): ?>
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
                        <?php $job_layout_responsibilities = set_value('responsibilities') ? set_value('responsibilities') : array(); ?>
                        <?php foreach ($job_layout_responsibilities as $index => $responsibility): ?>
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
                                   value="<?php echo set_value('basic_minimum'); ?>">
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
                                 value="<?php echo set_value('basic_maximum'); ?>">
                          </td>
                      </tr>
                    </table>
                    <br>
                  
                    <div class="form-group">
                      <label >Estereotipo <span>*</span></label>
                      <select name="stereotype" class="form-control" id="stereotype" style="min-width: 100%;" required>
                        <option value="">Seleccione</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Neutro">Neutro</option>
                      </select>
                    </div>
                          
                    <div class="form-group">
                      <label >Factor diferenciador <span></span></label>
                      <select name="factor_differentiating" class="form-control" id="factor_differentiating" style="min-width: 100%;">
                        <option value="">Seleccione</option>
                        <option value="Antigüedad en el puesto o en la empresa">Antigüedad en el puesto o en la empresa</option>
                        <option value="Desempeño">Desempeño</option>
                        <option value="Apoyo en proyectos específicos">Apoyo en proyectos específicos</option>
                        <option value="Escasez de oferta en el mercado">Escasez de oferta en el mercado</option>
                        <option value="Costo de vida">Costo de vida</option>
                        <option value="Experiencia laboral">Experiencia laboral</option>
                        <option value="Perfil académico o educativo">Perfil académico o educativo</option>
                        <option value="Lugar de trabajo">Lugar de trabajo</option>
                        <option value="Jornadas reducidas, atípicas o parciales">Jornadas reducidas, atípicas o parciales</option>
                        <option value="Otro">Otro</option>
                      </select>
                    </div>

                    <div id="content_factor_differentiating_other" class="form-group">
                      <label >Factor diferenciador Otro <span>*</span></label>
                      <textarea name="factor_differentiating_other" class="form-control" id="factor_differentiating_other" style="min-width: 100%;"></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label>Categoría ocupacional <span></span></label>
                      <select name="occupational_category" class="form-control" id="occupational_category" style="min-width: 100%;">
                        <option value="" data-level="-">Seleccione</option>
                        <?php foreach ($occupational_categories as $row): ?>
                          <option value="<?php echo $row->id; ?>" data-level="<?php echo $row->level; ?>">
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
                                      <option value="1">SI</option>
                                      <option value="0">NO</option>        
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
                                          <input type="hidden" name="factor[]" value="">
                                          <div class="factor-value">-</div>
                                        </td>
                                    <?php else: ?>
                                      <td width="40%">
                                        <select name="factor[]" class="form-control factor-manual">
                                          <option value="">Seleccione</option>
                                          <?php foreach ($row_factor['factors'] as $key => $factor): ?>
                                            <option 
                                                value="<?php echo $factor['id']; ?>" 
                                                data-factor-desc="<?php echo $factor['name']; ?>" 
                                                data-factor-grade="<?php echo $factor['grade']; ?>"
                                                data-factor-score="<?php echo $factor['score']; ?>"
                                              >
                                              <?php echo $factor['level']; ?>
                                            </option>
                                          <?php endforeach; ?>
                                        </select>
                                      </td>
                                      <td width="40%">-</td>
                                    <?php endif; ?>
                                    <td width="10%">-</td>
                                    <td width="10%">-</td>
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
              <button type="submit" class="btn btn-primary">Crear</button>
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
    <td width="80%">
      <textarea name="responsibilities[]" class="form-control text-resposibility" style="height: 35px;"></textarea>
    </td>
    <td width="10%">
      <button type="button" class="btn-remove-item"><i class="glyphicon glyphicon-remove"></i></button>
    </td>
  </tr>
</script>

<?php $this->load->view('admin/common/footer'); ?>

<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/select2/select2.min.js'); ?>" type="text/javascript"></script> 
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script type="text/javascript">
$(function(){


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

  function is_valid_factors() { 
    error = false;
    $("*[name='factor[]']").each(function(e, i){

      if ($.trim($(i).val()) == '') {
        error = true;
      }
    });

    return !error;
  }

  ///
//   $("#job_title").on("blur", function () {

//     const job_title = $.trim($(this).val());
//     const company_id = $("#company_id").val();

//     if (job_title === '' || company_id === '') return;

//     $.ajax({
//         url: "<?php echo site_url('validate-job-title-admin'); ?>",
//         type: "POST",
//         dataType: "json",
//         data: {
//             job_title: job_title,
//             company_id: company_id
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
//         },
//         error: function () {
//             toastr.error("Error al validar el cargo");
//         }
//     });
// });


  $( "#form-job-layout-create" ).submit(function(e){

      // BLOQUEO POR DUPLICADO
    // if (jobTitleExiste) {
    //    toastr.error("No puede guardar: el cargo ya existe en esta empresa.");
    //    return false; // ⛔ corta todo
    // }    

    if (!is_valid_responsibilities()) {
      return false;
    }

    if (!is_valid_factors()) {
      toastr["error"]('¡SECCIÓN VALORIZACIÓN DEL PUESTO: Factores no pueden quedar vacios!');
      return false;
    }

    $( "#submit_button" ).prop('disabled', true);
  });
  
  function updateCounter() {
      $( "#table-responsibilities tbody tr" ).each(function(){
      var counter = $(this).find("td:eq(0) span");
      counter.text($(this).index() + 1);
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

  $( "#occupational_group" ).change(function(){
    var jobChargeId = $(this).val();

    var url = "<?php echo site_url('admin/job_layouts/get_skills/'); ?>" + jobChargeId;

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

  $( "#sunat_code" ).select2({
      placeholder: "Selecciona una opción",
      allowClear: true
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
        if (e.target.validity.patternMismatch) {
          e.target.setCustomValidity("");
        }

        if (!e.target.validity.valid && e.target.validity.patternMismatch) {
          e.target.setCustomValidity("El formato de ingreso es correcto, máximo 8 números y 2 decimales. Ej: 12534388.32");
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
    $( "#factor_differentiating" ).closest('.form-group').find('span').html("");

    if (diff >= 50) {
      $( "#factor_differentiating" ).closest('.form-group').find('span').html("*");
      $( "#factor_differentiating" ).prop('required');
    }        
  });

  $( "#factor_differentiating" ).change(function(e){
    $( "#content_factor_differentiating_other" ).hide();
    $( "#factor_differentiating_other" ).removeAttr('required');

    if ($(this).val() == 'Otro') {
      $( "#factor_differentiating_other" ).val('');
      $( "#factor_differentiating_other" ).prop('required', true);
      $( "#content_factor_differentiating_other" ).show();
    }
  });

  $( "#occupational_category" ).change(function(){
    $( "#occupational_category_level" ).text($(this).find('option:selected').data('level'));
  });

  $( '#company_id' ).change(function(){
    window.location = "<?php echo site_url('admin/job_layouts/create'); ?>?company_id=" + $(this).val();
  });

  $( "#factor_differentiating" ).change();
});
</script>
