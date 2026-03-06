<script type="text/javascript">
  
  $(function(){

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

    $( "#form-mof-edit" ).submit(function(e){

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

    function addIndicator() {

      var template = $( "#tpl-add-indicators" ).html();
      var index = $( "#table-indicators" ).generateSequence();
      row = Mustache.render(template, {index: index});
      $( "#table-indicators tbody" ).prepend(row);
    }

    $( "form" ).keypress(function(e){ 
      if(e.which  == 13){
          
          return e.target.id != 'input-skill';
      }
    });

    $( "#occupational_group" ).change(function(){
      var jobChargeId = $(this).val();

      var url = "<?php echo site_url('admin/mofs/get_skills/'); ?>" + jobChargeId;

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

    $( "#btn-add-indicator" ).click(function(){
      addIndicator();
    }); 
    
    $( ".text-resposibility" ).each(function () {
      this.style.height = '0px';
      this.style.height = (this.scrollHeight + 5) + 'px';
    });

    $(document).on("input", ".text-resposibility", function(){
      this.style.height = '0px';
      this.style.height = (this.scrollHeight + 5) + 'px';
    });

    $( "#belonging_areas" ).select2({
       closeOnSelect: false
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

    $( "#exams-complementary" ).select2({
      closeOnSelect: false
    });

    $( ".validate-input" ).change(function(){

      var value = $(this).val();
    
      if (!value || value == '' || value == 0 || value == '0' || value == 'No aplica') {
        $(this).closest('tr').find('.input-perform-on-stage').prop('disabled', true);
        $(this).closest('tr').find('.input-perform-on-stage').attr({'required':false});
      } else {
        $(this).closest('tr').find('.input-perform-on-stage').prop('disabled', false);
        $(this).closest('tr').find('.input-perform-on-stage').attr({'required':true});
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
        id = 0;
        desc = $(this).find('option:selected').data('factor-desc');
        grade = $(this).find('option:selected').data('factor-grade');
        score = $(this).find('option:selected').data('factor-score');
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
  
    $( "#type_emo" ).change();
    $( ".factor-automatic" ).change();
    $( "#factor_differentiating" ).change();
  });
</script>