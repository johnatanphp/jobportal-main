<?php

# definimos los valores iniciales para nuestro calendario

//$month=date("n");

//$year=date("Y");

$diaActual=date("j");

 

# Obtenemos el dia de la semana del primer dia

# Devuelve 0 para domingo, 6 para sabado

$diaSemana=date("w",mktime(0,0,0,$month,1,$year))+7;

# Obtenemos el ultimo dia del mes

$ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1));

 

$meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",

"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


$anterior_mes = $month - 1;
$anterior_anio = $year;

if ($month == 1) {
  $anterior_mes = 12;
  $anterior_anio = $year - 1;
}

$siguiente_mes = $month + 1;
$siguiente_anio = $year;

if ($month == 12) {
  $siguiente_mes = 1;
  $siguiente_anio = $year + 1;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title;?></title>
<?php $this->load->view('admin/common/meta_tags'); ?>
<?php $this->load->view('admin/common/before_head_close'); ?>
<style type="text/css">
  
      #calendar {

      font-family:Arial;

      font-size:14px;
      width: 100%;

    }

    #calendar caption {

      text-align:left;

      padding:5px 10px;

      background-color:#003366;

      color:#fff;

      font-weight:bold;

    }

    #calendar th {

      background-color:#006699;

      color:#fff;

      width: 40px;
      height: 40px;

    }

    #calendar td {

      text-align:right;

      padding:2px 5px;

      background-color:#ccc;

      width: 40px;
      height: 40px;

    }

    #calendar .hoy {

      background-color:#999;
      color: #fff;

    }
</style>
</head>
<body class="skin-blue">
<?php $this->load->view('admin/common/after_body_open'); ?>
<?php $this->load->view('admin/common/header'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
<?php $this->load->view('admin/common/left_side'); ?>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Registrar días no laborales 
      <!--<small>advanced tables</small>--> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <!--<li><a href="#">Examples</a></li>-->
      <li class="active">Registrar días no laborales</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Días no laborales</h3>
            <!--Pagination-->
            <div class="paginationWrap"></div>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <div class="clearfix">&nbsp;</div>
            <table width="100%">
              <tr>
                <td width="15%" align="left">
                  <a href="<?php echo site_url('admin/gantt_activities/d/' . $anterior_anio . $anterior_mes);?>" class="btn btn-primary"><<</a>
                </td>
                <td width="70%" align="center">
                  <b><h4><?php echo $meses[$month]." ".$year?></h4></b>
                </td>
                <td width="15%" align="right">
                  <a href="<?php echo site_url('admin/gantt_activities/d/' . $siguiente_anio . $siguiente_mes);?>" class="btn btn-primary">>></a>
                </td>
              </tr>
            </table>
            <table id="calendar">

           <caption><?php echo $meses[$month]." ".$year?></caption>

  <tr>

    <th>Lun</th><th>Mar</th><th>Mie</th><th>Jue</th>

    <th>Vie</th><th>Sab</th><th>Dom</th>

  </tr>

  <tr bgcolor="silver">

    <?php

    $last_cell=$diaSemana+$ultimoDiaMes;

    // hacemos un bucle hasta 42, que es el máximo de valores que puede

    // haber... 6 columnas de 7 dias

    for($i=1;$i<=42;$i++)

    {

      if($i==$diaSemana)

      {

        // determinamos en que dia empieza

        $day=1;

      }

      if($i<$diaSemana || $i>=$last_cell)

      {

        // celca vacia

        echo "<td>&nbsp;</td>";

      }else{

        // mostramos el dia

        if($day==$diaActual && date('Y') == $year && date('m') == $month)

          echo "<td class='hoy'>$day</td>";

        else

          echo "<td>$day</td>";

        $day++;

      }

      // cuando llega al final de la semana, iniciamos una columna nueva

      if($i%7==0)

      {

        echo "</tr><tr>\n";

      }

    }

  ?>

  </tr>

</table>
          </div>
          
        
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
        <!-- /.box --> 
      </div>
    </div>
  </section>
  <!-- /.content --> 
</aside>
<!-- /.right-side -->
<?php $this->load->view('admin/common/footer'); ?>
