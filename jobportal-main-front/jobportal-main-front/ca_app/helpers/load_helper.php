<?php

if (!function_exists('load_js')){

    function load_js($path_or_array)
    {
        load_view($path_or_array);
    }
}

if (!function_exists('load_view')){

    function load_view($path_or_array)
    {
        $ci = & get_instance();

        if (!is_array($path_or_array)) {
            $path_or_array[] = $path_or_array;
        }

        foreach ($path_or_array as $path) {
            $ci->load->view($path);
        }
    }
}