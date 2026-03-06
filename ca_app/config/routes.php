<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Frontend
$route['companies/(:any)'] = 'Companies/index/$1';
$route['jobs/(:any)'] = 'Job_details/index/$1';

//Search global
$route['jobs.html'] = 'Job_search/index';
$route['jobs-(:any).html'] = 'Job_search/index/$1';

//Search by cities
$route['(:any)/jobs.html'] = 'Job_search/index//$1';
$route['(:any)/jobs-(:any).html'] = 'Job_search/index/$2/$1';

//Login job seekers
$route['login'] = 'auth_seeker/login/$1';
$route['fb-login'] = 'auth_seeker/fb_login/$1';
$route['linkedin-login'] = 'auth_seeker/linkedin_login/$1';
$route['redirect-fb-login'] = 'auth_seeker/redirect_fb_login';
$route['redirect-linkedin-login'] = 'auth_seeker/redirect_linkedin_login';

//Login companies
$route['company_login'] = 'auth_employer/login/$1';

//Logout general
$route['logout'] = 'logout/index';

$route['forgot'] = 'User/forgot/$1';
$route['search-resume'] = 'Resume_search/index/$1';
$route['search-resume/(:any)'] = 'Resume_search/index/$1';
$route['search/(:any)'] = 'Search/index/$1';
//$route['candidate/(:any)'] = 'Candidate/index/$1';
$route['industries/(:any)'] = 'Industries/index/$1';
$route['employer-login'] = 'auth_employer/login/$1';
$route['employer-signup'] = 'Employer_signup';
$route['jobseeker-signup'] = 'Jobseeker_signup';

$route['contact-us'] = 'Contact_us';
$route['(:any).html'] = 'Content/index/$1';

//Employer Section
$route['employer/job_applications/im_interested_cv'] = 'employer/job_applications/im_interested_cv/';
$route['employer/job_applications/send_message_to_candidate'] = 'employer/job_applications/send_message_to_candidate/$1';
$route['employer/job_applications/(:any)'] = 'employer/job_applications/index/$1';
$route['employer/edit_posted_job/(:num)'] = 'employer/edit_posted_job/index/$1';
$route['employer/recruitment_processes/(:num)/(:num)'] = 'employer/recruitment_processes/index/$1/$2';
$route['employer/recruitment_processes/(:num)'] = 'employer/recruitment_processes/index/$1';
$route['mployer/staff_requests/show/(:num)/(:num)'] = 'mployer/staff_requests/show/$1/$2';
$route['employer/staff_request/gantt_activities/(:num)/(:num)'] = 'employer/staff_request/gantt_activities/index/$1/$2';
//nueva modificacion
$route['validate-job-title'] = 'employer/job_layouts/job_layouts/validate_job_title';
$route['validate-job-title-edit/(:num)']
    = 'employer/job_layouts/job_layouts/validate_job_title_edit/$1';
$route['validate-job-title-admin'] = 'admin/job_layouts/validate_job_title_admin';
$route['validate-job-title-edit-admin'] = 'admin/job_layouts/validate_job_title_edit_admin';

//Backend
$route['admin/employers/(:num)'] = 'admin/Employers/index/$1';
$route['admin/job_seekers/(:num)'] = 'admin/Job_seekers/index/$1';
$route['admin/posted_jobs/(:num)'] = 'admin/Posted_jobs/index/$1';
$route['admin/institutes/(:num)'] = 'admin/Institutes/index/$1';
$route['admin/pages/(:num)'] = 'admin/Pages/index/$1';
$route['admin/industries/(:num)'] = 'admin/Industries/index/$1';
$route['admin/qualifications/(:num)'] = 'admin/Qualifications/index/$1';
$route['admin/skills/(:num)'] = 'admin/Skills/index/$1';
$route['admin/countries/(:num)'] = 'admin/Countries/index/$1';
$route['admin/cities/(:num)'] = 'admin/Cities/index/$1';
$route['admin/prohibited_keyword/(:num)'] = 'admin/Prohibited_keyword/index/$1';
$route['admin/menus/load_menu_pages/(:num)'] = 'admin/Menus/load_menu_pages/$1';
$route['admin/mofs/(:num)'] = 'admin/Mofs/index/$1';
$route['admin/job_profiles/(:num)'] = 'admin/Job_profiles/index/$1';
$route['admin/staff_request_authorities/(:num)/(:num)'] = 'admin/staff_request_authorities/index/$1/$2';
$route['admin/staff_request_authorities/(:num)'] = 'admin/staff_request_authorities/index/$1';
$route['admin/laboral_benefits/(:num)'] = 'admin/laboral_benefits/index/$1';

//oauth2
$route['auth/authorize'] = 'oauth2/authorize';
