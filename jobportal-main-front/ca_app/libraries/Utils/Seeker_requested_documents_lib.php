<?php
class Seeker_requested_documents_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        $this->load->library('Recruitment_process');
    }

    private function get_data_requested_documents($process_id, $seeker_id)
    {
        $process = $this->Recruitment_process->find($process_id);
        
        $job_id = $process->job_ID;

        $list = [];
        $documents = $this->Recruitment_process_contract_document->get_documents($job_id);

        foreach ($documents as $doc) {

            if (!$doc->document_id) {
                continue;
            }
            
            if ($doc->option_type_id == 1) {

                $attachments = $this->Recruitment_contract_document->all([
                    'seeker_id' => $seeker_id,
                    'document_id' => $doc->id
                ]);

                $list[] = [
                    'folder' => $doc->name,
                    'fn_query' => function($data) use ($attachments) {        
                
                        $data_file = [];
    
                        foreach ($attachments as $doc) {
                            
                            $file_part = explode('.', $doc->name);
                            $file_extension = end($file_part);
                            $file_name = str_replace('.' . $file_extension, '', $doc->name);

                            $data_file[] = [
                                'file' => $file_name,
                                'path_file' => $doc->file_path
                            ];
                        }
    
                        return $data_file;
                    }
                ];
            }

            if ($doc->option_type_id == 2) {

                if ($doc->id == 1) {

                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {        
                            $files = $data['identity_documents'];
                            $data_file = [];
        
                            foreach ($files as $doc) {
        
                                $data_file[] = [
                                    'file' => $doc->name,
                                    'path_file' => $doc->path
                                ];
                            }
        
                            return $data_file;
                        }
                    ];
                }

                if ($doc->id == 4) {
            
                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {
                            $file = $data['domicile_affidavit'];
                            $data_file = [];
        
                            if ($file) {
                                $data_file[] = [
                                    'file' => 'Declaración-Jurada-Domicilio',
                                    'path_file' => $file->attach_file_name
                                ];
                            }
        
                            return $data_file;
                        },
                    ];
                }

                if ($doc->id == 5) {
                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {
                            $file = $data['declaration_5th_category'];
                            $data_file = [];
        
                            if (!$file) {
                                return [];
                            }
        
                            $data_file[] = [
                                'file' => 'Declaración-Jurada-5ta-categoría',
                                'path_file' => $file->attach_file_name
                            ];
                            
                            return $data_file;
                        }
                    ];
                }

                if ($doc->id == 7) {
                
                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {
                            $file = $data['candidate_spouse'];
                            $data_file = [];

                            if ($file) {
                                $data_file[] = [
                                    'file' => 'Copia-DNI-Cónyuge',
                                    'path_file' => $file->attached_document_number
                                ];
                            }

                            return $data_file;
                        },
                    ];
                }

                if ($doc->id == 8) {
                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {
                            $file = $data['candidate_spouse'];
                            $data_file = [];
        
                            if ($file) {
                                $data_file[] = [
                                    'file' => 'Certificado-convivencia-cónyuge',
                                    'path_file' => $file->kinship_cert_attached
                                ];
                            }
        
                            return $data_file;
                        },
                    ];
                }

                if ($doc->id == 9) {

                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {
                            $result_files = $data['candidate_studies'];
                            $data_file = [];
        
                            foreach ($result_files as $key => $row) {
        
                                if (empty($row->attached_certificate)) {
                                    continue;
                                }
        
                                $data_file[] = [
                                    'file' => 'Certificado-de-Estudios-' . ($key + 1),
                                    'path_file' => $row->attached_certificate
                                ];  
                            }
                            return $data_file;
                        },
                    ];
                }
    
                if ($doc->id == 10) {
                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {
                            $result_files = $data['candidate_children'];
                            $data_file = [];
        
                            foreach ($result_files as $key => $row) {
                                if (empty($row->attached_document_number)) {
                                    continue;
                                }
        
                                $data_file[] = [
                                    'file' => 'Copia-DNI-Hijos-Menores-Edad-' . ($key + 1),
                                    'path_file' => $row->attached_document_number
                                ];  
                            }
                            return $data_file;
                        },
                    ];
                }

                if ($doc->id == 13) {
                    
                    $list[] = [
                        'folder' => $doc->name,
                        'fn_query' => function($data) {
                            $result_files = $data['candidate_experiences'];
                            $data_file = [];
        
                            foreach ($result_files as $key => $row) {
        
                                if (empty($row->attached_certificate)) {
                                    continue;
                                }
        
                                $data_file = [];
        
                                $data_file[] = [
                                    'file' => 'Certificados-trabajo-' . ($key + 1),
                                    'path_file' => $row->attached_certificate
                                ];  
                            }
                            return $data_file;
                        }
                    ];
                }
            }
        }

        return $list;
    }

    public function download(
        $job_id,
        $candidate_id = 0
    )
    {     
        $this->load->model('Recruitment_process_contract_document');
        $this->load->model('Recruitment_contract_document');
        $this->load->library('storage_lib', null, 'Storage_lib');
        
        $candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);

        $zip = new ZipArchive();
        $zip_name = 'DOC solicitados ' . $candidate->document_number . '_' . $candidate->first_name . '_' . $candidate->last_name . '.zip';
        $zip_name = str_replace(' ', '_', $zip_name);

        $path_file_zip = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5($zip_name);

        $zip->open($path_file_zip, ZipArchive::CREATE);
        
        $data_documents = $this->get_data_requested_documents($job_id, $candidate_id);        
        $result_documents = data_candidate_required_documents($job_id, $candidate_id);

        $path_files = [];

        foreach ($data_documents as $key => $data_doc) {

            $folder = str_pad(($key + 1), 2, '0', STR_PAD_LEFT) . ' - ' . $data_doc['folder'];
            $zip->addEmptyDir($folder);
            
            $data_file = $data_doc['fn_query']($result_documents);
            
            if (empty($data_file)) {
                continue;
            } 

            foreach ($data_file as $file) {
        
                $file_name = $file['file'];

                if (!$file_name) {
                    continue;
                }

                $current_path_file = $this->get_file($file['path_file']);

                if ($current_path_file === false) {
                    continue;
                }

                $file_part = explode('.', $current_path_file);
                $file_extension = end($file_part);
                $to_path_file = $folder . DIRECTORY_SEPARATOR . $file_name . '.' . $file_extension;
                $zip->addFile($current_path_file, $to_path_file);

                $path_files[] = $current_path_file;
            }
        }

        $zip->close();

        header("Content-type: application/octet-stream");
        header("Content-disposition: attachment; filename=" . $zip_name);
    
        readfile($path_file_zip);
        
        foreach ($path_files as $key => $path_file) {
            @unlink($path_file);
        }
        
        @unlink($path_file_zip);
    }

    public function get_file($path_file)
    {
        $file_value = $this->Storage_lib->get($path_file);
                        
        if ($file_value === false) {
            return false;
        }

        $file_name = @end(explode('/', $path_file));        
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace('.', '', (string)microtime(true)) . '_' . $file_name;
        
        @file_put_contents($path , $file_value);

        return $path;
    }
}
