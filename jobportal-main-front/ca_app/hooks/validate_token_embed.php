<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validate_token_embed 
{
    public function index()
    {
        $CI = & get_instance();
        $folder = $CI->uri->segment(1);
        
        if ($folder != 'embed') {
            return;
        }

        if (isset($_POST[$CI->config->item('embed_token_name')])) {
            $token = $CI->input->post($CI->config->item('embed_token_name'), '');
        } else {
            $token = $CI->input->get($CI->config->item('embed_token_name'), '');
        }
        
        if ($token != $CI->config->item('embed_token_value')) {
            show_404();
        }
    }
}
