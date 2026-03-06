<?php
//Hash the password
if ( ! function_exists('do_hashing'))
{
    function do_hashing($str)
    {
        $new_hashed='';
        if($str)
            $new_hashed = password_hash($str, PASSWORD_DEFAULT);
        return $new_hashed;
    }
}

//Verify the password if its valid or invalid
if ( ! function_exists('verify_hashing'))
{
    function verify_hashing($str, $hashed_value)
    {
        return password_verify($str, $hashed_value);
    }
}

//Check if there is need to rehash the password
if ( ! function_exists('needs_rehashing'))
{
    function needs_rehashing($str)
    {
        return password_needs_rehash($str, PASSWORD_DEFAULT);
    }
}

//Create a random password
if (!function_exists('create_random_password'))
{
    function create_random_password($length = 8, $prefix = '')
    {
        return substr(md5($prefix . microtime(true)), 0, $length);
    }
}

//Check strength password
if (!function_exists('is_password_strength'))
{
    function is_password_strength($password) {
        
        if (strlen($password) < 8) {
            return false;
        }       
        
        $capital_letter = false;
        $lower_case = false;
        $number = false;
        $special_caracters = false;

        for ($i = 0; $i < strlen($password); $i++) {
    
            if (ord($password[$i]) >= 65 && ord($password[$i]) <= 90) {
                $capital_letter = true;
            } else if (ord($password[$i]) >= 97 && ord($password[$i]) <= 122) {
                $lower_case = true;
            } else if (ord($password[$i]) >= 48 && ord($password[$i]) <= 57) {
                $number = true;
            } else {
                $special_caracters = true;
            }
        }

        return 
            $capital_letter && 
            $lower_case &&
            $number &&
            $special_caracters;
    }
}

if (!function_exists('_e')) {
    function _e($str, $flags = ENT_QUOTES) {

        return htmlentities(
            $str,
            $flags
        );
    }
}

if (!function_exists('e')) {
    function e($str, $flags = ENT_QUOTES) {
       echo _e((string)$str, $flags);
    }
}
