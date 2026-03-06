//JS
//=======Starts City Module=======
function grab_cities_by_country(country_name){

	if(country_name=='USA'){
		$(".ui-autocomplete-input.ui-widget.ui-widget-content.ui-corner-left").css('display','block');
		$("#city_text").css('display','none');
		$("#city_text").val('');
	}
	else{
		$(".ui-autocomplete-input.ui-widget.ui-widget-content.ui-corner-left").css('display','none');
		
		$("#city_text").css('display','block');
	}
}

function set_city_value(city_name){
	$("#city_text").val(city_name);
}

//=======Starts Employer section=========

$( document ).ready(function() {
	
	$("#edit_company_profile").click(function(){
		$('#edit_profile_modal').modal('show');
	});	
	
	$("#edit_company_desc").click(function(){
		$('#edit_profile_description_modal').modal('show');
	});	
});


function edit_posted_job(ID){
	$('#edit_posted_job').modal('show');
}

function delete_posted_job(ID){
	var myurl = baseUrl+'employer/edit_employer/delete_posted_job/'+ID;
	var is_confirm = confirm("Are you sure you want to delete this job?");
	if(is_confirm){
		  $.get(myurl, function (sts) {
			  if(sts=='done')
				  $("#pj_"+ID).fadeOut();
			  else
				  alert(sts);
	   	  });
		 // $("#pj_"+ID).fadeOut();
	}
}

function edit_company_summary(){
		
		$.ajax({
				type: "POST",
				url: baseUrl+"employer/edit_employer/summary",
				data: { cid: $("#cid").val(), content: $("#content").val()}
			  })
				.done(function( msg ) {
					if(msg=='done'){
						$('#edit_profile_description_modal').modal('toggle');
						location.reload();
					}
					else{
						alert(msg);
					}
		});

}

function del_logo(){
			$.ajax({
					type: "POST",
					url: baseUrl+"employer/edit_company/delete_company_logo"
				  })
					.done(function( msg ) {
						if(msg=='done'){
							location.reload();
						} else{
							alert("¡Algo salió mal!");	
							location.reload();
						}
			});

}

//=======Ends Employer section==========

//=======Starts Jobseeker section========
$( document ).ready(function() {
	$("#edit_jobseeker_profile").click(function(){
		$('#edit_profile_modal').modal('show');
	});	
	
	$("#edit_jobseeker_account").click(function(){
		$('#edit_profile_modal').modal('show');
	});
	
	$("#edit_desc").click(function(){
		$('#edit_profile_summary_modal').modal('show');
	});	
	
	$("#add_education").click(function(){
		$( "#add_education_modal" ).modal('show');
	});

	$("#add_other_study").click(function(){
		$( "#other_studies_modal" ).modal('show');
		$( "#frm_other_studies" )[0].reset();
	});		
	
	$("#add_exp").click(function(){
		$('#add_exp_modal').modal('show');
	});
	
	$("#cv").blur(function(){
	 var cv = $.trim($("#cv").val());
	
	 if(cv==''){
		$( this ).closest('div').addClass( "has-error" ); 
	 }
	 else{
		$( this ).closest('div').removeClass( "has-error" ); 
	 }
		
 });
 
 	$("#expected_salary").blur(function(){
	 var expected_salary = $.trim($("#expected_salary").val());
	
	 if(expected_salary==''){
		$( this ).closest('div').addClass( "has-error" ); 
	 }
	 else{
		$( this ).closest('div').removeClass( "has-error" ); 
	 }
		
 });
 
 	$("#cover_letter").blur(function(){
	 var cover_letter = $.trim($("#cover_letter").val());
	
	 if(cover_letter==''){
		$( this ).closest('div').addClass( "has-error" ); 
	 }
	 else{
		$( this ).closest('div').removeClass( "has-error" ); 
	 }
		
 });
 	/*
	$( ".submit-apply-job" ).click(function(){
		apply_job();
	});
	*/
	
	$("#msg_submit").click(function(){
		send_message();
	});
	
	$("#scam_submit").click(function(){
		scam_report();
	});
	
});

