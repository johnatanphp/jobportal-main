<script type="text/javascript">
$(function() {
  function getContractModels(params) {
    var url = "<?php echo site_url('general/overall_web_services/get_contract_models'); ?>";
    var data = params;

    var select = $( 'select[name="contract_type_model"]');
    select.html('<option value="">Cargando...</option>').prop('disabled', true);

    $.post(url, data, function(response) {

        var results =  response.data;
        
        $.each(results, function(i, row) {
            select.append(`
                <option value="${row.contract_model_code}||${row.contract_model_name}" >
                    ${row.contract_model_name}
                </option>
            `);
        });      
    }, 'json')
    .fail(function() {
        toastr["error"]('¡Ha ocurrido un error al tratar de listar los modelos de contratos!');
    }).always(function() {
        select.find("option:eq(0)").text("Seleccione");
        select.prop('disabled', false);
    }); 
  }

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

        var input = $( "*[name='" + fieldName + "']" );
        var wrapperInput = input.closest(".input-group").addClass("has-error");
        wrapperInput.before(error);
      }
    });
  }

  function validateStep(step) {

    var stepInputs = $( ".content-step" ).eq(step - 1).find(":input");
    var data = $( '#form-create-request' ).serialize() + "&_step=" + step;

    stepInputs.prop('disabled', true);    

    $( ".has-error" ).removeClass("has-error");
    $( ".info-error").remove();
    var url = "<?php echo site_url('employer/staff_request/create_external_staff_request/validate_step'); ?>"; 
    
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

    $.get( "<?php echo base_url('employer/staff_request/create_external_staff_request/get_job_competences'); ?>/" + chargeId, {}, function(competences) {
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

  function setDataConsultants(consultants) {  
    $.each(consultants, function(i, row) {
      var consultantValue = row.NO_CIA + '|' + row.CONSULTORA;
      $( "#consultant" ).append(
        '<option data-no_cia="' + row.NO_CIA + '" value="' + consultantValue + '">' + row.CONSULTORA + '</option>'
      );
    });  
    $( "#consultant" ).find("option:eq(0)").text("Seleccione");
    $( "#consultant" ).prop('disabled', false);
  }


  function setDataClients(clients) {  
    $.each(clients, function(i, row) {
      var clientValue = row.COD_CLIE + '|' + row.CLIENTE;
      $( "#client_company" ).append(
        '<option data-cod_clie="' + row.COD_CLIE + '" value="' + clientValue + '">' + row.CLIENTE + '</option>'
      );
    });
    $( "#client_company" ).find("option:eq(0)").text("Seleccione");
    $( "#client_company" ).prop('disabled', false);
  }

  function setDataCostCenters(costCenters) {  
    $.each(costCenters, function(i, row) {
      $( "#cost_center" ).append('<option value="' + row.COD_CCOSTO + '">' + row.COD_CCOSTO + '</option>');
    });  
    $( "#cost_center" ).find("option:eq(0)").text("Seleccione");
    $( "#cost_center" ).prop('disabled', false);
  }

  function getConsultants()
  {
    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_consultants'); ?>";
    $( "#consultant" ).html('<option value="">Cargando...</option>').prop('disabled', true);
    
    const parameters = {
      model_id: '2'
    };

    $.post(url, parameters, function(data) {
      const consultants =  data.MESSAGE == 'OK' ? data.CONSULTORA : [];
      setDataConsultants(consultants);    
    }, 'json')
    .fail(function() {
      console.error('¡Ha ocurrido un error al tratar de listar las consultoras!');
    }).always(function() {}); 
  }

  function getClientsCompany()
  {
    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_clients_company'); ?>";
    var data = {
      no_cia: $( "#consultant option:selected" ).data('no_cia'),
      uni_neg: $( "#business_unit option:selected" ).data('uni_neg'),
      model_id: '2'
    }

    $( "#client_company" ).html('<option value="">Cargando...</option>').prop('disabled', true);
  
    $.post(url, data, function(res) {
      var clients =  res.MESSAGE == 'OK' ? res.CLIENTE : [];
      setDataClients(clients);
    }, 'json')
    .fail(function() {
      alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
    }).always(function() {
      $( "#client_company" ).find("option:eq(0)").text("Seleccione");
      $( "#client_company" ).prop('disabled', false);
    }); 
  }

  function getAreas()
  {
    var data  = {
        no_cia: $( "#consultant option:selected" ).data('no_cia'),
    }

    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_areas'); ?>";
    $( "#wf-areas" ).html('<option value="">Cargando...</option>').prop('disabled', true); 
    
    $.post(url, data, function(res) {

      var areas = res.status ? res.areas : [];
      
      $.each(areas, function(i, row) {
        $( "#wf-areas" ).append('<option value="' + row.code + '">' + row.name + '</option>');
      });      
    }, 'json')
    .fail(function() {
      alert('¡Ha ocurrido un error al tratar de listar los centro de costo!');
    }).always(function() {
      $( "#wf-areas" ).find("option:eq(0)").text("Seleccione");
      $( "#wf-areas" ).prop('disabled', false);
    });      
  }

  function getCostCenters()
  {
    var data  = {
        no_cia: $( "#consultant option:selected" ).data('no_cia'),
        uni_neg: $( "#business_unit option:selected" ).data('uni_neg'),
        cod_clie: $( "#client_company option:selected" ).data('cod_clie'),
        model_id: '2'
    }

    var url = "<?php echo site_url('employer/staff_request/staff_requests/get_cost_centers'); ?>";
    $( "#cost_center" ).html('<option value="">Cargando...</option>').prop('disabled', true); 
    
    $.post(url, data, function(data) {

      var costCenters = data.MESSAGE == 'OK' ? data.CENTROCOSTO : [];
      setDataCostCenters(costCenters);

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

    var row = Mustache.render(template, {
      'benefit_id': row.benefit_id,
      'benefit_name': row.benefit_name,
      'minimum': row.minimum,
      'maximum': row.maximum,
      'benefit_info': row.minimum && row.maximum ? `De ${row.minimum} a ${row.maximum}` : ''
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

    var url = "<?php echo site_url('employer/staff_request/create_external_staff_request/get_data_job_profiles'); ?>";
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
    $( '#job_layout' ).html('<option value="">Cargando...</option>').prop('disabled', true); 
    
    $.post(url, data, function(res) {

      const jobLayouts = res.data || [];
      
      $.each(jobLayouts, function(i, row) {
        $( '#job_layout' ).append(`
          <option value="${row.id}" data-code-integration="${row.code_integration}">
            ${row.code ? row.code + ' - ' + row.job_title : row.job_title}
          </option>
        `);
      });
    }, 'json')
    .fail(function() {
      toastr["error"]('¡Ha ocurrido un error al tratar de listar los layouts de puestos!');
    }).always(function() {
      $( '#job_layout' ).find("option:eq(0)").text("Seleccione");
      $( '#job_layout' ).prop('disabled', false);
      $( '#job_layout' ).select2();
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

  $( "#consultant" ).change(function() {
    $( "#business_unit" ).val("");
    //getAreas();
    
    $( ".container-wf-areas" ).hide();
    if ($( "#consultant option:selected" ).data('no_cia') == '05') {
    //  $( ".container-wf-areas" ).show();
    }
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
    $( "#job-title" ).text($("#job-profile option:selected").text());

      var jobProfileID = $(this).val();
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

        $( "#table-functions tbody tr" ).remove();

        for (var i = 0; i < responsibilities.length; i++) {
          var functionName = (responsibilities[i]).responsibility;
          addRowFunctions(functionName);
        }

        $( "#table-competences tbody tr" ).remove();
        $( "#occupational_group" ).change();

        for (var i = 0; i < skills.length; i++) {
          var competenceName = (skills[i]).skill_name;
          addRowAdditionalCompetence(competenceName);
        }
      }, 'json'); 
  });

  $( '#request_template' ).change(function(){
    $( '#job-profile' ).closest('.input-group').removeClass("has-error").hide();
    $( '#job-profile' ).closest('.input-group').prev('.info-error').remove();

    $( '#job_layout' ).closest('.input-group').removeClass("has-error").hide();
    $( '#job_layout' ).closest('.input-group').prev('.info-error').remove();

    if ($(this).val() == '1') {
      $( '#job-profile' )
        .closest('.input-group')
        .show();
      
      $( '#job-profile' ).change();
    }

    if ($(this).val() == '2') {
      $( '#job_layout' )
        .closest('.input-group')
        .show();

      $( '#job_layout' ).change();
    } 
  });

  $( '#job_layout' ).change(function(){

    if ($(this).val() == '') {
      return;
    }

    const text = $(this).find("option:selected").text();
    $( "#job-title" ).text(text);
  
    const url = "<?php echo site_url('employer/job_layouts/job_layouts/get_job_layout_info'); ?>";
    const data = {
      'id': $(this).val()
    };

    $.get(url, data, function(response){

      const jobLayoutData = response.data;

      $( '#minimum_salary' ).val(jobLayoutData.basic_minimum);
      $( '#maximum_salary' ).val(jobLayoutData.basic_maximum);
      $( '#monthly_gross_salary' ).val(jobLayoutData.basic_maximum);

      $( "#occupational_group" ).val(jobLayoutData.job_charge_id);
      $( "#labor_experience_time" ).val(jobLayoutData.experience);
      $( "#specific_knowledges" ).val(jobLayoutData.education_min_detail);
      $( "#general_knowledges" ).val(jobLayoutData.education);

      const skills = jobLayoutData.skills;
      const responsibilities = jobLayoutData.responsibilities;
      const benefits = jobLayoutData.benefits;

      $( "#table-additional-benefits tbody tr" ).remove();

      for (let i = 0; i < benefits.length; i++) {
        const benefit = (benefits[i]);
        addRowBenefit(benefit);
      }

      $( "#table-functions tbody tr" ).remove();

      for (let i = 0; i < responsibilities.length; i++) {
        const functionName = (responsibilities[i]).responsibility;
        addRowFunctions(functionName);
      }

      $( "#table-competences tbody tr" ).remove();
      $( "#occupational_group" ).change();

      for (let i = 0; i < skills.length; i++) {
        const competenceName = (skills[i]).skill_name;
        addRowAdditionalCompetence(competenceName);
      }
    }, 'json');
  });

  $( '#start_date_work' ).change(function(){
    $( '#end_date_work' ).val('');
    $( '#end_date_work' ).closest('.has-error').prev().remove();
    $( '#end_date_work' ).closest('.has-error').removeClass('has-error');
    $( '#end_dare_work' ).prop('disabled', false);
  });

  $( '#type-expense' ).change(function(){
    const expenseType = $(this).val();

    $( '.js-resource-type-expense' ).each(function(i, e){
      if (!$(e).data('select')) {
        $(e).val(expenseType);
      }
    });
  });

  function cloneSearchRequest(requestId) {

    $( '#modal-sr-clone-loading' ).modal('show');

    jQuery.ajaxSetup({async: false});

    const url = "<?php echo site_url('employer/staff_request/staff_requests/clone_search_request'); ?>";
    const data = {
      request_id: requestId
    }
  
    $.post(url, data, function(data) {
      
      const form = $( '#form-create-request' );
      const request = data.data;

      const consultants = request.list_consultants.CONSULTORA ?? [];
      const clients = request.list_clients.CLIENTE ?? [];
      const costCenters = request.list_cost_centers.CENTROCOSTO ?? [];
      
      setDataConsultants(consultants);
      setDataClients(clients);
      setDataCostCenters(costCenters);

      $( 'select[name="type_requirement"]', form).val(request.type_requirement);
      $( 'select[name="type_expense"]', form).val(request.type_expense);

      $( 'select[name="type_expense"]', form).val(request.type_expense);
      $( 'select[name="consultant_name"]', form).val(request.no_cia + '|' + request.consultant_name);
      $( 'select[name="business_unit_name"]', form).val(request.cod_business_unit + '|' + request.business_unit_name);
      $( 'select[name="client_company_name"]', form).val(request.cod_clie + '|' + request.client_company_name);
      $( 'select[name="cost_center"]', form).val(request.cost_center);
      $( 'input[name="cost_center_client"]', form).val(request.cost_center_client);

      if (request.eecc_code) {
        const eecc_td1 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(0)');
        const eecc_td2 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(1)');
        const eecc_td3 = $( '#tbl-field-select-eecc tr:eq(0)' ).find('td:eq(2)');
        
        eecc_td1.html(`
          ${request.eecc_code}
          <br>
          ${ request.eecc_form_id ? request.eecc_form_id : request.eecc_description }
          <input type="hidden" name="eecc_code" value="${request.eecc_code}">
          <input type="hidden" name="eecc_description" value="${request.eecc_description}">
          <input type="hidden" name="eecc_form_id" value="${request.eecc_form_id}">
          <input type="hidden" name="eecc_job_code" value="${request.eecc_job_code}">
          <input type="hidden" name="eecc_job_vacancies" value="${request.eecc_job_vacancies}">
        `);
        eecc_td1.show();
        
        eecc_td2.html(`
          <a id="btn-select-eecc" href="#">
            <i class="glyphicon glyphicon-pencil"></i>
          </a>
        `);

        eecc_td2.show();
        eecc_td3.show();
        $( '#btn-remove-select-eecc' ).show();
      }

      getJobLayouts();
      
      if (request.job_profile_ID) {
        $( 'select[name="request_template"]', form).val('1');
        $( 'select[name="cost_center"]', form).change();
        $( 'select[name="job_profile"]', form).val(request.job_profile_ID);
      }

      if (request.job_layout_id) {
        $( 'select[name="request_template"]', form).val('2');
        $( 'select[name="job_layout"]', form).val(request.job_layout_id);
      }

      $( 'select[name="request_template"]', form).change();

      //Contratacion 
      $( 'input[name="contract_type_model_code"]', form).val(request.contract_type_model_code);
      $( 'input[name="vacancies"]', form).val(request.vacancies);
      $( 'select[name="industry"]', form).val(request.industry_ID);
      $( 'input[name="n_people_reporting"]', form).val(request.n_people_reporting);
      $( 'input[name="name_immediate_boss"]', form).val(request.name_immediate_boss);
      $( 'input[name="charge_immediate_boss"]', form).val(request.charge_immediate_boss);
      $( 'select[name="modality_contracting"]', form).val(request.modality_contracting);

      $( 'input[name="contract_time_qty"]', form).val(request.contract_time_qty);
      $( 'select[name="contract_time_duration"]', form).val(request.contract_time_duration);
      $( 'select[name="reason_request"]', form).val(request.reason_request);
      $( 'select[name="reason_request"]', form).change();
      $( 'select[name="job_mode"]', form).val(request.job_mode);
      $( 'select[name="workplace"]', form).val(request.workplace);
      $( 'textarea[name="working_hours_manual"]', form).val(request.working_hours);
      $( 'input[name="start_date_work"]', form).val(request.start_date_work);
      $( 'input[name="end_date_work"]', form).val(request.end_date_work);
      $( 'select[name="location"]', form).val(request.location);
      $( 'select[name="location"]', form).select2();
      $( 'input[name="job_address"]', form).val(request.job_address);      
      $( 'select[name="salary_delivery_period"]', form).val(request.salary_delivery_period);

      //Beneficios
      const additionalBenefits = request.additional_benefits;

      for (let row in additionalBenefits) {
        const benefit = additionalBenefits[row];
        $( `#benefit-detail-${benefit.benefit_ID}` ).val(benefit.detail);     
      }

      //Recursos
      const resources = request.request_resource; 
      
      if (resources) {
        $( 'select[name="emo_type"]', form).val(resources.emo_type_id);
        $( 'input[name="emo_protocol_detail"]', form).val(resources.emo_protocol_detail); 
        $( 'select[name="emo_expense_type"]', form).val(resources.emo_expense_type); 
        $( 'input[name="emo_staff_charge"]', form).val(resources.emo_staff_charge);
        $( 'select[name="emo_type"]', form).change();

        $( 'select[name="screening_type"]', form).val(resources.screening_type_id);
        $( 'select[name="screening_expense_type"]', form).val(resources.screening_expense_type); 
        $( 'input[name="screening_staff_charge"]', form).val(resources.screening_staff_charge);
        $( 'select[name="screening_perform_stage"]', form).val(resources.screening_perform_stage); 
        $( 'select[name="screening_type"]', form).change();

        $( 'select[name="covid19_type"]', form).val(resources.covid19_type_id);
        $( 'select[name="covid19_expense_type"]', form).val(resources.covid19_expense_type); 
        $( 'input[name="covid19_staff_charge"]', form).val(resources.covid19_staff_charge);
        $( 'select[name="covid19_perform_stage"]', form).val(resources.covid19_perform_stage); 
        $( 'select[name="covid19_type"]', form).change();

        $( 'select[name="exam_complementary_type"]', form).val(resources.exam_complementary);
        $( 'select[name="exam_complementary_staff_charge"]', form).val(resources.exam_complementary_staff_charge);
        $( 'select[name="exam_complementary_type"]', form).change();

        $( 'select[name="verify_home"]', form).val(resources.verify_home);
        $( 'select[name="verify_home_perform_stage"]', form).val(resources.verify_home_perform_stage);
        $( 'input[name="verify_home_staff_charge"]', form).val(resources.verify_home_staff_charge);
        $( 'select[name="verify_home"]', form).change();


        $( 'select[name="verify_credit"]', form).val(resources.verify_credit);
        $( 'select[name="verify_credit_perform_stage"]', form).val(resources.verify_credit_perform_stage);
        $( 'input[name="verify_credit_staff_charge"]', form).val(resources.verify_credit_staff_charge);
        $( 'select[name="verify_credit"]', form).change();

        $( 'select[name="verify_labor"]', form).val(resources.verify_labor);
        $( 'select[name="verify_labor_perform_stage"]', form).val(resources.verify_labor_perform_stage);
        $( 'input[name="verify_labor_staff_charge"]', form).val(resources.verify_labor_staff_charge);
        $( 'select[name="verify_labor"]', form).change();


        $( 'select[name="verify_degree"]', form).val(resources.verify_degree);
        $( 'select[name="verify_degree_perform_stage"]', form).val(resources.verify_degree_perform_stage);
        $( 'input[name="verify_degree_staff_charge"]', form).val(resources.verify_degree_staff_charge);
        $( 'select[name="verify_degree"]', form).change();
        

        $( 'select[name="verify_degree_person"]', form).val(resources.verify_degree_person);
        $( 'select[name="verify_degree_person_perform_stage"]', form).val(resources.verify_degree_person_perform_stage);
        $( 'input[name="verify_degree_person_staff_charge"]', form).val(resources.verify_degree_person_staff_charge);
        $( 'select[name="verify_degree_person"]', form).change();
      }

      $( 'select[name="gender"]', form).val(request.gender);
      $( 'input[name="minimum_age"]', form).val(request.minimum_age);
      $( 'input[name="maximum_age"]', form).val(request.maximum_age);

      //Data informatica
      const computingApplicacions = request.computing_applicacions;
      const templateComputingApplicacions = $( "#tpl-add-application" ).html();
      $( "#table-applications tbody tr" ).empty();

      for (let row in computingApplicacions) {
        const dataComputing = computingApplicacions[row];
        const counterComputing = $( ".tbl-section-computing" ).data('counterComputing') - 1;

        $( ".tbl-section-computing" ).data('counterComputing', counterComputing);

        const rowComputing = Mustache.render(templateComputingApplicacions, {
          index: counterComputing,
          name: dataComputing.name,
        });  
        $( "#table-applications tbody" ).prepend(rowComputing);

        $( `#computing-level-${counterComputing}` ).val(dataComputing.level);
      }

      //Data Idiomas
      const languages = request.languages;
      const templateLanguages = $( "#tpl-add-languages" ).html();
      $( "#table-languages tbody tr" ).empty();

      for (let row in languages) {

        const dataLanguage = languages[row];
        const counterLanguages = $( "#table-languages" ).data('counterLanguages') - 1;    
        $( "#table-languages" ).data('counterLanguages', counterLanguages);

        const rowLanguages = Mustache.render(templateLanguages, {
          index: counterLanguages,
          name: dataLanguage.language_name
        });  
        $( "#table-languages tbody" ).prepend(rowLanguages);

        $( `#languages-reading-level-${counterLanguages}`).val(dataLanguage.reading_level);
        $( `#languages-speaking-level-${counterLanguages}` ).val(dataLanguage.speaking_level);
        $ ( `#languages-writing-level-${counterLanguages}` ).val(dataLanguage.writing_level);
      }

      //Data funciones
      const functions = request.job_functions;
      const templateFunctions = $( "#tpl-add-functions" ).html();
      $( "#table-functions tbody tr" ).empty();

      for (let row in functions) {
        const dataFunction = functions[row];
        var rowFunction = Mustache.render(templateFunctions, {
          value: dataFunction.function
        });  
        $( "#table-functions tbody" ).prepend(rowFunction); 
      }

      //Data competencias
      const competenceAdditional = request.additional_competences;
      const templateCompetence = $( "#tpl-add-additional-competence" ).html();
      $( "#table-competences tbody .row-additional-competence" ).empty();

      for (row in competenceAdditional) {
        const dataCompetence = competenceAdditional[row];
        const rowCompetence = Mustache.render(templateCompetence, {
          value: dataCompetence.competence_name
        });  
        $( "#table-competences tbody" ).prepend(rowCompetence); 
      }

      $( 'textarea[name="additional_comments"]' ).val(request.additional_comments);

    }, 'json')
    .fail(function() {
      alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
    }).always(function(){
      jQuery.ajaxSetup({async: true});
      $( '#modal-sr-clone-loading' ).modal('hide');
    }); 
  } 
  
  $( "#location" ).select2();
  $( "#selector-replace-employee" ).select2();
  $( "#job-profile" ).select2();
  $( '#job_layout' ).select2();

  addRowApplication();
  addRowLanguages();
  confirmLeave();

  <?php if (empty($this->input->get('clone'))) { ?>
    getConsultants();
  <?php } ?>

  <?php if (!empty($this->input->get('clone'))) { ?>
    const cloneRequestID = "<?php echo $this->input->get('clone'); ?>";
    cloneSearchRequest(cloneRequestID);
  <?php } ?>

  showStep(1);

 $( ".step-inputs" ).each(function(){
    $(this).prepend('<div class="info-required"><span>*</span> (Campos obligatorios) </div>');
    $( "#form-confirm .info-required" ).remove();
 });
});
</script>