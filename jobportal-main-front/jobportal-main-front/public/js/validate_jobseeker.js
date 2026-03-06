//JS
var err = 0;
//Jobseeker Signup validation Starts
$(document).ready(function(){
	
 //Email address
 $("#seeker_form #email").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('email', 'email', 'email_err', closest_div, 'yes', '', '','');	
 });
 
 //Password
 $("#seeker_form #pass").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('pass', 'contraseña', 'pass_err', closest_div, 'no', '8', '','');	
 });
 
 //Confirm Password
//  $("#seeker_form #confirm_pass").blur(function(){
// 	 closest_div = $( this ).closest('div');
// 	 universal_validation('confirm_pass', 'confirmar contraseña', 'conf_pass_err', closest_div, 'no', '', 'pass','');	
//  });

//Document type
$("#seeker_form #document_type").blur(function(){
	closest_div = $( this ).closest('div');
	universal_validation('document_type', 'Tipo de documento', 'document_type_err', closest_div, 'no', '', '','');	
});

//Document number
$("#seeker_form #document_number").blur(function(){
 closest_div = $( this ).closest('div');
 universal_validation('document_number', 'Nro documento', 'document_number_err', closest_div, 'no', '', '','');	
});

//Document number validation
$('#seeker_form #document_number').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^0-9a-zA-Z]/g, '') ); 
	}
);

 //Full Name
 $("#seeker_form #full_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('full_name', 'nombre(s)', 'full_name_err', closest_div, 'no', '', '','');	
 });

  //Paternal last Name
 $("#seeker_form #paternal_last_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('paternal_last_name', 'Apellido paterno', 'paternal_last_name_err', closest_div, 'no', '', '','');	
 });

  //Maternal last Name
 $("#seeker_form #maternal_last_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('maternal_last_name', 'Apellido materno', 'maternal_last_name_err', closest_div, 'no', '', '','');	
 });

 $('#seeker_form #full_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ\s]/g,'') ); }
);

$( '#seeker_form #paternal_last_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ\s]/g,'') ); }
);

$( '#seeker_form #maternal_last_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ\s]/g,'') ); }
);

 //Gender
 $("#seeker_form #gender").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('gender', 'sexo', 'gender_err', closest_div, 'no', '', '','');
 });

 //DOB Day
 $("#seeker_form #dob_day").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_day', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 //DOB Month
 $("#seeker_form #dob_month").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_month', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 //DOB Year
 $("#seeker_form #dob_year").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_year', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
  //Civil Status
 $("#seeker_form #civil_status").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('civil_status', 'estado civil', 'civil_status_err', closest_div, 'no', '', '','');
 });

 //Departamento
 $("#seeker_form #department").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('department', 'departamento', 'department_err', closest_div, 'no', '', '','');
 });

 //Provincia
 $("#seeker_form #provinces").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('provinces', 'provincia', 'provinces_err', closest_div, 'no', '', '','');
 });

  //Distrito
 $("#seeker_form #districts").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('districts', 'distrito', 'districts_err', closest_div, 'no', '', '','');
 });

$("#seeker_form #city_text").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_text', 'ubicación', 'city_err', closest_div, 'no', '', '','');
 });
 
 //Mobile
 $("#seeker_form #mobile_number").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('mobile_number', 'teléfono móvil', 'mob_err', closest_div, 'no', '', '','');
 });

//Fecha de emisión del documento de identidad
/*
$("#seeker_form #document_date_issue").blur(function(){
	closest_div = $( this ).closest('div');
	universal_validation('document_date_issue', 'Fecha de emisión del documento de identidad', 'document_date_issue_err', closest_div, 'no', '', '','');
});
*/
//Url carnet de vacunación
/*
$("#seeker_form #vaccination_card_covid19_url").blur(function(){
	closest_div = $( this ).closest('div');
	universal_validation('vaccination_card_covid19_url', 'Url carnet vacunación COVID-19', 'vaccination_card_covid19_url_err', closest_div, 'no', '', '','');
});
*/

 //CV Uploding validation
 $("#seeker_form #cv_file").bind('change blur',function(){
 	if ($(this).val() != "") {
		closest_div = $( this ).closest('div');
		universal_validation('document', 'tu currículum', 'cv_err', closest_div, 'no', '', '','cv_file');
 	}
 });

 $('#seeker_form #accept_term').change(function() { 
 	$( ".accept_term_err" ).remove();
 });


 $('#seeker_form #accept_privacy_policy').change(function() { 
   
 	$( ".accept_privacy_policy_err" ).remove();
 });
});

