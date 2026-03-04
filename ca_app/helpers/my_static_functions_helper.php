<?php
if (!function_exists('date_formats')) {
    function date_formats($dated, $format='%m-%d-%Y') {
        $dated = str_replace('/', '-', (string)$dated);
        return ucwords(date($format, strtotime($dated)));
    }
}

if (!function_exists('currency_format')) {
    function currency_format($number) {
       
       $formatted=number_format($number,2,'.','');
        return $formatted;
    }
}

if (!function_exists('encode_json')) {
    function encode_json($string) {
       
       $encoded=json_encode($string);
        return $encoded;
    }
}

if (!function_exists('decode_json')) {
    function decode_json($string) {
       
       $decoded=json_decode($string);
        return $decoded;
    }
}

if (!function_exists('only_numeric')) {
    function only_numeric($value) {
       
       $formatted = preg_replace('#[^0-9]#i', '', $value);
        return $formatted;
    }
}

if (!function_exists('only_numeric')) {
    function only_numeric($value) {
       
       $formatted = json_encode('#[^0-9]#i', '', $value);
        return $formatted;
    }
}

//To remove any blank space from a string
if (!function_exists('remove_spaces')) {
    function remove_spaces($value) {
       
       $formatted = preg_replace('/\s+/', '', $value);
        return $formatted;
    }
}

if (!function_exists('replace_string')) {
    function replace_string($replace,$with,$string) {
       
       $string = str_replace($replace, $with, $string);
        return $string;
    }
}

if (!function_exists('url_encode')) {
    function url_encode($string) {
       
       $string = urlencode($string);
        return $string;
    }
}

if (!function_exists('url_decode')) {
    function url_decode($string) {
       
       $string = urldecode($string);
        return $string;
    }
}

if (!function_exists('generate_random_password')) {
    
    function generate_random_password(){
        
      $data    = "ABCDEFGHJKLMNPQRSTUVWXYZ2345";
      $Random  = substr($data, (rand()%(strlen($data))), 1);
      $Random .= substr($data, (rand()%(strlen($data))), 1);
      $Random .= substr($data, (rand()%(strlen($data))), 1);
      $Random .= substr($data, (rand()%(strlen($data))), 1);
      $Random .= substr($data, (rand()%(strlen($data))), 1);
      $Random .= substr($data, (rand()%(strlen($data))), 1);
      $Random .= substr($data, (rand()%(strlen($data))), 1);
      $Random .= substr($data, (rand()%(strlen($data))), 1);
      $pass    = $Random;
      return $pass; 
    }
}

if (!function_exists('my_encrypt')) {
    function my_encrypt($string, $key='onlinedating3') {
      $result = '';
      for($i=0; $i<strlen($string); $i++) {
       $char = substr($string, $i, 1);
       $keychar = substr($key, ($i % strlen($key))-1, 1);
       $ordChar = ord($char);
       $ordKeychar = ord($keychar);
       $sum = $ordChar + $ordKeychar;
       $char = chr($sum);
       $result.=$char;
      }
      return base64_encode($result);
     }
 }
 
if (!function_exists('my_decrypt')) {
    function my_decrypt($string, $key='onlinedating3') {
      $result = '';
      $string = base64_decode($string);
      for($i=0; $i<strlen($string); $i++) {
       $char = substr($string, $i, 1);
       $keychar = substr($key, ($i % strlen($key))-1, 1);
       $ordChar = ord($char);
       $ordKeychar = ord($keychar);
       $sum = $ordChar - $ordKeychar;
       $char = chr($sum);
       $result.=$char;
      }
      return $result;
     }
}

if (!function_exists('validate_data')) {
  function is_number_exist($str)
  {
      $Nmsg='';
      $strlength=strlen($str);
//    echo $strlength; exit;
      for($i=0;$i<$strlength;$i++)
      {
          if(is_numeric($str[$i]))
          {
              $Nmsg= "err";
          }
      }
      return $Nmsg; 
  }
}

if (!function_exists('validate_data')) {
    
    function validate_data($string='', $field_name='', $rules='trim',$stream='',$api_page='', $method='')
    {
        $rules_array = explode('|',$rules);
        foreach($rules_array as $rule){
            
            if($rule=='trim'){
                $string = trim($string);
            }
            if($rule=='required'){
                if(strlen($string)==0){
                    $msg = "ERROR: Required variable missing: ".$field_name;
                    echo $msg;
                    $this->Inbound->insert_inbound_stream($method, '', $stream, $msg, $this->agent->referrer(), $this->input->ip_address(), '0', $api_page);
                    exit;
                }
            }
            if($rule=='numeric'){
                if(!is_numeric($string)){
                    $msg = "ERROR: Only integer value is allow in ".$field_name;
                    echo $msg;
                    $this->Inbound->insert_inbound_stream($method, '', $stream, $msg, $this->agent->referrer(), $this->input->ip_address(), '0', $api_page);
                    exit;
                }
            }
            
            if($rule=='alpha'){
                if(is_number_exist($string)=='err'){
                    $msg = "ERROR: Please do not enter any number in ".$field_name;
                    echo $msg;
                    $this->Inbound->insert_inbound_stream($method, '', $stream, $msg, $this->agent->referrer(), $this->input->ip_address(), '0', $api_page);
                    exit;   
                }
            }
            
            if($rule=='secure'){
                $string = addslashes(strip_tags($string));
            }
            /*if($rule=='valid_email'){
                //$string   = mysql_real_escape_string(strip_tags($string));
            }*/
            
            
        }
        
        return $string;
    }
}

