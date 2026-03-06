<div style="padding: 10px 0;">
	<div class="candidate-section-content">
		<h4 class="candidate-section-content-title">
			<?php echo $document->name . ' (' . count($attached_files) . ')'; ?>
		</h4>
	</div> 
	<div>
		<div>
			<?php if ($attached_files): ?>
				<div style="padding: 8px 0;">
					<?php foreach ($attached_files as $index => $file): ?>
			            <div class="attach-file-item"> 
			              <div class="row">
			                <div class="col-md-12">
								<?php if ($file->file_source): ?>
									<a href="<?php echo file_url($file->file_source, 'public/uploads/employer/recruitment_selection_documents'); ?>" target="_blank">
										<?php echo $file->document_title ? $file->document_title . ' - ' . $file->name : $file->name; ?>
									</a>
								<?php endif; ?>
							</div> 
			              </div>
			            </div>
			        <?php endforeach ?>
				</div>
			<?php else: ?>
				<div style="text-align: center;margin-top: 3em;opacity: 0.4;">
					<svg width="100px" height="100px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M18.5 20C18.5 20.275 18.276 20.5 18 20.5H12.2678C11.9806 21.051 11.6168 21.5557 11.1904 22H18C19.104 22 20 21.104 20 20V9.828C20 9.298 19.789 8.789 19.414 8.414L13.585 2.586C13.57 2.57105 13.5531 2.55808 13.5363 2.5452C13.5238 2.53567 13.5115 2.5262 13.5 2.516C13.429 2.452 13.359 2.389 13.281 2.336C13.2557 2.31894 13.2281 2.30548 13.2005 2.29207C13.1845 2.28426 13.1685 2.27647 13.153 2.268C13.1363 2.25859 13.1197 2.24897 13.103 2.23933C13.0488 2.20797 12.9944 2.17648 12.937 2.152C12.74 2.07 12.528 2.029 12.313 2.014C12.2933 2.01274 12.2738 2.01008 12.2542 2.00741C12.2271 2.00371 12.1999 2 12.172 2H6C4.896 2 4 2.896 4 4V11.4982C4.47417 11.3004 4.97679 11.1572 5.5 11.0764V4C5.5 3.725 5.724 3.5 6 3.5H12V8C12 9.104 12.896 10 14 10H18.5V20ZM13.5 4.621L17.378 8.5H14C13.724 8.5 13.5 8.275 13.5 8V4.621Z" fill="#212121"/>
					<path d="M12 17.5C12 20.5376 9.53757 23 6.5 23C3.46243 23 1 20.5376 1 17.5C1 14.4624 3.46243 12 6.5 12C9.53757 12 12 14.4624 12 17.5ZM2.5 17.5C2.5 18.3335 2.75495 19.1075 3.19112 19.7482L8.74822 14.1911C8.10751 13.755 7.33353 13.5 6.5 13.5C4.29086 13.5 2.5 15.2909 2.5 17.5ZM6.5 21.5C8.70914 21.5 10.5 19.7091 10.5 17.5C10.5 16.6665 10.245 15.8925 9.80888 15.2518L4.25178 20.8089C4.89249 21.245 5.66647 21.5 6.5 21.5Z" fill="#212121"/>
					</svg>
					<div style="padding: 1em 0;font-size: 14px;color: #000;">No hay documentos</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
