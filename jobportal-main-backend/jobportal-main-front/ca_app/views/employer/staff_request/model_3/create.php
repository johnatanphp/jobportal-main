<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
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
    text-align: center;
    color: #fff;
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

.table-items {
  width: 100%;
  margin-bottom: 30px;
}

.table-items tr th {
  padding: 5px 5px 15px 5px;
}


.table-items tr td {
  padding: 3px;
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
  padding: 3px 7px;
}

#table-additional-benefits .tr-bg-1 {
  background: #e0e0e0;
}

#table-additional-benefits .tr-bg-2 {
  background: #fff;
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

#text-guide-function {
  font-size: 17px;
  text-transform: lowercase;
}

#table-competences {
  border: 1px solid #bbb;
  background:#efefef;
  width: 100%;
}

#table-competences tr td input {
  font-size: 15px;
}

#table-competences tr td {
  padding: 8px 4px;
  border-bottom: 1px solid #bbb;
  font-size: 15px;
  font-style: italic;
  color: #444;
}

#content-info-eg {
  font-size: 15px;
  padding: 10px 6px;
  background: #eee;
  margin-bottom: 10px;
}

.text-verb {
  font-weight: bold;
}

.text-object {
  font-style: italic;
}

.text-result {
  text-decoration: underline;
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

#table-employee-replace tr td {
  padding: 5px 10px;
}

#table-replace-employee tr td {
  padding: 5px 10px;
}

#btn-search-replace-employee {
  background: #e0e0e0 !important;
  border: 1px solid #ccc;
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

#tbl-field-select-eecc tr td a {
  color: #666666;
  text-decoration: underline;
}

#tbl-field-select-eecc {
  border: 1px solid #cccccc;
}
#tbl-field-select-eecc tr td {
  color: #555555;
}

.remove-dep-vanacies {
  background: transparent;
  border: 0;
  color: #ff6464;
  padding:1px;
}

