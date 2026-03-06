
<script type="text/javascript">
  
  $(function(){
    
    function is_valid_responsibilities() { 
      error = false;
      inputs = $( 'textarea[name="responsibilities[]"]');

      if (inputs.length == 0)  {
        toastr["error"]('¡Debe ingresar al menos 1 responsabilidad!');
        return false;
      }

      inputs.each(function(e, i){
        if ($.trim($(i).val()) == '') {
          error = true;
        }
      });

      if (error) {
        toastr["error"]('¡Las responsabilidades agregadas no deben quedar vacías!');
      }

      return !error;
    }

    function is_valid_grade() {
        grade_error = false;

        $( ".grade" ).each(function(){
            if ($.trim($(this).val()) == '') {
            grade_error = true;
            }
        });

        return !grade_error;
    }

    function is_valid_resources() {
      error = false;

      $( '.js-resource-error' ).remove();

      $( '.js-resource-input' ).each(function(e, i){
    
        if ($.trim($(i).val()) == '') {
          $(i).closest('tr').find('td:eq(1)').prepend('<span class="js-resource-error">Seleccione recurso</span>');
          error = true;
        }
      });

      if (error) {
        toastr["error"]('¡SECCIÓN RECURSOS: hay datos sin seleccionar!');
        return !error;
      }


      $( '.js-resource-type-expense' ).each(function(e, i){

        value = $(i).closest('tr').find('.js-resource-input').val();

        if (value != null && value != 0 && value != '' && value != 'No aplica' && value[0] != 0 && $.trim($(i).val()) == '') {
          error = true;
          $(i).closest('tr').find('td:eq(1)').prepend('<span class="js-resource-error">Seleccione Tipo de egreso</span>');
        }
      });

      if (error) {
        toastr["error"]('¡SECCIÓN RECURSOS: debe seleccionar un tipo de egreso!');
        return !error;
      }

      $( '.js-resource-stage' ).each(function(e, i){

        value = $(i).closest('tr').find('.js-resource-input').val();

        if (value != null && value != 0 && value != '' && value != 'No aplica' && value[0] != 0 && $.trim($(i).val()) == '') {
          error = true;
          $(i).closest('tr').find('td:eq(2)').prepend('<span class="js-resource-error">Seleccione etapa</span>');
        }
      });

      if (error) {
        toastr["error"]('¡SECCIÓN RECURSOS: debe seleccionar la etapa a realizar!');
        return !error;
      }

      return true;
    }

    function is_valid_factors() { 
        error = false;
        $("*[name='factor[]']").each(function(e, i){

            if ($.trim($(i).val()) == '') {
            error = true;
            }
        });

        return !error;
    }

    function getConsultants()
    {
      var url = "<?php echo site_url('admin/job_profiles/get_consultants'); ?>";
      $( "#consultant" ).html('<option value="">Cargando...</option>').prop('disabled', true);
        
      data = {
        company_id: "<?php echo $company->ID; ?>"
      };

      $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        async:false,
        success: function(data) {
          var consultants =  data.MESSAGE == 'OK' ? data.CONSULTORA : [];      
          $.each(consultants, function(i, row) {
            var consultantValue = row.NO_CIA + '|' + row.CONSULTORA;
            $( "#consultant" ).append('<option data-no_cia="' + row.NO_CIA + '" value="' + consultantValue + '">' + row.CONSULTORA + '</option>');
          });
        } 
      })
      .fail(function() {
        alert('¡Ha ocurrido un error al tratar de listar las consultoras!');
      }).always(function() {
        $( "#consultant" ).find("option:eq(0)").text("Seleccione");
        $( "#consultant" ).prop('disabled', false);
      }); 
    }

    function getClientsCompany()
    {
      var url = "<?php echo site_url('admin/job_profiles/get_clients_company'); ?>";
      var data = {
        company_id: "<?php echo $company->ID; ?>",
        no_cia: $( "#consultant option:selected" ).data('no_cia'),
        uni_neg: $( "#business-unit option:selected" ).data('uni_neg')
      }

      $( "#client-company" ).html('<option value="">Cargando...</option>').prop('disabled', true);
      
      $.ajax({
        type: 'POST',
        data: data,
        url: url,
        dataType: 'json',
        async:false,
        success: function(data){
          var clients =  data.MESSAGE == 'OK' ? data.CLIENTE : [];
          
          $.each(clients, function(i, row) {
            var clientValue = row.COD_CLIE + '|' + row.CLIENTE;
            $( "#client-company" ).append(
              '<option data-cod_clie="' + row.COD_CLIE + '" value="' + clientValue + '">' + row.CLIENTE + '</option>'
              );
          });
        }
      })
      .fail(function() {
        alert('¡Ha ocurrido un error al tratar de listar las empresas clientes!');
      }).always(function() {
        $( "#client-company" ).find("option:eq(0)").text("Seleccione");
        $( "#client-company" ).prop('disabled', false);
      }); 
    }

    function getCostCenters()
    {
      var data  = {
          company_id: "<?php echo $company->ID; ?>",
          no_cia: $( "#consultant option:selected" ).data('no_cia'),
          uni_neg: $( "#business-unit option:selected" ).data('uni_neg'),
          cod_clie: $( "#client-company option:selected" ).data('cod_clie'),
      }

      var url = "<?php echo site_url('admin/job_profiles/get_cost_centers'); ?>";
     
      $( "#cost-center" ).html('<option value="">Cargando...</option>').prop('disabled', true); 

      $.ajax({
        type: 'POST',
        data: data,
        url: url,
        dataType: 'json',
        async:false,
        success: function(data){
          var cost_centers = data.MESSAGE == 'OK' ? data.CENTROCOSTO : [];

          $.each(cost_centers, function(i, row) {
            $( "#cost-center" ).append('<option value="' + row.COD_CCOSTO + '">' + row.COD_CCOSTO + '</option>');
          });    
        }
      })
      .fail(function() {
        alert('¡Ha ocurrido un error al tratar de listar los centro de costo!');
      }).always(function() {
        $( "#cost-center" ).find("option:eq(0)").text("Seleccione");
        $( "#cost-center" ).prop('disabled', false);
      });      
    }

    function updateCounter() {
       $( "#table-responsibilities tbody tr" ).each(function(){
        var counter = $(this).find("td:eq(0) span");
        counter.text($(this).index() + 1);
      });
    }

    function addSkill() {
      var val = $.trim($( "#input-skill" ).val());
      
      if (val == '') {
        return;
      }

      var template = $( "#tpl-add-skill" ).html();
      skill_html = Mustache.render(template, {skill: val});
      $( "#wrapper-skills" ).prepend(skill_html);
      $( "#input-skill" ).val("");
    }

    $( "#form-profile-edit" ).submit(function(e){

      if (!is_valid_responsibilities()) {
        return false;
      }

      if (!is_valid_resources()) {
        return false;
      }

      if (!is_valid_grade()) {
        toastr["error"]('¡SECCIÓN DISCAPACIDADES: Todos los grados deben ser seleccionados!');
        return false;
      }

      if (!is_valid_factors()) {
        toastr["error"]('¡SECCIÓN VALORIZACIÓN DEL PUESTO: Factores no pueden quedar vacios!');
        return false;
      }

      $( "#submit_button" ).prop('disabled', true);
    });

    $( "#occupational_group" ).change(function(){
      var jobChargeId = $(this).val();

      var url = "<?php echo site_url('admin/job_profiles/get_skills/'); ?>" + jobChargeId;

      $( "#wrapper-fixed-skills" ).empty();
      
      $.post(url, {}, function(response) {

        var skills = response.skills;

        for (skill in skills) {
          $( "#wrapper-fixed-skills" ).append(
            `<span>
              ${skills[skill]}
            </span>`
          );
        }
      }, 'json');
    });

    $( "form" ).keypress(function(e){ 
      if(e.which  == 13){
          
          return e.target.id != 'input-skill';
      }
    });

    $( "#btn-add-responsibility" ).click(function(){
      var template = $( "#tpl-add-responsibility" ).html();
      row = Mustache.render(template);
      $( "#table-responsibilities tbody" ).prepend(row);
      updateCounter();
    });

    $( "#input-skill" ).keyup(function(e) {
      var val = $.trim($(this).val());

      if (e.keyCode == 13 && val != '') {
        addSkill();
      }

      e.preventDefault();
      return false;
    });

    $(document).on("click", ".btn-remove-item", function(){
      $(this).closest("tr").remove();
      updateCounter();
    });

    $( "#btn-add-skill" ).click(function(){
      addSkill();
    });

    $( ".text-resposibility" ).each(function () {
      this.style.height = '0px';
      this.style.height = (this.scrollHeight + 5) + 'px';
    });

    $(document).on("input", ".text-resposibility", function(){
      this.style.height = '0px';
      this.style.height = (this.scrollHeight + 5) + 'px';
    });

    $( "#exam_type_covid" ).change(function(){
      var arr = $(this).val();
      var index = arr.indexOf('0');
      if (index > -1 && arr.length > 1) {

        arr.splice(index, 1);
        $(this).val(['No aplica']);
        $(this).select2({
          closeOnSelect: false
        });
      }
    });

    $( "#consultant" ).change(function() {
      $( "#business-unit" ).val("");
    });

    $( "#client-company" ).change(function() {
      getCostCenters()
    });

    $( "#business-unit" ).change(function() {
      getClientsCompany();
    });

    $( "#type_emo" ).change(function(){

      var emo = $(this).val();

      if (emo == 'PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE') {
        $( "#emo-detail" ).show();
        $( "#protocol-detail" ).prop('required', true);
      } else {
        $( "#emo-detail" ).hide();
        $( "#protocol-detail" ).prop('required', false);
      }
    });

    $( ".factor-manual" ).change(function(){
      var tr = $(this).closest('tr');
      
      var desc = '-';
      var grade = '-';
      var score = '-';

      if ($(this).val() != '') {
        desc = $(this).find('option:selected').data('factor-desc');
        grade = $(this).find('option:selected').data('factor-grade');
        score = $(this).find('option:selected').data('factor-score');
      }

      tr.find('td:eq(1)').html(desc);
      tr.find('td:eq(2)').html(grade);
      tr.find('td:eq(3)').html(score);
    });

    $( ".factor-automatic" ).change(function(){
      var desc = '-';
      var grade = '-';
      var score = '-';
      var id = '';

      var factorTypeId = $(this).data('factor-type-id');
      var factorContent = $('#tbl-factor-type-' + factorTypeId);

      if ($(this).val() != '') {
        var id = 0;
        var desc = $(this).find('option:selected').data('factor-desc');
        var grade = $(this).find('option:selected').data('factor-grade');
        var score = $(this).find('option:selected').data('factor-score');  
      }

      var tr = factorContent.find('tr:eq(1)');

      tr.find('td:eq(0)').find('.factor-value').html(desc);
      tr.find('td:eq(1)').html(grade);
      tr.find('td:eq(2)').html(score);
      tr.find('input[name="factor[]"]').val(id);
    });

    $('.number-format' ).each(function(index, input){
      input.oninvalid = function(e) {
          if (!e.target.validity.valid && e.target.validity.patternMismatch) {
            e.target.setCustomValidity("El formato de ingreso es icorrecto, máximo 8 números y 2 decimales. Ej: 12534388.32");
          }
      };
      input.oninput = function(e) {
          e.target.setCustomValidity("");
      };
    });

    $( '.number-format' ).bind('keyup blur',function(){ 
      $(this).val( $(this).val().replace(/[^0-9\.]/g, '') ); 
    });

    $("#basic_maximum, #basic_minimum").keyup(function(){

      var basicMinimum = parseFloat($( "#basic_minimum" ).val());
      var basicMaximum = parseFloat($( "#basic_maximum" ).val());

      var diff = basicMaximum - basicMinimum;

      $( "#factor_differentiating" ).removeAttr('required');
      $( "#factor_differentiating" ).closest('.form-group').find('span').html("");

      if (diff >= 50) {
        $( "#factor_differentiating" ).closest('.form-group').find('span').html("*");
        $( "#factor_differentiating" ).prop('required');
      }        
    });

    $( "#factor_differentiating" ).change(function(e){
      $( "#content_factor_differentiating_other" ).hide();
      $( "#factor_differentiating_other" ).removeAttr('required');

      if ($(this).val() == 'Otro') {
        $( "#factor_differentiating_other" ).prop('required', true);
        $( "#content_factor_differentiating_other" ).show();
      }
    });

    $( "#occupational_category" ).change(function(){
      $( "#occupational_category_level" ).text($(this).find('option:selected').data('level'));
    });
  });
</script>

<script type="text/javascript">
    $(function (){

        tippy('.grade', {
            trigger: 'change',
            onTrigger(instance, event) {

                if (event.target.selectedIndex == 0) {
                    instance.disabled(); 
                    return;
                }
                            
                grade_info = $(event.target).find('option:selected').data('grade-info');
                instance.setProps({
                    content: grade_info,
                    allowHTML: true,
                });
            },
        });

        $( "#type_screening" ).select2({
            closeOnSelect: false
        });

        $( "#type_emo" ).select2({
            closeOnSelect: false
        });

        $( "#exam_type_covid" ).select2({
            closeOnSelect: false
        });

        $( "#exams-complementary" ).select2({
            closeOnSelect: false
        });

        if ($.trim($( '.grade:eq(0)' ).val()) != '') {
            $( 'input[name="check_validate_disability"]').prop('checked', true);
        }

        if ($.trim($( 'input[name="factor[]"]:eq(0)' ).val()) != '') {
            $( 'input[name="check_validate_factor"]').prop('checked', true);
        }

        $( "#type_emo" ).change();
        $( ".factor-automatic" ).change();
        $( "#factor_differentiating" ).change();
    });
</script>


