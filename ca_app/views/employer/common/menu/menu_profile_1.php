<?php 
    $row_user = $this->Employer->find($this->session->userdata('user_id'));
    $row_company = $this->Company->find($row_user->company_ID);

    $is_company_system_internal = $row_company->system_internal;
    $is_employer_admin = $row_user->is_admin == 'yes';
?>
<ul id="site-menu" class="featurlist">
    <?php if ($is_employer_admin): ?>
        <?php echo menu_item('employer/edit_company', 'Perfil de la empresa', '&#xE7F1;'); ?>
    <?php endif; ?>
    
    <?php if ($row_company->ID == 1): ?>
        <?php echo menu_item('employer/overall_employees', 'Consulta empleados Overall', '&#xE0BA;'); ?>
    <?php endif; ?>
    
    <?php if ($row_company->ID == 1 && has_permission_module('screening')): ?>
        <?php echo menu_item('employer/screening/search', 'Consulta Screening', 'archive', ['employer/screening/batch']); ?>
    <?php endif; ?>

    <?php if ($row_company->ID == 1 && false): ?>
        <?php echo menu_item('employer/recruitment_jobseeker_fits/Jobseeker_fits/search', 'Candidatos Aptos', '&#xE0BA;'); ?>
    <?php endif; ?>
    
    <?php if ($is_company_system_internal): ?>
        <?php echo menu_item('employer/all_staff_requests', 'Todas las solicitudes', 'directions', ['employer/staff_requests/cancel', 'employer/staff_requests/show']); ?>
    <?php endif; ?>

    <?php if ($is_company_system_internal): ?>
        <?php echo menu_item('employer/my_assigned_staff_requests', 'Mis solicitudes asignadas', 'archive'); ?>
    <?php endif; ?>

    <?php if ($is_company_system_internal): ?>
        <?php echo menu_item('employer/staff_requests_follow_up', 'Solicitudes en seguimiento', 'ballot'); ?>
    <?php endif; ?>

    <?php if ($is_company_system_internal): ?>
        <?php echo menu_item('general/authorities/pending_staff_requests', 'Solicitudes sin autorizar', 'sms_failed'); ?>
    <?php endif; ?>

    <?php echo menu_item('employer/post_new_job', 'Publicar nuevo empleo', '&#xE89C;'); ?>    
    <?php echo menu_item('employer/my_posted_jobs', 'Administrar empleos', '&#xEB3F;', ['employer/edit_posted_job']); ?>

    <?php if ($is_employer_admin && $is_company_system_internal): ?>
        <?php echo menu_item('employer/rys_forms/search', 'RyS Formularios', 'description', ['employer/rys_forms']); ?>
    <?php endif; ?>
    
    <?php echo menu_item('employer/recruitment/process_jobs', 'Reclutamiento', '&#xE065;', ['employer/recruitment_processes', 'employer/entry_job_seekers']); ?>
    
    <?php if ($row_company->ID == 1 && $this->config->item('module_recruitment_tm')): ?>
        <?php echo menu_item('https://overall.limapixel.com', 'Reclutamiento TM', 'description'); ?>
    <?php endif; ?>
    
    <?php if ($is_employer_admin): ?>
        <?php echo menu_item('employer/users/list_users/search', 'Gestionar usuarios', 'person_pin', ['employer/users']); ?>
    <?php endif; ?>

    <?php if ($is_company_system_internal): ?>
        <?php echo menu_item('employer/operational_reports/reports', 'Reportes Operativos', 'description'); ?>
        <?php echo menu_item('employer/settings', 'Ajustes', 'build'); ?>
    <?php endif; ?>
    
    <div class="clear"></div>
</ul>
