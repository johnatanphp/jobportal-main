<?php 
$languages = isset($languages) ? $languages : array();
?>
<div id="section-languages">
  <div class="step-title">
    Idiomas
  </div>
  <div class="step-inputs">
    <div>
      <table id="table-languages" class="table-items" data-counter-languages="0">
        <thead>
          <tr>
            <th>Idioma</th>
            <th>Lee</th>
            <th>Habla</th>
            <th>Escribe</th>
            <th>
              <button id="btn-add-language" type="button" class="btn-add-item">
                Agregar
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($languages as $index => $row_language): ?>
          <tr>
            <td>
              <input type="text" name="languages[<?php echo $index; ?>][name]" class="form-control" value="<?php echo $row_language->language_name; ?>">
            </td>
            <td>
              <select name="languages[<?php echo $index; ?>][reading_level]" class="form-control">
                <?php $reading_level = $row_language->reading_level; ?>
                <option value="">Nivel</option>
                <option value="basic" <?php echo $reading_level == 'basic' ? 'selected="selected"' : ''; ?>>Básico</option>
                <option value="intermediate" <?php echo $reading_level == 'intermediate' ? 'selected="selected"' : ''; ?>>Intermedio</option>
                <option value="advanced" <?php echo $reading_level == 'advanced' ? 'selected="selected"' : ''; ?>>Avanzado</option>
              </select>
            </td>
            <td>
              <select name="languages[<?php echo $index; ?>][speaking_level]" class="form-control">
                <?php $speaking_level = $row_language->speaking_level; ?>
                <option value="">Nivel</option>
                <option value="basic" <?php echo $speaking_level == 'basic' ? 'selected="selected"' : ''; ?>>Básico</option>
                <option value="intermediate" <?php echo $speaking_level == 'intermediate' ? 'selected="selected"' : ''; ?>>Intermedio</option>
                <option value="advanced" <?php echo $speaking_level == 'advanced' ? 'selected="selected"' : ''; ?>>Avanzado</option>
              </select>
            </td>
            <td>
              <select name="languages[<?php echo $index; ?>][writing_level]" class="form-control">
                <?php $writing_level = $row_language->writing_level; ?>
                <option value="">Nivel</option>
                <option value="basic" <?php echo $writing_level == 'basic' ? 'selected="selected"' : ''; ?>>Básico</option>
                <option value="intermediate" <?php echo $writing_level == 'intermediate' ? 'selected="selected"' : ''; ?>>Intermedio</option>
                <option value="advanced" <?php echo $writing_level == 'advanced' ? 'selected="selected"' : ''; ?>>Avanzado</option>
              </select>
            </td>
            <td>
              <button onclick="$(this).closest('tr').remove();" class="btn-remove-item">
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