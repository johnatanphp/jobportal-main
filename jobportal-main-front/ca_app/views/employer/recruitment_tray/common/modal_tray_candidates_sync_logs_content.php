<ul class="nav nav-pills" style="margin-bottom: 3px;">
  <li class="active"><a data-toggle="pill" href="#sync-parameters">Parámetros</a></li>
  <li><a data-toggle="pill" href="#sync-response">Respuesta</a></li>
</ul>
<div class="tab-content">
  <div id="sync-parameters" class="tab-pane fade in active" style="background: #e0e0e0; padding: 20px 0;">
    <pre>
      <?php echo htmlspecialchars($sync_row->parameters); ?>
    </pre>
  </div>
  <div id="sync-response" class="tab-pane fade" style="background: #e0e0e0; padding: 20px 0;">
    <pre>
      <?php echo htmlspecialchars($sync_row->response) ; ?>
    </pre>
  </div>
</div>
  