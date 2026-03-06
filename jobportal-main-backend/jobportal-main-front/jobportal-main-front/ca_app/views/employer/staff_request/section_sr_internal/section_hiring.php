<div id="section-hiring">
  <div class="step-title">
    Contratación
  </div>
  <div class="step-inputs">
    <div class="input-group <?php echo (form_error('contract_time_qty'))?'has-error':'';?>">
      <label class="input-group-addon">Tiempo de contratación <span>*</span></label>
      <table>
        <tr>      
          <td width="20%">
            <input id="contract_time_qty" class="form-control" name="contract_time_qty" value="<?php echo set_value('contract_time_qty') ;?>" maxlength="2" style="text-align: center;" placeholder="Ej: 1 Año">
          </td>
          <td width="3%"></td>
          <td>
            <select name="contract_time_duration" class="form-control" id="contract_time_duration">
              <?php $contract_time_duration = set_value('contract_time_duration') ? set_value('contract_time_duration') : @$hiring->contract_time_duration; ?>  
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
    <div class="input-group <?php echo (form_error('renovable'))?'has-error':'';?>">
      <label class="input-group-addon">Renovable <span>*</span></label>
      <select id="renovable" name="renovable" class="form-control">
        <?php $renovable = set_value('renovable'); ?>
        <option value="">Seleccione</option>
        <option value="yes" <?php echo $renovable == 'yes' ? 'selected="selected"' : ''; ?>>Si</option>
        <option value="no" <?php echo $renovable == 'no' ? 'selected="selected"' : ''; ?>>No</option>
      </select>
      <?php echo form_error('renovable'); ?>
    </div>
    <div class="input-group <?php echo (form_error('reason_request'))?'has-error':'';?>">
      <label class="input-group-addon">Motivo de requerimiento <span>*</span></label>

      <select id="reason_request" name="reason_request" class="form-control">
        <?php $reason_request = set_value('reason_request'); ?>
        <option value="">Seleccione</option>
        <?php foreach ($type_reasons as $row_reason): ?>
          <option value="<?php echo $row_reason->id; ?>" <?php echo $reason_request == $row_reason->id ? 'selected="selected"' : ''; ?>>
            <?php e($row_reason->name); ?>
          </option>
        <?php endforeach; ?>  
      </select>
      <?php echo form_error('reason_request'); ?>
    </div>

    <div id="content-replace-employee" style="display:none;">
      <div class="input-group">
        <label class="input-group-addon">Trabajador a reemplazar <span>*</span></label>
          <?php if ($country->ID == 56): ?>
            <table id="table-replace-employee" width="100%">
              <tr id="row-search-replace-employee">
                <td class="2">
                  <input id="search-replace-employee" name="search_employee_replace" type="text" class="form-control" value="" placeholder="Buscar por DNI, nombre y apellido">
                </td>
                <td>
                  <button id="btn-search-replace-employee" type="button" class="btn btn-xs">Buscar</button>
                </td>
              </tr>

              <tr id="row-selector-replace-employee" style="display:none;">
                <td colspan="2" width="80%">
                  <select id="selector-replace-employee" name="replace_employee" class="form-control" style="width: 100%;">
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
      <div id="content-main-attach-file" class="input-group">
        <label class="input-group-addon">Adjuntar documento<span></span></label>
        <div class="content-attach-files">
          <div class="attach-file-item">
            <a href="#" class="btn-attach-file">Cargar archivo</a>
          </div> 
        </div>
      </div>
    </div>

    <div class="input-group <?php echo (form_error('salary_range'))?'has-error':'';?>">
      <label class="input-group-addon">Rango de remuneración <span></span></label>
      <table id="table-range-salary" width="100%">
        <tr>
          <td>
            <input name="minimum_salary" 
                   type="text" 
                   class="form-control" 
                   id="minimum_salary" 
                   value="<?php echo set_value('minimun_salary'); ?>" 
                   placeholder="Mínimo"
                   readonly>
          </td>
          <td class="separator">a</td>
          <td>
            <input name="maximum_salary" 
                   type="text" 
                   class="form-control" 
                   id="maximum_salary" 
                   value="<?php echo set_value('maximum_salary'); ?>" 
                   placeholder="Máximo"
                   readonly>
          </td>
        </tr>
      </table>
      <?php echo form_error('salary_range'); ?>
    </div>                   
    <div class="input-group <?php echo (form_error('monthly_gross_salary'))?'has-error':'';?>">
      <label class="input-group-addon">Remuneración bruta mensual <span>*</span></label>
      <input name="monthly_gross_salary" type="text" class="form-control" id="monthly_gross_salary" value="<?php echo set_value('monthly_gross_salary'); ?>" style="width: 40%;">
      <?php echo form_error('monthly_gross_salary'); ?>
    </div>

    <div class="input-group <?php echo (form_error('type_remuneration'))?'has-error':'';?>">
      <label class="input-group-addon">Tipo de remuneración <span></span></label>

      <select id="type_remuneration" name="type_remuneration" class="form-control">
        <?php $type_remuneration = set_value('type_remuneration'); ?>
        <option value="">Seleccione</option>
        <option value="1" <?php echo $type_remuneration == '1' ? 'selected="selected"' : ''; ?>>Fija</option>
        <option value="2" <?php echo $type_remuneration == '2' ? 'selected="selected"' : ''; ?>>Variable</option>
        <option value="3" <?php echo $type_remuneration == '3' ? 'selected="selected"' : ''; ?>>Mixta</option>
      </select>
      <?php echo form_error('type_remuneration'); ?>
    </div>

    <div class="input-group <?php echo (form_error('salary_delivery_period'))?'has-error':'';?>">
      <label class="input-group-addon">Periodo de entrega de sueldo <span>*</span></label>
      
      <select name="salary_delivery_period" class="form-control" id="salary_delivery_period">
        <option value="">Seleccione</option>
        <?php $salary_delivery_period = set_value('salary_delivery_period'); ?>
        <option value="weekly" <?php echo $salary_delivery_period == 'weekly' ? 'selected="selected"' : ''; ?>>Semanal</option>
        <option value="biweekly" <?php echo $salary_delivery_period == 'biweekly' ? 'selected="selected"' : ''; ?>>Quincenal</option>
        <option value="monthly" <?php echo $salary_delivery_period == 'monthly' ? 'selected="selected"' : ''; ?>>Mensual</option>
      </select>
      <?php echo form_error('salary_delivery_period'); ?>
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
            <tbody></tbody>
            </table>                     
          </div>
          <div style="padding: 5px;text-align: right;">
            <a href="#" id="add-working-hours" style="font-size: 12px;">Agregar horario</a>
          </div>
        </div>

        <div class="<?php echo (form_error('working_hours_manual'))?'has-error':'';?>">
          <label class="input-group-addon" style="padding-left:0;vertical-align: top;">Detallar Horario laboral <span></span></label>
          <div id="content-working-hours-manual">
            <textarea rows="3" class="form-control" name="working_hours_manual" ></textarea>                  
          </div>
        </div>
      </div>
    </div>
    <br />
    <div class="input-group <?php echo (form_error('location'))?'has-error':'';?>">
      <label class="input-group-addon">Ubicación <span>*</span></label>
      <select id="location" name="location" class="form-control" style="width:100%;">
        <option value="">Seleccione</option>
        <?php foreach ($ubigeos as $row): ?>
          <?php $ubigeo_value = $row->ubigeo;?>
          <option value="<?php echo $ubigeo_value; ?>">
            <?php echo $ubigeo_value; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="input-group <?php echo (form_error('job_address'))?'has-error':'';?>">
      <label class="input-group-addon">Dirección de trabajo <span>*</span></label>
      <input name="job_address" type="text" class="form-control" id="job_address" value="<?php echo set_value('job_address'); ?>">
      <?php echo form_error('job_address'); ?>
    </div>

    <div class="input-group <?php echo (form_error('start_date_work'))?'has-error':'';?>">
        <label class="input-group-addon">Fecha inicio contrato <span></span></label>
        <input id="start_date_work" name="start_date_work" class="form-control" type="date" style="max-width:150px;">
        <?php echo form_error('start_date_work'); ?>
    </div>
    <div class="input-group <?php echo (form_error('end_date_work'))?'has-error':'';?>">
        <label class="input-group-addon">Fecha fin contrato <span></span></label>
        <input id="end_date_work" name="end_date_work" class="form-control" type="date" style="max-width:150px;">
        <?php echo form_error('end_date_work'); ?>
    </div>
    
    <div class="input-group <?php echo (form_error('observations'))?'has-error':'';?>">
        <label class="input-group-addon">Obsevaciones <span></span></label>
        <input id="observations" name="observations" class="form-control" type="text">
        <?php echo form_error('observations'); ?>
    </div>
  </div>
</div>