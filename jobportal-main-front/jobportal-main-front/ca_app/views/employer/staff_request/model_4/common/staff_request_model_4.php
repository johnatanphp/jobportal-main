<?php 
  if (!isset($export_to_pdf)) 
  {
    $export_to_pdf = false;
  }

  function check_empty($str = '')
  {
    $str = (string)$str;
    if (trim($str) == '') {

      return '<span style="font-style: italic;color:#777;">Sin especificar</span>';
    }
    return $str;
  }

  $job_template = null;
  $job_template_benefits = null;

  if (@$job_profile) {
    $job_template = $job_profile;
    $job_template_benefits = $jp_benefits;
  }

  if (@$job_layout) {
    $job_template = $job_layout;
    $job_template_benefits = $jl_benefits;
  }
?>

<div>
  <style>
    body {
      color: #333;
    }

    .col-xs-4 {
      width: 33.33333333%;
      position: relative;
      min-height: 1px;
      padding-right: 15px;
      padding-left: 15px;
      float: left;
    }

    .col-xs-7 {
      width: 58.33333333%;
      position: relative;
      min-height: 1px;
      padding-right: 15px;
      padding-left: 15px;
      float: left;
    }

    .section-title {
      border-bottom: 1px solid #999;
      margin-bottom: 10px;
      font-size: 19px;
      text-transform: uppercase;
    }

    .content-function {
      background: #eee;
      padding: 6px 8px;
      margin-bottom: 8px;
      border-radius: 5px;
    }
    
    .tbl-data tr td {
      vertical-align: top;
      padding: 4px 3px;
    }
    
    .tbl-data tr th {
      padding: 6px;
      background: #fff;
      color: #555;
      border-bottom: 1px solid #ccc;
    }

    .tbl-data tr:nth-child(even) {
      background-color: #fff;
    }

    .tbl-data tr:nth-child(odd) {
      background-color: #f5f5f5;
    }
    
    .tbl-data-2 tr td {
      padding: 6px;
    }
    
    .text-value-diff {
      text-decoration: line-through;
    }
  </style>
  <!-- Descripcion de la empresa -->
  <div id="section-descriptions-company">
    <div>
      <h3 class="section-title">
        Descripción de la empresa
      </h3>
    </div>
    <div>
      <table class="tbl-data" width="100%">
        <?php if ($request->type_expense != null): ?>
          <tr>
            <td width="33%">
              <label>Tipo de egreso</label>
            </td>
            <td>
              <?php echo check_empty($request->type_expense); ?>    
            </td>
          </tr>
        <?php endif; ?>
        <tr>
          <td width="33%">
            <label>Nombre de la empresa cliente</label>
          </td>
          <td>
            <?php echo check_empty($request->client_company_name); ?>    
          </td>
        </tr>
        <tr>
          <td width="33%">
            <label>Consultora</label>
          </td>
          <td>
            <?php echo check_empty($request->consultant_name); ?>    
          </td>
        </tr>
      </table>  
    </div>
  </div>
  <!-- End Descripción del puesto -->
  <!-- Descripcion del puesto -->
  <div id="section-job-descriptions">
    <div>
      <h3 class="section-title">
        Descripción del puesto 
    </div>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <td width="33%">
            <label>Nombre del puesto</label>
          </td>
          <td><?php echo $request->job_title; ?></td>
        </tr>
        <tr>
          <td width="33%">
            <label>Número de vacantes</label>
          </td>
          <td>
            <?php echo $request->vacancies; ?>    
          </td>
        </tr>
      </table>
    </div>
  </div>
  <!-- End Descripción del puesto -->

  <!-- Start Requisitos del puesto -->
  <div id="section-job-requirements" style="display: none;">
    <h3 class="section-title">
      Requisitos del puesto 
    </h3>
    <?php 
      $level_education = $this->Qualification->get_record_by_id(@$job_template->study_grade_req);
      $specialization_or_diploma = $this->Qualification->get_record_by_id(@$job_template->study_grade_min);
      $level_education = trim($level_education['text']);
      $specialization_or_diploma = trim($specialization_or_diploma['text']);
    ?>
    <div>
      <table class="tbl-data" width="100%">
        <tr>
          <td width="33%">
            <label>Profesión preferentes </label>
          </td>
          <td>
            <?php echo check_empty($job_template ? $job_template->education_req_detail : ''); ?>
          </td>
        </tr>
        <tr>
          <td width="33%"><label>Nivel de educación</label></td>
          <td><?php echo check_empty($level_education); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Tiempo mínimo de experiencia para el puesto</label></td>
          <td><?php echo check_empty(@$this->Work_experience->find(['code' => $job_template->experience])->name); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Experiencia previa en </label></td>
          <td><?php echo check_empty($job_template ? $job_template->experience_detail : ''); ?></td>
        </tr>
        <tr>
          <td width="33%"><label>Sexo</label></td>
          <td>
            <?php 
              $gender_list = [
                'male' => 'Hombre',
                'female' => 'Mujer',
                'both' => 'Ambos'
              ];
            ?>
            <?php echo check_empty(isset($gender_list[$request->gender]) ? $gender_list[$request->gender] : $request->gender); ?>
          </td>
        </tr>
        <tr>
          <td width="33%"><label>Especialización/Diplomado</label></td>
          <td><?php echo check_empty($specialization_or_diploma); ?></td>
        </tr>
       
      </table>
    </div>
  </div>
  <!-- End Requisitos del puesto -->

</div>

<?php if ($export_to_pdf == false): ?>
  <div id="modal-warning-salary-changes" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Valor cambiado</h4>
        </div>
        <div class="modal-body">
          Este valor del perfil laboral ha cambiado y no se tomara en cuenta como referencia.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>