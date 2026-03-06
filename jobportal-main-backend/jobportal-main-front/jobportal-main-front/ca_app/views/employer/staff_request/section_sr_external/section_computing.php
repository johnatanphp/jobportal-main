<?php
  $computing_applications = isset($computing_applications) ? $computing_applications : array();
?>
<div id="section-computing">
  <div class="step-title">
    Informática
  </div>
  <div class="step-inputs">

    <div class="row">
      <div class="col-md-8">
        <div class="content-applications">
          <table id="table-applications" class="table-items tbl-section-computing" data-counter-computing="0">
            <thead>
              <tr>
                <th>Programa</th>
                <th>Nivel</th>
                <th>
                  <button id="btn-add-application" type="button" class="btn-add-item" >
                    Agregar
                  </button>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($computing_applications as $index => $row_application): ?>
                <tr>
                  <td>
                    <input type="text" name="computing[<?php echo $index; ?>][name]" class="form-control" value="<?php echo $row_application->name; ?>">
                    <input type="hidden" name="computing[<?php echo $index; ?>][type]" class="form-control" value="application">
                  </td>
                  <td>
                    <select name="computing[<?php echo $index; ?>][level]" class="form-control">
                      <?php $level = $row_application->level; ?>
                      <option value="">Nivel</option>
                      <option value="basic" <?php echo $level == 'basic' ? 'selected="selected"' : ''; ?>>Básico</option>
                      <option value="intermediate" <?php echo $level == 'intermediate' ? 'selected="selected"' : ''; ?>>Intermedio</option>
                      <option value="advanced" <?php echo $level == 'advanced' ? 'selected="selected"' : ''; ?>>Avanzado</option>
                    </select>
                  </td>
                  <td>
                    <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
                      <i class="glyphicon glyphicon-remove"></i>
                    </button>
                  </td>
                </tr>

              <?php endforeach; ?>  
            </tbody>
          </table>
        </div>      
      </div>
    </div>
  </div>
</div>