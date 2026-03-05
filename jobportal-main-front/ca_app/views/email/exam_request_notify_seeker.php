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

<h3>Hola <?php echo $seeker->first_name; ?>,</h3>

<div style="padding: 10px 10px;">
    Tienes una cita, por favor debes ir al centro médico y fecha programada.
    <br />
    <ul>
        <li>Prueba para: <?php echo $exam_document->name; ?></li>
        <li>Empleo: <?php echo $job->job_title; ?></li>
        <li>
            Centro médico: <?php echo $medical_center->name . ($medical_center_location ? ' - ' . $medical_center_location->location : ''); ?>
        </li>
        <li>Dirección: <?php echo $medical_center_direction; ?></li>
        <li>
            Fecha: <?php echo date('d/m/Y', strtotime($exam_request_seeker->exam_date)); ?>
        </li>
        <li>Hora: <?php echo date('h:i A', strtotime($exam_request_seeker->exam_time)); ?></li>
    </ul>
</div>

<div class="separator"></div>
