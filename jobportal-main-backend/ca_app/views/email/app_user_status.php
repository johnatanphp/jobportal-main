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

<h3>Hola <?php echo $app_user->first_name; ?></h3>

¡Tu cuenta de empresa, se ha <?php echo $app_user_sts == 'active' ? 'Activado' : 'Bloqueado'; ?>!

<br />
<br />
Cuenta
<ul>
    <li>Usuario: <?php echo $app_user->email; ?></li>
    <li>Nombre: <?php echo $app_user->first_name; ?></li>
    <li>Empresa: <?php echo $company->company_name; ?></li>
</ul>
<?php if ($app_user_sts == 'active'): ?>
    <br />
    <br />
    <?php $url_login = site_url('login'); ?>
    <p class="wrapper-paragraph">
        <a href="<?php echo $url_login; ?>">Comenzar</a>
    </p>
<?php endif; ?>

<div class="separator"></div>