if (!function_exists('api_email_rule')) {
    function api_email_rule($email) {
       
       if(stristr($email,'@noemail.com') || $email=='' || stristr($email,'@nomail.com') || $email=='noemail@email.com'){
              $email='nomail@nomail.com';
            }
        return $email;  
    }   
}

if ( ! function_exists('object_to_array'))
{
 function object_to_array($object)
 {
  if (is_object($object))
  {
   // Gets the properties of the given object with get_object_vars function
   $object = get_object_vars($object);
  }
 
   return (is_array($object)) ? array_map(__FUNCTION__, $object) : $object;
 }
}

if ( ! function_exists('array_to_object'))
{
 function array_to_object($array)
 {
  return (is_array($array)) ? (object) array_map(__FUNCTION__, $array) : $array;
 }
}

if ( ! function_exists('insertspaces'))
{

function insertspaces($str,$value)
    {
        $spaces="";
        $str=substr($str,0,$value);
        
        if($value > 0)
        {
            $strlength=strlen($str);
            $remainlength=$value-$strlength;
            for($i=1;$i<=$remainlength;$i++)
            {
                $spaces.=" ";
            }
        }
        else
            $spaces="";
            
        return $str.$spaces;
    }
}

if ( ! function_exists('print_array'))
{
    function print_array($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        
    }
}

if ( ! function_exists('count_days'))
{   
    function count_days( $a, $b )
    {
        $a = strtotime($a);
        $b = strtotime($b);
        
        $gd_a = getdate( $a );
        $gd_b = getdate( $b );
        $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
        $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
        return round( abs( $a_new - $b_new ) / 86400 );
    }
}

if ( ! function_exists('count_years'))
{   
    function count_years( $a, $b )
    {
        $a = strtotime($a);
        $b = strtotime($b);
        
        $gd_a = getdate( $a );
        $gd_b = getdate( $b );
        $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
        $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
        return round( abs( $a_new - $b_new ) / 86400/365 );
    }
}

if ( ! function_exists('get_average'))
{
    function get_average($total_sum,$total_quantity)
    {
            if($total_quantity>0){
                $avg = $total_sum/$total_quantity;
                $avg= number_format($avg,2,'.',''); 
            }
            else{
                $avg = '0.00';
            }
            return $avg;        
    }
}

if ( ! function_exists('is_selected'))
{
    function is_selected($db_value,$current_value)
    {
            if($db_value==$current_value){
                $return = 'selected="selected"';
            }
            else{
                $return = '';
            }
            return $return;     
    }
}

if(!function_exists('dateDiff')) {
    
    function dateDiff($time1, $time2, $precision = 6) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }
 
    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }
 
    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();
 
    // Loop thru all intervals
    foreach ($intervals as $interval) {
      // Create temp time from time1 and interval
      $ttime = strtotime('+1 ' . $interval, $time1);
      // Set initial values
      $add = 1;
      $looped = 0;
      // Loop until temp time is smaller than time2
      while ($time2 >= $ttime) {
        // Create new temp time from time1 and interval
        $add++;
        $ttime = strtotime("+" . $add . " " . $interval, $time1);
        $looped++;
      }
 
      $time1 = strtotime("+" . $looped . " " . $interval, $time1);
      $diffs[$interval] = $looped;
    }
 
    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
      // Break if we have needed precission
      if ($count >= $precision) {
    break;
      }
      // Add value and interval 
      // if value is bigger than 0
      if ($value > 0) {
    // Add s if value is not 1
    if ($value != 1) {
      $interval .= "s";
    }
    // Add value and interval to times array
    $times[] = $value . " " . $interval;
    $count++;
      }
    }
 
    // Return string with times
    return implode(", ", $times);
  }
}

if ( ! function_exists('pagination_configuration')){
    function pagination_configuration(
        $base_url, 
        $total_rows, 
        $per_page = 25, 
        $uri_segment = 3, 
        $num_links = 4, 
        $use_page_numbers = TRUE, 
        $enable_query_strings = FALSE,
        $page_query_string = FALSE,
        $query_string_segment = 'page') {
      
        $ci =& get_instance();

        $config = [];
        
        $config["base_url"] = $base_url;
        $config["total_rows"] = $total_rows;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = $uri_segment;
        $config['num_links'] = $num_links;
        $config['use_page_numbers'] = $use_page_numbers;
        
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        
        //First Link
        $config['first_link'] = 'Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        
        //Last Link
        $config['last_link'] = 'Último';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        //Next Link
        $config['next_link'] = 'Siguiente';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        //Previous Link
        $config['prev_link'] = 'Anterior';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        
        //Current link
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</li></a>';
        
        //Digits Link
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['enable_query_strings'] = $enable_query_strings;
        $config['page_query_string'] = $page_query_string;
        $config['query_string_segment'] = $query_string_segment; 

        $ci->pagination->initialize($config);

        return $config;
    }
}