//=======Starts events globals========
$(document).ready(function(){

	$(document).on("click", ".view-profile", function(e) {
	
		var url = $(this).prop('href');

		if ($.trim(url) == '') {
			url = $(this).data('href');
		}
		
		showModal("#modal-view-profile", url);		
		e.preventDefault();
	});
});

function showModal(modalId, url) {
	$(modalId).load(url, function(response) {
		$(this).html(response).modal('show');
	});
}

function submitFormQuestionApply(form)
{
	var isValidForm = isValidFormQuestions(form);

	if (isValidForm){
		var data = $(form).serialize();

		apply_job(data);
	} 

	return false;
}

function isValidFormQuestions(form)
{
	var isValidForm = true;
	var listNameRequired = [];

	$( ".question-item" ).removeClass('error');

	$(".required", form).each(function(i, e){

		listNameRequired.push($(e).attr('name'));
	});
	
	for (var i in listNameRequired) {
		
		var e = listNameRequired[i];
		var element = $( "*[name='" + e + "']" );
		var elementType = element.prop('type');
	
		if (elementType == "radio" || elementType == "checkbox") {

			if ($("input[name='" + e +"']:checked").length == 0) {
				
				var contentQuestion = element.closest(".question-item");
				$(contentQuestion).addClass('error');

				isValidForm = false;
			
			}
		} else if ($.trim(element.val()) == "") {
			var contentQuestion = element.closest(".question-item");
			$(contentQuestion).addClass('error');
				
			isValidForm = false;
		} 
	}

	return isValidForm;
}

//=======End events globals========
function apply_job(data)
{	
	var returnval = true;
	
	if ($("#jid").val()==''){
		$("#jid").closest('div').addClass( "has-error" ); 
		returnval = false;	
	}

	//submitter	
	if(returnval){
		
		var data = "jid=" + $( "#jid" ).val() + "&" + data;
		
		$.ajax({
				type: "POST",
				url: baseUrl+"jobseeker/apply_job",
				data: data
			  })
				.done(function( msg ) {
					if(msg=='done'){
						$('#japply').modal('toggle');
						$('#emsg').html('');
						is_already_applied = 'yes';
						$('#msg').html('<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Has aplicado con éxito para este trabajo. </div>');	
						$('.actionBox').html('<h4>Ya has solicitado este trabajo</h4> <a href="javascript:;" class="applyjobgray"><span>Aplicar</span></a>');
					}
					else
					{
						$('#msg').html('');
						$('#emsg').html('<span class="label label-warning">'+msg+'</span>');
					}
		});
	}
	else{
		return false;
	}
}

function scam_report(){
	/*bootbox.alert("Hello world!");*/
	var returnval = true;

	if($("#reason").val()==''){
		$("#reason").closest('div').addClass( "has-error" ); 
		returnval = false;	
	}else{
		$("#reason").closest('div').removeClass( "has-error" ); 
	}

	if($("#scjid").val()==''){
		bootbox.alert("Falta la información del empleo, vuelva a intentarlo después de actualizar la página web.");
		returnval = false;	
	}

	if(returnval){
		$.ajax({
				type: "POST",
				url: baseUrl+"jobseeker/scam_report",
				data: { scjid: $("#scjid").val(), reason: $("#reason").val(), 'g-recaptcha-response': $("#g-recaptcha-response").val()}
			  })
				.done(function( res_json ) {
					var obj = jQuery.parseJSON(res_json);
					
					if(obj.msg=='done'){
						$('#scam').modal('toggle');
						$("#reason").val('');
						$('#scam_emsg').html('');
						$('#msg').html('<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Tu mensaje ha sido enviado con éxito. </div>');	
						bootbox.alert("Su mensaje ha sido enviado con éxito.");
					}
					else
					{
						$('#msg').html('');
						$('#scam_emsg').html('<span class="label label-danger">'+obj.msg+'</span>');
					}
		});
	}
	else{
		return false;
	}
}