function validate_form(theForm) {	

	if (theForm.email.value=='') {
		 closest_div = $( '#email' ).closest('div');
		 error_msg('email_err',closest_div,'Por favor ingrese su dirección de correo.');
		theForm.email.focus();
		return false;
	}
	
	if (theForm.pass.value=='') {
		closest_div = $( '#pass' ).closest('div');
		error_msg('pass_err',closest_div,'Por favor ingrese la contraseña.');
		theForm.pass.focus();
		return false;
	}

	if ($.trim(theForm.pass.value)=='estreLLA7583!!') {
		closest_div = $( '#pass' ).closest('div');
		error_msg('pass_err',closest_div,'Debe cambiar la contraseña estreLLA7583!!, esta contraseña solo es un ejemplo del patrón a utilizar');
		theForm.pass.focus();
		return false;
	}

	if (!isPasswordStrength($( '#pass' ).val())) {
		closest_div = $( '#pass' ).closest('div');
		error_msg('pass_err',closest_div,'Contraseña debe tener minúculas, mayúculas, números y caracteres especiales.');
		theForm.pass.focus();
		return false;
	}

	// if (theForm.confirm_pass.value==''){
	// 	closest_div = $( '#confirm_pass' ).closest('div');
	// 	error_msg('conf_pass_err',closest_div,'Por favor ingrese conforma la contraseña.');
	// 	theForm.confirm_pass.focus();
	// 	return false;
	// }
	
	// if (theForm.confirm_pass.value!=theForm.pass.value){
	// 	closest_div = $( '#confirm_pass' ).closest('div');
	// 	error_msg('conf_pass_err',closest_div,'Las contraseñas no coinciden.');
	// 	theForm.confirm_pass.focus();
	// 	return false;
	// }
	
	if (theForm.full_name.value==''){
		closest_div = $( '#full_name' ).closest('div');
		error_msg('full_name_err',closest_div,'Por favor proporciona tu nombre(s).');
		theForm.full_name.focus();
		return false;
	}

	if (theForm.paternal_last_name.value == '') {
		closest_div = $( '#paternal_last_name' ).closest('div');
		error_msg('paternal_last_name_err',closest_div,'Por favor proporciona tu apellido paterno.');
		theForm.paternal_last_name.focus();
		return false;
	}

	if (theForm.maternal_last_name.value == '') {
		closest_div = $( '#maternal_last_name' ).closest('div');
		error_msg('maternal_last_name_err',closest_div,'Por favor proporciona tu apellido materno.');
		theForm.maternal_last_name.focus();
		return false;
	}

	if (theForm.document_type.value == '') {
		closest_div = $( '#document_type' ).closest('div');
		error_msg('document_type_err',closest_div,'Elija el tipo de documento.');
		theForm.document_type.focus();
		return false;
	}

	if (theForm.document_number.value == '') {
		closest_div = $( '#document_number' ).closest('div');
		error_msg('document_number_err',closest_div,'Ingrese el número de documento.');
		theForm.document_number.focus();
		return false;
	}
/*
	if (theForm.document_date_issue.value == '') {
		closest_div = $( "#document_date_issue" ).closest('div');
		$( ".document_date_issue_err" ).remove();
		error_msg('document_date_issue_err', closest_div, 'Selecciona una fecha de emisión');
		$( "#document_date_issue" ).focus();
		return false;
	}
*/
	if($( "#gender" ).val() == '') {
		closest_div = $( '#gender' ).closest('div');
		error_msg('gender_err',closest_div,'Por favor selecciona tu sexo.');
		$( "#gender" ).focus();
		return false;
	}

	if (theForm.dob_day.value=='' || theForm.dob_month.value=='' || theForm.dob_year.value==''){
		closest_div = $( '#dob_day' ).closest('div');
		error_msg('dob_err',closest_div,'Por favor proporciona tu fecha de nacimiento.');
		theForm.dob_day.focus();
		return false;
	}

	if ($( "#civil_status" ).val() == '') {
		closest_div = $( '#civil_status' ).closest('div');
		error_msg('civil_status_err',closest_div,'Por favor selecciona tu estado civil.');
		$( "#civil_status" ).focus();
		return false;
	}

	if (theForm.mobile_number.value==''){
		closest_div = $( '#mobile_number' ).closest('div');
		error_msg('mob_err',closest_div,'Por favor ingresa tu número de teléfono móvil.');
		theForm.mobile_number.focus();
		return false;
	}
/*
	if ($.trim(theForm.vaccination_card_covid19_url.value)=='') {
		closest_div = $( '#vaccination_card_covid19_url' ).closest('div');
		error_msg('vaccination_card_covid19_url_err',closest_div, 'Por favor ingresa la url del carnet de vacunación del COVID-19.');
		theForm.vaccination_card_covid19_url.focus();
		return false;
	}

	if ($.trim(theForm.vaccination_card_covid19_url.value) != '' && 
	    $.trim(theForm.vaccination_card_covid19_url.value.substring(0, 74)) != 'https://carnetvacunacion.minsa.gob.pe/#publico/certificado/certificado?Tk=') {
		closest_div = $( '#vaccination_card_covid19_url' ).closest('div');
		error_msg('vaccination_card_covid19_url_err',closest_div, 'URL ingresada no es válida');
		theForm.vaccination_card_covid19_url.focus();
		return false;
	}

	if ($.trim(theForm.vaccination_card_covid19_url.value) != '') {
		url_tk = $.trim(theForm.vaccination_card_covid19_url.value).split('Tk=');
		url_tk_value = $.trim(url_tk[1]).split('-');
		
		if (url_tk_value.length <= 1 || $.trim(url_tk_value[0]) == '') {
			closest_div = $( '#vaccination_card_covid19_url' ).closest('div');
			error_msg('vaccination_card_covid19_url_err',closest_div, 'URL ingresada no es válida');
			theForm.vaccination_card_covid19_url.focus();
			return false;
		}
	}
	*/

	if ($( "#department" ).is(':enabled') && $( "#department" ).val() == '') {
		closest_div = $( "#department" ).closest('div');
		$( ".department_err" ).remove();
		error_msg('department_err',closest_div,'Por favor elige un departamento.');
		$( "#department" ).focus();
		return false;
	}

	if ($( "#provinces" ).is(':enabled') && $( "#provinces" ).val() == '') {
		closest_div = $( "#provinces" ).closest('div');
		$( ".provinces_err" ).remove();
		error_msg('provinces_err',closest_div,'Por favor elige una provincia.');
		$( "#provinces" ).focus();
		return false;
	}

	if ($( "#districts" ).is(':enabled') && $( "#districts" ).val() == '') {
		closest_div = $( "#districts" ).closest('div');
		$( ".districts_err" ).remove();
		error_msg('districts_err',closest_div,'Por favor elige un distrito.');
		$( "#districts" ).focus();
		return false;
	}

	if ($( "#city_text" ).is(':enabled') && $( "#city_text" ).val() == '') {
		closest_div = $( "#city_text" ).closest('div');
		$( ".city_err" ).remove();
		error_msg('city_err',closest_div,'Por favor ingresa tu ubicación.');
		$( "#city_text" ).focus();
		return false;
	}

	if (theForm.accept_term && theForm.accept_term.checked !== true) {
		closest_div = $( "#accept_term" ).closest('div');
		error_msg('accept_term_err',closest_div,'Por favor acepta los terminos y condiciones.');

		return false;
	}

    if (theForm.accept_privacy_policy && theForm.accept_privacy_policy.checked !== true) {
    	closest_div = $( "#accept_privacy_policy" ).closest('div');
    	error_msg('accept_privacy_policy_err',closest_div,'Por favor acepta la política de privacidad de datos.');

		return false;
	}

	grecaptcha.execute();

	return false;
}
//Jobseeker Signup validation Ends

