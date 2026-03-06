<div id="section-hiring">
  <div class="step-title">
    Contratación
  </div>
  <div class="step-inputs">
    
    <div class="input-group <?php echo (form_error('modality_contracting'))?'has-error':'';?>">
      <label class="input-group-addon">Modalidad de contratación <span>*</span></label>
      
      <select id="modality_contracting" class="form-control" name="modality_contracting">
        <?php $modality_contracting = set_value('modality_contracting') ? set_value('modality_contracting') : @$request->modality_contracting; ?>
        <option value="">Seleccione</option>
        <option value="outsourcing" <?php echo $modality_contracting == 'outsourcing' ? 'selected="selected"' : ''; ?>>Tercerización</option>
        <option value="intermediation" <?php echo $modality_contracting == 'intermediation' ? 'selected="selected"' : ''; ?>>Intermediación</option>
        <option value="direct_form_client" <?php echo $modality_contracting == 'direct_form_client' ? 'selected="selected"' : ''; ?>>Planilla directa del cliente</option>
        <option value="others" <?php echo $modality_contracting == 'others' ? 'selected="selected"' : ''; ?>>Otros</option>  
      </select>

      <?php echo form_error('modality_contracting'); ?>
    </div>
    <div class="input-group <?php echo (form_error('contract_time_qty'))?'has-error':'';?>">
      <label class="input-group-addon">Tiempo de contratación <span>*</span></label>
      <table>
        <tr>      
          <td width="20%">
            <input id="contract_time_qty" class="form-control" name="contract_time_qty" value="<?php echo set_value('contract_time_qty') ? set_value('contract_time_qty') : @$request->contract_time_qty;?>" maxlength="2" style="text-align: center;" placeholder="Ej: 1 Año">
          </td>
          <td width="3%"></td>
          <td>
            <select name="contract_time_duration" class="form-control" id="contract_time_duration">
              <?php $contract_time_duration = set_value('contract_time_duration') ? set_value('contract_time_duration') : @$request->contract_time_duration; ?>  
              <option value="día(s)" <?php echo $contract_time_duration == 'día(s)' ? 'selected="selected"' : ''; ?>>Día(s)</option>
              <option value="mes(es)" <?php echo $contract_time_duration == 'mes(es)' ? 'selected="selected"' : ''; ?>>Mes(es)</option>
              <option value="año(s)" <?php echo $contract_time_duration == 'año(s)' ? 'selected="selected"' : ''; ?>>Año(s)</option> 
            </select>
        </td>
        </tr>
      </table>
      <?php 
        echo form_error('contract_time_qty');
        echo form_error('contract_time_duration');
      ?>
    </div>
    <div class="input-group <?php echo (form_error('reason_request'))?'has-error':'';?>">
      <label class="input-group-addon">Motivo de requerimiento <span>*</span></label>
      <select id="reason_request" name="reason_request" class="form-control">
        <?php $reason_request = set_value('reason_request') ? set_value('reason_request') : @$request->reason_request; ?>
        <option value="">Seleccione</option>
        <?php foreach ($type_reasons as $row_reason): ?>
          <option value="<?php echo $row_reason->id; ?>" <?php echo $reason_request == $row_reason->id ? 'selected="selected"' : ''; ?>>
            <?php e($row_reason->name); ?>
          </option>
        <?php endforeach; ?>  
      </select>
      <?php echo form_error('reason_request'); ?>
    </div>

  <?php
    $is_replaced_employee = $reason_request == 'replacement' || $reason_request == 'license' || $reason_request == 'vacations';
    
    $employee_replace_dni =  @$request->employee_replaced_dni;
    $employee_replace_name = @$request->employee_replaced_name;      
  ?>
  
  <div id="content-replace-employee" class="input-group <?php echo (form_error('dni_employee_replace'))?'has-error':'';?>" <?php echo !$is_replaced_employee ? 'style="display:none;"' : ''; ?>>
    <label class="input-group-addon">Trabajador a reemplazar <span>*</span></label>

      <?php if ($company->country_id == 56): ?>
        <table id="table-replace-employee" width="100%">
          <tr id="row-search-replace-employee" <?php echo !empty($employee_replace_dni) ? 'style="display:none;"' : ''; ?>>
            <td class="2">
              <input id="search-replace-employee" name="search_employee_replace" type="text" class="form-control" value="" placeholder="Buscar por DNI, nombre y apellido">
            </td>
            <td>
              <button id="btn-search-replace-employee" type="button" class="btn btn-xs">Buscar</button>
            </td>
          </tr>
      
          <tr id="row-selector-replace-employee" <?php echo empty($employee_replace_dni) ? 'style="display:none;"' : ''; ?>>
            <td colspan="2" width="80%">
              <select id="selector-replace-employee" name="replace_employee" class="form-control" style="width: 100%;">
                <?php if ($employee_replace_dni): ?>
                  <?php $employee_replace_value = $employee_replace_dni . ' - ' . $employee_replace_name; ?>
                  <option value="<?php echo $employee_replace_value; ?>">
                    <?php echo $employee_replace_value; ?>
                  </option>
                <?php endif; ?>
              </select>  
            </td>
            <td>
              <button id="remove-replace-employee" type="button" class="btn btn-xs"  title="Buscar por otro filtro">
                <i class="glyphicon glyphicon-filter"></i>
              </button>
            </td>
          </tr>
        </table>
      <?php else: ?>
            <input type="text" name="replace_employee" class="form-control" placeholder="Trabajador a reemplazar">
      <?php endif; ?>
    
    <?php echo form_error('replace_employee'); ?>
  </div>

    <div class="input-group <?php echo (form_error('minimum_salary') || form_error('maximum_salary')) ? 'has-error':'';?>">
      <label class="input-group-addon">Rango de remuneración <span></span></label>
      <table id="table-range-salary" width="100%">
        <tr>
          <td>
            <input name="minimum_salary" 
                   type="text" 
                   class="form-control" 
                   id="minimum_salary" 
                   value="<?php echo set_value('minimun_salary') ? set_value('minimun_salary') : @$request->minimum_salary; ?>" 
                   placeholder="Mínimo"
                   readonly>
          </td>
          <td class="separator">a</td>
          <td>
            <input name="maximum_salary" 
                   type="text" 
                   class="form-control" 
                   id="maximum_salary" 
                   value="<?php echo set_value('maximum_salary') ? set_value('maximum_salary') : @$request->maximum_salary; ?>" 
                   placeholder="Máximo"
                   readonly>
          </td>
        </tr>
      </table>
      <?php echo form_error('salary_range'); ?>
    </div>                   
    <div class="input-group <?php echo (form_error('monthly_gross_salary'))?'has-error':'';?>">
      <label class="input-group-addon">Remuneración bruta mensual <span>*</span></label>
      <input name="monthly_gross_salary" type="text" class="form-control" id="monthly_gross_salary" value="<?php echo set_value('monthly_gross_salary') ? set_value('monthly_gross_salary') : @$request->monthly_gross_salary; ?>" style="width: 40%;">
      <?php echo form_error('monthly_gross_salary'); ?>
    </div>
    
    <div class="input-group <?php echo (form_error('job_mode'))?'has-error':'';?>">
      <label class="input-group-addon">Tipo de jornada laboral <span>*</span></label>
      <select name="job_mode" class="form-control">
        <option value="">Seleccione</option>
        <?php $job_mode = set_value('job_mode') ? set_value('job_mode') : @$request->job_mode; ?>
        <option value="full_time" <?php echo ($job_mode == 'full_time')?'selected="selected"':'';?>>Full-Time</option>
        <option value="part_time" <?php echo ($job_mode =='part_time')?'selected="selected"':'';?>>Part-Time</option>
        <option value="per_hours" <?php echo ($job_mode =='per_hours')?'selected="selected"':'';?>>Por Horas</option>
        <option value="weekends" <?php echo ($job_mode =='weekends')?'selected="selected"':'';?>>Fines de Semana</option>
        <option value="telecommuting" <?php echo ($job_mode =='telecommuting')?'selected="selected"':'';?>>Teletrabajo</option>
      </select>
      <?php echo form_error('job_mode'); ?>
    </div>

    <div class="input-group <?php echo (form_error('workplace'))?'has-error':'';?>">
      <label class="input-group-addon">Lugar de trabajo <span>*</span></label>
      <select name="workplace" id="workplace" class="form-control">
        <?php $workplace = set_value('workplace') ? set_value('workplace') : @$request->workplace; ?>
        <option value="">Seleccione</option>
        <option value="Planta" <?php echo $workplace == 'Planta' ? 'selected="selected"' : ''; ?>>
          Planta
        </option>
        <option value="Oficina" <?php echo $workplace == 'Oficina' ? 'selected="selected"' : ''; ?>>
          Oficina
        </option>
        <option value="Oficina y Planta" <?php echo $workplace == 'Oficina y Planta' ? 'selected="selected"' : ''; ?>>
          Oficina y Planta
        </option>
        <option value="Otros" <?php echo $workplace == 'Otros' ? 'selected="selected"' : ''; ?>>
          Otros
        </option>
      </select>
      <?php echo form_error('workplace'); ?>
    </div>

    <div>
      <label class="input-group-addon" style="vertical-align: top;">Horario laboral <span>*</span></label>
      <div style="padding: 20px;">
        <div class="field_working_hours"></div>
        <div style="background: #eee; color: #555;padding: 10px;">
          Por favor ingrese el horario laboral, puede asignarlo mediante la opción 'Agregar horario' o puede detallarlo en la caja de texto, ambas opciones son válidas. 
        </div>
        <div class="<?php echo (form_error('working_hours'))?'has-error':'';?>">
          <div id="content-working-hours">
            <table id="tbl-working-hours" style="margin-bottom: 5px;">
              <?php $working_hours = !empty(@$working_hours) ? $working_hours : array(); ?>

              <tbody>
              <?php foreach ($working_hours as $index => $row): ?>
                <tr class="row-working-hours">
                  <td>
                    <select class="form-control" name="working_hours[<?php echo $index; ?>][start_day]">
                      <option value="">Seleccione</option>
                      <option value="Lunes" <?php echo $row->start_day == 'Lunes' ? 'selected="selected"' : ''; ?>>
                        Lunes
                      </option>
                      <option value="Martes" <?php echo $row->start_day == 'Martes' ? 'selected="selected"' : ''; ?>>
                        Martes
                      </option>
                      <option value="Miércoles" <?php echo $row->start_day == 'Miércoles' ? 'selected="selected"' : ''; ?>>
                        Miércoles
                      </option>
                      <option value="Jueves" <?php echo $row->start_day == 'Jueves' ? 'selected="selected"' : ''; ?>>
                        Jueves
                      </option>
                      <option value="Viernes" <?php echo $row->start_day == 'Viernes' ? 'selected="selected"' : ''; ?>>
                        Viernes
                      </option>
                      <option value="Sábado" <?php echo $row->start_day == 'Sábado' ? 'selected="selected"' : ''; ?>>
                        Sábado
                      </option>
                      <option value="Domingo" <?php echo $row->start_day == 'Domingo' ? 'selected="selected"' : ''; ?>>
                        Domingo
                      </option>
                    </select>
                  </td>
                  <td width="5%" style="text-align: center;">a</td>
                  <td>
                    <select class="form-control" name="working_hours[<?php echo $index; ?>][end_day]">
                      <option value="">Seleccione</option>
                      <option value="Lunes" <?php echo $row->end_day == 'Lunes' ? 'selected="selected"' : ''; ?>>
                        Lunes
                      </option>
                      <option value="Martes" <?php echo $row->end_day == 'Martes' ? 'selected="selected"' : ''; ?>>
                        Martes
                      </option>
                      <option value="Miércoles" <?php echo $row->end_day == 'Miércoles' ? 'selected="selected"' : ''; ?>>
                        Miércoles
                      </option>
                      <option value="Jueves" <?php echo $row->end_day == 'Jueves' ? 'selected="selected"' : ''; ?>>
                        Jueves
                      </option>
                      <option value="Viernes" <?php echo $row->end_day == 'Viernes' ? 'selected="selected"' : ''; ?>>
                        Viernes
                      </option>
                      <option value="Sábado" <?php echo $row->end_day == 'Sábado' ? 'selected="selected"' : ''; ?>>
                        Sábado
                      </option>
                      <option value="Domingo" <?php echo $row->end_day == 'Domingo' ? 'selected="selected"' : ''; ?>>
                        Domingo
                      </option>
                    </select>
                  </td>
                  <td width="5%"></td>
                  <td width="10%">
                    <input style="text-align: center;" type="text" name="working_hours[<?php echo $index; ?>][start_time]" value="<?php echo date('h:i', strtotime($row->start_time)); ?>" placeholder="12:00" data-timepicker class="form-control">
                  </td>
                  <td width="8%">
                    <select id="" class="form-control" name="working_hours[<?php echo $index; ?>][start_time_abr]">
                      <option value="am" <?php echo date('a', strtotime($row->start_time)) == 'am' ? 'selected="selected"' : ''; ?>>
                        AM
                      </option>
                      <option value="pm" <?php echo date('a', strtotime($row->start_time)) == 'pm' ? 'selected="selected"' : ''; ?>>
                        PM
                      </option>
                    </select>
                  </td>
                  <td width="5%" style="text-align: center;">a</td>
                  <td width="10%">
                    <input style="text-align: center;" type="text" name="working_hours[<?php echo $index; ?>][end_time]" value="<?php echo date('h:i', strtotime($row->end_time)); ?>" placeholder="12:00" data-timepicker class="form-control">
                  </td>
                  <td width="8%">
                    <select id="" class="form-control" name="working_hours[<?php echo $index; ?>][end_time_abr]">
                      <option value="am" <?php echo date('a', strtotime($row->end_time)) == 'am' ? 'selected="selected"' : ''; ?>>
                        AM
                      </option>
                      <option value="pm" <?php echo date('a', strtotime($row->end_time)) == 'pm' ? 'selected="selected"' : ''; ?>>
                        PM
                      </option>
                    </select>
                  </td>

                  <td width="5%" style="text-align: center;">
                    <a href="#" class="remove-working-hours">
                      <i class="glyphicon glyphicon-remove"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>    
              </tbody>          
            </table>                     
          </div>
          <div style="padding: 5px;text-align: right;">
            <a href="#" id="add-working-hours" style="font-size: 12px;">Agregar horario</a>
          </div>
        </div>

        <div class="<?php echo (form_error('working_hours_manual'))?'has-error':'';?>">
          <label class="input-group-addon" style="padding-left:0;vertical-align: top;">Detallar Horario laboral <span></span></label>
          <div id="content-working-hours-manual">
            <textarea rows="3" class="form-control" name="working_hours_manual" ><?php echo @$request->working_hours; ?></textarea>                  
          </div>
        </div>
      </div>
    </div>
    
    <br />

    <div class="input-group <?php echo (form_error('start_date_work'))?'has-error':'';?>">
        <label class="input-group-addon">Fecha inicio contrato <span></span></label>
        <input id="start_date_work" name="start_date_work" class="form-control" type="date" style="max-width:150px;" value="<?php echo @$request->start_date_work; ?>">
        <?php echo form_error('start_date_work'); ?>
    </div>
    <div class="input-group <?php echo (form_error('end_date_work'))?'has-error':'';?>">
        <label class="input-group-addon">Fecha fin contrato <span></span></label>
        <input id="end_date_work" name="end_date_work" class="form-control" type="date" style="max-width:150px;" value="<?php echo @$request->end_date_work; ?>">
        <?php echo form_error('end_date_work'); ?>
    </div>

    <div class="input-group <?php echo (form_error('location'))?'has-error':'';?>">
      <label class="input-group-addon">Ubicación <span>*</span></label>
      <?php if ($company->ID == '56'): ?>
      <select id="location" name="location" class="form-control" style="width: 100%;">
        <option value="">Seleccione</option>
        <?php foreach ($ubigeos as $row): ?>x
          <?php 
            $ubigeo = $row->ubigeo;
            $ubigeo_val = set_value('location') ? set_value('location') : @$request->location;
          ?>
          <option value="<?php echo $ubigeo; ?>" <?php echo  $ubigeo == $ubigeo_val ? 'selected="selected"' : ''; ?>>
            <?php echo $ubigeo; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php endif; ?>
      <?php if ($company->ID != '56'): ?>
        <input 
              name="location" 
              class="form-control" 
              type="text" 
              value="<?php echo @$request->location; ?>">
      <?php endif; ?>
      <?php echo form_error('location'); ?>
    </div>
   
    <div class="input-group <?php echo (form_error('job_address'))?'has-error':'';?>">
      <label class="input-group-addon">Dirección de trabajo <span>*</span></label>
      <input name="job_address" type="text" class="form-control" id="job_address" value="<?php echo set_value('job_address') ? set_value('job_address') : @$request->job_address; ?>">
      <?php echo form_error('job_address'); ?>
    </div>
    <div class="input-group <?php echo (form_error('salary_delivery_period'))?'has-error':'';?>">
      <label class="input-group-addon">Periodo de entrega de sueldo <span>*</span></label>
      
      <select name="salary_delivery_period" class="form-control" id="salary_delivery_period">
        <option value="">Seleccione</option>
        <?php $salary_delivery_period = set_value('salary_delivery_period') ? set_value('salary_delivery_period') : @$request->salary_delivery_period; ?>
        <option value="weekly" <?php echo $salary_delivery_period == 'weekly' ? 'selected="selected"' : ''; ?>>Semanal</option>
        <option value="biweekly" <?php echo $salary_delivery_period == 'biweekly' ? 'selected="selected"' : ''; ?>>Quincenal</option>
        <option value="monthly" <?php echo $salary_delivery_period == 'monthly' ? 'selected="selected"' : ''; ?>>Mensual</option>
      </select>
      <?php echo form_error('salary_delivery_period'); ?>
    </div>
  </div>
</div>