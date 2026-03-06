<style type="text/css">
    p {
        padding: 4px 0px;
        display: block;
        line-height: 1.5;
        font-size: 15px;
        color: #222;
    }

    h3 {
        color: #222;
        font-size: 17px;
    }

    .wrapper-paragraph {
        text-align: center;
        padding-top: 10px;
    }

    .wrapper-paragraph a {
        padding: 10px 15px;
        text-align: center;
        color:  #fff;
        background: #005da4;
        border-radius: 5px;
        font-weight: bold;
        text-decoration: none;
        font-size: 15px;
    }
</style>

<div style="padding: 10px 10px;">

Hola <?php echo $seeker->first_name; ?>,
<br />
<br />

Queremos informarte que tuvimos que cancelar su cita médica programada con fecha <?php echo date('d/m/Y', strtotime($exam_request_seeker->exam_date)); ?> a las <?php echo date('h:i a', strtotime($exam_request_seeker->exam_time)); ?> horas, para el puesto de <?php echo $job->job_title; ?>.

<br />
Le notificaremos nueva fecha de agenda a la brevedad.
<br />
<br />
Para mayor detalle puede comunicarse con el reclutador del proceso.
<br />
<br />
 
Equipo de Reclutamiento y Selección - Overall

<br />
<br />
Slds.

</div>

<div class="separator"></div>
