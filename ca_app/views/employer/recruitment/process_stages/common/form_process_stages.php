<?php echo form_open('employer/recruitment/process_stages/save', ['id' => 'form-recruitment-stages-config']); ?>
  <div class="modal-header">
    <h4 class="modal-title">Etapas del proceso</h4>
  </div>
  <div class="modal-body">
    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>"> 
    <?php foreach ($stages as $stage): ?>
      <?php 
        $count_candidates = $this->Recruitment_candidate->count_all_candidates_by_stage($job_id, $stage->id);  
      ?>
      <div style="padding: 6px 2px;;border-bottom: 1px solid #eeeeee;">
        <div style="display:flex;height: 25px;">
          <label style="margin:0;flex:1;font-weight: normal;"><?php echo $stage->name; ?></label>
          <?php 
            $check_selected =  array_search($stage->id, array_column((array)$selected_stages, 'id'));
          ?>
        
          <div class="checkbox-wrapper-s1">
            <div class="round">
              <input type="checkbox" 
                 id="<?php echo $stage->id; ?>"
                 name="stages[]" 
                 class="stages-check"
                 value="<?php echo $stage->id; ?>" 
                 <?php echo $check_selected !== false ? 'checked' : ''; ?>
                 <?php echo $stage->id == 6 || $stage->id == 7 ? 'disabled' : ''; ?>
                 data-count-candidate="<?php echo $count_candidates; ?>">
              <label for="<?php echo $stage->id; ?>"></label>
            </div>
          </div>

        </div>
        <div>
          <span style="font-size:12px;color: #888;"><?php echo $count_candidates; ?> candidatos</span>
        </div>
      </div>
    <?php endforeach; ?>
    <br>
    <br>
  </div>
  <div class="modal-footer">
    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancelar</button>
    <button class="btn btn-sm btn-primary" type="submit">Guardar</button>
  </div>
<?php echo form_close(); ?>

<script>
$(function(){
  $( '#form-recruitment-stages-config' ).submit(function(e){
    e.preventDefault();
    
    const url = $(this).prop('action');
    const data = $(this).serialize();
    
    $( '#modal-recruitment-stages-config .modal-dialog' ).addClass('load load-image');

    $.post(url, data, function(response){
      const status = response.status
      if (status) {
        toastr["success"](response.message);
        reloadDataCandidates('false');
      } else {
        toastr["error"](response.message);
      }
    }, 'json')
    .fail(function(){
      toastr["error"]('¡Un error ha ocurrido!');
    }).always(function(){
      $( '#modal-recruitment-stages-config .modal-dialog' ).removeClass('load load-image');
    });

    return false;
  });

  $( '.stages-check' ).change(function(){

    if (!$(this).is(':checked') && $(this).data('count-candidate') > 0) {
      toastr["warning"]('No se puede quitar la etapa porque tiene candidatos');
      $(this).prop('checked', true);
    }
  });
});
</script>