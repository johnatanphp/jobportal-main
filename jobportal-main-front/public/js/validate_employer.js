//JS
var err = 0;
//Employer signup validation
$(document).ready(function(){
	
 //Email address
 $("#emp_form #email").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('email', 'email', 'email_err', closest_div, 'yes', '', '','', 'yes');	
 });
 
 //Password
 $("#emp_form #pass").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('pass', 'contraseña', 'pass_err', closest_div, 'no', '8', '','');	
 });
 
 //Confirm Password
 $("#emp_form #confirm_pass").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('confirm_pass', 'confirmar contraseña', 'conf_pass_err', closest_div, 'no', '', 'pass','');	
 });
 
 //RUC company
 $("#emp_form #company_ruc").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_ruc', 'RUC de la empresa', 'company_ruc_err', closest_div, 'no', '', '','');	
 });

 //Full Name
 $("#emp_form #full_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('full_name', 'nombre completo', 'full_name_err', closest_div, 'no', '', '','');	
 });

 $('#emp_form #full_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
);
 
 //Address
 $("#emp_form #current_address").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('current_address', 'dirección', 'address_err', closest_div, 'no', '', '','');
 });
 
 $("#emp_form #city_text").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_text', 'ciudad', 'city_err', closest_div, 'no', '', '','');
 });
 
 $('#emp_form #city_text').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
);

$('#emp_form').bind('keyup blur',function(){ 
    if($('.ui-autocomplete-input').val()!=''){
		$( '.city_err').remove(); 
	}
});

 //Mobile
 $("#emp_form #mobile_phone").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('mobile_phone', 'teléfono móvil', 'mob_err', closest_div, 'no', '', '','');
 });
/* $('#emp_form #mobile_phone').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^0-9\s]/g,'') ); }
);*/

 //Company Name
 $("#emp_form #company_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_name', 'nombre de la empresa', 'company_err', closest_div, 'no', '', '','');
 });
 
 $("#emp_form #industry_id").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('industry_id', 'área', 'industry_err', closest_div, 'no', '', '','');
 });
  
 $("#emp_form #company_location").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_location', 'lugar de la empresa', 'company_location_err', closest_div, 'no', '', '','');
 });
  
 $("#emp_form #company_description").blur(function(){
	 closest_div = $( this ).closest('div');
	 check_bad_words($("#company_description").val(), bad_words, 'company_description');
	 universal_validation('company_description', 'acerca de la empresa', 'company_description_err', closest_div, 'no', '', '','');
	 
 });
 
  $("#emp_form #company_description").blur(function(){
	 check_bad_words($("#company_description").val(), bad_words, 'company_description');	 
 });
  
  /*
 $("#emp_form #company_phone").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_phone', 'teléfono de la empresa', 'company_phone_err', closest_div, 'no', '', '','');
 });
 */


 /*$('#emp_form #company_phone').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^0-9\s]/g,'') ); }
);*/

 $("#emp_form #company_website").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_website', 'sitio web de la empresa', 'company_website_err', closest_div, 'no', '', '','');
 });
 
 //Logo Uploding validation
 $("#emp_form #company_logo").bind('change blur',function(){
	closest_div = $( this ).closest('div');
	universal_validation('document', 'logo de la empresa', 'company_logo_err', closest_div, 'no', '', '','company_logo');
 });
 
});

