<?php 
  if (!isset($export_to_pdf)) 
  {
    $export_to_pdf = false;
  }

  function check_empty($str = '')
  {
    $str = (string)$str;
    if (trim($str) == '') {

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
      background: #fff;
      color: #555;
      border-bottom: 1px solid #ccc;
    }

    .tbl-data tr:nth-child(even) {
      background-color: #fff;
    }

    .tbl-data tr:nth-child(odd) {
      background-color: #f5f5f5;
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
        Descripción de la empresa
      </h3>
    </div>
    <div>
      <table class="tbl-data" width="100%">
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
            <label>Área</label>
          </td>
          <td>
            <?php echo check_empty($request->wf_area_name); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>EECC Código</label>
          </td>
          <td>
            <?php echo check_empty($request->eecc_code); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>EECC Descripción</label>
          </td>
          <td>
            <?php echo check_empty($request->eecc_description); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Usuario responsable</label>
          </td>
          <td><?php echo $request->recruiter_first_name; ?></td>
        </tr>
        <tr>
          <td width="33%">
            <label>Modelo Contrato Código</label>
          </td>
          <td><?php echo check_empty($request->contract_type_model_code); ?></td>
        </tr>
        <tr>
          <td width="33%">
            <label>Centro de costo cliente</label>
          </td>
          <td><?php echo check_empty($request->cost_center_client); ?></td>
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
            <label>Nombre del puesto</label>
          </td>
          <td><?php echo $request->job_title; ?></td>
        </tr>
        <tr>
          <td width="33%">
            <label>Número de vacantes</label>
          </td>
          <td>
            <?php echo $request->vacancies; ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Grupo ocupacional</label>
          </td>
          <td>
            <?php $job_charge = $this->Job_charge->get_job_charge_by_id($request->charge_ID); ?>
            <?php echo check_empty(@$job_charge->charge_name); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Área / Departamento</label>
          </td>
          <td>
            <?php $industy = $this->Industry->get_industries_by_id($request->industry_ID); ?>
            <?php echo check_empty(@$industy->industry_name); ?>   
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>N° de personas que reportan </label>
          </td>
          <td>
            <?php echo check_empty($request->n_people_reporting); ?>  
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Nombre del jefe inmediato </label>
          </td>
          <td>
            <?php echo check_empty($request->name_immediate_boss); ?>  
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Cargo del jefe inmedito</label>
          </td>
          <td>
            <?php echo check_empty($request->charge_immediate_boss); ?>  
          </td>
        </tr>
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
            <label>Modalidad de contratación</label>
          </td>
          <td>
            <?php echo check_empty(modality_contracting_text($request->modality_contracting)); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Fecha inicio contrato</label>
          </td>
          <td>
            <?php echo check_empty(job_mode_text($request->start_date_work)); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Fecha fin contrato</label>
          </td>
          <td>
            <?php echo check_empty(job_mode_text($request->end_date_work)); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Tiempo de contratación</label>
          </td>
          <td>
            <?php echo check_empty($request->contract_time_qty . ' ' . $request->contract_time_duration); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Motivo de requerimiento</label>
          </td>
          <td>
            <?php echo check_empty(reason_request_text($request->reason_request)); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Trabajador a reemplazar</label>
          </td>
          <td>
            <?php echo $request->employee_replaced_dni . ' - ' .  $request->employee_replaced_name; ?>
          </td>
        </tr>
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
        <tr>
          <td width="33%">
            <label>Tipo de jornada laboral</label>
          </td>
          <td>
            <?php echo check_empty(job_mode_text($request->job_mode)); ?>
          </td>
        </tr>
        <?php if ($request->start_hour_work != null): ?>
          <tr>
            <td width="33%">
              <label>Horario de trabajo </label>
            </td>
            <td>
              <?php
                  echo date('h:i a', strtotime($request->start_hour_work)) . ' a ' . date('h:i a', strtotime($request->end_hour_work)) ; 
              ?>
            </td>
          </tr>
        <?php endif; ?>
        <?php if ($request->start_hour_lunch != null): ?>
          <tr>
            <td width="33%">
              <label>Horario de almuerzo</label>
            </td>
            <td>
              <?php
                if ($request->start_hour_lunch != null):
                  echo date('h:i a', strtotime($request->start_hour_lunch)) . ' a ' . date('h:i a', strtotime($request->end_hour_lunch)) ;
                else:
                  echo check_empty();
                endif; 
              ?>
            </td>
          </tr>
        <?php endif; ?>
        <tr>
          <td width="33%">
            <label>Lugar de trabajo</label>
          </td>
          <td>
            <?php echo check_empty($request->workplace); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Ubicación</label>
          </td>
          <td>
            <?php echo check_empty($request->location); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Dirección de trabajo </label>
          </td>
          <td>
            <?php echo check_empty($request->job_address); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Periodo de entrega de sueldo</label>
          </td>
          <td>
            <?php echo check_empty(salary_delivery_period_text($request->salary_delivery_period)); ?>
          </td>
        </tr>
          
        <?php if ($request->household_allowance): ?>
          <tr>
            <td width="33%">
              <label>Asignación familiar</label>
            </td>
            <td>
              <?php echo check_empty($request->household_allowance == 'yes' ? 'Sí' : 'No'); ?>
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
                      <?php echo $row->start_day . ' a ' . $row->end_day . ' '; ?>
                      <?php echo date('h:i a', strtotime($row->start_time)) . ' a ' . date('h:i a', strtotime($row->end_time)); ?>    
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
      Beneficios adicionales
    </h3>
    <div class="row">
      <div class="col-md-12">
        <table class="tbl-show-items">
          <?php foreach ($additional_benefits as $row): ?>
            <?php $diff_benefit = get_diff_benefit_value($row, $job_template_benefits); ?>
            <tr>
              <td>
                <b><i class="glyphicon glyphicon-ok" style="color:green;"></i>&nbsp;&nbsp;<?php echo $row->benefit_name; ?></b>
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

  <!-- Inicio Section resources -->
  <?php if ($request_resource): ?>
    <div id="sr-section-resources">
      <h3 class="section-title">
        Recursos
      </h3>

      <div class="row">
        <div class="col-md-12">
          <table class="tbl-data" width="100%">
            <thead>
              <tr>
                <th colspan="2" align="center" style="text-align:center;">EXAM. EMO</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td width="30%"><b>Tipo</td>
                <td><?php echo check_empty($request_resource->emo_type_name ?? 'No aplica'); ?></td>
              </tr>
              <?php if ($request_resource->emo_type_id): ?>
                <tr>
                  <td width="30%"><b>Tipo Egreso</b></td>
                  <td><?php echo check_empty($request_resource->emo_expense_type ?? '-'); ?></td>
                </tr>

                <?php if ($request_resource->emo_type_id == 10): ?>
                  <tr>
                    <td width="30%"><b>Protocolo detalle</td>
                    <td><?php echo check_empty($request_resource->emo_protocol_detail ?? '-'); ?></td>
                  </tr>
                <?php endif; ?>
                
                <tr>
                  <td width="30%"><b>Encargado</b></td>
                  <td><?php echo check_empty($request_resource->emo_staff_charge ?? '-'); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <br>
          <table class="tbl-data" width="100%">
            <thead>
              <tr>
                <th colspan="2" align="center" style="text-align:center;">SCREENING</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td width="30%"><b>Tipo</td>
                <td><?php echo check_empty($request_resource->screening_type_name ?? 'No aplica'); ?></td>
              </tr>
              <?php if ($request_resource->screening_type_id): ?>
                <tr>
                  <td width="30%"><b>Tipo Egreso</b></td>
                  <td><?php echo check_empty($request_resource->screening_expense_type ?? '-'); ?></td>
                </tr>
                <tr>
                  <td width="30%"><b>Realizar en</b></td>
                  <td><?php echo check_empty(($rys_stages[$request_resource->screening_perform_stage] ?? '-') ?? '-'); ?></td>
                </tr>
                <tr>
                  <td width="30%"><b>Encargado</b></td>
                  <td><?php echo check_empty($request_resource->screening_staff_charge ?? '-'); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <br>
          <table class="tbl-data" width="100%">
            <thead>
              <tr>
                <th colspan="2" align="center" style="text-align:center;">EXAM. COVID-19</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td width="30%"><b>Tipo</td>
                <td><?php echo check_empty($request_resource->covid19_type_name ?? 'No aplica'); ?></td>
              </tr>
              <?php if ($request_resource->covid19_type_id): ?>
                <tr>
                  <td width="30%"><b>Tipo Egreso</b></td>
                  <td><?php echo check_empty($request_resource->covid19_expense_type ?? '-'); ?></td>
                </tr>
                <tr>
                  <td width="30%"><b>Realizar en</b></td>
                  <td><?php echo check_empty(($rys_stages[$request_resource->covid19_perform_stage] ?? '-') ?? '-'); ?></td>
                </tr>
                <tr>
                  <td width="30%"><b>Encargado</b></td>
                  <td><?php echo check_empty($request_resource->covid19_staff_charge ?? '-'); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <br>
          <table class="tbl-data" width="100%">
            <thead>
              <tr>
                <th colspan="2" align="center" style="text-align:center;">EXAM. COMPLEMENTARIOS</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td width="30%"><b>Tipo</td>
                <td><?php echo check_empty($request_resource->exam_complementary ?? 'No aplica'); ?></td>
              </tr>
              <?php if ($request_resource->exam_complementary != 'No aplica'): ?>
                <tr>
                  <td width="30%"><b>Encargado</b></td>
                  <td><?php echo check_empty($request_resource->exam_complementary_staff_charge ?? '-'); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <br>
          <table class="tbl-data" width="100%">
            <thead>
              <tr>
                <th colspan="2" align="center" style="text-align:center;">VERIFICACIONES</th>
            </tr>
            </thead>
          </table>
          <table class="tbl-data" width="100%">
            <thead>
              <tr>
                <th width="230">Verificación</th>
                <th>Verificar</th>
                <th>Realizar en</th>
                <th>Encargado</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>Domicilio</td>
                <td><?php echo check_empty($request_resource->verify_home ? 'SI' : 'NO'); ?></td>
                <td><?php echo check_empty($rys_stages[$request_resource->verify_home_perform_stage] ?? '-'); ?></td>
                <td><?php echo check_empty($request_resource->verify_home_staff_charge ? $request_resource->verify_home_staff_charge : '-'); ?></td>
              </tr>
              <tr>
                <td>Crediticia</td>
                <td><?php echo check_empty($request_resource->verify_credit ? 'SI' : 'NO'); ?></td>
                <td><?php echo check_empty($rys_stages[$request_resource->verify_credit_perform_stage] ?? '-'); ?></td>
                <td><?php echo check_empty($request_resource->verify_credit_staff_charge ? $request_resource->verify_credit_staff_charge : '-'); ?></td>
              </tr>
              <tr>
                <td>Laboral</td>
                <td><?php echo check_empty($request_resource->verify_labor ? 'SI' : 'NO'); ?></td>
                <td><?php echo check_empty($rys_stages[$request_resource->verify_labor_perform_stage] ?? '-'); ?></td>
                <td><?php echo check_empty($request_resource->verify_labor_staff_charge ? $request_resource->verify_labor_staff_charge : '-'); ?></td>
              </tr>
              <tr>
                <td>Grados y titulos</td>
                <td><?php echo check_empty($request_resource->verify_degree ? 'SI' : 'NO'); ?></td>
                <td><?php echo check_empty($rys_stages[$request_resource->verify_degree_perform_stage] ?? '-'); ?></td>
                <td><?php echo check_empty($request_resource->verify_degree_staff_charge ? $request_resource->verify_degree_staff_charge : '-'); ?></td>
              </tr>
              <tr>
                <td>Grados y titulos presencial</td>
                <td><?php echo check_empty($request_resource->verify_degree_person ? 'SI' : 'NO'); ?></td>
                <td><?php echo check_empty($rys_stages[$request_resource->verify_degree_person_perform_stage] ?? '-'); ?></td>
                <td><?php echo check_empty($request_resource->verify_degree_person_staff_charge ? $request_resource->verify_degree_person_staff_charge : '-'); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
   <?php endif; ?>
   <!-- Fin Section resources -->

  <!-- Start Requisitos del puesto -->
  <div id="section-job-requirements">
    <h3 class="section-title">
      Requisitos del puesto 
    </h3>
    <?php 
      $level_education = $this->Qualification->get_record_by_id(@$job_template->study_grade_req);
      $specialization_or_diploma = $this->Qualification->get_record_by_id(@$job_template->study_grade_min);
      $level_education = trim($level_education['text']);
      $specialization_or_diploma = trim($specialization_or_diploma['text']);
    ?>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <td width="33%">
            <label>Profesión preferentes </label>
          </td>
          <td>
            <?php echo check_empty($job_template ? $job_template->education_req_detail : ''); ?>
          </td>
        </tr>
        <tr>
          <td width="33%"><label>Nivel de educación</label></td>
          <td><?php echo check_empty($level_education); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Tiempo mínimo de experiencia para el puesto</label></td>
          <td><?php echo check_empty(@$this->Work_experience->find(['code' => $job_template->experience])->name); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Experiencia previa en </label></td>
          <td><?php echo check_empty($job_template ? $job_template->experience_detail : ''); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Sexo</label></td>
          <td>
            <?php 
              $gender_list = [
                'male' => 'Hombre',
                'female' => 'Mujer',
                'both' => 'Ambos'
              ];
            ?>
            <?php echo check_empty(isset($gender_list[$request->gender]) ? $gender_list[$request->gender] : $request->gender); ?>
          </td>
        </tr>
        <tr>
          <td width="33%"><label>Especialización/Diplomado</label></td>
          <td><?php echo check_empty($specialization_or_diploma); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Tiempo mínimo de experiencia laboral</label></td>
          <td><?php echo check_empty($this->Work_experience->find(['code' => $request->labor_experience_time])->name); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Rango de edad</label></td>
          <td><?php echo check_empty(!empty($request->minimum_age) ? $request->minimum_age . ' a ' . $request->maximum_age . ' años': null); ?></td>
        </tr>
      </table>
    </div>
  </div>
  <!-- End Requisitos del puesto -->

  <!-- Start Conocimientos -->
  <div id="section-knowledges">
    <h3 class="section-title">
      Conocimientos
    </h3>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <td width="33%">
            <label>Conocimientos generales</label>
          </td>
          <td>
            <?php echo check_empty($request->general_knowledges); ?>
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Conocimientos específicos</label>
          </td>
          <td>
            <?php echo check_empty($request->specific_knowledges); ?>
          </td>
        </tr>
      </table>
    </div>
  </div>
  <!-- End Conocimientos -->

  <!-- Start Section Informática -->
  <div id="section-computing">
    <h3 class="section-title">
      Informática
    </h3>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <th width="55%">Programas</th>
          <th>Nivel</th>
        </tr>
        <?php foreach ($computing_applicacion as $index => $row): ?>
          <tr>
            <td><?php echo $row->name; ?></td>
            <td><?php echo check_empty(level_text($row->level)); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <!-- End Section Informática -->

  <!-- Start Section Idiomas -->
  <div id="section-languages">
    <h3 class="section-title">
      Idiomas
    </h3>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <th>Idioma</th>
          <th>Lee</th>
          <th>Habla</th>
          <th>Escribe</th>
        </tr>
        <?php foreach ($languages as $index => $row): ?>
          <tr>            
            <td><?php echo $row->language_name; ?></td>
            <td><?php echo check_empty(level_text($row->reading_level));?></td>
            <td><?php echo check_empty(level_text($row->speaking_level));?></td>
            <td><?php echo check_empty(level_text($row->writing_level));?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
  <!-- End Section Idiaomas -->

  <!-- Start Section Funciones del puesto -->
  <div id="section-job-functions">
    <h3 class="section-title">
      Funciones específicas del puesto
    </h3>
    <div class="row">
      <div class="col-md-12">
        <?php foreach ($job_functions as $row_function): ?>
        <div class="content-function"><?php echo $row_function->function; ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <!-- End Section Funciones del puesto -->

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
            <b><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;&nbsp;<?php echo $row->competence_name; ?></b>
          </td>
        </tr>
        <?php endforeach; ?>

        <?php foreach ($fixed_competences as $row): ?>
        <tr>
          <td>
            <b><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;&nbsp;<?php echo $row->competence_name; ?></b>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
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
          <div style="padding: 6px;"><?php echo check_empty(_e($request->additional_comments)); ?></div>
      </div>
    </div>
  </div>
  <!-- End Section comenarios adicionales -->
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