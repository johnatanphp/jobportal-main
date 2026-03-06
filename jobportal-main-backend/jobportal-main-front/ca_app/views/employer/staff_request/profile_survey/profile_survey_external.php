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

                .step-counter {
                    text-align: right;
                    text-transform: uppercase;
                }

                .btn-controls-step {
                    display: none;
                }

                .content-checkbox label {
                    display: block;
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
                #table-range-age .separator
                {
                    padding: 0px 20px;
                    text-align: center;
                }

                #text-guide-function {
                    font-size: 17px;
                    text-transform: lowercase;
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

                .info-error {
                    display:block;
                    padding: 2px 0px 5px;
                    color:#a94442;
                    font-size: 13px;
                }

            </style>
    
            <div class="formwraper" style="border:0;">
                <div class="row">
                    <?php echo form_open_multipart('employer/staff_request/profile_survey_external/save/' . $request->ID, array('id' => 'form-request-save-profile-survey-external', 'class' => 'formint', 'style' => 'padding: 5px 15px;'));?>
                        <div class="col-md-12">
                            <div>
                                <input type="hidden" name="request_id" value="<?php echo $request->ID; ?>" >
                                <input type="hidden" name="job_profile" value="<?php echo $request->job_profile_ID; ?>">
                                <input type="hidden" name="job_layout" value="<?php echo $request->job_layout_id; ?>">
                            </div>
                       
                            <div class="container-submit" style="padding: 10px 0px;position: sticky;top: 0px;background: #f5f5f5;z-index: 999;border: 1px solid #ccc;">
                                <div class="row" style="margin: 0;">
                                    <div class="col-sm-6">
                                        <h4><?php e(mb_strtoupper(@$request->job_title)); ?></h4>
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
                                <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                                        Descripción del puesto
                                    </a>
                                    </h4>
                                </div>
                                <div id="collapse2" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                    <?php $this->load->view('employer/staff_request/section_sr_external/section_job_descriptions'); ?>
                                    </div>
                                </div>
                                </div>

                                <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                                        Contratación
                                    </a>
                                    </h4>
                                </div>
                                <div id="collapse3" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                    <?php $this->load->view('employer/staff_request/section_sr_external/section_hiring'); ?>
                                    </div>
                                </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                                                Beneficios laborales
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse4" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <div id="section-additional-benefits">
                                                <div class="step-title">
                                                    Beneficios adicionales del puesto
                                                </div>

                                                <div class="step-inputs">
                                                    <table id="table-additional-benefits" width="100%" class="table table-striped">
                                                        <?php 
                                                            $benefit_info_data = [];

                                                            if ($job_profile) {
                                                                $benefit_info_data = [
                                                                'Bono de Productividad' => "De $job_profile->bonuses_commissions_minimum a $job_profile->bonuses_commissions_maximum Soles",
                                                                'Comisiones' => "De $job_profile->bonuses_commissions_minimum a $job_profile->bonuses_commissions_maximum Soles",
                                                                'Movilidad' => "De $job_profile->mobility_minimum a $job_profile->mobility_maximum Soles",
                                                                'Vales de Alimento' => "De $job_profile->food_maximum a $job_profile->food_maximum Soles"
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
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                                            Requisitos del puesto
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse5" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                        <?php $this->load->view('employer/staff_request/section_sr_external/section_job_requirements'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
                                            Conocimientos
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse6" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                        <?php 
                                            $this->load->view('employer/staff_request/section_sr_external/section_knowledges'); 
                                        ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">
                                            Informática
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse7" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                        <?php 
                                            $this->load->view('employer/staff_request/section_sr_external/section_computing');  
                                        ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse8">
                                            Lenguajes
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse8" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                        <?php 
                                            $this->load->view('employer/staff_request/section_sr_external/section_languages'); 
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse9">
                                            Funciones del puesto
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse9" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                        <?php 
                                            $this->load->view('employer/staff_request/section_sr_external/section_job_functions'); 
                                        ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse10">
                                            Competencias
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse10" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                        <?php 
                                            $this->load->view('employer/staff_request/section_sr_external/section_competences');  
                                        ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse11">
                                            Comentarios adicionales
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse11" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                        <?php
                                            $this->load->view('employer/staff_request/section_sr_external/section_additional_comments'); 
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Footer-->
<script src="<?php echo base_url('public/js/bad_words.js'); ?>"></script>
<script src="<?php echo base_url('public/js/jquery.timepicker.js'); ?>" type="text/javascript"></script>  
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

<script id="tpl-add-additional-competence" type="text/template">
    <tr class="row-additional-competence">
    <td>
        <input type="text" name="additional_competences[]" class="form-control">
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
    <input type="text" name="computing[{{index}}][name]" class="form-control">
    <input type="hidden" name="computing[{{index}}][type]" class="form-control" value="application">
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

<script id="tpl-add-functions" type="text/template">
    <tr>
    <td>
        <input type="text" name="functions[]" class="form-control">
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
    <input type="text" name="languages[{{index}}][name]" class="form-control">
    </td>
    <td>
    <select name="languages[{{index}}][reading_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
    </select>
    </td>
    <td>
    <select name="languages[{{index}}][speaking_level]" class="form-control">
        <option value="">Nivel</option>
        <option value="basic">Básico</option>
        <option value="intermediate">Intermedio</option>
        <option value="advanced">Avanzado</option>
    </select>
    </td>
    <td>
    <select name="languages[{{index}}][writing_level]" class="form-control">
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
<script type="text/javascript">

$(function(){

    window.indexLanguages = 0;
    window.indexComputing = 0;
    window.indexLanguages = 0;

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

    function addWorkingHours() {
        var template = $( "#tpl-add-working-hours" ).html();
        var index  = $("#tpl-add-working-hours" ).generateSequence() * -1;

        var row = Mustache.render(template, {index: index});  
        $( "#tbl-working-hours tbody" ).append(row);
    }

    function addRowFunctions()
    {
        var template = $( "#tpl-add-functions" ).html();

        var row = Mustache.render(template, {});  
        $( "#table-functions tbody" ).prepend(row); 
    }

    function addRowLanguages() {
        var template = $( "#tpl-add-languages" ).html();
        var counterLanguages = $( "#table-languages" ).data('counterLanguages') - 1;    
        $( "#table-languages" ).data('counterLanguages', counterLanguages);

        var row = Mustache.render(template, {index: counterLanguages});  
        $( "#table-languages tbody" ).prepend(row);
    }

    function addRowApplication() {
        var template = $( "#tpl-add-application" ).html();
        var counterComputing = $( ".tbl-section-computing" ).data('counterComputing') - 1;
        $( ".tbl-section-computing" ).data('counterComputing', counterComputing);

        var row = Mustache.render(template, {index: counterComputing});  
        $( "#table-applications tbody" ).prepend(row);
    }

    function addRowAdditionalCompetence() {
        var template = $( "#tpl-add-additional-competence" ).html();

        var row = Mustache.render(template, {});  
        $( "#table-competences tbody" ).prepend(row); 
    }

    $( "#form-request-save-profile-survey-external" ).submit(function(e) {
        e.preventDefault();
        if (!window.confirm("¿Está seguro de guardar el levantamiento de perfil?")) {
            return;
        }
        
        $( ".has-error" ).removeClass("has-error");
        $( ".info-error").remove();

        var url = $(this).prop('action');
        var data = $(this).serialize();

        parent = $(this).find('.container-submit');
        parent.find('button').prop('disabled', true);
        parent.find('button[type=submit]').html('Guardando...');

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

    $( "#btn-add-application" ).click(function(){
        addRowApplication();
    });

    $( "#btn-add-language" ).click(function(){
        addRowLanguages();
    });

    $( "#btn-add-function" ).click(function(){
        addRowFunctions();
    });

    $( "#btn-add-additional-competence" ).click(function(){
        addRowAdditionalCompetence();
    });

    $(document).on("click", "#remove-replace-employee", function(){
        $( "#row-selector-replace-employee" ).hide();
        $( "#row-search-replace-employee" ).show();
        $( "#selector-replace-employee" ).val("");
    });

    $( "#reason_request" ).change(function() {
    $( "#content-replace-employee" ).hide();
    var reasonRequestVal = $(this).val();

    if (reasonRequestVal == 'replacement' || 
        reasonRequestVal == 'vacations'  || 
        reasonRequestVal == 'license') {
        $( "#content-replace-employee" ).show();
    }
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
