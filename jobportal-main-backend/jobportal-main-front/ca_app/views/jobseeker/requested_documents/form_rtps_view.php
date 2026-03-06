<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
  body {
    font-family: 'Inter', sans-serif;
    margin: 0;
  }
  .container-steps {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 10px 10px 0px 0px;
    padding: 20px;
    background-color: #fff;
    min-height: 366px;
  }

  .container-steps h2 {
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: 20px;
    line-height: 24.2px;
    margin: 0 0 10px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #ccc;
  }

  .details {
    display: grid;
    grid-template-columns: 1fr 2fr;
    grid-gap: 10px 20px;
    align-items: center;
  }

  .details p {
    margin: 0;
    font-size: 14px;
    line-height: 18px;
  }

  .input-group {
    display: flex;
    width: 100%;
    margin-bottom: 14px;
  }

  .input-group p {
    margin: 0;
    font-size: 14px;
    line-height: 18px;
    flex-shrink: 0;
  }

  .input-group span {
    color: red;
  }

  .form-group {
    display: flex;
    align-items: center;
  }

  .form-group label {
    margin-right: 15px;
    display: flex;
    align-items: center;
  }

  .form-group input {
    margin-right: 5px;
  }

  input, select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 14px;
    line-height: 18px;
  }

  .buttons {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 0px 0px 10px 10px;
    padding: 15px;
  }

  button {
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: 14px;
    border-radius: 5px;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
  }
  
  .buttons button {
      margin-left: 10px;
  }

  #prevBtn {
    background-color: #f0f0f0;
    color: #333;
    border: 1px solid #ccc;
  }

  #prevBtn:hover {
    background-color: #e0e0e0;
  }

  #nextBtn, #submit_button {
    background-color: #007bff;
    color: white;
  }

  #nextBtn:hover, #submit_button:hover {
    background-color: #0056b3;
  }

  .agregar-btn {
    background-color: white;
    border: 1px solid blue;
    color: blue;
    border-radius: 5px;
    padding: 10px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
  }

  .agregar-btn:hover {
    background-color: #f0f0f0;
  }

  .ui-autocomplete { 
    z-index: 99999999; 
  }

  .modal-dialog .formwraper .input-group-addon {
    width: 48%;
  }

  .modal-dialog .formwraper {
    border: none;
  }

  #check_disability {
    vertical-align: middle;
    margin: 0 5px 0 0;
  }

  .content-conditions-policy {
    font-size: 13px;
    padding: 15px;
    margin-top: 20px;
    background: #eee;
    color: #333;
    text-align: center;
  }

  .content-conditions-policy a {
    text-decoration: underline;
  }

  .info-rightful-claimants {
    padding: 10px 5px;
    background: #eee;
    border-bottom: 1px solid #ccc;
  }

  .info-rightful-claimants table td {
    padding: 3px 15px;
  }

  .info-rightful-claimants ul li {
    font-size: 13px;
    line-height: 1.5;
    color: #444;
    padding: 4px 0;
  }

  .remove-rightful-claimants {
    background: #eee;
    border-radius: 5px;
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

  .content-rightful-claimants {
    padding: 15px 0;
  }

  .content-add-rightful-claimants {
    text-align: right;
    padding: 10px 0;
  }

  .formwraper .formint .btn-rc-action {
    background: #e0e0e0;
    border: 1px solid #bbb;
  }

  .btn-back {
    color: #005da4;
    background: #ffffff;
  }

  .step {
    display: none;
  }

  .step.active {
    display: block;
  }

  .step-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .step-indicator div {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color: #ccc;
    margin-right: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  .step-indicator div::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
  }

  .step-indicator div.completed::after {
    background-color: green;
  }

  .step-indicator div.active::after {
    background-color: blue;
  }

  .step-indicator div.future::after {
    background-color: #ccc;
  }

  .step-indicator .line {
    flex: 1;
    height: 2px;
    background-color: #ccc;
    position: relative;
    top: 7px;
  }

  .step-indicator .line.completed {
    background-color: green;
  }

  .title-container {
      text-align: center;
      margin: 20px 0;
  }

  .title-container h1 {
      font-family: 'Inter', sans-serif;
      font-weight: 700;
      font-size: 20px;
      line-height: 24.2px;
      margin: 0;
  }

  .subtitle {
      font-family: 'Inter', sans-serif;
      font-weight: 400;
      font-size: 14px;
      line-height: 18px;
      color: #666;
      margin: 0;
  }

  .input-group p:first-child {
      margin-right: 50px;
  }

  .text-link {
    color: #0D6EFD;
    font-size: 13px;
    margin-bottom: 20px;
  }

  #additional-info .form-group {
    display: flex;
    align-items: center;
    margin-top: 20px;
  }

  #additional-info .declaration-label {
    font-size: 12px;
    line-height: 14px;
    display: flex;
    align-items: center;
    margin: 0;
  }

  #declaration-checkbox {
    margin-right: 10px;
  }

  .formwraper {
    width: 100%;
    padding: 0px 10px;
  }

  .formwraper .input-group {
    width: 100% !important;
  }

  input.invalid, select.invalid, textarea.invalid {
    border-color: red;
  }

  .form-check {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .form-check-label {
    font-size: 14px;
    line-height: 18px;
    margin-top: 0.8rem;
  }

  .beneficiaries {
    border-bottom: 1px solid #e5e5e5 !important;
  }

  .custom-btn {
    background-color: #ffffff; /* Fondo blanco */
    color: #007bff; /* Color del texto azul */
    border: 1px solid #007bff; /* Borde azul */
    border-radius: 5px; /* Bordes redondeados */
    padding: 5px 15px; /* Relleno */
    font-size: 14px; /* Tamaño de fuente */
    font-family: 'Inter', sans-serif; /* Fuente */
    font-weight: bold; /* Negrita */
    cursor: pointer; /* Manito */
    transition: background-color 0.3s, color 0.3s; /* Transición suave */
  }

  .custom-btn:hover {
    background-color: #007bff; /* Fondo azul al pasar el ratón */
    color: #ffffff; /* Texto blanco al pasar el ratón */
  }

  .custom-modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 10px 20px;
    border-top: 1px solid #e5e5e5;
    border-radius: 0 0 10px 10px;
    background-color: #f9f9f9;
  }

  .custom-modal-footer .btn {
    border-radius: 5px;
    font-size: 14px;
    padding: 10px 20px;
    margin-left: 10px;
  }

  .custom-modal-footer .btn-secondary {
    background-color: #6c757d;
    color: #fff;
    border: none;
  }

  .custom-modal-footer .btn-secondary:hover {
    background-color: #5a6268;
  }

  .custom-modal-footer .btn-primary {
    background-color: #007bff;
    color: #fff;
    border: none;
  }

  .custom-modal-footer .btn-primary:hover {
    background-color: #0056b3;
  }

  .head-table {
    background-color: #E9ECEF !important;
    border: 1px, 0px, 1px, 0px solid #BCBCBC !important;
  }

  .hidden {
    display: none;
  }

  .radio-change-pension {
    padding-left: 50px;
  }

  @media (max-width: 600px) {
    .container-steps {
        min-height: auto;
        width: 100%;
        padding: 10px;
    }

    .details {
        grid-template-columns: 1fr;
    }

    .input-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .input-group p {
        margin-bottom: 4px;
    }

    .col-12 {
      width: 100% !important;
    }
    .select-kinship {
      width: 100% !important;
    }
    .select2 {
      width: 100% !important;
    }
    .form-check-input {
      width: 30px;
      margin-right: 14px;
    }
    .form-check {
      justify-content: left;
    }
    .document-view {
      margin-left: 20px;
    }
    .radio-change-pension {
      padding-left: 18px;
      padding-top: 20px;
    }
  }

  .form-group input[name="i_have_account_number"] {
    width: 20px;
  }

  .iti--inline-dropdown {
    width: 100%;
  }

  .iti__country-name, .iti__dial-code {
    color: #333 !important;
  }
  
  .load-file { 
      margin: 0;
  }
  
  .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    white-space: nowrap;
    vertical-align: middle;
  }
  
    @media (min-width: 768px) {
        .table-responsive {
            overflow-y: hidden;
        }
    }

</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<!--Header-->
<?php 
  $this->load->view('common/header');