//Jobseeker Edit my account validations Starts
$(document).ready(function(){
	
 //Full Name
 $("#account_form #full_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('full_name', 'nombres', 'full_name_err', closest_div, 'no', '', '','');	
 });


  //Paternal last Name
 $("#account_form #paternal_last_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('paternal_last_name', 'Apellido paterno', 'paternal_last_name_err', closest_div, 'no', '', '','');	
 });

  //Maternal last Name
 $("#account_form #maternal_last_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('maternal_last_name', 'Apellido materno', 'maternal_last_name_err', closest_div, 'no', '', '','');	
 });

//Document type
$("#account_form #document_type").blur(function(){
	closest_div = $( this ).closest('div');
	universal_validation('document_type', 'Tipo de documento', 'document_type_err', closest_div, 'no', '', '','');	
});

//Document number
$("#account_form #document_number").blur(function(){
	closest_div = $( this ).closest('div');
	universal_validation('document_number', 'Número de documento', 'document_number_err', closest_div, 'no', '', '','');	
});

$('#account_form #document_number').bind('keyup blur',function(){ 
	$(this).val( $(this).val().replace(/[^0-9A-Za-z]/g, '') ); 
});

$('#account_form #full_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ\s]/g,'') ); }
);

$('#account_form #paternal_last_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ\s]/g,'') ); }
);

