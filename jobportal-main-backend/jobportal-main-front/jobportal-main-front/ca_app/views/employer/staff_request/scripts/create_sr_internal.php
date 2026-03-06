<script type="text/javascript">
$(function() {

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
    var url = "<?php echo site_url('employer/staff_request/staff_requests/remove_attached_file'); ?>";
    var data = {
      file_path: filePath
    };

    $.post(url, data, function(response){
      var success = response.success;
      if (success) {
        var linkFile = '<a href="#" target="_blank" class="btn-attach-file" >Cargar archivo</a>';
        item.html(linkFile);
      }
    }, 'json').fail(function(){
      alert("Error al remover el archivo");
    });
  }

  $(document).off('click', '.btn-attach-file');
  $(document).on('click', '.btn-attach-file', function(){
    showSelectorFiles();
  });
  
  $(document).off('click', '.item-remove').on('click', '.item-remove', function(){
    var item = $(this).closest('.attach-file-item');
    var filePath = $(this).data('file-path');
    removeFile(filePath, item);
    $(this).remove();
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

  function validateDataStaffRequest() {

      var totalSteps = $( ".content-step" ).length;
      var currentStep = $( ".content-step:visible").index() + 1;

      $( "#prev-step-request" ).prop('disabled', true);
      $( "#next-step-request" ).prop('disabled', true);
      $( "#create-request" ).prop('disabled', true);

      if (currentStep < totalSteps) {
        validateStep(currentStep);
      } else {
        window.onbeforeunload = null;
        $( "#form-create-request" ).get(0).submit();
      }
  }

  function showErrorsStep(errors) {
    
    $.each(errors,function(fieldName, message_error) {

      if (fieldName == "field_working_hours") {
        $( ".field_working_hours" ).html('<p class="info-error"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;&nbsp;' + message_error + '</p>')
        $( ".field_working_hours" ).addClass("has-error");
      } else {
        if (fieldName == "dni_employee_replace") {
          fieldName = "search_employee_replace";
        }

        var input = $( "*[name='" + fieldName + "']" );
        var wrapperInput = input.closest(".input-group ");

        if (!wrapperInput.length) {
          wrapperInput = input.closest(".form-group ");
        }
        
        wrapperInput.addClass("has-error");
        wrapperInput.before('<p class="info-error"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;&nbsp;' + message_error + '</p>');
      }
    });
  }

  function validateStep(step) {
    
    $( ".has-error" ).removeClass("has-error");
    $( ".info-error" ).remove();
    
    var stepInputs = $( ".content-step" ).eq(step - 1).find(":input");    
    var data = stepInputs.serialize() + "&_step=" + step;
    var url = "<?php echo base_url('employer/staff_request/create_internal_staff_request/validate_step'); ?>";

    stepInputs.prop('disabled', true);    

    $.post(url, data, function(data) {
      var successfulValidation = data.status_validation;
    
      if (successfulValidation) {
          showStep(step + 1);
      } else {
        showErrorsStep(data.message_errors);
      }
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

  function cleanErrorReplaceEmployee() {
    var contentDiv = $( "#selector-replace-employee" ).closest(".input-group");
    contentDiv.siblings(".info-error").remove();
    contentDiv.removeClass("has-error");
  }

  function getConsultants()
  {
    var url = "<?php echo site_url('general/overall_web_services/get_consultants'); ?>";
    $( "#consultant" ).html('<option value="">Cargando...</option>').prop('disabled', true);

    $.post(url, {}, function(data) {
      var consultants =  data.MESSAGE == 'OK' ? data.CONSULTORA : [];
      setDataConsultants(consultants);   
    }, 'json')
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
  
    $.post(url, data, function(data) {
      var clients =  data.MESSAGE == 'OK' ? data.CLIENTE : [];
      setDataClients(clients);
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
        no_cia: $( "#consultant option:selected" ).data('no_cia'),
        uni_neg: $( "#business_unit option:selected" ).data('uni_neg'),
        cod_clie: $( "#client_company option:selected" ).data('cod_clie'),
    }

    var url = "<?php echo site_url('general/overall_web_services/get_cost_centers'); ?>";
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

  function getAuthoritiesDR()
  {
    var data  = {
        business_unit_name : $( "#business_unit option:selected" ).data('uni_neg'),
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
    validateDataStaffRequest();
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

  $( "#select_mof" ).change(function(){

    if ($(this).val() == '') {
      return;
    }

    var text = $(this).find("option:selected").text();
    $( "#job_title" ).text(text);

    var url = "<?php echo site_url('employer/mofs/mofs/get_mof_info'); ?>";
    var data = {
      'id': $(this).val()
    };

    $.get(url, data, function(response){

      var mof = response.mof;

      $( '#minimum_salary' ).val(mof.basic_minimum);
      $( '#maximum_salary' ).val(mof.basic_maximum);
      $( '#monthly_gross_salary' ).val(mof.basic_maximum);

      var benefits = mof.benefits;

      $( "#table-additional-benefits tbody tr" ).remove();

      for (var i = 0; i < benefits.length; i++) {
        var benefit = (benefits[i]);
        addRowBenefit(benefit);
      }

    }, 'json');

  });

  $( "#reason_request" ).change(function() {
    var reasonRequestVal = $(this).val();
    
    if (reasonRequestVal == 'replacement' || 
        reasonRequestVal == 'vacations'  || 
        reasonRequestVal == 'license') {
      $( "#content-replace-employee" ).show();
      $( "#content-main-attach-file").show();
    } else {
      cleanErrorReplaceEmployee();
      $( "#content-replace-employee" ).hide();
      $( "#content-main-attach-file").hide();
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

  $( "#consultant" ).change(function() {
    $( "#business_unit" ).val("");
  });

  $( "#client_company" ).change(function() {
    getCostCenters();
    getJobLayouts();
  });

  $( "#business_unit" ).change(function() {
    getClientsCompany();
    getAuthoritiesDR();
  });

  $( "#add-working-hours" ).click(function(e) {
    e.preventDefault();
    addWorkingHours();
  });

  $(document).on("click", ".remove-working-hours", function(e) {
    e.preventDefault();
    $(this).closest('.row-working-hours').remove();
  });

  $( '#request_template' ).change(function(){
    $( '#select_mof' ).closest('.input-group').removeClass("has-error").hide();
    $( '#select_mof' ).closest('.input-group').prev('.info-error').remove();

    $( '#job_layout' ).closest('.input-group').removeClass("has-error").hide();
    $( '#job_layout' ).closest('.input-group').prev('.info-error').remove();

    if ($(this).val() == '1') {
      $( '#select_mof' )
        .closest('.input-group')
        .show();
      
      $( '#select_mof' ).change();
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
    $( "#job_title" ).text(text);

    const url = "<?php echo site_url('employer/job_layouts/job_layouts/get_job_layout_info'); ?>";
    const data = {
      'id': $(this).val()
    };

    $.get(url, data, function(response){

      const jobLayoutData = response.data;

      $( '#minimum_salary' ).val(jobLayoutData.basic_minimum);
      $( '#maximum_salary' ).val(jobLayoutData.basic_maximum);
      $( '#monthly_gross_salary' ).val(jobLayoutData.basic_maximum);

      const benefits = jobLayoutData.benefits;

      $( "#table-additional-benefits tbody tr" ).remove();

      for (let i = 0; i < benefits.length; i++) {
        const benefit = (benefits[i]);
        addRowBenefit(benefit);
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

      const consultants = request.list_consultants ?? [];
      const clients = request.list_clients ?? [];
      const costCenters = request.list_cost_centers ?? [];
      
      setDataConsultants(consultants);
      setDataClients(clients);
      setDataCostCenters(costCenters);

      $( 'select[name="type_requirement"]', form).val(request.type_requirement);
      $( 'select[name="type_expense"]', form).val(request.type_expense);

      $( 'select[name="type_expense"]', form).val(request.type_expense);
      $( 'select[name="consultant_name"]', form).val(request.no_cia + '|' + request.consultant_name);
      $( 'select[name="business_unit_name"]', form).val(request.cod_business_unit + '|' + request.business_unit_name);
      $( 'select[name="business_unit_name"]', form).change();
      $( 'select[name="client_company_name"]', form).val(request.cod_clie + '|' + request.client_company_name);
      $( 'select[name="cost_center"]', form).val(request.cost_center);
      $( 'input[name="cost_center_client"]', form).val(request.cost_center_client);

      $( 'select[name="internal_area"]' ).val(request.belonging_area_ID);
      $( 'select[name="internal_area"]' ).change();

      getJobLayouts();
      
      if (request.mof_ID) {
        $( 'select[name="request_template"]', form).val('1');
        $( 'select[name="select_mof"]', form).val(request.mof_ID);
      }

      if (request.job_layout_id) {
        $( 'select[name="request_template"]', form).val('2');
        $( 'select[name="job_layout"]', form).val(request.job_layout_id);
      }

      $( 'select[name="request_template"]', form).change();
      $( 'input[name="user_management"]', form).val(request.user_management);
      $( 'input[name="applicant_headquarter"]', form).val(request.applicant_headquarter);

      //Contratacion 
      $( 'select[name="renovable"]', form).val(request.renovable);
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
      $( 'input[name="observations"]', form).val(request.observations);
    
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

      $( 'textarea[name="additional_comments"]' ).val(request.additional_comments);

    }, 'json')
    .fail(function() {
      alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
    }).always(function(){
      jQuery.ajaxSetup({async: true});
      $( '#modal-sr-clone-loading' ).modal('hide');
    }); 
  }

  $( "#select_mof" ).select2();

 $( "#location" ).select2();
 $( "#selector-replace-employee" ).select2();
 $( '#job_layout' ).select2();

 confirmLeave();
 showStep(1);

 <?php if (empty($this->input->get('clone'))) { ?>
    getConsultants();
  <?php } ?>

  <?php if (!empty($this->input->get('clone'))) { ?>
    const cloneRequestID = "<?php echo $this->input->get('clone'); ?>";
    cloneSearchRequest(cloneRequestID);
  <?php } ?>

 $( ".step-inputs" ).each(function(){
    $(this).prepend('<div class="info-required"><span>*</span> (Campos obligatorios) </div>');
    $( "#form-confirm .info-required" ).remove();
 });
});
</script>