?>
<!--/Header-->
<div class="container detailinfo">
  <div class="row"> <?php echo form_open_multipart('jobseeker/form_rtps/index/' . $contract_document_type->id . '/' . $process->id, array('name' => 'form_rtps', 'id' => 'form_rtps', 'onSubmit' => ''));?>
  
    <div <?php echo $this->session->userdata('menu') != '0' ? 'class="col-md-3"' : 'class="col-md-2"'; ?>>
      <div class="dashiconwrp">
        <?php if ($this->session->userdata('menu') != '0'): ?>
          <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-md-9">  
      <?php echo $this->session->flashdata('msg'); ?>
    
      <div id="container-form-rtps">
        <div style="color: #0D6EFD; font-size: 14px; font-family: Inter; font-weight: 600; line-height: 16px; word-wrap: break-word">
          <a href="#" class="_link-back">
            <span class="glyphicon glyphicon-menu-left"></span><i class="bi bi-chevron-left"></i>
            Volver
          </a>
        </div>
        <div class="title-container">
          <h1><?php e($contract_document_type->name); ?></h1>
          <p class="subtitle">Ingrese los datos solicitados para proceder con la firma digital</p>
        </div>
        <div id="step-indicator" class="step-indicator"></div>
            <div class="step active">
              <div class="container-steps">
                <h2>Datos personales</h2>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Documento <span></span></p>
                  <p class="col-12 col-sm-9">
                    <?php echo document_type_text($jobseeker->document_type) . ' - ' . $jobseeker->document_number; ?>
                  </p>
                </div>
              
                <div class="input-group row">
                  <p class="col-12 col-sm-3">Email <span></span></p>
                  <p class="col-12 col-sm-9"><?php echo $jobseeker->email; ?></p>
                </div>
                            
                <div class="input-group row">
                  <p class="col-12 col-sm-3">Ha trabajado para overall <span></span></p>
                  <p class="col-12 col-sm-9">
                    <?php echo $worked_in_overall ? 'SI' : 'NO'; ?>
                  </p>
                </div>

                <div class="input-group row <?php echo (form_error('first_name'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Primer nombre <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input id="first_name" name="first_name" type="text" class="form-control" placeholder="Nombre" value="<?php echo @$form_rtps->first_name ? $form_rtps->first_name : $jobseeker->first_name; ?>" maxlength="40" required>
                  </div>
                  <?php echo form_error('first_name'); ?> 
                </div>
                
                <div class="input-group row <?php echo (form_error('second_name'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Segundo nombre <span></span></p>
                  <div class="col-12 col-sm-9">
                    <input id="second_name" name="second_name" type="text" class="form-control" placeholder="Segundo nombre" value="<?php echo @$form_rtps->second_name; ?>" maxlength="40">
                  </div>
                  <?php echo form_error('second_name'); ?> 
                </div>
                
                <div class="input-group row <?php echo (form_error('third_name'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Tercer nombres <span></span></p>
                  <div class="col-12 col-sm-9">
                    <input id="third_name" name="third_name" type="text" class="form-control" placeholder="Tercer nombre" value="<?php echo @$form_rtps->third_name; ?>" maxlength="40">
                  </div>
                  <?php echo form_error('third_name'); ?> 
                </div>

                <div class="input-group row <?php echo (form_error('paternal_last_name'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Apellido paterno <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input id="paternal_last_name" name="paternal_last_name" type="text" class="form-control" placeholder="Apellido paterno" value="<?php echo $jobseeker->paternal_last_name; ?>" maxlength="30" required>
                  </div>
                  <?php echo form_error('paternal_last_name'); ?> 
                </div>

                <div class="input-group row <?php echo (form_error('maternal_last_name'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Apellido materno <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input id="maternal_last_name" name="maternal_last_name" type="text" class="form-control" placeholder="Apellido materno" value="<?php echo $jobseeker->maternal_last_name; ?>" maxlength="30" required>
                  </div>
                  <?php echo form_error('maternal_last_name'); ?> 
                </div>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Fecha de nacimiento <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input type="date" 
                           name="dob" 
                           class="form-control" 
                           value="<?php echo $jobseeker->dob ? $jobseeker->dob : ''; ?>" 
                           max="2014-12-31" required >
                  </div>
                </div>

                <div class="input-group row <?php echo (form_error('gender'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Sexo <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="gender" required>
                      <option value="">Seleccione</option>

                      <?php foreach ($genders as $row_gender): ?>
                        <option value="<?php echo $row_gender->id; ?>" <?php echo ($jobseeker->gender == $row_gender->id) ? 'selected' : ''; ?>>
                          <?php e($row_gender->name); ?>
                        </option>
                      <?php endforeach; ?>

                    </select>
                  </div>
                  <?php echo form_error('gender'); ?>
                </div>

                <div class="input-group row <?php echo (form_error('civil_status')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Estado civil<span> *</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="civil_status" required>
                      <option value="">Seleccione</option>

                      <?php foreach ($civil_status as $row_civil_status): ?>
                        <option value="<?php echo $row_civil_status->id; ?>" <?php echo ($jobseeker->civil_status == $row_civil_status->id) ? 'selected' : ''; ?>>
                          <?php e($row_civil_status->name); ?>
                        </option>
                      <?php endforeach; ?>   
                    </select>
                  </div>
                  <?php echo form_error('civil_status'); ?>
                </div>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Discapacidad <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="disability_type" id="disability_type" required>
                      <option value="">Seleccione</option>
                      <option value="0" <?php echo !$jobseeker->disability ? 'selected' : ''; ?>>No presento discapacidad</option>
                      <?php foreach ($disabilities as $row_disability): ?>
                        <option value="<?php echo $row_disability->id; ?>" <?php echo ($jobseeker->disability == $row_disability->id) ? 'selected' : ''; ?>>
                          <?php e($row_disability->name); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                   </div>
                  <?php echo form_error('disability_type'); ?>
                </div>

                <div class="input-group row <?php echo (form_error('domiciled'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Domiciliado</p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="domiciled" id="domiciled">
                      <?php $domiciled = @$form_rtps->domiciled; ?>
                      <option value="">Seleccione</option>
                      <option value="1" <?php echo $domiciled == '1' ? 'selected="selected"' : ''; ?>>Si</option>
                      <option value="0" <?php echo $domiciled == '0' ? 'selected="selected"' : ''; ?>>No</option>
                    </select>
                  </div>
                  <?php echo form_error('domiciled'); ?>
                </div>

                <div class="input-group row <?php echo (form_error('full_mobile_phone_number'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Teléfono móvil <span>*</span></p>
                  <?php 
                    $mobile = phone_number_format($jobseeker->mobile); 
                  ?>
                  <div class="col-12 col-sm-9">
                    <input name="mobile" 
                           type="text" 
                           class="form-control" 
                           value="<?php echo $mobile; ?>" 
                           maxlength="15" 
                           required 
                           style="width: 100%;"/>  
                  </div>
                  <?php echo form_error('full_mobile_phone_number'); ?>
                </div>

                <!-- Se debe mostrar para formulario completo -->
                <!-- <div class="input-group row <?php echo (form_error('nationality')) ? 'has-error' : '';?>">
                  <p>País nacionalidad <span>*</span></p>
                  <select class="form-control" name="nationality">
                    <option value="">Seleccione</option>
                    <?php $nationality = $jobseeker->nationality; ?>
                    <?php foreach ($result_countries as $row_country): ?>
                      <?php $selected = $nationality == $row_country->ID ? 'selected' : ''; ?>
                      <option value="<?php echo $row_country->ID; ?>" <?php echo $selected; ?>>
                        <?php echo $row_country->country_name; ?>    
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('nationality'); ?>
                </div> -->
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Domicilio actual</h2>

                <div class="input-group row <?php echo (form_error('current_country')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">País <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select id="current_country" class="form-control" name="current_country" required style="width: 100%;">
                      <option value="">Seleccione</option>
                      <?php $current_country = $jobseeker->country; ?>
                      <?php foreach ($result_countries as $row_country): ?>
                        <?php $selected = $current_country == $row_country->ID ? 'selected' : ''; ?>
                        <option value="<?php echo $row_country->ID; ?>" <?php echo $selected; ?>>
                          <?php echo $row_country->country_name; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('current_country'); ?>
                </div>

                <?php
                  $part_ubigeo = explode(',', (string)$jobseeker->city);
                  $department = trim((string)@$part_ubigeo[0]);
                  $province = trim((string)@$part_ubigeo[1]);
                  $district = trim((string)@$part_ubigeo[2]);
                ?>

                <div id="content-current-department" class="input-group row <?php echo (form_error('current_department')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Departamento <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select id="current_department" class="form-control" name="current_department" required>
                      <option value="">Seleccione</option>
                      <?php $current_department = $department; ?>
                      <?php foreach ($result_departments as $row_ubigeo): ?>
                        <?php $selected = $current_department == $row_ubigeo->department ? 'selected' : ''; ?>       
                        <option value="<?php echo $row_ubigeo->department; ?>" <?php echo $selected; ?>>
                          <?php echo $row_ubigeo->department; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('current_department'); ?>
                </div>

                <div id="content-current-province" class="input-group row <?php echo (form_error('current_province')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Provincia <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select id="current_province" class="form-control" name="current_province" required>
                      <option value="">Seleccione</option>
                      <?php $current_province = $province; ?>
                      <?php foreach ($provinces as $row_ubigeo): ?>
                        <?php $selected = $current_province == $row_ubigeo->province ? 'selected' : ''; ?>
                        <option value="<?php echo $row_ubigeo->province; ?>" <?php echo $selected; ?>>
                          <?php echo $row_ubigeo->province; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('current_province'); ?>
                </div>

                <div id="content-current-district" class="input-group row <?php echo (form_error('born_district')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Distrito <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select id="current_district" class="form-control" name="current_district" required>
                      <option value="">Seleccione</option>
                      <?php $current_district = $district; ?>
                      <?php foreach ($districts as $row_ubigeo): ?>
                        <?php $selected = $current_district == $row_ubigeo->district ? 'selected' : ''; ?>
                        <option value="<?php echo $row_ubigeo->district; ?>" <?php echo $selected; ?>>
                          <?php echo $row_ubigeo->district; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('current_district'); ?>
                </div>
                 <!-- Se debe mostrar para formulario completo -->
                      
                <!-- <div id="content-current-city" class="input-group row">
                  <p>Lugar <span></span></p>
                  <input id="city" name="city" type="text" class="form-control" value="<?php echo $jobseeker->country != '56' ? $jobseeker->city : ''; ?>">
                </div>

                <div class="input-group row">
                  <p>Vía <span>*</span></p>
                  <select class="form-control" name="way_id">
                    <option value="">Seleccione</option>
                    
                    <?php foreach ($result_ways as $row): ?>
                      <?php $selected = $jobseeker->way_id == $row->id ? 'selected' : ''; ?>
                      <option value="<?php echo $row->id; ?>" <?php echo $selected; ?>>
                        <?php echo $row->name; ?>    
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php echo form_error('way_id'); ?>
                </div> -->

                <div class="input-group row <?php echo (form_error('present_address')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Dirección <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <textarea id="present-address" rows="3" class="form-control" required name="present_address"><?php echo set_value('present_address') ? set_value('present_address') : $jobseeker->present_address; ?></textarea>
                    <span style="font-size:12px;color:red;display:none;" class="present-address-alert-length"></span>
                  </div>
                </div>

                <!-- <div class="input-group row">
                  <p>Número <span>*</span></p>
                  <input type="number" class="form-control" name="address_number" value="<?php echo $jobseeker->address_number; ?>">
                </div>

                <div class="input-group row">
                  <p>Interior <span></span></p>
                  <input type="text" class="form-control" name="domicile_interior" value="<?php echo $jobseeker->domicile_interior; ?>" maxlength="20">
                </div> -->
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Lugar de nacimiento</h2>

                <div class="input-group row <?php echo (form_error('born_country')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">País <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select id="born-country" name="born_country" class="form-control" required>
                      <option value="">Seleccione</option>
                      <?php $born_country = @$form_rtps->born_country; ?>
                      <?php foreach ($result_countries as $row_country): ?>
                        <?php $selected = $born_country == $row_country->ID ? 'selected' : ''; ?>
                        <option value="<?php echo $row_country->ID; ?>" <?php echo $selected; ?>>
                          <?php echo $row_country->country_name; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('born_country'); ?>
                </div>

                <div id="content-born-department" class="input-group row <?php echo (form_error('born_department')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Departamento <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="born_department" id="born_department" required>
                      <option value="">Seleccione</option>
                      <?php $born_department = @$form_rtps->born_department; ?>
                      <?php foreach ($result_departments as $row_ubigeo): ?>
                        <?php $selected = $born_department == $row_ubigeo->department ? 'selected' : ''; ?>       
                        <option value="<?php echo $row_ubigeo->department; ?>" <?php echo $selected; ?>>
                          <?php echo $row_ubigeo->department; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('born_department'); ?>
                </div>

                <div id="content-born-province" class="input-group row <?php echo (form_error('born_province')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Provincia <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="born_province" id="born_province" required>
                      <option value="">Seleccione</option>
                      <?php $born_province = @$form_rtps->born_province; ?>
                      <?php foreach ($result_provinces as $row_ubigeo): ?>
                        <?php $selected = $born_province == $row_ubigeo->province ? 'selected' : ''; ?>
                        <option value="<?php echo $row_ubigeo->province; ?>" <?php echo $selected; ?>>
                          <?php echo $row_ubigeo->province; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('born_province'); ?>
                </div>

                <div id="content-born-district" class="input-group row <?php echo (form_error('born_district')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Distrito <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="born_district" id="born_district" required>
                      <option value="">Seleccione</option>
                      <?php $born_district = @$form_rtps->born_district; ?>
                      <?php foreach ($result_districts as $row_ubigeo): ?>
                        <?php $selected = $born_district == $row_ubigeo->district ? 'selected' : ''; ?>
                        <option value="<?php echo $row_ubigeo->district; ?>" <?php echo $selected; ?>>
                          <?php echo $row_ubigeo->district; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <?php echo form_error('born_district'); ?>
                </div>

                <div class="input-group row <?php echo (form_error('place_birth'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Lugar de nacimiento <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input name="place_birth" required type="text" class="form-control" id="place_birth" placeholder="Lugar de nacimiento" value="<?php echo @$form_rtps->place_birth; ?>">
                  </div>
                  <?php echo form_error('place_birth'); ?> 
                </div>
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Redes</h2>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Linkedin</p>
                  <div class="col-12 col-sm-9">
                    <input name="linkedin" type="text" class="form-control" value="<?php echo $jobseeker->linkedin; ?>" maxlength="70" placeholder="https://www.linkedin.com/in/alguien">
                  </div>
                </div>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Facebook</p>
                  <div class="col-12 col-sm-9">
                    <input name="facebook" type="text" class="form-control" value="<?php echo $jobseeker->facebook; ?>" maxlength="70" placeholder="https://www.facebook.com/alguien">
                  </div>
                </div>
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Contacto de emergencia</h2>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Parentesco <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select name="emergency_contact_kinship" class="form-control" required>
                      <option value="">Seleccione</option>
                      <?php 
                        $emergency_contact_kinship_list = [
                          'Padre',
                          'Madre',
                          'Hijo(a)',
                          'Tio(a)',
                          'Hermano(a)',
                          'Sobrino(a)',
                          'Cuñado(a)',
                          'Primo(a)',
                          'Conyuge',
                          'Novio(a)',
                          'Amigo(a)'
                        ]; 
                      ?>
                      <?php foreach ($emergency_contact_kinship_list as $contact_kinship_row): ?>
                        <option value="<?php echo $contact_kinship_row; ?>" <?php echo $contact_kinship_row == @$seeker_additional_info->emergency_contact_kinship ? 'selected' : ''; ?>>
                          <?php echo $contact_kinship_row; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                

                <div class="input-group row <?php echo (form_error('emergency_contact_full_mobile_phone_number'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Teléfono <span>*</span></p>
                  <?php 
                    $mobile = phone_number_format(@$seeker_additional_info->emergency_contact_mobile); 
                  ?>
                  
                  <div class="col-12 col-sm-9">
                    <input name="emergency_contact_mobile" 
                           type="text" 
                           class="form-control" 
                           value="<?php echo $mobile; ?>" 
                           maxlength="15" 
                           required />
                  </div>
                  <?php echo form_error('emergency_contact_full_mobile_phone_number'); ?>
                </div>

                <div class="input-group row <?php echo (form_error('emergency_contact_name'))?'has-error':'';?>">
                    <p class="col-12 col-sm-3">Nombre de contacto</p>
                    <div class="col-12 col-sm-9">
                      <input name="emergency_contact_name" type="text" class="form-control" value="<?php echo $seeker_additional_info->emergency_contact_name; ?>" maxlength="20" />
                    </div>
                </div>
                <?php echo form_error('emergency_contact_name'); ?>

              </div>
            </div>
            <div class="step"> 
              <div class="container-steps">
                <h2>Registro de educación</h2>

                <div class="input-group row <?php echo (form_error('level_education')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Nivel educativo <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="level_education" id="level_education" required>
                      <option value="">Seleccione</option>
                      <?php $level_education = @$form_rtps->level_education; ?>
                      <option value="Superior" <?php echo $level_education == 'Superior' ? 'selected' : ''; ?>>Superior</option>
                      <option value="Ténico" <?php echo $level_education == 'Ténico' ? 'selected' : ''; ?>>Técnico</option>
                      <option value="Otros" <?php echo $level_education == 'Otros' ? 'selected' : ''; ?>>Otros</option>
                    </select>
                  </div>
                  <?php echo form_error('level_education'); ?>
                </div>

                <div id="content_degree_obtained_type" class="<?php echo ($level_education == 'Superior' || $level_education == 'Ténico') ? '' : 'hidden'; ?> input-group row <?php echo (form_error('degree_obtained_type')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Grado obtenido <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select class="form-control" name="degree_obtained_type" id="degree_obtained_type" <?php echo $level_education != 'Otros' ? 'required' : ''; ?>>
                      <option value="">Seleccione</option>
                      <?php $degree_obtained_type = @$form_rtps->degree_obtained_type; ?>
                      <option value="Trunco" <?php echo $degree_obtained_type == 'Trunco' ? 'selected' : ''; ?>>Trunco</option>
                      <option value="Encurso" <?php echo $degree_obtained_type == 'Encurso' ? 'selected' : ''; ?>>En curso</option>
                      <option value="Egresado" <?php echo $degree_obtained_type == 'Egresado' ? 'selected' : ''; ?>>Egresado (Bachiller o titulado)</option>
                    </select>
                  </div>
                  <?php echo form_error('degree_obtained_type'); ?>
                </div>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Institución Educativa</p>
                  <div class="col-12 col-sm-9">
                    <input name="degree_obtained_institution" 
                      type="text" 
                      class="form-control" 
                      id="degree_obtained_institution" 
                      placeholder="Institución Educativa"
                      value="<?php echo @$form_rtps->degree_obtained_institution; ?>"
                    >
                  </div>
                </div>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Especialidad o Carrera</p>
                  <div class="col-12 col-sm-9">
                    <input name="specialty" 
                        type="text" 
                        class="form-control" 
                        id="specialty" 
                        placeholder="Especialidad o Carrera"
                        value="<?php echo @$form_rtps->specialty; ?>"
                        >
                    </div>
                </div>

                <div class="input-group row">      
                  <p class="col-12 col-sm-3">Año de egreso</p>
                  <div class="col-12 col-sm-9">
                    <input name="degree_obtained_year" 
                          type="number"
                          min="1950"
                          class="form-control" 
                          id="degree_obtained_year" 
                          placeholder="Año de egreso"
                          value="<?php echo @$form_rtps->degree_obtained_year; ?>"
                          >
                   </div>
                </div>
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Licencia de conducir</h2>

                <div class="input-group <?php echo (form_error('driver_license')) ? 'has-error' : '';?>">
                  <p>Posee licencia de conducir <span>*</span></p></p>
                  <?php $driver_license = @$form_rtps->driver_license; ?>

                  <div class="form-group">
                      <label>
                          <input type="radio" name="driver_license" value="1" required <?php echo $driver_license == '1' ? 'checked' : ''; ?>>
                          SI
                      </label>
                      <label>
                          <input type="radio" name="driver_license" value="0" required <?php echo $driver_license == '0' ? 'checked' : ''; ?>>
                          NO
                      </label>
                  </div>

                  <?php echo form_error('driver_license'); ?>
                </div>
                <!-- Se debe mostrar para formulario completo -->

                <!-- <div id="driver-license-type-content" class="input-group <?php echo (form_error('driver_license_type')) ? 'has-error' : '';?>">
                  <p>Ingrese tipo de licencia<span>*</span></p></p>
                  <input type="text" name="driver_license_type" class="form-control" value="<?php echo @$form_rtps->driver_license_type; ?>">
                  <?php echo form_error('driver_license_type'); ?>
                </div> -->
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Ingresos de 5ta Categoría</h2>

                <div class="input-group <?php echo (form_error('fifth_category_income')) ? 'has-error' : '';?>">
                  <p>¿Ha recibido ingresos de 5ta categoria en el año actual? <span>*</span></p></p>
                  <div class="form-group"> 
                    <?php $fifth_category_income = @$form_rtps->fifth_category_income; ?>
                    <?php $attached_document_license = @$form_rtps->attached_document_license; ?>
                    <label>
                        <input type="radio" required name="fifth_category_income" value="1" <?php echo $fifth_category_income == '1' ? 'checked' : ''; ?>>
                        SI
                    </label>
                    <label>
                        <input type="radio" required name="fifth_category_income" value="0" <?php echo $fifth_category_income == '0' ? 'checked' : ''; ?>>
                        NO
                    </label>
                  </div>
                  <?php echo form_error('fifth_category_income'); ?>
                </div>
                <div id="content-document-license" class="row <?php echo $fifth_category_income == 1 ? '' : 'hidden'; ?>" >
                  <div class="input-group row mb-2">
                    <div class="col-12 col-sm-3">
                      <label class="input-group-addon">
                        Adjuntar documento: <span>*</span>
                      </label>
                    </div>
                    <div class="col-12 col-sm-9 wrapper-document-load">
                    <input id="attached_document_license" class="document-input-file" type="hidden" name="attached_document_license"
                      value="<?php echo $attached_document_license ? $attached_document_license : '' ?>" data-url-upload="<?php echo base_url('jobseeker/form_rtps/upload_license/'); ?>"
                      <?php echo $fifth_category_income == 1 ? 'required' : ''; ?>>
                      <div class="document-load <?php echo $attached_document_license == '' ? '' : 'hidden' ?>">
                        <button type="button" class="btn btn-primary custom-btn load-file">
                          Cargar
                        </button>
                      </div>
                      <?php if ($attached_document_license) : ?>
                        <div class="document-view wrapper-doc-license"
                          style="text-align: center; border: 1px solid #ccc;background: #fff;padding: 8px; ?> <?php echo $attached_document_license != '' ? 'display: none' : '' ?>">
                          <a id="document-link-license" target="_blank" href="<?php echo file_url($attached_document_license, 'public/uploads/candidate/rtps_rightful_claimants/license_documents'); ?>">
                            <i class="glyphicon glyphicon-file"></i>
                            Ver documento
                          </a>
                          <button type="button" class="btn btn-primary custom-btn pull-right load-file">Cambiar</button>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Sistema de pensiones</h2>
                <?php $pension_affiliated = @$form_rtps->pension_affiliated; ?>
                <?php $pension_name = @$form_rtps->pension_name; ?>
                <?php $pension_type = @$form_rtps->pension_type; ?>
                <?php $pension_change = @$form_rtps->pension_change; ?>
                <?php $pension_change_type = @$form_rtps->pension_change_type; ?>
                <?php $pension_join = @$form_rtps->pension_join; ?>

                <div class="input-group row <?php echo (form_error('pension_affiliated')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">¿Eres afiliado?: </p>
                  <div class="form-group col-12 col-sm-9">
                    <label>
                        <input type="radio" required name="pension_affiliated" value="1" <?php echo $pension_affiliated == '1' ? 'checked' : ''; ?>>
                        SI
                    </label>
                    <label>
                        <input type="radio" required name="pension_affiliated" value="0" <?php echo $pension_affiliated == '0' ? 'checked' : ''; ?>>
                        NO
                    </label>
                  </div>
                  <?php echo form_error('pension_affiliated'); ?>
                </div>

                <div class="input-group row" style="height: 5px;">
                  <p class="col-12 col-sm-3"></p>
                  <div class="form-group col-12 col-sm-9">
                    <a href="https://servicios.sbs.gob.pe/reportesituacionprevisional/afil_consulta.aspx" target="_blank" class="text-link">
                      ¿No sabes si estas inscrito? <img src="<?php echo base_url('public/images/vinculo.svg');?>" />
                    </a>
                  </div>
                </div>

                <div id="it-afiliate" class="<?php echo ($pension_affiliated == 1) ? '' : 'hidden'; ?>">
                    <div class="input-group row <?php echo (form_error('pension_name')) ? 'has-error' : '';?>">
                      <p class="col-12 col-sm-3">Afiliado a <span></span></p>
                      <div class="col-12 col-sm-9">
                        <select class="form-control" name="pension_name" id="pension-affiliation" <?php echo $pension_affiliated ? 'required' : ''; ?>>
                          <option value="">Seleccione</option>
                          <option value="SNP" <?php echo $pension_name == 'SNP' ? 'selected' : ''; ?>>SNP</option>
                          <option value="AFP" <?php echo $pension_name == 'AFP' ? 'selected' : ''; ?>>AFP</option>
                        </select>
                      </div>
                      <?php echo form_error('pension_name'); ?>
                    </div>
                    <div id="content_pension_type" class="input-group row <?php echo (form_error('pension_type')) ? 'has-error' : '';?> 
                      <?php echo ($pension_type != '' && $pension_name != 'SNP') ? '' : 'hidden'; ?>">
                      <p class="col-12 col-sm-3">Nombre de AFP <span></span></p>
                      <div class="col-12 col-sm-9">
                        <select name="pension_type" id="pension_type" class="form-control" <?php echo $pension_type != '' && $pension_affiliated == 1 ? 'required' : ''; ?>>
                          <option value="">Seleccione</option>
                          <option value="HABITAT" <?php echo $pension_type == 'HABITAT' && $pension_affiliated == 1 ? 'selected' : ''; ?>>HABITAT</option>
                          <option value="INTEGRA" <?php echo $pension_type == 'INTEGRA' && $pension_affiliated == 1 ? 'selected' : ''; ?>>INTEGRA</option>
                          <option value="PRIMA" <?php echo $pension_type == 'PRIMA' && $pension_affiliated == 1 ? 'selected' : ''; ?>>PRIMA</option>
                          <option value="PROFUTURO" <?php echo $pension_type == 'PROFUTURO' && $pension_affiliated == 1 ? 'selected' : ''; ?>>PROFUTURO</option>
                        </select>
                      </div>
                      <?php echo form_error('pension_type'); ?>
                    </div>
                    <br />
                    <div id="content-pension-change" class="input-group row <?php echo (form_error('pension_change')) ? 'has-error' : '';?> <?php echo ($pension_affiliated == 1) ? '' : 'hidden'; ?>">
                      <p class="col-12 col-sm-3">¿Desea cambiar de sistema privado de pensiones?<br> si es asi, especifique: <span></span></p>
                      <div class="form-group col-12 col-sm-9"> 
                        <label>
                            <input type="radio" name="pension_change" value="1" <?php echo $pension_change == '1' ? 'checked' : ''; ?>>
                            SI
                        </label>
                        <label>
                            <input type="radio" name="pension_change" value="0" <?php echo $pension_change == '0' ? 'checked' : ''; ?>>
                            NO
                        </label>
                      </div>
                      <?php echo form_error('pension_change'); ?>
                    </div>
          
                    <div id="content-pension-type" class="input-group row <?php echo (form_error('pension_change_type')) ? 'has-error' : '';?> <?php echo ($pension_affiliated == 0 || $pension_change == 0) ? 'hidden' : ''; ?>">
                      <p class="col-12 col-sm-3"></p>
                      <div class="col-12 col-sm-9">
                        <select name="pension_change_type" id="pension_change_type" class="form-control">
                          <option value="">Seleccione</option>
                          <option value="PROFUTURO" <?php echo $pension_change_type == 'PROFUTURO' ? 'selected' : ''; ?>>PROFUTURO</option>
                         
                        </select>
                      </div>
                      <?php echo form_error('pesion_change_type'); ?>
                    </div>
                </div>

                <?php $pension_join_name = @$form_rtps->pension_join_name; ?>
                <?php $pension_join_type = @$form_rtps->pension_join_type; ?>
                
                <div id="content-pension-join" class="<?php echo (form_error('pension_join')) ? 'has-error' : ''; ?> <?php echo $pension_affiliated == '1' ? ' hidden' : ''; ?>">
                  <div class="input-group row">
                      <p class="col-12 col-sm-3">Seleccione un Sistema de Pensiones<span></span></p>
                      <div class="col-12 col-sm-9">
                        <select class="form-control" name="pension_join_name" id="pension_join_name" <?php echo $pension_affiliated == 0 ? 'required' : ''; ?>>
                          <option value="">Seleccione</option>
                          <option value="SNP" <?php echo $pension_join_name == 'SNP' ? 'selected' : ''; ?>>SNP</option>
                          <option value="AFP" <?php echo $pension_join_name == 'AFP' ? 'selected' : ''; ?>>AFP</option>
                        </select>
                      </div>
                      <?php echo form_error('pension_join_name'); ?>
                  </div>
                </div>
                    
                <div id="it-not-afiliate" class="<?php echo $pension_join == 1 && $pension_join_name == 'AFP' ? '' : 'hidden'; ?>">
                  <div class="input-group row">
                    <p class="col-12 col-sm-3">¿A qué Tipo de Sistema desea afiliarse voluntariamente?: <span></span></p>
                    <div class="col-12 col-sm-9">
                      <select name="pension_join_type" id="pension_join_type" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="PROFUTURO" <?php echo $pension_join_type == 'PROFUTURO' && !$pension_affiliated ? 'selected' : ''; ?>>PROFUTURO</option>

                        <!--
                        <option value="INTEGRA" <?php echo $pension_join_type == 'INTEGRA' && !$pension_affiliated ? 'selected' : ''; ?>>INTEGRA</option>
                        <option value="HABITAT" <?php echo $pension_join_type == 'HABITAT' && !$pension_affiliated ? 'selected' : ''; ?>>HABITAT</option>
                        <option value="PRIMA" <?php echo $pension_join_type == 'PRIMA' && !$pension_affiliated ? 'selected' : ''; ?>>PRIMA</option>
                        <option value="PROFUTURO" <?php echo $pension_join_type == 'PROFUTURO' && !$pension_affiliated ? 'selected' : ''; ?>>PROFUTURO</option>
                        -->
                      </select>
                    </div>
                    <?php echo form_error('pension_join_type'); ?>
                  </div>
                </div>

              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Pago de Haberes</h2>
                <?php $i_have_account_number = @$form_rtps->bank_account_number != '' ; ?>

                <div class="input-group row">
                  <p class="col-12 col-sm-3"> Cuenta bancaria <span>*</span></p>
                  <div class="form-group col-12 col-sm-9">
                    <label>
                        <input type="radio" required name="i_have_account_number" value="1" <?php echo $i_have_account_number ? 'checked' : ''; ?> >
                        Tengo una cuenta
                    </label>
                    <label>
                        <input type="radio" required name="i_have_account_number" value="0">
                        Aperturar
                    </label>
                  </div>
                  <?php echo form_error('i_have_account_number'); ?>
                </div>

                <div class="input-group row <?php echo (form_error('bank_name'))?'has-error':'';?>" style="<?php echo $i_have_account_number === false ? 'display: none;' : ''; ?>">
                  <p class="col-12 col-sm-3">Seleccione la entidad bancaria donde le gustaría percibir sus pagos <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select id="bank_name" name="bank_name" class="form-control" style="width: 100%;" required>
                      <option value="">Seleccione</option>
                      <?php $bank_name = @$form_rtps->bank_name; ?>

                      <?php foreach ($result_banks as $row_bank): ?>
                        <option value="<?php echo $row_bank->bank_name; ?>" <?php echo $bank_name == $row_bank->bank_name ? 'selected' : ''; ?> data-account-digits="<?php echo $row_bank->account_digits; ?>" data-cci-digits="<?php echo $row_bank->cci_digits; ?>">
                          <?php echo $row_bank->bank_name; ?>    
                        </option>
                      <?php endforeach ?>
                    </select>
                   </div>
                  <?php echo form_error('bank_name'); ?> 
                </div>

                <div id="content-i-have-account-number" style="<?php echo $i_have_account_number === false ? 'display: none;' : ''; ?>">

                <div class="input-group row <?php echo (form_error('bank_account_number')) ? 'has-error' : ''; ?>">
                  <p class="col-12 col-sm-3">Nro de cuenta <span>*</span></p>
                  <div class="col-12 col-sm-9" style="position: relative;">
                    <input
                      id="bank_account_number"
                      name="bank_account_number"
                      type="text"
                      class="form-control"
                      placeholder="Nro de cuenta"
                      <?php echo $i_have_account_number ? 'required' : ''; ?>
                      value="<?php echo trim(str_replace('-', '', (string)@$form_rtps->bank_account_number)); ?>"
                      pattern="^[0-9]+$"
                      title="Solo se permiten dígitos (0-9)."
                      maxlength="20"
                      style="padding-right: 30px;"
                    >

                    <!-- ICONO DE AYUDA  -->
                    <span id="tooltip-icon" style="
                      position: absolute;
                      right: 10px;
                      top: 60%;
                      transform: translateY(-50%);
                      cursor: pointer;
                      background: #333;
                      color: white;
                      border-radius: 50%;
                      width: 20px;
                      height: 20px;
                      text-align: center;
                      font-size: 14px;
                      line-height: 20px;
                      display: none;
                    ">?</span>

                    <!-- TOOLTIP -->
                    <div id="bbva-tooltip" style="
                      visibility: hidden;
                      width: 250px;
                      background-color: #333;
                      color: #fff;
                      text-align: left;
                      border-radius: 6px;
                      padding: 10px;
                      position: absolute;
                      z-index: 100;
                      bottom: 125%;
                      right: 0;
                      opacity: 0;
                      transition: opacity 0.3s;
                    ">
                      Para completar el número de cuenta de 20 dígitos, solo debes tomar los 2 últimos dígitos del CCI y colocarlos en la posición 9 y 10 del número de cuenta de 18 dígitos.
                      <div style="position: absolute; top: 100%; right: 10px; border-width: 5px; border-style: solid; border-color: #333 transparent transparent transparent;"></div>
                    </div>
                  </div>
                </div>

                  <div class="input-group row">
                    <p class="col-12 col-sm-3">Nro de cuenta interbancario <span>*</span></p>
                    <div class="col-12 col-sm-9">
                      <input name="bank_interbank_account_number" 
                        type="text" 
                        class="form-control"  
                        placeholder="Nro de cuenta interbancario"
                        value="<?php echo trim(str_replace('-', '', (string)@$form_rtps->bank_interbank_account_number)); ?>"
                        <?php echo $i_have_account_number ? 'required' : ''; ?>
                        pattern="^[0-9]+$"
                        title="Solo se permiten dígitos (0-9)."
                        maxlength="20"
                      >
                      </div>
                  </div>

                  <div class="input-group row <?php echo (form_error('bank_account_type')) ? 'has-error' : ''; ?>">
                    <p class="col-12 col-sm-3">Tipo de cuenta <span>*</span></p>
                    <div class="col-12 col-sm-9">
                      <select name="bank_account_type" class="form-control" style="width: 100%;" <?php echo $i_have_account_number ? 'required' : ''; ?>>
                        <option value="Ahorro" <?php echo @$form_rtps->bank_account_type == 'Ahorro' ? 'selected' : ''; ?>>Ahorro</option>
                        <!-- 
                        <option value="Corriente" <?php echo @$form_rtps->bank_account_type == 'Corriente' ? 'selected' : ''; ?>>Corriente</option>
                        <option value="Interbancario" <?php echo @$form_rtps->bank_account_type == 'Interbancario' ? 'selected' : ''; ?>>Interbancario</option>
                        -->
                      </select>
                    </div>
                    <?php echo form_error('bank_account_type'); ?> 
                  </div>
                </div>
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Pago de CTS</h2>

                <div class="input-group row <?php echo (form_error('payment_cts_bank_name'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Seleccione la entidad bancaria donde le gustaría percibir sus pagos <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select id="payment_cts_bank_name" name="payment_cts_bank_name" class="form-control" style="width: 100%;" required>
                      <option value="">Seleccione</option>
                      <?php $bank_name = @$form_rtps->payment_cts_bank_name; ?>
                      <?php foreach ($result_banks as $row_bank): ?>
                        <option value="<?php echo $row_bank->bank_name; ?>" <?php echo $bank_name == $row_bank->bank_name ? 'selected' : ''; ?>>
                          <?php echo $row_bank->bank_name; ?>    
                        </option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <?php echo form_error('payment_cts_bank_name'); ?> 
                </div>

                <div class="input-group row <?php echo (form_error('payment_cts_currency'))?'has-error':'';?>">
                  <p class="col-12 col-sm-3">Moneda <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select name="payment_cts_currency" class="form-control" style="width: 100%;" required>
                      <option value="">Seleccione</option>
                      <?php $payment_cts_currency = @$form_rtps->payment_cts_currency; ?>
                      <option value="PEN" <?php echo $payment_cts_currency == 'PEN' ? 'selected' : ''; ?>>
                        Soles    
                      </option>
                      <option value="USD" <?php echo $payment_cts_currency == 'USD' ? 'selected' : ''; ?>>
                        Dólares    
                      </option>
                    </select>
                  </div> 
                  <?php echo form_error('payment_cts_currency'); ?> 
                </div>
              </div>
            </div>
            <!-- <div class="step">
              <div class="container-steps">
                <h2>Sindicalizado</h2>

                <div class="input-group <?php echo (form_error('unionized')) ? 'has-error' : '';?>">
                  <p>Sindicalizado <span>*</span></p></p>
                  <select class="form-control" name="unionized" id="unionized">
                    <option value="">Seleccione</option>
                    <?php $unionized = @$form_rtps->unionized; ?>
                    <option value="1" <?php echo $unionized == '1' ? 'selected' : ''; ?>>SI</option>
                    <option value="0" <?php echo $unionized == '0' ? 'selected' : ''; ?>>NO</option>
                  </select>
                  <?php echo form_error('unionized'); ?>
                </div>
              </div>
            </div>
            -->
            <div class="step">
              <div class="container-steps">
                <h2>Datos de los Derechohabientes</h2>

                <div class="info-rightful-claimants">
                  <table>
                    <tr>
                      <td>
                        <i class="glyphicon glyphicon-info-sign" style="font-size: 1.5em;"></i>
                      </td>
                      <td>
                        <p>
                          Un derechohabiente es toda aquella persona que depende económicamente de ti, ej: Tu cónyuge, hijos menores de edad o hijos con alguna discapacidad. 
                          Si no tienes derechohabientes ignora esta sección.
                        </p>
                      </td>
                    </tr>
                  </table>  
                </div>
                <div class="content-add-rightful-claimants">
                  <button id="add-rightful-claimant" type="button" class="btn btn-sm btn-default">Agregar</button>
                </div>
                <div class="content-rightful-claimants">
                  <div class="table-responsive">
                    <table width="100%" class="table table-striped tbl-rightful-claimants">
                      <tr class="head-table">
                        <th style="min-width: 100px;"></th>
                        <th>Doc. Identidad</th>
                        <th>Nombres y apellidos</th>
                        <th>Fecha de nacimiento</th>
                        <th>Parentesco</th>
                      </tr>
                      <?php foreach ($rightful_claimants as $key => $person): ?>
                        <tr data-rc-id="<?php echo $person->ID; ?>" >
                          <td>
                            <button type="button" class="btn btn-xs btn-rc-action edit-rightful-claimant" style="background-color: transparent;">
                            <img src="<?php echo base_url('public/images/edit-table.svg');?>" />
                            </button>
                            <button type="button" class="btn btn-xs btn-rc-action remove-rightful-claimants" style="background-color: transparent;">
                            <img src="<?php echo base_url('public/images/delete-table.svg');?>" />
                            </button>
                            <div class="rightful-claimants-inputs<?php echo $person->ID;?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][first_name]" value="<?php echo $person->first_name; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][paternal_last_name]" value="<?php echo $person->paternal_last_name; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][maternal_last_name]" value="<?php echo $person->maternal_last_name; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][document_type]" value="<?php echo $person->document_type; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][document_number]" value="<?php echo $person->document_number; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][birthdate]" value="<?php echo format_date($person->birthdate, 'Y-m-d'); ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][gender]" value="<?php echo $person->gender; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][kinship]" value="<?php echo $person->kinship; ?>" class="input-kinship">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][attached_document]" value="<?php echo $person->attached_document_number; ?>" data-file-url="<?php echo file_url($person->attached_document_number, 'public/uploads/candidate/document_rightful_claimants'); ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][family_bond_cert_type]" value="<?php echo $person->kinship_cert_type; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][family_bond_cert_code]" value="<?php echo $person->kinship_cert_code; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][live_same_domicile]" value="<?php echo $person->live_same_domicile; ?>">                        
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][nationality]" value="<?php echo $person->nationality_id; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][domicile_way_id]" value="<?php echo $person->domicile_way_id; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][domicile_number]" value="<?php echo $person->domicile_number; ?>">
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][domicile_interior]" value="<?php echo $person->domicile_interior; ?>">
    
                            <?php if ($person->kinship == '4' || $person->kinship == '5' || $person->kinship == '6'): ?>
                                <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][place_birth]" value="<?php echo $person->place_birth; ?>">
                                <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][place_birth_certificate]" value="<?php echo $person->place_birth_certificate; ?>">
                            <?php endif; ?>
                        
                            <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][attached_cert_cohabitation]" value="<?php echo $person->kinship_cert_attached; ?>"  data-file-url="<?php echo $person->kinship_cert_attached ? file_url($person->kinship_cert_attached) : ''; ?>">
                            
                            <?php if ($person->live_same_domicile == '0'): ?>
                                <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][domicile]" value="<?php echo $person->domicile; ?>">
                                <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][ubigeo]" value="<?php echo $person->ubigeo; ?>">
                            <?php endif; ?>
                            </div>
                          </td>
                          <td>
                            <?php echo document_type_text($person->document_type) . ' - ' . $person->document_number; ?>
                          </td>
                          <td>
                            <?php echo $person->first_name . ' ' . $person->last_name; ?>
                          </td>
                          <td>
                            <?php echo format_date($person->birthdate, 'Y-m-d'); ?>
                          </td>
                          <td>
                            <?php echo $person->kinship_name; ?>
                          </td>
                        </tr>
                      <?php endforeach ?>
                    </table>
                  </div>
                </div>
                <div class="content-conditions-policy">
                  * Al hacer clic en "Guardar", aceptas nuestras <a href="#" data-toggle="modal" data-target="#modal-conditions-policy">Condiciones y política de ingreso</a>. 
                </div>
              </div>
            </div>
            <div class="buttons">
              <div id="additional-info" style="<?php echo ($fifth_category_income == '0') ? '' : 'display: none;'; ?>">
                <div class="form-group">
                    <label class="declaration-label row">
                        <div class="col-3">
                          <input type="checkbox" name="declaration" id="declaration-checkbox" <?php if ($fifth_category_income == '0') echo 'checked'; ?>>
                        </div>
                        <span class="col-9">Declaro bajo juramento: No haber percibido ingresos de 5ta categoría correspondientes al presente año hasta la fecha.</span>
                    </label>
                </div>
              </div>
              <button type="button" id="prevBtn" onclick="nextPrev(-1)">Atrás</button>
              <button type="button" id="nextBtn" onclick="nextPrev(1)">Siguiente</button>
              <button type="submit" id="submit_button" style="display:none">Guardar</button>
            </div>
        </div>
        
        
      </div>
      
    </div>
    <!--/Job Detail--> 

    <div id="container-educations">
      <?php foreach ($result_studies as $study): ?>
        <div class="education-inputs-<?php echo $study->ID; ?>">
          <input type="hidden" name="education[<?php echo $study->ID; ?>][id]" value="<?php echo $study->ID; ?>">
          <input type="hidden" name="education[<?php echo $study->ID ?>][degree_title]" value="<?php echo $study->degree_title; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][major_subject]" value="<?php echo $study->major; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][edu_country]" value="<?php echo $study->country; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][institution_educ_type]" value="<?php echo $study->institution_educational_type_id; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][institution_educ_class]" value="<?php echo $study->institution_educational_class_id; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][institution_type]" value="<?php echo $study->institution_type_id; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][institution]" value="<?php echo $study->institution; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][career]" value="<?php echo $study->career; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][year_start_date]" value="<?php echo date('Y', strtotime($study->start_date)); ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][month_start_date]" value="<?php echo date('m', strtotime($study->start_date)); ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][year_end_date]" value="<?php echo $study->end_date ? date('Y', strtotime($study->end_date)) : ''; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][month_end_date]" value="<?php echo $study->end_date ? date('m', strtotime($study->end_date)) : ''; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][tuition_number]" value="<?php echo $study->tuition_number ? $study->tuition_number : ''; ?>" >
          <input type="hidden" name="education[<?php echo $study->ID ?>][studying]" value="<?php echo !$study->end_date ? 'true' : 'false'; ?>">
        </div>
      <?php endforeach; ?>
    </div>

    <?php echo form_close(); ?>
  </div>
  
