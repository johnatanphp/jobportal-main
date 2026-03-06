<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
    'function' => 'enabled_logs_db',
    'filename' => 'logs.php',
    'filepath' => 'hooks',
);

$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'load_config_site',
    'filename' => 'setup.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'load_company_account_settings',
    'filename' => 'setup.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'load_jobseeker_account_settings',
    'filename' => 'setup.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Jobseeker_Login_Hook',
    'function' => 'validate_jobseeker_login',
    'filename' => 'jobseeker_login_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Jobseeker_validate_data_hook',
    'function' => 'validate',
    'filename' => 'jobseeker_validate_data_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Admin_Login_Hook',
    'function' => 'validate_admin_login',
    'filename' => 'admin_login_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Admin_check_privileges_user_hook',
    'function' => 'index',
    'filename' => 'admin_check_privileges_user_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Employer_login_hook',
    'function' => 'validate',
    'filename' => 'employer_login_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Employer_check_profile_hook',
    'function' => 'validate',
    'filename' => 'employer_check_profile_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Employer_check_privileges_hook',
    'function' => 'index',
    'filename' => 'employer_check_privileges_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class' => 'Jobseeker_forms_hook',
    'function' => 'validate',
    'filename' => 'jobseeker_forms_hook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Validate_token_embed',
    'function' => 'index',
    'filename' => 'validate_token_embed.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Geo_visitor_hook',
    'function' => 'check',
    'filename' => 'geo_visitor_hook.php',
    'filepath' => 'hooks'
);
/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