function validate_employer_form(theForm){
	return_val = true;
	if(theForm.email.value==''){
		 closest_div = $( '#email' ).closest('div');
		 error_msg('email_err',closest_div,'Por favor ingrese su dirección de correo.');
		theForm.email.focus();
		return_val = false;
	}
	
	var filter = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if(filter.test(theForm.email.value)===false){
		 closest_div = $( '#email' ).closest('div');
		 error_msg('email_err',closest_div,'Por favor ingrese su dirección de correo electrónico válida.');
		theForm.email.focus();
		return_val = false;
	}
	
	if(theForm.pass.value==''){
		closest_div = $( '#pass' ).closest('div');
		error_msg('pass_err',closest_div,'Por favor ingrese la contraseña.');
		theForm.pass.focus();
		return_val = false;
	}

	if (!isPasswordStrength($( '#pass' ).val())) {
		closest_div = $( '#pass' ).closest('div');
		error_msg('pass_err',closest_div,'Contraseña debe tener minúculas, mayúculas, números y caracteres especiales.');
		theForm.pass.focus();
		return_val = false;
	}
	
	if(theForm.confirm_pass.value==''){
		closest_div = $( '#confirm_pass' ).closest('div');
		error_msg('conf_pass_err',closest_div,'Por favor, confirme su contraseña.');
		theForm.confirm_pass.focus();
		return_val = false;
	}
	
	if(theForm.confirm_pass.value!=theForm.pass.value){
		closest_div = $( '#confirm_pass' ).closest('div');
		error_msg('conf_pass_err',closest_div,'La contraseña no coincide.');
		theForm.confirm_pass.focus();
		return_val = false;
	}
	
	if(theForm.full_name.value==''){
		closest_div = $( '#full_name' ).closest('div');
		error_msg('full_name_err',closest_div,'Por favor ingresa tu nombre completo.');
		theForm.full_name.focus();
		return_val = false;
	}
	
	if(theForm.mobile_phone.value==''){
		closest_div = $( '#mobile_phone' ).closest('div');
		error_msg('mob_err',closest_div,'Por favor ingresa tu número de celular.');
		theForm.mobile_phone.focus();
		return_val = false;
	}
	
	if(theForm.company_name.value==''){
		closest_div = $( '#company_name' ).closest('div');
		error_msg('company_name_err',closest_div,'Por favor proporcione el nombre de la empresa.');
		theForm.company_name.focus();
		return_val = false;
	}

	if(theForm.industry_id.value==''){
		closest_div = $( '#industry_id' ).closest('div');
		error_msg('industry_id_err',closest_div,'Por favor ingresa el área de trabajo de la empresa.');
		theForm.industry_id.focus();
		return_val = false;
	}

	if(theForm.company_location.value==''){
		closest_div = $( '#company_location' ).closest('div');
		error_msg('company_location_err',closest_div,'Por favor ingresa algo acerca de la empresa.');
		theForm.company_location.focus();
		return_val = false;
	}
	
	/*
	if(theForm.company_phone.value==''){
		closest_div = $( '#company_phone' ).closest('div');
		error_msg('company_phone_err',closest_div,'Por favor proporcione el teléfono de la empresa.');
		theForm.company_phone.focus();
		return_val = false;
	}
	*/
	
	if(theForm.company_website.value==''){
		closest_div = $( '#company_website' ).closest('div');
		error_msg('company_website_err',closest_div,'Por favor proporcione el sitio web de la empresa.');
		theForm.company_website.focus();
		return_val = false;
	}
	
	if(theForm.company_description.value==''){
		closest_div = $( '#company_description' ).closest('div');
		error_msg('company_description_err',closest_div,'Por favor proporcione una descripción de la empresa.');
		theForm.company_description.focus();
		return_val = false;
	}	
	
	var ext = $('#company_logo').val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
		closest_div = $( '#company_logo' ).closest('div');
		closest_div.addClass( "has-error" ); 
		closest_div.append( error_wrapper('company_logo_err', 'Proporcione un logo válido de la empresa.') );
		theForm.company_logo.focus();
		return_val = false;
	}
	
	check_bad_words($("#company_description").val(), bad_words, 'company_description');
	
	return return_val;
}

//Employer edit company validation
$(document).ready(function(){
 
  //RUC company
 $("#emp_comp_form #company_ruc").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_ruc', 'RUC de la empresa', 'company_ruc_err', closest_div, 'no', '', '','');	
 });
  
 $("#emp_comp_form #city_text").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_text', 'ciudad', 'city_err', closest_div, 'no', '', '','');
 });
 
 $('#emp_comp_form #city_text').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
);

