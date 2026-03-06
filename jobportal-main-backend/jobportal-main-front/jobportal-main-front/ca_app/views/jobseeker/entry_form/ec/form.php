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

  .subtitle-country {
    color: #007bff;
    margin-top: 1em;
    text-transform: uppercase;
  }

  .emoji-country {
    font-size: 20px;
  }

  .input-group p:first-child {
      margin-right: 50px;
  }

  .text-link {
    color: #0D6EFD;
    font-size: 13px;
    margin-bottom: 20px;
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
    font-size: 14px;
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

  #content-document {
    margin: 20px 0px;
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
  <div class="row"> 
    <?php echo form_open_multipart('jobseeker/entry_form/ec/form_ec/save',array('id' => 'entry_form', 'onSubmit' => ''));?>
    <input type="hidden" name="process_id" value="<?php echo $process_id; ?>">
    <input type="hidden" name="contract_document_type_id" value="<?php echo $contract_document_type->id; ?>">

    <div <?php echo $this->session->userdata('menu') != '0' ? 'class="col-md-3"' : 'class="col-md-2"'; ?>>
      <div class="dashiconwrp">
        <?php if ($this->session->userdata('menu') != '0'): ?>
          <?php $this->load->view('jobseeker/common/jobseeker_menu'); ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-md-9">  
      <?php echo $this->session->flashdata('msg'); ?>
    
      <div id="container-entry-form">
        <div style="color: #0D6EFD; font-size: 14px; font-family: Inter; font-weight: 600; line-height: 16px; word-wrap: break-word">
          <a href="#" class="_link-back">
            <span class="glyphicon glyphicon-menu-left"></span><i class="bi bi-chevron-left"></i>
            Volver
          </a>
        </div>
        <div class="title-container">
          <h1><?php e($contract_document_type->name); ?></h1>
          <p class="subtitle">Ingrese los datos solicitados para proceder con la contratación</p>
          <p class="subtitle subtitle-country">
            <span class="emoji-country">
              <?php if ($process_country->flag_icon): ?>
              <img width="22" height="22" src="<?php echo $process_country->flag_icon; ?>" />
              <?php endif; ?>
            </span> OVERALL <?php e($process_country->country_name); ?> &#8226; <?php e($contract_document_type->company_name); ?></p>
        </div>
        <div id="step-indicator" class="step-indicator"></div>
            <div class="step active">
              <div class="container-steps">
                <h2>Datos personales</h2>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Documento identidad<span></span></p>
                  <div class="col-12 col-sm-9">
                    <table width="100%">
                      <tr>
                        <td style="vertical-align: bottom;padding: 0;padding-right: 10px;">
                          <select class="form-control input-select2" name="document_type_id" style="width: 100%;height: 100%;" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($document_types as $row): ?>
                              <option value="<?php echo $row->id; ?>" <?php echo ($jobseeker->document_type == $row->id) ? 'selected' : ''; ?>>
                                <?php e($row->abbreviation); ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                        <td>
                          <input name="document_number" type="text" class="form-control" placeholder="Número de documento" value="<?php echo $jobseeker->document_number; ?>" maxlength="25" required>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
      
                <div class="input-group row">
                  <p class="col-12 col-sm-3">Email <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input name="email" type="text" class="form-control" placeholder="Email" value="<?php echo $jobseeker->email; ?>" maxlength="180" required>
                  </div>
                  <?php echo form_error('first_name'); ?> 
                </div>
                            
                <div class="input-group row">
                  <p class="col-12 col-sm-3">Primer nombre <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input id="first_name" name="first_name" type="text" class="form-control" placeholder="Primer nombre" value="<?php echo @$entry_form->first_name; ?>" maxlength="30" required>
                  </div>
                  <?php echo form_error('first_name'); ?> 
                </div>
                
                 <div class="input-group row">
                  <p class="col-12 col-sm-3">Segundo nombre <span></span></p>
                  <div class="col-12 col-sm-9">
                    <input id="second_name" name="second_name" type="text" class="form-control" placeholder="Segundo nombre" value="<?php echo @$entry_form->second_name; ?>" maxlength="30">
                  </div>
                  <?php echo form_error('second_name'); ?> 
                </div>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Tercer nombre <span></span></p>
                  <div class="col-12 col-sm-9">
                    <input id="third_name" name="third_name" type="text" class="form-control" placeholder="Tercer nombre" value="<?php echo @$entry_form->third_name; ?>" maxlength="30">
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
                    <select class="form-control input-select2" name="gender" required>
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
                    <select class="form-control input-select2" name="civil_status" required>
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

              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Domicilio actual</h2>
                
                <div class="input-group row <?php echo (form_error('present_address')) ? 'has-error' : '';?>">
                  <p class="col-12 col-sm-3">Dirección <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <textarea id="present-address" rows="3" class="form-control" required name="present_address"><?php echo set_value('present_address') ? set_value('present_address') : $jobseeker->present_address; ?></textarea>
                    <span style="font-size:12px;color:red;display:none;" class="present-address-alert-length"></span>
                  </div>
                </div>
                
              </div>
            </div>
            <div class="step">
              <div class="container-steps">
                <h2>Lugar de origen</h2>

                <div class="input-group row">
                  <p class="col-12 col-sm-3">Eres Extranjero? <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select name="is_foreign" class="form-control input-select2" required>
                      <option value="">Seleccione</option>
                      <?php  ?>
                        <option value="1" <?php echo @$entry_form->origin_country_id != @$entry_form->form_country_id ? 'selected="selected"' : ''; ?>>
                          Si
                        </option>
                        <option value="0" <?php echo @$entry_form->origin_country_id == @$entry_form->form_country_id ? 'selected="selected"' : ''; ?>>
                          No
                        </option>
                    </select>
                  </div>
                </div>

                <div class="input-group row" style="display: none;">
                  <p class="col-12 col-sm-3">País <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select name="origin_country_id" class="form-control input-select2" style="width: 100%;">
                      <option value="">Seleccione</option>
                      <?php $origin_country_id = @$entry_form->origin_country_id; ?>
                      <?php foreach ($result_countries as $row_country): ?>
                        <?php $selected = $origin_country_id == $row_country->ID && @$entry_form->origin_country_id != 49 ? 'selected' : ''; ?>
                        <option value="<?php echo $row_country->ID; ?>" <?php echo $selected; ?>>
                          <?php echo $row_country->country_name; ?>    
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="input-group row" style="display: none;">
                  <p class="col-12 col-sm-3">Tipo de visa <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <select name="visa_type" class="form-control input-select2">
                      <option value="">Seleccione</option>
                        <option value="Tipo 1" <?php echo $entry_form->visa_type == 'Tipo 1' ? 'selected="selected"' : ''; ?>>
                          Tipo 1
                        </option>
                        <option value="Tipo 2" <?php echo $entry_form->visa_type == 'Tipo 2' ? 'selected="selected"' : ''; ?>>
                          Tipo 2
                        </option>
                    </select>
                  </div>
                </div>

              </div>
            </div>

            <div class="step">
              <div class="container-steps">
                <h2>Seguridad social</h2>
                
                <div class="input-group row">
                  <p class="col-12 col-sm-3">Nro Seguridad Social <span>*</span></p>
                  <div class="col-12 col-sm-9">
                    <input class="form-control" maxlength="20" required name="social_security_number" value="<?php e(@$entry_form->social_security_number); ?>"/>
                  </div>
                </div>
                
              </div>
            </div>

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
                        <th>Nombres y apellidos</th>
                        <th>Parentesco</th>
                        <th></th>
                      </tr>
                      <?php foreach ($rightful_claimants as $key => $person): ?>
                        <tr data-rc-id="<?php echo $person->id; ?>" >
                          <td>
                            <?php echo $person->first_name . ' ' . $person->paternal_last_name . ' ' . $person->maternal_last_name; ?>
                          </td>
                          <td>
                            <?php echo $person->kinship_name; ?>
                          </td>
                          <td>
                            <button type="button" class="btn btn-xs btn-rc-action edit-rightful-claimant" style="background-color: transparent;">
                              <img src="<?php echo base_url('public/images/edit-table.svg');?>" />
                            </button>
                            <button type="button" class="btn btn-xs btn-rc-action remove-rightful-claimants" style="background-color: transparent;">
                              <img src="<?php echo base_url('public/images/delete-table.svg');?>" />
                            </button>
                            <div class="rightful-claimants-inputs<?php echo $person->id;?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][first_name]" value="<?php echo $person->first_name; ?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][paternal_last_name]" value="<?php echo $person->paternal_last_name; ?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][maternal_last_name]" value="<?php echo $person->maternal_last_name; ?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][kinship]" value="<?php echo $person->kinship_id; ?>" class="input-kinship">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][second_name]" value="<?php echo $person->second_name; ?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][gender]" value="<?php echo $person->gender_id; ?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][birthdate]" value="<?php echo $person->birthdate; ?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][document_type]" value="<?php echo $person->identity_document_type_id; ?>">
                              <input type="hidden" name="rightful_claimants[<?php echo $person->id; ?>][document_number]" value="<?php echo $person->identity_document_number; ?>">
                            </div>
                          </td>
                        </tr>
                      <?php endforeach ?>
                    </table>
                  </div>
                </div>
                <div class="content-conditions-policy hide"  >
                  * Al hacer clic en "Guardar", aceptas nuestras <a href="#" data-toggle="modal" data-target="#modal-conditions-policy">Condiciones y política de ingreso</a>. 
                </div>
              </div>
            </div>
            <div class="buttons">
              <button class="btn btn-sm" type="button" id="prevBtn">Atrás</button>
              <button class="btn btn-sm" type="button" id="nextBtn">Siguiente</button>
              <button class="btn btn-sm" type="submit" id="submit_button" style="display:none">Guardar</button>
            </div>
        </div>
        
      </div>
      
    </div>
    <?php echo form_close(); ?>
  </div>
  
  <!-- Modal -->
  <div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>
  <?php $this->load->view('jobseeker/entry_form/ec/common/modal_add_rightful_claimant'); ?>
