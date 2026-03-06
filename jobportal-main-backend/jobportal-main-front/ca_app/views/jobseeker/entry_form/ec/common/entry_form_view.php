<?php 
  $is_session_rrhh = $this->session->userdata('current_profile_id') == 3;
?>
  <style style="text/css">

    table {
      border-spacing: 0;
      border-collapse: collapse;
    }
    
    .section-rtps {
      padding: 8px 0;
      font-size: 14px;
    }

    .section-rtps table tr th {
      padding: 6px;
      background: #e0e0e0;
      font-size: 12px;
      text-align: left;
    }

    .section-rtps table tr td {
      padding: 3px 4px;
      font-size: 11px;
    }

    .section-rtps-title {
      font-size: 15px;
      text-align: center;
      display: block;
    }
    .content {
      font-size: 16px;
      line-height: 36px;
      text-align: justify;
      margin-top: 20px;
      padding: 10px 44px;
    }
    .signature {
      margin-top: 50px;
      text-align: center;
      font-size: 14px;
      display: flex;
      justify-content: space-around;
      margin-bottom: 40px;
      align-items: end;
    }
    .signature div {
      text-align: center;
    }
    .signature p {
      margin: 5px 0;
    }
    .signature-line {
      border-top: 1px solid #000;
      padding: 0px 20px;
    }
    .fingerprint {
      height: 100px;
      width: auto;
      border: 1px solid #000;
      margin-bottom: 10px
    }
    .signature-section {
      text-align: left;
      margin-top: 40px;
    }

    .signature-section span {
      margin: 5px 0;
    }
    .table-jubilado {
      width: 47%;
    }
    .personal-data-table {
      width: 100%;
      table-layout: fixed;
      word-wrap: break-word;
      border-collapse: collapse;
    }

    .personal-data-table th, 
    .personal-data-table td {
        word-wrap: break-word;
        white-space: normal;
        padding: 8px;
        text-align: left;
    }

    .personal-data-table th {
      background-color: #f2f2f2;
    }

    .personal-data-table td.text-center {
        text-align: center;
    }

    .flex-row {
      display: flex;
      flex-wrap: wrap;
      margin: 10px;
    }
    .full-width {
      width: 100%;
    }
    .half-width {
      width: 100%;
    }
    .align-right {
      text-align: right;
    }
    .bold-text {
      font-weight: bold;
    }
    @media (min-width: 768px) {
      .half-width {
        width: 50%;
      }
    }

    @media (max-width: 600px) {
      .content {
        font-size: 12px;
        line-height: 26px;
        text-align: justify;
        padding: 10px 10px;
      }
      .table-jubilado {
        width: 100%;
      }
    }
  </style>

  <div>
  <?php if ($is_session_rrhh): ?>
    <a id="export-excel-entry-form" style="margin: 8px 4px;display:none;" class="btn btn-primary btn-xs pull-right" href="#" download="Planilla-RTPS-<?php echo $entry_form->first_name; ?>.xls">
      Exportar a excel
    </a>
  <?php endif; ?>
  <div class="section-rtps">
    <table width="100%" border="1" >
      <tr>
        <td>
          <img src="<?php echo base_url('public/images/overall_blue.png'); ?>">
        </td>
        <td align="center">
            <span class="section-rtps-title">
              DECLARACIÓN JURADA DE INFORMACIÓN PERSONAL DEL TRABAJADOR
            </span>
        </td>
      </tr>
    </table>
  </div>

  <div class="section-rtps">
    <table class="personal-data-table" border="1" width="100%">
      <tr>
          <th colspan="6">
            DATOS PERSONALES
          </th>
      </tr>
      <tr>
          <td width="30%">
            <b>Nombres y Apellidos</b>
          </td>
          <td colspan="5">
            <?php 
              $candidate_name = trim($entry_form->first_name . ' ' . $entry_form->second_name  . ' ' . $entry_form->third_name);
              $candidate_last_name = trim($entry_form->paternal_last_name . ' ' . $entry_form->maternal_last_name);
            ?>
            <?php echo mb_strtoupper(trim($candidate_name . ' '  . $candidate_last_name)); ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Tipo de documento de identidad</b>
          </td>
          <td>
            <?php echo document_type_text($entry_form->identity_document_type_id); ?>
          </td>
          <td>
            <b>N°:</b>
          </td>
          <td colspan="3">
            <?php echo $entry_form->identity_document_number; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Fecha de nacimiento</b>
          </td>    
          <td width="20%">
            <?php echo format_date($entry_form->birthdate, 'd/m/Y'); ?>
          </td>
          <td>
            <b>Sexo</b>
          </td>
          <td colspan="3">
            <b>M ( <?php echo ($entry_form->gender_id == 1) ? 'X' : ''; ?> )</b>&nbsp;&nbsp;
            <b>F ( <?php echo ($entry_form->gender_id == 2) ? 'X' : ''; ?> )</b>
          </td>
      </tr>
      <tr>
        <td width="30%">
          <b>E-Mail</b>
        </td>
        <td colspan="5">
          <?php echo $entry_form->email; ?>
        </td>
      </tr>
      <tr>
        <td width="30%">
          <b>Estado civil</b>
        </td>
        <td colspan="2">
          <?php echo civil_status_text($entry_form->civil_status_id); ?>
        </td>
        <td>
          <b>Celular</b>
        </td>
        <td colspan="2">
          <?php echo $entry_form->mobile_phone; ?>
        </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Dirección</b>
          </td>
          <td colspan="5">
            <?php echo $entry_form->address; ?>
          </td>
      </tr>
    </table>
  </div>
  <br>
  <?php if ($entry_form->origin_country_id != '49'): ?>
    <div class="section-rtps">
      <table class="personal-data-table" border="1" width="100%">
        <tr>
          <th width="100%" colspan="4">
            LUGAR DE ORIGEN
          </th>
        </tr>
        <tr>
          <td width="20%"><b>Eres Extranjero</b></td>
          <td colspan="3"><?php e($entry_form->origin_country_id != '49' ? 'SI' : 'NO') ?></td>
        </tr>
        <tr>
          <td width="20%"><b>País</b></td>
          <td><?php  e(country_text($entry_form->origin_country_id)); ?></td>
          <td width="20%"><b>Tipo de visa</b></td>
          <td><?php e($entry_form->visa_type); ?></td>
        </tr>
      </table>
    </div>
    <br>
  <?php endif; ?>
  <div class="section-rtps">
    <table class="personal-data-table" border="1" width="100%">
      <tr>
        <th width="100%" colspan="6">
          SEGURIDAD SOCIAL
        </th>
      </tr>
      <tr>
        <td width="20%"><b>Número</b></td>
        <td colspan="5"><?php e($entry_form->social_security_number); ?></td>
      </tr>
    </table>
  </div>
  <br>
  <?php if (count($rightful_claimants) > 0): ?>
    <div class="section-rtps">
      <table width="100%" border="1">
        <tr>
          <th colspan="5">DATOS DE LOS DERECHOHABIENTES</th>
        </tr>
        <?php foreach ($rightful_claimants as $key => $person) : ?>
          <?php if ($key > 0): ?>
            <tr height="15" bgcolor="#eeeeee"><td colspan="5"></td></tr>
          <?php endif; ?>
          <tr>
            <td width="30%">
              <b>Apellidos y Nombre</b>
            </td>
            <td colspan="4">
              <?php e(trim($person->first_name . ' ' . $person->paternal_last_name . ' ' . $person->maternal_last_name)); ?>
            </td>
          </tr>
          <tr>
            <td width="30%">
              <b>Parentesco</b>
            </td>
            <td colspan="4">
              <?php e(trim($person->kinship_name)); ?>
            </td>
          </tr>
   
        <?php endforeach; ?>
      </table>
    </div>
  <?php endif; ?>

  <div class="section-rtps">
      <table width="100%" border="0">
        <tr>
          <td colspan="1" align="right">
            <b>FECHA: <?php echo date('d/m/Y', strtotime($entry_form->created_at)); ?></b> 
          </td>
        </tr>
      </table>
  </div>
</div>