<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>

<style type="text/css">
    .form-control {
      max-width: 550px;
    }

    .formint {
      padding: 15px;
    }

    .sub-title-h3 {
      font-size: 16px;
      padding: 8px 2px;
      text-transform: uppercase;
      border-bottom: 1px solid #888;
      margin: 0px 0 15px 0px;
      display: block;
    }

    .row {
      margin-bottom: 8px;
    }

    #wrapper-skills span {
      background: #ddd;
      padding: 5px;
      margin: 4px 2px;
      display: inline-block;
    }

    #wrapper-belonging-areas span {
      background: #e6f0ff;
      display: inline-block;
      padding: 3px 4px;
      margin: 3px 2px;
      border-radius: 5px;
    }

    #table-responsibilities tr .counter {
      background: #aaa;
      color: #000;
      border-radius: 50%;
      width: 15px;
      height: 15px;
      padding: 2px 7px;
    }

    .link-edit {
      text-decoration: underline;
      display: block;
    }

    .tbl-base-1 th,
    .tbl-base-1 td {
        padding: 4px;
    }

    .tbl-base-2 th,
    .tbl-base-2 td {
      padding: 6px 4px;
    }

    .tbl-style-1 tbody tr:nth-child(even) {
      background-color: #fff;
    }

    .tbl-style-1 tbody tr:nth-child(odd) {
      background-color: #eee;
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
    <h1> Gestionar Layouts de puesto</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo site_url('admin/job_layouts');?>">Layouts de puesto</a></li>
      <li class="active">Mostrar</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- title row -->
    <div class="row">
      <?php if (validation_errors() != false):?>
          <div class="message-container">
            <div class="callout callout-danger">
                <h4>¡Por favor verifica algunos datos del formualario!</h4>
            </div>
          </div>
      <?php endif;?>
    
      <?php if ($this->session->flashdata('added_action') == true): ?>
        <div class="message-container">
          <div class="callout callout-success">
            <h4>El Layout de puesto ha sido creado con éxito.</h4>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('update_action') == true): ?>
        <div class="message-container">
          <div class="callout callout-success">
            <h4>El Layout de puesto ha sido actualizado con éxito.</h4>
          </div>
        </div>
      <?php endif; ?>

      <div class="col-md-8"> 
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" style="display: block;float:none;">
                Mostrar layout de puesto
                <span class="pull-right" style="padding-right: 10px;font-size:15px;">
                    <div><?php echo $job_layout->version; ?></div>
                    <div>Fecha de actualización: <?php  echo date_formats($job_layout->last_update, 'd/m/Y'); ?></div>
                    <a style="margin-top:8px;" class="link-edit" href="<?php echo site_url('admin/job_layouts/edit/') . $job_layout->id; ?>">
                      Editar
                    </a>
                    <a style="margin-top:8px;" class="link-edit" href="#" data-toggle="modal" data-target="#modal-list-permissions">
                      Ver permisos clientes
                    </a>
                    <a style="margin-top:8px;" 
                       class="link-edit"
                       data-toggle="modal" 
                       data-target="#modal-export"
                       href="#">
                      Exportar
                    </a>
                </span>
                </h3>
            </div>
          
            <div class="formint">
              <h3 class="sub-title-h3">Descripción del puesto</h3>
              <div class="row">
                <div class="col-xs-4"><label>ID perfil</label></div>
                <div class="col-xs-7"><?php echo $job_layout->id; ?></div>
              </div>
              
              <div class="row">
                <div class="col-xs-4"><label>Código</label></div>
                <div class="col-xs-7"><?php echo $job_layout->code ? $job_layout->code : '-'; ?></div>
              </div>

              <div class="row">
                <div class="col-xs-4"><label>Código integración</label></div>
                <div class="col-xs-7"><?php echo $job_layout->code_integration ? $job_layout->code_integration : '-'; ?></div>
              </div>
            
              <?php if ($country->iso_3166_1_alpha2 == 'PE'): ?>
                <div class="row">
                  <div class="col-xs-4"><label>Código SUNAT</label></div>
                  <div class="col-xs-7"><?php echo $sunat_code ? $sunat_code->code . ' - ' . $sunat_code->description : '-'; ?></div>
                </div>
              <?php endif; ?>
            
              <div class="row">
                <div class="col-xs-4"><label>Nombre del cargo</label></div>
                <div class="col-xs-7"><?php echo $job_layout->job_title; ?></div>
              </div>

              <div class="row">
                <div class="col-xs-4"><label>Grupo ocupacional</label></div>
                <div class="col-xs-7">
                  <?php $charge = $this->Job_charge->get_job_charge_by_id($job_layout->job_charge_id); ?>
                  <?php echo $charge ? $charge->charge_name : '-'; ?>
                </div>    
              </div>

              <div class="row">
                  <div class="col-xs-4"><label>Criterio de riesgo</label></div>
                  <div class="col-xs-7">
                    <?php echo !empty($job_layout->risk_criteria) ? $job_layout->risk_criteria : '-'; ?>
                  </div>    
              </div>

              <h3 class="sub-title-h3">Educación Deseable</h3>

              <div class="row">
                <div class="col-xs-4"><label>Grado de estudio</label></div>
                <div class="col-xs-7">
                  <?php
                    $study_grade_req = $this->Qualification->get_record_by_id($job_layout->study_grade_req);
                    echo $study_grade_req['text'];
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-4"><label>Más detalle</label></div>
                <div class="col-xs-7"><?php echo $job_layout->education_req_detail; ?></div>
              </div>
              
              <h3 class="sub-title-h3">Educación Mínima</h3>

              <div class="row">
                <div class="col-xs-4"><label>Grado de estudio</label></div>
                <div class="col-xs-7">
                <?php
                  $study_grade_min = $this->Qualification->get_record_by_id($job_layout->study_grade_min);
                  echo $study_grade_min['text'];
                ?>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-4"><label>Más detalle</label></div>
                <div class="col-xs-7"><?php echo $job_layout->education_min_detail; ?></div>
              </div>

              <h3 class="sub-title-h3">Formación</h3>
              <div class="row">
                <div class="col-xs-7"><?php echo $job_layout->education;?></div>
              </div>

              <h3 class="sub-title-h3">Experiencia</h3>

              <div class="row">
                <div class="col-xs-4"><label>Año(s) de experiencia</label></div>
                <div class="col-xs-7">
                  <?php
                    echo ($this->Work_experience->find(['code' => $job_layout->experience]))->name;
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-4"><label>Más detalle</label></div>
                <div class="col-xs-7"><?php echo $job_layout->experience_detail; ?></div>
              </div>

              <h3 class="sub-title-h3">Habilidades</h3>

              <div class="row" style="margin-bottom: 10px;">
                <div class="col-xs-9">
                  <div id="wrapper-skills">  
                    <?php foreach ($skills as $row_skill): ?>
                      <span><?php echo $row_skill->skill_name; ?></span>
                    <?php endforeach; ?>
                    <?php foreach ($go_skills as $row_skill): ?>
                      <span><?php echo $row_skill->skill_name; ?></span>
                    <?php endforeach; ?>
                  </div>
                </div>    
              </div>

              <h3 class="sub-title-h3">Responsabilidades</h3>

              <div class="row"> 
                <div class="col-md-12" >           
                  <table id="table-responsibilities" class="tbl-base-2 tbl-style-1" width="100%">
                    <tbody>
                      <?php $responsibilities = set_value('responsibilities') ? set_value('responsibilities') : $responsibilities; ?>
                      <?php foreach ($responsibilities as $index => $responsibility): ?>
                      <?php $responsibility = is_object($responsibility) ? $responsibility->responsibility : $responsibility; ?>
                      <tr>
                          <td width="5%"><span class="counter"><?php echo ($index + 1); ?></span></td>
                          <td width="80%"><?php echo $responsibility; ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <br />
              <div class="row">
                  <div class="col-md-12">
                      <h4 class="sub-title-h3">
                          Estructura Salarial
                      </h4>
                  </div>
              </div>

              <div class="row">
                  <div class="col-xs-4">
                      <label>Básico</label>
                  </div>
                  <div class="col-xs-7"><?php echo ($country->currency_code ?? '') . ' ' . $job_layout->basic_minimum . ' a ' .  $job_layout->basic_maximum; ?></div>
              </div>

              <div class="row">
                  <div class="col-xs-4">
                      <label>Estereotipo</label>
                  </div>
                  <div class="col-xs-7"><?php echo $job_layout->stereotype; ?></div>
              </div>

              <div class="row">
                  <div class="col-xs-4">
                      <label>Factor diferenciador</label>
                  </div>
                  <div class="col-xs-7"><?php echo $job_layout->factor_differentiating; ?></div>
              </div>

              <?php if ($job_layout->factor_differentiating == 'Otro'): ?>
                  <div class="row">
                      <div class="col-xs-4">
                          <label>Factor diferenciador Otro</label>
                      </div>
                      <div class="col-xs-7"><?php echo $job_layout->factor_differentiating_other; ?></div>
                  </div>
              <?php endif; ?>

              <?php 
                $occupational_category = $this->Occupational_category->find($job_layout->occupational_category_id);
              ?>
              <div class="row">
                  <div class="col-xs-4">
                      <label>Categoría ocupacional</label>
                  </div>
                  <div class="col-xs-7"><?php echo $occupational_category ? $occupational_category->name : '-'; ?></div>
              </div>
              
              <div class="row">
                <div class="col-xs-4">
                    <label>Nivel Categoría ocupacional</label>
                </div>
                <div class="col-xs-7"><?php echo $occupational_category ? $occupational_category->level : '-'; ?></div>
              </div>

              <?php if (isset($disability_options)): ?>
                <?php foreach ($disability_options as $row_theme): ?>
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="sub-title-h3">
                        <?php echo 'PERMITIR ' . $row_theme->name; ?>
                      </h3>

                      <?php foreach ($row_theme->subthemes as $row_subtheme): ?>
                        <div class="row">
                          <div class="col-xs-4">
                              <label><?php echo $row_subtheme->name; ?></label>
                          </div>
                          <div class="col-xs-7">
                          <?php if ($row_subtheme->jl_disability_allow !== null): ?>
                              <?php echo $row_subtheme->jl_disability_allow == 1 ? 'SI' : 'NO'; ?>
                          <?php else: ?>
                              -
                          <?php endif; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>

              <?php if (isset($results_disability)): ?>
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
                              <table class="table" width="100%">
                                <tr>
                                  <th>
                                    <?php echo $row_section->name; ?>
                                  </th>
                                  <th>
                                    Grado
                                  </th>
                                </tr>
                                <?php foreach ($row_section->items as $key => $row_item): ?>
                                  <tr>
                                    <td width="70%"><?php echo $row_item->name; ?></td>
                                    <td>
                                      <?php 
                                        echo $row_item->grade; 
                                      ?>
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
              <?php endif; ?>

              <?php if (isset($disability_values)): ?>
                <div class="row">
                  <div class="col-md-12">
                      <h3 class="sub-title-h3">
                        Valorización del puesto con discapacidad
                      </h3>

                      <table class="table table-striped" width="100%">
                          <tr>
                            <th>Tipo de discapacidad</th>
                            <th>Valorización</th>
                            <th>Nivel de riesgo</th>
                            <th>Puesto adaptable con discapacidad</th>
                          </tr>

                          <?php
                            $disability_risk = 1;
          
                            $value_color = [
                              1 => 'green',
                              2 => 'yellow',
                              3 => 'red'
                            ];
                            $value_risk_level = [
                              1 => 'BAJO',
                              2 => 'TOLERABLE',
                              3 => 'ALTO'
                            ]; 
                            $value_adaptable_position = [
                              1 => 'ADAPTABLE',
                              2 => 'ADAPTABLE PARCIALMENTE',
                              3 => 'NO ADAPTABLE'
                            ];
                            
                            $value_measures = [
                              1 => 'LAS "CONDICIONES DE TRABAJO" DEL PUESTO SON ADECUADAS PARA INCLUIR PERSONAL CON DISCAPACIDAD',
                              2 => 'REQUIERE REVISIÓN POR LA GERENCIA/JEFATURA DEL PUESTO PARA DESPLEGAR RECURSOS ADICIONALES ANTES DE ASIGNAR PERSONAL CON DISCAPACIDAD',
                              3 => 'LAS "CONDICIONES DE TRABAJO" DEL PUESTO NO ES APTO PARA PERSONAS CON DISCAPACIDAD'
                            ];
                          ?>
                          
                          <?php foreach ($disability_values as $value): ?>

                            <?php 
                              $value_status = 0;

                              if ($value['value'] >= 15 && $value['value'] <= 17) {
                                $value_status = 1;
                                $disability_risk = 0;
                              }
                              
                              if ($value['value'] >= 18 && $value['value'] <= 20) {
                                $value_status = 2;
                                $disability_risk = 0;
                              }

                              if ($value['value'] >= 21 && $value['value'] <= 45) {
                                $value_status = 3;
                              }
                              
                              $disability_value_info = isset($value_measures[$value_status]) ? $value_measures[$value_status] : ''; 
                            ?>

                            <tr>
                              <td align="center">
                                <?php echo $value['theme'] . ' <br/>' . '(' . $value['subtheme'] . ')'; ?>
                              </td>
                              <td align="center" style="color: #000; <?php echo isset($value_color[$value_status]) ? ' background: ' . $value_color[$value_status] . ';' : ''; ?>">
                                <?php echo $value['value'] > 0 ? '<span class="disabilily-value-info" href="#" style="cursor:pointer;color:#000;text-decoration:underline;text-decoration-style: dotted;" data-tippy-content="' . htmlentities($disability_value_info). '">' . $value['value'] . '</span>' : '-'; ?>
                              </td>
                              <td align="center">
                                <?php echo isset($value_risk_level[$value_status]) ? $value_risk_level[$value_status] : '-'; ?>
                              </td align="center">
                              <td align="center">
                                <?php echo isset($value_adaptable_position[$value_status]) ? $value_adaptable_position[$value_status] : '-'; ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                      </table>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (isset($disability_eligibles)): ?>
                <div class="row">
                  <div class="col-md-12">
                    <h3 class="sub-title-h3">
                        Discapacidades aptas
                    </h3>
              
                    <?php if ($disability_risk == 1): ?>
                        Las condiciones del trabajo del puesto no es apto para personas con discapacidad
                    <?php endif; ?>

                    <?php if ($disability_risk == 0): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Discapacidades
                                    </th>
                                    <th>Recursos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($disability_eligibles as $row): ?>
                                    <tr>
                                        <td><?php echo $row->disability; ?></td>
                                        <td><?php echo $row->resources; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>

              <div class="row">
                <div class="col-md-12">
                  <h3 class="sub-title-h3">
                      Valorización del puesto
                  </h3>
                  
                  <?php foreach ($factor_valuations as $row_factor): ?>
                    
                    <div class="col-md-12"style="padding: 5px;">
                      <h5 style="padding: 5px;background:#f9f9f9;"><b><?php echo $row_factor['name']; ?></b></h5>

                      <table class="table" width="100%" style="margin: 10px;">
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
                          <?php if ($row_factor['automatic'] == 0): ?>
                            <td width="40%">
                              <?php echo $row_factor['value_factor_level'] ? $row_factor['value_factor_level'] : '-'; ?> 
                            </td>
                            <td width="40%"><?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?></td>
                          <?php else: ?>
                            <td width="40%" colspan="2"><?php echo $row_factor['value_factor_name'] ? $row_factor['value_factor_name'] : '-'; ?></td>
                          <?php endif; ?>
                          
                          <td width="10%"><?php echo $row_factor['value_factor_grade'] ? $row_factor['value_factor_grade'] : '-'; ?></td>
                          <td width="10%"><?php echo $row_factor['value_factor_score'] ? $row_factor['value_factor_score'] : '-'; ?></td>
                        </tr>
                      </table>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <h3 class="sub-title-h3">
                    Total de Valorización
                  </h3>
                  <div><h4 style="font-size: 16px;">Puntaje total: <?php echo $factor_total_score; ?></h4></div>
                </div>
              </div>
           </div>
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