</style>
<?php $this->load->view('common/before_head_close'); ?>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php $this->load->view('common/header'); ?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row">
    <div class="col-md-3">
      <div class="dashiconwrp">
        <?php $this->load->view('employer/common/menu/sidebar');?>
      </div>
    </div>
  
    <div class="col-md-9"> 
      <?php echo $this->session->flashdata('msg'); ?>

      <?php if (validation_errors() != false): ?>
        <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert">&times;</a>
          <strong>Error</strong> La solicitud no se pudo crear, los datos enviados no son válidos.
        </div>
      <?php endif; ?> 

      <div class="formwraper">
        <div class="titlehead">Crear solicitud Modelo 3</div>
        <div class="row"> 
          <?php echo form_open_multipart('',array('id' => 'form-create-request'));?>
          <div class="col-md-12">
            <div class="formint">    
              <div id="step-counter" style="display: none;">
                <ul>
                  <li><a rel="tooltip" title="Paso 1: Datos de la solicitud">1</a></li>
                  <li><a rel="tooltip" title="Paso 2: Descripción del puesto">2</a></li>
                  <li><a rel="tooltip" title="Paso 3: Contratación">3</a></li>
                  <li><a rel="tooltip" title="Paso 4: Beneficios adicionales">4</a></li>
                  <li><a rel="tooltip" title="Paso 5: Comentarios adicionales del cliente">5</a></li>
                </ul>
              </div>
              <div id="wrapper-steps">
                <div class="content-step">
                  <!-- Start section select request type -->
                  <div id="section-request-type">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="step-title">Datos de la solicitud</div>
                        <div class="step-inputs">   

                          <div class="input-group">
                            <label class="input-group-addon">Gerencia <span></span></label>
                            <select name="management" id="management" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>
                              <?php foreach($managers as $manager): ?>
                                <option value="<?php echo $manager->manager;?>">
                                  <?php echo $manager->manager;?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>                       
                          <div class="input-group">
                            <label class="input-group-addon">Consultora <span>*</span></label>
                            <select  id="consultant" name="consultant_name" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>    
                            </select>
                          </div>

                          <div class="input-group" >
                            <label class="input-group-addon">Unidad de negocio <span>*</span></label>
                            <select id="business_unit" name="business_unit_name" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                          
                          <div class="input-group" >
                            <label class="input-group-addon">Empresa cliente <span>*</span></label>
                            <select id="client_company" name="client_company_name" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>
                            </select>
                          </div>

                          <div class="input-group" >
                            <label class="input-group-addon">Centro de costo <span>*</span></label>
                            <select id="cost_center" name="cost_center" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>
                            </select>
                          </div>

                          <div class="input-group">
                            <label class="input-group-addon">Plantilla <span>*</span></label>
                            <select name="request_template" type="text" class="form-control" id="request-template" style="width: 100%;">
                              <option value="">Seleccione</option>
                              <option value="1">Manual</option>
                              <?php if ($this->config->item('job_profile_module_enabled')): ?>
                                <option value="2">Perfil de puesto</option>
                              <?php endif; ?>
                              <?php if ($this->config->item('job_layouts_module_enabled')): ?>
                                <option value="3">Layout de puesto</option>
                              <?php endif; ?>
                            </select>
                          </div>

                          <div class="input-group" style="display:none;">
                            <label class="input-group-addon">Puesto <span>*</span></label>
                            <input id="job-title-input" type="text" class="form-control" name="job_title">
                          </div>

                          <div class="input-group" style="display:none;">
                            <label class="input-group-addon">Perfil de puesto <span>*</span></label>
                            <select id="job-profile" name="job_profile" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>
                            </select>
                          </div>

                          <div class="input-group" style="display:none;">
                            <label class="input-group-addon">Layout de puesto <span>*</span></label>
                            <select id="job-layout" name="job_layout" type="text" class="form-control" style="width: 100%;">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- End section select request type -->
                </div>
                <div class="content-step">
                  <div id="section-job-description">
                    <div class="step-title">
                      Descripción del puesto
                    </div>
                    <div class="step-inputs">
                        
                      <div class="input-group" >
                        <label class="input-group-addon">Tipo de requerimiento <span>*</span></label>
                        <select id="type-requirement" name="type_requirement" class="form-control" style="width: 100%;">
                          <option value="">Seleccione</option>
                          <option value="REGULAR">REGULAR</option>
                          <option value="ESPECIAL">ESPECIAL</option>
                        </select>
                      </div>
                      <div class="input-group">
                        <label class="input-group-addon">Fecha solicitud <span></span></label>
                        <?php echo date('d/m/Y'); ?>
                      </div>

                      <div class="input-group">
                        <label class="input-group-addon">Campaña <span></span></label>
                        <input type="text" name="campaign" class="form-control" maxlength="60">
                      </div>

                      <div class="input-group">
                        <label class="input-group-addon">Servicio <span>*</span></label>
                        <select name="service" class="form-control" style="width:100%;">
                          <option value="">Seleccione</option>
                          <?php 
                            $service_list = [
                              'Promotoria',
                              'Mercaderismo'
                            ];
                          ?>
                          <?php foreach ($service_list as $service_row): ?>
                            <option value="<?php echo $service_row; ?>">
                              <?php echo $service_row; ?> 
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="input-group">
                        <label class="input-group-addon">Fecha de entrega <span>*</span></label>
                        <input type="date" 
                               name="delivery_date" 
                               class="form-control" 
                               style="width: 40%;" 
                               min="<?php echo date("Y-m-d",strtotime(date('Y-m-d ') . "+ 7 day")); ?>">
                        <div id="delivery-date-regular" 
                             style="display:none;">
                          <?php echo date("d/m/Y",strtotime(date('Y-m-d ') . "+ 7 day")); ?>
                        </div>
                      </div>

                      <div class="input-group">
                        <label class="input-group-addon">División <span></span></label>
          
                        <select name="division" class="form-control" style="width: 40%;">
                          <option value="">Seleccione</option>
                          <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                          <option value="OPERATIVO">OPERATIVO</option>
                        </select>
                      </div>  

                      <div class="input-group">
                        <label class="input-group-addon">Canal <span>*</span></label>
                        <select name="channel" class="form-control" style="width: 40%;">
                          <option value="">Seleccione</option>
                          <option value="Moderno">Moderno</option>
                          <option value="Tradicional">Tradicional</option>
                          <option value="Retail">Retail</option>
                        </select>
                      </div>

                      <div class="input-group content-req-by-department content-vacancies">
                        <label class="input-group-addon">Número de vacantes <span>*</span></label>
                        <input name="vacancies" type="text" class="form-control" id="vacancies" value="<?php echo set_value('vacancies') ? set_value('vacancies') : @$request->vacancies; ?>" style="width: 40%;">
                      </div>    
                      
                      <?php //if ($company->ID == 1): ?>
                        <div class="step-title">
                          Ubicación
                        </div>
                        <br>
                        
                        <div class="input-group">
                          <label class="input-group-addon">Requerimiento por Dpto <span></span></label>
                          <input type="checkbox" name="req_by_department" checked>
                        </div>

                        <div class="content-req-by-department content-department-vacancies">
                          <div class="js-error-dep-vacancies"></div>
                          <div class="input-group">
                            <label class="input-group-addon">Departamentos <span>*</span></label>
                            <table id="tbl-department-vacancies" class="table" style="width:100%;">
                              <thead>
                                <tr>
                                  <th>Ubicación</th>
                                  <th>Vacantes</th>
                                  <th style="text-align:right;">
                                    <button type="button" class="btn btn-xs btn-default add-item-department-vacancies">Agregar</button>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      
                        <div class="input-group content-req-by-department content-location-department">
                          <label class="input-group-addon">Departamento <span>*</span></label>
                          <select name="department[]" class="form-control" style="width: 40%;" multiple="true">
                              <?php foreach ($departments as $dep): ?>
                                <option value="<?php e($dep->department); ?>">
                                    <?php e($dep->department); ?>
                                </option>
                              <?php endforeach; ?> 
                          </select>
                        </div>

                        <div class="input-group">
                          <label class="input-group-addon">Zona <span>*</span></label>
                          <select name="zone[]" 
                                  class="form-control" 
                                  style="width: 40%;" 
                                  multiple="true">
                            <?php 
                              $zones = [
                                'Norte',
                                'Sur',
                                'Este',
                                'Oeste',
                                'Centro',
                                'Casco urbano'
                              ];
                            ?>
                            <?php foreach ($zones as $zone): ?>
                              <option value="<?php e($zone); ?>"><?php e($zone); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="input-group">
                          <label class="input-group-addon">Comentario <span></span></label>
                          <input type="text" class="form-control" name="zone_comments">
                        </div>
                      <?php //endif; ?>
                    </div>
                  </div>
                </div>

                <div class="content-step">

                  <div id="section-hiring">
                    <div class="step-title">
                      Contratación
                    </div>
                    <div class="step-inputs">
                                
                      <div class="input-group">
                        <label class="input-group-addon">Tipo de contrato <span>*</span></label>
                        <select name="type_contract" class="form-control">
                          <option value="" >Seleccione</option>
                          <option value="Renovable">Renovable</option>
                          <option value="No renovable">No renovable</option>
                        </select>
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

                      <div class="input-group <?php echo (form_error('gender'))?'has-error':'';?>">
                        <label class="input-group-addon">Sexo <span>*</span></label>
                        <select name="gender" class="form-control">
                          <?php $gender = set_value('gender') ? set_value('gender') : @$request->gender; ?>
                          <option value="" >Seleccione</option>
                          <option value="male" <?php echo $gender == 'male' ? 'selected="selected"' : ''; ?>>Hombre</option>
                          <option value="female" <?php echo $gender == 'female' ? 'selected="selected"' : ''; ?>>Mujer</option>
                          <option value="both" <?php echo $gender == 'both' ? 'selected="selected"' : ''; ?>>Ambos</option>
                        </select>
                        <?php echo form_error('gender'); ?>
                      </div>
                      <div class="input-group <?php echo (form_error('job_mode'))?'has-error':'';?>">
                        <label class="input-group-addon">Tipo de jornada laboral <span>*</span></label>
                        <select name="job_mode" class="form-control">
                          <option value="">Seleccione</option>
                          <?php $job_mode = set_value('job_mode') ? set_value('job_mode') : @$request->job_mode; ?>
                          <option value="full_time" <?php echo ($job_mode == 'full_time')?'selected="selected"':'';?>>Full-Time</option>
                          <option value="part_time" <?php echo ($job_mode =='part_time')?'selected="selected"':'';?>>Part-Time</option>
                          <option value="weekends" <?php echo ($job_mode =='weekends')?'selected="selected"':'';?>>Fines de Semana</option>
                          <option value="telecommuting" <?php echo ($job_mode =='telecommuting')?'selected="selected"':'';?>>Teletrabajo</option>
                        </select>
                        <?php echo form_error('job_mode'); ?>
                      </div>

                      <div class="input-group">
                        <label class="input-group-addon">Carnet de sanidad <span>*</span></label>
                        <select name="health_card" class="form-control">
                          <option value="">Seleccione</option>
                          <option value="No aplica">No aplica</option>
                          <option value="Con manipulación de alimentos">Con manipulación de alimentos</option>
                          <option value="Sin manipulación de alimentos">Sin manipulación de alimentos</option>
                        </select>
                      </div>

                      <div class="input-group">
                        <label class="input-group-addon" style="vertical-align: top;">Horario laboral <span>*</span></label>
                        <textarea rows="3" class="form-control" name="working_hours_manual" ><?php echo @$request->working_hours; ?></textarea>                  
                      </div>

                      <br />
                    </div>
                  </div>
                </div>

                <div class="content-step">
                  <div id="section-additional-benefits">
                    <div class="step-title">
                      Estructura Salarial
                    </div>
                  
                    <div class="step-inputs">

                      <div class="input-group <?php echo (form_error('minimum_salary') || form_error('maximum_salary')) ? 'has-error':'';?>">
                        <label class="input-group-addon">Rango de remuneración <span></span></label>
                        <table id="table-range-salary" width="100%">
                          <tr>
                            <td>
                              <input name="minimum_salary" type="text" class="form-control" id="minimum_salary" value="<?php echo set_value('minimun_salary') ? set_value('minimun_salary') : @$request->minimum_salary; ?>" placeholder="Mínimo">
                            </td>
                            <td class="separator">a</td>
                            <td>
                              <input name="maximum_salary" type="text" class="form-control" id="maximum_salary" value="<?php echo set_value('maximum_salary') ? set_value('maximum_salary') : @$request->maximum_salary; ?>" placeholder="Máximo">
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

                      <div class="step-title">
                        Beneficios laborales
                      </div>

                      <table id="table-additional-benefits" 
                            width="100%" 
                            class="table table-striped">
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="content-step">
                  <div id="section-additional-comments">
                    <div class="step-title">
                      Comentarios adicionales del cliente
                    </div>
                    <div class="step-inputs">
                      <div class="input-group <?php echo (form_error('additional_comments'))?'has-error':'';?>">
                        <textarea name="additional_comments" class="form-control" rows="6"><?php echo set_value('additional_comments') ? set_value('additional_comments') : @$request->additional_comments; ?></textarea>
                        <?php echo form_error('additional_comments'); ?>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="content-step">
                  <div id="form-confirm">
                    <div class="step-title">
                      Confirmación
                    </div>
                    <div class="step-inputs">
                      <div style="max-width: 520px;margin: 0 auto;text-align: center;">
                        <h4 style="line-height: 1.5;">
                          Completaste todos los pasos, si estás seguro de crear la solicitud haz clic en 'Crear solicitud'.
                        </h4> 
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div align="center" style="margin-top: 30px;">
                <input id="prev-step-request" type="button" value="Atrás" class="btn btn-primary" />
                <input id="next-step-request" type="submit" value="Siguiente" class="btn btn-primary" />
                <input id="create-request" type="submit" value="Crear solicitud" class="btn btn-primary" />
              </div>
            </div>
          </div>
          <?php echo form_close();?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/tooltip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>  
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

