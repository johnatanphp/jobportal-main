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

<h3>Hola <?php echo $name; ?>,</h3>
<p>Felicitaciones has sido seleccionado para el puesto <b>"<?php echo $job->job_title; ?>"</b>, por favor envíanos los siguientes documentos para que puedas ingresar y trabajar con nosotros.</p>
<div>
	<ul>
		<?php foreach ($documents as $key => $doc): ?>

			<?php if (!$doc->document_id) {
					continue;
				}	
			?>
			<li><?php e($doc->name); ?></li>
		<?php endforeach; ?>
				
		<?php foreach ($seeker_forms as $assignment_form): ?>
			<li><?php echo ' Declaración Jurada: ' . $assignment_form->form_name; ?></li>
		<?php endforeach; ?>
	</ul>
</div>

<p class="wrapper-paragraph">
	<a href="<?php echo $url_link; ?>">Enviar documentos</a>
</p>

<div class="separator"></div>