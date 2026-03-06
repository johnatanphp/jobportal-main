    <div id="modal-prev-next-steps" class="modal" role="dialog">
      <div class="modal-dialog" style="max-width: 400px;margin: 0 auto;margin-top: 5%;">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Etapas del proceso</h4>
          </div>
          <div class="modal-body">
            <ul style="list-style: none;">
                <?php foreach ($stage_items as $stage_index => $stage_row): ?>
                    <li style="padding:7px 9px;">
                        <a style="color:#333;display: block;border-bottom: 1px solid #ccc; font-size: 15px;padding: 3px 1px;" href="#" class="js-load-data-prev-next" data-stage="<?php echo $stage_row->id; ?>" data-dismiss="modal">
                            -
                            <?php echo $stage_row->name . ' (' . $stage_row->count_candidates . ')'; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>