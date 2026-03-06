<div style="padding: 10px 0;">
	<div class="candidate-section-content">
		<h4 class="candidate-section-content-title">
			<?php e($document->name); ?>
		</h4>
	</div> 
	<div>
		<div>
			<?php if ($attached_files): ?>
				<div style="padding: 8px 0;">
					<h5 style="padding: 8px 0;"><b>Documentos adjuntos</b></h5>
					<?php foreach ($attached_files as $index => $file): ?>
			            <div class="attach-file-item"> 
			              <div class="row">
			                <div class="col-md-12">
			                  <a href="<?php echo file_url($file->file_source, 'public/uploads/employer/recruitment_selection_documents'); ?>" target="_blank">
			                    <?php e($file->document_title . ' - ' . $file->name); ?>
			                  </a>
			                </div> 
			              </div>
			            </div>
			        <?php endforeach ?>
				</div>
			<?php else: ?>
				Ningún resultado encontrado
			<?php endif; ?>
		</div>
	</div>
</div>