<!-- Modal -->
<div id="modal-add-rightful-claimant" class="modal fade" role="dialog">
  <div class="modal-dialog" style="">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Guardar derechohabientes</h4>
      </div>
      <div class="modal-body beneficiaries">
        <div class="section-rightful-claimant">
          <div class="formwraper">
            <input id="rc-id" type="hidden" value="2">
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Nombres <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-name" type="text" name="first_name" class="form-control" value="" placeholder="Nombres">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Apellido Paterno <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-paternal-name" type="text" name="paternal_last_name" class="form-control" value="" placeholder="Apellido paterno">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Apellido Materno <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-maternal-name" type="text" name="maternal_last_name" class="form-control" value="" placeholder="Apellido materno">
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Documento de identidad <span>*</span></label>
              <div class="col-12 col-sm-9">
                <table width="100%">
                  <tr>
                    <td width="35%">
                      <select id="rc-document-type" name="document_type" class="form-control" style="width: 88%;">
                        <option value="">Documento</option>
                        <?php foreach ($document_types as $doc_type): ?>
                          <option value="<?php echo $doc_type->id; ?>">
                            <?php e($doc_type->name); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td>
                      <input id="rc-document-number" name="document_number" type="text" class="form-control" placeholder="Número de documento" value="">
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Fecha de nacimiento <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-birthdate" type="date" class="form-control" name="birthdate" value="" placeholder="Fecha de nacimiento"> 
              </div>
            </div>
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Sexo <span>*</span></label>
              <div class="col-12 col-sm-9">
                <div class="row">
                  <div class="form-check form-check-inline col-12 col-sm-5">
                      <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="2">
                      <label class="form-check-label" for="genderFemale">Femenino</label>
                  </div>
                  <div class="form-check form-check-inline col-12 col-sm-5">
                      <input class="form-check-input" type="radio" name="gender" id="genderMale" value="1">
                      <label class="form-check-label" for="genderMale">Masculino</label>
                  </div>
                </div>
              </div>
            </div>
            
            <div id="content-document" class="input-group row mb-2">
              <label class="input-group-addon col-12 col-sm-3">
                Adjuntar Doc. Identidad <span>*</span>
              </label>
              <div class="wrapper-document-load wrapper-doc-number col-12 col-sm-9">
                <input id="rc-attached-document" class="document-input-file"  type="hidden" name="attached_document" value="" data-url-upload="<?php echo site_url('jobseeker/form_rtps/upload_document'); ?>"> 
                <div class="document-load">
                  <button type="button" class="btn btn-sm btn-default btn-block load-file">
                    <i class="glyphicon glyphicon-paperclip"></i> Elegir archivo
                  </button>
                </div>
                
                <div class="document-view" style="text-align: center; border: 1px solid #ccc;background: #fff;padding: 8px; ?>">
                    <a class="document-link" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                        Ver documento
                    </a>
                    <button type="button" class="btn btn-xs btn-default pull-right load-file">Cambiar</button>
                </div>
              </div>
            </div>
             
            <div class="input-group row mb-2">
              <label class="input-group-addon col-12 col-sm-3">Vinculo familiar <span>*</span></label>
              <div class="col-12 col-sm-9">
                <select id="rc-kinship" name="kinship" class="form-control select-kinship" style="width: 100%;">
                  <option value="">Seleccione</option>
                  <?php foreach ($kinship_types as $row): ?>
                    <option value="<?php echo $row->id; ?>"><?php e($row->name); ?></option>
                  <?php endforeach; ?>
                </select> 
              </div>
            </div>
            
            <div class="input-group row mb-2">
              <label class="input-group-addon col-12 col-sm-3" style="white-space: normal;line-height: 1.5;">
                  ¿Tienes el certificado del vínculo familiar? <span>*</span>
              </label>
              <div class="col-12 col-sm-9">
                    
                <div class="row">
                    <div class="form-check form-check-inline col-12 col-sm-3">
                        <input class="form-check-input" type="radio" name="i_have_cert_kinship" id="i_have_cert_kinship_1" value="1">
                        <label class="form-check-label" for="i_have_cert_kinship_1">Si</label>
                    </div>
                    <div class="form-check form-check-inline col-12 col-sm-3">
                        <input class="form-check-input" type="radio" name="i_have_cert_kinship" id="i_have_cert_kinship_0" value="0">
                        <label class="form-check-label" for="i_have_cert_kinship_0">No</label>
                    </div>
                </div>
              </div>
            </div>
            
            <div class="input-group row mb-2 content-cert-kinship-type" style="display: none;">
              <label class="input-group-addon col-12 col-sm-3">Certificado Vinculo tipo <span>*</span></label>
              <div class="col-12 col-sm-9">
                <select id="rc-family-bond-certs" name="family_bond_cert_type" class="form-control" style="width:100%;">
                    <option value="">Seleccione</option>
                </select>    
              </div>
            </div>
            
            <div class="input-group row mb-2 content-cert-kinship-attach" style="display: none;">
              <label class="input-group-addon col-12 col-sm-3">Cargar Certificado Vinculo <span>*</span></label>
                <div class="wrapper-document-load wrapper-cert-cohabitation col-12 col-sm-9">
                  <input id="rc-attached-cert-cohabitation" class="document-input-file" type="hidden" name="attached_cert_cohabitation" value="" data-url-upload="<?php echo site_url('jobseeker/form_rtps/upload_cert_cohabitation'); ?>">
                  <div class="document-load">
                    <button type="button" class="btn btn-sm btn-default btn-block load-file">
                      <i class="glyphicon glyphicon-paperclip"></i> Elegir archivo
                    </button>
                  </div>
                  
                   <div class="document-view" style="text-align: center; border: 1px solid #ccc;background: #fff;padding: 8px; ?>">
                    <a class="document-link" href="" target="_blank">
                      <i class="glyphicon glyphicon-file"></i>
                      Ver documento
                    </a>
                    <button type="button" class="btn btn-xs btn-default pull-right load-file">Cambiar</button>
                  </div>
                </div>
            </div>
            
            <div class="input-group row">
              <label class="input-group-addon col-12 col-sm-3">Vive en el mismo domicilio <span>*</span></label>
              <div class="col-12 col-sm-9">
                <select id="rc-live-same-domicile" name="live_same_domicile" class="form-control live_same_domicile i3" style="width:100%">
                  <option value="">Seleccione</option>
                  <option value="1">Si</option>
                  <option value="0">No</option>
                </select>     
              </div>
            </div>
            <div class="input-group content-domicile row">
              <label class="input-group-addon col-12 col-sm-3">Ubicación / Ciudad <span> *</span></label>
              <div class="col-12 col-sm-9">
                <select id="rc-ubigeo" name="ubigeo" style="width: 100%;">
                  <option value="">Seleccione</option>
                  <?php foreach ($result_ubigeos as $row_ubigeo): ?>
                    <?php 
                      $city_value = $row_ubigeo->order_administrative1 . ', ' . $row_ubigeo->order_administrative2 . ', ' . $row_ubigeo->order_administrative3; 
                      $city_selected =  $city_value == $jobseeker->city ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $city_value; ?>" <?php echo $city_selected; ?>> <?php echo $city_value; ?></option>
                  <?php endforeach; ?>  
                </select>
              </div>
            </div>
            <div class="input-group content-domicile row">
              <label class="input-group-addon col-12 col-sm-3">Dirección <span>*</span></label>
              <div class="col-12 col-sm-9">
                <input id="rc-domicile" name="domicile" type="text" class="form-control" placeholder="Domicilio" value=""> 
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer custom-modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button id="save-rightful-claimant" type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

  <div id="modal-conditions-policy" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Condiciones y Política de ingreso</h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script id="tpl-document-progress" type="text/template">
    <div class="document-progress">
      <div class="document-info-progress">
        <span class="content-progress-bar">
          <span class="total-progress-bar"></span>
        </span>
      </div>
      <div class="document-error" style="text-align: center;display: none;">  
        <span class="document-error-info" style="color: red;"></span>
        <a href="#" class="load-file">Volver a intentar</a>
      </div>
    </div>
  </script>

  <script id="tpl-document-preview" type="text/template">
    <div class="document-view" style="text-align: center; border: 1px solid #ccc;background: #fff;padding: 8px; ?>">
      <a class="document-link" href="{{urlFile}}" target="_blank">
        <i class="glyphicon glyphicon-file"></i>
        Ver documento
      </a>
      <button type="button" class="btn btn-xs btn-default pull-right load-file">Cambiar</button>
    </div>
  </script>

  <script id="tpl-add-rightful-claimant" type="text/template">
    <tr data-rc-id="{{rcId}}">
      <td>
        <button type="button" class="btn btn-xs btn-rc-action edit-rightful-claimant" style="background-color: transparent;">
          <img src="<?php echo base_url('public/images/edit-table.svg');?>" />
        </button>
        <button type="button" class="btn btn-xs btn-rc-action remove-rightful-claimants" style="background-color: transparent;">
          <img src="<?php echo base_url('public/images/delete-table.svg');?>" />
        </button>
      </td>
      <td>{{documentNumber}}</td>
      <td>{{name}} {{lastName}}</td>
      <td>{{birthdate}}</td>
      <td>{{kinship}}</td>
    </tr>
  </script>