$('#account_form #maternal_last_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-ZÑñáéíóúÁÉÍÓÚ\s]/g,'') ); }
);

 //DOB Day
 $("#account_form #dob_day").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_day', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 //DOB Month
 $("#account_form #dob_month").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_month', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 //DOB Year
 $("#account_form #dob_year").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob_year', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 //Address
 $("#account_form #pressent_address").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('pressent_address', 'dirección', 'address_err', closest_div, 'no', '', '','');
 });
 
 //City
 $("#account_form #city_dropdown").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_dropdown', 'ubicación', 'city_err', closest_div, 'no', '', '','');
 });
 
 $("#account_form #city_text").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city_text', 'ubicación', 'city_err', closest_div, 'no', '', '','');
 });

 //Mobile
 $("#account_form #mobile").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('mobile', 'teléfono móvil', 'mob_err', closest_div, 'no', '', '','');
 });

 //Gender
 $("#account_form #gender").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('gender', 'sexo', 'gender_err', closest_div, 'no', '', '','');
 });

 //Civil Status
 $("#account_form #civil_status").change(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('civil_status', 'estado civil', 'civil_status_err', closest_div, 'no', '', '','');
 });

 //Fecha de emisión del documento de identidad
 /*
$("#account_form #document_date_issue").blur(function(){
	closest_div = $( this ).closest('div');
	universal_validation('document_date_issue', 'Fecha de emisión del documento de identidad', 'document_date_issue_err', closest_div, 'no', '', '','');
});
*/

//Url carnet de vacunación
/*
$("#account_form #vaccination_card_covid19_url").blur(function(){
	closest_div = $( this ).closest('div');
	universal_validation('vaccination_card_covid19_url', 'Url carnet vacunación COVID-19', 'vaccination_card_covid19_url_err', closest_div, 'no', '', '','');
});
*/
});