function edit_jobseeker_profile(){
		
		$.ajax({
				type: "POST",
				url: baseUrl+"jobseeker/edit_jobseeker/profile",
				data: { full_name: $("#full_name").val(), mobile: $("#mobile").val(), country: $("#country").val(), city: $("#city").val() }
			  })
				.done(function( msg ) {
					if(msg=='done'){
						$('#edit_profile_modal').modal('toggle');
						$('#emsg_profle').html('');
						//$('#msg').html('<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Success!</strong> Profile has been updated successfully. </div>');	
						location.reload();
					}
					else{
						$('#msg').html('');
						$('#emsg_profle').html('<span class="label label-warning">'+msg+'</span>');
					}
		});

}

function edit_jobseeker_summary(){
		
		$.ajax({
				type: "POST",
				url: baseUrl+"jobseeker/edit_jobseeker/summary",
				data: { content: $("#content").val()}
			  })
				.done(function( msg ) {
					if(msg=='done'){
						$('#edit_profile_summary_modal').modal('toggle');
						$('#emsg_summary').html('');
						location.reload();
					}
					else{
						$('#msg').html('');
						$('#emsg_summary').html('<span class="label label-warning">'+msg+'</span>');
					}
		});

}

function update_posted_job_status_employer(id){
	var myurl = baseUrl+'employer/edit_posted_job/status/'+id;
	//var is_confirm = confirm("Are you sure you want to deactivate this job?");
	$.get(myurl, function (sts) {
		var sts = sts == 'active' ? 'Activo' : 'Desactivo';
		var class_label = 'success';
		if (sts != 'Activo') {
			var class_label = 'danger';
		}

   		$("#sts_"+id).html('<span class="label label-'+class_label+'">'+sts+'</span>');
 });
}

function update_status_employer(id) {
	var myurl = baseUrl + 'employer/manage_employers/change_status/' + id;
	$.get(myurl, function (sts) {
		var class_label = 'success';
		var sts = sts == 'active' ? 'Activo' : 'Bloqueado';

		if (sts != 'Activo') {
			var class_label = 'danger';
		}

   		$( "#sts_" + id ).html('<span class="label label-' + class_label + '">' + sts + '</span>');
 });
}

function update_status_rrhh_user(id) {
	var myurl = baseUrl + 'employer/manage_rrhh_users/change_status_user/' + id;
	$.post(myurl, function (sts) {
		var class_label = 'success';
		var sts = sts == 'active' ? 'Activo' : 'Bloqueado';

		if (sts != 'Activo') {
			class_label = 'danger';
		}

		$( "#sts_" + id ).html('<span class="label label-' + class_label + '">' + sts + '</span>');
	});
}

function add_js_edu() {

	var data = $( "#frm_add_education" ).serialize();
	$( "#js_education_submitter" ).prop('disabled', true);

	$.ajax({
		type: "POST",
		url: baseUrl+"jobseeker/education/add",
		data: data
	})
	.done(function( msg ) {
		if (msg == 'done') {
			$('#add_education_modal').modal('hide');
			$('#emsg_add_edu').html('');
			location.reload();
		} else {
			$('#emsg_add_edu').html('<span class="label label-warning">' + msg + '</span>');
			$( "#js_education_submitter" ).prop('disabled', false);
		}
	});
}

function del_edu(id){
		var ed_id = id;
		
		confirmed = confirm("¿Seguro que quieres eliminar tu educación?");
		if(confirmed){
			$('#edu_'+id).fadeOut();
			$.ajax({
					type: "POST",
					url: baseUrl+"jobseeker/education/delete",
					data: { id: id}
				  })
					.done(function( msg ) {
						if(msg=='done'){
							$('#edu_'+id).fadeOut();
						}
			});
		}

}

function del_photo(){
			$.ajax({
					type: "POST",
					url: baseUrl+"jobseeker/edit_jobseeker/delete_photo"
				  })
					.done(function( msg ) {
						if(msg=='done'){
							location.reload();
						} else{
							alert("Something went wrong!");	
							location.reload();
						}
			});

}

