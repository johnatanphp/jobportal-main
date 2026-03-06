<?php

if (!function_exists('get_date_ranges')) {
	function get_date_ranges() {

		$today =  date('Y-m-d');
		$yesterday = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$six_days_ago = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-6,date("Y")));
		$start_of_this_month = date('Y-m-d', mktime(0,0,0,date("m"),1,date("Y")));
		$end_of_this_month = date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
		$start_of_last_month = date('Y-m-d', mktime(0,0,0,date("m")-1,1,date("Y")));
		$end_of_last_month = date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime((date('m') - 1).'/01/'.date('Y').' 00:00:00'))));
		$start_of_this_year =  date('Y-m-d', mktime(0,0,0,1,1,date("Y")));
		$end_of_this_year =  date('Y-m-d', mktime(0,0,0,12,31,date("Y")));
		$start_of_last_year =  date('Y-m-d', mktime(0,0,0,1,1,date("Y")-1));
		$end_of_last_year =  date('Y-m-d', mktime(0,0,0,12,31,date("Y")-1));
		$start_of_time =  date('Y-m-d', 0);

		$previous_week = strtotime("-1 week +1 day");
		$current_week = strtotime("-0 week +1 day");

		$previous_start_week = strtotime("last monday midnight",$previous_week);
		$previous_end_week = strtotime("next sunday",$previous_start_week);

		$previous_start_week = date("Y-m-d",$previous_start_week);
		$previous_end_week = date("Y-m-d",$previous_end_week);

		$current_start_week = strtotime("last monday midnight",$current_week);
		$current_end_week = strtotime("next sunday",$current_start_week);

		$current_start_week = date("Y-m-d",$current_start_week);
		$current_end_week = date("Y-m-d",$current_end_week);


		$current_month = date('m');
		$current_year = date('Y');


		if($current_month>=1 && $current_month<=3)
		{
			$start_of_this_quarter = strtotime('1-January-'.$current_year); 
			$end_of_this_quarter = strtotime('31-March-'.$current_year);
		}
		elseif($current_month>=4 && $current_month<=6)
		{
			$start_of_this_quarter = strtotime('1-April-'.$current_year);
			$end_of_this_quarter = strtotime('30-June-'.$current_year); 
		}
		elseif($current_month>=7 && $current_month<=9)
		{
			$start_of_this_quarter = strtotime('1-July-'.$current_year);
			$end_of_this_quarter = strtotime('30-September-'.$current_year);
		}
		elseif($current_month>=10 && $current_month<=12)
		{
			$start_of_this_quarter = strtotime('1-October-'.$current_year);
			$end_of_this_quarter = strtotime('31-December-'.$current_year);
		}
		$start_of_this_quarter = date("Y-m-d", $start_of_this_quarter);
		$end_of_this_quarter = date("Y-m-d", $end_of_this_quarter);


		if($current_month>=1 && $current_month<=3)
		{
			$start_of_last_quarter = strtotime('1-October-'.($current_year-1));
			$end_of_last_quarter = strtotime('31-December-'.$current_year-1); 
		} 
		elseif($current_month>=4 && $current_month<=6)
		{
			$start_of_last_quarter = strtotime('1-January-'.$current_year);
			$end_of_last_quarter = strtotime('31-March-'.$current_year); 
		}
		elseif($current_month>=7 && $current_month<=9)
		{
			$start_of_last_quarter = strtotime('1-April-'.$current_year);
			$end_of_last_quarter = strtotime('30-June-'.$current_year);
		}
		elseif($current_month>=10 && $current_month<=12)
		{
			$start_of_last_quarter = strtotime('1-July-'.$current_year);
			$end_of_last_quarter = strtotime('30-September-'.$current_year);
		}

		$start_of_last_quarter = date("Y-m-d", $start_of_last_quarter);
		$end_of_last_quarter = date("Y-m-d", $end_of_last_quarter);

		return [
			$today. '/' . $today => 'Hoy',
			$yesterday. '/' . $yesterday => 'Ayer',
			$six_days_ago. '/' . $today => 'Últimos 7 días',
			$current_start_week. '/' . $current_end_week => 'Esta semana',
			$previous_start_week. '/' . $previous_end_week => 'Semana pasada',
			$start_of_this_month . '/' . $end_of_this_month => 'Este mes',
			$start_of_last_month . '/' . $end_of_last_month => 'Mes pasado',
			//$start_of_this_quarter . '/' . $end_of_this_quarter	=> lang('reports_this_quarter'),
			//$start_of_last_quarter . '/' . $end_of_last_quarter	=> lang('reports_last_quarter'),
			$start_of_this_year . '/' . $end_of_this_year => 'Este año',
			$start_of_last_year . '/' . $end_of_last_year => 'Año pasado',
			$start_of_time . '/' . 	$today => 'Todo',
		];
	}
}



if (!function_exists('_date_locale_format')) {
	function _date_locale_format($timestamp, $pattern, $locale = null) 
	{
		if (empty($locale)) {
			$locale = 'es_PE.utf8';		
		}

		$formatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL, null, IntlDateFormatter::GREGORIAN, $pattern);
		if ($formatter === null) {
			throw new \Exception(intl_get_error_message());
		}

		return $formatter->format($timestamp);
	}
}