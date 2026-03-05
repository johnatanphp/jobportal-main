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
  </style>
  <div class="modal-dialog" style=" width: 65%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <a id="back-page" href="#" style="display: none;">
            <i class="glyphicon glyphicon-chevron-left"></i>
          </a>
          R&S de la solicitud
        </h4>
      </div>
      <input id="rs-job-id" type="hidden" value="<?php echo $rs_process->job_ID; ?>">
      <div class="modal-body">
        <div id="view-2"></div>
        <div id="view-1">
          <div>
            <label>Etapa actual: </label>
            <?php 
              echo $rs_stages[$rs_process->sts_stage] ? $rs_stages[$rs_process->sts_stage] : '-'; 
            ?>
          </div>
          <br />
          <div>
            <label>Filtrar por Etapas</label>
            <select id="rs-stage" class="form-control">
            
              <?php foreach ($rs_stages as $stage_id => $stage): ?>
                <option value="<?php echo $stage_id; ?>" <?php echo $stage_id == $rs_process->sts_stage ? "selected='selected'" : ""; ?>>
                  <?php echo $stage; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div id="content-rs-candidates" >Cargando...</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  
  $(document).ready(function(){

    function loadCandidates(jobId, stage) {
      var url = "<?php echo site_url('employer/recruitment_candidates/data_rs_stage_candidates'); ?>";
      var data = {
        job_id: jobId,
        stage: stage
      };

      $.post(url, data, function(html) {
        $( "#content-rs-candidates" ).html(html);
      })
      .fail(function(){
        alert("¡Ha ocurrido un error!");
      });
    }

    function loadRSCandidate(candidateId, jobId) {
      var url = "<?php echo site_url('employer/recruitment_candidates/detail_rs_process/'); ?>" + candidateId + '/' + jobId;
      $( "#view-2" ).load(url, {}, function(html_view) {
        $( "#view-2" ).html(html_view);
        $( "#view-1" ).hide();
        $( "#view-2" ).show();
        $( "#back-page" ).show();
      });
    }

    $( "#rs-stage" ).change(function(){
      
      $( "#content-rs-candidates" ).html("Cargando...");
      var jobId = $( "#rs-job-id" ).val();
      var stage = $(this).val();
      loadCandidates(jobId, stage);      
    });

    $(document).off("click", ".show-rs-candidate");
    $(document).on("click", ".show-rs-candidate", function(e) {
      e.preventDefault();
      var candidateId = $(this).data('candidate-id');
      var jobId = $(this).data('job-id');

      loadRSCandidate(candidateId, jobId);
    });

    $( "#back-page" ).click(function(e){
      e.preventDefault();
      $( "#view-2" ).hide();
      $( "#view-1" ).show();
      $( "#back-page" ).hide();
    });

    $( "#rs-stage" ).change();
  });
</script>