<script type="text/javascript">

$(document).ready(function() {

  function addWorkingHours() {
    var template = $( "#tpl-add-working-hours" ).html();
    var index  = $("#tpl-add-working-hours" ).generateSequence() * -1;

    var row = Mustache.render(template, {index: index});  
    $( "#tbl-working-hours tbody" ).append(row);
  }

  function validateDataRequest() {

    var totalSteps = $( ".content-step" ).length;
    var currentStep = $( ".content-step:visible").index() + 1;

    $( "#prev-step-request" ).prop('disabled', true);
    $( "#next-step-request" ).prop('disabled', true);
    $( "#create-request" ).prop('disabled', true);

    if (currentStep < totalSteps) {
      validateStep(currentStep);
      return;
    }

    window.onbeforeunload = null;
    $( "#form-create-request" ).get(0).submit();
  }

  function showErrorsStep(errors) {
    $.each(errors, function(fieldName, message_error) {

      if (fieldName == "field_working_hours") {
          $( ".field_working_hours" ).html('<p class="info-error"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;&nbsp;' + message_error + '</p>')
          $( ".field_working_hours" ).addClass("has-error");
        } else {

        var error = '<p class="info-error"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;&nbsp;' + message_error + '</p>';

        if (fieldName == 'functions[]') {
          $( ".js-error-functions" ).append(error);
          return false;
        }

        if (fieldName == 'dep_vacancies') {
          $( ".js-error-dep-vacancies" ).append(error);
          return false;
        }

        var input = $( "*[name='" + fieldName + "']" );
        var wrapperInput = input.closest(".input-group").addClass("has-error");
        wrapperInput.before(error);
      }
    });
  }

  function validateStep(step) {

    var stepInputs = $( ".content-step" ).eq(step - 1).find(":input");
    var data = stepInputs.serialize() + "&_step=" + step;

    stepInputs.prop('disabled', true);    

    $( ".has-error" ).removeClass("has-error");
    $( ".info-error").remove();
    var url = "<?php echo site_url('employer/staff_request/create_model_3/validate_step'); ?>"; 
    
    $.post(url, data, function(data) {

      var successValidation = data.status_validation;
      
      if (!successValidation) {
        showErrorsStep(data.message_errors);
        return;
      }

      showStep(step + 1);
    
    }, 'json')
    .fail(function(){
      alert('Ha ocurrido un error!');
    }).always(function(){
      stepInputs.prop('disabled', false);
      $( "#prev-step-request" ).prop('disabled', false);
      $( "#next-step-request" ).prop('disabled', false);
      $( "#create-request" ).prop('disabled', false);
    });
  }

  function searchCompetencesByJobChargeId(chargeId) {

    $( "#occupational_group" ).prop('disabled', true);
    $( "#next-step-request" ).prop('disabled', true);

    $.get( "<?php echo base_url('employer/staff_request/create_model_3/get_job_competences'); ?>/" + chargeId, {}, function(competences) {
      //Update view table
      $( "#table-competences .row-fixed-competence").remove();

      $.each(competences, function(i, row){
        var competenceName = row.competence_name;
        addRowFixedCompetence(competenceName);
      }); 
    }, 'json')
    .fail(function(){
      alert('Ha ocurrido un error!');
    })
    .always(function(){
      $( "#occupational_group" ).prop('disabled', false);
      $( "#next-step-request" ).prop('disabled', false);
    });
  }

  function getConsultants()
  {
    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_consultants'); ?>";
    $( "#consultant" ).html('<option value="">Cargando...</option>').prop('disabled', true);

    var data = {
      manager: $("#management").val(),
      model_id: '3'
    };

    $.post(url, data, function(data) {
    
      var consultants =  data.MESSAGE == 'OK' ? data.CONSULTORA : [];
      $.each(consultants, function(i, row) {
        var consultantValue = row.NO_CIA + '|' + row.CONSULTORA;
        $( "#consultant" ).append(
          '<option data-no_cia="' + row.NO_CIA + '" value="' + consultantValue + '">' + row.CONSULTORA + '</option>'
        );
      });      
    }, 'json')
    .fail(function() {
      console.error('¡Ha ocurrido un error al tratar de listar las consultoras!');
    }).always(function() {
      $( "#consultant" ).find("option:eq(0)").text("Seleccione");
      $( "#consultant" ).prop('disabled', false);
    }); 
  }

  function getBusinessUnit()
  {
    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_businnes_unit'); ?>";
    var data = {
      manager: $("#management").val(),
      consultant: $( "#consultant option:selected" ).data('no_cia')
    }
    $( "#business_unit" ).html('<option value="">Cargando...</option>').prop('disabled', true);

    $.post(url, data, function(data) {  

      var unit = data.MESSAGE == 'OK' ? data.BUSINESS_UNIT : [];

      $.each(unit, function(i, row) {
        var BusinessUnitValue = row.COD_UNIT + '|' + row.NAME_UNIT;

        $( "#business_unit" ).append(
          '<option data-uni_neg="' + row.COD_UNIT + '" value="' + BusinessUnitValue + '">' + row.NAME_UNIT + '</option>'
        );

      });      
      
    }, 'json')
    .fail(function() {

      console.error('¡Ha ocurrido un error al tratar de listar las unidades de negocio!');

    }).always(function() {

      $( "#business_unit" ).find("option:eq(0)").text("Seleccione");
      $( "#business_unit" ).prop('disabled', false);
    });
    
  }

  function getClientsCompany()
  {
    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_clients_company'); ?>";
    var data = {
      manager: $("#management").val(),
      no_cia: $( "#consultant option:selected" ).data('no_cia'),
      uni_neg: $( "#business_unit option:selected" ).data('uni_neg'),
      model_id: '3'
    }

    $( "#client_company" ).html('<option value="">Cargando...</option>').prop('disabled', true);
  
    $.post(url, data, function(data) {
    
      var clients =  data.MESSAGE == 'OK' ? data.CLIENTE : [];

      $.each(clients, function(i, row) {
        var clientValue = row.COD_CLIE + '|' + row.CLIENTE;
        $( "#client_company" ).append(
          '<option data-cod_clie="' + row.COD_CLIE + '" value="' + clientValue + '">' + row.CLIENTE + '</option>'
        );
      });      
    }, 'json')
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
        manager: $("#management").val(),
        no_cia: $( "#consultant option:selected" ).data('no_cia'),
        uni_neg: $( "#business_unit option:selected" ).data('uni_neg'),
        cod_clie: $( "#client_company option:selected" ).data('cod_clie'),
        model_id: '3'
    }

    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_cost_centers'); ?>";
    $( "#cost_center" ).html('<option value="">Cargando...</option>').prop('disabled', true); 
    
    $.post(url, data, function(data) {

      var cost_centers = data.MESSAGE == 'OK' ? data.CENTROCOSTO : [];
      
      $.each(cost_centers, function(i, row) {
        $( "#cost_center" ).append('<option value="' + row.COD_CCOSTO + '">' + row.COD_CCOSTO + '</option>');
      });      
    }, 'json')
    .fail(function() {
      alert('¡Ha ocurrido un error al tratar de listar los centro de costo!');
    }).always(function() {
      $( "#cost_center" ).find("option:eq(0)").text("Seleccione");
      $( "#cost_center" ).prop('disabled', false);
    });      
  }

  function cleanErrorReplaceEmployee() {
    var contentDiv = $( "#selector-replace-employee" ).closest(".input-group");
    contentDiv.siblings(".info-error").remove();
    contentDiv.removeClass("has-error");
  }

  function addRowBenefit(row) {
    var template = $( "#tpl-additional-benefits" ).html();
    var benefitInfo = row.minimum != null && row.maximum != null ? `De ${row.minimum} a ${row.maximum}` : '';
    var isChecked = row.minimum > 0 && row.maximum > 0;

    var row = Mustache.render(template, {
      'benefit_id': row.benefit_id,
      'benefit_name': row.benefit_name,
      'minimum': row.minimum,
      'maximum': row.maximum,
      'benefit_info': benefitInfo,
      'isChecked': isChecked
    });  
    $( "#table-additional-benefits tbody" ).prepend(row); 
  }

  function addRowFunctions(value) {
    var template = $( "#tpl-add-functions" ).html();

    var row = Mustache.render(template, {
      value: value
    });  
    $( "#table-functions tbody" ).prepend(row); 
  }

  function addRowAdditionalCompetence(value) {
    var template = $( "#tpl-add-additional-competence" ).html();

    var row = Mustache.render(template, {
      value: value
    });  
    $( "#table-competences tbody" ).prepend(row); 
  }

  function addRowFixedCompetence(competenceName) {
    var template = $( "#tpl-add-fixed-competence" ).html();
    var row = Mustache.render(template, {competenceName: competenceName});  
    $( "#table-competences tbody" ).append(row); 
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

  function getJobProfiles() {

     var data  = {
        consultant: $( "#consultant" ).val(),
        business_unit: $( "#business_unit" ).val(),
        client_company: $( "#client_company" ).val(),
        cost_center: $( "#cost_center" ).val()
    }

    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_job_profiles'); ?>";
    $( "#job-profile" ).html('<option value="">Cargando...</option>').prop('disabled', true); 
    
    $.post(url, data, function(data) {

      var job_profiles = data.job_profiles || [];
      
      $.each(job_profiles, function(i, row) {
        $( "#job-profile" ).append('<option value="' + row.ID + '">' + row.job_title + '</option>');
      });
    }, 'json')
    .fail(function() {
      alert('¡Ha ocurrido un error al tratar de listar los perfiles!');
    }).always(function() {
      $( "#job-profile" ).find("option:eq(0)").text("Seleccione");
      $( "#job-profile" ).prop('disabled', false);
      $( "#job-profile" ).select2();
      $( 'input[name="select_type_job"]:checked' ).change();
    });  
  }

  function getJobLayouts() {

    const consultantCode = (($( '#consultant' ).val() || '').split('|'))[0];
    const clientCode = (($( '#client_company' ).val() || '').split('|'))[0];
    
    const data  = {
      consultant_code: consultantCode,
      client_code: clientCode,
    }

    const url = "<?php echo site_url('employer/staff_request/staff_requests/get_job_layouts'); ?>";
    $( '#job-layout' ).html('<option value="">Cargando...</option>').prop('disabled', true); 
    
    $.post(url, data, function(res) {

      const jobLayouts = res.data || [];
      
      $.each(jobLayouts, function(i, row) {
        $( '#job-layout' ).append(`
          <option value="${row.id}" data-code-integration="${row.code_integration}">
            ${row.code ? row.code + ' - ' + row.job_title : row.job_title}
          </option>
        `);
      });
    }, 'json')
    .fail(function() {
      toastr["error"]('¡Ha ocurrido un error al tratar de listar los layouts de puestos!');
    }).always(function() {
      $( '#job-layout' ).find("option:eq(0)").text("Seleccione");
      $( '#job-layout' ).prop('disabled', false);
      $( '#job-layout' ).select2();
    });  
  }

  function confirmLeave() {
    window.setInterval(function() {  
      var beforeunload = null;
      var val = $.trim($( "#consultant" ).val());
      val+= $.trim($( "#client_company" ).val());
      val+= $.trim($( "#business_unit" ).val());
      val+= $.trim($( "#cost_center" ).val());
  
      if (val != '') {
        beforeunload = function() { return ''; }; 
      }
       window.onbeforeunload = beforeunload;
    }, 1000);
  }

  function showStep(step) {
    var totalSteps = $( ".content-step" ).length;
    var currentStep = step;

    $( "#prev-step-request" ).hide();
    $( "#next-step-request" ).hide();
    $( "#create-request" ).hide();

    if (currentStep > 1) {
      $( "#prev-step-request" ).show();
    }

    if (currentStep < totalSteps) {
      $( "#next-step-request" ).show();
    }

    if (totalSteps == currentStep) {
      $( "#create-request" ).show();
    }

    $( ".content-step" ).hide().eq((currentStep - 1)).show();

    $( "#step-counter ul li" ).removeClass("complete active");

    for (var i = 0; i < (currentStep - 1); i++) {
      $( "#step-counter ul li" ).eq(i).addClass('complete');  
    }

    $( "#step-counter ul li" ).eq((currentStep - 1)).addClass('active');
    $( "#step-counter" ).show();
    $( "html, body" ).scrollTop(0);
  }

  $( "#form-create-request" ).submit(function(e) {
    e.preventDefault();
    validateDataRequest();
  });

  $( "#prev-step-request" ).click(function(){  
      var prevStep = $( ".content-step:visible").index();
      showStep(prevStep);
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

  $( "#occupational_group" ).change(function(){
    var job_charge_id = $(this).val();
    $( ".row-fixed-competence" ).remove();
    searchCompetencesByJobChargeId(job_charge_id);
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

  $( "#reason_request" ).change(function() {
    var reasonRequestVal = $(this).val();
    
    if (reasonRequestVal == 'replacement' || 
        reasonRequestVal == 'vacations'  || 
        reasonRequestVal == 'license') {
      $( "#content-replace-employee" ).show();
    } else {
      cleanErrorReplaceEmployee();
      $( "#content-replace-employee" ).hide();
    }
  });

  $( "#add-working-hours" ).click(function(e) {
    e.preventDefault();
    addWorkingHours();
  });

  $(document).on("click", ".remove-working-hours", function(e) {
    e.preventDefault();
    $(this).closest('.row-working-hours').remove();
  });

  $( "#management" ).change(function () {
    getConsultants();
  })

  $( "#consultant" ).change(function() {
    getBusinessUnit();
  });

  $( "#client_company" ).change(function() {
    getCostCenters();
    getJobLayouts();
  });

  $( "#business_unit" ).change(function() {
    getClientsCompany();
  });

  $( "#cost_center" ).change(function(){
    getJobProfiles();
  });

  $( "#job-profile" ).change(function(){
      var jobProfileID = $.trim($(this).val());

      if (jobProfileID == '') {
        return;
      }

      var url = "<?php echo site_url('employer/staff_request/staff_requests/get_data_job_profile/'); ?>" + jobProfileID;

      $.get(url, {}, function(response) {

        var jobProfile = response.job_profile;
        var profile = jobProfile.profile;

        $( '#minimum_salary' ).val(profile.basic_minimum);
        $( '#maximum_salary' ).val(profile.basic_maximum);
        $( '#monthly_gross_salary' ).val(profile.basic_maximum);

        $( "#occupational_group" ).val(profile.job_charge_ID);
        $( "#labor_experience_time" ).val(profile.experience);
        $( "#specific_knowledges" ).val(profile.education_min_detail);
        $( "#general_knowledges" ).val(profile.education);

        var skills = jobProfile.skills;
        var responsibilities = jobProfile.responsibilities;
        var benefits = jobProfile.benefits;

        $( "#table-additional-benefits tbody tr" ).remove();

        for (var i = 0; i < benefits.length; i++) {
          var benefit = (benefits[i]);
          addRowBenefit(benefit);
        }
      }, 'json'); 
  });

  $( '#type-requirement' ).change(function(){
    
    if ($(this).val() == 'REGULAR') {
      $( 'input[name="delivery_date"]').val("<?php echo date("Y-m-d",strtotime(date('Y-m-d') . "+ 7 day")); ?>");
      $( 'input[name="delivery_date"]').hide();
      $( '#delivery-date-regular').show();
    }

    if ($(this).val() == 'ESPECIAL') {
      $( '#delivery-date-regular' ).hide();
      $( 'input[name="delivery_date"]').show();
    }
  });

  $( 'select[name="department[]"]' ).change(function(){
    updateInputZone();
  });

  $( 'input[name="req_by_department"]' ).change(function(){

    $( '.content-req-by-department' ).hide();

    if ($(this).is(':checked')) {
      $( '.content-vacancies' ).show();
      $( '.content-location-department' ).show();
      $( '.content-department-vacancies' ).hide();
    } else {
      $( '.content-location-department' ).hide();
      $( '.content-vacancies' ).hide();
      $( '.content-department-vacancies' ).show();
    }

    updateInputZone();
  });

  $( '.add-item-department-vacancies' ).click(function(){
    
    const template = $( "#tpl-department" ).html();
    const index  = $("#tbl-department-vacancies" ).generateSequence() * -1;
    const selectDepartament = Mustache.render(template, {
      'index': index,
    });  
    
    $( '#tbl-department-vacancies tbody' ).prepend(`
      <tr>
        <td>
        ${selectDepartament}
        </td>
        <td width="100">
          <input type="number" name="dep_vacancies[${index}][vacancies]" class="form-control" style="text-align:center;">
        </td>
        <td width="10" style="vertical-align: middle;">
          <button class="remove-dep-vanacies">
            <i class="glyphicon glyphicon-remove"></i>
          </button>
        </td>
      </tr>
    `);
  });

  $( document ).on('click', '.remove-dep-vanacies', function(e){
    $(this).closest('tr').remove();
    updateInputZone();
  });

  $( document ).on('change', '.select-dep-vacancies', function(e){
    updateInputZone();
  });

  function updateInputZone() {

    const isChecked = $( 'input[name="req_by_department"]' ).is(':checked');

    if (isChecked) {
      $( "select[name='zone[]']" ).closest('.input-group').hide();
      $( 'p.info-error' ).remove();
      
      const depList = $( 'select[name="department[]"]' ).val();

      if (depList && depList.includes('Lima')) {
        $( "select[name='zone[]']" ).closest('.input-group').show();
      }
    } else {
      const departmentLima = $( '.select-dep-vacancies' ).filter(function(i, e){
          return $(e).val() == 'Lima';
      });

      $( 'p.info-error' ).remove();
      $( "select[name='zone[]']" ).closest('.input-group').hide();

      if (departmentLima.length > 0)  {
        $( "select[name='zone[]']" ).closest('.input-group').show();
      }
    } 
  }

  $( '#request-template' ).change(function(){

    $( '#job-title-input' ).closest('.input-group').removeClass("has-error").hide();
    $( '#job-title-input' ).closest('.input-group').prev('.info-error').remove();

    $( '#job-profile' ).closest('.input-group').removeClass("has-error").hide();
    $( '#job-profile' ).closest('.input-group').prev('.info-error').remove();

    $( '#job-layout' ).closest('.input-group').removeClass("has-error").hide();
    $( '#job-layout' ).closest('.input-group').prev('.info-error').remove();

    if ($(this).val() == '1') {
      $( '#job-title-input' )
        .closest('.input-group')
        .show();
    }

    if ($(this).val() == '2') {
      $( '#job-profile' )
        .closest('.input-group')
        .show();
      
      $( '#job-profile' ).change();
    }

    if ($(this).val() == '3') {
      $( '#job-layout' )
        .closest('.input-group')
        .show();

      $( '#job-layout' ).change();
    } 
  });

  $( '#job-layout' ).change(function(){
    if ($(this).val() == '') {
      return;
    }
  
    const url = "<?php echo site_url('employer/job_layouts/job_layouts/get_job_layout_info'); ?>";
    const data = {
      'id': $(this).val()
    };

    $.get(url, data, function(response) {

      const jobLayout = response.data;
   
      $( '#minimum_salary' ).val(jobLayout.basic_minimum);
      $( '#maximum_salary' ).val(jobLayout.basic_maximum);
      $( '#monthly_gross_salary' ).val(jobLayout.basic_maximum);

      $( "#occupational_group" ).val(jobLayout.job_charge_ID);
      $( "#labor_experience_time" ).val(jobLayout.experience);
      $( "#specific_knowledges" ).val(jobLayout.education_min_detail);
      $( "#general_knowledges" ).val(jobLayout.education);

      const benefits = jobLayout.benefits;

      $( "#table-additional-benefits tbody tr" ).remove();

      for (let i = 0; i < benefits.length; i++) {
        const benefit = (benefits[i]);
        addRowBenefit(benefit);
      }
    }, 'json'); 
  });

  $( "#location" ).select2();
  $( "#selector-replace-employee" ).select2();
  $( "#job-profile" ).select2();
  $( "select[name='zone[]']" ).select2();
  $( "select[name='department[]']" ).select2();
  $( 'input[name="req_by_department"]' ).change();
  $( '#job-layout' ).select2();

  confirmLeave();

  showStep(1);

 $( ".step-inputs" ).each(function(){
    $(this).prepend('<div class="info-required"><span>*</span> (Campos obligatorios) </div>');
    $( "#form-confirm .info-required" ).remove();
 });
});
</script>