<?php 

if (!function_exists('file_url'))
{
    function file_url($path, $local_folder = '') {
        $ci = & get_instance();

        if (substr((string)$path, 0, 8) == 'https://' || 
            substr((string)$path, 0, 7) == 'http://' ) {
            return $path;
        }

        if (file_exists(FCPATH . $path)) {
            return base_url($path);
        }

        if (file_exists(FCPATH . $local_folder . '/' . $path)) {
            return base_url($local_folder . '/' . $path);
        }

        return 'https://overall-portal-de-empleo.s3.amazonaws.com/' . $path;
    }
}

if (!function_exists('img_pic_candidate'))
{
    function img_pic_candidate($path) {

        if (empty($path)) {
            return base_url('public/images/no_pic.jpg');
        }

        return file_url($path, 'public/uploads/candidate/thumb');
    }
}

if (!function_exists('img_pic_company'))
{
    function img_pic_company($path, $type = '') {

        if (empty($path) && $type == '') {
            return base_url('public/images/no_logo.jpg');
        }

        if (empty($path) && $type == 'thumb') {
            return base_url('public/images/no_logo_thumb.jpg');
        }

        if ($type == 'thumb') {
            $path_local = 'public/uploads/employer/thumb';
        } else {
            $path_local = 'public/uploads/employer';
        }

        if (file_exists($path_local . '/' . $path)) {
            return base_url($path_local . '/' . $path);
        }

        if ($type != '') {

            $path_info = pathinfo($path);
            $path = str_replace('.' . $path_info['extension'], '', $path);

            $path = $path . '_' . $type . '.' .  $path_info['extension'];
        }

        return file_url($path, $path_local);
    }
}

if (!function_exists('file_ext')) {
    function file_ext($file_name)
    {
        return pathinfo($file_name, PATHINFO_EXTENSION);
    }
}

if (!function_exists('img_loading_url')) {
    function img_loading_url()
    {
        return base_url('public/images/1488.gif');
    }
}