function edit_js_edu(id){

	var data = $( "#frm_edit_education" ).serialize() + "&ID=" + id;

	$( '#js_edit_edu_submit' ).prop('disabled', true); 
	$.ajax({
		type: "POST",
		url: baseUrl + "jobseeker/education/edit",
		data: data
	})
	.done(function(msg) {
		if (msg == 'done') {
			$('#edit_education_modal').modal('hide');
			$('#emsg_edit_edu').html('');
			location.reload();
		} else {
			$('#emsg_edit_edu').html('<span class="label label-warning">' + msg + '</span>');
			$( '#js_edit_edu_submit' ).prop('disabled', false);
		}
	});
}

function load_edit_js_edu(id) {
	$('#edit_education_modal').modal('toggle');
	$('#ed_studying').off('change.checkdate');

	$.ajax({
		type: "POST",
		url: baseUrl+"jobseeker/education/education_by_id",
		data: { id: id}
		})
		.done(function( data ) {
			obj = jQuery.parseJSON(data);
			var start_date = obj.start_date;
			var end_date = obj.end_date;
			var pieces_start_date = start_date.split("-");
			var completion_year = "";
			var completion_month = "";

			$("#ed_edu_id").val(obj.ID);
			select_value('ed_degree_title',obj.degree_title);
			$("#ed_major_subject").val(obj.major);
			$("#ed_institute").val(obj.institude);
			select_value('ed_edu_country',obj.country);
			
			$( "#ed_start_year" ).val(pieces_start_date[0]);
			$( "#ed_start_month" ).val(pieces_start_date[1]);
			
			$( "#ed_studying" ).prop('checked', true);

			if (end_date != null && end_date != '0000-00-00') {
				var pieces_end_date = end_date.split("-");
				completion_year = pieces_end_date[0];
				completion_month = pieces_end_date[1];
				$( "#ed_studying" ).prop('checked', false);
			}

			$( "#ed_completion_year" ).val(completion_year);
			$( "#ed_completion_month" ).val(completion_month);
	
			$( "#ed_studying" ).change();

			validate_range_dates('#ed_start_year', '#ed_start_month', '#ed_completion_year', '#ed_completion_month', '#ed_studying');
	});
}

function del_applied_job(id){

		confirmed = confirm("¿Seguro que quieres eliminar este trabajo aplicado?");
		if(confirmed){
			$('#aplied_'+id).fadeOut();
			$.ajax({
					type: "POST",
					url: baseUrl+"jobseeker/edit_jobseeker/delete_applied_job",
					data: { id: id}
				  })
					.done(function( msg ) {
						if(msg=='done'){
							
						}
			});
		}
}


function add_js_exp() {
		
	var data = $( "#frm_add_exp" ).serialize();
	$( '#js_exp_submit' ).prop('disabled', true);

	$.ajax({
		type: "POST",
		url: baseUrl+"jobseeker/experience/add",
		data: data			  
	})
	.done(function( msg ) {
		if (msg == 'done') {
			$('#add_exp_modal').modal('hide');
			$('#emsg_add_exp').html('');
			location.reload();
		} else {
			$('#emsg_add_exp').html('<span class="label label-warning">' + msg + '</span>');
			$( '#js_exp_submit' ).prop('disabled', false);
		}
	});
}

function load_edit_js_exp(id){
			$('#edit_exp_modal').modal('toggle');
			
			$('#ed_exp_working').off('change.checkdate');

			$.ajax({
					type: "POST",
					url: baseUrl+"jobseeker/experience/experience_by_id",
					data: { id: id}
				  })
					.done(function( data ) {
						obj = jQuery.parseJSON(data);

						var start_date = obj.start_date;
						var end_date = obj.end_date;
						var pieces_start_date = start_date.split("-");
						var completion_year = "";
						var completion_month = "";

						$("#ed_exp_id").val(obj.ID);
						$("#ed_job_title").val(obj.job_title);
						$("#ed_company_name").val(obj.company_name);

						select_value('ed_exp_country',obj.country);

						$( "#ed_exp_industry" ).val(obj.industry);
						$( "#ed_exp_job_level" ).val(obj.job_level);
						$( "#ed_exp_area" ).val(obj.area);
						$( "#ed_exp_description" ).val(obj.description);

						$( "#ed_exp_start_year" ).val(pieces_start_date[0]);
						$( "#ed_exp_start_month" ).val(pieces_start_date[1]);
						
						$( "#ed_exp_working" ).prop('checked', true);

						if (end_date != null && end_date != '0000-00-00') {
							var pieces_end_date = end_date.split("-");
							completion_year = pieces_end_date[0];
							completion_month = pieces_end_date[1];
							$( "#ed_exp_working" ).prop('checked', false);
						}

						$( "#ed_exp_completion_year" ).val(completion_year);
						$( "#ed_exp_completion_month" ).val(completion_month);
						$( "#ed_exp_working" ).change();

                        validate_range_dates('#ed_exp_start_year', '#ed_exp_start_month', '#ed_exp_completion_year', '#ed_exp_completion_month', '#ed_exp_working');
			});
}

