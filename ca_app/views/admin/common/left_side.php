<?php
  $user_admin_id = $this->session->userdata('admin_id');
  $is_user_admin = $user_admin_id == 1;
  $permission_module_mofs = $is_user_admin || $user_admin_id == 2;
  $permission_module_job_profiles = $is_user_admin || $user_admin_id == 2;
  $permission_module_job_layouts = $is_user_admin || $user_admin_id == 2;
  $permission_module_job_seeker = $is_user_admin || $user_admin_id == 3;
?>
<aside class="left-side sidebar-offcanvas"> 
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar"> 
    <!-- Sidebar user panel -->
    <ul class="sidebar-menu">
      <?php if ($is_user_admin): ?>
        <li> <a href="<?php echo base_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
      <?php endif; ?>

      <?php if ($permission_module_job_seeker): ?>
        <li> <a href="<?php echo base_url('admin/job_seekers');?>"><i class="fa fa-angle-double-right"></i><span>Manage Jobseekers</span> </a> </li>
      <?php endif; ?>
        
      <?php if ($is_user_admin): ?>
        <li> 
          <a href="<?php echo base_url('admin/companies');?>"><i class="fa fa-angle-double-right"></i><span>Companies</span> </a>
        </li>
        <li> <a href="<?php echo base_url('admin/posted_jobs');?>"><i class="fa fa-angle-double-right"></i><span>Manage Posted Jobs</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/pages');?>"><i class="fa fa-angle-double-right"></i><span>CMS</span> </a> </li>        
        <li> <a href="<?php echo base_url('admin/invite_employer');?>"><i class="fa fa-angle-double-right"></i><span>Invite Employer</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/invite_jobseeker');?>"><i class="fa fa-angle-double-right"></i><span>Invite Jobseeker</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/industries');?>"><i class="fa fa-angle-double-right"></i><span>Manage Job Industries</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/institutes');?>"><i class="fa fa-angle-double-right"></i><span>Manage Institute</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/salary');?>"><i class="fa fa-angle-double-right"></i><span>Manage Salary</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/qualifications');?>"><i class="fa fa-angle-double-right"></i><span>Manage Qualification</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/ads');?>"><i class="fa fa-angle-double-right"></i><span>Manage Ads</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/countries');?>"><i class="fa fa-angle-double-right"></i><span>Manage Countries</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/cities');?>"><i class="fa fa-angle-double-right"></i><span>Manage Cities</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/prohibited_keyword');?>"><i class="fa fa-angle-double-right"></i><span>Manage Prohibited Keywords</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/skills');?>"><i class="fa fa-angle-double-right"></i><span>Manage Skills</span> </a> </li>
      <?php endif; ?>
        
      <?php if ($permission_module_job_layouts && $this->config->item('job_layouts_module_enabled')): ?>
        <li>
          <a href="<?php echo base_url('admin/job_layouts');?>"><i class="fa fa-angle-double-right"></i><span>Gestionar Layouts de puesto</span> </a>
        </li>
      <?php endif; ?>

      <?php if ($permission_module_mofs): ?>
        <li>
          <a href="<?php echo base_url('admin/mofs');?>"><i class="fa fa-angle-double-right"></i><span>Gestionar MOF</span> </a>
        </li>
      <?php endif; ?>

      <?php if ($permission_module_job_profiles): ?>
        <li>
          <a href="<?php echo base_url('admin/job_profiles');?>"><i class="fa fa-angle-double-right"></i><span>Gestionar Perfil laboral</span> </a>
        </li>
      <?php endif; ?>
      
      <?php if ($is_user_admin): ?>
        <li> <a href="<?php echo base_url('admin/staff_request_authorities');?>"><i class="fa fa-angle-double-right"></i><span>Gestionar Autoridades</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/laboral_benefits');?>"><i class="fa fa-angle-double-right"></i><span>Beneficios laborales</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/config');?>"><i class="fa fa-angle-double-right"></i><span>Configuración</span> </a> </li>
      <?php endif; ?>
      <li> <a href="<?php echo base_url('admin/home/logout');?>"><i class="fa fa-angle-double-right"></i><span>Logout</span> </a> </li>
    </ul>
  </section>
  <!-- /.sidebar --> 
</aside>
