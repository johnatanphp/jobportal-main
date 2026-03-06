<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Geo_lite2_lib
{

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get_country()
    {
        $ip_address = $this->input->ip_address();
        $path_db_file = $this->get_path_db_file();
        
        try {
            $reader = new \GeoIp2\Database\Reader($path_db_file);

            $record = $reader->country($ip_address);

            // $country_name = $record->country->name;
            // $country_code = $record->country->isoCode;

            return $record->country;    
        } catch (\Exception $e) {
            //var_dump($e->getMessage());
        }

        return null;
    }

    public function get_path_db_file()
    {
        $db_file = 'GeoLite2-Country.mmdb';

        // Verificar si ya existe la db en el servidor
        $path_file = '/usr/local/share/geoip/' . $db_file;

        if (file_exists($path_file)) {
            return $path_file;
        }

        $folder_tmp = sys_get_temp_dir();

        $path_file = $folder_tmp . DIRECTORY_SEPARATOR . $db_file;

        // Verificar si existe l sdb en la carpeta temporal
        if (file_exists($path_file)) {
            return $path_file;
        }

        // Si no existe descargarla 
        if ($this->download_file($folder_tmp, $db_file)) {
            return $path_file;
        }

        return null;
    }

    private function download_file($dir_save, $filename)
    {
        $db_url = 'https://overall-portal-de-empleo.s3.us-east-1.amazonaws.com/static/geoip/GeoLite2-Country.mmdb';

        $path_save = $dir_save . DIRECTORY_SEPARATOR . $filename;

        if (!is_dir($dir_save)) {
            mkdir($dir_save, 0777, true);
        }

        return copy($db_url, $path_save);
    }
}
