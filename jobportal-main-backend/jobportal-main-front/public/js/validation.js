//=============== Functions ==================

function universal_validation(
	field_id, 
	field_text, 
	error_id, 
	closest_div, 
	email_validation, 
	text_length, 
	match_with, 
	file_upload_id, 
	required_email_business
	) {	
	var required_email_business = required_email_business || 'no';

	var filter = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var field_value = $.trim($("#"+field_id).val());
	$( '.'+error_id).remove();  
	
	if(file_upload_id==''){
	 if(field_value==''){
		closest_div.addClass( "has-error" ); 

		if (!(/^exp_start_month|exp_start_year|exp_completion_month|exp_completion_year|ed_exp_start_month|ed_exp_start_year|ed_exp_completion_month|ed_exp_completion_year$/.test(field_id))) {
			closest_div.append( error_wrapper(error_id, 'Por favor ingrese el campo ' + field_text+'.') ); 
		}
		err=1; 
		return false;
	 }
	}
	
	 if(text_length!=''){
	   if(field_value.length<text_length){
		  closest_div.addClass( "has-error" ); 
		  
		  closest_div.append( error_wrapper(error_id, 'El campo ' + field_text + ' tiene que tener mínimo '+text_length+' caracteres de longitud.') ); 

		  err=1; 
		  return false;
	   }
	 }
	
	 if(match_with!=''){
	   if(field_value!=$.trim($("#"+match_with).val())){
		  closest_div.addClass( "has-error" ); 
		  closest_div.append( error_wrapper(error_id, field_text + ' no coincide.') ); 
		  err=1; 
		  return false;
	   }
	 }
	 
	 if(file_upload_id!=''){
		 ext_array = ['doc','docx','pdf','txt','rtf','png','jpg','jpeg'];
		 if(file_upload_id=='company_logo') {
			ext_array = ['png','jpg','jpeg'];
		 }

		 if (file_upload_id == 'cv_file') {
		 	ext_array = ['doc', 'docx', 'pdf'];
		 }
		 
		var ext = $('#'+file_upload_id).val().split('.').pop().toLowerCase();
		if($.inArray(ext, ext_array) == -1) {
			closest_div.addClass( "has-error" ); 
			closest_div.append( error_wrapper(error_id, ' archivo proporcionado inválido!') );
			err=1; 
			return false;
		}
	 }
	 
	 if(email_validation=='yes'){
	   if(filter.test(field_value)===false){
		  closest_div.addClass( "has-error" ); 
		  closest_div.append( error_wrapper(error_id, ' Por favor, introduce una dirección de correo electrónico válida.') );
		  err=1; 
		  return false;
	   }
	 }

	if (required_email_business == 'yes') {
		
        free_emails = [
            'gmail',
            'hotmail',
            'outlook',
            'yahoo',
            'icloud'
        ];

		email_parts = field_value.split('@');
		domain_parts = (email_parts[1]).split('.');

		if (free_emails.indexOf(domain_parts[0]) != -1) {  
			closest_div.addClass( "has-error" ); 
			closest_div.append( error_wrapper(error_id, ' Email ingresado debe ser empresarial.') );
			err = 1; 
			return false;
		}
	 }

	 if (field_id == 'pass') {

		if (!isPasswordStrength($( "#" + field_id).val())) {
			closest_div.addClass( "has-error" ); 
			closest_div.append(error_wrapper(error_id, ' Contraseña debe tener minúculas, mayúculas, números y caracteres especiales.') );
			err=1; 
			return false;
		}
	 }

	if (field_id == 'vaccination_card_covid19_url') {
		if ($.trim($( "#" + field_id).val()) != '' && 
			$.trim($( "#" + field_id).val().substring(0, 74)) != 'https://carnetvacunacion.minsa.gob.pe/#publico/certificado/certificado?Tk=') {
			closest_div.addClass( "has-error" ); 
			closest_div.append( error_wrapper(error_id, ' URL no es válida.') );
			err = 1; 
			return false;
		}

		if ($.trim($( "#" + field_id).val()) != '') {
			url_tk = $.trim($( "#" + field_id).val()).split('Tk=');
			url_tk_value = $.trim(url_tk[1]).split('-');
			
			if (url_tk_value.length <= 1 || $.trim(url_tk_value[0]) == '') {
				closest_div.addClass( "has-error" ); 
				closest_div.append( error_wrapper(error_id, ' URL no es válida.') );
				err = 1; 
				return false;
			}
		}
	}

	 closest_div.removeClass( "has-error" ); 
	 $( '.' + error_id).remove();  
	 err = 0;
}