function validate_account_form(theForm) {

	if(theForm.full_name.value==''){
		closest_div = $( '#full_name' ).closest('div');
		error_msg('full_name_err',closest_div,'Por favor ingresa tu nombre completo.');
		theForm.full_name.focus();
		return false;
	}

	if (theForm.paternal_last_name.value == '') {
		closest_div = $( '#paternal_last_name' ).closest('div');
		error_msg('paternal_last_name_err',closest_div,'Por favor proporciona tu apellido paterno.');
		theForm.paternal_last_name.focus();
		return false;
	}

	if (theForm.maternal_last_name.value == '') {
		closest_div = $( '#maternal_last_name' ).closest('div');
		error_msg('maternal_last_name_err',closest_div,'Por favor proporciona tu apellido materno.');
		theForm.maternal_last_name.focus();
		return false;
	}

	if (theForm.document_type.value == '') {
		closest_div = $( '#document_type' ).closest('div');
		error_msg('document_type_err',closest_div,'Elija el tipo de documento.');
		theForm.document_type.focus();
		return false;
	}

	if (theForm.document_number.value == '') {
		closest_div = $( '#document_number' ).closest('div');
		error_msg('document_number_err',closest_div,'Ingrese el número de documento.');
		theForm.document_number.focus();
		return false;
	}
/*
	if (theForm.document_date_issue.value == '') {
		closest_div = $( "#document_date_issue" ).closest('div');
		$( ".document_date_issue_err" ).remove();
		error_msg('document_date_issue_err', closest_div, 'Selecciona una fecha de emisión');
		$( "#document_date_issue" ).focus();
		return false;
	}
*/	
	if($( "#gender" ).val() == '') {
		closest_div = $( '#gender' ).closest('div');
		error_msg('gender_err',closest_div,'Por favor selecciona tu sexo.');
		$( "#gender" ).focus();
		return false;
	}

	if(theForm.dob_day.value=='' || theForm.dob_month.value=='' || theForm.dob_year.value==''){
		closest_div = $( '#dob_day' ).closest('div');
		error_msg('dob_err',closest_div,'Por favor proporciona tu fecha de nacimiento.');
		theForm.dob_day.focus();
		return false;
	}

	if($( "#civil_status" ).val() == '') {
		closest_div = $( '#civil_status' ).closest('div');
		error_msg('civil_status_err',closest_div,'Por favor selecciona tu estado civil.');
		$( "#civil_status" ).focus();
		return false;
	}

	if(theForm.mobile.value==''){
		closest_div = $( '#mobile' ).closest('div');
		error_msg('mob_err',closest_div,'Por favor ingresa tu número de teléfono móvil.');
		theForm.mobile.focus();
		return false;
	}
/*
	if ($.trim(theForm.vaccination_card_covid19_url.value)=='') {
		closest_div = $( '#vaccination_card_covid19_url' ).closest('div');
		error_msg('vaccination_card_covid19_url_err',closest_div, 'Por favor ingresa la url del carnet de vacunación del COVID-19.');
		theForm.vaccination_card_covid19_url.focus();
		return false;
	}

	if ($.trim(theForm.vaccination_card_covid19_url.value) != '' && 
		$.trim(theForm.vaccination_card_covid19_url.value.substring(0, 74)) != 'https://carnetvacunacion.minsa.gob.pe/#publico/certificado/certificado?Tk=') {
		closest_div = $( '#vaccination_card_covid19_url' ).closest('div');
		error_msg('vaccination_card_covid19_url_err',closest_div, 'URL ingresada no es válida');
		theForm.vaccination_card_covid19_url.focus();
		return false;
	}

	if ($.trim(theForm.vaccination_card_covid19_url.value) != '') {
		url_tk = $.trim(theForm.vaccination_card_covid19_url.value).split('Tk=');
		url_tk_value = $.trim(url_tk[1]).split('-');
		
		if (url_tk_value.length <= 1 || $.trim(url_tk_value[0]) == '') {
			closest_div = $( '#vaccination_card_covid19_url' ).closest('div');
			error_msg('vaccination_card_covid19_url_err',closest_div, 'URL ingresada no es válida');
			theForm.vaccination_card_covid19_url.focus();
			return false;
		}
	}
	*/

	if ($( "#city_dropdown" ).is(':enabled') && $( "#city_dropdown" ).val() == '') {
		closest_div = $( "#city_dropdown" ).closest('div');
		$( ".city_err" ).remove();
		error_msg('city_err',closest_div,'Por favor ingresa tu ubicación.');
		$( "#city_dropdown" ).focus();
		return false;
	}

	if ($( "#city_text" ).is(':enabled') && $( "#city_text" ).val() == '') {
		closest_div = $( "#city_text" ).closest('div');
		$( ".city_err" ).remove();
		error_msg('city_err',closest_div,'Por favor ingresa tu ubicación.');
		$( "#city_text" ).focus();
		return false;
	}

	if(theForm.present_address.value==''){
		closest_div = $( '#present_address' ).closest('div');
		error_msg('address_err',closest_div,'Por favor ingresa tu dirección.');
		theForm.present_address.focus();
		return false;
	}

	return true;
}
//Jobseeker Edit my account validations Ends

//Jobseeker edit profile from cv builder Starts
function validate_cv_builder_form(theForm){

	if(theForm.full_name.value==''){
		closest_div = $( '#full_name' ).closest('div');
		error_msg('full_name_err',closest_div,'Por favor ingresa tu nombre.');
		theForm.full_name.focus();
		return false;
	}

	if(theForm.city_dropdown.value=='' && theForm.city_text.value==''){
		closest_div = $( '#city_dropdown' ).closest('div');
		$(".city_err").remove();
		error_msg('city_err',closest_div,'Por favor ingresa tu ubicación.');
		theForm.city_dropdown.focus();
		return false;
	}
	
	if(theForm.mobile.value==''){
		closest_div = $( '#mobile' ).closest('div');
		error_msg('mob_err',closest_div,'Por favor ingresa tu número de teléfono móvil.');
		theForm.mobile.focus();
		return false;
	}

	return true;
}
//Jobseeker edit profile from cv builder Ends