if ( ! function_exists('get_file_extension'))
{
    function get_file_extension($filename)
    {
            $ext1 = explode('.',$filename);
            $ext2 = array_reverse($ext1);
            $file_extenstion = strtolower($ext2[0]);
            return $file_extenstion;        
    }
}
if ( ! function_exists('make_friendly_url'))
{
    function make_friendly_url($param)
    {
        $param = trim($param);
        $string1 = preg_replace('/[^a-zA-Z0-9 ]/s', '', $param);
        $param = strtolower(preg_replace('/\s+/', '-', $string1));
        return $param;      
    }
}
if ( ! function_exists('make_job_url_from_segments'))
{
    function make_job_url_from_segments($segment)
    {
        $param = trim($param);
        $string1 = preg_replace('/[^a-zA-Z0-9 ]/s', '', $param);
        $param = strtolower(preg_replace('/\s+/', '-', $string1));
        return $param;      
    }
}

if ( ! function_exists('is_active_like'))
{
    function is_active_like($current_page, $page, $flag = false)
    {
        $CI = & get_instance();
        
        if ($flag == false) {

            if ($current_page == $page) {
                return 'active';
            }

            return '';
        }

        $pieces = explode('/', $page);

        $url_path_current = [];

        foreach ($pieces as $index => $str) {
            $url_path_current[] = $CI->uri->segment($index + 1);            
        }

        $path_current = implode('/', $url_path_current);

        if ($path_current == $page) {
            return 'active';
        }

        return '';
    }
}

if ( ! function_exists('menu_item'))
{
    function menu_item($url, $name, $icon, $list_selected = [])
    {
        $CI = & get_instance();
        $is_active = false;

        foreach ($list_selected as $path) {
            $is_active = is_active_like('', $path, true);
            if ($is_active) {
                break;
            }
        }

        if (!$is_active) {
            $is_active = is_active_like('', $url, true);
        }
            
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
          $url = site_url($url);
        }

        return 
        '<li>
            <a href="' . $url . '" class="innerfetbox ' . $is_active . '">
                <i class="material-icons">' . $icon . '</i> <span>' . $name . '</span>
            </a>
	    </li>';
    }
}

if ( ! function_exists('make_slug'))
{
    function make_slug($string)
    {
        $lower_case_string = strtolower($string);
        $string1 = preg_replace('/[^a-zA-Z0-9\s]/s', '', $lower_case_string);
        return strtolower(preg_replace('/\s+/', '-', $string1));        
    }
}
if ( ! function_exists('validate_company_url'))
{
    function validate_company_url($string)
    {
        $invalid_char_array = array("http://http://", "https://http://", "http://https://", "https://https://");
        $new_url = str_replace($invalid_char_array, 'http://', $string);
        $url = strpos($new_url,'http://') !== false ? $new_url : 'http://'.$new_url;    
        
        if(!filter_var($url, FILTER_VALIDATE_URL))
            $return = '';   
        else
            $return = $url;
        return $return;
    }
}

if ( ! function_exists('date_difference_in_years'))
{
    function date_difference_in_years($date_from, $date_to)
    {
        if (!$date_from) {
            return 0;
        }

        if (!$date_to) {
            return 0;
        }

        $date1=date_create($date_from);
        $date2=date_create($date_to);
        $diff=date_diff($date1,$date2);
        return $diff->y;
    }
}

if ( ! function_exists('check_keywords'))
{
    function check_keywords($keywords){
        $keywords_array = explode(', ',$keywords);
        return count($keywords_array);
    }
}

if ( ! function_exists('get_extension_name'))
{
    function get_extension_name($ext){
        switch($ext){
            case 'doc':
                $icon_name = 'word';
            break;
            
            case 'docx':
                $icon_name = 'word';
            break;
            
            case 'pdf':
                $icon_name = 'pdf';
            break;
            
            case 'jpg':
                $icon_name = 'image';
            break;
            
            case 'jpeg':
                $icon_name = 'image';
            break;
            
            case 'gif':
                $icon_name = 'image';
            break;
            
            case 'png':
                $icon_name = 'image';
            break;
            
            case 'rtf':
                $icon_name = 'text';
            break;
            
            case 'txt':
                $icon_name = 'text';
            break;
        }
        
        return $icon_name;
    }
}

if ( ! function_exists('get_singular_plural'))
{
    function get_singular_plural($val,$singular, $plural){
        
        if($val==1)
            $return = $singular;        
        elseif($val>1)
            $return = $plural;
        else
            $return = '';
        return $return;
    }
}

if ( ! function_exists('replace_string_array'))
{
    function replace_string_array($content,$translator_array){
        return strtr($content, $translator_array);
    }
}

if ( ! function_exists('active_link'))
{
    function active_link($link){
        $CI =& get_instance();
        $data = '';
        if($CI->uri->segment(1)==$link)
                $data='class="active"';
            else
                $data='class="inactive"';
        return $data;
    }
}

