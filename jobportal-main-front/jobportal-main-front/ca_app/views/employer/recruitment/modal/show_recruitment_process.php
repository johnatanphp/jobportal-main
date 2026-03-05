<div>
  <style type="text/css">
    #content-rs-candidates {
      padding: 8px 5px;
    }

    #view-2 .modal-dialog {
      width: auto;
    }

    #view-2 .modal-content {
      border: 0;
      -webkit-box-shadow: none;
      box-shadow: none;
    }

    #view-2 .modal-dialog {
      margin-top: 0;
    }

    #modal-show-rs-process .modal-body {
      padding: 0;
    }
  </style>
  <div class="modal-dialog" style="max-width: 620px;width: 100%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <a id="back-page" href="#" style="display: none;">
            <i class="glyphicon glyphicon-chevron-left"></i>
          </a>
          Proceso de reclutamiento de la solicitud
        </h4>
      </div>
      <input id="rs-process-id" type="hidden" value="<?php echo $rs_process->id; ?>">
      <div class="modal-body">
        <div id="view-2"></div>
        <div id="view-1">
          <div style="padding: 15px;">
            <div>
              <label>Etapa actual: </label>
              <?php 
                echo $rs_process->stage_name; 
              ?>
            </div>
            <div>
              <label>Filtrar por Etapas</label>
              <select id="rs-stage" class="form-control">
                <?php foreach ($rs_stages as $stage): ?>
                  <option value="<?php echo $stage->id; ?>" <?php echo $stage->id == $rs_process->stage_id ? "selected='selected'" : ""; ?>>
                    <?php echo $stage->name . ' (' . $stage->count_candidates . ')'; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div id="content-rs-candidates" style="background: #f4f4f4;padding: 2em 15px;" >
            <div style="text-align: center">Cargando...</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  
  $(function(){

    function loadCandidates(processId, stage) {
      var url = "<?php echo site_url('employer/recruitment_candidates/data_rs_stage_candidates'); ?>";
      var data = {
        process_id: processId,
        stage: stage
      };

      $.post(url, data, function(html) {
        $( "#content-rs-candidates" ).html(html);
      })
      .fail(function(){
        alert("¡Ha ocurrido un error!");
      });
    }

    function loadRSCandidate(processId, candidateId) {
      var url = "<?php echo site_url('employer/recruitment_candidates/detail_process_job/'); ?>" + processId + '/' + candidateId;
      $( "#view-2" ).load(url, {}, function(html_view) {
        $( "#view-2" ).html(html_view);
        $( "#view-1" ).hide();
        $( "#view-2" ).show();
        $( "#back-page" ).show();
        $( "#modal-show-rs-process .modal-dialog" ).css({
          "max-width": "820px", 
        });
      });
    }
    $( "#rs-stage" ).off();
    $( "#rs-stage" ).change(function(){
      $( "#content-rs-candidates" ).html(`<div style="text-align: center;">Cargando...</div>`);
      loadCandidates($( "#rs-process-id" ).val(), $(this).val());      
    });

    $(document).off("click", ".show-rs-candidate");
    $(document).on("click", ".show-rs-candidate", function(e) {
      e.preventDefault();
      var processId = $( "#rs-process-id" ).val();
      var candidateId = $(this).data('candidate-id');
      
      loadRSCandidate(processId, candidateId);
    });

    $( "#back-page" ).click(function(e){
      e.preventDefault();
      $( "#view-2" ).hide();
      $( "#view-1" ).show();
      $( "#back-page" ).hide();

      $( "#modal-show-rs-process .modal-dialog" ).css({
        "max-width": "620px", 
      });
    });

    loadCandidates($( "#rs-process-id" ).val(), $(this).val());     
  });
</script>