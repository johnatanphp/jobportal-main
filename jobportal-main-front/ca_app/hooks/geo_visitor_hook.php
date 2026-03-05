<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Geo_visitor_hook
{
    public function check()
    {
        if (is_cli()) {
            return;
        }

        $ci = & get_instance();
        
        if ($ci->session->userdata('visitor')) {
            return;
        }

        $folder = $ci->uri->segment(1);

        if ($folder == 'api') {
            return;
        } 
        
        $ci->load->library('GeoLite2/Geo_lite2_lib');
		$country = $ci->geo_lite2_lib->get_country();

        $origin_country = @$country->isoCode;

        $country_row = $ci->Country->find([
            'iso_3166_1_alpha2' => $origin_country,
            'has_operation_overall' => 1
        ]);

        $ci->session->set_userdata([
            'visitor' => [
               'origin_country_id' => $country_row ? $country_row->ID : 56,
               'selected_country_id' => $country_row ? $country_row->ID : 56
            ]
        ]);
    }
}
