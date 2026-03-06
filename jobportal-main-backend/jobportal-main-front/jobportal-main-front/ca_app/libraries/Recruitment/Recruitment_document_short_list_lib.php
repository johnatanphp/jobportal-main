<?php

class Recruitment_document_short_list_lib 
{   
    public function __construct(){}

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function download($job_id = 0, $candidate_id = 0)
    {     
        $this->db->from('tbl_recruitment_attached_documents');
        $this->db->where('seeker_ID', $candidate_id);
        $this->db->where('job_id', $job_id);
        $this->db->where_in('key', ['report_competence', 'work_reference']);

        $result = $this->db->get()->result();

        $documents = [
            'report_competence' => [
                'folder' => 'Informes por competencia',
                'file' => 'Informe-por-competencia'
            ],
            'work_reference' => [
                'folder' => 'Referencias laborales',
                'file' => 'Referencias-laborales'
            ]
        ];

        $zip = new ZipArchive();
        $zip_name = 'Documentos-R&S-sl-' . uniqid(time()) . '.zip';

        $path_file_zip = sys_get_temp_dir() . '/' . $zip_name;

        $zip->open($path_file_zip, ZipArchive::CREATE);
        
        $index = 1;

        foreach ($result as $key => $row) {
        
            if (!isset($documents[$row->key])) {
                continue;
            } 

            $folder = $documents[$row->key]['folder'];
            $file_name = $documents[$row->key]['file'];

            $file_path = $row->file_source;
            $file_path_temp = $this->get_file($file_path);

            if ($file_path_temp === false) {
                continue;
            }
            
            $file_part = explode('.', $file_path);
            $file_extension = end($file_part);
            $to_path_file = $folder . "/" . $file_name . "-" . ($index++) . "." . $file_extension;

            $zip->addEmptyDir($folder);
            $zip->addFile($file_path_temp, $to_path_file);
        }
        
        $zip->close();

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=" . $zip_name);

        readfile($path_file_zip);
        unlink($path_file_zip);
    }

    public function check_download($job_id = 0, $candidate_id = 0)
    {
        $this->db->from('tbl_recruitment_attached_documents');
        $this->db->where('seeker_ID', $candidate_id);
        $this->db->where('job_id', $job_id);
        $this->db->where_in('key', ['report_competence', 'work_reference']);

        $result = $this->db->get()->result();

        return count($result) > 0;
    }

    private function get_file($file_path = '')
    {
        if (empty($file_path)) {
            return false;
        }

        $file_content = file_get_contents('https://overall-portal-de-empleo.s3.amazonaws.com/' . $file_path);

        if (!$file_content) {
            return false;
        }

        $file_path_tmp = false;

        try {  
            $file_path_tmp = sys_get_temp_dir() . '/' . md5($file_path);

            if (!file_put_contents($file_path_tmp, $file_content)) {
                return false;
            }
           
        } catch (\Exception $e) {
            $file_path_tmp = false;
        } 
       
        return $file_path_tmp;
    }
}
