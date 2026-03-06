<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Estado reclutamiento</h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label>Agregado el:</label>
        <span><?php e($tray_candidate->created_at); ?></span>
      </div>
      
      <div class="form-group">
        <label>Agregado por: </label>
        <span><?php e($tray_candidate_created_by->first_name); ?></span>
      </div>

      <div class="form-group">
        <label>Estado: </label>
        <span><?php e($tray_candidate_status->name); ?></span>
      </div>

      <?php if ($contract && $contract->synchronization_error): ?>
        <div class="form-group">
          <label>Error migración: </label>
          <span style="color: red;"><?php e($contract->synchronization_error); ?></span>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php if (in_array($tray_candidate->status_id, [3, 5])): ?>
    <div class="row">
      <div class="col-md-12">
        <table class="table" style="width: 100%;">
          <thead>
            <tr>
              <th>Evento</th>
              <th>Fecha de envio</th>
              <th>Estado</th>
              <th>Log</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sync_logs as $log): ?>
              <tr>
                <td><?php e($log->sync_name); ?></td>
                <td><?php e($log->sync_created_at); ?></td>
                <td><?php e($log->sync_success == 1 ? 'OK': 'Fallido'); ?></td>
                <th>
                <a href="#" 
                    data-id="<?php echo $log->sync_id; ?>"
                    class="show-modal-sync-log-detail">
                    Ver
                </a>
                </th>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
$(function(){
  $( '.show-modal-sync-log-detail' ).click(function(){

    const url = "<?php echo site_url('employer/recruitment_tray/process_candidates/get_sync_log_detail'); ?>";
    const data = {
      'id' : $(this).data('id')
    };
    $.get(url, data, function(res){
      $( '#modal-tray-candidates-sync-logs .modal-body' ).html(res);

      try {
        $( '#sync-parameters pre' ).html(JSON.stringify(JSON.parse($( '#sync-parameters pre' ).html()), null, 2));
      } catch (e) {}
      
      try {
        $( '#sync-response pre' ).html(JSON.stringify(JSON.parse($( '#sync-response pre' ).html()), null, 2));
      } catch (e) {}
      
      $( '#modal-tray-candidates-sync-logs' ).modal('show');
    });
  });
}); 
</script>