//Jobseeker Edit profile validations Starts
$(document).ready(function(){
	
  $("#jobseeker_profile_submitter").click(function(){	
	  if(is_empty($("#full_name"), 'full_name', 'nombre')) return false;
	  if(is_empty($("#mobile"), 'mobile', 'teléfono móvil')) return false;
	  if(is_empty($("#country"), 'country', 'país')) return false;
	  if(is_empty($("#city"), 'city', 'ubicación')) return false;
	  edit_jobseeker_profile();
  });
  
  
  $("#frm_edit_profile #full_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('full_name', 'nombre', 'full_name_err', closest_div, 'no', '', '','');	
 });
 $('#frm_edit_profile  #full_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
 );
 
 $("#frm_edit_profile #mobile").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('mobile', 'teléfono móvil', 'mobile_err', closest_div, 'no', '', '','');
 });

 $("#frm_edit_profile #dob").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('dob', 'fecha de nacimiento', 'dob_err', closest_div, 'no', '', '','');
 });
 
 $("#frm_edit_profile #present_address").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('present_address', 'dirección', 'present_address_err', closest_div, 'no', '', '','');
 });
 
 $("#frm_edit_profile #country").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('country', 'país', 'country_err', closest_div, 'no', '', '','');
 });
 
 $("#frm_edit_profile #city").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('city', 'ubicación', 'city_err', closest_div, 'no', '', '','');
 });
 
 $('#frm_edit_profile  #full_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
 );
 
});

//Jobseeker Edit profile validations Ends

//Starts Edit Jobseeker Summary
$(document).ready(function(){
	
  $("#summary_submit").click(function(){	
	  if(is_empty($("#content"), 'content', 'resumen profesional')) return false;
	  edit_jobseeker_summary();
  });
  
  
  $("#frm_seeker_summary #content").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('content', 'resumen profesional', 'content_err', closest_div, 'no', '', '','');	
 });
 $('#frm_seeker_summary  #content').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/(<([^>]+)>)/ig,'') ); }
 );

});
//Ends Jobseeker summary


//Jobseeker Add Education Starts
$(document).ready(function(){

	$("#js_education_submitter").click(function(){	
		
		if(is_empty($("#degree_title"), 'degree_title', 'grado')) return false;
		if(is_empty($("#major_subject"), 'major_subject', 'carrera')) return false;
		if(is_empty($("#institute"), 'institute', 'institución')) return false;
		if(is_empty($("#edu_country"), 'edu_country', 'país')) return false;
		if(is_empty($("#month_start_date"), 'month_start_date', 'mes de la fecha de inicio')) return false;
		if(is_empty($("#year_start_date"), 'year_start_date', 'año de la fecha de inicio')) return false;
		
		if (!$( "#studying" ).is(':checked')) {
			if (is_empty($( "#month_end_date" ), 'month_end_date', 'mes de la fecha de finalización') ||
				is_empty($( "#year_end_date" ), 'exp_completion_year', 'año de la fecha de finalización')) {
				return false;
			}
		}	

		add_js_edu();
	});  
});
//Jobseeker Add Education Ends


//Jobseeker Edit Education Starts
$(document).ready(function(){
	
	$("#js_edit_edu_submit").click(function(){	

		if(is_empty($("#ed_degree_title"), 'ed_degree_title', 'grado')) return false;
		if(is_empty($("#ed_major_subject"), 'ed_major_subject', 'carrera')) return false;
		if(is_empty($("#ed_institute"), 'ed_institute', 'institución')) return false;
		if(is_empty($("#ed_edu_country"), 'ed_edu_country', 'país')) return false;
		if(is_empty($("#ed_start_month"), 'month_start_date', 'mes de la fecha de inicio')) return false;
		if(is_empty($("#ed_start_year"), 'year_start_date', 'año de la fecha de inicio')) return false;

		if (!$( "#ed_studying" ).is(':checked')) {
			if (is_empty($( "#ed_completion_month" ), 'month_end_date', 'mes de la fecha de finalización') ||
				is_empty($( "#ed_completion_year" ), 'year_end_date', 'año de la fecha de finalización')) {
				return false;
			}
		}

		edit_js_edu();
	});
});
//Jobseeker Edit Education Ends