</div>
<div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">

  $(function(){
    var iti_mobile = window.intlTelInput($( 'input[name="mobile"]' )[0], {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: 'pe'
    });

    $( 'input[name="mobile"]' ).data('iti-instance', iti_mobile);
  
    var iti_emergency_contact_mobile = window.intlTelInput($( 'input[name="emergency_contact_mobile"]' )[0], {
      loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
      separateDialCode: true,
      autoPlaceholder: 'aggressive',
      initialCountry: 'pe'
    });
    $( 'input[name="emergency_contact_mobile"]' ).data('iti-instance', iti_emergency_contact_mobile);
  });
  
  $( ".iti__search-input" ).attr({'placeholder' : 'Buscar'});

  const bankList = [];
  <?php foreach ($result_banks as $row_bank): ?>
    bankList.push({
      name: "<?php echo $row_bank->bank_name; ?>",
      accountDigits: <?php echo $row_bank->account_digits; ?>,
      cciDigits: <?php echo $row_bank->cci_digits; ?>,
    });
  <?php endforeach ?>

  function showSelectorFiles(elementTarget) {
    
    var wrapperDocument = elementTarget.closest('.wrapper-document-load');
    var studyId = wrapperDocument.data('study-id');
    var url = $( ".document-input-file", wrapperDocument).data('url-upload');
    var fileDocument = $( '<input type="file" name="file" style="display: none;">');
   
    $(fileDocument).fileupload({
      dataType: 'json',
      url: url,
      autoUpload: true,
      add: function (e, data) {
        
        var wrapperDocument = $(e.target).closest('.wrapper-document-load');
        var tplProgress = $("#tpl-document-progress").html();

        $( ".document-view", wrapperDocument).hide();
        $( ".document-load", wrapperDocument).hide();
        $( ".document-progress", wrapperDocument).remove();

        wrapperDocument.append(tplProgress);
        
        data.context = wrapperDocument;
        data.submit();
      },
      progress: function(e, data) {
        var wrapperDocument = data.context;
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $( ".total-progress-bar", wrapperDocument).css({width: progress + 'px'});
      }, 
      done: function (e, data) {
        var wrapperDocument = data.context,
            error = data.result.error,
            urlFile = data.result.url_file,
            fileName = data.result.file_name;
        
        $( ".document-info-progress", wrapperDocument).remove();

        if (error) {
          $( ".document-error", wrapperDocument).show();
          $( ".document-error-info", wrapperDocument).text(error);
          return;
        }
        
        $( ".document-view", wrapperDocument).remove();

        var tplPreview = $( "#tpl-document-preview" ).html();
        var previewHtml = Mustache.render(tplPreview, {
          urlFile: urlFile
        });

        wrapperDocument.append(previewHtml);
        $( ".document-input-file", wrapperDocument).val(fileName);
      }
    });

    wrapperDocument.append(fileDocument);
    fileDocument[0].click();
  }
  
  function jobseekerHaveSpouse() {
    return $( ".input-kinship" ).filter(function(){
      return $(this).val() == '1' || $(this).val() == '2';
    }).length > 0;
  }
  
  function addRightfulClaimants() {
  $( ".section-rightful-claimant" ).find('input[type="hidden"],input[type="text"],input[type="file"], select').val("");
    $(".input-group", ".section-rightful-claimant").removeClass('has-error');
    $(".document-view", ".section-rightful-claimant").hide();
    $(".document-load", ".section-rightful-claimant").show();
    $(".document-progress", ".section-rightful-claimant").remove();    
    $( "#modal-add-rightful-claimant" ).modal('show');
    $( "#modal-add-rightful-claimant" ).removeData('row-edit');
    
    if (jobseekerHaveSpouse()) {
      $( "#rc-kinship option[value='1']").prop('disabled', true);
      $( "#rc-kinship option[value='2']").prop('disabled', true);
    } else {
      $( "#rc-kinship option[value='1']").prop('disabled', false);
      $( "#rc-kinship option[value='2']").prop('disabled', false);
    }

    $( "select[name='nationality']", "#modal-add-rightful-claimant" ).val("<?php echo $jobseeker->nationality; ?>");
    $( '#genderMale' ).prop('checked', false);
    $( '#genderFemale').prop('checked', false);
    
    $( '#i_have_cert_kinship_0' ).prop('checked', true);
    $( '#i_have_cert_kinship_0' ).change();
  }
  
  function editRightfulClaimants(tr) {
    $(".input-group", ".section-rightful-claimant").removeClass('has-error');
    $(".document-view", ".section-rightful-claimant").show();
    $(".document-load", ".section-rightful-claimant").hide();
    $(".document-progress", ".section-rightful-claimant").remove();

    var id = tr.data('rc-id'),
        name = $( "input[name='rightful_claimants[" + id + "][first_name]']").val(),
        paternalLastName = $( "input[name='rightful_claimants[" + id + "][paternal_last_name]']").val(),
        maternalLastName = $( "input[name='rightful_claimants[" + id + "][maternal_last_name]']").val(),
        documentType = $( "input[name='rightful_claimants[" + id + "][document_type]']").val(),
        documentNumber = $( "input[name='rightful_claimants[" + id + "][document_number]']").val(),
        gender = $("input[name='rightful_claimants[" + id + "][gender]']").val();
        birthdate = $( "input[name='rightful_claimants[" + id + "][birthdate]']").val(),
        kinship = $( "input[name='rightful_claimants[" + id + "][kinship]']").val(),
        nationality = $( "input[name='rightful_claimants[" + id + "][nationality]']").val(),
        familyBondCertType = $( "input[name='rightful_claimants[" + id + "][family_bond_cert_type]']").val()
        familyBondCertCode = $( "input[name='rightful_claimants[" + id + "][family_bond_cert_code]']").val()
        placeBirth = $( "input[name='rightful_claimants[" + id + "][place_birth]']").val(),
        placeBirthCertificate = $( "input[name='rightful_claimants[" + id + "][place_birth_certificate]']").val(),
        liveSameDomicile = $( "input[name='rightful_claimants[" + id + "][live_same_domicile]']").val(),
        ubigeo = $( "input[name='rightful_claimants[" + id + "][ubigeo]']").val(),
        domicile = $( "input[name='rightful_claimants[" + id + "][domicile]']").val(),
        domicileWayId = $( "input[name='rightful_claimants[" + id + "][domicile_way_id]']").val(),
        domicileNumber = $( "input[name='rightful_claimants[" + id + "][domicile_number]']").val(),
        domicileInterior = $( "input[name='rightful_claimants[" + id + "][domicile_interior]']").val(),
        attachedDocument = $( "input[name='rightful_claimants[" + id + "][attached_document]']").val(),
        attachedCertCohabitation = $( "input[name='rightful_claimants[" + id + "][attached_cert_cohabitation]']").val(),
        urlFileDocument = $( "input[name='rightful_claimants[" + id + "][attached_document]']").data('file-url'),
        urlFileCertCohabitation = $( "input[name='rightful_claimants[" + id + "][attached_cert_cohabitation]']").data('file-url');   
        // attachedDocLicense = $( "input[name='rightful_claimants[" + id + "][attached_document_license]']").val(),
        urlFileDocLicense = $( "input[name='rightful_claimants[" + id + "][attached_document_license]']").data('file-url');   
    
    
    $( "#rc-kinship" ).val(kinship);
    $( "#rc-id" ).val(id);
    $( "#rc-name" ).val(name);
    $( "#rc-paternal-name" ).val(paternalLastName);
    $( "#rc-maternal-name" ).val(maternalLastName);
    $( "#rc-document-type" ).val(documentType);
    $( "#rc-document-number" ).val(documentNumber);
    if (gender == 1) {
        $('#genderMale').prop('checked', true);
    } else if (gender == 2) {
        $('#genderFemale').prop('checked', true);
    }
    
    $( "#rc-birthdate" ).val(birthdate);
    $( "#rc-family-bond-certs-code ") .val(familyBondCertCode);
    $( "#rc-place-birth" ).val(placeBirth);
    $( "#rc-place-birth-certificate" ).val(placeBirthCertificate);
    $( "#rc-live-same-domicile" ).val(liveSameDomicile);
    $( "#rc-attached-document" ).val(attachedDocument);
    // $( "#attached_document_license" ).val(attachedDocLicense);
    $( "#rc-domicile" ).val(domicile);
    $( "#rc-ubigeo" ).val(ubigeo).trigger('change');
    $( "#rc-nationality" ).val(nationality);
    $( "#rc-domicile-way" ).val(domicileWayId);
    $( "#rc-domicile-number" ).val(domicileNumber);
    $( "#rc-domicile-interior" ).val(domicileInterior);

    $( ".document-link", ".wrapper-doc-number" ).prop("href", urlFileDocument);
    $( "#document-link-license" ).prop("href", urlFileDocLicense);
  
    if (kinship != '1' && kinship != '2' && jobseekerHaveSpouse()) {
      $( "#rc-kinship option[value='1']").prop('disabled', true);
      $( "#rc-kinship option[value='2']").prop('disabled', true);
    } else {
      $( "#rc-kinship option[value='1']").prop('disabled', false);
      $( "#rc-kinship option[value='2']").prop('disabled', false);
    }

    $( "#rc-kinship" ).change();

  
    if (attachedCertCohabitation) {
      $( '#i_have_cert_kinship_1' ).prop('checked', true);
      $( '#i_have_cert_kinship_1' ).change();
    } else {
       $( '#i_have_cert_kinship_0' ).prop('checked', true);
       $( '#i_have_cert_kinship_0' ).change();
    }
    
    $( "#rc-family-bond-certs") .val(familyBondCertType);
    
    if (attachedCertCohabitation) {
       $( ".document-link", ".wrapper-cert-cohabitation" ).prop("href", urlFileCertCohabitation);       
       $( '.document-load', '.content-cert-kinship-attach' ).hide();
       $( '.document-view', '.content-cert-kinship-attach' ).show();
       $( "#rc-attached-cert-cohabitation" ).val(attachedCertCohabitation);
    }
    
    $( "#rc-live-same-domicile" ).change();

    $( "#modal-add-rightful-claimant" ).data('row-edit', tr);
    $( "#modal-add-rightful-claimant" ).modal('show');
  }

  function saveRightfulClaimants() {
    var data = validDataRightfulClaimants();

    if (data.errors > 0) {
      toastr["warning"]("¡Existen datos sin completar!");
      return;
    }

    if ($( '#rc-kinship' ).val() == '4' && dateDiffYears($( '#rc-birthdate' ).val()) >= 18) {
      toastr["warning"](
        "¡La edad del hijo es mayor o igual a 18 años, no puede seleccionar Hijo menor de edad!"
      );
      return;
    }

    if ($( '#rc-kinship' ).val() == '5' && dateDiffYears($( '#rc-birthdate' ).val()) < 18) {
      toastr["warning"](
        "¡La edad del hijo es menor a 18 años, no puede seleccionar Hijo mayor de edad!"
      );
      return;
    }
  
    var id = $( "#rc-id" ).val();

    if (id == '') {
      id = $( ".tbl-rightful-claimants" ).generateSequence() * -1;
    }

    $( ".rightful-claimants-inputs" + id).remove();
    var contentInput  = $( "<div class='rightful-claimants-inputs" + id + "'>");

    $( ".section-rightful-claimant" ).find(':input').each(function(i, element) {
      var elementName = $.trim($(element).prop('name'));
      var elementType = $(element).prop('type');

      var elementValue = '';
      if (elementType === 'radio') {
        elementValue = $('input[name="' + elementName + '"]:checked').val() || '';
      } else {
        elementValue = $(element).val();
      }

      if (elementValue != '' && elementName && elementName != 'file') {
        var inputHidden = $("<input/>").attr({
          'type': 'hidden',
          'name': 'rightful_claimants[' + id + '][' + elementName + ']', 
          'value': elementValue,
          'class': (elementName == 'kinship' ? 'input-kinship' : ''),
        });

        contentInput.append(inputHidden);
      }
    });

    var rowEdit = $( "#modal-add-rightful-claimant" ).data('row-edit') || false;

    var tplRow = $( "#tpl-add-rightful-claimant" ).html();

    var newRow = Mustache.render(tplRow, {
      rcId: id,
      documentNumber: $( "#rc-document-type  option:selected" ).text() + ' - ' + $( "#rc-document-number").val(),
      name: $( "#rc-name").val(),
      lastName: $( "#rc-paternal-name").val() + ' ' + $( "#rc-maternal-name").val(),
      birthdate : $( "#rc-birthdate" ).val(),
      kinship: $( "#rc-kinship  option:selected" ).text()
    });

    var $newRow = $(newRow);
    $newRow.append(contentInput);

    if (rowEdit) {
      rowEdit.replaceWith($newRow);
    } else {
      $( ".tbl-rightful-claimants tbody" ).append($newRow);
    }

    $( "#modal-add-rightful-claimant" ).modal('hide');
  }

  $( "#save-rightful-claimant" ).click(function(){
    saveRightfulClaimants();
  });

  $( "#add-rightful-claimant" ).click(function(){
    addRightfulClaimants();
  });

  $(document).on("click", ".edit-rightful-claimant", function(e){
    var row = $(this).closest('tr');
    editRightfulClaimants(row);
  });

  $(document).on("click", ".load-file", function(e) {
    e.preventDefault();
    showSelectorFiles($(this));
  });

  $(document).on("change", "#rc-kinship", function(e) {

    var contentSectionChild = $(this).closest('.section-rightful-claimant').find('.content-section-child');

    $( '#rc-attached-cert-cohabitation' ).val('');
    $( '.document-load', '.content-cert-kinship-attach' ).show();
    $( '.document-view', '.content-cert-kinship-attach' ).hide();
    
    createOptionsFamilyBondCerts();
  });

  function createOptionsFamilyBondCerts() {

    var familyBondId = $( '#rc-kinship' ).val();
    var url = "<?php echo site_url('jobseeker/form_rtps/get_family_bond_certs/'); ?>" + familyBondId;
    
    $.ajax({
      url: url,
      data: null,
      async: false,
      success: function(result) {
        var certs = result.data;

        $( '#rc-family-bond-certs' ).html('<option value="">Seleccione</option>');

        var options = '';

        for (var row in certs) {
          var cert = certs[row];
          options+=`
            <option value="${cert.id}" >${cert.name}</option>
          `;
        }

        $( '#rc-family-bond-certs' ).append(options);
    },
      dataType: 'json'
    });
  }

  $( "#born-country" ).change(function(){

    $( "#content-born-department" ).hide();
    $( "#content-born-province" ).hide();
    $( "#content-born-district" ).hide();

    if ($(this).val() == '56') {
      $( "#content-born-department" ).show();
      $( "#content-born-province" ).show();
      $( "#content-born-district" ).show();
    }
  });

  $(document).ready(function() {
    let pension_desired = document.getElementById("pension_desired_pension_system");
    
    // $('input[type=radio][name=gender]').change(function() {
    //   var value = (this.id == 'genderFemale') ? 2 : 1;
    //   $('#'+this.id).val(value);
    // });
    
    // $('input[type=radio][name=i_have_cert_kinship]').change(function() {
    //   var value = (this.id == 'i_have_cert_kinship_0') ? 0 : 1;
    //   $('#'+this.id).val(value);
    // });
    
    $('input[name="fifth_category_income"]').change(function() {
      if ($(this).val() == 1 || $(this).val() == '1') {
        $( '#content-document-license' ).show();
        $( "#content-document-license" ).removeClass('hidden');
        $( '#attached_document_license' ).attr('required', 'true');
        $( '#additional-info' ).hide();
        $( '#declaration-checkbox' ).removeAttr('required');
      } else {
        $( '#attached_document_license' ).removeAttr('required');
        $( '#content-document-license' ).hide();
        $( '#additional-info' ).show();
        $( '#declaration-checkbox' ).attr('required', 'true');
      }
    });

    const $tooltip = $('#bbva-tooltip');
    const $tooltipIcon = $('#tooltip-icon');
    const $bankSelect = $('#bank_name');
    const $accountInput = $('#bank_account_number');

    function accountWidth() {
      const selectedBank = $bankSelect.val().trim().toUpperCase();

      if (selectedBank === 'BBVA CONTINENTAL') {
        $accountInput.css('width', '94%');
      } else {
        $accountInput.css('width', '100%');
      }
    }

    function toggleTooltipVisibility() {
      const selectedBank = $bankSelect.val().toUpperCase().trim();
      accountWidth();

      if (selectedBank === 'BBVA CONTINENTAL') {
        $tooltipIcon.show();
      } else {
        $tooltipIcon.hide();
        $tooltip.css({ visibility: 'hidden', opacity: 0 });
      }
    }

    // Mostrar/Ocultar tooltip al pasar el mouse
    $tooltipIcon.hover(
      function () {
        $tooltip.css({ visibility: 'visible', opacity: 1 });
      },
      function () {
        $tooltip.css({ visibility: 'hidden', opacity: 0 });
      }
    );

    $bankSelect.on('change', toggleTooltipVisibility);

    // Inicial
    toggleTooltipVisibility();
    
    // Manejador para la pulsación de teclas (evita ingresar caracteres no numéricos al teclear)
    $('input[name="bank_account_number"], input[name="bank_interbank_account_number"]').on('keypress', function(event) {
        // Permitir teclas de control (por ejemplo, backspace, tab, flechas)
        if (event.which === 8 || event.which === 9 || event.which === 37 || event.which === 39) {
            return;
        }
        // Evitar la entrada de caracteres que no sean dígitos (ASCII 48-57)
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });
    
    // Manejador para el evento 'paste' para filtrar el contenido pegado
    $('input[name="bank_account_number"], input[name="bank_interbank_account_number"]').on('paste', function(e) {
        // Obtener el texto pegado del portapapeles
        var clipboardData = e.originalEvent.clipboardData || window.clipboardData;
        var pastedData = clipboardData.getData('Text');
        
        // Filtrar el contenido para que solo contenga dígitos
        var digitsOnly = pastedData.replace(/\D/g, '');
        
        // Si el contenido pegado tiene caracteres no numéricos, prevenimos el pegado normal
        if (digitsOnly !== pastedData) {
            e.preventDefault();
            // Opcional: Insertar solo los dígitos en la posición del cursor
            var input = $(this);
            // Obtener la posición actual del cursor
            var start = this.selectionStart;
            var end = this.selectionEnd;
            // Construir el nuevo valor
            var currentValue = input.val();
            var newValue = currentValue.substring(0, start) + digitsOnly + currentValue.substring(end);
            input.val(newValue);
            
            // Actualizar la posición del cursor para que quede después del texto insertado
            var newCursorPos = start + digitsOnly.length;
            this.setSelectionRange(newCursorPos, newCursorPos);
        }
    });

    $('#bank_name').on('change', function(){
      var selectedOption = $(this).find('option:selected');
      var accountDigits = selectedOption.data('account-digits');
      var accountInput = $('input[name="bank_account_number"]');

      // Actualizar validación para Nro de cuenta
      if (parseInt(accountDigits) > 0) {
        accountInput.attr('pattern', '^[0-9]{' + accountDigits + '}$');
        accountInput.attr('title', 'El número de cuenta debe tener exactamente ' + accountDigits + ' dígitos.');
        accountInput.attr('maxlength', accountDigits);
      } else {
        accountInput.attr('pattern', '^[0-9]+$');
        accountInput.attr('title', 'Solo se permiten dígitos (0-9).');
        accountInput.removeAttr('maxlength');
      }

      var cciDigits = selectedOption.data('cci-digits');
      var interbankInput = $('input[name="bank_interbank_account_number"]');

      // Actualizar validación para Nro de cuenta interbancario
      if (parseInt(cciDigits) > 0) {
        interbankInput.attr('pattern', '^[0-9]{' + cciDigits + '}$');
        interbankInput.attr('title', 'El número de cuenta interbancario debe tener exactamente ' + cciDigits + ' dígitos.');
        interbankInput.attr('maxlength', cciDigits);
      } else {
        interbankInput.attr('pattern', '^[0-9]+$');
        interbankInput.attr('title', 'Solo se permiten dígitos (0-9).');
        interbankInput.removeAttr('maxlength');
      }
    });

    $('input[name="pension_affiliated"]').change(function() {
      if ($(this).val() == 1) {
        $("#it-afiliate").show();
        $("#it-afiliate").removeClass('hidden');
        $( 'select[name="pension_name"]').attr("required", "true");
        $('input[name="pension_join"]').removeAttr("required");
        $('input[name="pension_join"]' ).prop('checked', false);
        $( '#content-pension-join' ).addClass('hidden');
        
        $("#content-pension-change").removeClass('hidden');
        $( 'input[name="pension_change"]').attr("required", "true");
        $( 'input[name="pension_change"]').attr('checked', false);

        $( '#it-not-afiliate' ).addClass('hidden');
        $("#content-pension").removeClass('hidden');
        $( 'select[name="pension_join_name"]').removeAttr("required");
        $('input[name="pension_change"]').attr("required", "true");
        $( 'select[name="pension_join_type"]').removeAttr("required");
        $( 'select[name="pension_join_type"]').val('');
        
      } else {
        $("#it-afiliate").hide();
        $("#pension-name").removeAttr("required");
        $("#pension_type").removeAttr("required");
        $("#pension_type").val("");
        $("#pension_change").removeAttr("required");

        $('#pension_name').val('');
        $( 'select[name="pension_name"]').val('');
        $( 'select[name="pension_name"]').removeAttr("required");

        $("#content-pension-change").addClass('hidden');
        $("#content-pension-type").addClass('hidden');
        $( '#content-pension-join' ).removeClass('hidden');
        $('input[name="pension_join"]').attr("required", "true");
        $( 'input[name="pension_change"]').removeAttr("required");

        $( 'select[name="pension_join_name"]').attr("required", "true");
        $( 'select[name="pension_join_name"]').val('');
        
      }
    });

    $( 'input[name="i_have_account_number"]' ).change(function(){

      const haveAccountNumber = $(this).val();

      if (haveAccountNumber == '1') {
        $('#content-i-have-account-number').show();
        $('select[name="bank_name"]').closest('.input-group').show();
        $('input[name="bank_account_number"]').attr("required", "true");
        $('select[name="bank_account_type"]').attr("required", "true");
        $('input[name="bank_interbank_account_number"]').attr("required", "true");
        
        // Armar el select con las opciones de banco y sus datos de validación en data-attributes
        $('select[name="bank_name"]').html(`<option value="">Seleccione</option>`);
        for (let bankIndex in bankList) {
          let bank = bankList[bankIndex];
          $('select[name="bank_name"]').append(
            `<option value="${bank.name}" data-account-digits="${bank.accountDigits}" data-cci-digits="${bank.cciDigits}">${bank.name}</option>`
          );
        }
      }

      if (haveAccountNumber == 0) {
        $( 'select[name="bank_name"]' ).closest('.input-group').show();
        $( '#content-i-have-account-number' ).hide();
        $( 'input[name="bank_account_number"]' ).removeAttr("required");
        $( 'select[name="bank_account_type"]' ).removeAttr("required");
        $( 'input[name="bank_interbank_account_number"]' ).removeAttr("required");

        $( 'select[name="bank_name"]' ).html(`
          <option value="">Seleccione</option>
          <option value="Banco de Crédito del Perú">Banco de Crédito del Perú</option>
          <option value="Interbank">Interbank</option>
          <option value="Scotiabank Perú">Scotiabank Perú</option>
          <option value="BBVA Continental">BBVA Continental</option>
        `);
      }
    });

    $('input[name="pension_join"]').change(function() {
   
      if ($(this).val() == 1) {
        $( "#it-not-afiliate" ).removeClass('hidden');
        $("#pension_type").attr("required", "true");
      } else { 
        $( "#it-not-afiliate" ).addClass('hidden');
        $("#pension_type").removeAttr("required");
        $("#pension_type").val('');
      }
    });
    
    $( "#pension-affiliation" ).change(function(){
 
      if ($(this).val() == 'AFP') {
        $("#pension_type").attr("required", "true");
        $("#content_pension_type").removeClass('hidden');
        $('input[name="pension_change"]').attr('required', 'true');
      } else {
        $("#content_pension_type").addClass('hidden');
        $("#pension_type").removeAttr("required");
      }
    });

    $( "#pension_join_name" ).change(function(){
      
      if ($(this).val() == 'AFP') {
        $( 'select[name="pension_join_type"]' ).attr('required', true);
        $( '#it-not-afiliate' ).removeClass('hidden');
      } else {
        $( '#it-not-afiliate' ).addClass('hidden');
        $( 'select[name="pension_join_type"]' ).removeAttr("required");
      }
    });

    $('input[name="pension_change"]').change(function() {
      if ($(this).val() == 1) {
        console.log($("#content-pension-type"));
        $("#content-pension-type").removeClass('hidden');
        $("#pension_change_type").attr("required", "true");
      } else {
        $("#content-pension-type").addClass('hidden');
        $("#pension_change_type").removeAttr("required");
      }
    });

    $( "#level_education" ).change(function(){
      $("#content_degree_obtained_type").removeClass('hidden');
      $("#content_degree_obtained_type").hide();
      $('#degree_obtained_type').removeAttr('required');

      if ($(this).val() == 'Ténico' || $(this).val() == 'Superior') {
        $("#content_degree_obtained_type").show();
        $("#degree_obtained_type").attr('required', 'true');
      } else {
        $('#degree_obtained_type').val('');
      }
    });
  });

  $(document).on('click', '.remove-rightful-claimants', function() {
    if (confirm('¿Seguro de remover el derechohabitante?')) {
      $(this).closest('tr').remove();
    }
  });

  $(document).on('change', '#rc-live-same-domicile', function(){

    var liveSameDomicile = parseBool($(this).val());
    var contentDomicile = $(this).closest('.section-rightful-claimant').find('.content-domicile');
    contentDomicile.hide();
  
    if (!liveSameDomicile) {
      contentDomicile.show();  
    }
  });

  function validDataRightfulClaimants()
  {   
    $(".input-group", ".section-rightful-claimant").removeClass('has-error');

    var elementError = $( ".section-rightful-claimant" ).find("input,select").filter(function() {
      
      var element = $(this),
          elementName = $.trim(element.prop('name'));

      // Verificar si es un radio button
      if (element.prop('type') === 'radio') {
          var name = element.prop('name');
          // Verificar si hay al menos un radio button seleccionado con el mismo nombre
          if ($("input[name='" + name + "']:checked").length === 0) {
              element.closest('.input-group').addClass('has-error');
              return true;
          }
          return false;
      }
      
      if (elementName == 'family_bond_cert_type' && $( 'input[name="i_have_cert_kinship"]:checked' ).val() == 0) {
          return false;
      }
      
      if (elementName == 'attached_cert_cohabitation' && $( 'input[name="i_have_cert_kinship"]:checked' ).val() == 0) {
          return false;
      }

      if (element.val() != '' || 
          elementName == 'file' || 
          $(element).prop('id') == 'rc-id') {
        return false;
      }

      var selectKinship = $( "#rc-kinship" );

      //Si vinculo es matrimonial
      if (selectKinship.val() == '1' || selectKinship.val() == '2') {
        if (elementName == 'place_birth' || 
           elementName == 'place_birth_certificate') {

          return false;
        }
      }

      var liveSameDomicile = parseBool($( "#rc-live-same-domicile" ).val());

      if (liveSameDomicile) {
        if (elementName == 'domicile' || 
            elementName == 'ubigeo' ||
            elementName == 'domicile_way_id' ||
            elementName == 'domicile_number' ||
            elementName == 'domicile_interior') {
          return false;
        }
      }

      if (elementName == 'domicile_interior') {
        return false;
      }

      element.closest('.input-group').addClass('has-error');
      return true;
    });
      
    return {
      'errors' : elementError.length,
      'elementError' : elementError
    }    
  }

  $( "#form_rtps" ).submit(function(e) {

    e.preventDefault();

    const dataExtra = {
      'full_mobile_phone_number': ($( 'input[name="mobile"]' ).data('iti-instance')).getNumber(intlTelInput.utils.numberFormat.E164),
      'emergency_contact_full_mobile_phone_number': ($( 'input[name="emergency_contact_mobile"]' ).data('iti-instance')).getNumber(intlTelInput.utils.numberFormat.E164)
    };

    const data = $(this).serialize() + '&' + $.param(dataExtra);
    const url = $(this).prop('action');

    $( '#container-form-rtps' ).addClass('load load-image');

    $.post(url, data, function(response) {
      if (response.status == true) {
        window.location = response.data.redirect_url;
        return;
      }

      if (response.status == false) {
        if (response.data && response.data.redirect_url) {
          window.location = response.data.redirect_url;
        } else {
          $( '#container-form-rtps' ).removeClass('load load-image');
          toastr["error"](response.message);
        }
      }
    }, 'json')
    .error(function() {
      $( '#container-form-rtps' ).removeClass('load load-image');
      toastr["error"]('Ha ocurrido un error');
    });

    return false;
  });

  $( '#driver-license' ).change(function(){
    
    $( '#driver-license-type-content' ).hide();

    if ($(this).val() == 1) {
      $( '#driver-license-type-content' ).show();
    }
  }); 

  $( "#born_department" ).change(function(){

    var url = "<?php echo site_url('ubigeos/get_provinces_by'); ?>";

    var data = {
        department: $(this).val() 
    };

    $.post(url, data, function(result){
      provinces = result.data;
      
      $( "#born_province" ).empty().html("<option>Buscando...</option>");

      var provinces_options = '<option value="">Seleccione</option>';

      for (row in provinces) {
          province_row = provinces[row];

          provinces_options+=`<option value="${province_row.province}">${province_row.province}</option>`;
      }

      $( "#born_province" ).html(provinces_options);
      $( "#born_district" ).empty().html("<option>Seleccione</option>");

    }, 'json');
  });

  $( "#born_province" ).change(function(){

    var url = "<?php echo site_url('ubigeos/get_districts_by'); ?>";

    var data = {
        province: $(this).val() 
    };

    $.post(url, data, function(result){
      districts = result.data;
      
      $( "#born_district" ).empty().html("<option>Buscando...</option>");

      var distrinct_options = '<option value="">Seleccione</option>';

      for (row in districts) {
          distrinct_row = districts[row];

          distrinct_options+=`<option value="${distrinct_row.district}">${distrinct_row.district}</option>`;
      }

      $( "#born_district" ).html(distrinct_options);

    }, 'json');
  });

  $( "#current_country" ).change(function(){

    $( "#content-current-department" ).hide();
    $( "#content-current-province" ).hide();
    $( "#content-current-district" ).hide();
    $( "#content-current-city" ).hide();

    if ($(this).val() == '56') {
      $( "#content-current-department" ).show();
      $( "#content-current-province" ).show();
      $( "#content-current-district" ).show();
    } 

    if ($(this).val() != '56') {
      $( "#content-current-city" ).show();
    }
  });

  $( "#current_department" ).change(function(){

    var url = "<?php echo site_url('ubigeos/get_provinces_by'); ?>";

    var data = {
      department: $(this).val() 
    };

    $.post(url, data, function(result){
      provinces = result.data;
      
      $( "#current_province" ).empty().html("<option>Buscando...</option>");

      var provinces_options = '<option value="">Seleccione</option>';

      for (row in provinces) {
          province_row = provinces[row];

          provinces_options+=`<option value="${province_row.province}">${province_row.province}</option>`;
      }

      $( "#current_province" ).html(provinces_options);
      $( "#current_district" ).empty().html("<option>Seleccione</option>");

    }, 'json');
  });

  $( "#current_province" ).change(function(){

    var url = "<?php echo site_url('ubigeos/get_districts_by'); ?>";

    var data = {
        province: $(this).val() 
    };

    $.post(url, data, function(result){
      districts = result.data;
      
      $( "#current_district" ).empty().html("<option>Buscando...</option>");

      var distrinct_options = '<option value="">Seleccione</option>';

      for (row in districts) {
          distrinct_row = districts[row];

          distrinct_options+=`<option value="${distrinct_row.district}">${distrinct_row.district}</option>`;
      }

      $( "#current_district" ).html(distrinct_options);

    }, 'json');
  });

  function dateDiffYears(date) {
    var startDate  = new Date(date).getTime();
    var endDate    = new Date("<?php echo date('Y-m-d'); ?>").getTime();

    var diff = endDate - startDate;

    return Math.floor(diff / (1000 * 60 * 60 * 24 * 365));
  }

  $( '#degree_obtained' ).change(function() {
    var year = $.trim($(this).find('option:selected').data('year'));
    var speciality = $.trim($(this).find('option:selected').data('speciality'));
    var institution = $.trim($(this).find('option:selected').data('institution'));
  });

  $( '#btn-modal-education-add' ).click(function(){
    $( '#modal-education-add' ).remove();
    $(`<div class="modal fade" id="modal-education-add"></div>`).appendTo('body');
    
    const url = "<?php echo site_url('jobseeker/form_rtps/form_education'); ?>";

    $.post(url, {}, function(view){
      $( '#modal-education-add' ).html(view).modal('show');
    });
  });

  $( '#btn-modal-education-edit' ).click(function(){

    if ($( '#degree_obtained' ).val() == '') {
      toastr['error']('Para editar debe seleccionar un nivel académico');
      return;
    }

    const id = $( '#degree_obtained option:selected' ).data('id');
    const institutionEducType = $( `input[name="education[${id}][institution_educ_type]"]`).val();
    const institutionType = $( `input[name="education[${id}][institution_type]"]`).val();
    const institution = $( `input[name="education[${id}][institution]"]`).val();

    const data = {
      institution_educ_type: institutionEducType,
      institution_type: institutionType,
      institution: institution
    };
    
    $( '#modal-education-add' ).remove();
    $(`<div class="modal fade" id="modal-education-add"></div>`).appendTo('body');
    
    const url = "<?php echo site_url('jobseeker/form_rtps/form_education'); ?>";

    $.post(url, data, function(view){
      $( '#modal-education-add' ).html(view).modal('show');
    
      $( 'input[name="id"]', '#frm_add_education' ).val($( `input[name="education[${id}][id]"]`).val());
      $( 'select[name="degree_title"]', '#frm_add_education' ).val($( `input[name="education[${id}][degree_title]"]`).val());
      $( 'input[name="major_subject"]', '#frm_add_education' ).val($( `input[name="education[${id}][major_subject]"]`).val());
      $( 'select[name="edu_country"]', '#frm_add_education' ).val($( `input[name="education[${id}][edu_country]"]`).val());
      $( 'select[name="institution_educ_type"]', '#frm_add_education' ).val($( `input[name="education[${id}][institution_educ_type]"]`).val());
      $( 'select[name="institution_educ_class"]', '#frm_add_education' ).val($( `input[name="education[${id}][institution_educ_class]"]`).val());
      $( 'select[name="institution_type"]', '#frm_add_education' ).val($( `input[name="education[${id}][institution_type]"]`).val());
      $( 'select[name="institution"]', '#frm_add_education' ).val($( `input[name="education[${id}][institution]"]`).val());
      $( 'select[name="career"]', '#frm_add_education' ).val($( `input[name="education[${id}][career]"]`).val());
      $( 'select[name="year_start_date"]', '#frm_add_education' ).val($( `input[name="education[${id}][year_start_date]"]`).val());
      $( 'select[name="month_start_date"]', '#frm_add_education' ).val($( `input[name="education[${id}][month_start_date]"]`).val());
      $( 'select[name="year_end_date"]', '#frm_add_education' ).val($( `input[name="education[${id}][year_end_date]"]`).val());
      $( 'select[name="month_end_date"]', '#frm_add_education' ).val($( `input[name="education[${id}][month_end_date]"]`).val());
      $( 'input[name="tuition_number"]', '#frm_add_education' ).val($( `input[name="education[${id}][tuition_number]"]`).val());

      const studying = $( `input[name="education[${id}][studying]"]`).val();

      if (studying == 'true') {
        $( 'input[name="studying"]', '#frm_add_education' ).prop('checked', true);
        $( 'input[name="studying"]', '#frm_add_education' ).change();
      }

      $( 'select[name="year_start_date"]', '#frm_add_education' ).change();
    });
  });

  $( '#emergency_contact_kinship' ).change(function(){
    const inputDisabled = $(this).val() == '';

    $( '#emergency_contact_name' ).prop('disabled', inputDisabled);
    $( '#emergency_contact_name' ).closest('.input-group ').removeClass('has-error');
    
    $( '#emergency_contact_mobile_code' ).prop('disabled', inputDisabled);
    $( '#emergency_contact_mobile_code' ).closest('.input-group ').removeClass('has-error');

    $( '#emergency_contact_mobile' ).prop('disabled', inputDisabled);
    $( '#emergency_contact_mobile' ).closest('.input-group ').removeClass('has-error');
  });

  $( '#present-address' ).keyup(function(){
    const maxLength = 100;
    const value = $(this).val();

    $( '.present-address-alert-length' ).hide();

    if (value.length > maxLength) {
      $( '.present-address-alert-length' ).text(`Máximo 100 caracteres de longitud, actuales: ${ parseInt(value.length) }`).show();
    } else {
      $(this).closest('.input-group').removeClass('has-error');
      $( '.errowbox', $(this).closest('.input-group')).remove();
    }
  });

  $( '#present-address' ).keyup();
  $( "#pension-affiliation" ).change();
  $( "#born-country" ).change();
  $( '#current_country' ).change();
  $( "#rc-ubigeo" ).select2();
  $( '#degree_obtained' ).change();
  $( '#driver-license' ).change();
  $( '#emergency_contact_kinship' ).change();
  $( "#bank_name" ).select2();
  $( "#payment_cts_bank_name" ).select2();
  $( "#current_country" ).select2();

  $( '#first_name' ).bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
  });
  
  $( '#paternal_last_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
  });
  
  $( '#maternal_last_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
  });

  document.addEventListener("DOMContentLoaded", function() {
    let steps = document.getElementsByClassName("step");
    let stepIndicator = document.getElementById("step-indicator");
    
    validateBornCountry($( "#born-country" ).val());

    for (let i = 0; i < steps.length; i++) {
        let circle = document.createElement("div");
        circle.className = "step-circle";
        stepIndicator.appendChild(circle);

        if (i < steps.length - 1) {
            let line = document.createElement("div");
            line.className = "line";
            stepIndicator.appendChild(line);
        }
    }

    showStep(0);
  });
  
  $( 'input[name="i_have_cert_kinship"]' ).change(function(){
    $( '.content-cert-kinship-type' ).hide();
    $( '.content-cert-kinship-attach' ).hide();
    $( '#rc-family-bond-certs' ).val('');
    $( '#rc-attached-cert-cohabitation' ).val('');
    
    $( '.document-load', '.content-cert-kinship-attach' ).show();
    $( '.document-view', '.content-cert-kinship-attach' ).hide();
       console.log($(this).val()); 
    if ($(this).val() == 1) {
      $( '.content-cert-kinship-type' ).show();
      $( '.content-cert-kinship-attach' ).show();
    }
  });

  let currentStep = 0;

  function showStep(n) {
      var additionalInfo = document.getElementById('additional-info');
      let steps = document.getElementsByClassName("step");


      for (let i = 0; i < steps.length; i++) {
          steps[i].classList.remove("active");
      }
      steps[n].classList.add("active");

      let indicators = document.getElementsByClassName("step-circle");
      let lines = document.getElementsByClassName("line");

      for (let i = 0; i < indicators.length; i++) {
          indicators[i].className = "step-circle";
          if (i < n) {
              indicators[i].classList.add("completed");
          } else if (i == n) {
              indicators[i].classList.add("active");
          } else {
              indicators[i].classList.add("future");
          }
      }

      for (let i = 0; i < lines.length; i++) {
          lines[i].className = "line";
          if (i < n) {
              lines[i].classList.add("completed");
          }
      }

      // Mostrar información adicional cuando el paso es 8 (Ingreso de 5ta Categoría)
      if (n === 7) {
          var selectedFifthCategoryIncome = $('input[name="fifth_category_income"]:checked').val();

          if (selectedFifthCategoryIncome == '0') {
            additionalInfo.style.display = "block";
          }
      } else {
          additionalInfo.style.display = "none";
      }

      if (n == 0) {
          document.getElementById("prevBtn").style.display = "none";
      } else {
          document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (steps.length - 1)) {
          document.getElementById("nextBtn").style.display = "none";
          document.getElementById("submit_button").style.display = "inline";
      } else {
          document.getElementById("nextBtn").style.display = "inline";
          document.getElementById("submit_button").style.display = "none";
      }
  }

  function nextPrev(n) {
      let steps = document.getElementsByClassName("step");
      let currentInputs = steps[currentStep].querySelectorAll("input");
      let currentSelects = steps[currentStep].querySelectorAll("select");
      let currentTextarea = steps[currentStep].querySelectorAll("textarea");
      let radioGroups = steps[currentStep].querySelectorAll("input[type='radio'][required]");

      // Validar declaracion
      var additionalInfo = document.getElementById('declaration-checkbox');
      if (currentStep === 7) {
        var selectedFifthCategoryIncome = $('input[name="fifth_category_income"]:checked').val();
        if (!additionalInfo.checked && selectedFifthCategoryIncome == "0") {
          toastr["warning"]("Por favor, confirme la declaración para continuar.");

          return false;
        }
      }

      // Validar los campos del paso actual
      for (let i = 0; i < currentInputs.length; i++) {
          if (currentInputs[i].hasAttribute("required") && !currentInputs[i].value) {
              currentInputs[i].classList.add("invalid");
              toastr["warning"]("Por favor, llene todos los campos requeridos.");
              return false; 
          } else {
              currentInputs[i].classList.remove("invalid");
          }

          if (currentStep === 9) {
            // Validar maxlength únicamente para el campo "bank_account_number"
            if (
              currentInputs[i].name === "bank_account_number" &&
              currentInputs[i].hasAttribute("maxlength") &&
              currentInputs[i].value
            ) {
              let maxLength = parseInt(currentInputs[i].getAttribute("maxlength"));

              var accountDigits = $('#bank_name option:selected').data('account-digits');

              // Validar que el valor sea numérico (solo dígitos)
              if (!/^\d+$/.test(currentInputs[i].value)) {
                currentInputs[i].classList.add("invalid");
                toastr["warning"]("El campo número de cuenta solo puede contener números.");
                return false;
              }

              // Validar que el valor tenga exactamente la longitud requerida
              if (currentInputs[i].value.length !== accountDigits && accountDigits > 0) {
                currentInputs[i].classList.add("invalid");
                toastr["warning"]("El campo número de cuenta debe tener exactamente " + accountDigits + " dígitos.");
                return false;
              }
            }

            if (
              currentInputs[i].name === "bank_interbank_account_number" &&
              currentInputs[i].value
            ) {
              let maxLength = parseInt(currentInputs[i].getAttribute("maxlength"));
              var cciDigits = $('#bank_name option:selected').data('cci-digits');

              // Validar que el valor sea numérico (solo dígitos)
              if (!/^\d+$/.test(currentInputs[i].value)) {
                currentInputs[i].classList.add("invalid");
                toastr["warning"]("El campo número de cuenta solo puede contener números.");
                return false;
              }

              // Validar que el valor tenga exactamente la longitud requerida
              if (currentInputs[i].value.length !== cciDigits && cciDigits > 0) {
                currentInputs[i].classList.add("invalid");
                toastr["warning"]("El campo número de cuenta interbancario debe tener exactamente " + maxLength + " dígitos.");
                return false;
              }
            }
          }
      }

      for (let i = 0; i < currentSelects.length; i++) {
          if (currentSelects[i].hasAttribute("required") && !currentSelects[i].value) {
              currentSelects[i].classList.add("invalid");
              toastr["warning"]("Por favor, seleccione una opción.");
              return false;
          } else {
              currentSelects[i].classList.remove("invalid");
          }
      }

      for (let i = 0; i < currentTextarea.length; i++) {
          if (currentTextarea[i].hasAttribute("required") && !currentTextarea[i].value) {
              currentTextarea[i].classList.add("invalid");
              toastr["warning"]("Por favor, llene todos los campos requeridos.");
              return false;
          } else {
              currentTextarea[i].classList.remove("invalid");
          }
      }

      let radioNames = new Set();
      let allValid = true;
      radioGroups.forEach(radio => radioNames.add(radio.name));
      radioNames.forEach(name => {
          let checked = steps[currentStep].querySelector(`input[name="${name}"]:checked`);
          if (!checked) {
              allValid = false;
              toastr["warning"]("Por favor, seleccione una opción.");
              return false;
          }
      });

      if (!allValid) return false;

      // Si la validación pasa, continuar al siguiente paso
      steps[currentStep].classList.remove("active");
      currentStep += n;

      showStep(currentStep);

      document.querySelector('.container-steps').scrollIntoView({ behavior: 'smooth' });
  }

  document.addEventListener("DOMContentLoaded", function() {
      let currentStep = 0;

      function updateNextButtonState() {
          let steps = document.getElementsByClassName("step");
          let currentInputs = steps[currentStep].querySelectorAll("input[required], select[required], input[type='radio'][required]");
          let allValid = true;

          currentInputs.forEach(function(input) {
              if (!input.value) {
                  allValid = false;
              }
          });

          //document.getElementById("nextBtn").disabled = !allValid;
      }

      function updateDivs() {
          let divAfiliate = document.getElementById('it-afiliate');
          let divNotAfiliate = document.getElementById('it-not-afiliate');
          let selectedValue = document.querySelector('input[name="pension_is_affiliate"]:checked');

          if (selectedValue) {
              if (selectedValue.value === "1") {
                  divAfiliate.style.display = "block";
                  divNotAfiliate.style.display = "none";
              } else if (selectedValue.value === "0") {
                  divAfiliate.style.display = "none";
                  divNotAfiliate.style.display = "block";
              }
          }
      }

      // Función para manejar cambios en los inputs y selects
      function handleInputChange() {
          updateNextButtonState();
          updateDivs();
      }

      // Añadir event listeners a todos los inputs y selects requeridos
      document.querySelectorAll('input[required], select[required]').forEach(function(input) {
          input.addEventListener('input', handleInputChange);
          input.addEventListener('change', handleInputChange);
      });

      document.getElementById("born-country").addEventListener("change", function() {
        validateBornCountry(this.value);
        updateNextButtonState();
      });
  });

  function validateBornCountry(value) {
        let department = document.getElementById("content-born-department");
        let province = document.getElementById("content-born-province");
        let district = document.getElementById("content-born-district");
        let departmentSelect = document.getElementById("born_department");
        let provinceSelect = document.getElementById("born_province");
        let districtSelect = document.getElementById("born_district");

        if (value === "56") {
            $(department).show();
            $(province).show();
            $(district).show();
            departmentSelect.setAttribute("required", "true");
            provinceSelect.setAttribute("required", "true");
            districtSelect.setAttribute("required", "true");
        } else {

            $(department).hide();
            $(province).hide();
            $(district).hide();
            departmentSelect.removeAttribute("required");
            provinceSelect.removeAttribute("required");
            districtSelect.removeAttribute("required");
        }
      }

</script>
</body>
</html>
