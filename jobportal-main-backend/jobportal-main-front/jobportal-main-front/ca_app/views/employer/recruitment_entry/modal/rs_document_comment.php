<div class="modal fade" role="dialog">
	<div class="modal-dialog">
		
		<?php echo form_open('employer/recruitment_entry/recruitment_candidates/save_document_comments', array('id' => 'form-rs-document-comments')); ?>
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Comentarios</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>">
					<input type="hidden" name="document_id" value="<?php echo $document_id; ?>">
					<input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
					
					<?php if (!is_null($ref_id)): ?>
						<input type="hidden" name="ref_id" value="<?php echo $ref_id; ?>">
					<?php endif; ?>
					<textarea id="txt-comments" name="comments" class="form-control" rows="8"><?php echo $rs_document ? $rs_document->comments : ''; ?></textarea>
				</div>
				<div class="modal-footer">
					<button id="btn-send-comments" type="submit" class="btn btn-primary">
						Enviar comentario
					</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$( "#form-rs-document-comments" ).submit(function(e) {

		e.preventDefault();

		if ($.trim($( "#txt-comments" ).val()) == '') {
			toastr["error"]("¡Por favor ingrese un comentario!");
			return;
		}
		
		$( "#btn-send-comments" ).prop('disabled', true);

		var data = $(this).serialize();
		var url = $(this).prop('action');

		$.post(url, data, function(result) {
			if (result.success) {
				$( ".modal" ).modal('hide');
				toastr["success"]("¡Comentario enviado al candidato!");
				return;
			}

			toastr["error"]("¡Error al enviar el comentario!");
			$( "#btn-send-comments" ).prop('disabled', false);

		}, 'json');

		return false;
	});
</script>