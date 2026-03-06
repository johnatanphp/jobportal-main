<?php 
  $job_functions = isset($job_functions) ? $job_functions : array();   
?>
<div id="section-job-functions">
  <div class="step-title">
    Funciones específicas del puesto (***) <span id="text-guide-function">(<span class="text-verb">Verbo</span> + <span class="text-object">Objeto</span> + <span class="text-result">Resultado</span>)</span>
  </div>
  <div class="step-inputs">
    <div id="content-info-eg" >Ejemplo: <span class="text-verb">Analizar</span> <span class="text-object">las hojas de vida de los postulantes</span> <span class="text-result">para clasificarlos según su cumplimiento de perfil<span></div>
    <div>
      <div class="js-error-functions has-error" ></div>
      <table id="table-functions" class="table-items">
        <thead>
          <tr>
            <th width="80%">
              Función <span style="color: red;">*</span>
            </th>
            <th>
              <button id="btn-add-function" type="button" class="btn-add-item">Agregar</button>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($job_functions as $index => $row_function): ?>
          <tr>
            <td>
              <input type="text" name="functions[]" class="form-control" value="<?php echo $row_function->function; ?>">
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