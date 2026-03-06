<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link href="<?php echo base_url('public/css/jquery-ui.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('public/css/select2/select2.min.css');?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
        <div class="titlehead">Editar solicitud interna</div>
        <div class="row"> 
          <?php echo form_open_multipart('employer/staff_request/edit_internal_staff_request/save/' . $staff_request->ID, array('id' => 'form-create-request', 'class' => 'formint'));?>

          <div class="col-md-12" style="text-align: right;padding: 10px 15px;">
              <a class="" href="<?php echo site_url('employer/staff_request/staff_requests/show/' . $staff_request->ID); ?>">Cancelar</a>      
              <button type="submit" class="btn btn-primary">Terminar y guardar</button>
          </div>
          <div class="col-md-12">
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

                    <div class="input-group" >
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
              <div id="collapse2" class="panel-collapse collapse">
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
              <div id="collapse3" class="panel-collapse collapse">
                <div class="panel-body">
                
                  <div id="section-job-description">
                    <div class="step-title">
                      Descripción del puesto
                    </div>
                    <div class="step-inputs">
                      <!--
                      <div class="input-group <?php echo (form_error('job_title'))?'has-error':'';?>">
                        <label class="input-group-addon">Nombre del puesto <span></span></label>
                        <label id="job_title"></label>
                        <?php echo form_error('job_title'); ?>
                      </div>

                      <div class="input-group <?php echo (form_error('area'))?'has-error':'';?>">
                        <label class="input-group-addon">Área perteneciente </label>
                        <label id="belonging_area"></label>
                        <?php echo form_error('industry'); ?>
                      </div>
                    -->

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
              
              <div id="collapse4" class="panel-collapse collapse">
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
                          <option value="new" <?php echo $reason_request == 'new' ? 'selected="selected"' : ''; ?>>Nuevo puesto</option>
                          <option value="replacement" <?php echo $reason_request == 'replacement' ? 'selected="selected"' : ''; ?>>Reemplazo</option>
                          <option value="vacations" <?php echo $reason_request == 'vacations' ? 'selected="selected"' : ''; ?>>Vacaciones</option>
                          <option value="license" <?php echo $reason_request == 'license' ? 'selected="selected"' : ''; ?>>Licencia</option>
                        </select>
                        <?php echo form_error('reason_request'); ?>
                      </div>
                      <?php
                        $employee_replace_dni =  $staff_request->employee_replaced_dni;
                        $employee_replace_name = $staff_request->employee_replaced_name;      
                      ?>
                      <div id="content-replace-employee" <?php echo $reason_request == 'new' ? 'style="display:none"' : ''; ?>>
                        <div class="input-group">
                          <label class="input-group-addon">Trabajador a reemplazar <span>*</span></label>
                          <table id="table-replace-employee" width="100%">
                            <tr id="row-search-replace-employee" <?php echo $reason_request != 'new' ? 'style="display:none;"' : ''; ?>>
                              <td class="2">
                                <input id="search-replace-employee" name="search_employee_replace" type="text" class="form-control" value="" placeholder="Buscar por DNI, nombre y apellido">
                              </td>
                              <td>
                                <button id="btn-search-replace-employee" type="button" class="btn btn-xs">Buscar</button>
                              </td>
                            </tr>

                            <tr id="row-selector-replace-employee" <?php echo $reason_request == 'new' ? 'style="display:none;"' : ''; ?>>
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
                              <input name="minimum_salary" type="text" class="form-control" id="minimum_salary" value="<?php echo $staff_request->minimum_salary; ?>" placeholder="Mínimo">
                            </td>
                            <td class="separator">a</td>
                            <td>
                              <input name="maximum_salary" type="text" class="form-control" id="maximum_salary" value="<?php echo $staff_request->maximum_salary; ?>" placeholder="Máximo">
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
                          <label class="input-group-addon">Fecha de inicio de labores <span></span></label>
                          <input id="start_date_work" name="start_date_work" class="form-control" type="text" style="max-width:150px;" value="<?php echo $staff_request->start_date_work; ?>">
                          <?php echo form_error('start_date_work'); ?>
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
              <div id="collapse5" class="panel-collapse collapse">
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

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
                    Autoridades
                  </a>
                </h4>
              </div>
              <div id="collapse6" class="panel-collapse collapse">
                <div class="panel-body">        
                  <div>
                    <div class="step-title">Autoridades encargadas de aprobar la solicitud</div>
                    <div class="step-inputs" style="max-width: 500px; margin: 0 auto;margin-top: 10px;">
                      <div class="form-group <?php echo (form_error('authorities[1][]'))?'has-error':'';?>">
                        <label class="label-authority"><b>Aprobador de Unidad de Negocio</b> <span></span></label>
                        <table id="table-authorities-DR">
                          <tbody>
                            <?php foreach ($request_authorities1 as $row): ?>
                              <tr>
                                <input type="hidden" name="authorities[1][]" value="<?php echo $row->personal_name . ',' . $row->personal_email; ?>" >
                                <td>
                                  <?php 
                                    echo $row->personal_name;
                                  ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>

                      <div class="form-group <?php echo (form_error('authorities[2]'))?'has-error':'';?>">
                        <label class="label-authority"><b>Gerente Administrativo</b> <span>*</span></label>
                        <select name="authorities[2]" class="form-control">
                          <option value="">Seleccione</option>

                          <?php foreach ($authorities2 as $row_authority): ?>
                            <?php 
                              $authority = $request_authorities2[0];
                              $authorization2_value = $authority->personal_name . ',' . $authority->personal_email; 
                              $authority2_value = $row_authority->name . ',' . $row_authority->email;
                            ?>
                            <option value="<?php echo $authority2_value; ?>" <?php echo $row_authority->email == $authority->personal_email ? 'selected="selected"' : ''; ?>>
                              <?php echo $row_authority->name; ?>    
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="form-group <?php echo (form_error('authorities[3]'))?'has-error':'';?>">
                        <label class="label-authority"><b>Gerente / Jefe de Area</b> <span>*</span></label>
                        <select name="authorities[3]" class="form-control">
                          <option value="">Seleccione</option>
                          <?php foreach ($authorities3 as $row_authority): ?>
                            <?php
                              $authority = $request_authorities3[0]; 
                              $authorization3_value = $authority->personal_name . ',' . $authority->personal_email; 
                              $authority3_value = $row_authority->name . ',' . $row_authority->email;
                            ?>
                            <option value="<?php echo $authority3_value; ?>" <?php echo $row_authority->email == $authority->personal_email ? 'selected="selected"' : ''; ?>>
                                <?php echo $row_authority->name; ?>    
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <!-- <div class="form-group <?php echo (form_error('authorities[4]'))?'has-error':'';?>">
                        <label class="label-authority">
                          <b>
                            GERENTE DE GESTIÓN HUMANA / 
                          JEFE DE RECLUTAMIENTO Y SELECCIÓN / <br />
                          COORDINADOR DE RECLUTAMIENTO Y SELECCIÓN MARKETING 
                          </b> 
                          <span>*</span>
                        </label>
                        <select name="authorities[4]" class="form-control">
                          <option value="">Seleccione</option>
                          <?php foreach ($authorities4 as $row_authority): ?>
                            <?php
                              $authority = $request_authorities4[0]; 
                              $authorization4_value = $authority->personal_name . ',' . $authority->personal_email; 
                              $authority4_value = $row_authority->name . ',' . $row_authority->email;
                            ?>
                            <option value="<?php echo $authority4_value; ?>" <?php echo $row_authority->email == $authority->personal_email ? 'selected="selected"' : ''; ?>>
                               <?php echo $row_authority->name; ?>    
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div> -->
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
</div>
<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>

