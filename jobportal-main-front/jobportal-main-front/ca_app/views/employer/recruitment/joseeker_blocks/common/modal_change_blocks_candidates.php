<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Bloques actuales</h4>
    </div>
    <div class="modal-body">
      <table class="table">
          <tr>
              <th>Bloque</th>
              <th>Fecha creación</th>
              <th>Fecha actualización</th>
              <th>Total candidatos</th>
              <th></th>
          </tr>
          <?php foreach ($blocks as $index => $row_blocks): ?>
            <tr>
              <td> 
                BLOQUE <?php e($index + 1); ?>
              </td>
              <td>
                  <?php e($row_blocks->creation_date); ?>
              </td>
              <td>
                  <?php e($row_blocks->update_date ? $row_blocks->update_date : '-'); ?>
              </td>
              <td align="center">
                  <?php e($row_blocks->total_candidates); ?>
              </td>
              <td>
                <?php echo form_open('employer/recruitment/jobseeker_blocks/move', ['class' => "form-move-seeker-blocks"]); ?>
                  <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                  <input type="hidden" name="creation_date" value="<?php e($row_blocks->creation_date); ?>">
                  <input type="hidden" name="update_date" value="<?php e($row_blocks->update_date); ?>">
                  <button class="btn btn-xs btn-primary" type="submit">Mover aquí</button>
                <?php echo form_close(); ?>
              </td>
            </tr>
          <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>