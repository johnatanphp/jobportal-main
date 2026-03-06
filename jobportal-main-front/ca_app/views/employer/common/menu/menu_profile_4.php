<ul class="featurlist">
	<?php echo menu_item('employer/rys_forms/export', 'Reporte Formulario', 'assignment'); ?>
	<?php if ($this->config->item('job_layouts_module_enabled')): ?>
		<?php echo menu_item('employer/job_layouts/my_job_layouts', 'Layouts creados', 'receipt', ['employer/job_layouts/job_layouts/show']); ?>
	<?php endif; ?>
	<?php echo menu_item('employer/mofs/mofs/search', 'MOF creados', 'receipt', ['employer/mofs/mofs/show']); ?>
	<?php echo menu_item('employer/job_profiles/my_job_profiles', 'Perfiles creados', 'receipt', ['employer/job_profiles/profiles/show']); ?>
	<div class="clear"></div>
</ul>