</div>

<?php $this->load->view('common/bottom_ads');?>
<!--Footer-->
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>

<script type="text/javascript">

  (function(){

    let currentStep = 0;

    function createStepsForm(event) {
      let steps = document.getElementsByClassName("step");
      let stepIndicator = document.getElementById("step-indicator");
      
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
    }

    function showStep(n) {
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

      if (n < 0) {
        steps[currentStep].classList.remove("active");
        currentStep += n;
        showStep(currentStep);
        document.querySelector('.container-steps').scrollIntoView({ behavior: 'smooth' });
        return;
      }
      
      let currentInputs = steps[currentStep].querySelectorAll("input");
      let currentSelects = steps[currentStep].querySelectorAll("select");
      let currentTextarea = steps[currentStep].querySelectorAll("textarea");
      let radioGroups = steps[currentStep].querySelectorAll("input[type='radio'][required]");

      // Validar los campos del paso actual
      for (let i = 0; i < currentInputs.length; i++) {
        if (currentInputs[i].hasAttribute("required") && !currentInputs[i].value) {
          currentInputs[i].classList.add("invalid");
          toastr["warning"]("Por favor, llene todos los campos requeridos.");
          return false; 
        } else {
          currentInputs[i].classList.remove("invalid");
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

    function saveEntryForm(e) {
      e.preventDefault();

      const dataExtra = {
        'full_mobile_phone_number': ($( 'input[name="mobile"]' ).data('iti-instance')).getNumber(intlTelInput.utils.numberFormat.E164)
      };

      const data = $(this).serialize() + '&' + $.param(dataExtra);
      const url = $(this).prop('action');

      $( '#container-entry-form' ).addClass('load load-image');

      $.post(url, data, function(response) {
        if (response.status == true) {
          window.location = response.data.redirect_url;
          return;
        }

        if (response.status == false) {
          if (response.data && response.data.redirect_url) {
            window.location = response.data.redirect_url;
          } else {
            $( '#container-entry-form' ).removeClass('load load-image');
            toastr["error"](response.message);
          }
        }
      }, 'json')
      .error(function() {
        $( '#container-entry-form' ).removeClass('load load-image');
        toastr["error"]('Ha ocurrido un error');
      });

      return false;
    }

    function init() {
      //Init eventos
      document.addEventListener("DOMContentLoaded", createStepsForm);

      $( "#entry_form" ).submit(saveEntryForm);

      $( '#prevBtn' ).click(function(){
        nextPrev(-1);
      });

      $( '#nextBtn' ).click(function(){
        nextPrev(1);
      });
    
      $( '#first_name' ).bind('keyup blur',function(){ 
        $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
      });
      
      $( '#paternal_last_name').bind('keyup blur',function(){ 
        $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
      });
      
      $( '#maternal_last_name').bind('keyup blur',function(){ 
        $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ'\s]/g,'') ); 
      });

      $( 'select[name="is_foreign"]' ).change(function(){
        $( 'select[name="origin_country_id"]' ).closest('.input-group').hide();
        $( 'select[name="origin_country_id"]' ).removeAttr('required');
        $( 'select[name="visa_type"]' ).closest('.input-group').hide();
        $( 'select[name="visa_type"]' ).removeAttr('required');

        if ($(this).val() == 1) {
          $( 'select[name="origin_country_id"]' ).closest('.input-group').show();
          $( 'select[name="origin_country_id"]' ).attr('required', true);
          $( 'select[name="visa_type"]' ).closest('.input-group').show();
          $( 'select[name="visa_type"]' ).attr('required', true);
        }
      });
      
      // Init otros componentes
      const iti_mobile = window.intlTelInput($( 'input[name="mobile"]' )[0], {
        loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
        separateDialCode: true,
        autoPlaceholder: 'aggressive',
        initialCountry: 'mx'
      });

      $( 'input[name="mobile"]' ).data('iti-instance', iti_mobile);
      $( ".iti__search-input" ).attr({'placeholder' : 'Buscar'});
      $( '.input-select2' ).select2();
      $( '#current_country' ).change();
      $( 'select[name="is_foreign"]' ).change();
    }

    //Inicializar 
    init();
  })();
  
</script>
</body>
</html>