function edit_js_exp(){

	var data = $( "#frm_edit_exp" ).serialize();
	$( '#js_edit_exp_submit' ).prop('disabled', true);

	$.ajax({
		type: "POST",
		url: baseUrl+"jobseeker/experience/edit",
		data: data
	})
	.done(function( msg ) {
		if (msg == 'done') {
			$('#edit_exp_modal').modal('hide');
			$('#emsg_edit_exp').html('');
			location.reload();
		} else {
			$('#emsg_edit_exp').html('<span class="label label-warning">'+msg+'</span>');
			$( '#js_edit_exp_submit' ).prop('disabled', false);
		}
	});
}

function del_exp(id){
		var ed_id = id;
		
		confirmed = confirm("¿Seguro que quieres eliminar tu experiencia?");
		if(confirmed){
			$('#edu_'+id).fadeOut();
			$.ajax({
					type: "POST",
					url: baseUrl+"jobseeker/experience/delete",
					data: { id: id}
				  })
					.done(function( msg ) {
						if(msg=='done'){
							$('#exp_'+id).fadeOut();
						}
			});
		}
}

function add_other_studies()
{
	var data = $( "#frm_other_studies" ).serialize();
	$( '#add_other_study_submit' ).prop('disabled', true);

	$.ajax({
		type: "POST",
		url: baseUrl + "jobseeker/other_studies/add",
		data: data			  
	})
	.done(function( msg ) {

		if (msg == 'done') {
			$( "#other_studies_modal" ).modal('hide');
			$('#emsg_add_exp').html('');
			location.reload();
		} else {
			$('#emsg_add_exp').html('<span class="label label-warning">' + msg + '</span>');
			$( '#add_other_study_submit' ).prop('disabled', false);
		}
	});
}

function edit_other_studies()
{
	var data = $( "#frm_edit_other_studies" ).serialize();
	$( '#edit_other_study_submit' ).prop('disabled', true);

	$.ajax({
		type: "POST",
		url: baseUrl+"jobseeker/other_studies/edit",
		data: data
	})
	.done(function( msg ) {
		if (msg == 'done'){
			$('#edit_other_studies_modal').modal('hide');
			$('#emsg_edit_exp').html('');
			location.reload();
		} else{
			$( '#emsg_edit_exp' ).html('<span class="label label-warning">' + msg + '</span>');
			$( '#edit_other_study_submit' ).prop('disabled', false);
		}
	});
}

