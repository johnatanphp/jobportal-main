<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('common/meta_tags'); ?>
<title><?php echo $title;?></title>
<?php $this->load->view('common/before_head_close'); ?>
<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.7/themes/base/jquery.ui.all.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
  .ui-autocomplete { 
    z-index:99999999; 
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
    border: 1px solid #ccc;
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

  .document-load {
    __text-align: center;
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
  
  .formwraper {
    border:0;
  }
</style>
</head>
<body>
<?php $this->load->view('common/after_body_open'); ?>
<div class="siteWraper">
<div>
  <div> 
  <?php echo form_open_multipart('embed/jobseeker/requested_documents/form_rtps/index/' . $jobseeker->ID, ['name' => 'form_rtps', 'id' => 'form_rtps', 'onSubmit' => '']); ?>
    <?php echo input_embed_token(); ?>
    <div>  
      <?php echo $this->session->flashdata('msg'); ?>
      <div class="formwraper">
        
        <?php if (true): ?>
        <div class="formint">
          <?php if ($this->session->userdata('is_job_seeker')): ?>
            <div style="text-align: right;">
              <a href="#" class="show-rs-doc-comments" data-document="form_rtps">
                Ver comentarios recibidos
              </a>  
            </div>  
          <?php endif; ?>        

          <?php if ($form_rtps): ?>
            <div style="text-align: right;">
              <a href="<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/show/' . $form_rtps->seeker_ID); ?>" 
                class="btn btn-xs btn-primary">
                Regresar
              </a>  
            </div>  
          <?php endif; ?>
          
          <h3 class="sub-title-h3">Datos Personales</h3>
          <div class="input-group">
            <label class="input-group-addon">Nombre completo <span></span></label>
            <label>
              <?php echo mb_strtoupper($jobseeker->first_name . ' ' . $jobseeker->last_name); ?>    
            </label>
          </div>

          <div class="input-group">
            <label class="input-group-addon">Documento <span></span></label>
              <label>
                <?php echo document_type_text($jobseeker->document_type) . ' - ' . $jobseeker->document_number; ?>
              </label>
          </div>
          <div class="input-group">
            <label class="input-group-addon">Sexo <span></span></label>
            <label>
              <?php echo gender_text($jobseeker->gender); ?>
            </label>
          </div>
          <div class="input-group">
            <label class="input-group-addon">Fecha de nacimiento <span></span></label>
            <label>
              <?php echo format_date($jobseeker->dob, 'd/m/Y'); ?>
            </label>
          </div>

          <div class="input-group">
            <label class="input-group-addon">Email <span></span></label>
            <label><?php echo $jobseeker->email; ?></label>
          </div>
          
          <div class="input-group">
            <label class="input-group-addon">Estado civil<span></span></label>
            <label>
              <?php echo civil_status_text($jobseeker->civil_status); ?>
            </label>
          </div>

          <div class="input-group">
            <label class="input-group-addon" name="nationality">Nacionalidad <span></span></label>
            <label>
              <?php echo citizen_text($jobseeker->nationality); ?>
            </label>
          </div>

        <div class="input-group">
          <label class="input-group-addon">Ha trabajado para overall <span></span></label>
          <label>
            <?php echo $worked_in_overall ? 'SI' : 'NO'; ?>
          </label>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Discapacidad <span>*</span></label>
          <select class="form-control" name="disability_type" id="disability_type">
            <option value="">Seleccione</option>
            <option value="0" <?php echo !$jobseeker->disability ? 'selected' : ''; ?>>No presento discapacidad</option>
            <?php foreach ($disabilities as $row_disability): ?>
              <option value="<?php echo $row_disability->id; ?>" <?php echo ($jobseeker->disability == $row_disability->id) ? 'selected' : ''; ?>>
                <?php e($row_disability->name); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('disability_type'); ?>
        </div>

        <div class="input-group <?php echo (form_error('domiciled'))?'has-error':'';?>">
          <label class="input-group-addon">Domiciliado <span>*</span></label>
          <select class="form-control" name="domiciled" id="domiciled">
            <?php $domiciled = @$form_rtps->domiciled; ?>
            <option value="">Seleccione</option>
            <option value="1" <?php echo $domiciled == '1' ? 'selected="selected"' : ''; ?>>Si</option>
            <option value="0" <?php echo $domiciled == '0' ? 'selected="selected"' : ''; ?>>No</option>
          </select>
          <?php echo form_error('domiciled'); ?>
        </div>

        <div class="input-group <?php echo (form_error('mobile'))?'has-error':'';?>">
          <label class="input-group-addon">Teléfono móvil <span>*</span></label>
          <?php 
            $mobile_parts = explode(' ', $jobseeker->mobile);
            $count_mobile_parts = count($mobile_parts);

            $mobile_phone_code = '';
            $mobile = '';

            if ($count_mobile_parts == 3) {
              $mobile_phone_code = $mobile_parts[0] . ' ' . $mobile_parts[1];
              $mobile = $mobile_parts[2];
            } elseif ($count_mobile_parts == 2)  {
              $mobile_phone_code = $mobile_parts[0];
              $mobile = $mobile_parts[1];
            } else {
              $mobile = $jobseeker->mobile; 
            }
          ?>
          <table width="100%">
            <tr>
              <td width="150">
                <select name="mobile_code" class="form-control" style="width:150px;">
                  <option value="">Seleccione</option>
                  <?php 
                    foreach ($result_countries as $row_country):
                      if (empty($row_country->phone_code)) {
                        continue;
                      }
                    $country_phone_code = '+' . $row_country->phone_code;
                    $selected = ($mobile_phone_code == $country_phone_code) ? 'selected="selected"' : '';
                  ?>
                  <option value="<?php echo $country_phone_code; ?>" <?php echo $selected;?>><?php echo $row_country->country_name . " (" . $country_phone_code . ")"; ?></option>
                  <?php endforeach ?>
                </select>     
              </td>
              <td>
                <input name="mobile" type="text" class="form-control" value="<?php echo $mobile; ?>" maxlength="15" />
              </td>
            </tr>
          </table>
          <?php echo form_error('mobile'); ?>
        </div>

        <div class="input-group <?php echo (form_error('nationality')) ? 'has-error' : '';?>">
          <label class="input-group-addon">País nacionalidad <span>*</span></label>
          <select class="form-control" name="nationality" style="width:100%;">
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
        </div>

        <h3 class="sub-title-h3" style="margin-top: 25px;">Redes (Opcionales)</h3>

        <div class="input-group">
          <label class="input-group-addon">Linkedin</label>
          <input name="linkedin" type="text" class="form-control" value="<?php echo $jobseeker->linkedin; ?>" maxlength="70" placeholder="https://www.linkedin.com/in/alguien">
        </div>

        <div class="input-group">
          <label class="input-group-addon">Facebook</label>
          <input name="facebook" type="text" class="form-control" value="<?php echo $jobseeker->facebook; ?>" maxlength="70" placeholder="https://www.facebook.com/alguien">
        </div>

        <h3 class="sub-title-h3" style="margin-top: 25px;">Contacto de emergencia (Opcional)</h3>

        <div class="input-group">
          <label class="input-group-addon">Parentesco <span></span></label>
          <select id="emergency_contact_kinship" name="emergency_contact_kinship" class="form-control" style="width:100%;">
            <option value="">No tengo contacto de emergencia</option>
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

        <div class="input-group <?php echo (form_error('emergency_contact_mobile'))?'has-error':'';?>">
          <label class="input-group-addon">Teléfono <span></span></label>
          <?php 
            $mobile_parts = explode(' ', @$seeker_additional_info->emergency_contact_mobile ? $seeker_additional_info->emergency_contact_mobile : '');
            $count_mobile_parts = count($mobile_parts);

            $mobile_phone_code = '';
            $mobile = '';

            if ($count_mobile_parts == 3) {
              $mobile_phone_code = $mobile_parts[0] . ' ' . $mobile_parts[1];
              $mobile = $mobile_parts[2];
            } elseif ($count_mobile_parts == 2)  {
              $mobile_phone_code = $mobile_parts[0];
              $mobile = $mobile_parts[1];
            } else {
              $mobile = @$seeker_additional_info->emergency_contact_mobile; 
            }
          ?>
          <table width="100%">
            <tr>
              <td width="150">
                <select id="emergency_contact_mobile_code" name="emergency_contact_mobile_code" class="form-control" style="width:150px;">
                  <option value="">Seleccione</option>
                  <?php 
                    foreach ($result_countries as $row_country):
                      if (empty($row_country->phone_code)) {
                        continue;
                      }
                    $country_phone_code = '+' . $row_country->phone_code;
                    $selected = ($mobile_phone_code == $country_phone_code) ? 'selected="selected"' : '';
                  ?>
                  <option value="<?php echo $country_phone_code; ?>" <?php echo $selected;?>><?php echo $row_country->country_name . " (" . $country_phone_code . ")"; ?></option>
                  <?php endforeach ?>
                </select>     
              </td>
              <td>
                <input id="emergency_contact_mobile" name="emergency_contact_mobile" type="text" class="form-control" value="<?php echo $mobile; ?>" maxlength="15" />
              </td>
            </tr>
          </table>
          <?php echo form_error('emergency_contact_mobile'); ?>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Nombre y apellido<span></span></label>
          <input id="emergency_contact_name" 
                 type="text" 
                 class="form-control" 
                 name="emergency_contact_name" 
                 value="<?php echo @$seeker_additional_info->emergency_contact_name; ?>" 
                 maxlength="65"
                 placeholder="Nombre y apellido">
        </div>

        <h3 class="sub-title-h3" style="margin-top: 25px;">Lugar de nacimiento</h3>

        <div class="input-group <?php echo (form_error('born_country')) ? 'has-error' : '';?>">
          <label class="input-group-addon">País <span>*</span></label>
          <select class="form-control" name="born_country" id="born-country" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $born_country = @$form_rtps->born_country; ?>
            <?php foreach ($result_countries as $row_country): ?>
              <?php $selected = $born_country == $row_country->ID ? 'selected' : ''; ?>
              <option value="<?php echo $row_country->ID; ?>" <?php echo $selected; ?>>
                <?php echo $row_country->country_name; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('born_country'); ?>
        </div>

        <div id="content-born-department" class="input-group <?php echo (form_error('born_department')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Departamento <span>*</span></label>
          <select class="form-control" name="born_department" id="born_department" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $born_department = @$form_rtps->born_department; ?>
            <?php foreach ($result_departments as $row_ubigeo): ?>
              <?php $selected = $born_department == $row_ubigeo->department ? 'selected' : ''; ?>       
              <option value="<?php echo $row_ubigeo->department; ?>" <?php echo $selected; ?>>
                <?php echo $row_ubigeo->department; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('born_department'); ?>
        </div>

        <div id="content-born-province" class="input-group <?php echo (form_error('born_province')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Provincia <span>*</span></label>
          <select class="form-control" name="born_province" id="born_province" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $born_province = @$form_rtps->born_province; ?>
            <?php foreach ($result_provinces as $row_ubigeo): ?>
              <?php $selected = $born_province == $row_ubigeo->province ? 'selected' : ''; ?>
              <option value="<?php echo $row_ubigeo->province; ?>" <?php echo $selected; ?>>
                <?php echo $row_ubigeo->province; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('born_province'); ?>
        </div>

        <div id="content-born-district" class="input-group <?php echo (form_error('born_district')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Distrito <span>*</span></label>
          <select class="form-control" name="born_district" id="born_district" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $born_district = @$form_rtps->born_district; ?>
            <?php foreach ($result_districts as $row_ubigeo): ?>
              <?php $selected = $born_district == $row_ubigeo->district ? 'selected' : ''; ?>
              <option value="<?php echo $row_ubigeo->district; ?>" <?php echo $selected; ?>>
                <?php echo $row_ubigeo->district; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('born_district'); ?>
        </div>

        <div class="input-group <?php echo (form_error('place_birth'))?'has-error':'';?>">
          <label class="input-group-addon">Lugar de nacimiento <span>*</span></label>
          <input name="place_birth" type="text" class="form-control" id="place_birth" placeholder="Lugar de nacimiento" value="<?php echo @$form_rtps->place_birth; ?>">
          <?php echo form_error('place_birth'); ?> 
        </div>

        <h3 class="sub-title-h3" style="margin-top: 25px;">Domicilio actual</h3>
        
        <div class="input-group <?php echo (form_error('current_country')) ? 'has-error' : '';?>">
          <label class="input-group-addon">País <span>*</span></label>
          <select id="current_country" class="form-control" name="current_country" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $current_country = $jobseeker->country; ?>
            <?php foreach ($result_countries as $row_country): ?>
              <?php $selected = $current_country == $row_country->ID ? 'selected' : ''; ?>
              <option value="<?php echo $row_country->ID; ?>" <?php echo $selected; ?>>
                <?php echo $row_country->country_name; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('current_country'); ?>
        </div>

        <?php
          $part_ubigeo = explode(',', $jobseeker->city);
          $department = trim(@$part_ubigeo[0]);
          $province = trim(@$part_ubigeo[1]);
          $district = trim(@$part_ubigeo[2]);
        ?>

        <div id="content-current-department" class="input-group <?php echo (form_error('current_department')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Departamento <span>*</span></label>
          <select id="current_department" class="form-control" name="current_department" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $current_department = $department; ?>
            <?php foreach ($result_departments as $row_ubigeo): ?>
              <?php $selected = $current_department == $row_ubigeo->department ? 'selected' : ''; ?>       
              <option value="<?php echo $row_ubigeo->department; ?>" <?php echo $selected; ?>>
                <?php echo $row_ubigeo->department; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('current_department'); ?>
        </div>

        <div id="content-current-province" class="input-group <?php echo (form_error('current_province')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Provincia <span>*</span></label>
          <select id="current_province" class="form-control" name="current_province" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $current_province = $province; ?>
            <?php foreach ($provinces as $row_ubigeo): ?>
              <?php $selected = $current_province == $row_ubigeo->province ? 'selected' : ''; ?>
              <option value="<?php echo $row_ubigeo->province; ?>" <?php echo $selected; ?>>
                <?php echo $row_ubigeo->province; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('current_province'); ?>
        </div>

        <div id="content-current-district" class="input-group <?php echo (form_error('born_district')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Distrito <span>*</span></label>
          <select id="current_district" class="form-control" name="current_district" style="width:100%;">
            <option value="">Seleccione</option>
            <?php $current_district = $district; ?>
            <?php foreach ($districts as $row_ubigeo): ?>
              <?php $selected = $current_district == $row_ubigeo->district ? 'selected' : ''; ?>
              <option value="<?php echo $row_ubigeo->district; ?>" <?php echo $selected; ?>>
                <?php echo $row_ubigeo->district; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('current_district'); ?>
        </div>
              
        <div id="content-current-city" class="input-group">
          <label class="input-group-addon">Lugar <span></span></label>
          <input id="city" name="city" type="text" class="form-control" value="<?php echo $jobseeker->country != '56' ? $jobseeker->city : ''; ?>">
        </div>

        <div class="input-group">
          <label class="input-group-addon">Vía <span>*</span></label>
          <select class="form-control" name="way_id" style="width:100%;">
            <option value="">Seleccione</option>
            
            <?php foreach ($result_ways as $row): ?>
              <?php $selected = $jobseeker->way_id == $row->id ? 'selected' : ''; ?>
              <option value="<?php echo $row->id; ?>" <?php echo $selected; ?>>
                <?php echo $row->name; ?>    
              </option>
            <?php endforeach; ?>
          </select>
          <?php echo form_error('way_id'); ?>
        </div>

        <div class="input-group <?php echo (form_error('present_address')) ? 'has-error' : '';?>">
          <label class="input-group-addon">Dirección <span>*</span></label>
          <textarea id="present-address" rows="3" class="form-control" name="present_address"><?php echo set_value('present_address') ? set_value('present_address') : $jobseeker->present_address; ?></textarea>
          <span style="font-size:12px;color:red;display:none;" class="present-address-alert-length"></span>
        </div>

        <div class="input-group">
          <label class="input-group-addon">Número <span>*</span></label>
          <input type="number" class="form-control" name="address_number" value="<?php echo $jobseeker->address_number; ?>">
        </div>

        <div class="input-group">
          <label class="input-group-addon">Interior <span></span></label>
          <input type="text" class="form-control" name="domicile_interior" value="<?php echo $jobseeker->domicile_interior; ?>" maxlength="20">
        </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Educación</h3>

          <div class="input-group <?php echo (form_error('level_education')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Nivel educativo <span>*</span></label>
            <select class="form-control" name="level_education" id="level_education">
              <option value="">Seleccione</option>
              <!--
              <option value="higher">Superior</option>
              <option value="technical">Técnico</option>
              <option value="others">Otros</option>
              -->
              <?php $level_education = @$form_rtps->level_education; ?>
              <option value="Superior" <?php echo $level_education == 'Superior' ? 'selected' : ''; ?>>Superior</option>
              <option value="Ténico" <?php echo $level_education == 'Ténico' ? 'selected' : ''; ?>>Técnico</option>
              <option value="Otros" <?php echo $level_education == 'Otros' ? 'selected' : ''; ?>>Otros</option>
            </select>
            <?php echo form_error('level_education'); ?>
          </div>
          <div class="input-group <?php echo (form_error('degree_obtained'))?'has-error':'';?>">
            <label class="input-group-addon">Nivel académico <span>*</span></label>            
            <button id="btn-modal-education-edit" 
                    type="button" 
                    class="btn btn-xs btn-default pull-right" 
                    style="margin-left: 5px;"
                    title="Editar educación">
              <i class="glyphicon glyphicon-pencil"></i>
            </button>
            
            <button id="btn-modal-education-add" 
                    type="button" 
                    class="btn btn-xs btn-default pull-right"
                    title="Agregar educación">
              <i class="glyphicon glyphicon-plus"></i>
            </button>

            <select id="degree_obtained" name="degree_obtained" class="form-control" style="width:100%;margin-top:5px;">
              <option value="">Seleccione</option>
              <?php $degree_obtained = @$form_rtps->degree_obtained; ?>
                
                <?php foreach ($result_studies as $study): ?>
                  <?php $selected = $degree_obtained == $study->degree_title ? 'selected' : ''; ?>
                  <?php $degree_obtained_year = $study->end_date ? date('Y', strtotime($study->end_date)) : ''; ?>
                  <option value="<?php echo $study->degree_title; ?>" <?php echo $selected; ?>
                          data-id="<?php echo $study->ID; ?>"
                          data-year="<?php e($degree_obtained_year); ?>"
                          data-speciality="<?php e($study->major); ?>"
                          data-institution="<?php e($study->institution_name ? $study->institution_name : $study->institude); ?>">
                    <?php echo $study->degree_title . ' - ' . $study->major; ?>    
                  </option>
                <?php endforeach; ?>
            </select>
            <?php echo form_error('degree_obtained'); ?> 
          </div>

          <div class="input-group <?php echo (form_error('degree_obtained_institution'))?'has-error':'';?>">
            <label class="input-group-addon">Institución <span>*</span></label>
            <input name="degree_obtained_institution" 
                   type="text" 
                   class="form-control" 
                   id="degree_obtained_institution" 
                   placeholder="Institución" 
                   value="<?php echo @$form_rtps->degree_obtained_institution; ?>"
                   readonly>
            <?php echo form_error('degree_obtained_institution'); ?> 
          </div>

          <div class="input-group <?php echo (form_error('specialty'))?'has-error':'';?>">
            <label class="input-group-addon">Título <span>*</span></label>
            <input name="specialty" type="text" class="form-control" id="specialty" placeholder="Especialidad" value="<?php echo @$form_rtps->specialty; ?>" readonly>
            <?php echo form_error('specialty'); ?> 
          </div>

          <div class="input-group <?php echo (form_error('degree_obtained_year'))?'has-error':'';?>">
            <label class="input-group-addon">Año de egreso <span></span></label>        
            <?php $degree_obtained_year = @$form_rtps->degree_obtained_year; ?>
            <input type="text" id="degree_obtained_year" name="degree_obtained_year" class="form-control" value="<?php echo $degree_obtained_year; ?>" placeholder="Año de egreso" readonly>
            <?php echo form_error('degree_obtained_year'); ?> 
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Licencia de conducir</h3>
          
          <div class="input-group <?php echo (form_error('driver_license')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Posee licencia de conducir <span>*</span></p></label>
            <select class="form-control" name="driver_license" id="driver-license">
              <option value="">Seleccione</option>
              <?php $driver_license = @$form_rtps->driver_license; ?>
              <option value="1" <?php echo $driver_license == '1' ? 'selected' : ''; ?>>SI</option>
              <option value="0" <?php echo $driver_license == '0' ? 'selected' : ''; ?>>NO</option>
            </select>
            <?php echo form_error('driver_license'); ?>
          </div>

          <div id="driver-license-type-content" class="input-group <?php echo (form_error('driver_license_type')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Ingrese tipo de licencia<span>*</span></p></label>
            <input type="text" name="driver_license_type" class="form-control" value="<?php echo @$form_rtps->driver_license_type; ?>">
            <?php echo form_error('driver_license_type'); ?>
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Sindicalizado</h3>
          
          <div class="input-group <?php echo (form_error('unionized')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Sindicalizado <span>*</span></p></label>
            <select class="form-control" name="unionized" id="unionized">
              <option value="">Seleccione</option>
              <?php $unionized = @$form_rtps->unionized; ?>
              <option value="1" <?php echo $unionized == '1' ? 'selected' : ''; ?>>SI</option>
              <option value="0" <?php echo $unionized == '0' ? 'selected' : ''; ?>>NO</option>
            </select>
            <?php echo form_error('unionized'); ?>
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Ingresos de 5ta Categoría</h3>
          
          <div class="input-group <?php echo (form_error('fifth_category_income')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Ha recibido ingresos de <p style="font-size: 14px;">5ta categoría en el año actual <span>*</span></p></label>
            <select class="form-control" name="fifth_category_income" id="fifth-category-income">
              <option value="">Seleccione</option>
              <?php $fifth_category_income = @$form_rtps->fifth_category_income; ?>
              <option value="1" <?php echo $fifth_category_income == '1' ? 'selected' : ''; ?>>SI</option>
              <option value="0" <?php echo $fifth_category_income == '0' ? 'selected' : ''; ?>>NO</option>
            </select>
            <?php echo form_error('fifth_category_income'); ?>
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Sistema de pensiones</h3>
          
          <div id="content-pension-affiliation" class="input-group <?php echo (form_error('pension_affiliation')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Afiliado a <span></span></label>
            <select class="form-control" name="pension_affiliation" id="pension-affiliation">
              <option value="">Seleccione</option>
              <?php $pension_affiliation = @$form_rtps->pension_affiliation; ?>
              <option value="SNP" <?php echo $pension_affiliation == 'SNP' ? 'selected' : ''; ?>>SNP</option>
              <option value="AFP" <?php echo $pension_affiliation == 'AFP' ? 'selected' : ''; ?>>AFP</option>
            </select>
            <?php echo form_error('pension_affiliation'); ?>
          </div>
        
          <div id="content-pension-retired" class="input-group <?php echo (form_error('pension_retired')) ? 'has-error' : '';?>">
            <label class="input-group-addon">Jubilado <span>*</span></label>
            <select class="form-control" name="pension_retired" id="pension-retired">
              <option value="">Seleccione</option>
              <?php $pension_retired = @$form_rtps->pension_retired; ?>
              <option value="0" <?php echo $pension_retired == '0' ? 'selected' : ''; ?>>No</option>
              <option value="1" <?php echo $pension_retired == '1' ? 'selected' : ''; ?>>Si</option>
            </select>
            <?php echo form_error('pension_retired'); ?>
          </div>

          <div id="content-pension-affiliation-date" class="input-group <?php echo (form_error('pension_affiliation_date'))?'has-error':'';?>">
            <label class="input-group-addon">Fecha de afiliación <span></span></label>
            <input name="pension_affiliation_date" type="date" class="form-control" id="pension_affiliation_date" placeholder="Fecha de afiliación" value="<?php echo @$form_rtps->pension_affiliation_date ? $form_rtps->pension_affiliation_date : ''; ?>">
            <?php echo form_error('pension_affiliation_date'); ?> 
          </div>

          <div id="content-pension-name-afp" class="input-group <?php echo (form_error('pension_name_afp'))?'has-error':'';?>">
            <label class="input-group-addon">Nombre AFP <span>*</span></label>
            <select name="pension_name_afp" id="pension_name_afp" class="form-control">
              <option value="">Seleccione</option>
              <?php $pension_name_afp = @$form_rtps->pension_name_afp; ?>
              <option value="HABITAT" <?php echo $pension_name_afp == 'HABITAT' ? 'selected' : ''; ?>>HABITAT</option>
              <option value="INTEGRA" <?php echo $pension_name_afp == 'INTEGRA' ? 'selected' : ''; ?>>INTEGRA</option>
              <option value="PRIMA" <?php echo $pension_name_afp == 'PRIMA' ? 'selected' : ''; ?>>PRIMA</option>
              <option value="PROFUTURO" <?php echo $pension_name_afp == 'PROFUTURO' ? 'selected' : ''; ?>>PROFUTURO</option>
            </select>
            <?php echo form_error('pension_name_afp'); ?> 
          </div>

          <div id="content-pension-cuspp" class="input-group <?php echo (form_error('pension_cuspp'))?'has-error':'';?>">
            <label class="input-group-addon">CUSPP <span></span></label>
            <input name="pension_cuspp" type="text" class="form-control" id="pension_cuspp" placeholder="CUSPP" value="<?php echo @$form_rtps->pension_cuspp; ?>">
            <?php echo form_error('pension_cuspp'); ?> 
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Pago de Haberes</h3>

          <div class="input-group <?php echo (form_error('bank_name'))?'has-error':'';?>">
            <label class="input-group-addon">Entidad Bancaria (depósito) <span>*</span></label>
            <select name="bank_name" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>
              <?php $bank_name = @$form_rtps->bank_name; ?>
              <?php foreach ($result_banks as $row_bank): ?>
                <option value="<?php echo $row_bank->bank_name; ?>" <?php echo $bank_name == $row_bank->bank_name ? 'selected' : ''; ?>>
                  <?php echo $row_bank->bank_name; ?>    
                </option>
              <?php endforeach ?>
            </select>
            <?php echo form_error('bank_name'); ?> 
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">Pago de CTS</h3>

          <div class="input-group <?php echo (form_error('payment_cts_bank_name'))?'has-error':'';?>">
            <label class="input-group-addon">Entidad Bancaria (depósito) <span>*</span></label>
            <select name="payment_cts_bank_name" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>
              <?php $bank_name = @$form_rtps->payment_cts_bank_name; ?>
              <?php foreach ($result_banks as $row_bank): ?>
                <option value="<?php echo $row_bank->bank_name; ?>" <?php echo $bank_name == $row_bank->bank_name ? 'selected' : ''; ?>>
                  <?php echo $row_bank->bank_name; ?>    
                </option>
              <?php endforeach ?>
            </select>
            <?php echo form_error('payment_cts_bank_name'); ?> 
          </div>

          <div class="input-group <?php echo (form_error('payment_cts_currency'))?'has-error':'';?>">
            <label class="input-group-addon">Moneda <span>*</span></label>
            <select name="payment_cts_currency" class="form-control" style="width: 100%;">
              <option value="">Seleccione</option>
              <?php $payment_cts_currency = @$form_rtps->payment_cts_currency; ?>
              <option value="PEN" <?php echo $payment_cts_currency == 'PEN' ? 'selected' : ''; ?>>
                Soles    
              </option>
              <option value="USD" <?php echo $payment_cts_currency == 'USD' ? 'selected' : ''; ?>>
                Dólares    
              </option>
            </select>
            <?php echo form_error('payment_cts_currency'); ?> 
          </div>

          <h3 class="sub-title-h3" style="margin-top: 25px;">
            Datos de los Derechohabientes
          </h3>
          <div class="info-rightful-claimants">
            <table>
              <tr>
                <td>
                  <i class="glyphicon glyphicon-info-sign" style="font-size: 1.5em;"></i>
                </td>
                <td>
                  <ul>
                    <li>Un derechohabiente es todo aquella persona que depende económicamente de ti, Ej: Tu cónyuge, hijos menores de edad o hijos con alguna discapacidad.</li>
                    <li>Si no tienes derechohabientes ignora esta sección.</li>
                  </ul>
                </td>
              </tr>
            </table>  
          </div>
          <div class="content-add-rightful-claimants">
            <button id="add-rightful-claimant" type="button" class="btn btn-xs btn-primary">Agregar</button>
          </div>
          <div class="content-rightful-claimants">
            <div class="table-responsive">
              <table width="100%" class="table table-striped tbl-rightful-claimants">
                <tr>
                  <th>Doc. Identidad</th>
                  <th>Nombres y apellidos</th>
                  <th>Fecha de nacimiento</th>
                  <th>Parentesco</th>
                  <th></th>
                </tr>
                <?php foreach ($rightful_claimants as $key => $person): ?>
                  <tr data-rc-id="<?php echo $person->ID; ?>" >
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
                    <td>
                      <button type="button" class="btn btn-xs btn-rc-action edit-rightful-claimant">
                        <i class="glyphicon glyphicon-pencil"></i>
                      </button>
                      <button type="button" class="btn btn-xs btn-rc-action remove-rightful-claimants">
                        <i class="glyphicon glyphicon-remove"></i>
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
                    
                        <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][attached_cert_cohabitation]" value="<?php echo $person->kinship_cert_attached; ?>"  data-file-url="<?php echo file_url($person->kinship_cert_attached, 'public/uploads/candidate/certificates_cohabitation'); ?>">
                        
                        <?php if ($person->live_same_domicile == '0'): ?>
                          <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][domicile]" value="<?php echo $person->domicile; ?>">
                          <input type="hidden" name="rightful_claimants[<?php echo $person->ID; ?>][ubigeo]" value="<?php echo $person->ubigeo; ?>">
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach ?>
              </table>
            </div>
          </div>
          <div class="content-conditions-policy">
            * Al hacer clic en "Guardar", aceptas nuestras <a href="#" data-toggle="modal" data-target="#modal-conditions-policy">Condiciones y política de ingreso</a>. 
            <div align="center" style="margin-top: 20px;">
              <input type="submit" name="submit_button" id="submit_button" value="Guardar ficha" class="btn btn-primary" />
            </div>
          </div>
        </div>
        <?php endif; ?>


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
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Guardar derechohabientes</h4>
      </div>
      <div class="modal-body">
        <div class="section-rightful-claimant">
          <div class="formwraper">
            <input id="rc-id" type="hidden" value="2">
            <div class="input-group">
              <label class="input-group-addon">Nombres <span>*</span></label>
              <input id="rc-name" type="text" name="first_name" class="form-control" value="" placeholder="Nombres">
            </div>
            <div class="input-group">
              <label class="input-group-addon">Apellido Paterno <span>*</span></label>
              <input id="rc-paternal-name" type="text" name="paternal_last_name" class="form-control" value="" placeholder="Apellido paterno">
            </div>
            <div class="input-group">
              <label class="input-group-addon">Apellido Materno <span>*</span></label>
              <input id="rc-maternal-name" type="text" name="maternal_last_name" class="form-control" value="" placeholder="Apellido materno">
            </div>
            <div class="input-group">
              <label class="input-group-addon">Documento de identidad <span>*</span></label>
              <table width="100%">
                <tr>
                  <td width="35%">
                    <select id="rc-document-type" name="document_type" class="form-control" style="width:100%;">
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
            <div class="input-group">
              <label class="input-group-addon">Fecha de nacimiento <span>*</span></label>
              <input id="rc-birthdate" type="date" class="form-control" name="birthdate" value="" placeholder="Fecha de nacimiento"> 
            </div>
             <div class="input-group">
              <label class="input-group-addon">Sexo <span>*</span></label>
              <select id="rc-gender" class="form-control" name="gender" style="width:100%;">
                <option value="">Seleccione</option>
                <option value="1">Hombre</option>
                <option value="2">Mujer</option>
              </select>
            </div>
          
            <div class="input-group">
              <label class="input-group-addon">País nacionalidad <span>*</span></label>
              <select id="rc-nationality" class="form-control" name="nationality" style="width: 100%;">
                <option value="">Seleccione</option>
                <?php foreach ($result_countries as $row_country): ?>
                  <option value="<?php echo $row_country->ID; ?>" <?php echo $selected; ?>>
                    <?php echo $row_country->country_name; ?>    
                  </option>
                <?php endforeach; ?>   
              </select>
            </div>
          
            <div class="input-group">
              <label class="input-group-addon">Vinculo familiar <span>*</span></label>
              <select id="rc-kinship" name="kinship" class="form-control select-kinship" style="width:100%;">
                <option value="">Seleccione</option>
                <?php foreach ($kinship_types as $row): ?>
                  <option value="<?php echo $row->id; ?>"><?php e($row->name); ?></option>
                <?php endforeach; ?>
              </select> 
            </div>
            <div class="input-group">
              <label class="input-group-addon">Tipo Certificado: <span>*</span></label>
              <select id="rc-family-bond-certs" name="family_bond_cert_type" class="form-control" style="width:100%;">
                <option value="">Seleccione</option>
              </select>     
            </div>
            <div class="input-group">
              <label class="input-group-addon">Código Certificado: <span>*</span></label>
              <input id="rc-family-bond-certs-code" name="family_bond_cert_code" type="text" class="form-control" placeholder="Código certificado" value="">   
            </div>
            <div class="input-group">
              <label class="input-group-addon">
                Adjuntar certificado: <span>*</span></label>
                <div class="wrapper-document-load wrapper-cert-cohabitation">
                  <input id="rc-attached-cert-cohabitation" class="document-input-file" type="hidden" name="attached_cert_cohabitation" value="" data-url-upload="<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/upload_cert_cohabitation/' . $jobseeker->ID); ?>">
                  <div class="document-load">
                    <button type="button" class="btn btn-sm btn-default load-file">
                      Cargar
                    </button>
                  </div>
                  
                   <div class="document-view" style="text-align: center; border: 1px solid #ccc;background: #fff;padding: 8px; ?>">
                    <a class="document-link" href="" target="_blank">
                      <i class="glyphicon glyphicon-file"></i>
                      Ver documento
                    </a>
                    <button type="button" class="btn btn-primary btn-xs pull-right load-file">Cambiar</button>
                  </div>
                </div>
            </div>
            <div class="input-group">
              <label class="input-group-addon">Vive en el mismo domicilio: <span>*</span></label>
              <select id="rc-live-same-domicile" name="live_same_domicile" class="form-control live_same_domicile i3">
                <option value="">Seleccione</option>
                <option value="1">Si</option>
                <option value="0">No</option>
              </select>     
            </div>
            <div class="input-group content-domicile">
              <label class="input-group-addon">Ubicación / Ciudad <span> *</span></label>
            
              <select id="rc-ubigeo" name="ubigeo" style="width: 100%;">
                <option value="">Seleccione</option>
                <?php foreach ($result_ubigeos as $row_ubigeo): ?>
                  <?php 
                    $city_value = $row_ubigeo->order_administrative1 . ', ' . $row_ubigeo->order_administrative2 . ', ' . $row_ubigeo->order_administrative3; 
                    $city_selected = '';
                  ?>
                  <option value="<?php echo $city_value; ?>" <?php echo $city_selected; ?>> <?php echo $city_value; ?></option>
                <?php endforeach; ?>  
              </select>
            </div>

            <div class="input-group content-domicile">
              <label class="input-group-addon">Vía <span>*</span></label>
              <select id="rc-domicile-way" class="form-control" name="domicile_way_id" style="width:100%;">
                <option value="">Seleccione</option>
                <?php foreach ($result_ways as $row): ?>
                  <option value="<?php echo $row->id; ?>">
                    <?php echo $row->name; ?>    
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="input-group content-domicile">
              <label class="input-group-addon">Dirección <span>*</span></label>
              <input id="rc-domicile" name="domicile" type="text" class="form-control" placeholder="Domicilio" value=""> 
            </div>
            <div class="input-group content-domicile">
              <label class="input-group-addon">Número <span>*</span></label>
              <input id="rc-domicile-number" name="domicile_number" type="number" class="form-control" placeholder="Número" value=""> 
            </div>

            <div class="input-group content-domicile">
              <label class="input-group-addon">Interior <span></span></label>
              <input id="rc-domicile-interior" name="domicile_interior" type="text" class="form-control" placeholder="Interior" value="" maxlength="20"> 
            </div>
            <div class="content-section-child" style="display: none;">
              <div class="input-group">
                <label class="input-group-addon">Lugar de nacimiento <span>*</span></label>
                <input id="rc-place-birth" name="place_birth" type="text" class="form-control i1" placeholder="Lugar de nacimiento" value=""> 
              </div>
              <div class="input-group">
                <label class="input-group-addon">Emisión la partida de <br/> nacimiento: <span>*</span></label>
                <input id="rc-place-birth-certificate" name="place_birth_certificate" type="text" class="form-control i2" placeholder="Lugar de la emisión de la partida de nacimiento" value=""> 
              </div>
            </div>
            <div id="content-document" class="input-group">
              <label class="input-group-addon">
                Copia del Doc. de identidad: <span>*</span></label>
                <div class="wrapper-document-load wrapper-doc-number">
                  <input id="rc-attached-document" class="document-input-file"  type="hidden" name="attached_document" value="" data-url-upload="<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/upload_document/' . $jobseeker->ID); ?>"> 
                  <div class="document-load">
                    <button type="button" class="btn btn-sm btn-default load-file">
                      Cargar
                    </button>
                  </div>
                  
                   <div class="document-view" style="text-align: center; border: 1px solid #ccc;background: #fff;padding: 8px; ?>">
                    <a class="document-link" target="_blank">
                      <i class="glyphicon glyphicon-file"></i>
                      Ver documento
                    </a>
                    <button type="button" class="btn btn-primary btn-xs pull-right load-file">Cambiar</button>
                  </div>
                </div>
            </div>
            <div style="text-align: center;padding-top: 10px;" class="input-group">
              <button id="save-rightful-claimant" type="button" class="btn btn-primary">Guardar</button>
            </div>
          </div>
        </div>
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
      <button type="button" class="btn btn-primary btn-xs pull-right load-file">Cambiar</button>
    </div>
  </script>

  <script id="tpl-add-rightful-claimant" type="text/template">
    <tr data-rc-id="{{rcId}}">
      <td>{{documentNumber}}</td>
      <td>{{name}} {{lastName}}</td>
      <td>{{birthdate}}</td>
      <td>{{kinship}}</td>
      <td>
        <button type="button" class="btn btn-xs btn-rc-action edit-rightful-claimant">
          <i class="glyphicon glyphicon-pencil"></i>
        </button>
        <button type="button" class="btn btn-xs btn-rc-action remove-rightful-claimants">
          <i class="glyphicon glyphicon-remove"></i>
        </button>
      </td>
    </tr>
  </script>
