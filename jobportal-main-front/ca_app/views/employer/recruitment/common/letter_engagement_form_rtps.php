<?php 
  $is_session_rrhh = $this->session->userdata('current_profile_id') == 3;
?>
<style style="text/css">
  .document {
    font-size: 14px;
    line-height: 28px;
    text-align: justify;
    margin-top: 20px;
    padding: 10px 48px;
  }

  .title {
    text-align: center;
    text-transform: uppercase;
    text-decoration: underline;
    margin-bottom: 30px;
  }

  .highlight {
    font-weight: bold;
  }

  ul {
    margin-top: 0;
    padding-left: 20px;
  }

  .signature-section {
    text-align: left;
    margin-top: 40px;
  }

  .signature-section span {
    margin: 5px 0;
  }
  .text-signature {
    padding-left: 30px;
  }
  .text-signature2 {
    padding-left: 70px;
  }
  @media (max-width: 600px) {
    .document {
      font-size: 12px;
      line-height: 26px;
      text-align: justify;
      padding: 10px 10px;
    }
  }
</style>

<div class="section-rtps">
  <div class="document">
    <h4 class="title">CARTA COMPROMISO</h4>
    <span>Por medio del presente, yo, <span class="highlight"><?php echo mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name); ?></span> con DNI <span class="highlight"><?php echo $form_rtps->document_number; ?></span> en mi calidad de <span class="highlight">PUESTO DE TRABAJO</span>, 
      tomo conocimiento que mi empleador <span class="highlight"><?php echo $company->name; ?></span> con R.U.C. <span class="highlight"><?php echo $company->ruc; ?></span> efectuará abonos en mi cuenta de ahorros por los siguientes conceptos, 
      con la finalidad de efectuar gastos operativos que mi labor demanda:
    </span>
    <ul style="margin-bottom: 20px;">
        <li><b>Caja chica</b></li>
        <li><b>Viáticos</b></li>
        <li><b>Movilidades</b></li>
        <li><b>Compras menores diversas por proyecto.</b></li>
    </ul>
    <span>
      Asimismo, me comprometo a realizar la respectiva rendición de gastos en un plazo máximo de 7 días útiles después de haber sido realizado el abono en mi cuenta, presentando al área 
      administrativa las facturas y comprobantes de pago debidamente autorizados por la consultora que represento. En caso de no efectuar dicha rendición hasta el último día del mes en que se 
      realizó el abono en mi cuenta, o de ocurrir algún faltante, por medio de esta carta autorizo voluntariamente a mi empleador <span class="highlight"><?php echo $company->name; ?></span> a que efectué los descuentos 
      respectivos de mi remuneración mensual y/o liquidación beneficios sociales, considerando el abono realizado en mi cuenta como un Adelanto de mi Remuneración.
    </span>

    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 50px; margin-bottom: 40px;">
      <tr>
        <td style="width: 20%; text-align: left; vertical-align: bottom; font-size: 14px;">
          <div align="center">
            <span>..............................................................</span><br />
            <span><?php echo mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name); ?></span><br />
            <span>DNI: <span class="highlight"><?php echo $form_rtps->document_number; ?></span></span><br /><br />
          </div>
        </td>
        <td style="width: 80%;"></td>
      </tr>
    </table>
  </div>
</div>
