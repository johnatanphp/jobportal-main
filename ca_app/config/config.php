<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Ensure constants are loaded before anything else
if (file_exists(APPPATH.'config/constants.php'))
{
	require_once(APPPATH.'config/constants.php');
}

// Fallback for SITE_URL if constants.php failed or was not present
if (!defined('SITE_URL')) {
    define('SITE_URL', 'http://localhost:5000/');
}

$config['base_url']     = SITE_URL;
$config['index_page'] = '';
$config['uri_protocol'] = 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']     = 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = TRUE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = FCPATH . 'vendor/autoload.php';
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-@=';
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['allow_get_array'] = TRUE;
$config['log_threshold'] = 1;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = 'gd345fgdfgdfg456dfgd$6767^3f';
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'coo_sess';
$config['sess_table_name']              = 'tbl_sessions';
$config['sess_expiration'] = 86400;
$config['sess_save_path'] = '/tmp/ci_sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
$config['cookie_prefix']        = '';
$config['cookie_domain']        = '';
$config['cookie_path']          = '/';
$config['cookie_secure']        = TRUE;
$config['cookie_httponly']      = TRUE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_jpd_name';
$config['csrf_cookie_name'] = 'csrf_jpdf_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';