<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/tooltip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>  
<script type="text/javascript" src="<?php echo base_url('public/js/select2/select2.min.js');?>"></script>
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

$(document).ready(function() {

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

  function getDataMOFs(area_id) {

    var data = {
      area_id: area_id
    };

    $.post("<?php echo base_url('employer/staff_request/staff_requests/get_data_mofs'); ?>", data, function(data) {
      var mofs = data.mofs;
      $( "#select_mof" ).empty().append('<option value="">Seleccione</option>');
      $( "#select_mof" ).prop('disabled', true);
      
      $.each(mofs, function(i, mof) {
        $( "#select_mof" ).append('<option value="' + mof.mof_id + '">' + (mof.code + ' - '+ mof.job_title) + '</option>');
      });      
    }, 'json')
    .fail(function(){
      alert('Ha ocurrido un error!');
    }).always(function() {
      $( "#select_mof" ).prop('disabled', false);
    });
  }

  function cleanErrorReplaceEmployee() {
    var contentDiv = $( "#selector-replace-employee" ).closest(".input-group");
    contentDiv.siblings(".info-error").remove();
    contentDiv.removeClass("has-error");
  }

  function getConsultants()
  {
      var url = "<?php echo site_url('general/overall_web_services/get_consultants'); ?>";

      $( "#consultant" ).html('<option value="">Cargando...</option>').prop('disabled', true);
      
      $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        async:false,
        success: function(data) {
          var consultants =  data.MESSAGE == 'OK' ? data.CONSULTORA : [];      
          $.each(consultants, function(i, row) {
            $( "#consultant" ).append('<option data-no_cia="' + row.NO_CIA + '" value="' + row.CONSULTORA + '">' + row.CONSULTORA + '</option>');
          });

          $( "#consultant" ).val($( "#consultant" ).data('value'));
        } 
      })
      .fail(function() {
        alert('¡Ha ocurrido un error al tratar de listar las consultoras!');
      }).always(function() {
        $( "#consultant" ).find("option:eq(0)").text("Seleccione");
        $( "#consultant" ).prop('disabled', false);
      }); 
  }

  function getClientsCompany()
  {
    var url = "<?php echo site_url('general/overall_web_services/get_clients_company'); ?>";
    var data = {
        no_cia: $( "#consultant option:selected" ).data('no_cia'),
        uni_neg: $( "#business_unit option:selected" ).data('uni_neg')
      }

      $( "#client_company" ).html('<option value="">Cargando...</option>').prop('disabled', true);
      
      $.ajax({
        type: 'POST',
        data: data,
        url: url,
        dataType: 'json',
        async:false,
        success: function(data){
          var clients =  data.MESSAGE == 'OK' ? data.CLIENTE : [];
          
          $.each(clients, function(i, row) {
            $( "#client_company" ).append('<option data-cod_clie="' + row.COD_CLIE + '" value="' + row.CLIENTE + '">' + row.CLIENTE + '</option>');
          });

          $( "#client_company" ).val($( "#client_company" ).data('value'));
        }
      })
      .fail(function() {
        alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
      }).always(function() {
        $( "#client_company" ).find("option:eq(0)").text("Seleccione");
        $( "#client_company" ).prop('disabled', false);
      });  
  }
  
  function getCostCenters()
  {
    var data  = {
        no_cia: $( "#consultant option:selected" ).data('no_cia'),
        uni_neg: $( "#business_unit option:selected" ).data('uni_neg'),
        cod_clie: $( "#client_company option:selected" ).data('cod_clie'),
    }

    var url = "<?php echo site_url('general/overall_web_services/get_cost_centers'); ?>";
     $( "#cost_center" ).html('<option value="">Cargando...</option>').prop('disabled', true); 

      $.ajax({
        type: 'POST',
        data: data,
        url: url,
        dataType: 'json',
        async:false,
        success: function(data){
          var cost_centers = data.MESSAGE == 'OK' ? data.CENTROCOSTO : [];

          $.each(cost_centers, function(i, row) {
            $( "#cost_center" ).append('<option value="' + row.COD_CCOSTO + '">' + row.COD_CCOSTO + '</option>');
          });

          $( "#cost_center" ).val($( "#cost_center" ).data('value'));     
        }
      })
      .fail(function() {
        alert('¡Ha ocurrido un error al tratar de listar los centro de costo!');
      }).always(function() {
        $( "#cost_center" ).find("option:eq(0)").text("Seleccione");
        $( "#cost_center" ).prop('disabled', false);
      });         
  }

  function getAuthoritiesDR()
  {
    var data  = {
        business_unit_name : $( "#business_unit" ).val(),
    }

    var url = "<?php echo base_url('employer/staff_request/staff_requests/get_authorities_DR_by_business_unit'); ?>";
   
    $.post(url, data, function(data) {
      var authorities = data.authorities;
      $( "#table-authorities-DR tbody" ).empty();

      $.each(authorities, function(i, row_authority) {
        var authorityInput = '<input type="hidden" name="authorities[1][]" value="' + row_authority.name + ',' + row_authority.email + '" >';
        $( "#table-authorities-DR tbody" ).append('<tr><td>' + row_authority.name + authorityInput + '</td></tr>');
      });      
    }, 'json')
    .fail(function() {
      alert('Ha ocurrido un error!');
    }).always(function() {});   
  }

  $( "#form-create-request" ).submit(function(e) {
    e.preventDefault();
    if (!window.confirm("¿Está seguro de guardar los datos de la solicitudi?")) {
      return;
    }
   
    $( ".has-error" ).removeClass("has-error");
    $( ".info-error").remove();

    var url = $(this).prop('action');
    var data = $(this).serialize();

    $.post(url, data, function(response) {

      if (!response.success) {
        showErrors(response.errors);
        return;
      }

      window.location = "<?php echo site_url('employer/staff_request/staff_requests/show/'); ?>" + response.request_id;

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
        alert('¡Ningún resultado encontrado!');
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
  $( "#start_date_work" ).datepicker({
    changeMonth: true,        
    dateFormat: "dd/mm/yy",
    dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
    dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
    monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
    monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec" ],
  });

 $( "#location" ).select2();
 $( "#selector-replace-employee" ).select2();

  $( "#consultant" ).change(function() {
    $( "#business_unit" ).val("");
  });

  $( "#client_company" ).change(function() {
    getCostCenters()
  });

  $( "#business_unit" ).change(function() {
    getClientsCompany();
    getAuthoritiesDR();
  });
  
  $( "#business_unit" ).change(function() {
    getClientsCompany();
  });

  getConsultants();
  getClientsCompany();
  getCostCenters();
  
  $( "#business_unit" ).attr({disabled: false});

});
</script>
</body>
</html>