function load_edit_other_studies(id)
{
	$( "#edit_other_studies_modal" ).modal('toggle');
	$( "#ed_otst_studying" ).off('change.checkdate');
	
	$.ajax({
			type: "POST",
			url: baseUrl+"jobseeker/other_studies/get_other_study",
			data: { id: id}
		  })
	.done(function( data ) {
		obj = jQuery.parseJSON(data);

		var start_date = obj.start_date;
		var end_date = obj.end_date;
		var pieces_start_date = start_date.split("-");
		var completion_year = "";
		var completion_month = "";
	
		$( "#ed_otst_id" ).val(obj.ID);
		$( "#ed_otst_study_name" ).val(obj.name);
		
		select_value('ed_otst_country',obj.country);

		$( "#ed_otst_institute" ).val(obj.institute);
		$( "#ed_otst_type_study" ).val(obj.type);

		select_value('ed_otst_start_month', pieces_start_date[1]);
		select_value('ed_otst_start_year', pieces_start_date[0]);
	
		$( "#ed_otst_studying" ).prop('checked', true);

		if (end_date != null && end_date != '0000-00-00') {
			var pieces_end_date = end_date.split("-");
			completion_year = pieces_end_date[0];
			completion_month = pieces_end_date[1];
			$( "#ed_otst_studying" ).prop('checked', false);
		}

		select_value('ed_otst_completion_year', completion_year);
		select_value('ed_otst_completion_month', completion_month);
		
		$( "#ed_otst_studying" ).change();
		validate_range_dates('#ed_otst_start_year', '#ed_otst_start_month', '#ed_otst_completion_year', '#ed_otst_completion_month', '#ed_otst_studying');
    
	});
}

function del_other_studies(id)
{
	var ed_id = id;
	confirmed = confirm("¿Seguro que quieres eliminar tu otro estudio?");
	
	if (confirmed) {
		
		$('#osts_' + id).fadeOut();
		
		$.ajax({
			type: "POST",
			url: baseUrl + "jobseeker/other_studies/delete",
			data: { id: id}
		})
		.done(function( msg ) {
			if(msg == 'done'){
				$('#otst_'+ id).fadeOut();
			}
		});
	}
}

function del_cv(id, fl){

		confirmed = confirm("¿Seguro que quieres eliminar tu currículum?");
		if(confirmed){
			$('#cv_'+id).fadeOut();
			$.ajax({
					type: "POST",
					url: baseUrl+"resumes/delete",
					data: { id: id, fl: fl}
				  })
					.done(function( msg ) {
						if(msg=='done'){
							
						}
			});
		}
}

//=======Ends Jobseeker section=========
function select_value(field_id, val){
	/*$("#"+field_id+" option").each(function() {
		$(this).removeAttr('selected');
  		if($(this).val() == val) {
    		$(this).attr('selected', 'selected');     
  		}                        
	});	*/

	$("#"+field_id+" option[value='"+val+"']").attr('selected', 'selected');
}
$( document ).ready(function() {
	
	$("#reg_pop").click(function(){
		$('.registerPopup').slideToggle();
	});

	$( "._link-back" ).click(function(e) {
		e.preventDefault();
		window.history.back();
	});
});

function check_bad_words(content, bad_words, field_id){
	var text = content.split(' ');
	for (var i=0; i < text.length; i++) {
			if($.inArray($.trim(text[i].toLowerCase()), bad_words) !==-1){
				$( '#'+field_id ).closest('div').append( error_wrapper(field_id+'_err', '"'+$.trim(text[i])+'" cannot be used.') );
				$( '#'+field_id ).focus();
				return false;
			}
		}
	//$( '#'+field_id+'_err' ).remove();
}

//Add Post Job Skill
function add_job_skill(){
	$('#js_skill_add').attr("disabled", true);
	$('#js_skill_add').val('Agregando...');
	var new_skill = "'"+$('#skill').val()+"'";
	var skill = $('#s_val').val();
	selected_skill_array = skill.split(", ");
	if (selected_skill_array.indexOf($('#skill').val()) >= 0){
		$("#skill").val('');
		$("#skill").focus();
		
		$('#js_skill_add').attr("disabled", false);
		$('#js_skill_add').val('Agregar');
		return false;
	}
	
	skill = skill+', '+$("#skill").val().toLowerCase();
	$('#s_val').val(skill);

	$("#myskills").append('<li>'+$("#skill").val()+'<a href="javascript:remove_job_skill('+new_skill+');" class="delete"><i class="fa fa-times-circle"></i></a></li>');
	$("#skill").val('');
	$("#skill").focus();
	
	$('#js_skill_add').attr("disabled", false);
	$('#js_skill_add').val('Agregar');
	$('#info-required-skill').hide();
}