$('#emp_comp_form').bind('keyup blur',function(){ 
    if($('.ui-autocomplete-input').val()!=''){
		$( '.city_err').remove(); 
	}
});

 //Company Name
 $("#emp_comp_form #company_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_name', 'nombre de la empresa', 'company_err', closest_div, 'no', '', '','');
 });
 
 $("#emp_comp_form #industry_id").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('industry_id', 'área de la empresa', 'industry_err', closest_div, 'no', '', '','');
 });
  
 $("#emp_comp_form #company_location").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_location', 'lugar de la empresa', 'company_location_err', closest_div, 'no', '', '','');
 });
  
 $("#emp_comp_form #company_description").blur(function(){
	 closest_div = $( this ).closest('div');
	 check_bad_words($("#company_description").val(), bad_words, 'company_description');
	 universal_validation('company_description', 'acerca de la empresa', 'company_description_err', closest_div, 'no', '', '','');
	 
 });
 
  $("#emp_comp_form #company_description").blur(function(){
	 check_bad_words($("#company_description").val(), bad_words, 'company_description');	 
 });
  
 $("#emp_comp_form #company_phone").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_phone', 'teléfono de la empresa', 'company_phone_err', closest_div, 'no', '', '','');
 });

 $("#emp_comp_form #company_website").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('company_website', 'sitio web de la empresa', 'company_website_err', closest_div, 'no', '', '','');
 });
  
});

function validate_employer_company_form(theForm){
	
	if(is_empty($("#company_ruc"), 'company_ruc', 'RUC de la empresa')) return false;
	if(is_empty($("#company_name"), 'company_name', 'Nombre de la empresa')) return false;
	if(is_empty($("#industry_id"), 'industry_id', 'Área de la empresa')) return false;	
	if(is_empty($("#company_location"), 'company_location', 'Dirección de la empresa')) return false;	
	if(is_empty($("#country"), 'country', 'País de la empresa')) return false;	
	
	if($("#city_text").val()==''){
		closest_div = $( '#city_text' ).closest('div');
		$(".city_err").remove();
		error_msg('city_err',closest_div,'Por favor ingresa la ciudad de la empresa');
		$("#city_text").focus();
		return false;
	}
	
	if(is_empty($("#company_phone"), 'company_phone', 'Teléfono de la empresa')) return false;
	
	if(is_empty($("#company_website"), 'company_website', 'Sitio web de la empresa')) return false;	
	if(is_empty($("#company_description"), 'company_description', 'Descrición de la empresa')) return false;	
	check_bad_words($("#company_description").val(), bad_words, 'company_description');
	return true;
}

//Employer edit employer personal profile validation
$(document).ready(function(){

 //Full Name
 $("#emp_personal_form #full_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('full_name', 'nombre completo', 'full_name_err', closest_div, 'no', '', '','');	
 });
 $('#emp_personal_form #full_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
);

 //DOB Day
 $("#emp_personal_form #dob_day").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_day', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 //DOB Month
 $("#emp_personal_form #dob_month").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_month', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 //DOB Year
 $("#emp_personal_form #dob_year").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_year', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 $("#emp_personal_form #city_text").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_text', 'ciudad', 'city_err', closest_div, 'no', '', '','');
 });
 
 $('#emp_personal_form #city_text').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
);

 //Mobile
 $("#emp_personal_form #mobile_phone").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('mobile_phone', 'número de teléfono móvil', 'mob_err', closest_div, 'no', '', '','');
 });
 /*$('#emp_personal_form #mobile_phone').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^0-9\s]/g,'') ); }
);*/

});