</div>
<div id="modal-rs-doc-comments" class="modal fade" role="dialog"></div>
<?php $this->load->view('common/before_body_close'); ?>
<script src="<?php echo base_url('public/js/jquery-ui.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/mustache.2.3.0.min.js');?>"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/vendor/jquery.ui.widget.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.iframe-transport.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/js/jquery-upload/js/jquery.fileupload.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
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
    $( ".section-rightful-claimant" ).find('input, select').val("");
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
        gender = $( "input[name='rightful_claimants[" + id + "][gender]']").val(),
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
 
    $( "#rc-kinship" ).val(kinship);
    $( "#rc-id" ).val(id);
    $( "#rc-name" ).val(name);
    $( "#rc-paternal-name" ).val(paternalLastName);
    $( "#rc-maternal-name" ).val(maternalLastName);
    $( "#rc-document-type" ).val(documentType);
    $( "#rc-document-number" ).val(documentNumber);
    $( "#rc-gender" ).val(gender);
    $( "#rc-birthdate" ).val(birthdate);
    $( "#rc-family-bond-certs-code ") .val(familyBondCertCode);
    $( "#rc-place-birth" ).val(placeBirth);
    $( "#rc-place-birth-certificate" ).val(placeBirthCertificate);
    $( "#rc-live-same-domicile" ).val(liveSameDomicile);
    $( "#rc-attached-document" ).val(attachedDocument);
    $( "#rc-attached-cert-cohabitation" ).val(attachedCertCohabitation);
    $( "#rc-domicile" ).val(domicile);
    $( "#rc-nationality" ).val(nationality);
    $( "#rc-domicile-way" ).val(domicileWayId);
    $( "#rc-domicile-number" ).val(domicileNumber);
    $( "#rc-domicile-interior" ).val(domicileInterior);
    $( "#rc-ubigeo" ).val(ubigeo).trigger('change');
    $( ".document-link", ".wrapper-doc-number" ).prop("href", urlFileDocument);
    $( ".document-link", ".wrapper-cert-cohabitation" ).prop("href", urlFileCertCohabitation);
  
    if (kinship != '1' && kinship != '2' && jobseekerHaveSpouse()) {
      $( "#rc-kinship option[value='1']").prop('disabled', true);
      $( "#rc-kinship option[value='2']").prop('disabled', true);
    } else {
      $( "#rc-kinship option[value='1']").prop('disabled', false);
      $( "#rc-kinship option[value='2']").prop('disabled', false);
    }

    $( "#rc-kinship" ).change();

    $( "#rc-family-bond-certs") .val(familyBondCertType);

    $( "#rc-live-same-domicile" ).change();

    $( "#modal-add-rightful-claimant" ).data('row-edit', tr);
    $( "#modal-add-rightful-claimant" ).modal('show');
  }

  function saveRightfulClaimants() {
    var data = validDataRightfulClaimants();

    if (data.errors > 0) {
      toastr["error"]("¡Existen datos sin completar!");
      return;
    }

    if ($( '#rc-kinship' ).val() == '4' && dateDiffYears($( '#rc-birthdate' ).val()) >= 18) {
      toastr["error"](
        "¡La edad del hijo es mayor o igual a 18 años, no puede seleccionar Hijo menor de edad!"
      );
      return;
    }

    if ($( '#rc-kinship' ).val() == '5' && dateDiffYears($( '#rc-birthdate' ).val()) < 18) {
      toastr["error"](
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

      if ($(element).val() != '' && elementName && elementName != 'file') {
  
        var inputHidden = $("<input/>").attr({
          'type': 'hidden',
          'name': 'rightful_claimants[' + id + '][' + element.name + ']', 
          'value': element.value,
          'class': (element.name == 'kinship' ? 'input-kinship' : ''),
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

    if ($(this).val() == '4' || $(this).val() == '5' || $(this).val() == '6') {
      contentSectionChild.show();
      $( "#content-cert-cohabitation" ).hide();
    } else {
      contentSectionChild.hide();
      $( "#content-cert-cohabitation" ).show();
    }
    createOptionsFamilyBondCerts();
  });

  function createOptionsFamilyBondCerts() {

    var familyBondId = $( '#rc-kinship' ).val();

    var url = "<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/get_family_bond_certs'); ?>";
    
    $.ajax({
      url: url,
      data: {
        'family_bond_id': familyBondId
      },
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

  $( "#pension-affiliation" ).change(function(){

      $( "#content-pension-retired" ).hide();
      $( "#content-pension-affiliation-date" ).hide();
      $( "#content-pension-name-afp" ).hide();
      $( "#content-pension-cuspp" ).hide();

    if ($(this).val() == 'AFP') {
      $( "#content-pension-retired" ).show();
      $( "#content-pension-affiliation-date" ).show();
      $( "#content-pension-name-afp" ).show();
      $( "#content-pension-cuspp" ).show();
    }
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

  function validRightfulClaimants(form) {

    // $( ".input-group" ).removeClass('has-error');

    // var inputError = $(form).find(".content-rightful-claimants").filter(function(){

    // var elementError = $(this).find('input,select').filter(function(){

    //     element = $(this);
    //     elementName = $.trim(element.prop('name'));

    //     var selectKinship = $( "#rc-kinship" );

    //     //Si vinculo es matrimonial
    //     if (selectKinship.val() == '1' || selectKinship.val() == '2') {
    //       if (elementName == 'place_birth' || 
    //         elementName == 'place_birth_certificate') {

    //         return false;
    //       }
    //     }

    //     var liveSameDomicile = parseBool($( "#rc-live-same-domicile" ).val());

    //     if (liveSameDomicile) {
    //       if (elementName == 'domicile' || 
    //           elementName == 'ubigeo' ||
    //           elementName == 'domicile_way_id' ||
    //           elementName == 'domicile_number' ||
    //           elementName == 'domicile_interior') {
    //         return false;
    //       }
    //     }

    //     if ($.trim(element.val()) == '') {
    //       return true;
    //     }

    //     return false;
    //   });

    //   return elementError.length;
    // });

    // return inputError.length == 0;
  }

  function validForm(form) {

    $( ".input-group" ).removeClass('has-error');
    var elementError = $(form).find("input,select,textarea").filter(function() {
    
    var element = $(this),
        elementName = $.trim(element.prop('name'));

    if (elementName.substring(0, 9) == 'education') {
      return false;
    }

    if (elementName.substring(0, 18) == 'rightful_claimants') {
      return false;
    }

    if ($.trim(element.val()) != '' || 
        elementName == 'file' || 
        elementName == 'pension_affiliation') {
      return false;
    }

    if ($( '#driver-license' ).val() == 0 && elementName == 'driver_license_type') {
      return false;
    }

    if ((elementName == 'born_department' ||
        elementName == 'born_province' ||
        elementName == 'born_district') && $( "#born-country").val() != '56' ) {
        return false;
    }

    if (elementName == 'pension_affiliation_date' || 
        elementName == 'pension_cuspp') {
      return false;
    }
      
    if ((elementName == 'pension_retired' ||
        elementName == 'pension_name_afp') && $( "#pension-affiliation").val() != 'AFP' ) {
        return false;
    }

    if ((elementName == 'current_department' ||
        elementName == 'current_province' ||
        elementName == 'current_district') && $( "#current_country").val() != '56' ) {
        return false;
    }

    if (elementName == 'city' && $( "#current_country").val() == '56' ) {
      return false;
    }

    if (elementName == 'linkedin' || 
        elementName == 'facebook') {
      return false;
    }

    if (elementName == 'domicile_interior') {
      return false;
    }

    if (elementName == 'degree_obtained_year') {
      return false;
    }

    if ($( '#emergency_contact_kinship' ).val() == '') {
      //Input a ignorar si el parentesco esta seleccionado como vacio
      const elementIgnore = [
        'emergency_contact_kinship', 
        'emergency_contact_name', 
        'emergency_contact_mobile_code', 
        'emergency_contact_mobile'
      ];

      if (elementIgnore.includes(elementName)) {
        return false;
      }
    }

    element.closest('.input-group').addClass('has-error');
    return true;
  });

  return {
    'errors' : elementError.length,
    'elementError' : elementError
  }
}

  function validDataRightfulClaimants()
  {   
      $(".input-group", ".section-rightful-claimant").removeClass('has-error');

      var elementError = $( ".section-rightful-claimant" ).find("input,select").filter(function() {
      
      var element = $(this),
          elementName = $.trim(element.prop('name'));

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

  $( "#form_rtps" ).submit(function() {
    var isValidRightfulClaimants = validRightfulClaimants(this);

    if (isValidRightfulClaimants === false) {
      toastr["error"]("¡Hay datos sin completar en derechohabientes!");
      return false;
    }

    var checkForm = validForm(this);

    if (checkForm.errors > 0) {
      toastr["error"]("¡Existen datos requeridos sin completar!");
      return false;
    }
    return true;
  });

  $(document).on('focus', '.datepicker', function() {
    
    if ($(this).hasClass('hasDatepicker') === true) {
      return;
    }
    
    $(this).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd",
      dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
      dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
      monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
      monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec" ],
      maxDate: 'now',
       beforeShow: function() {
        setTimeout(function(){
            $('.ui-datepicker').css('z-index', 99999999999);
        }, 0);
      }
    });
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

    //if ($( '#specialty' ).val() == '' && speciality != '') {
      $( '#specialty' ).val(speciality); 
    //}

    //if ($( '#degree_obtained_year').val() == '' && year != '') {
      $( '#degree_obtained_year' ).val(year); 
    //}

    //if ($( '#degree_obtained_institution').val() == '' && institution != '') {
      $( '#degree_obtained_institution' ).val(institution); 
    //}
  });

  $( '#btn-modal-education-add' ).click(function(){
    $( '#modal-education-add' ).remove();
    $(`<div class="modal fade" id="modal-education-add"></div>`).appendTo('body');
    
    const url = "<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/form_education'); ?>";

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
    
    const url = "<?php echo site_url_embed('embed/jobseeker/requested_documents/form_rtps/form_education'); ?>";

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
  $( "#rc-ubigeo" ).select2();
  $( "#current_country" ).change();
  $( '#degree_obtained' ).change();
  $( '#driver-license' ).change();
  $( '#emergency_contact_kinship' ).change();

</script>
</body>
</html>
