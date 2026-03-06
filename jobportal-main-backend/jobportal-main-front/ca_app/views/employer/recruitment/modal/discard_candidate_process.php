<div id="modal-discard-candidate-process" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Descartar candidato</h4>
      </div>
      <div class="modal-body">
      	<div class="stop-tracking-info">
      		Por favor confirme si está seguro de detener el seguimiento a el candidato, además puede
      		ingresar comentarios / observaciones de ser neceario.   
      	</div>

      	<div>
	      	<label>Comentarios / Observaciones (Opcional)</label>
	        <textarea id="stop-tracking-comments" class="form-control" name="comments" rows="5"></textarea>
     	  </div>
        <br>
        <div class="panel-group accordion-notifications" id="accordion-notifications-discard">
          <div class="panel panel-default">
              <div class="panel-heading">
              <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion-notifications-discard" href="#collapse1" style="justify-content: end;">
                      <label style="margin: 0 10px 0 0;color: #666;font-size: 13px;">
                          <span class="glyphicon glyphicon-bell" style="color: #666;margin-right: 5px;"></span>
                          ¿Notificar al postulante?
                      </label>
                      <span class="glyphicon glyphicon-chevron-right"></span>
                  </a>
              </h4>
              </div>
              <div id="collapse1" class="panel-collapse collapse">
                  <div class="panel-body">

                      <div class="row">
                          <div class="col-md-6">
                              <label style="font-weight: normal;">Por Correo</label>
                              
                          </div>
                          <div class="col-md-6">
                              <div class="checkbox-wrapper-2" style="float: right;">
                                  <input class="tgl tgl-light" id="notify-candidate-by-mail" type="checkbox" name="notify_candidate_by_mail" value="1"/>
                                  <label class="tgl-btn" for="notify-candidate-by-mail" style="width: 38px;height: 21px;">
                              </div>
                          </div>
                      </div>
                  
                      <div class="row">
                          <div class="col-md-6">
                              <label style="font-weight: normal;">Por WhatsApp</label>
                          </div>
                          <div class="col-md-6">
                              <div class="checkbox-wrapper-2" style="float: right;">
                              <input class="tgl tgl-light" id="notify-candidate-by-whatsapp" type="checkbox" name="notify_candidate_by_whatsapp" value="1"/>
                              <label class="tgl-btn" for="notify-candidate-by-whatsapp" style="width: 38px;height: 21px;">
                              </div>
                          </div>
                      </div>
                      
                  </div>
              </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btn-stop-tracking" type="button" class="btn btn-primary">Descartar</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){

  $(document).off("click", ".stop-tracking-candidate");
  $(document).on("click", ".stop-tracking-candidate", function(){
    var candidateId = $(this).closest(".wrapper-candidate").data("candidate-id");

    $( "#modal-discard-candidate-process" ).data('candidate-id', candidateId);
    $( "#modal-discard-candidate-process" ).modal('show');

    $( 'textarea[name="comments"]', '#modal-discard-candidate-process').val('');
    $( 'input[name="notify_candidate_by_mail"]', '#modal-discard-candidate-process').prop('checked', false);
    $( 'input[name="notify_candidate_by_whatsapp"]', '#modal-discard-candidate-process').prop('checked', false);
  });

  $(document).off("click", "#btn-stop-tracking");
  $(document).on("click", "#btn-stop-tracking", function() {
      var candidateId = $( "#modal-discard-candidate-process" ).data('candidate-id');

      var containerCandidate = $(".wrapper-candidate[data-candidate-id='" + candidateId + "']");
    
      var stage = $( "#current_stage" ).val();
      var process_id = $( "#global_process_id" ).val();
      var comments = $( "#stop-tracking-comments" ).val();

      var data = "jobseeker_id=" + candidateId + 
              "&process_id=" + process_id +
              "&stage=" + stage + 
              "&notify_candidate_by_mail=" + ($( 'input[name="notify_candidate_by_mail"]', '#modal-discard-candidate-process').is(':checked') ? 1 : 0) + 
              "&notify_candidate_by_whatsapp=" + ($( 'input[name="notify_candidate_by_whatsapp"]', '#modal-discard-candidate-process').is(':checked') ? 1 : 0) + 
              "&comments=" + comments

      var btnStoTraking = $( "#btn-stop-tracking" );
      btnStoTraking.prop('disabled', true);

      var url = app.siteUrl('employer/recruitment_candidates/stop_tracking_candidate');
      $.post(url, data, function(response) {
        var status = response.success;
        if (status) {
          toastr["success"]("¡El candidato ha sido descartado!");

          // $( 'button[class="btn btn-xs modal-open-detail-candidate-discarded"]', containerCandidate).remove();

          // containerCandidate.find('.container-alerts').append(`
          //     <button class="btn btn-xs modal-open-detail-candidate-discarded" 
          //             type="button" data-note-discarded="${comments}" 
          //             style="border-radius: 4px;display:inline-block;background: #ccc;font-size: 12px;font-style: italic;">
          //         Descartado
          //     </button>
          // `);

          reloadDataCandidates();
          $( "#modal-discard-candidate-process" ).modal('hide');
        } else {
          toastr["error"]("¡Ha ocurrido un error!");
        }
      }, 'json')
      .fail(function(){
        toastr["error"]("¡Ha ocurrido un error!");
      }).always(function(){
        btnStoTraking.prop('disabled', false);
      });
  });
  
  $( '#accordion-notifications-discard' ).on('show.bs.collapse', function () {  
		
		$( '.glyphicon-chevron-right', $(this).find('.panel-heading a'))
			.removeClass('glyphicon-chevron-right')
			.addClass('glyphicon-chevron-down');
	});

	$( '#accordion-notifications-discard' ).on('hide.bs.collapse', function () {
		$( '.glyphicon-chevron-down', $(this).find('.panel-heading a'))
			.removeClass('glyphicon-chevron-down')
			.addClass('glyphicon-chevron-right'); 
	});
});
</script>