function validate_employer_personal_form(theForm){
	
	if(is_empty($("#full_name"), 'full_name', 'nombre completo')) return false;
	if(is_empty($("#dob_day"), 'dob_day', 'fecha de nacimiento')) return false;	
	if(is_empty($("#dob_day"), 'dob_month', 'fecha de nacimiento')) return false;	
	if(is_empty($("#dob_day"), 'dob_year', 'fecha de nacimiento')) return false;	
	if(is_empty($("#city_text"), 'city_text', 'ciudad')) return false;	
	if(is_empty($("#mobile_phone"), 'mobile_phone', 'número de teléfono móvil')) return false;
		
	return true;
}

//Starts Edit Employer Summary
$(document).ready(function(){
	
  $("#summary_submit").click(function(){	
	  if(is_empty($("#content"), 'content', 'resumen')) return false;
	  if(is_empty($("#cid"), 'cid', 'ID')) return false;
	  edit_company_summary();
  });
  
  
  $("#frm_employer_desc #content").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('content', 'resumen', 'content_err', closest_div, 'no', '', '','');	
 });
 $('#frm_employer_desc  #content').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/(<([^>]+)>)/ig,'') ); }
 );
 
 $("#frm_employer_desc #content").blur(function(){
 	check_bad_words($("#company_description").val(), bad_words, 'company_description');
 });
});
//Ends Employer summary

//Starts Post New Job Employer 
$(document).ready(function(){

 $("#post_job_form #industry_id").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('industry_id', 'área del empleo', 'industry_id_err', closest_div, 'no', '', '','');	
 });
 
 $("#post_job_form #job_title").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('job_title', 'título del empleo', 'job_title_err', closest_div, 'no', '', '','');	
 });
 
 $("#post_job_form #vacancies").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('vacancies', 'N°. de vacantes', 'vacancies_err', closest_div, 'no', '', '','');	
 });
 
 $('#post_job_form #vacancies').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^0-9\s]/g,'') ); }
);

 $("#post_job_form #last_date").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('last_date', 'fecha de finalización', 'last_date_err', closest_div, 'no', '', '','');	
 });
 
 $("#post_job_form #city_dropdown").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_dropdown', 'ciudad', 'city_dropdown_err', closest_div, 'no', '', '','');	
 });

 $("#post_job_form #city_text").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_text', 'ciudad', 'city_text_err', closest_div, 'no', '', '','');	
 });

 $("#post_job_form #editor1").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('editor1', 'descripción del empleo', 'editor1_err', closest_div, 'no', '', '','');	
 });
 
 $("#post_job_form #editor1").blur(function(){
	 check_bad_words(CKEDITOR.instances['editor1'].getData(), bad_words, 'editor1');
 });

$( "#post_job_form #min_pay").bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^0-9\.,]/g,'') );
});

$( "#post_job_form #max_pay").bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^0-9\.,]/g,'') );
});


});
function validate_new_post_job_form(theForm){
	
	if(is_empty($("#industry_id"), 'industry_id', 'área')) return false;
	if(is_empty($("#job_title"), 'job_title', 'título del empleo')) return false;	
	if(is_empty($("#vacancies"), 'vacancies', 'n° de vacantes')) return false;	
	if(is_empty($("#last_date"), 'last_date', 'fecha de finalización del empleo')) return false;	
	
	if ($( "#city_text" ).is(':visible')) {
		if(is_empty($( "#city_text" ), 'city_text', 'ciudad')) return false;
	} else {
		if(is_empty($( "#city_dropdown" ), 'city_dropdown', 'ciudad')) return false;	
	}

	if(CKEDITOR.instances['editor1'].getData()==''){
		closest_div = $("#editor1").closest('div');
		 error_msg('editor1_err',closest_div,'Por favor proporcione la descripción del empleo.');
		$("#editor1").focus();
		return false;
	  }
	
	if(is_empty($("#s_val"), 'skill', 'habilidad requerida')) return false;

	check_bad_words(CKEDITOR.instances['editor1'].getData(), bad_words, 'editor1');

	if ($( "#has-questions" ).is(':checked')) {
		$( "#error-has-questions").remove();

		if ($( ".form-question" ).length == 0) {
			$("<div id='error-has-questions' class='msg-error-questions'>Por favor ingrese al menos 1 pregunta</div>")
			.insertBefore($( "#has-questions" ).closest('.row'))
			.hide()
			.fadeIn(500);

			return false;
		}

		if (!validateQuestionForms()) return false;
	}
	
	return true;
}