//Remove Post Job Skill
function remove_job_skill(js_skill){
	var skill = $('#s_val').val();
	js_skill = ', ' + js_skill.toLowerCase();
	skillValue = skill.replace(js_skill, '');
	$('#s_val').val(skillValue);

	if (skillValue == '') {
		$('#info-required-skill').show();
	}
}

function send_message(){
	var returnval = true;

	/*if($("#message").val()==''){
		$("#message").closest('div').addClass( "has-error" ); 
		returnval = false;	
	}
	
	if($("#jid").val()==''){
		$("#jid").closest('div').addClass( "has-error" ); 
		returnval = false;	
	}*/

	if(returnval){
		$.ajax({
				type: "POST",
				url: baseUrl+"employer/job_applications/send_message_to_candidate",
				data: { jsid: $("#jsid").val(), message: $("#message").val() }
			  })
				.done(function( msg ) {
					if(msg=='done'){
						$('#send_msg').modal('toggle');
						$('#emsg').html('');
						$("#message").val('');
						$('#msg').html('<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> El mensaje ha sido enviado.</div>');	
					}
					else
					{
						$('#msg').html('');
						$('#emsg').html('<span class="label label-warning">'+msg+'</span>');
					}
		});
	}
	else{
		return false;
	}
}

//__________________Update status recruiter ____________________________

function update_status_staff_recruiter(id)
{
	var myurl = baseUrl + 'employer/manage_staff_recruiters/change_status_staff_recruiter/' + id;
	$.get(myurl, function (sts) {
		var class_label = 'success';
		var sts = sts == 'active' ? 'Activo' : 'Bloqueado';

		if (sts != 'Activo') {
			var class_label = 'danger';
		}

   		$( "#sts_" + id ).html('<span class="label label-' + class_label + '">' + sts + '</span>');
 });
}

function parseBool(value) {
	return (/^(true|1)$/i).test(value);
}

function validate_range_dates(year_range_1, month_range_1, year_range_2, month_range_2, check_range)
{
	var yr1 = $(year_range_1);
	var mr1 = $(month_range_1);
	var yr2 = $(year_range_2);
	var mr2 = $(month_range_2);
	var check_range = $(check_range);

	$(year_range_1).change(function() {

		if  (parseInt(yr2.val()) < parseInt($(this).val())) {
			yr2.val("");
			mr2.val("");
		}	

		// if ($(this).val() == "") {

		// 	mr1.attr({'disabled': true});
		// 	yr2.attr({'disabled': true});
		// 	mr2.attr({'disabled': true});

		// } else {
		// 
		// }
		
		mr1.attr({'disabled': false});

		var yr1_val = parseInt(yr1.val());

		var yr2_options = $(year_range_2 + ' option' );

		yr2_options.each(function(i, o) {
		
			var option = $(o);
			var option_val = parseInt(option.prop('value'));

			if (option_val < yr1_val) {
				option.attr({'disabled': true});
			} else {
				option.attr({'disabled': false});
			}
		});

	});

	$(month_range_1).change(function() {

		yr2.val("");
		mr2.val("");

		if ($(this).val() == "") {

			yr2.attr({'disabled': true});
			mr2.attr({'disabled': true});

		} else {

			if (!check_range.is(':checked')) {
				yr2.attr({'disabled': false});
			}
		}
	});

	$(year_range_2).change(function() {

		mr2.val("");

		if ($(this).val() == "") {

			mr2.attr({'disabled': true});
		} else {
			mr2.attr({'disabled': false});
		}

		var mr1_val = parseInt(mr1.val());
		var yr1_val = parseInt(yr1.val());
		var yr2_val = parseInt(yr2.val());
		var mr2_options = $(month_range_2 + ' option' );

		mr2_options.each(function(i, o) {
		
			var option = $(o);
			var option_val = parseInt(option.prop('value'));

			if (option_val < mr1_val && option_val != 0 && yr1_val == yr2_val) {
				option.attr({'disabled': true});
			} else {
				option.attr({'disabled': false});
			}
		});

	});

	$(check_range).on('change.checkdate', function(){

		if (!$(this).is(':checked')) {
			mr1.change();
		}
	})
}

