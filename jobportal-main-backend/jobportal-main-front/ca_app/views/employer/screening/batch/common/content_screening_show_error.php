
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Detalle del Error</h4>
</div>
<div class="modal-body">      
  <div>
    <div id="screening-batch-item-error" style="background: #e0e0e0; padding: 20px 0;">
      <pre>
        <?php echo htmlspecialchars($batch_item->response) ; ?>
      </pre>
    </div>
  </div>
</div>
