<style type="text/css">
	.body-content {
		background:#f4f4f4;
		border-radius: 8px;
		border:1px solid #e9e9e9;
		padding: 15px;
		text-align: left;
	}

	.body-content span {
		padding: 5px 0px;
		display: block;
		line-height: 1.5;
	}

	.reason {
		font-style: italic;
	}
</style>
		
<p><b>Hola <?php echo $name ?>:</b></p>
<p>
	El personal de RRHH te ha dejado un comentario.
	<br />
	<br />
	<b style="font-size: 13px;"><?php echo $document; ?>:</b>
</p>

<div class="body-content">
	<span class="reason">
		<?php echo nl2br($comments); ?>
	</span>
</div>

<div class="separator"></div>