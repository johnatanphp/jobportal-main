<?php

class Translate_routes {
	
	private $ci = null;

	public function __construct(){

		$this->ci = & get_instance();
	}

	public function scan_path($path, $allowed_filters, $callback_func)
	{
		$params = array();

		foreach ($allowed_filters as $_key => $key) {
			$pos = strpos($path, $key . '-');

			if ($pos !== false) {
				$pos_start = $pos + (strlen($key) + 1);
				$pos_end = strlen($path);
				$value = substr($path, $pos_start, $pos_end);
				$path = $callback_func($path, $key, $value, $params);
			}
		}

		return $params;
	}

	public function get_params_search_jobs($path, $city)
	{
		$allowed_filters = array('industry', 'search');

		$params = $this->scan_path($path, $allowed_filters, function($p, $k, $v, &$pr){

			if ($k == 'industry') {

				$industries = $this->ci->Industry->get_all_industries();

				foreach ($industries as $key => $row) {

					$_pos = strpos($v, $row->slug);

					if ($_pos !== false) {
						$p = preg_replace('/' . $row->slug . '\-?/', '', $v);
						
						$pr['industry'] = $row->industry_name;
						return $p;
					}
				}
				return $p;
			} else {

				$pr[$k] = $v;
			}

		});

		if ($city != '') {

			$cities = $this->ci->City->get_all_cities();

			foreach ($cities as $key => $row) {
	
				$city_slug = make_slug($row->city_name);

				if ($city_slug == $city) {
					$params['city'] = $row->city_name;
				}
			}
		}

		return $params;
	}	
}