function validateQuestionForms()
{
	var is_valid = true;

	$( ".form-question" ).each(function(i, formQuestion) {

		$( ".msg-error-questions", formQuestion).remove();
		$( ".error", formQuestion).removeClass("error");

		var errors = $('<div class="msg-error-questions"></div>');

		var question = $( ".question", formQuestion);
		var typeQuestion = $( ".type-question", formQuestion);

		var questionVal = $.trim(question.val());
		var typeQuestionVal = $.trim(typeQuestion.val());

		if (questionVal == '') {
			question.addClass("error");
			errors.append("<p>Por favor ingrese una pregunta</p>");
			is_valid = false;
		}

		if (typeQuestionVal == '') {
			typeQuestion.addClass("error");	
			errors.append("<p>Por favor seleccione el tipo de pregunta</p>");
			is_valid = false;
		}

		if (/^(checkbox|multiple_choice|dropdown)$/.test(typeQuestionVal)) {

			var options = $( ".simple-option", formQuestion);	
			
		    if (options.length == 0) {
		    	errors.append("<p>Por favor ingrese al menos 1 opción</p>");
		    	is_valid = false;
		    }

		    var inputsEmpty = options.filter(function(a, b) {
		    	return $.trim(b.value) == '';
		    });

		    if (inputsEmpty.length > 0) {
				inputsEmpty.addClass('error');
				errors.append("<p class=''>No deje ninguna opción en blanco en esta pregunta</p>");
		   		is_valid = false;
		   }
		} 

		if(/^(multiple_choice_grid)$/.test(typeQuestionVal)) {

			var rowOptions = $( ".row-option", formQuestion);
			var colOptions = $( ".col-option", formQuestion);

			if (rowOptions.length == 0) {
				errors.append('<p>Por favor ingrese al menos 1 fila en esta pregunta</p>');
				is_valid = false;
			}

			if (colOptions.length == 0) {
				errors.append('<p>Por favor ingrese al menos 1 columna en esta pregunta</p>');
				is_valid = false;
			}

			var rowsEmpty = rowOptions.filter(function(i, option){
				return $.trim(option.value)  == '';
			});

			if (rowsEmpty.length > 0) {
				rowsEmpty.addClass('error');
				errors.append('<p>No deje ninguna fila en blanco</p>');
				is_valid = false;
			}

			var colsEmpty = colOptions.filter(function(i, option){				
				return $.trim(option.value)  == '';
			});

			if (colsEmpty.length > 0) {
				colsEmpty.addClass('error');
				errors.append('<p>No deje ninguna columna en blanco</p>');
				is_valid = false;
			}
		}

		if (errors.children().length > 0) {
			$(formQuestion).prepend(errors).hide().fadeIn(500);
		} else {
			errors.remove();
		}
	});

	return is_valid;
}

//Employer delete logo Starts
$(document).ready(function(){
	
  $("#remove_logo").click(function(){	
	  var resp = confirm("¿Seguro que quieres eliminar el logotipo de la empresa?");
	  if(resp==true){
		del_logo();  
	  }
  });
});

//Add Skills Jobseeker
$(document).ready(function(){
	
  $("#js_skill_add").click(function(){	
	  if(is_empty($("#skill"), 'skill', 'habilidad')) return false;
	  add_job_skill();
  });
  
   $("#skill").bind('keyup blur', function(){
	 $( this ).closest('div').removeClass( "has-error" ); 
	 $(".skill_err").remove();
 });
 
 //Remove
 $(document.body).on('click','#myskills li a',function(){	
	 $( this ).closest('li').remove();
 });
 
});
