<div class="modal-dialog" style="width:75%;max-width:920px;">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Levantamiento de perfil</h4>
    </div>
    <div class="modal-body">
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

        #tooltip
        {
            text-align: center
        ;    color: #fff;
            background: #444;
            position: absolute;
            z-index: 100;
            padding: 12px 10px;
            border-radius: 5px;
            text-transform: uppercase;
            font-size: 14px;
            box-shadow: 0 0 7px #333;
            font-weight: bold;
        }

        #tooltip:after /* triangle decoration */
        {
          width: 0;
          height: 0;
          border-left: 6px solid transparent;
          border-right: 6px solid transparent;
          border-top: 6px solid #444;
          content: '';
          position: absolute;
          left: 50%;
          bottom: -6px;
          margin-left: -6px;
        }

        #tooltip.top:after
        {
          border-top-color: transparent;
          border-bottom: 10px solid #111;
          top: -20px;
          bottom: auto;
        }

        #tooltip.left:after
        {
          left: 10px;
          margin: 0;
        }

        #tooltip.right:after
        {
          right: 10px;
          left: auto;
          margin: 0;
        }

        .content-step {
          display: none;
        }

        .step-title {
          color: #333;
          font-size: 17px;
          text-transform: uppercase;
          padding: 8px 4px;
          border-bottom: 1px solid #999;

        }

        .step-inputs {
          padding: 20px 0px;
        }

        #prev-step-request,
        #next-step-request,
        #create-request {
          display: none;
        }

        .content-checkbox label {
          display: block;
        }

        .btn-add-item {
          margin:0;
          padding: 4px 7px;
          border:0;
          border-radius: 5px;
          
          background: #bbb;
          color: #444;
          font-size: 12px;
        }

        #table-range-salary,
        #table-range-age {
          width: 80%
        }

        #table-range-salary .separator,
        #table-range-age .separator {
          padding: 0px 20px;
          text-align: center;
        }

        #content-info-eg {
          font-size: 15px;
          padding: 10px 6px;
          background: #eee;
          margin-bottom: 10px;
        }

        #step-counter {
          text-align: right;
        }

        #step-counter ul {
          list-style: none;
          margin: 0;
        }

        #step-counter ul li {
          display: inline-block;
          padding: 2px;
          margin: 0;
          border: 2px solid #fff;
          border-radius: 50%;
        }

        #step-counter ul li a {
          display: block;
          position: relative;
          margin: 0;
          background: #ddd;
          padding-top: 4px;
          border-radius: 50%;
          text-align: center;
          color: #666;
          width: 32px;
          height: 32px;
          border: 2px solid #fff;
          font-weight: bold;
          text-decoration:none;
          cursor: default;
          font-size: 13px;
        }

        #step-counter ul li.complete a {
          background: #444;
          color: #fff;
        }

        #step-counter ul li.active {
          border:2px solid #444;
        }

        #step-counter ul li.active a {
          background: #444;
          color: #fff;
          border: 2px solid #444;
          font-size: 14px;
        }

        .info-error {
          display:block;
          padding: 2px 0px 5px;
          color:#a94442;
          font-size: 13px;
        }

        #table-replace-employee tr td {
          padding: 5px 10px;
        }

        #table-personal-authorizes tr td {
          padding: 6px 5px; 
        }

        #btn-search-replace-employee {
          background: #e0e0e0 !important;
          border: 1px solid #ccc;
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

        #table-additional-benefits tr td {
          padding: 7px;
        }

        #table-additional-benefits tr:nth-child(odd) {
          background-color: #e0e0e0;
        }

        .label-authority {
          font-weight: normal;
        }

        .label-authority span {
          color: red;
        }

        #table-authorities-DR tr td {
          padding-left: 12px;
          font-style: italic;
        }

        #tbl-working-hours tr td {
          padding: 5px 2px;
        }

        #tbl-working-hours {
          border-bottom: 1px solid #ccc;
        }

        #tbl-working-hours tr:nth-child(even) {
          background-color: #e0e0e0;
        }

        #tbl-working-hours tr:nth-child(odd) {
          background-color: #fff;
        }

        .remove-working-hours {
          color: red;
        }

        .content-progress-bar {
          background: #ccc;
          display: inline-block;
          width: 100%;
          height: 10px;
        }

        .total-progress-bar {
          background: #52b2ef;
          display: block;
          height: 10px;
          width: 0;
        }

        .item-remove {
          position: absolute;
          right: 7px;
          top: 5px;
          background: transparent;
          border: 0;
          padding: 0;
          margin: 0;
        }

        .attach-file-item {
          padding: 7px;
          border: 1px solid #666;
          position: relative;
        }
      </style>
      <?php $this->load->view('common/before_head_close'); ?>
      </head>

      <?php $this->load->view('common/after_body_open'); ?>
      <div class="siteWraper">

      <div class="">
        <div class="row">
          <div class="formwraper" style="border:0;">  
            <div>
                <?php echo form_open_multipart('employer/staff_request/profile_survey_internal/save/' . $staff_request->ID, array('id' => 'form-request-save-profile-survey-internal', 'class' => 'formint', 'style' => 'padding: 5px 15px;'));?>
              
                <div class="col-md-12">

                <div class="container-submit" style="padding: 10px 0px;position: sticky;top: 0px;background: #f5f5f5;z-index: 999;border: 1px solid #ccc;">
                    <div class="row" style="margin: 0;">
                        <div class="col-sm-6">
                            <h4><?php e(mb_strtoupper($staff_request->job_title)); ?></h4>
                        </div>
                        <div class="col-sm-6">
                            <div align="right">
                                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>      
                                <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="panel-group" id="accordion">

                  <div class="panel panel-default" style="display: none;">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                          Datos de la solicitud
                        </a>
                      </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse in">
                      <div class="panel-body">
                      
                      <div class="step-inputs">
                        <div class="input-group" >
                            <label class="input-group-addon">Tipo de requerimiento <span>*</span></label>
                            <select id="type-requirement" name="type_requirement" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>
                              <?php $type_requerimient = $staff_request->type_requirement; ?>
                              <option value="EVALUACIÓN" <?php echo $type_requerimient == 'EVALUACIÓN' ? 'selected="selected"' : ''; ?>>EVALUACIÓN</option>
                              <option value="CONTRATACIÓN" <?php echo $type_requerimient == 'CONTRATACIÓN' ? 'selected="selected"' : ''; ?>>CONTRATACIÓN</option>
                              <option value="RECLUTAMIENTO Y SELECCIÓN" <?php echo $type_requerimient == 'RECLUTAMIENTO Y SELECCIÓN' ? 'selected="selected"' : ''; ?>>RECLUTAMIENTO Y SELECCIÓN</option>
                              <option value="INDIGACIÓN SALARIAL" <?php echo $type_requerimient == 'INDIGACIÓN SALARIAL' ? 'selected="selected"' : ''; ?>>INDIGACIÓN SALARIAL</option>
                            </select>
                          </div>

                          <div class="input-group" >
                            <label class="input-group-addon">Consultora <span>*</span></label>
                            <select  id="consultant" name="consultant_name" class="form-control" style="width: 100%;" data-value="<?php echo $staff_request->consultant_name;?>" disabled="true">
                              <option value="">Seleccione</option>    
                            </select>
                          </div>

                          <div class="input-group">
                            <label class="input-group-addon">Unidad de negocio <span>*</span></label>
                            <select id="business_unit" name="business_unit_name" class="form-control" style="width: 100%;" disabled="true">
                              <option value="">Seleccione</option>
                              <?php $business_unit_name = $staff_request->business_unit_name; ?>
                              <option data-uni_neg="DD" value="DESCENTRALIZACIÓN" <?php echo $business_unit_name == 'DESCENTRALIZACIÓN' ? 'selected="selected"' : ''; ?>>DESCENTRALIZACIÓN</option>
                              <option data-uni_neg="IN" value="INTERNO" <?php echo $business_unit_name == 'INTERNO' ? 'selected="selected"' : ''; ?>>INTERNO</option>
                              <option data-uni_neg="MK" value="MARKETING" <?php echo $business_unit_name == 'MARKETING' ? 'selected="selected"' : ''; ?>>MARKETING</option>
                              <option data-uni_neg="SI" value="SERVICIOS INDUSTRIALES" <?php echo $business_unit_name == 'SERVICIOS INDUSTRIALES' ? 'selected="selected"' : ''; ?>>SERVICIOS INDUSTRIALES</option>
                              <option data-uni_neg="FR" value="FRANQUICIADO" <?php echo $business_unit_name == 'FRANQUICIADO' ? 'selected="selected"' : ''; ?>>FRANQUICIADO</option>
                              <option data-uni_neg="HO" value="HOSPITALITY" <?php echo $business_unit_name == 'HOSPITALITY' ? 'selected="selected"' : ''; ?>>HOSPITALITY</option>
                              <option data-uni_neg="DI" value="DISTRIBUIDORA" <?php echo $business_unit_name == 'DISTRIBUIDORA' ? 'selected="selected"' : ''; ?>>DISTRIBUIDORA</option>
                            </select>
                          </div>

                          <div class="input-group" >
                            <label class="input-group-addon">Empresa cliente <span>*</span></label>
                            <select id="client_company" name="client_company_name" class="form-control" style="width: 100%;" data-value="<?php echo $staff_request->client_company_name; ?>" disabled="true">
                              <option value="">Seleccione</option>
                            </select>
                          </div>

                          <div class="input-group" >
                            <label class="input-group-addon">Centro de costo <span>*</span></label>
                            <select id="cost_center" name="cost_center" class="form-control" style="width: 100%;" data-value="<?php echo $staff_request->cost_center; ?>" disabled="true">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default" style="display: none;">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                          Área y MOF
                        </a>
                      </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse in">
                      <div class="panel-body">
                
                        <div id="section-job-description">
                          <div class="step-title">
                            Seleccione Área y MOF
                          </div>
                          <div class="step-inputs">
                            <div class="input-group <?php echo (form_error('internal_area'))?'has-error':'';?>">
                              <label class="input-group-addon">Área perteneciente <span>*</span></label>
                              <select name="belonging_area_id" class="form-control" id="internal_area" style="width: 90%;">
                                <option value="">Seleccione</option>
                                <?php foreach ($internal_areas as $row_area): ?>
                                  <option value="<?php echo $row_area->ID; ?>" <?php echo $staff_request->belonging_area_ID == $row_area->ID ? 'selected="selected"' : ''; ?>>
                                    <?php echo $row_area->area_name; ?>    
                                  </option>
                                <?php endforeach; ?>                  
                              </select>
                              <?php echo form_error('internal_area'); ?>
                            </div>
                            <div class="input-group <?php echo (form_error('select_mof'))?'has-error':'';?>">
                              <label class="input-group-addon">Seleccione MOF <span>*</span></label>
                              <select name="select_mof" type="text" class="form-control" id="select_mof" style="width: 90%;" data-value="<?php echo $staff_request->mof_ID; ?>">
                                <option value="">Seleccione</option>
                                <?php foreach ($mofs as $row_mof): ?>
                                  <option value="<?php echo $row_mof->mof_id; ?>" <?php echo $row_mof->mof_id == $staff_request->mof_ID ? 'selected="selected"' : ''; ?>>
                                    <?php echo $row_mof->job_title; ?>
                                  </option>
                                <?php endforeach; ?>
                              </select>
                              <?php echo form_error('select_mof'); ?>
                            </div>
                          </div>
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                          Descripción del puesto
                        </a>
                      </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse in">
                      <div class="panel-body">
                      
                        <div id="section-job-description">
                          <div class="step-title">
                            Descripción del puesto
                          </div>
                          <div class="step-inputs">
                            <div class="input-group <?php echo (form_error('user_management'))?'has-error':'';?>">
                              <label class="input-group-addon">Gerencia usuaria <span>*</span></label>
                              <input name="user_management" type="text" class="form-control" id="user_management" value="<?php echo $staff_request->user_management; ?>">
                              <?php echo form_error('user_management'); ?>
                            </div>
                            <div class="input-group <?php echo (form_error('applicant_headquarter'))?'has-error':'';?>">
                              <label class="input-group-addon">Jefatura del solicitante <span>*</span></label>
                              <input name="applicant_headquarter" type="text" class="form-control" id="applicant_headquarter" value="<?php echo $staff_request->applicant_headquarter; ?>">
                              <?php echo form_error('applicant_headquarter'); ?>
                            </div>

                            <?php echo form_error('vacancies', '<div class="info-error"><i class="glyphicon glyphicon-remove-circle" ></i>&nbsp;&nbsp;', '</div>'); ?>
                            <div class="input-group <?php echo (form_error('vacancies'))?'has-error':'';?>">
                              <label class="input-group-addon">Número de vacantes <span>*</span></label>
                              <input name="vacancies" type="text" class="form-control" id="vacancies" value="<?php echo $staff_request->vacancies; ?>" style="width: 40%;">
                            </div>

                            <div class="input-group <?php echo (form_error('name_immediate_boss'))?'has-error':'';?>">
                              <label class="input-group-addon">Nombre del jefe inmediato <span>*</span></label>
                              <input name="name_immediate_boss" type="text" class="form-control" id="name_immediate_boss" value="<?php echo $staff_request->name_immediate_boss; ?>">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                          Contratación
                        </a>
                      </h4>
                    </div>
                    
                    <div id="collapse4" class="panel-collapse collapse in">
                      <div class="panel-body">
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
                                    <input id="contract_time_qty" class="form-control" name="contract_time_qty" value="<?php echo $staff_request->contract_time_qty ;?>" maxlength="2" style="text-align: center;" placeholder="Ej: 1 Año">
                                  </td>
                                  <td width="3%"></td>
                                  <td>
                                    <select name="contract_time_duration" class="form-control" id="contract_time_duration">
                                      <?php $contract_time_duration = $staff_request->contract_time_duration; ?>  
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
                                <?php $renovable = $staff_request->renovable; ?>
                                <option value="">Seleccione</option>
                                <option value="yes" <?php echo $renovable == 'yes' ? 'selected="selected"' : ''; ?>>Si</option>
                                <option value="no" <?php echo $renovable == 'no' ? 'selected="selected"' : ''; ?>>No</option>
                              </select>
                              <?php echo form_error('renovable'); ?>
                            </div>
                            <div class="input-group <?php echo (form_error('reason_request'))?'has-error':'';?>">
                              <label class="input-group-addon">Motivo de requerimiento <span>*</span></label>
                              <select id="reason_request" name="reason_request" class="form-control">
                                <?php $reason_request = $staff_request->reason_request; ?>
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
                              $employee_replace_dni =  $staff_request->employee_replaced_dni;
                              $employee_replace_name = $staff_request->employee_replaced_name;      
                            ?>
                            <div id="content-replace-employee" <?php echo !$is_replaced_employee ? 'style="display:none"' : ''; ?>>
                              <div class="input-group">
                                <label class="input-group-addon">Trabajador a reemplazar <span>*</span></label>
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
                                <?php echo form_error('replace_employee'); ?>
                              </div>
                              <div id="content-main-attach-file" class="input-group">
                                <label class="input-group-addon">Adjuntar documento<span></span></label>
                                <div class="content-attach-files">
                                  <div class="attach-file-item">
                                    <?php if ($staff_request->employee_change_file_path): ?>
                                      <div class="row"> 
                                        <div class="col-md-12 content-filename">
                                          <a class="attached-item__link" href="<?php echo file_url($staff_request->employee_change_file_path); ?>" target="_blank">
                                            <i class="glyphicon glyphicon-download-alt"></i>&nbsp;ARCHIVO ADJUNTO
                                          </a>
                                          <input type="hidden" name="attached_file" value="<?php echo $staff_request->employee_change_file_path; ?>">
                                        </div> 
                                      </div>
                                      <button class="item-remove" type="button" data-file-path="<?php echo $staff_request->employee_change_file_path; ?>">
                                        <i class="glyphicon glyphicon-remove"></i>
                                      </button>
                                    <?php else: ?>
                                      <a href="#" class="btn-attach-file">Cargar archivo</a>
                                    <?php endif; ?>
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
                                           value="<?php echo $staff_request->minimum_salary; ?>" 
                                           placeholder="Mínimo"
                                           readonly>
                                  </td>
                                  <td class="separator">a</td>
                                  <td>
                                    <input name="maximum_salary" 
                                           type="text" 
                                           class="form-control" 
                                           id="maximum_salary" 
                                           value="<?php echo $staff_request->maximum_salary; ?>" 
                                           placeholder="Máximo"
                                           readonly>
                                  </td>
                                </tr>
                              </table>
                              <?php echo form_error('salary_range'); ?>
                            </div>                   
                            <div class="input-group <?php echo (form_error('monthly_gross_salary'))?'has-error':'';?>">
                              <label class="input-group-addon">Remuneración bruta mensual <span>*</span></label>
                              <input name="monthly_gross_salary" type="text" class="form-control" id="monthly_gross_salary" value="<?php echo $staff_request->monthly_gross_salary; ?>" style="width: 40%;">
                              <?php echo form_error('monthly_gross_salary'); ?>
                            </div>

                            <div class="input-group <?php echo (form_error('type_remuneration'))?'has-error':'';?>">
                              <label class="input-group-addon">Tipo de remuneración <span></span></label>

                              <select id="type_remuneration" name="type_remuneration" class="form-control">
                                <?php $type_remuneration = $staff_request->type_remuneration; ?>
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
                                <?php $salary_delivery_period = $staff_request->salary_delivery_period; ?>
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
                                      <?php $working_hours = !empty($working_hours) ? $working_hours : array(); ?>

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
                                    <textarea rows="3" class="form-control" name="working_hours_manual" ><?php echo $staff_request->working_hours; ?></textarea>                  
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
                                  <?php $ubigeo_value = $row->order_administrative1 . ', ' . $row->order_administrative2 . ', ' . $row->order_administrative3;?>
                                  <option value="<?php echo $ubigeo_value; ?>" <?php echo $staff_request->location == $ubigeo_value ? 'selected="selected"' : ''; ?>>
                                    <?php echo $ubigeo_value; ?>
                                  </option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="input-group <?php echo (form_error('job_address'))?'has-error':'';?>">
                              <label class="input-group-addon">Dirección de trabajo <span>*</span></label>
                              <input name="job_address" type="text" class="form-control" id="job_address" value="<?php echo $staff_request->job_address; ?>">
                              <?php echo form_error('job_address'); ?>
                            </div>

                            <div class="input-group <?php echo (form_error('start_date_work'))?'has-error':'';?>">
                              <label class="input-group-addon">Fecha de inicio contratación<span></span></label>
                              <input id="start_date_work" name="start_date_work" class="form-control" type="date" style="max-width:150px;" value="<?php echo $staff_request->start_date_work; ?>">
                              <?php echo form_error('start_date_work'); ?>
                            </div>

                            <div class="input-group <?php echo (form_error('end_date_work'))?'has-error':'';?>">
                              <label class="input-group-addon">Fecha de fin contratación<span></span></label>
                              <input id="end_date_work" name="end_date_work" class="form-control" type="date" style="max-width:150px;" value="<?php echo $staff_request->end_date_work; ?>">
                              <?php echo form_error('end_date_work'); ?>
                            </div>

                            <div class="input-group <?php echo (form_error('observations'))?'has-error':'';?>">
                                <label class="input-group-addon">Obsevaciones <span></span></label>
                                <input id="observations" name="observations" class="form-control" type="text" value="<?php echo $staff_request->observations; ?>">
                                <?php echo form_error('observations'); ?>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                          Beneficios adicionales
                        </a>
                      </h4>
                    </div>
                    <div id="collapse5" class="panel-collapse collapse in">
                      <div class="panel-body">                
                        <div id="section-additional-benefits">
                          <div class="step-title">
                            Beneficios adicionales
                          </div>

                          <div class="step-inputs">
                            <table id="table-additional-benefits" width="100%">
                              <?php 
                                  $benefit_info_data = [];

                                  if ($mof) {
                                    $benefit_info_data = [
                                      'Bono de Productividad' => "De $mof->bonuses_commissions_minimum a $mof->bonuses_commissions_maximum Soles",
                                      'Comisiones' => "De $mof->bonuses_commissions_minimum a $mof->bonuses_commissions_maximum Soles",
                                      'Movilidad' => "De $mof->mobility_minimum a $mof->mobility_maximum Soles",
                                      'Vales de Alimento' => "De $mof->food_maximum a $mof->food_maximum Soles"
                                    ];
                                  }
                              ?>
                              <?php foreach ($additional_benefits as $benefit): ?>
                              <tr data-row-benefit-name="<?php echo $benefit->benefit_name; ?>">
                                <td width="45%">
                                  <?php echo $benefit->benefit_name; ?>
                                  <input type="hidden" name="additional_benefits[<?php echo $benefit->ID; ?>][id]">
                                  <span style="display: block; font-size: 12px;color:#666666;" class="benefit-info" >
                                    <?php echo isset($benefit_info_data[$benefit->benefit_name]) ? $benefit_info_data[$benefit->benefit_name] : ''; ?>
                                  </span>
                                </td>
                                <td width="15%">
                                  <input type="checkbox" name="additional_benefits[<?php echo $benefit->ID; ?>][checked]" value="true" class="benefit-checked" <?php echo $benefit->ID == $benefit->request_benefit_ID ? 'checked="checked"' : ''; ?>>
                                </td>
                                <td width="15%">
                                  <label>Especificar</label>
                                </td>
                                <td width="25%">
                                  <input type="text" name="additional_benefits[<?php echo $benefit->ID?>][detail]" class="form-control benefit-detail" value="<?php echo $benefit->detail; ?>">
                                </td>
                              </tr>
                              <?php endforeach; ?>
                            </table>              
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div> 
                </div>
                <?php echo form_close();?>
          
            </div>
          </div>
        </div>
      </div>
      
      <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
      <script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
      <script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
      <script id="tpl-add-working-hours" type="text/template" >
        <tr class="row-working-hours">
          <td>
            <select class="form-control" name="working_hours[{{index}}][start_day]">
              <option value="">Seleccione</option>
              <option value="Lunes">Lunes</option>
              <option value="Martes">Martes</option>
              <option value="Miércoles">Miércoles</option>
              <option value="Jueves">Jueves</option>
              <option value="Viernes">Viernes</option>
              <option value="Sábado">Sábado</option>
              <option value="Domingo">Domingo</option>
            </select>
          </td>
          <td width="5%" style="text-align: center;">a</td>
          <td>
            <select class="form-control" name="working_hours[{{index}}][end_day]">
              <option value="">Seleccione</option>
              <option value="Lunes">Lunes</option>
              <option value="Martes">Martes</option>
              <option value="Miércoles">Miércoles</option>
              <option value="Jueves">Jueves</option>
              <option value="Viernes">Viernes</option>
              <option value="Sábado">Sábado</option>
              <option value="Domingo">Domingo</option>
            </select>
          </td>
          <td width="5%"></td>
          <td width="10%">
            <input style="text-align: center;" type="text" name="working_hours[{{index}}][start_time]" value="" placeholder="12:00" data-timepicker class="form-control">
          </td>
          <td width="8%">
            <select id="" class="form-control" name="working_hours[{{index}}][start_time_abr]">
              <option value="am" >AM</option>
              <option value="pm" >PM</option>
            </select>
          </td>
          <td width="5%" style="text-align: center;">a</td>
          <td width="10%">
            <input style="text-align: center;" type="text" name="working_hours[{{index}}][end_time]" value="" placeholder="12:00" data-timepicker class="form-control">
          </td>
          <td width="8%">
            <select id="" class="form-control" name="working_hours[{{index}}][end_time_abr]">
              <option value="am" >AM</option>
              <option value="pm" >PM</option>
            </select>
          </td>

          <td width="5%" style="text-align: center;">
            <a href="#" class="remove-working-hours">
              <i class="glyphicon glyphicon-remove"></i>
            </a>
          </td>
        </tr>
      </script>
      <script type="text/javascript">

      $(function() {

        function showErrors(errors) {
          $.each(errors, function(fieldName, message_error) {
              if (fieldName == "field_working_hours") {
                $( ".field_working_hours" ).html('<p class="info-error"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;&nbsp;' + message_error + '</p>')
                $( ".field_working_hours" ).addClass("has-error");
              } else {
              var error = '<p class="info-error"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;&nbsp;' + message_error + '</p>';

              if (fieldName == 'functions[]') {
                $( ".js-error-functions" ).append(error);
                return;
              }

              var input = $( "*[name='" + fieldName + "']" );
              var wrapperInput = input.closest(".input-group").addClass("has-error");
              wrapperInput.before(error);
            }
          });
        }

        function showSelectorFiles() {
          $( "#input-attach-file" ).remove();
          var inputFile = $('<input id="input-attach-file" type="file" name="file" style="display:none;">');
          $( ".content-attach-files" ).append(inputFile);
          
          $(inputFile).fileupload({
            dataType: 'json',
            url: "<?php echo site_url('employer/staff_request/staff_requests/upload_internal_staff_request_file'); ?>",
            autoUpload: true,
            add: function (e, data) {
              $(".attach-file-item").remove();
              $( "#btn-attach-file").remove();

              var fileName = data.files[0].name;
              var item = $('<div class="attach-file-item">' + 
                            '<div class="row">' + 
                                  '<div class="col-md-12 content-filename">Cargando...</div>' + 
                                  '<div class="col-md-12">' + 
                                      '<span class="content-progress-bar">' +
                                          '<span class="total-progress-bar"></span>' +
                                      '</span>' +
                                  '</div>' +
                              '</div>' +
                              '<button class="item-remove" type="button">' +
                                  '<i class="glyphicon glyphicon-remove"></i>' +
                              '</button>' +
                          '</div>');

              $( ".content-attach-files" ).append(item);
              data.context = item;      
              data.submit();
            },
            progress: function(e, data) {
              var progressBar = $( ".total-progress-bar", data.context);
              var progress = parseInt(data.loaded / data.total * 100, 10);
              progressBar.width(progress + "%");
            }, 
            done: function (e, data) {

              var item = data.context;
              var originalFileName = data.result.original_file_name;
              var urlFile = data.result.url_file;
              var fileName = data.result.file_name;
              
              $( ".content-progress-bar", item).remove();
            
              if (data.result.error) {
                var linkFile = '<a href="#" target="_blank" class="btn-attach-file" >Volver a intentar</a>';
                $( ".attach-file-item", item).html(linkFile);
                alert(data.result.error);
                return;
              }

              var linkFile = '<a href="' + urlFile + '" target="_blank" >' + originalFileName + '</a>';
              $( ".content-filename", item).html(linkFile);
              $( ".content-filename", item).append('<input type="hidden" name="attached_file" value="' + data.result.location + '">');
              
              $( ".item-remove", item).data('file-path', data.result.location).show();
            }
          });
          
          inputFile[0].click();
        }

        function removeFile(filePath, item) { 
          var linkFile = '<a href="#" target="_blank" class="btn-attach-file" >Cargar archivo</a>';
          item.html(linkFile);
        }

        $(document).off('click', '.btn-attach-file');
        $(document).on('click', '.btn-attach-file', function(){
          showSelectorFiles();
        });
        
        $(document).off('click', '.item-remove').on('click', '.item-remove', function(){
          var item = $(this).closest('.attach-file-item');
          var fileId = $(this).data('file-path');
          removeFile(fileId, item);
        });

        function addWorkingHours() {
          var template = $( "#tpl-add-working-hours" ).html();
          var index  = $("#tpl-add-working-hours" ).generateSequence() * -1;

          var row = Mustache.render(template, {index: index});  
          $( "#tbl-working-hours tbody" ).append(row);
        }

        function cleanErrorReplaceEmployee() {
          var contentDiv = $( "#selector-replace-employee" ).closest(".input-group");
          contentDiv.siblings(".info-error").remove();
          contentDiv.removeClass("has-error");
        }

        $( "#form-request-save-profile-survey-internal" ).submit(function(e) {
          e.preventDefault();
          if (!window.confirm("¿Está seguro de guardar el levantamiento de perfil?")) {
            return;
          }
        
          $( ".has-error" ).removeClass("has-error");
          $( ".info-error").remove();

          parent = $(this).find('.container-submit');
          parent.find('button').prop('disabled', true);
          parent.find('button[type=submit]').html('Guardando...');

          var url = $(this).prop('action');
          var data = $(this).serialize();

          $.post(url, data, function(response) {

            if (!response.status) {
              parent.find('button').prop('disabled', false);
              parent.find('button[type=submit]').html('Guardar');

              toastr["error"](response.message);
              showErrors(response.data.errors);
              
              return;
            }

            window.location.reload();

          }, 'json');

          return false;
        });

        $( "#prev-step-request" ).click(function(){
            var prevStep = $( ".content-step:visible").index();
            showStep(prevStep);
        });

        $( "#confirm-no" ).click(function(){
            var prevStep = $( ".content-step:visible").index();
            showStep(prevStep);
        });

        $( "#btn-add-additional-competence" ).click(function(){
          addRowAdditionalCompetence();
        });

        $( "#occupational_group" ).change(function(){
          var job_charge_id = $(this).val();
          $( ".row-fixed-competence" ).remove();
          searchCompetencesByJobChargeId(job_charge_id);
        });

        $( "#internal_area" ).change(function(){
          var area_id = $(this).val();
          var area_name = $(this).find("option:selected").text();
          
          getDataMOFs(area_id);
          $( "#belonging_area" ).text(area_name);
        });

        $( "#reason_request" ).change(function() {
          var reasonRequestVal = $(this).val();
          
          if (reasonRequestVal == 'replacement' || 
              reasonRequestVal == 'vacations'  || 
              reasonRequestVal == 'license') {
            $( "#content-replace-employee" ).show();
            //$( "#content-main-attach-file").show();
          } else {
            cleanErrorReplaceEmployee();
            $( "#content-replace-employee" ).hide();
            //$( "#content-main-attach-file").hide();
          }
        });

        $(document).on("click", "#remove-replace-employee", function(){
          $( "#row-selector-replace-employee" ).hide();
          $( "#row-search-replace-employee" ).show();
          $( "#selector-replace-employee" ).val("");
          cleanErrorReplaceEmployee();
        });

        $( "#btn-search-replace-employee" ).click(function() {
          var query = $( "#search-replace-employee" ).val();
          
          if (query.length < 3) {
            alert('¡Por favor ingrese al menos 3 caracteres!');
            return;
          }

          var url = "<?php echo site_url('general/overall_web_services/search_employee_api?q='); ?>" + query;
        
          $( "#search-replace-employee" ).prop('disabled', true);
          $( "#btn-search-replace-employee" ).prop('disabled', true);
          $( "#row-selector-replace-employee" ).hide();
          $( "#prev-step-request" ).prop('disabled', true);
          $( "#next-step-request" ).prop('disabled', true);
          
          $.getJSON(url, function(response) {
        
            var data_employees = response.data_employees;
          
            cleanErrorReplaceEmployee();
            
            if (data_employees.length == 0) {
              toastr["error"]("¡Ningún resultado encontrado!");
              return;
            }

            var selector_options = '<option value="">Seleccione</option>';
            
            $( "#selector-replace-employee" ).empty();
            $( "#selector-replace-employee" ).append(selector_options);


            for (var row_emp in data_employees) {
              var employee = data_employees[row_emp];
              var selector_value = employee.DNI + ' - ' + 
                                  employee.PRIMER_NOMBRE + ' ' + employee.SEGUNDO_NOMBRE + ' ' +
                                  employee.APELLIDO_PATERNO + ' ' + employee.APELLIDO_MATERNO; 


              selector_options = '<option value="' + selector_value + '">' + selector_value + '</option>';
          
              $( "#selector-replace-employee option[value='" + selector_value + "']").remove();
              $( "#selector-replace-employee" ).append(selector_options);
            }

            $( "#selector-replace-employee" ).select2();
            $( "#row-search-replace-employee" ).hide();
            $( "#row-selector-replace-employee" ).show();
        
          }).fail(function(){
            alert("Ha ocurrido un error!");
          }).always(function() {
            $( "#search-replace-employee" ).prop('disabled', false);
            $( "#btn-search-replace-employee" ).prop('disabled', false);
            $( "#prev-step-request" ).prop('disabled', false);
            $( "#next-step-request" ).prop('disabled', false);
          });
        });

        $( "#add-working-hours" ).click(function(e) {
          e.preventDefault();
          addWorkingHours();
        });

        $(document).on("click", ".remove-working-hours", function(e) {
          e.preventDefault();
          $(this).closest('.row-working-hours').remove();
        });

        $( "#select_mof" ).select2();

        $( document ).on('change', '#start_date_work', function(){
          $( '#end_date_work' ).val('');
          $( '#end_date_work' ).closest('.has-error').prev().remove();
          $( '#end_date_work' ).closest('.has-error').removeClass('has-error');
          $( '#end_dare_work' ).prop('disabled', false);
        });
        

      $( "#location" ).select2();
      $( "#selector-replace-employee" ).select2();   

      });
      </script>

    </div>
  </div>
</div>
