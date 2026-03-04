<?php
  if (!isset($export_to_pdf))
  {
    $export_to_pdf = false;
  }

  function check_empty($str = '') {
    if (trim((string)$str) == '') {

      return '<span style="font-style: italic;color:#777;">Sin especificar</span>';
    }
    return $str;
  }

  $job_template = null;
  $job_template_benefits = null;

  if (@$job_profile) {
    $job_template = $job_profile;
    $job_template_benefits = $jp_benefits;
  }

  if (@$job_layout) {
    $job_template = $job_layout;
    $job_template_benefits = $jl_benefits;
  }
?>

<div>
  <style>
    body {
      color: #333;
    }

    .col-xs-4 {
      width: 33.33333333%;
      position: relative;
      min-height: 1px;
      padding-right: 15px;
      padding-left: 15px;
      float: left;
    }

    .col-xs-7 {
      width: 58.33333333%;
      position: relative;
      min-height: 1px;
      padding-right: 15px;
      padding-left: 15px;
      float: left;
    }

  .section-title {
    padding: 6px 2px;
    border-bottom: 1px solid #999;
    margin-bottom: 10px;
    font-size: 19px;
    text-transform: uppercase;
  }

  .content-function {
    background: #eee;
    padding: 6px 8px;
    margin-bottom: 8px;
    border-radius: 5px;
  }
  
  .tbl-data tr td {
    vertical-align: top;
    padding: 4px 3px;
  }
  
  .tbl-data tr th {
    padding: 6px;
    background: #777;
    color: #fff;
  }

  .tbl-data tr:nth-child(even) {
    background-color: #f5f5f5;
  }

  .tbl-data tr:nth-child(odd) {
    background-color: #fff;
  }
  
  .tbl-data-2 tr td {
    padding: 6px;
  }

  .text-value-diff {
    text-decoration: line-through;
  }
  </style>
  <!-- Descripcion de la empresa -->
  <div id="section-descriptions-company">
    <div>
      <h3 class="section-title">
        Datos de la solicitud
      </h3>
    </div>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <td width="33%">
            <label>Gerencia</label>
          </td>
          <td>
            <?php echo check_empty(_e($request->management)); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Unidad de negocio</label>
          </td>
          <td>
            <?php echo check_empty($request->business_unit_name); ?>    
          </td>
        </tr>
        <?php if ($request->type_expense != null): ?>
          <tr>
            <td width="33%">
              <label>Tipo de egreso</label>
            </td>
            <td>
              <?php echo check_empty($request->type_expense); ?>    
            </td>
          </tr>
        <?php endif; ?>
        <tr>
          <td width="33%">
            <label>Nombre de la empresa cliente</label>
          </td>
          <td>
            <?php echo check_empty($request->client_company_name); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Consultora</label>
          </td>
          <td>
            <?php echo check_empty($request->consultant_name); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Centro de costo</label>
          </td>
          <td>
            <?php echo check_empty($request->cost_center); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Usuario responsable</label>
          </td>
          <td><?php e($request->recruiter_first_name); ?></td>
        </tr>
      </table>  
    </div>
  </div>
  <!-- End Descripción del puesto -->
  <!-- Descripcion del puesto -->
  <div id="section-job-descriptions">
    <div>
      <h3 class="section-title">
        Descripción del puesto 
    </div>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <td width="33%">
            <label>Fecha solicitud</label>
          </td>
          <td>
            <?php e($request->creation_date); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Campaña</label>
          </td>
          <td>
            <?php e($request->campaign); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Servicio</label>
          </td>
          <td>
            <?php e($request->service); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Fecha de entrega</label>
          </td>
          <td>
            <?php e($request->delivery_date); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>División</label>
          </td>
          <td>
            <?php echo check_empty(_e($request->division)); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Canal</label>
          </td>
          <td>
            <?php e($request->channel); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Nombre del puesto</label>
          </td>
          <td><?php e($request->job_title); ?></td>
        </tr>
        <tr>
          <td width="33%">
            <label>Número de vacantes</label>
          </td>
          <td>
            <?php e($request->vacancies); ?>    
          </td>
        </tr>
        
        <?php if (count($staff_request_department_vacancies) == 0):  ?>
          <tr>
            <td width="33%">
              <label>Departamento</label>
            </td>
            <td>
              <?php e($request->department); ?>    
            </td>
          </tr>
        <?php endif; ?>

        <?php if (count($staff_request_department_vacancies) > 0):  ?>
          <tr>
            <td width="33%">
              <label>Departamentos</label>
            </td>
            <td>
              <table style="width:100%">
                <tr>
                  <th style="background:#fff;color:#333;border-bottom: 1px solid #bbb;">Ubicación</th>
                  <th style="background:#fff;color:#333;border-bottom: 1px solid #bbb;">Vacantes</th>
                </tr>
                <?php foreach ($staff_request_department_vacancies as $row): ?>
                  <tr>
                    <td width="50%">
                      <?php e($row->department); ?>
                    </td>
                    <td> <?php e($row->vacancies); ?></td>
                  </tr>
                <?php endforeach; ?>
              </table>
            </td>
          </tr>
        <?php endif; ?>

          <tr>
            <td width="33%">
              <label>Zona</label>
            </td>
            <td>
              <?php echo check_empty(_e(join(', ', explode(',', $request->zone)))); ?>    
            </td>
          </tr>

          <tr>
            <td width="33%">
              <label>Comentario</label>
            </td>
            <td>
              <?php echo check_empty(_e($request->zone_comments)); ?>    
            </td>
          </tr>
        <?php //endif; ?>

      </table>
    </div>
  </div>
  <!-- End Descripción del puesto -->

  <!-- Start Contratación -->
  <div id="section-hiring">
    <div>
      <h3 class="section-title">
        Contratación
      </h3>
    </div>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <td width="33%">
            <label>Tipo de contrato</label>
          </td>
          <td>
            <?php e($request->type_contract); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Motivo de requerimiento</label>
          </td>
          <td>
            <?php echo check_empty(_e(reason_request_text($request->reason_request))); ?>
          </td>
        </tr>

        <tr>
          <td width="33%"><label>Sexo</label></td>
          <td><?php echo check_empty(_e(gender_text($request->gender))); ?></td>
        </tr>

        <tr>
          <td width="33%">
            <label>Trabajador a reemplazar</label>
          </td>
          <td>
            <?php e($request->employee_replaced_dni . ' - ' .  $request->employee_replaced_name); ?>
          </td>
        </tr>

        <tr>
          <td width="33%">
            <label>Tipo de jornada laboral</label>
          </td>
          <td>
            <?php echo check_empty(_e(job_mode_text($request->job_mode))); ?>
          </td>
        </tr>

        <tr>
          <td width="33%">
            <label>Carnet de sanidad</label>
          </td>
          <td>
            <?php e($request->health_card); ?>
          </td>
        </tr>

        <?php if ($request->start_hour_work != null): ?>
          <tr>
            <td width="33%">
              <label>Horario de trabajo </label>
            </td>
            <td>
              <?php
                  e(date('h:i a', strtotime($request->start_hour_work)) . ' a ' . date('h:i a', strtotime($request->end_hour_work))); 
              ?>
            </td>
          </tr>
        <?php endif; ?>
        
        <?php if ($working_hours): ?>
          <tr>
            <td width="33%">
              <label>Horario de trabajo</label>
            </td>
            <td>
              <table id="tbl-working-hours" width="100%">
                <?php foreach ($working_hours as $row): ?>
                  <tr>
                    <td>
                      <?php e($row->start_day . ' a ' . $row->end_day . ' '); ?>
                      <?php e(date('h:i a', strtotime($row->start_time)) . ' a ' . date('h:i a', strtotime($row->end_time))); ?>    
                    </td>
                  </tr>
                <?php endforeach; ?>
              </table>
            </td>
          </tr>
        <?php endif; ?>

        <?php if ($request->working_hours): ?>
          <tr>
            <td width="33%">
              <label>Horario de trabajo</label>
            </td>
            <td>
              <?php echo nl2br($request->working_hours); ?>
            </td>
          </tr>
        <?php endif; ?>
      </table>
    </div>
  </div>
  <!-- End Contratación -->

  <!-- Start Beneficios adicionales -->
  <div id="section-additional-benefits">
    <h3 class="section-title">
          Estructura salarial
    </h3>
    <div class="row">
      <div class="col-md-12">
        <table class="tbl-show-items">
          <tr>
            <td width="33%">
              <label>Rango de remuneración </label>
            </td>
            <td>
              <?php 
                $range_salary_request = number_format((float)trim((string)$request->minimum_salary), 2, '.', '') . ' a ' . number_format((float)trim((string)$request->maximum_salary), 2, '.', ''); 
                $range_salary_template = number_format((float)trim((string)@$job_template->basic_minimum), 2, '.', '') . ' a ' . number_format((float)trim((string)@$job_template->basic_maximum), 2, '.', ''); 
              ?>
              <?php if ($range_salary_request != $range_salary_template && $job_template): ?>
                <a href="#" style="color:#ad7812;" data-toggle="modal" data-target="#modal-warning-salary-changes">
                  <i class="glyphicon glyphicon-warning-sign"></i>
                  <span class="text-value-diff"><?php echo $range_salary_template; ?></span>
                </a>
                <i class="glyphicon glyphicon-arrow-right"></i>
              <?php endif; ?>

              <?php echo check_empty(!empty($request->minimum_salary) ? $range_salary_request : null); ?>
            </td>
          </tr>
          <tr>
            <td width="33%">
              <label>Remuneración bruta mensual</label>
            </td>
            <td>
              <?php if (number_format((float)trim((string)$request->monthly_gross_salary), 2, '.', '') != number_format((float)trim((string)@$job_template->basic_maximum), 2, '.', '') && $job_template): ?>
                <a href="#" style="color:#ad7812;" data-toggle="modal" data-target="#modal-warning-salary-changes">
                  <i class="glyphicon glyphicon-warning-sign"></i>
                  <span class="text-value-diff"><?php echo number_format((float)trim((string)@$job_template->basic_maximum), 2, '.', ''); ?></span>
                </a>
                <i class="glyphicon glyphicon-arrow-right"></i>
              <?php endif; ?>
              <?php echo check_empty(number_format((float)trim((string)$request->monthly_gross_salary), 2, '.', '')); ?>
            </td>
          </tr>
        
          <?php foreach ($additional_benefits as $row): ?>
            <?php $diff_benefit = get_diff_benefit_value($row, $job_template_benefits); ?>
  
            <?php if (is_numeric($row->detail) && $row->detail && $row->detail <= 0 && !$diff_benefit) {
              continue;
            }
            ?>
            <tr>
              <td>
                <b><i class="glyphicon glyphicon-ok" style="color:green;"></i>&nbsp;&nbsp;<?php e($row->benefit_name); ?></b>
              </td>
            </tr>
            <tr>
              <td style="font-style:italic;">
                <?php if ($diff_benefit && @$job_profile): ?>
                  <a href="#" style="color:#ad7812;" data-toggle="modal" data-target="#modal-warning-salary-changes">
                    <i class="glyphicon glyphicon-warning-sign"></i>
                    <span class="text-value-diff"><?php echo $diff_benefit['maximum']; ?></span>
                  </a>
                  <i class="glyphicon glyphicon-arrow-right"></i>
                <?php endif; ?>
                <?php echo '&nbsp;&nbsp;&nbsp;' . check_empty(number_format((float)trim((string)$row->detail), 2, '.', '')); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
  <!-- End Beneficios adicionales -->

  <!-- Start Section comentarios adicionales -->
  <div id="section-additional-comments">
    <h3 class="section-title">
      Comentarios adicionales del cliente
    </h3>
    <div class="row">
      <div class="col-md-12">
         <?php echo check_empty(_e($request->additional_comments)); ?>
      </div>
    </div>
  </div>
  <!-- End Section comenarios adicionales -->

  <?php if (count($job_functions) > 0): ?>
    <!-- Start Section Funciones del puesto -->
    <div id="section-job-functions">
      <h3 class="section-title">
        Funciones específicas del puesto
      </h3>
      <div class="row">
        <div class="col-md-12">
          <?php foreach ($job_functions as $row_function): ?>
          <div class="content-function"><?php e($row_function->function); ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- End Section Funciones del puesto -->      
  <?php endif; ?>

  <?php if (count($additional_competences) > 0): ?>
    <!-- Start Section competences -->
    <div id="section-competences">
      <h3 class="section-title">
        Competencias
      </h3>
      <div>
        <table class="tbl-data-2" width="100%">
          <?php foreach ($additional_competences as $row): ?>
          <tr>
            <td>
              <b><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;&nbsp;<?php e($row->competence_name); ?></b>
            </td>
          </tr>
          <?php endforeach; ?>

        </table>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php if ($export_to_pdf == false): ?>
<div id="modal-warning-salary-changes" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Valor cambiado</h4>
      </div>
      <div class="modal-body">
        Este valor del perfil laboral ha cambiado y no se tomara en cuenta como referencia.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>