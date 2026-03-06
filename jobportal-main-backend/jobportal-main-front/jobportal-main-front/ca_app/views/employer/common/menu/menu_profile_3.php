<ul class="featurlist">
	<?php //echo menu_item('employer/recruitment_entry/process_entry_list', 'Lista Procesos', 'library_books', ['employer/recruitment_entry/process_entry_list']); ?>
	<?php echo menu_item('employer/recruitment_entry/entry_list', 'Lista Candidatos', 'library_books', ['employer/recruitment_entry/recruitment_candidates/show_process']); ?>
	<?php if (has_permission_module('recruitment_tray')): ?>
		<?php echo menu_item('employer/recruitment_tray/client_list', 'Bandeja reclutamiento', 'library_books', ['employer/recruitment_tray/client_list']); ?>
	<?php endif; ?>
	<div class="clear"></div>
</ul>