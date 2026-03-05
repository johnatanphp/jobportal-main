<style>
  
  #modal-screening-batches-logs .formwraper {
    border-radius: 0;
    border: 0;
    box-shadow: none;
  }

  #modal-screening-batches-logs .input-group-addon {
    font-weight: bold;
  }
</style>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Log del Lote</h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="formwraper">
        <div class="input-group">
          <label class="input-group-addon">Id</label>
          <span><?php e($batch->id); ?></span>
        </div>   

        <div class="input-group">
          <label class="input-group-addon"> Error</label>
          <span><?php e($batch->error_message); ?></span>
        </div> 
      </div> 
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-style-1" data-dismiss="modal">Cerrar</button>
</div>
