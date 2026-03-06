
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

  function is_valid_factors() { 
      error = false;
      $("*[name='factor[]']").each(function(e, i){

          if ($.trim($(i).val()) == '') {
          error = true;
          }
      });

      return !error;
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

    if (!is_valid_factors()) {
      toastr["error"]('¡SECCIÓN VALORIZACIÓN DEL PUESTO: Factores no pueden quedar vacios!');
      return false;
    }

    $( "#submit_button" ).prop('disabled', true);
  });

  $( "#occupational_group" ).change(function(){
    var jobChargeId = $(this).val();

    var url = "<?php echo site_url('admin/job_layouts/get_skills/'); ?>" + jobChargeId;

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
  if ($.trim($( 'input[name="factor[]"]:eq(0)' ).val()) != '') {
      $( 'input[name="check_validate_factor"]').prop('checked', true);
  }

  $( ".factor-automatic" ).change();
  $( "#factor_differentiating" ).change();
});
</script>


