<?php 
    $row_user = $this->Employer->find($this->session->userdata('user_id'));
    $row_company = $this->Company->find($row_user->company_ID);

    $is_company_system_internal = $row_company->system_internal;
    $is_employer_admin = $row_user->is_admin == 'yes';
?>
<ul class="featurlist">
    <?php echo menu_item('employer/staff_request/my_staff_requests', 'Mis solicitudes creadas', 'library_books', ['employer/staff_request/staff_requests/show']); ?>
    <?php if (has_permission_module('staff_requests')): ?>
        <?php echo menu_item(
            'employer/staff_request/staff_requests/select_type_request', 
            'Crear solicitud', 
            'note_add', [
                'employer/staff_request/create_internal_staff_request', 
                'employer/staff_request/create_external_staff_request', 
                'employer/staff_request/create_model_3'
            ]
        ); 
    ?>
    <?php endif; ?>
    <?php echo menu_item('general/authorities/pending_staff_requests', 'Solicitudes sin autorizar', 'sms_failed'); ?>
    
    <?php echo menu_item('employer/job_layouts/job_layouts/create', 'Crear layout de puesto', 'note_add'); ?>
    <?php echo menu_item('employer/job_layouts/my_job_layouts', 'Layouts creados', 'receipt', ['employer/job_layouts/job_layouts/show', 'employer/job_layouts/job_layouts/edit']); ?>

    <?php if ($this->config->item('job_profile_module_enabled')): ?>
        <?php echo menu_item('employer/job_profiles/profiles/create', 'Crear perfil de puesto', 'note_add'); ?>
        <?php echo menu_item('employer/job_profiles/my_job_profiles', 'Perfiles creados', 'receipt', ['employer/job_profiles/profiles/show', 'employer/job_profiles/profiles/edit']); ?>
    <?php endif; ?>
    
    <?php if ($this->config->item('mof_module_enabled')): ?>
        <?php echo menu_item('employer/mofs/mofs/create', 'Crear MOF', 'note_add'); ?>
        <?php echo menu_item('employer/mofs/mofs/search', 'MOF creados', 'receipt', ['employer/mofs/mofs/show', 'employer/mofs/mofs/edit']); ?>
    <?php endif; ?>
    
    <?php if ($row_company->ID == 1 && $this->config->item('module_recruitment_tm')): ?>
        <?php echo menu_item('https://overall.limapixel.com', 'Reclutamiento TM', 'description'); ?>
    <?php endif; ?>
    
	<div class="clear"></div>
</ul>