//Jobseeker Add Experience Starts
$(document).ready(function(){
	
	$("#js_exp_submit").click(function(){	

		if(is_empty($("#company_name"), 'company_name', 'empresa')) return false;
		if(is_empty($("#exp_industry"), 'exp_industry', 'industria')) return false;
		if(is_empty($("#job_title"), 'job_title', 'Puesto')) return false;
		if(is_empty($("#exp_job_level"), 'exp_job_level', 'nivel del puesto')) return false;
		if(is_empty($("#exp_area"), 'exp_area', 'Área')) return false; 
		if(is_empty($("#exp_country"), 'exp_country', 'País')) return false;
		if(is_empty($("#exp_start_month"), 'exp_start_month', 'mes de la fecha de inicio')) return false;
		if(is_empty($("#exp_start_year"), 'exp_start_year', 'año de la fecha de inicio')) return false;

		if (!$( "#exp_working" ).is(':checked')) {
			if (is_empty($( "#exp_completion_month" ), 'exp_completion_month', 'fecha de fin') ||
	     		is_empty($( "#exp_completion_year" ), 'exp_completion_year', 'fecha de fin')) {
				return false;
			}
		}
		
		add_js_exp();
	});

	$("#frm_add_exp #company_name").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('company_name', 'empresa', 'company_name_err', closest_div, 'no', '', '','');
	});

	$("#frm_add_exp #exp_industry").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('exp_industry', 'industria', 'exp_industry_err', closest_div, 'no', '', '','');
	});

	$("#frm_add_exp #job_title").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('job_title', 'puesto', 'job_title_err', closest_div, 'no', '', '','');	
	});

	$("#frm_add_exp #exp_job_level").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('exp_job_level', 'nivel del puesto', 'exp_job_level_err', closest_div, 'no', '', '','');
	});

	$("#frm_add_exp #exp_area").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('exp_area', 'área', 'exp_area_err', closest_div, 'no', '', '','');
	});

	$("#frm_add_exp #exp_country").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('exp_country', 'país', 'exp_country_err', closest_div, 'no', '', '','');
	});

	$("#frm_add_exp #exp_start_month").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('exp_start_month', 'mes de la fecha de inicio', 'exp_start_month_err', closest_div, 'no', '', '','');
	});

	$("#frm_add_exp #exp_start_year").blur(function(){
		closest_div = $( this ).closest('div');
		universal_validation('exp_start_year', 'año de la fecha de inicio', 'exp_start_year_err', closest_div, 'no', '', '','');
	});

	$("#frm_add_exp #exp_completion_month").blur(function(){

		if (!$( "#exp_working" ).is(':checked')) {
			closest_div = $( this ).closest('div');
			universal_validation('exp_completion_month', 'mes de la fecha fin', 'exp_completion_month_err', closest_div, 'no', '', '','');
		}
	});

	$("#frm_add_exp #exp_completion_year").blur(function(){

		if (!$( "#exp_working" ).is(':checked')) {
			closest_div = $( this ).closest('div');
			universal_validation('exp_completion_year', 'año de la facha fin', 'exp_completion_year_err', closest_div, 'no', '', '','');
		}
	});
})
//Jobseeker Add Experience Ends

//Jobseeker Edit Experience Starts
$(document).ready(function(){
	
	$("#js_edit_exp_submit").click(function(){	
		
		if(is_empty($("#ed_company_name"), 'ed_company_name', 'empresa')) return false;
		if(is_empty($("#ed_exp_industry"), 'ed_exp_industry', 'industria')) return false;
		if(is_empty($("#ed_job_title"), 'ed_job_title', 'Puesto')) return false;
		if(is_empty($("#ed_exp_job_level"), 'ed_exp_job_level', 'nivel del puesto')) return false;
		if(is_empty($("#ed_exp_area"), 'ed_exp_area', 'área')) return false; 
		if(is_empty($("#ed_exp_country"), 'ed_exp_country', 'país')) return false;
		if(is_empty($("#ed_exp_start_month"), 'ed_exp_start_month', 'mes de la fecha de inicio')) return false;
		if(is_empty($("#ed_exp_start_year"), 'ed_exp_start_year', 'año de la fecha de inicio')) return false;

		if (!$( "#ed_exp_working" ).is(':checked')) {
			if (is_empty($( "#ed_exp_completion_month" ), 'ed_exp_completion_month', 'fecha de fin') ||
				is_empty($( "#ed_exp_completion_year" ), 'ed_exp_completion_year', 'fecha de fin')) {
				return false;
			}
		}

		edit_js_exp();
	});
});
//Jobseeker Edit Experience Ends

//Jobseeker delete photo Starts
$(document).ready(function(){
	
  $("#remove_pic").click(function(){	
	  var resp = confirm("¿Seguro que quieres eliminar tu foto?");
	  if(resp==true){
		del_photo();  
	  }
  });
  
  
  $("#frm_seeker_summary #content").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('content', 'summary', 'content_err', closest_div, 'no', '', '','');	
 });
 $('#frm_seeker_summary  #content').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/(<([^>]+)>)/ig,'') ); }
 );

});