$(window).resize(function(){
	adjustFooter();
});

function adjustFooter() {
	var h = $( ".footerWrap" ).outerHeight(true);

	$( "body" ).css({
		'margin-bottom': h + 'px'
	});

	$( ".footerWrap" ).css({
		'height': h + 'px',
		'position': 'absolute',
		'visibility': 'visible'
	});
}

$(document).ready(function(){

	$.fn.extend({
		check: function() {

			return $(this).each(function() {

				var oCheck = $(this);
				var labelParent = $(this).closest('label');
				var iCheck = $('<i class="material-icons">check_box_outline_blank</i>');
				var textChecked = 'check_box';
				var textUnChecked = 'check_box_outline_blank';

				labelParent.prepend(iCheck);
				labelParent.addClass('label-check');
				
				oCheck.change(function(){
					if ($(this).is(':checked')) {
						iCheck.text(textChecked);
					} else {
						iCheck.text(textUnChecked);
					}
				});

				oCheck.hide();
			});			
		},

		radio: function() {

			return $(this).each(function() {

				var oRadio = $(this);
				var labelParent = $(this).closest('label');
				var iRadio = $('<i class="material-icons">radio_button_unchecked</i>');
				var textChecked = 'radio_button_checked';
				var textUnChecked = 'radio_button_unchecked';

				labelParent.prepend(iRadio);
				labelParent.addClass('label-radio');
				
				oRadio.change(function(){

					var nameRadio = $(this).attr('name');

					$('input[name="' + nameRadio + '"]').each(function(i, radio) {
						if ($(radio).is(':checked')) {
							
							$(radio).closest('label').find('i').text(textChecked);

						} else {
							$(radio).closest('label').find('i').text(textUnChecked);
						}
					});
				});

				oRadio.hide();
				oRadio.change();
			});			
		},		
	});
});


$(document).ready(function(){

	$.fn.extend({
		generateSequence: function() {
		
			var sequence = $(this).data('[sequence]');
			var seqIncremet = sequence ? sequence + 1 : 1;
			$(this).data('[sequence]', seqIncremet);

			return seqIncremet;
		}
	});
});

$(document).on('click', 'a[href="#"]', function(e){
	e.preventDefault();
});

$(document).ready(function() {
	
	$( ".show-selector-profiles" ).click(function(){
		var url = baseUrl + 'user/show_select_profiles';
		$( "#modal-load-user-profiles" ).load(url, {}, function(response) {
			$(this).html(response).modal('show');
		});
	});

	$( ".show-rs-doc-comments" ).click(function() {

		var data = {
			document: $(this).data('document')
		};

		var refId = $(this).data('ref-id');

		if (refId) {
			data['ref_id'] = refId;
		}

		var url = baseUrl + "jobseeker/requested_documents/modal_show_document_comments";

		$( "#modal-rs-doc-comments" ).load(url, data, function(html) {
			$(this).html(html).modal('show');
		});
    });

    $( ".js-del-cookies-notification" ).click(function() {
    	var action = $(this).data('cookie');
    	var expire = action == 'accept' ? (365 * 10) : (0.5 / 1440);

    	$.cookie('cookies_accepted', 'yes', { expires: expire, path : '/'});
    	
    	$( "#wrapper-cookie-policy" ).fadeOut(300, function() {
    		$(this).remove();
    	});
    });

    $( "#accept-term-and-policy" ).click(function(){

    	var btnAccept = $(this);

    	btnAccept.attr('disabled', true);

    	var url = baseUrl + "jobseeker/legal_terms/accept";

    	$.post(url, {}, function(result) {

    		if (result.success == false) {
    			btnAccept.attr('disabled', false);
    			alert("¡ERROR por favor intenta de nuevo!");

    			return;
    		}

    		$( "#content-notice-terms-policy" ).fadeOut(350, function() {

    			$(this).remove();
    		});
    	}, 'json');

    });

	$(function(){
		$( '.modal' ).on("hidden.bs.modal", function (e) { 
			if ($('.modal:visible').length) { 
				$('body').addClass('modal-open');
			}
		});
	});
});