<div id="modal-export" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <?php echo form_open('admin/job_layouts/export', ['method' => 'get', 'target' => '_blank']); ?>
      <input type="hidden" name="id"  value="<?php echo $job_layout->id; ?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Exportar Detalle Layout de puesto</h4>
        </div>
        <div class="modal-body">
          <div>
          <label>Formato <span></span></label>
            <select id="export-format" 
                    name="format" 
                    class="form-control" 
                    style="width: 100%;"
                    required="true">
        
                <option value="excel">
                  Excel
                </option>
                <option value="pdf">
                  Pdf
                </option>
            </select>
          </div>
          <br>
          <div>
            <label>Secciones</label>
          </div>
          <div>
            <input type="checkbox" name='disability' value="1" checked>
            <label for="">Discapacidad</label>
              </div>
          <div>
            <input type="checkbox" name='valorization' value="1" checked>
            <label for="">Valorización</label>
          </div>
          <div>
            <input type="checkbox" name='salary_structure' value="1" checked>
            <label for="">Estructura salarial</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Exportar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    <?php echo form_close(); ?>

  </div>
</div>

<?php $this->load->view('admin/job_layouts/common/modal_job_layout_permissions'); ?>
<?php $this->load->view('admin/job_layouts/common/modal_job_layout_permissions_clients'); ?>

<?php $this->load->view('admin/common/footer'); ?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.2.11/js/dataTables.checkboxes.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script type="text/javascript">
  $(function(){
    tippy('.disabilily-value-info', {trigger: 'click'});
  });
</script>
</body>
</html>

