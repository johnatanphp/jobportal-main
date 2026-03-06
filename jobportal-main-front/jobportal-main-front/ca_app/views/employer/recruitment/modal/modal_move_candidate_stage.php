<div id="modal-move-candidate-stage" class="modal" role="dialog">
  <div class="modal-dialog" style="width:96%;max-width: 500px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mover candidatos de etapa</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <p style="font-size: 16px;">¿Esta seguro de mover a los candidatos de etapa?</p>
          </div>
        </div>

        <div class="row" style="margin-top: 25px;">

          <div class="col-md-12">

            <div class="panel-group accordion-notifications" id="accordion-notifications-move">
              <div class="panel panel-default">
                  <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion-notifications-move" href="#collapse-move-1" style="justify-content: end;">
                          <label style="margin: 0 10px 0 0;color: #666;font-size: 13px;">
                              <span class="glyphicon glyphicon-bell" style="color: #666;margin-right: 5px;"></span>
                              ¿Notificar al postulante?
                          </label>
                          <span class="glyphicon glyphicon-chevron-right"></span>
                      </a>
                  </h4>
                  </div>
                  <div id="collapse-move-1" class="panel-collapse collapse">
                      <div class="panel-body">

                        <div class="row">
                          <div class="col-md-8">
                            <label style="font-weight: normal;">Por Correo</label>
                            
                          </div>
                          <div class="col-md-4">
                            <div class="checkbox-wrapper-2" style="float: right;">
                              <input class="tgl tgl-light" id="notify-candidate-move-by-mail" type="checkbox" name="notify_candidate_by_mail" value="1"/>
                              <label class="tgl-btn" for="notify-candidate-move-by-mail" style="width: 38px;height: 21px;">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-8">
                          <label style="font-weight: normal;">Por WhatsApp</label>
                          </div>
                          <div class="col-md-4">
                            <div class="checkbox-wrapper-2" style="float: right;">
                              <input class="tgl tgl-light" id="notify-candidate-move-by-whatsapp" type="checkbox" name="notify_candidate_by_whatsapp" value="1"/>
                              <label class="tgl-btn" for="notify-candidate-move-by-whatsapp" style="width: 38px;height: 21px;">
                            </div>
                          </div>
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
        <button id="btn-candidates-move-stage" type="button" class="btn btn-primary">Mover</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){

  $(document).on("click", ".move-candidates-selected", function(e) {

    const candidatesSelected = $( "input[name='candidate_ids[]']:checked" ).length;

    if (!candidatesSelected) {
        return;
    }   

    $( '#btn-candidates-move-stage' ).data('stage', $(this).data('stage'));
    $( '#modal-move-candidate-stage' ).modal('show');
  });

  $( '#btn-candidates-move-stage' ).click(function(){

    const nofifyByMail = $( 'input[name="notify_candidate_by_mail"]', '#modal-move-candidate-stage').is(':checked') ? 1 : 0;
    const nofifyByWhatsapp = $( 'input[name="notify_candidate_by_whatsapp"]', '#modal-move-candidate-stage').is(':checked') ? 1 : 0;

    const candidatesSelected = $( "input[name='candidate_ids[]']:checked" ).length;

    if (candidatesSelected > 10 && (nofifyByMail || nofifyByWhatsapp)) {
      toastr["error"]('¡Solo puede mover hasta 10 candidatos a la vez si estan activas las notificaciones!');
      return;
    }

    const stage = $(this).data('stage');
    const data = $( "#wrapper-candidates :input" ).serialize() + 
                "&process_id=" + $( "#global_process_id" ).val() + 
                "&stage=" + stage +
                "&notify_candidate_by_mail=" + nofifyByMail +
                "&notify_candidate_by_whatsapp=" + nofifyByWhatsapp;

    const url = "<?php echo site_url('employer/recruitment_candidates/move_candidates_stage'); ?>";

    btnSubmit = $(this);
    btnSubmit.html('Moviendo...');
    $( '#modal-move-candidate-stage .modal-content' ).addClass('load load-image');
  
    $.post(url, data, function(response) {

      btnSubmit.html('Mover');
      $( '#modal-move-candidate-stage .modal-content' ).removeClass('load load-image');

      if (response.success == false) {
        toastr["error"](response.message);
        return;
      }

      if (response.success) {
        const dataResponse = response.data;

        $( '#modal-move-candidate-stage' ).modal('hide');
        toastr["success"](`¡Se han movido ${dataResponse.count_success} candidato(s)!`);
        reloadDataCandidates();
        return;
      }  

    }, 'json')
    .fail(function(){
      btnSubmit.html('Mover');
      $( '#modal-move-candidate-stage .modal-content' ).removeClass('load load-image');
      toastr["error"]("¡Ha ocurrido un error!");
    });
  });
});
</script>