<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Comentarios recibidos</h4>
		</div>
		<div class="modal-body">
			<div>
				<?php 
					$comments = nl2br($rs_document->comments);
				?>
				<?php if ($comments != ''): ?>
					<?php echo $comments; ?>
				<?php else: ?>
					<span style="font-style: italic;">
						¡No tienes comentarios recibidos!
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