if (!function_exists('generate_random_characters')) {
    
    function generate_random_characters($random_string_length){
        
      $characters    = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";
      $string = '';
      for ($i = 0; $i < $random_string_length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $string;   
    }
}

if (!function_exists('validate_jobseeker_data')) {
    
    function validate_jobseeker_data()
    {
        $ci =& get_instance();
        $user_id = $ci->session->userdata('user_id');
        

        if (!is_jobseeker_data_complete()) {
            redirect('jobseeker/my_account');
            exit;
        }
    }
}

if (!function_exists('is_jobseeker_data_complete')) {
    
    function is_jobseeker_data_complete()
    {
        $ci =& get_instance();
        $user_id = $ci->session->userdata('user_id');   
        
        $job_seeker = $ci->Job_seeker->get_job_seeker_by_id($user_id);

        // (empty($job_seeker->first_name) ||
        // empty($job_seeker->last_name) ||
        // empty($job_seeker->paternal_last_name) ||
        // empty($job_seeker->maternal_last_name) ||
        // empty($job_seeker->document_type) ||
        // empty($job_seeker->document_number) ||
        // empty($job_seeker->gender) ||
        // empty($job_seeker->dob) || $job_seeker->dob == '0000-00-00' || 
        // empty($job_seeker->civil_status) ||
        // empty($job_seeker->country) ||
        // empty($job_seeker->city) || 
        // empty($job_seeker->mobile))

        if (empty(trim((string)$job_seeker->first_name))) {
            return false;
        }

        return true;
    }
}

if (!function_exists('get_answers_to_question')) {

    function get_answers_to_question($question_id, $applied_id)
    {
        $ci =& get_instance();
        $answer_data = $ci->Applied_jobs->get_answers_to_question($question_id, $applied_id);

        return $answer_data;
    }
}

if (!function_exists('document_type_text')) {
    function document_type_text($str)
    {
        static $__cache_list_document_type = NULL;

        if (is_null($__cache_list_document_type)) { 
            $list = [];

            $ci =& get_instance();
            $ci->db->from('tbl_identity_document_types');
       
            foreach ($ci->db->get()->result() as $row) {
                $list[$row->id] = $row->name;
            }

            $__cache_list_document_type = $list;
        }
    
        return isset($__cache_list_document_type[$str]) ? $__cache_list_document_type[$str] : $str;
    }
}

if (!function_exists('document_type_abbr')) {

    function document_type_abbr($str)
    {
        static $__cache_list_document_type_abbr = NULL;

        if (is_null($__cache_list_document_type_abbr)) { 
            $list = [];

            $ci =& get_instance();
            $ci->db->from('tbl_identity_document_types');
       
            foreach ($ci->db->get()->result() as $row) {
                $list[$row->id] = $row->abbreviation;
            }
            $__cache_list_document_type_abbr = $list;
        }
    
        return isset($__cache_list_document_type_abbr[$str]) ? $__cache_list_document_type_abbr[$str] : $str;
    }
}

if (!function_exists('civil_status_text')) {

    function civil_status_text($str)
    {
        static $__cache_list_civil_status = NULL;

        if (is_null($__cache_list_civil_status)) { 
            $list = [];

            $ci =& get_instance();
            $ci->db->from('tbl_civil_status');
       
            foreach ($ci->db->get()->result() as $row) {
                $list[$row->id] = $row->name;
            }
            
            $__cache_list_civil_status = $list;
        }

        return isset($__cache_list_civil_status[$str]) ? $__cache_list_civil_status[$str] : $str;
    }
}

if (!function_exists('gender_text')) {

    function gender_text($str)
    {
        static $__cache_list_genders = NULL;

        if (is_null($__cache_list_genders)) { 
        
            $list = [];

            $ci =& get_instance();
            $ci->db->from('tbl_genders');
       
            foreach ($ci->db->get()->result() as $row) {
                $list[$row->id] = $row->name;
            }

            $__cache_list_genders = $list;
        }
    
        return isset($__cache_list_genders[$str]) ? $__cache_list_genders[$str] : $str;
    }
}

if (!function_exists('country_text')) {

    function country_text($str)
    {
        static $__cache_list_countries = NULL;

        if (is_null($__cache_list_countries)) { 
            $list = [];
            $ci =& get_instance();
            $ci->db->from('tbl_countries');
            
            foreach ($ci->db->get()->result() as $row) {
                $list[$row->ID] = $row->country_name;
            }

            $__cache_list_countries = $list;
        }

        return isset($__cache_list_countries[$str]) ? $__cache_list_countries[$str] : $str;
    }
}

if (!function_exists('citizen_text')) {

    function citizen_text($str)
    {
        static $__cache_list_citizen = NULL;

        if (is_null($__cache_list_citizen)) { 

            $list = [];

            $ci =& get_instance();
            $ci->db->from('tbl_countries');
    
            foreach ($ci->db->get()->result() as $row) {
                $list[$row->ID] = $row->country_citizen;
            }

            $__cache_list_citizen = $list;
        }

        return isset($__cache_list_citizen[$str]) ? $__cache_list_citizen[$str] : $str;
    }
}

if (!function_exists('disability_text')) {
    function disability_text($str)
    {
        static $__cache_list_disability = NULL;

        if (is_null($__cache_list_disability)) { 
            $list = [];

            $ci =& get_instance();
            $ci->db->from('tbl_disabilities');
            
            foreach ($ci->db->get()->result() as $row) {
                $list[$row->id] = $row->name;
            }

            $__cache_list_disability = $list;
        }
        
        return isset($__cache_list_disability[$str]) ? $__cache_list_disability[$str] : '';
    }
}

if (!function_exists('experience_text')) {

    function experience_text($experience)
    {
        $data = array(
            'fresh' => 'Sin experiencia',
            '<1' => 'Menos de 1 año',
            '3m' => '3 meses',
            '6m' => '6 meses',
            '1' => '1 año',
            '2' => '2 años',
            '3' => '3 años',
            '4' => '4 años',
            '5' => '5 años',
            '6' => '6 años',
            '7' => '7 años',
            '8' => '8 años',
            '9' => '9 años',
            '10' => '10 años',  
            '10+' => 'Más de 10 años'
        );

        if (isset($data[$experience])) {
            return $data[$experience];
        }
        
        return is_numeric($experience) ? ($experience > 1 ? $experience . ' años ' :  $experience . ' año ') : $experience;
    }
}

if (!function_exists('job_mode_text')) {

    function job_mode_text($job_mode)
    {
        $data = array(
            'full_time' => 'Full-Time',
            'part_time' => 'Part-Time',
            'per_hours' => 'Por horas',
            'weekends' => 'Fines de semana',
            'telecommuting' => 'Teletrabajo',
        );

        return isset($data[$job_mode]) ? $data[$job_mode] : $job_mode;
    }
}

if (!function_exists('status_posted_text')) {

    function status_posted_text($status)
    {
        $data = array(
            'pending' => 'Pendiente',
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'blocked' => 'Bloqueado',
        );

        return isset($data[$status]) ? $data[$status] : $status;
    }
}

if (!function_exists('get_age')) {

    function get_age($date)
    {
        $dob = new DateTime($date);
        $today = new DateTime();
        $years = $today->diff($dob);
    
        return $years->y;
    }
}

if (!function_exists('jobseeker_cv_data')) {

    function jobseeker_cv_data($jobseeker_id)
    {
        $ci =& get_instance();

        $row = $ci->Job_seeker->get_job_seeker_by_id($jobseeker_id);

        $seeker_additional_info = $ci->Jobseeker_additional_info->get_record_by_userid($jobseeker_id);

        //Latest Job
        $row_latest_exp = $ci->Jobseeker_experience->get_latest_job_by_seeker_id($row->ID);
        //Experience
        $result_experience = $ci->Job_seeker->get_experience_by_jobseeker_id($row->ID);

        //Qualification
        $result_qualification = $ci->Job_seeker->get_qualification_by_jobseeker_id($row->ID);

        //Resumes
        $result_resume = $ci->Resume->get_records_by_seeker_id($row->ID, 5, 0);

        //Additional Info
        $row_additional = $ci->Jobseeker_additional_info->get_record_by_userid($row->ID);

        //Other studies
        $result_other_studies = $ci->Jobseeker_other_studies->get_other_studies_by_seeker_id($row->ID);

        $result_answers_applicant = array();

        $photo = $row->photo;

        $data['row'] = $row;
        $data['seeker_additional_info'] = $seeker_additional_info;
        $data['result_experience'] = $result_experience;
        $data['result_qualification'] = $result_qualification;
        $data['result_resume'] = $result_resume;
        $data['row_additional'] = $row_additional;
        $data['latest_job_title'] = ($row_latest_exp) ? $row_latest_exp->job_title:'';
        $data['latest_job_company_name'] = ($row_latest_exp) ? $row_latest_exp->company_name:'';
        $data['result_other_studies'] = $result_other_studies;
        $data['photo'] = $photo;
    
        return $data;
    }
}

if (!function_exists('make_job_slug')) {

    function make_job_slug($company_slug, $job_title, $id)
    {   
        $job_title = strtolower(trim($job_title));
        $job_title_slug = preg_replace('/[^a-zA-Z0-9 ]/s', '', $job_title);
        $job_title_slug = preg_replace('/\s+/', '-', $job_title_slug);      
        
        $final_job_url =  $job_title_slug . '-' . $company_slug . '-' . $id;
    
        return $final_job_url;      
    }
}

if (!function_exists('validate_employer_admin')) {

    function validate_employer_admin()
    {
        $ci =& get_instance();
        
        $user_id = $ci->session->userdata('user_id');

        $row_employer = $ci->Employer->get_employer_by_id($user_id);

        if (!$row_employer) {
            redirect('login');
        }

        if ($row_employer->is_admin != 'yes') {
            show_error('No está autorizado para ver esta página', 403, 'Página no autorizada');
            exit(4);
        }
    }
}   

if (!function_exists('pagination_url')) {

    function pagination_url()
    {
        $ci =& get_instance();
        $url = $_SERVER['QUERY_STRING'] ? current_url() . '?' . $_SERVER['QUERY_STRING'] : current_url();
        return preg_replace('/\?*&*page=.*/', '', $url);
    }
}   

if (!function_exists('load_email_view')) {

    function load_email_view($view, $data = null, $return_string = true)
    {
        $ci =& get_instance();

        $content_email = $ci->load->view($view, $data, true);
        $dom = new DOMDocument();
        @$dom->loadHTML($content_email);
        
        $obj_styles = $dom->getElementsByTagName('style');
        $style = '';
        
        foreach ($obj_styles as $node) {
            $style.= $node->nodeValue;
        }

        $data = array(
            'style' => $style,
            'content_email' => $content_email
        );

        if ($return_string) {
        
            return $ci->load->view('common/template_email', $data, true);
        }

        $ci->load->view('common/template_email', $data);
    }
}

if (!function_exists('bytes_random')) {

    function bytes_random($lenght = 13) {

        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes($lenght);
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes($lenght);
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        
        return $bytes;
    }
}

if (!function_exists('str_random')) {

    function str_random($lenght = 13) {

        $bytes = bytes_random(ceil($lenght / 2));

        return substr(bin2hex($bytes), 0, $lenght);
    }
}

if (!function_exists('create_token')) {

    function create_token($lenght = 13, $str = '') {

        if (empty($str)) {
            $str = microtime(true);
        }

        $str_random = str_random($lenght);

        return substr(str_replace(array('/', '+', '='), '', base64_encode($str_random . '-' . $str)), 0, $lenght);
    }
}

if (!function_exists('split_phone')) {
    function split_phone($phone)
    {
        $phone_parts = explode(' ', $phone);
        $count_phone_parts = count($phone_parts);

        $code = '';
        $number = '';

        if ($count_phone_parts == 3) {
            $code = $phone_parts[0] . ' ' . $phone_parts[1];
            $number = $phone_parts[2];
        } elseif ($count_phone_parts == 2)  {
            $code = $phone_parts[0];
            $number = $phone_parts[1];
        }

        return compact('code', 'number');
    }
}

if (!function_exists('flash_message')) {
    function flash_message($msg_type, $msg_text)
    {           
        $ci =& get_instance();
        $ci->session->set_flashdata(
            'msg', 
            '<div class="alert alert-' . $msg_type . '"> <a href="#" class="close" data-dismiss="alert">&times;</a>' . $msg_text .' </div>'
        );
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'Y-m-d')
    {           
        $date = str_replace('/', '-', (string)$date);
        return date($format, strtotime($date));
    }
}

if (!function_exists('get_url_file_by_path_or_link')) {
    function get_url_file_by_path_or_link($path_or_link_file = '')
    {
        return site_url($path_or_link_file);
    }
}

if (!function_exists('get_text_policy_site')) {

    function get_text_policy_site()
    {
        $ci =& get_instance();

        $terms = $ci->config->item('enable_notice_terms');
        $privacy_policy = $ci->config->item('enable_notice_privacy_policy');    
        $cookies_policy = $ci->config->item('enable_notice_cookies_policy');    

        $array_text_link = array();
        $text_policy = "";

        if ($terms == '1') {
            $array_text_link[] = '<a href="' . site_url('terms.html') . '">Condiciones de uso</a>';
        }

        if ($privacy_policy == '1') {
            $array_text_link[] = '<a href="' . site_url('privacy-policy.html') . '">Política de privacidad</a>';
        }

        if ($cookies_policy == '1') {
            $array_text_link[] = '<a href="' . site_url('cookies-policy.html') . '">Política de cookies</a>';
        }

        $count_total_policy = count($array_text_link);

        if ($count_total_policy == 0) {
            return false;
        }

        $text_policy = $array_text_link[0];

        if ($count_total_policy == 3) {
            $text_policy.= ', ' . $array_text_link[1];
        }  

        if ($count_total_policy > 1) {
            $text_policy.= ' y ' . $array_text_link[$count_total_policy - 1];
        }            
        return $text_policy;
    }

    function is_field_empty($array) {

      foreach ($array as $key => $data) {
        if ($data != '') {
          return false;
        }
      }

      return true;
    }
}

if (!function_exists('exam_request_exam_types')) {
    function exam_request_exam_types($exam_type_id = 0) {

        $exam_types = [$exam_type_id];

        if ($exam_type_id == 4) {
            $exam_types = [1, 2]; //EMO + COVID-19
        }

        return $exam_types;
    }
}

if (!function_exists('str_sort')) {
    function str_sort($str)
    {
        $skills = explode(',', $str);
        $array_skills = [];

        foreach ($skills as $skill) {
            $array_skills[] = trim($skill);
        }
        sort($array_skills);

        return join(',', $array_skills);
    }
}

if (!function_exists('is_peruvian')) {
    function is_peruvian($document_identity_type)
    {
        return $document_identity_type == '1';
    }
}

if (!function_exists('born_in_peru')) {
    function born_in_peru($document_identity_type)
    {
        return $document_identity_type == '1';
    }
}

function is_enabled_rys_seeker_identification_document($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3,
        5
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_photo($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_form_rtps($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_domicile_affidavit($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_declaration_5th_category($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_spouse_identification_document($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_certificate_5th_category($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_study_certificates($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_doc_spouse_act($seeker, $spouse) {

    $ci =& get_instance();
    $profile_id = $ci->session->userdata('current_profile_id');

    if ($profile_id == 1 ||
        $profile_id == 2 || 
        $profile_id == 3 || 
            //Perfil legal
        ($profile_id == 5 && $seeker->document_type != '1' && $spouse && $spouse->document_type == '1' && $spouse->kinship == 1)) {
        return true;
   }

   return false;
}

function is_enabled_rys_seeker_doc_childrens($seeker, $childrens) {

    $ci =& get_instance();
    $profile_id = $ci->session->userdata('current_profile_id');

    if ($profile_id == 1 ||
        $profile_id == 2 ||
        $profile_id == 3 || 
       ($profile_id == 5 && $seeker->document_type != '1' && count($childrens) > 0)) {
        return true;
    }

    return false;
}

function is_enabled_rys_seeker_receipt_service($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_experience_certificates($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_police_records($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

function is_enabled_rys_seeker_doc_immigration_records($seeker) {

    $ci =& get_instance();
    $profile_id = $ci->session->userdata('current_profile_id');

    if ($profile_id == 1 ||
        $profile_id == 2 ||
        $profile_id == 3 || 
       ($profile_id == 5 && $seeker->document_type != '1') ||
       ($profile_id == 6 && $seeker->document_type != '1')) {
        return true;
    }

    return false;
}

function is_enabled_rys_seeker_doc_residency_verifications($seeker) {

    $ci =& get_instance();
    $profile_id = $ci->session->userdata('current_profile_id');

    if ($profile_id == 1 ||
        $profile_id == 2 ||
        $profile_id == 3 || 
       ($profile_id == 5 && $seeker->document_type != '1')) {
        return true;
    }

    return false;
}

function is_enabled_rys_seeker_rys_form_affidavit($seeker) {

    $ci =& get_instance();

    $allowed_profiles = [
        1,
        2,
        3
    ];

    $profile_id = $ci->session->userdata('current_profile_id');

    return in_array($profile_id, $allowed_profiles);
}

if (!function_exists('input_embed_token')) {
    function input_embed_token()
    {
        $ci =& get_instance();
        
        return "<input type='hidden' name='" . $ci->config->item('embed_token_name') . "' value='" . $ci->config->item('embed_token_value'). "'/>";
    }
}

if (!function_exists('embed_token_name')) {
    function embed_token_name()
    {
        $ci =& get_instance();

        return $ci->config->item('embed_token_name');
    }
}

if (!function_exists('embed_token')) {
    function embed_token()
    {
        $ci =& get_instance();

        return $ci->config->item('embed_token_value');
    }
}

if (!function_exists('dd')) {
    function dd($arg)
    {
        echo "<pre>"; 
        print_r($arg); 
        echo "</pre>";
        die();
    }
}

if (!function_exists('get_allowed_files')) {
    function get_allowed_files()
    {   
        return [
            '.jpg', 
            '.png', 
            '.doc', 
            '.docx', 
            '.xlsx', 
            '.pdf'
        ]; 
    }
}

if (!function_exists('mime_types')) {
    function get_mime_types($extensions)
    {   
        $allowed_files = [
            '.jpg' => 'image/jpeg', 
            '.png' => 'image/png', 
            '.doc' => 'application/msword', 
            '.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
            '.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
            '.pdf' => 'application/pdf'
        ];

        $mimes_types = [];

        foreach ($extensions as $ext) {

            if (isset($allowed_files[$ext])) {
                $mimes_types[] = $allowed_files[$ext];
            }
        }

        return $mimes_types;
    }
}

if (!function_exists('site_url_embed')) {
    function site_url_embed($url_path) {   
        $ci =& get_instance();
        return site_url($url_path . '?' . $ci->config->item('embed_token_name') . '=' . $ci->config->item('embed_token_value'));
    }
}

if (!function_exists('format_mobile')) {
    function format_mobile($mobile) {   
        
        $ci =& get_instance();

        if ($ci->config->item('env') != 'production' && $ci->config->item('mobile_notifications')) {
            $mobile = $ci->config->item('mobile_notifications');
        }

        $mobile = phone_number_format($mobile);

        return $mobile;
    }
}

if (!function_exists('phone_number_format')) {
    function phone_number_format($mobile)
    {       
        $mobile = trim((string)$mobile);
        $mobile = str_replace(' ', '', $mobile);
        $mobile = str_replace('-', '', $mobile);
        $mobile = str_replace('+', '', $mobile);

        if (strlen($mobile) == 9 && $mobile[0] == '9') {
            $mobile = '51' . $mobile;
        }

        $mobile = '+' . $mobile;

         try {
            $phone_number_util = \libphonenumber\PhoneNumberUtil::getInstance();

            $phone_number_object = $phone_number_util->parse($mobile, null);

            $is_valid_number = $phone_number_util->isValidNumber($phone_number_object);

            if ($is_valid_number == true) {
                return $mobile;
            }
        } catch (\libphonenumber\NumberParseException $e) {}

        return '';
    }
}

if (!function_exists('base64_to_image')) {
    function base64_to_image($base64) {   

        $bin = base64_decode($base64);

        // Gather information about the image using the GD library
        $img = imagecreatefromstring($bin);

        if (!$img) {
            return false;
        }
        
        // Specify the location where you want to save the image
        $img_tmp_path = tempnam(sys_get_temp_dir(), rand(1, 99999)) . '.png';

        $imgpng = imagepng($img, $img_tmp_path, 0);

        imagedestroy($img);

        if (!$imgpng) {
            return false;
        }

        return $img_tmp_path;
    }
}

if (!function_exists('get_consultant_code')) {
    function get_consultant_code($overall_cia_code)
    {   
        $ci =& get_instance();
        $env_prod = true;//$ci->config->item('env') == 'production';

        if ($env_prod) {
            return $overall_cia_code;
        }

        static $__cache_ca_sistemas_cias = NULL;

        if (is_null($__cache_ca_sistemas_cias)) {
           
            $ci->db->from('tbl_ca_workflow_consultants');
            $results = $ci->db->get()->result();

            $cias = [];

            foreach ($results as $row) {
                $cias[$row->overall_cia_code] = $row;
            }

            $__cache_ca_sistemas_cias = $cias; 
        }

        return isset($__cache_ca_sistemas_cias[$overall_cia_code]) ? ($__cache_ca_sistemas_cias[$overall_cia_code])->cia_code : $overall_cia_code;
    }
}

if (!function_exists('apiv2_response')) {
    function apiv2_response($status, $message = '', $data = []) {   
     
        $response_data = [
        'status' => $status,
        'message' => $message
      ];

      if (count($data) > 0 || $status === true) {
        $response_data['data'] = $data;
      }

      return $response_data;
    }
}

if (!function_exists('apiv2_candidate_card')) {
    function apiv2_candidate_card($candidate_row) {   
        return [
            'id' => (string)$candidate_row->seeker_id,
            'email' => (string)$candidate_row->seeker_email,
            'first_name' => (string)$candidate_row->seeker_first_name,
            'paternal_last_name' => (string)$candidate_row->seeker_paternal_last_name,
            'maternal_last_name' => (string)$candidate_row->seeker_maternal_last_name,
            'country_name_iso_3166_1_alpha_2' => (string)$candidate_row->seeker_country_iso_3166_1_alpha2,
            'country_name' => (string)$candidate_row->seeker_country_name,
            'identification_document_type_abbreviation' => (string)$candidate_row->seeker_document_type_name_abbreviation,
            'identification_document_type_name' => (string)$candidate_row->seeker_document_type_name,
            'identification_document_number' => (string)$candidate_row->seeker_document_number,
            'gender_name' => gender_text((string)$candidate_row->seeker_gender),
            'birth_date' => (string)$candidate_row->seeker_dob,
            'mobile_phone_number' => (string)phone_number_format($candidate_row->seeker_phone_number),
            'overall_accumulated_working_time_days' => (int)$candidate_row->overall_accumulated_working_time_days,
            'overall_status' => isset($candidate_row->overall_status) ? $candidate_row->overall_status : 'N',
            'current_processes' => (int)$candidate_row->current_processes,
            'avatar_url' => img_pic_candidate((string)$candidate_row->seeker_avatar),
            'is_blacklisted' => (int)$candidate_row->blacklisted
        ];
    }
}

if ( ! function_exists('api_show_404'))
{
	/**
	 * 404 Page Handler
	 *
	 * This function is similar to the show_error() function above
	 * However, instead of the standard error template it displays
	 * 404 errors.
	 *
	 * @param	string
	 * @param	bool
	 * @return	void
	 */
	function api_show_404()
	{
		$_error =& load_class('Exceptions', 'core');
        $_error->api_show_404();
		exit(4); // EXIT_UNKNOWN_FILE
	}
}

if (!function_exists('validate_short_list_session_or_token')) {
    
    function validate_short_list_session_or_token()
    {
        $ci =& get_instance();

        $token = isset($_GET['t']) ? trim($_GET['t']) : false;

        if ($token !== false) {

            $ci->db->from('tbl_recruitment_short_list_tokens');
            $ci->db->where('token', $token);
            $token_row = $ci->db->get()->row();

            if (!$token_row) {
                redirect('login');
            }

            $now = new DateTime('now');
            $token_datetime = new DateTime($token_row->created_at);
            $minutes = abs($now->getTimestamp() - $token_datetime->getTimestamp()) / 60;

            //Si el link pasa del dia de creado expira
            if ($minutes > 2880) {
                flash_message('danger', 'El enlace de la terna o shor list ya ha caducado, por favor indique que le suministren otro enlace!');
                redirect('login');
            }

            return;
        }

        $user_id = $ci->session->userdata('user_id');

        if (!$user_id) {
            redirect('login');
        }
    }
}

function base64_url_encode($base64) {

    // Reemplaza los caracteres + y / con - y _
    $url_safe = strtr($base64, '+/', '-_');

    // Elimina el padding = del final
    $url_safe_no_padding = rtrim($url_safe, '=');

    return $url_safe_no_padding;
}

function base64_url_decode($data) {
    // Agrega el padding = que fue eliminado
    $padded_data = str_pad($data, strlen($data) % 4, '=', STR_PAD_RIGHT);

    // Reemplaza los caracteres - y _ con + y /
    $base64 = strtr($padded_data, '-_', '+/');

    // Decodifica la cadena de Base64
    return $base64;
}
