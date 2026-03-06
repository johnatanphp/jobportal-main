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
  Se requiere que envies un video grabación para seguir con el proceso de reclutamiento y selección.
</div>
<ul>
    <li><b>Empresa:</b> <?php echo $employer->company_name; ?></li>
    <li><b>Puesto:</b> <?php echo $job->job_title; ?></li>
</ul>

<p class="wrapper-paragraph">
    <a href="<?php echo $url_link; ?>">Grabar video</a>
</p>

<div class="separator"></div>