<script id="tpl-department" type="text/template" >
  <select name="dep_vacancies[{{index}}][department]" class="form-control select-dep-vacancies" style="width: 100%">
    <option value="">Seleccione</option>
    <?php foreach ($departments as $dep): ?>
      <option value="<?php e($dep->department); ?>">
        <?php e($dep->department); ?>
      </option>
    <?php endforeach; ?> 
  </select>
</script>

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

<script id="tpl-additional-benefits-general" type="text/template" >
  <?php foreach ($additional_benefits as $row): ?>
    <tr>
      <td width="45%">
        <?php echo $row->benefit_name; ?>
        <input type="hidden" name="additional_benefits[<?php echo $row->ID; ?>][id]">
        <span style="display: block; font-size: 12px;color:#666666;" class="benefit-info" >
        </span>
      </td>
      <td width="15%">
        <input type="checkbox" name="additional_benefits[<?php echo $row->ID; ?>][checked]" value="true" class="benefit-checked">
      </td>
      <td width="15%">
        <label>Especificar</label>
      </td>
      <td width="25%">
        <input type="text" name="additional_benefits[<?php echo $row->ID; ?>][detail]" class="form-control benefit-detail" value="">
      </td>
    </tr>
  <?php endforeach; ?>
</script>

<script id="tpl-additional-benefits" type="text/template" >
  <tr data-row-benefit-name="{{benefit_name}}">
    <td width="45%">
      {{benefit_name}}
      <input type="hidden" name="additional_benefits[{{benefit_id}}][id]">
      <span style="display: block; font-size: 12px;color:#666666;" class="benefit-info" >
        {{benefit_info}}
      </span>
    </td>
    <td width="15%">
      <input type="checkbox" 
             name="additional_benefits[{{benefit_id}}][checked]" 
             {{#isChecked}} checked {{/isChecked}} 
             value="true" 
             class="benefit-checked">
    </td>
    <td width="15%">
      <label>Especificar</label>
    </td>
    <td width="25%">
      <input type="text" name="additional_benefits[{{benefit_id}}][detail]" class="form-control benefit-detail" value="{{ minimum }}">
    </td>
  </tr>
</script>

<?php $this->load->view('employer/staff_request/model_3/scripts/create_model_3_js'); ?>
</body>
</html>