function check_error(){
	if(err==1){
		return false;
	}else{
		
	}
}

function error_wrapper(id, error_msg){
	return '<div class="errowbox '+id+'"><div class="erormsg"> '+error_msg+' </div></div>';	
}

function error_msg(id, closest_div, text_msg){
	$("."+id).remove();
	closest_div.addClass( "has-error" );
	closest_div.append( error_wrapper(id, text_msg) );
}

function isPasswordStrength(password) {

	if (password.length < 8) {		
		return false;
	}
	
	var capitalLetter = false;
	var lowerCase = false;
	var number = false;
	var specialCatacters = false;
	
	for (var i = 0; i < password.length; i++) {

		if (password.charCodeAt(i) >= 65 && password.charCodeAt(i) <= 90) {
			capitalLetter = true;
		} else if (password.charCodeAt(i) >= 97 && password.charCodeAt(i) <= 122) {
			lowerCase = true;
		} else if (password.charCodeAt(i) >= 48 && password.charCodeAt(i) <= 57) {
			number = true;
		} else {
			specialCatacters = true;
		}
	}
  
    $return = 
	    capitalLetter === true && 
		lowerCase === true && 
		number === true && 
		specialCatacters === true;

    return $return;
}

function is_empty(field_obj, field_name, field_label){

	if(field_obj.val()==''){
		 closest_div = $( field_obj ).closest('div');
		 closest_div.addClass( "has-error" );

		if (!(/^exp_start_month|exp_start_year|exp_completion_month|exp_completion_year|ed_exp_start_month|ed_exp_start_year|ed_exp_completion_month|ed_exp_completion_year|month_start_date|year_start_date|month_end_date|year_end_date|start_month|start_year|completion_month|completion_year$/.test(field_name))) {
			error_msg(field_name+'_err',closest_div,'Por favor ingrese el campo '+field_label);
		}
		
		field_obj.focus();
		return true;
	  }
	  return false;
}

function admin_is_empty(field_obj, field_name, field_label){
	if(field_obj.val()==''){
		 alert('Por favor ingrese ' + field_label);
		field_obj.focus();
		return true;
	  }
	  return false;
}

// ===== Front-end contact us form validation

$(document).ready(function(){

  $("#frm_contact_us #full_name").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('full_name', 'nombre completo', 'full_name_err', closest_div, 'no', '', '','');	
 });
 
 $('#frm_contact_us #full_name').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^a-zA-Z\s]/g,'') ); }
 );
 
 $("#frm_contact_us #email").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('email', 'email', 'email_err', closest_div, 'no', '', '','');
 });
 
 $("#frm_contact_us #phone").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('phone', 'teléfono', 'phone_err', closest_div, 'no', '', '','');
 });
 $('#frm_contact_us #phone').bind('keyup blur',function(){ 
    $(this).val( $(this).val().replace(/[^0-9\s]/g,'') ); }
 );

 $("#frm_contact_us #message").blur(function(){
	 closest_div = $( this ).closest('div');
	 universal_validation('message', 'mensaje', 'message_err', closest_div, 'no', '', '','');
 });

});

function validate_contact_form(theForm){
	if(is_empty($("#full_name"), 'full_name', 'nombre completo')) return false;
	if(is_empty($("#email"), 'email', 'email')) return false;
	if(is_empty($("#phone"), 'phone', 'teléfono')) return false;
	if(is_empty($("#message"), 'message', 'mensaje')) return false;
	return true;
}
