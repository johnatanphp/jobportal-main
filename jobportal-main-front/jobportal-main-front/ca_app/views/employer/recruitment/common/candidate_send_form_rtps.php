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
      width: 86px;
      margin-bottom: 10px
    }
    @media (max-width: 600px) {
      .content {
        font-size: 12px;
        line-height: 26px;
        text-align: justify;
        padding: 10px 10px;
      }
    }
  </style>

<div class="section-rtps">
  <table width="100%" border="1" >
    <tr>
        <td>
          <img src="<?php echo base_url('public/images/overall_blue.png'); ?>">
        </td>
        <td align="center">
            <span class="section-rtps-title">
            DECLARACIÓN DE ACEPTACIÓN DE LA ENTREGA DE BOLETAS DE PAGO POR CORREO ELECTRONICO
            </span>
        </td>
        <td>
          <?php echo $form_rtps->version; ?>
        </td>
    </tr>
  </table>
</div>
<?php
  // Obtener la fecha actual
  $day = date("d");
  $month = date("F");
  $year = date("Y");

  $months = array(
    "January" => "ENERO",
    "February" => "FEBRERO",
    "March" => "MARZO",
    "April" => "ABRIL",
    "May" => "MAYO",
    "June" => "JUNIO",
    "July" => "JULIO",
    "August" => "AGOSTO",
    "September" => "SEPTIEMBRE",
    "October" => "OCTUBRE",
    "November" => "NOVIEMBRE",
    "December" => "DICIEMBRE"
  );
  $month = $months[$month];
?>
<div class="content">
  <span>
    El suscrito (a), <b><?php echo mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name); ?></b>,
    identificado (a) con Documento de Identidad N° <b><?php echo $form_rtps->document_number; ?></b>,
    actuando en nombre y representación propia en calidad de trabajador de la empresa
    <b><?php echo $company->name; ?></b>, en el marco del Decreto Supremo
    N° 009-2011-TR y en aplicación del Artículo 3° del Decreto Legislativo N° 1310, manifiesto que
    con la firma de la presente declaración acepto y autorizo a mi empleador a que remita mis
    boletas de pagos a mi correo electrónico personal: <b><?php echo $form_rtps->email; ?></b>,
    de manera mensual y permanente, hasta la conclusión de mi vínculo laboral. Asimismo, declaro que he sido
    debidamente capacitado sobre el procedimiento del envío y recepción de mis boletas de pago a través de mi
    correo electrónico, el mismo que me comprometo a verificar mensualmente.
  </span>
  <br /><br />
  <span>En señal de conformidad y aceptación, firmo en la ciudad de <b>LIMA</b> a los <b><?php echo $day; ?></b> días del mes de <b><?php echo $month; ?></b> del año <b><?php echo $year; ?></b>.</span>
</div>

<table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 50px; margin-bottom: 40px;">
  <tr>
    <td style="width: 50%; text-align: center; vertical-align: bottom; font-size: 14px;">
      <div>
        <span class="signature-line">
            <?php echo mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name); ?>
        </span>
      </div>
    </td>
    <td style="width: 25%;"></td>
    <td style="width: 25%; vertical-align: bottom; font-size: 14px;">
    </td>
  </tr>
</table>
