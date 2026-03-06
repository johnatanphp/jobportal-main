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
        <div class="titlehead">
          <a href="<?php echo site_url('employer/staff_request/my_staff_requests'); ?>" style="color:#fff;">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </a>
          Editar solicitud externa
        </div>

        <div class="row"> 
          <?php echo form_open_multipart('',array('id' => 'form-edit-request'));?>
          <div class="col-md-12">
            <div class="formint">    
              <div id="step-counter" style="display: none;">
                <ul>
                  <li><a rel="tooltip" title="Paso 1: Datos de la solicitud">1</a></li>
                  <li><a rel="tooltip" title="Paso 2: Descripción del puesto">2</a></li>
                  <li><a rel="tooltip" title="Paso 3: Contratación">3</a></li>
                  <li><a rel="tooltip" title="Paso 4: Beneficios adicionales">4</a></li>
                  <li><a rel="tooltip" title="Paso 5: Recursos">5</a></li>
                  <li><a rel="tooltip" title="Paso 6: Requisitos del puesto">6</a></li>
                  <li><a rel="tooltip" title="Paso 7: Conocimientos">7</a></li>
                  <li><a rel="tooltip" title="Paso 8: Informática">8</a></li>
                  <li><a rel="tooltip" title="Paso 9: Idiomas">9</a></li>
                  <li><a rel="tooltip" title="Paso 10: Funciones específicas del puesto">10</a></li>
                  <li><a rel="tooltip" title="Paso 11: Competencias">11</a></li>
                  <li><a rel="tooltip" title="Paso 12: Comentarios adicionales del cliente">12</a></li>
                </ul>
              </div>
              <div id="wrapper-steps">
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_data'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_job_descriptions'); ?>
                </div>
                
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_hiring'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_additional_benefits'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_resources'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_job_requirements'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_knowledges'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_computing'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_languages'); ?>
                </div>  

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_job_functions'); ?>
                </div>
                
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_competences'); ?>
                </div>

                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_edit_sr_external/section_additional_comments'); ?>
                </div>
              
                <div class="content-step">
                  <div id="form-confirm">
                    <div class="step-title">
                      Confirmación
                    </div>
                    <div class="step-inputs">
                      <div style="max-width: 520px;margin: 0 auto;text-align: center;">
                        <h4 style="line-height: 1.5;">
                          Completaste todos los pasos, si estás seguro haz clic en 'Guardar'.
                        </h4> 
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div align="center" style="margin-top: 30px;">
                <input id="prev-step-request" type="button" value="Atrás" class="btn btn-primary" />
                <input id="next-step-request" type="submit" value="Siguiente" class="btn btn-primary" />
                <input id="create-request" type="submit" value="Guardar" class="btn btn-primary" />
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

<div>
  <?php if ($this->config->item('system_internal') == 'sap'): ?>
    <?php $this->load->view('employer/staff_request/modal/select_eecc_sap'); ?>
  <?php endif; ?>
  <?php if ($this->config->item('system_internal') == 'integrado'): ?>
    <?php $this->load->view('employer/staff_request/modal/select_eecc_eplani'); ?>
  <?php endif; ?>
</div>

<script src="<?php echo base_url('public/js/tooltip.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>  
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

<script id="tpl-add-functions" type="text/template">
    <tr>
      <td>
        <input type="text" name="functions[]" class="form-control" value="{{value}}">
      </td>
      <td>
        <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
          <i class="glyphicon glyphicon-remove"></i>
        </button>
      </td>
    </tr>
</script>

<script id="tpl-add-fixed-competence" type="text/template">
    <tr class="row-fixed-competence">
      <td colspan="2">
        {{competenceName}}
      </td>
    </tr>
</script>

<script id="tpl-add-additional-competence" type="text/template">
    <tr class="row-additional-competence">
      <td>
        <input type="text" name="additional_competences[]" class="form-control" value="{{value}}">
      </td>
      <td>
        <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
          <i class="glyphicon glyphicon-remove"></i>
        </button>
      </td>
    </tr>
</script>

<script id="tpl-add-application" type="text/template">
  <tr>
    <td>
      <input type="text" name="computing[{{index}}][name]" class="form-control" value="{{name}}">
      <input type="hidden" name="computing[{{index}}][type]" class="form-control" value="application">
    </td>
    <td>
      <select id="computing-level-{{index}}" name="computing[{{index}}][level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
    </td>
  </tr>
</script>

<script id="tpl-add-programming-language" type="text/template">
  <tr>
    <td>
      <input type="text" name="computing[{{index}}][name]" class="form-control">
      <input type="hidden" name="computing[{{index}}][type]" class="form-control" value="programming_language">
    </td>
    <td>
      <select name="computing[{{index}}][level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
    </td>
  </tr>
</script>

<script id="tpl-add-languages" type="text/template">
  <tr>
    <td>
      <input type="text" name="languages[{{index}}][name]" class="form-control" value="{{name}}">
    </td>
    <td>
      <select id="languages-reading-level-{{index}}" name="languages[{{index}}][reading_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <select id="languages-speaking-level-{{index}}" name="languages[{{index}}][speaking_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <select id="languages-writing-level-{{index}}" name="languages[{{index}}][writing_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
      </select>
    </td>
    <td>
      <button onclick="$(this).closest('tr').remove();" class="btn-remove-item">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
    </td>
  </tr>
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
      <input type="checkbox" name="additional_benefits[{{benefit_id}}][checked]" checked value="true" class="benefit-checked">
    </td>
    <td width="15%">
      <label>Especificar</label>
    </td>
    <td width="25%">
      <input id="benefit-detail-{{benefit_id}}" type="text" name="additional_benefits[{{benefit_id}}][detail]" class="form-control benefit-detail" value="{{ minimum }}">
    </td>
  </tr>
</script>
<?php $this->load->view('employer/staff_request/scripts/edit_sr_external'); ?>
</body>
</html>