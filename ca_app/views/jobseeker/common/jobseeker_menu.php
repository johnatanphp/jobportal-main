<ul class="featurlist">
    <li>
        <a href="<?php echo base_url('jobseeker/my_account');?>" class="innerfetbox <?php echo is_active_like($this->uri->segment(2),'my_account');?>"><i class="material-icons">&#xE851;</i> <span>Mi perfil</span></a>
    </li>
    <li>
        <a href="<?php echo base_url('jobseeker/cv_manager');?>" class="innerfetbox <?php echo is_active_like($this->uri->segment(2),'cv_manager');?> <?php echo is_active_like($this->uri->segment(2),'cv_builder');?>"><i class="material-icons">&#xE85D;</i> <span>Mi currículum</span></a>
    </li>
    <?php if (candidate_is_process_contracting()): ?>
        <li><a href="<?php echo base_url('jobseeker/requested_documents');?>" class="innerfetbox <?php echo is_active_like($this->uri->segment(2),'requested_documents');?>"><i class="material-icons">folder_shared</i> <span>Documentos solicitados</span></a>
        </li>
    <?php endif; ?>
    <li>
        <a href="<?php echo base_url('jobseeker/my_forms');?>" class="innerfetbox <?php echo (is_active_like($this->uri->segment(2), 'my_forms') == '' ? is_active_like($this->uri->segment(2), 'forms') : 'active'); ?>"><i class="material-icons">list_alt</i> <span>Encuestas</span>
        </a>
    </li>
    <li>
        <a href="<?php echo base_url('jobseeker/my_jobs');?>" class="innerfetbox <?php echo is_active_like($this->uri->segment(2),'my_jobs');?>"><i class="material-icons">&#xE065;</i> <span>Mis postulaciones</span></a>
    </li>
    <li>
        <a href="<?php echo base_url('jobseeker/matching_jobs');?>" class="innerfetbox <?php echo is_active_like($this->uri->segment(2),'matching_jobs');?>"><i class="material-icons">&#xE24C;</i> <span>Sugerencias de Empleos</span></a>
    </li>
    <li>
        <a href="<?php echo base_url('jobseeker/add_skills');?>" class="innerfetbox <?php echo is_active_like($this->uri->segment(2),'add_skills');?>"><i class="material-icons">&#xE54B;</i> <span>Mis Habilidades</span></a>
    </li>
    <li>
        <a href="<?php echo base_url('jobseeker/settings');?>" class="innerfetbox <?php echo is_active_like($this->uri->segment(2),'settings');?>"><i class="material-icons">build</i> <span>Ajustes</span></a>
    </li>
    
    <div class="clear"></div>
</ul>
