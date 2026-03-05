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
        <div class="titlehead">Crear solicitud interna</div>
        <div class="row"> 
          <?php echo form_open_multipart('', array('id' => 'form-create-request'));?>
          <div class="col-md-12">
            <div class="formint">
              <div id="step-counter" style="display: none;">
                <ul>
                  <li><a rel="tooltip" title="Paso 1: Datos de la solicitud">1</a> </li>
                  <li><a rel="tooltip" title="Paso 2: Seleccione Área y Plantilla">2</a> </li>
                  <li><a rel="tooltip" title="Paso 3: Descripción del puesto">3</a></li>
                  <li><a rel="tooltip" title="Paso 4: Contratación">4</a></li>
                  <li><a rel="tooltip" title="Paso 5: Beneficios adicionales">5</a></li>
                  <li><a rel="tooltip" title="Paso 6: Recursos">6</a></li>
                  <li><a rel="tooltip" title="Paso 7: Autoridades">7</a></li>
                </ul>
              </div>
              <div id="wrapper-steps">
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_sr_internal/section_data'); ?>
                </div>
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_sr_internal/section_mof'); ?>
                </div>
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_sr_internal/section_job_description'); ?>
                </div>
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_sr_internal/section_hiring'); ?>
                </div>
                <div class="content-step">
                  <?php $this->load->view(
                    'employer/staff_request/section_sr_internal/section_additional_benefits'
                  ); ?>
                </div>
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_sr_internal/section_resources'); ?>
                </div>
                <div class="content-step">
                  <?php $this->load->view('employer/staff_request/section_sr_internal/section_authorities'); ?>
                </div>
                
                <div class="content-step">
                  <div id="form-confirm">
                    <div class="step-title">
                      Confirmación
                    </div>
                    <div class="step-inputs">
                      <div style="max-width: 520px;margin: 0 auto;text-align: center;">
                        <h4 style="line-height: 1.5;">Completaste todos los pasos, si estás seguro de crear la solicitud haz clic en 'Crear solicitud'.</h4> 
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
<?php $this->load->view('employer/staff_request/modal/clone_loading'); ?>
<?php $this->load->view('employer/staff_request/scripts/create_sr_internal'); ?>
</body>
</html>