//Jobseeker Add Other studies
$(document).ready(function(){

	$( "#add_other_study_submit" ).click(function(){	

		if(is_empty($("#otst_name_study"), 'study_name', 'nombre del estudio')) return false;
		if(is_empty($("#otst_type_study"), 'type_study', 'tipo de estudio')) return false;
		if(is_empty($("#otst_institute"), 'institute', 'Institución')) return false;
		if(is_empty($("#otst_country"), 'country', 'país')) return false;
		if(is_empty($("#otst_start_month"), 'start_month', 'mes de la fecha de inicio')) return false;
		if(is_empty($("#otst_start_year"), 'start_year', 'año de la fecha de inicio')) return false;
		
		if (!$( "#otst_studying" ).is(':checked')) {

			if(is_empty($("#otst_completion_month"), 'completion_month', 'mes de la fecha de finalización')) return false;
			if(is_empty($("#otst_completion_year"), 'completion_year', 'año de la fecha de finalización')) return false;
		}

		add_other_studies();
	});

});
//Jobseeker Add Other studies

//Jobseeker Edit Other studies
$(document).ready(function(){

	$( "#edit_other_study_submit" ).click(function(){	

		if(is_empty($("#ed_otst_study_name"), 'study_name', 'nombre del estudio')) return false;
		if(is_empty($("#ed_otst_type_study"), 'type_study', 'tipo de estudio')) return false;
		if(is_empty($("#ed_otst_institute"), 'institute', 'Institución')) return false;
		if(is_empty($("#ed_otst_country"), 'country', 'país')) return false;
		if(is_empty($("#ed_otst_start_month"), 'start_month', 'mes de la fecha de inicio')) return false;
		if(is_empty($("#ed_otst_start_year"), 'start_year', 'año de la fecha de inicio')) return false;

		if (!$( "#ed_otst_studying" ).is(':checked')) {

			if(is_empty($("#ed_otst_completion_month"), 'completion_month', 'mes de la fecha de finalización')) return false;
			if(is_empty($("#ed_otst_completion_year"), 'completion_year', 'año de la fecha de finalización')) return false;
		}
		
		edit_other_studies();
	});

});
//Jobseeker Add Other studies


$(function() {
	if($( "#start_date" ).length > 0) {
	$( "#start_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	
	$( "#end_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	
	$( "#ed_start_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	
	$( "#ed_end_date" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	}
  });
  
  $(".fa-upload").click(function(){
	  $("#upload_pic").click();
  });

  $("#upload_pic").change(function(){
	  ext_array = ['png','jpg','jpeg','gif'];	
	  var ext = $('#upload_pic').val().split('.').pop().toLowerCase();
	  if($.inArray(ext, ext_array) == -1) {
		  alert('¡Archivo inválido!');
		  return false;
	  }
	 this.form.submit();
  });
//Jobseeker delete photo ends


//Jobseeker upload cv
$(".upload_cv").click(function(){
	  $("#upload_resume").click();
  });

  $("#upload_resume").change(function(){
	  ext_array = ['doc','docx','pdf','rtf','png','jpg','jpeg'];	
	  var ext = $('#upload_resume').val().split('.').pop().toLowerCase();
	  if($.inArray(ext, ext_array) == -1) {
		  alert('¡Archivo inválido!');
		  return false;
	  }
	 this.form.submit();
  });
  
//Jobseeker Edit additional info validations Starts
$(document).ready(function(){
 
	$("#additional_form #interest").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('interest', 'intereses', 'interest_err', closest_div, 'no', '', '','');
	});

	$("#additional_form #description").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('description', 'objetivos', 'description_err', closest_div, 'no', '', '','');
	});

	$('#additional_form  #salary_min').bind('keyup blur',function(){
		$(this).val( $(this).val().replace(/[^0-9\.]/g, '') );
	}); 

	$('#additional_form  #salary_max').bind('keyup blur',function(){
		$(this).val( $(this).val().replace(/[^0-9\.]/g, '') ); 
	}); 
});

function validate_additional_form(theForm){
	if(is_empty($("#interest"), 'interest', 'intereses')) return false;
	if(is_empty($("#description"), 'description', 'objetivos')) return false;
	
	return true;
}

//Jobseeker Edit additional info validations Ends
