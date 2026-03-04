<br>
<div class="container-search-seeker">

    <div class="row" style="padding:10px;">
        <div class="col-md-12">
        </div>
    </div>
</div>

<div class="container-seeker-message" style="padding:10px 20px;"></div>

<div class="formwraper">
    <div class="container-form-create">

        <div class="input-group">
            <label class="input-group-addon">Documento <span>*</span></label>
            <table width="100%" class="tbl-seeker-document-number">
                <tr>
                    <td width="80">
                        <select data-name="document_type" class="form-control">
                            <option value="">Tipo de Doc.</option>
                            <?php foreach ($document_types as $doc_type): ?>
                                <option value="<?php echo $doc_type->id; ?>">
                                    <?php e($doc_type->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input data-name="document_number" type="text" class="form-control" placeholder="Número de documento" value="<?php echo set_value('document_number'); ?>" maxlength="40">
                    </td>
                    <td>
                        <button class="btn btn-xs btn-primary" type="button" name="search_doc_number" style="margin:5px;">Buscar</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>
                        <button type="button" class="btn btn-xs btn-primary cancel-seeker-register">Cancelar</button>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo form_open_multipart('employer/recruitment/jobseeker_register',array('name' => 'seeker_form', 'id' => 'form-rys-seeker-create'));?>

            <input type="hidden" name="document_type">
            <input type="hidden" name="its_reniec">
            <input type="hidden" name="document_number">
            <input type="hidden" value="" name="current_address">
            <input type="hidden" value="" name="photo">
            <input type="hidden" name="register_add_action">

            <div class="input-group" style="display:none;">
                <label class="input-group-addon">Foto <span></span></label>
               <img src="" 
                    alt="Foto" 
                    class="photo" 
                    style="width:80px;">
            </div>

            <div class="input-group <?php echo (form_error('email'))?'has-error':'';?>">
                <label class="input-group-addon">Email <span>*</span></label>
                <input name="email" type="text" class="form-control" id="email" placeholder="Email" value="" maxlength="100" required>
                <?php echo form_error('email'); ?>
            </div>

            <div class="input-group <?php echo (form_error('pass'))?'has-error':'';?>">
                <label class="input-group-addon">Contraseña <span>*</span></label>
                <table width="100%">
                    <tr>
                        <td width="60%">
                            <input name="pass" 
                                    type="password" 
                                    class="form-control" 
                                    id="pass" 
                                    autocomplete="off" 
                                    placeholder="Contraseña" 
                                    value="" 
                                    maxlength="15" 
                                    required>
                        </td>
                        <td style="padding:5px;">
                            <button class="btn btn-xs btn-default btn-generate-password" 
                                    type="button">
                                    Generar
                            </button>
                            <button class="btn btn-xs btn-default btn-toggle-show-hide-password" 
                                    type="button">
                                    Ver / Ocultar
                            </button>
                        </td>
                    </tr>
                </table>
                
                <span style="color: #777;font-style: italic;display:block;">Debe contener mayúsculas, minúsculas, números y caracteres especiales.</span>
                <span style="color: #777;font-style: italic;">Ejemplo: estreLLA7583!!</span>
                <?php echo form_error('pass'); ?> 
            </div>

            <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
                <label class="input-group-addon">Nombre(s) <span>*</span></label>
                <input name="full_name" type="text" class="form-control" id="full_name" placeholder="Nombre(s)" value="" maxlength="30" required>
                <?php echo form_error('full_name'); ?>
            </div>
            <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
                <label class="input-group-addon">Apellido Paterno <span>*</span></label>
                <input name="paternal_last_name" type="text" class="form-control" id="paternal_last_name" placeholder="Apellido Paterno" value="" maxlength="30" required>
                <?php echo form_error('paternal_last_name'); ?>
            </div>
            <div class="input-group <?php echo (form_error('full_name'))?'has-error':'';?>">
                <label class="input-group-addon">Apellido Materno <span>*</span></label>
                <input name="maternal_last_name" type="text" class="form-control" id="maternal_last_name" placeholder="Apellido Materno" value="" maxlength="30" required>
                <?php echo form_error('maternal_last_name'); ?>
            </div>

            <div class="input-group <?php echo (form_error('gender'))?'has-error':'';?>">
                <label class="input-group-addon">Sexo <span>*</span></label>
                <select class="form-control" name="gender" id="gender" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($genders as $row_gender): ?>
                    <option value="<?php echo $row_gender->id; ?>">
                        <?php e($row_gender->name); ?>
                    </option>
                    <?php endforeach; ?>                               
                </select>
                <?php echo form_error('gender'); ?> </div>
            <div class="input-group <?php echo (form_error('dob_day'))?'has-error':'';?>">
                <label class="input-group-addon">Fecha de nacimiento <span>*</span></label>
                <select class="form-control" name="dob_day" id="dob_day" required>
                <option value="">Día</option>
                <?php 
                for($dy=1;$dy<=31;$dy++):
            $day =sprintf("%02s", $dy);
                    $selected = (set_value('dob_day')==$day)?'selected="selected"':'';
            ?>
                <option value="<?php echo $day;?>" <?php echo $selected;?>><?php echo $day;?></option>
                <?php endfor;?>
                </select>
                <select class="form-control" name="dob_month" id="dob_month" required>
                <option value="">Mes</option>
                <?php for($mnth=1;$mnth<=12;$mnth++):
                $month =sprintf("%02s", $mnth);
            $dummy_date = '2014-'.$month.'-'.'01';
                $selected = (set_value('dob_month')==$month)?'selected="selected"':'';
            ?>
                <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo ucwords(date('M', strtotime($dummy_date)));?></option>
                <?php endfor;?>
                </select>
                <select class="form-control" name="dob_year" id="dob_year" required>
                <option value="">Año</option>
                <?php for($year=date("Y")-10;$year>=1901;$year--):
                $selected = (set_value('dob_year')==$year)?'selected="selected"':'';
            if((set_value('dob_year')=='' && $year=='1980')){
                $selected = 'selected="selected"';
            }
            ?>
                <option value="<?php echo $year;?>" <?php echo $selected;?>><?php echo $year;?></option>
                <?php endfor;?>
                </select>
                <?php echo form_error('dob_day'); echo form_error('dob_month'); echo form_error('dob_month'); ?> </div>

            <div class="input-group <?php echo (form_error('civil_status')) ? 'has-error' : '';?>">
                <label class="input-group-addon">Estado civil <span>*</span></label>
                <select class="form-control" name="civil_status" id="civil_status" required>
                <option value="">Seleccione</option>

                <?php foreach ($civil_status as $row_civil_status): ?>
                    <option value="<?php echo $row_civil_status->id; ?>">
                    <?php e($row_civil_status->name); ?>
                    </option>
                <?php endforeach; ?>   
            
                </select>
                <?php echo form_error('civil_status'); ?>
            </div>
            <div class="input-group <?php echo (form_error('mobile_number'))?'has-error':'';?>">
                <label class="input-group-addon">Teléfono móvil <span>*</span></label>
                <input name="mobile_number" 
                       type="text" 
                       class="form-control" 
                       id="mobile_number" 
                       value="" 
                       maxlength="15" 
                       required/>    
                <?php echo form_error('mobile_number'); ?>
            </div>

            <div class="input-group <?php echo (form_error('nationality'))?'has-error':'';?>">
                <label class="input-group-addon" name="nationality">Nacionalidad <span>*</span></label>
                <select class="form-control" name="nationality" id="nationality" style="width:100%;" required>
                <?php foreach ($result_countries as $row_country): 
                    if ($row_country->country_citizen!=''):
                        $selected = ($country->ID == $row_country->ID)?'selected="selected"':'';
                        
                ?>
                <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_citizen;?></option>
                <?php endif; endforeach;?>
                </select>
                <?php echo form_error('nationality'); ?> 
            </div>
            <div class="input-group <?php echo (form_error('disability')) ? 'has-error' : '';?>">
                <label class="input-group-addon">
                <input id="check_disability" type="checkbox" name="check_disability" value="true" <?php echo (set_value('check_disability') != null ? 'checked' : ''); ?>>
                Presento discapacidad
                </label>
                <select class="form-control" name="disability" id="disability" required>
                    <?php foreach ($disabilities as $row_disability): ?>
                        <option value="<?php echo $row_disability->id; ?>">
                        <?php e($row_disability->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo form_error('disability'); ?>
            </div>  

            <div class="input-group <?php echo (form_error('country'))?'has-error':'';?>">
                <label class="input-group-addon">País <span>*</span></label>
                <select name="country" id="country" class="form-control" style="width:50%" required>
                <?php 
                foreach($result_countries as $row_country):

                if (empty($row_country->phone_code)) {
                    continue;
                }

                $selected = ($country->ID == $row_country->ID) ? 'selected="selected"':'';
                
                ?>
                <option value="<?php echo $row_country->ID;?>" <?php echo $selected;?>><?php echo $row_country->country_name;?></option>
                <?php endforeach;?>
                </select>
                <?php echo form_error('country'); ?>
            </div>

            <div class="input-group <?php echo (form_error('city'))?'has-error':'';?>">
                <label class="input-group-addon">Ubicación / Ciudad<span> *</span></label>
                <input id="city_text" name="city" type="text" class="form-control" value="<?php echo set_value("city"); ?>" autocomplete="off">

                <div id="ubigeos" class="row">
                <div class="col-md-4">
                    <select id="department" name="department" class="form-control" style="width: 100%;">
                    <option value="">Departamentos</option>  
                    <?php foreach ($departments as $row): ?>
                        <?php 
                            $department = $row->department; 
                            $department_selected =  $department == set_value('department') ? 'selected="selected"' : '';
                        ?>
                        <option value="<?php echo $department; ?>" <?php echo $department_selected; ?>>
                            <?php echo $department; ?>    
                        </option>
                    <?php endforeach; ?>  
                    </select>
                </div>
                
                <div class="col-md-4">
                    <select id="provinces" name="province" class="form-control" style="width: 100%;">
                    <option value="">Provincias</option>  
                    <?php foreach ($provinces as $row): ?>
                        <?php 
                            $province = $row->province; 
                            $province_selected =  $province == set_value('province') ? 'selected="selected"' : '';
                        ?>
                        <option value="<?php echo $province; ?>" <?php echo $province_selected; ?>>
                            <?php echo $province; ?>    
                        </option>
                    <?php endforeach; ?>  
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="districts" name="district" class="form-control" style="width: 100%;">
                    <option value="">Distritos</option>  
                    <?php foreach ($districts as $row): ?>
                        <?php 
                            $district = $row->district; 
                            $district_selected =  $district == set_value('district') ? 'selected="selected"' : '';
                        ?>
                        <option value="<?php echo $district; ?>" <?php echo $district_selected; ?>>
                            <?php echo $district; ?>    
                        </option>
                    <?php endforeach; ?>  
                    </select>
                </div>
                </div>
            </div>

            <div class="input-group" style="margin-top: 20px;">
                <label class="input-group-addon" style="vertical-align: top;line-height: 1.3;">¿Notificar al postulate?</label>
                <div>
                    <div class="row">
                        <div class="col-xs-8">
                            <label style="font-weight: normal;font-size: 13px;">Por Correo</label>
                        </div>
                        <div class="col-xs-4">
                            <div class="checkbox-wrapper-2" style="float: right;">
                                <input class="tgl tgl-light" id="notify-register-candidate-by-mail" type="checkbox" name="notify_candidate_by_mail" value="1" />
                                <label class="tgl-btn" for="notify-register-candidate-by-mail" style="width: 36px;height: 19px;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <label style="font-weight: normal;font-size: 13px;">Por WhatsApp</label>
                        </div>
                        <div class="col-xs-4">
                            <div class="checkbox-wrapper-2" style="float: right;">
                                <input class="tgl tgl-light" id="notify-register-candidate-by-whatsapp" type="checkbox" name="notify_candidate_by_whatsapp" value="1" />
                                <label class="tgl-btn" for="notify-register-candidate-by-whatsapp" style="width: 36px;height: 19px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div style="border-top: 1px solid #ddd;">
                <div align="right" style="padding: 6px 12px;">
                    <br/>
                    <button type="button" name="registser" class="btn btn-primary" style="display: none;">Registrar</button>
                    <button type="button" class="btn btn-default">Cancelar</button>
                    <button type="button" name="register_add" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $(function() {
        var iti_mobile = window.intlTelInput($( '#mobile_number' )[0], {
            loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),
            separateDialCode: true,
            autoPlaceholder: 'aggressive',
            initialCountry: "<?php echo strtolower($country->iso_3166_1_alpha2); ?>",
            //hiddenInput: () => ({ phone: "full_mobile_phone_number"}),
        });

        $( '#mobile_number' ).data('iti-instance', iti_mobile);
    });
</script>

