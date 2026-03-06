<?php 
$additional_competences = isset($additional_competences) ? $additional_competences : array();
$fixed_competences = isset($fixed_competences) ? $fixed_competences : array();
?>
<div id="section-competences">
  <div class="step-title">
    Competencias
  </div>
  <div class="step-inputs">
    <div style="max-width:500px;margin:0 auto;">

      <div style="padding: 10px 0px;text-align: right;"><button id="btn-add-additional-competence" type="button" class="btn-add-item">Agregar competencia</button></div>
      <table id="table-competences">
        <tbody>
          <?php foreach ($additional_competences as $row_competence): ?>
          <tr>
            <td>
              <input type="text" name="additional_competences[]" class="form-control" value="<?php echo $row_competence->competence_name; ?>">          
            </td>
            <td>
              <button type="button" onclick="$(this).closest('tr').remove();" class="btn-remove-item">
              <i class="glyphicon glyphicon-remove"></i>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>

          <?php foreach ($fixed_competences as $row_competence): ?>
          <tr class="row-additional-competence">
            <td>
              <?php echo $row_competence->competence_name; ?>          
            </td>
          </tr>
          <?php endforeach; ?>

        </tbody>
      </table> 
    </div>
  </div>
</div>