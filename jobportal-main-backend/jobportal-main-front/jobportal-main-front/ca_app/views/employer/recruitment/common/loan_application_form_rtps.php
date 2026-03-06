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
    font-weight: bold;
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
    padding-left: 40px;
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
<div class="section-rtps">
  <div class="document">
    <h4 class="title">SOLICITUD DE PRESTAMO</h4>
    <span>Quien suscribe, <span class="highlight"><?php echo mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name); ?></span>, identificado con DNI y/o C.E N° <span class="highlight"><?php echo $form_rtps->document_number; ?></span>, domiciliado en <span class="highlight"><?php echo $form_rtps->domicile; ?></span>, solicito a la empresa <span class="highlight"><?php echo $company->name; ?></span>, identificado con RUC N° <span class="highlight"><?php echo $company->ruc; ?></span>, un préstamo para cubrir los gastos administrativos, en relación a la documentación que debo presentar a la empresa, en el proceso de selección en el que me encuentro.</span>
    
    <span>Por lo cual, la empresa antes mencionada me hace entrega del monto total de S/ <span class="highlight">25</span>, para los fines antes señalados.</span>
    
    <h4 class="title" style="margin-top: 20px;">AUTORIZACIÓN PARA REALIZAR EL DESCUENTO DEL PRESTAMO</h4>
    <span>Quien suscribe, <span class="highlight"><?php echo mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name); ?></span>, identificado con DNI y/o C.E N° <span class="highlight"><?php echo $form_rtps->document_number; ?></span>, domiciliado en <span class="highlight"><?php echo $form_rtps->domicile; ?></span>, autorizo a la empresa <span class="highlight"><?php echo $company->name; ?></span>, identificado con RUC N° <span class="highlight"><?php echo $company->ruc; ?></span>, a efectuar el descuento del monto de S/ <span class="highlight"> 25 </span>, por el préstamo realizado.</span>
    
    <span>La empresa podrá descontar el monto antes mencionado de mis ingresos mensuales.</span>
    
    <span>En señal de conformidad, se firma la solicitud de préstamo y autorización, a los <b><?php echo $day; ?></b> días del mes de <b><?php echo $month; ?></b> del año <b><?php echo $year; ?></b></span>
    
    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 50px; margin-bottom: 40px;">
      <tr>
        <td style="width: 20%; text-align: left; vertical-align: bottom; font-size: 14px;">
            <div align="center">
              <span>__________________________________________</span><br />
              <span><?php echo mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name); ?></span><br />
              <span>DNI, C.E Nro. <span class="highlight"><?php echo $form_rtps->document_number; ?></span></span><br /><br />
            </div>
        </td>
        <td style="width: 80%;"></td>
      </tr>
    </table>
  </div>
</div>
