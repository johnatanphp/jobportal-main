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

    .section-rtps-info {
      font-weight: bold;
      font-size: 14px;
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
    <a id="export-excel-form-rtps" style="margin: 8px 4px;display:none;" class="btn btn-primary btn-xs pull-right" href="#" download="Planilla-RTPS-<?php echo $form_rtps->first_name; ?>.xls">
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
          <td>
            <?php echo $form_rtps->version; ?>
          </td>
      </tr>
    </table>
  </div>

  <div style="font-size: 13px;margin:7px 0;">Declaro bajo juramento la veracidad de la información proporcionada en el presente documento. El cual suscribo en señal de conformidad y aceptación, mediante firma digital de acuerdo a lo regulado por la Ley N° 27269 y su reglamento, aprobado por el Decreto Supremo No. 052-2008-PCM, firma que es válida y que garantizan mi real y auténtica voluntad.</div>
  <div style="font-size: 13px;margin:7px 0;">De conformidad con lo Dispuesto en la Ley Nº 28882 de Simplificación de la Certificación Domiciliaria, en su Artículo 1º. DECLARO BAJO JURAMENTO que el domicilio descrito en este documento corresponde a mi domicilio actual.</div>

  <div class="section-rtps">
    <table class="personal-data-table" border="1" width="100%">
      <tr>
          <th colspan="6">
            DATOS PERSONALES
          </th>
      </tr>
      <tr>
          <td width="30%">
            <b>Apellidos y Nombres:</b>
          </td>
          <td colspan="5">
            <?php echo mb_strtoupper(trim($form_rtps->first_name . ' ' . $form_rtps->second_name . ' ' . $form_rtps->third_name) . ' ' . $form_rtps->last_name); ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Tipo de documento de identidad:</b>
          </td>
          <td>
            <?php echo document_type_text($form_rtps->document_type); ?>
          </td>
          <td>
            <b>N°:</b>
          </td>
          <td colspan="3">
            <?php echo $form_rtps->document_number; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Fecha de nacimiento:</b>
          </td>    
          <td width="20%">
            <?php echo format_date($form_rtps->birthdate, 'd/m/Y'); ?>
          </td>
          <td>
            <b>Lugar de nacimiento:</b>
          </td>
          <td colspan="3">
            <?php echo $form_rtps->place_birth; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Departamento:</b>
          </td>    
          <td colspan="5">
            <?php echo $form_rtps->born_department; ?>
          </td>    
      </tr>
      <tr>
          <td width="30%">
            <b>Provincia:</b>
          </td>    
          <td colspan="2">
            <?php echo $form_rtps->born_province; ?>
          </td>
          <td>
            <b>Distrito:</b>
          </td>
          <td colspan="2">
            <?php echo $form_rtps->born_district; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Indicar Nacionalidad:</b>
          </td>
          <td colspan="2">
            <?php echo ucfirst(citizen_text($form_rtps->nationality) ?? ''); ?>
          </td>
          <td>
            <b>Domiciliado:</b>
          </td>
          <td colspan="2" class="text-center">
            <b>SI ( <?php echo ($form_rtps->domiciled == 1) ? 'X' : ''; ?> )</b>&nbsp;&nbsp;
            <b>NO ( <?php echo ($form_rtps->domiciled == 0) ? 'X' : ''; ?> )</b>
          </td>
      </tr>
      <tr>
        <td width="30%">
          <b>Sexo:</b>
        </td>
        <td colspan="2" class="text-center">
          <b>M ( <?php echo ($form_rtps->gender == 1) ? 'X' : ''; ?> )</b>&nbsp;&nbsp;
          <b>F ( <?php echo ($form_rtps->gender == 2) ? 'X' : ''; ?> )</b>
        </td>
        <td rowspan="2">
          <b>Discapacidad: </b>
        </td>
        <td rowspan="2" colspan="2">
          &nbsp;<b>SI ( <?php echo ($form_rtps->disability == 1) ? 'X' : ''; ?> )</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>¿De qué tipo? : </b>
            <?php if ($form_rtps->disability): ?>  
              &nbsp;
              <?php echo disability_text($form_rtps->disability_type); ?>
            <?php endif; ?> 
            <br />
            <b>NO ( <?php echo ($form_rtps->disability == 0) ? 'X' : ''; ?> )</b>
        </td>
      </tr>
      <tr>
        <td width="30%">
          <b>Estado civil:</b>
        </td>
        <td colspan="2">
          <?php echo civil_status_text($form_rtps->civil_status); ?>
        </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Teléfono domicilio</b>
          </td>
          <td colspan="2">
            <?php echo $form_rtps->home_phone; ?>
          </td>
          <td colspan="3">
            <b>N° de Hijos: </b> 
            <?php
              $n_children = $this->Jobseeker_form_rtps->count_rightful_claimant_children_by_form_id(
                $form_rtps->ID
              );
              echo $n_children;
            ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>E-Mail: </b>
          </td>
          <td colspan="2">
            <?php echo $form_rtps->email; ?>
          </td>
          <td colspan="3">
            <b>Celular: </b> <?php echo $form_rtps->cell_phone; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Domicilio: </b>
          </td>
          <td colspan="5">
            <?php echo $form_rtps->domicile; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Departamento: </b>
          </td>
          <td colspan="2">
            <?php echo $form_rtps->department; ?>
          </td>
          <td width="20%">
            <b>Provincia: </b>
          </td>
          <td colspan="2">
            <?php echo $form_rtps->province; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Distrito:</b>
          </td>
          <td colspan="2">
            <?php echo $form_rtps->district; ?>
          </td>
          <td width="20%">
            <b>Referencia:</b>
          </td>
          <td colspan="2">
            <?php echo $form_rtps->reference; ?>
          </td>
      </tr>
      <tr>
          <td width="30%">
            <b>Nivel educativo:</b>
          </td>
          <td colspan="5" class="text-center">
            <?php $lvl = $form_rtps->level_education; ?>
            <b>
              Superior ( <?php echo ($lvl == 'Superior') ? 'X' : ''; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;
              Técnico ( <?php echo ($lvl == 'Ténico') ? 'X' : ''; ?> ) &nbsp;&nbsp;&nbsp;&nbsp;
              Otros Indicar: ( <?php echo ($lvl == 'Otros') ? 'X' : ''; ?> )
            </b>
          </td>
      </tr>
      <tr>
          <td><b>Institución educativa:</b></td>
          <td colspan="5"><?php echo $form_rtps->degree_obtained_institution; ?></td>
      </tr>
      <tr>
          <td><b>Año de egreso:</b></td>
          <td colspan="5"><?php echo $form_rtps->degree_obtained_year ? $form_rtps->degree_obtained_year : ''; ?></td>
      </tr>
      <tr>
          <td width="30%">
            <b>Especialidad o carrera:</b>
          </td>
          <td colspan="5">
            <?php echo $form_rtps->specialty; ?>
          </td>
      </tr>
      <?php if ($form_rtps->driver_license != null): ?>
          <tr>
            <td width="30%">
              <b>Licencia de conducir:</b>
            </td>
            <td colspan="5">
              <?php $driver = $form_rtps->driver_license; ?>
              <b>
                NO ( <?php echo ($driver == '0') ? 'X' : ''; ?> )
                <br />
                SI ( <?php echo ($driver == '1') ? 'X' : ''; ?> )
                <span style="margin-left: 40px;">Tipo: <?php echo $form_rtps->driver_license_type; ?></span>
              </b>
            </td>
          </tr>
      <?php endif; ?>
    </table>
  </div>

  <?php if ($form_rtps->fifth_category_income != null): ?>
    <div class="section-rtps">
      <table width="100%" border="1">
        <tr>
          <td>
            <b>¿Ha recibido ingresos de 5ta categoría (trabajo en planillas) en el presente año?</b><br />
            <?php $fifth_category_income = $form_rtps->fifth_category_income; ?>
            <table width="100%" cellpadding="10">
              <tr>
                  <td width="45%" align="right">
                    <br />
                    <b>SI ( <?php echo ($fifth_category_income == '1') ? 'X' : ''; ?> )</b><br /><br /><br />
                  </td>
                  <td width="55%">
                    <br />
                    <b>NO ( <?php echo ($fifth_category_income == '0') ? 'X' : ''; ?> )</b>
                    <br />
                    <b>DECLARO BAJO JURAMENTO: No haber percibido ingresos de 5ta<br />
                    categoría correspondientes al presente año hasta la fecha.</b>
                  </td>
              </tr>
            </table>
            <br />
          </td>
        </tr>
      </table>
    </div>
  <?php endif; ?>
  <pagebreak />

  <div style="font-size: 13px; margin: 10px 0px;">
    <p style="font-size: 13px;font-weight: bold;">
    Desde el 1 de Abril de 2018, los nuevos trabajadores que opten por incorporarse al Sistema Privado de Pensiones
    (SPP), serán afiliados obligatoria y automáticamente a AFP correspondiente a este año.
    </p>
    <p style="font-size: 13px;font-weight: bold;">
      Si actualmente no se encuentra en ningún Sistema de Pensiones y elegí el privado (AFP), se le afiliará
    por default en PROFUTURO que a partir de este año es la AFP que corresponde.
    </p>
  </div>

  <div class="section-rtps">
    <?php
      $pension_affiliated = $form_rtps->pension_affiliated;
      $pension_name = $form_rtps->pension_name;
      $pension_type = $form_rtps->pension_type;
      $pension_change = $form_rtps->pension_change;
      $pension_change_type = $form_rtps->pension_change_type;
      $pension_join_name = $form_rtps->pension_join_name;
      $pension_join_type = $form_rtps->pension_join_type;
    ?>
    <table width="50%">
      <tr>
        <td width="60%"></td>
        <td>SI</td>
        <td>NO</td>
      </tr>
      <tr>
        <td width="60%">Se encuentra afiliado algún sistema <br> de pensiones:</td>
        <td>(<?php echo $pension_affiliated ? 'X' : ' '; ?>)</td>
        <td>(<?php echo !$pension_affiliated ? 'X' : ' '; ?>)</td>
      </tr>
    </table>
    <br>
    <table width="100%" border="0">
      <tr>
        <td width="50%" style="vertical-align: top;border: 1px solid #333;">
          <table width="100%" border="0">
            <tr>
              <td colspan="2"><b>Completar si tu respuesta es SI</b></td>
            </tr>
            <tr>
              <td width="50%">SNP</td>
              <td> (<?php echo $pension_name == 'SNP' ? 'X' : ' '; ?>)</td>
            </tr>
            <tr>
              <td width="50%">AFP</td>
              <td>
                <table width="100%">
                    <tr>
                      <td width="50%">Hábitat</td>
                      <td>(<?php echo $pension_name == 'AFP' && $pension_type == 'HABITAT' ? 'X' : ' '; ?>)</td>
                    </tr>
                    <tr>
                        <td>Prima</td>
                        <td>(<?php echo $pension_name == 'AFP' && $pension_type == 'PRIMA' ? 'X' : ' '; ?>)</td>
                    </tr>
                    <tr>
                        <td>Profuturo </td>
                        <td>(<?php echo $pension_name == 'AFP' && $pension_type == 'PROFUTURO' ? 'X' : ' '; ?>)</td>
                    </tr>
                    <tr>
                        <td>Integra</td>
                        <td>(<?php echo $pension_name == 'AFP' && $pension_type == 'INTEGRA' ? 'X' : ' '; ?>)</td>
                    </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>

        <td width="50%" style="vertical-align: top;border: 1px solid #333;">
          <table width="100%" border="0">
              <tr>
                <td colspan="2"><b>Completar si tu respuesta es NO</b></td>
              </tr>
              <tr>
                <td width="50%" style="vertical-align: top;">Elijo afiliarme voluntariamente a:</td>
                <td style="vertical-align: top;">
                  <table width="100%">
                      <tr>
                        <td width="55%">SNP</td>
                        <td>(<?php echo $pension_join_name == 'SNP' ? 'X' : ' '; ?>)</td>
                      </tr>
                      <tr>
                        <td width="55%">AFP PROFUTURO</td>
                        <td>(<?php echo $pension_join_name == 'AFP' && $pension_join_type == 'PROFUTURO' ? 'X' : ' '; ?>)</td>
                      </tr>
                  </table>
                </td>
              </tr>
          </table>
        </td>
      </tr>
    </table>

    <?php 
      $pension_type_from = 'SNP';

      if ($pension_affiliated && $pension_name == 'AFP') {
        $pension_type_from = $pension_type;
      }
    ?>
    <table width="50%" style="margin-top: 10px;">
      <tr>
        <td>
          <b>Deseas cambiarte de <?php echo $pension_type_from; ?> a AFP Profuturo: SI (<?php echo $pension_affiliated && $pension_change == '1' && $pension_change_type == 'PROFUTURO' ? 'X' : '&nbsp;'; ?>)  &nbsp; NO (<?php echo  $pension_affiliated && $pension_change == '0' ? 'X' : '&nbsp;'; ?>)</b>
        </td>
      </tr>
    </table>
  
    <table class="table-jubilado" border="1" style="margin: 30px 0px">
      <tr>
        <td>
          <b>¿Se encuentra jubilado?</b>
        </td>
        <td>
          <b>SI (   )</b>
        </td>
        <td>
        <b>NO (   )</b>
        </td>
      </tr> 
    </table>
  </div>

  <div style="font-size: 15px;">
    <p style="font-size: 13px;font-weight: bold;">
      El Trabajador comunicará dentro de los diez días iniciados el vínculo, el nombre de la empresa financiera.
      Caso contrario se podrá abonar en cualquier entidad bancaria. Decreto Supremo No 003-2010- TR
    </p>
  </div>
  <br>
  <div class="section-rtps">
    <table width="100%" border="1">
      <tr>
        <th colspan="6">PAGO DE HABERES</th>
      </tr>
      <tr>
        <td colspan="5">
          <b>Entidad Bancaria (depósito):</b> <?php echo $form_rtps->bank_name; ?>
        </td>
        <td colspan="1">
          <b>Nº de Cuenta:</b> <?php echo $form_rtps->bank_account_number; ?>
        </td>
      </tr>
      <!-- <tr>
        <td>
          <b>Depósito en cuenta:</b> SI
        </td>
        <td colspan="5">
          <b>Cheque:</b> NO
        </td>
      </tr> -->
      <tr>
        <td colspan="6">
          <table width="100%">
            <tr>
              <td width="30%"><b>Tipo de cuenta:</b></td>
              <td width="20%"><b>Ahorros ( <?php echo ($form_rtps->bank_account_type == 'Ahorro') ? 'X' : ''; ?> )</b></td>
              <td width="20%"><b>Otros ( <?php echo ($form_rtps->bank_account_type != 'Ahorro') ? 'X' : ''; ?> )</b></td>
              <td width="30%"><b>Especificar:</b> <?php echo ($form_rtps->bank_account_type != 'Ahorro') ? $form_rtps->bank_account_type : ''; ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>

  <div class="section-rtps">
    <table width="100%" border="1">
      <tr>
        <th>PAGO DE CTS</th>
      </tr>
      <tr>
        <td>
          <b>Entidad Bancaria (depósito):</b> <?php e($form_rtps->payment_cts_bank_name); ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php 
            $currency_list = [
              'PEN' => 'Soles',
              'USD' => 'Dólares'
            ];
          ?>
          <b>Tipo de Moneda:</b> <?php e(isset($currency_list[$form_rtps->payment_cts_currency]) ? $currency_list[$form_rtps->payment_cts_currency] : ''); ?>
        </td>
      </tr>
    </table>
  </div>

  <?php if (count($rtps_rightful_claimants) > 0): ?>
    <div class="section-rtps">
      <table width="100%" border="1">
        <tr>
          <th colspan="5">DATOS DE LOS DERECHOHABIENTES</th>
        </tr>
        <?php foreach ($rtps_rightful_claimants as $key => $person) : ?>
          <?php if ($key > 0): ?>
            <tr height="15" bgcolor="#eeeeee"><td colspan="5"></td></tr>
          <?php endif; ?>
          <tr>
            <td width="30%">
              <b>Apellidos y Nombre</b>
            </td>
            <td colspan="4">
              <?php echo $person->first_name . ' ' . $person->last_name; ?>
            </td>
          </tr>
          <tr>
            <td width="30%">
              <b>Tipo de documento de identidad</b>
            </td>
            <td width="20%">
              <?php echo document_type_text($person->document_type); ?>
            </td>
            <td width="20%">
              <b>N°</b>
            </td>
            <td colspan="2">
              <?php echo $person->document_number; ?>
            </td>
          </tr> 
          <tr>
            <td width="30%">
              <b>Fecha de nacimiento</b>
            </td>    
            <td width="20%">
              <?php echo format_date($person->birthdate, 'd/m/Y'); ?>
            </td>
            <td width="20%">
              <b>Lugar de nacimiento</b>
            </td>
            <td colspan="2">
              <?php echo $person->place_birth; ?>
            </td>
          </tr>
          <tr>
          <td width="30%">
              <b>Sexo</b>
            </td>    
            <td width="20%">
              <?php echo $person->gender ? ($person->gender == 2 ? 'Femenino' : 'Masculino') : ''; ?>
            </td>
            <td>
              <b>Vínculo familiar</b>
            </td>
            <td colspan="2">
              <?php echo $person->kinship_name; ?>
            </td>
          </tr>
          <!-- <tr>
            <td width="30%">
              <b>Certificado Tipo</b>
            </td>    
            <td colspan="4">
              <?php echo $person->kinship_cert_name; ?>
            </td>
          </tr>
          <tr>
            <td width="30%">
              <b>Certificado Código</b>
            </td>    
            <td colspan="4">
              <?php echo $person->kinship_cert_code; ?>
            </td>
          </tr> -->

          <?php if ($person->kinship == 4 || $person->kinship == 5): //Hijo ?>
            <tr>
              <td width="30%">
                <b>Municipalidad que emitió la partida de nacimiento</b>
              </td>
              <td colspan="4">
                <?php echo $person->place_birth_certificate; ?>
              </td>
            </tr>
          <?php endif; ?>

          <tr>
            <td width="30%">
              <b>Vive en el mismo domicilio?</b>
            </td>
            <td colspan="4">
              <?php if ($person->live_same_domicile != null): ?>
                <?php echo $person->live_same_domicile ? 'SI' : 'NO'; ?>
              <?php endif; ?>
            </td>
          </tr>

          <?php if ($person->live_same_domicile == 0): ?>
            <tr>
              <td width="30%">
                <b>Indicar domicilio si no vive con el trabajador</b>
              </td>
              <td colspan="4">
                <?php echo $person->ubigeo . ' - ' . $person->domicile; ?>                 
              </td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </table>
    </div>
  <?php endif; ?>

  <div class="section-rtps">
      <table width="100%" border="0">
        <tr>
          <td colspan="3" align="left">
            <b>NOTA:</b> <br />
            <b>ADJUNTAR COPIA DE DOCUMENTO DE IDENTIDAD:</b> <br />
          </td>
          <td colspan="3" align="right">
            <b>FECHA: <?php echo date('d/m/Y', strtotime($form_rtps->creation_date)); ?></b> 
          </td>
        </tr>
      </table>
  </div>

  <div style="font-size:13px;margin: 7px 0;" > Realizo la presente declaración jurada manifestando que la información proporcionada es verdadera y autorizo la verificación de todo lo declarado.</div>
  <div style="font-size:13px;margin: 7px 0;">En caso de falsedad declaro haber incurrido en el delito Contra La Fé Pública, falsificación de Documentos, (Artículo 427º del Código Penal, en concordancia con el Artículo IV inciso 1.7) “Principio de Presunción de Veracidad” del Título Preliminar de la Ley de Procedimiento Administrativo General, Ley Nº 27444.</div>
</div>