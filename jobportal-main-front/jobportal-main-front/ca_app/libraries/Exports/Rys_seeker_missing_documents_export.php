<?php

class Rys_seeker_missing_documents_export
{
    private $spreadsheet;
    private $filters;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;    
    }

    public function __construct($filters = null)
    {
        
        $this->spreadsheet = null;

        if (is_null($filters) === false) {
            $this->build($filters);
        }
    }

    public function init()
    {
        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);

        $sheet = $this->spreadsheet->getActiveSheet();

        $this->db->from('tbl_recruitment_contract_document_types rcdt');
        $this->db->where_in('id', [
            1,
            2,
            3,
            //4,
            //5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15
        ]);
        $this->db->where('company_id', 1);
        $this->db->where('active', 1);
        $this->db->order_by('id', 'ASC');
        
        $this->documents = $this->db->get()->result();
    }

    public function build($filters = [])
    {
        $this->filters = $filters;

        $this->init();
        $this->build_header();
        
        if ($this->build_data()) {
            return $this;
        }

        return false;
    }

    private function data()
    {
        $seeker_ids = $this->filters['seeker_ids'];
        $result_documents = $this->documents;

        $seeker_identification_documents = $this->get_seeker_identification_documents($seeker_ids);
        $rtps_form = $this->get_rtps_form($seeker_ids);
        $spouse = $this->get_spouse($seeker_ids);

        $minor_childrens = $this->get_minor_childrens($seeker_ids);
        $seeker_academic = $this->get_seeker_academic($seeker_ids);
        $seeker_experiences = $this->get_seeker_experiences($seeker_ids);
        $document_contract = $this->get_document_contract($seeker_ids);

        $this->db->select([
            'js.ID AS seeker_id',
            'js.first_name',
            'js.last_name',
            'js.document_type',
            'js.document_number',
            'js.email',
            'idc.name as doc_type_name',
            'pj.ID as job_id',
            'pj.request_ID AS request_id',
            'pj.job_title',
            'e_recruiter.first_name AS recruiter_name',
            'e_responsible.first_name AS responsible_name',
            'sr.consultant_name',
            'sr.client_company_name',
            'sr.cost_center',
            'sr.type_expense',
            'sr.request_type',
        ]);
        $this->db->from('tbl_job_seekers js');
        $this->db->join('tbl_recruitment_candidates rc', 'rc.seeker_ID=js.ID');
        $this->db->join('tbl_post_jobs pj', 'pj.ID=rc.job_ID');
        $this->db->join('tbl_staff_requests sr', 'sr.ID=pj.request_ID');
        $this->db->join('tbl_employers e_recruiter', 'e_recruiter.ID=sr.recruiter_ID');
        $this->db->join('tbl_employers e_responsible', 'e_responsible.ID=sr.employer_ID');
        $this->db->join('tbl_identity_document_types idc', 'js.document_type=idc.id', 'left');
        
        $this->db->where_in('rc.seeker_ID', $seeker_ids);
        $this->db->where('rc.stage', 7);
        $this->db->where('rc.contracted', 0);
        $this->db->where('rc.discarded', 0);

        $result_seekers = $this->db->get()->result();

        $seeker_doc_missing = false;
        $seeker_doc_data = [];

        foreach ($result_seekers as $seeker) {
            foreach ($result_documents as $document_row) {

                if ($document_row->id == 1) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                    if (isset($seeker_identification_documents[$seeker->seeker_id])) {
                        $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = true;
                    }
                    continue;
                }

                if ($document_row->id == 3) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                    if (isset($rtps_form[$seeker->seeker_id][$seeker->job_id]) && 
                        ($rtps_form[$seeker->seeker_id][$seeker->job_id])->evicertia_status == 3) {
                        $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id ] = true;
                    }
                    continue;
                }

                if ($document_row->id == 7) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                    if (isset($spouse[$seeker->seeker_id][$seeker->job_id]) && 
                        ($spouse[$seeker->seeker_id][$seeker->job_id])->attached_document_number) {
                        $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = true;
                    }
                }

                if ($document_row->id == 8) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                    if (isset($spouse[$seeker->seeker_id][$seeker->job_id]) && 
                        ($spouse[$seeker->seeker_id][$seeker->job_id])->kinship_cert_attached) {
                        $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = true;
                    }
                    continue;
                }

                if ($document_row->id == 9) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                    if (isset($seeker_academic[$seeker->seeker_id])) {
                        $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = true;
                    }
                    continue;
                }

                if ($document_row->id == 10) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                    if (isset($minor_childrens[$seeker->seeker_id][$seeker->job_id])) {
                        foreach ($minor_childrens[$seeker->seeker_id][$seeker->job_id] as $doc_row) {
                            if (!$doc_row->attached_document_number) {
                                break;
                            }
                        }
                        
                        $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = true;
                    }   
                    continue;     
                }

                if ($document_row->id == 13) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                    if (isset($seeker_experiences[$seeker->seeker_id])) {
                        foreach ($seeker_experiences[$seeker->seeker_id] as $doc_row) {
                            if ($doc_row->attached_certificate) {
                                $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = true;
                                break;
                            }
                        }
                    }   
                    continue;     
                }

                $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = false;
                if (isset($document_contract[$document_row->id][$seeker->seeker_id])) {
                    $seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id] = true;
                }
            }
        }   

        //Filtrar
        $seeker_data = [];

        foreach ($result_seekers as $seeker) {
            foreach ($result_documents as $document_row) {
                if ($document_row->id == 7 && !@$spouse[$seeker->seeker_id][$seeker->job_id]) {
                    continue;
                }

                if ($document_row->id == 8 && !@$spouse[$seeker->seeker_id][$seeker->job_id]) {
                    continue;
                }

                if ($document_row->id == 10 && count(@$minor_childrens[$seeker->seeker_id][$seeker->job_id]) == 0) { 
                    continue;     
                }

                //Si los documentos son para extranjeros y el postulantes es peruano ignorar
                if (($document_row->id == 14 || $document_row->id == 15) && $seeker->document_type == 1) {
                    continue;
                }

                if (isset($seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id]) && 
                   !$seeker_doc_data[$seeker->seeker_id][$seeker->job_id][$document_row->id]) {

                    $seeker->documents = $seeker_doc_data[$seeker->seeker_id][$seeker->job_id];
                    $seeker_data[] = $seeker;

                    break;
                }
            }
        }

        $seeker_rows = [];
       
        //Register data
        foreach ($seeker_data as $seeker) {
            $seeker_row = $seeker;
            
            foreach ($result_documents as $document_row) {
                $seeker_row->{$document_row->id} = $seeker->documents[$document_row->id] ? 'Ok' : 'Pendiente';
            }

            $seeker_rows[] = $seeker_row;
        }

        return $seeker_rows;
    }

    private function build_header()
    {
        $header[] = 'N°';
        $header[] = 'Nombres';
        $header[] = 'Apellidos';
        $header[] = 'Doc. Tipo';
        $header[] = 'Doc. Numero';
        $header[] = 'Email';
        $header[] = 'RyS ID';
        $header[] = 'Req. ID';
        $header[] = 'Solicitante Req';
        $header[] = 'Responsable Rys';
        $header[] = 'Consultora';
        $header[] = 'Cliente';
        $header[] = 'Centro de costo';
        $header[] = 'Tipo Egreso';
        $header[] = 'Tipo Solicitud';
        $header[] = 'Empleo / Puesto';

        foreach ($this->documents as $key => $row_doc) {
            $header[] = $row_doc->name;
        }
       
        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }

    public function build_data()
    {
        $seekers = $this->data();

        if (count($seekers) == 0) {
            return false;
        }

        $sheet = $this->spreadsheet->getActiveSheet();


        foreach ($seekers as $index => $row) {

            $col = [];
            $col[] = ($index + 1);
            $col[] = $row->first_name;
            $col[] = $row->last_name;
            $col[] = $row->doc_type_name;
            $col[] = $row->document_number;
            $col[] = $row->email;
            $col[] = $row->job_id;
            $col[] = $row->request_id;
            $col[] = $row->recruiter_name;
            $col[] = $row->responsible_name;
            $col[] = $row->consultant_name;
            $col[] = $row->client_company_name;
            $col[] = $row->cost_center;
            $col[] = $row->type_expense;
            $col[] = $row->request_type;
            $col[] = $row->job_title;

            foreach ($this->documents as $key => $row_doc) {
                $col[] = $row->{$row_doc->id};
            }
       
            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );

            $this->spreadsheet->getActiveSheet()
                ->getCell('E' . ($index + 4))
                ->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }

        return true;
    }

    public function download($filename = 'reporte-excel')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function save($filepath = '')
    {
        \PhpOffice\PhpSpreadsheet\Shared\File::setUseUploadTempDirectory(true);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, "Xlsx");
        $writer->save($filepath);
    }

    public function output()
    {
        ob_start();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter(
            $this->spreadsheet, 
            'Xlsx'
        );
        $writer->save('php://output');
        $result_object = ob_get_clean();
    }

    private function get_seeker_identification_documents($seeker_ids)
    {   
        $this->db->from('tbl_seeker_identification_documents sid');
        $this->db->where_in('seeker_ID', $seeker_ids);
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $key => $row) {
            $list[$row->seeker_ID] = $row;
        }

        return $list;
    }

    private function get_rtps_form($seeker_ids)
    {
        $this->db->from('tbl_seeker_form_rtps rtps');
        $this->db->where_in('seeker_ID', $seeker_ids);
        
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $key => $row) {
            $list[$row->seeker_ID][$row->job_id] = $row;
        }

        return $list;
    }

    private function get_spouse($seeker_ids)
    {
        $this->db->from('tbl_seeker_form_rtps rtps');
        $this->db->join('tbl_form_rtps_rightful_claimants rtps_rightful', 'rtps.ID=rtps_rightful.form_ID');
        $this->db->where('kinship', 1); //Conyuge

        $this->db->where_in('rtps.seeker_ID', $seeker_ids);
        
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $key => $row) {
            $list[$row->seeker_ID][$row->job_id] = $row;
        }

        return $list;
    }

    private function get_minor_childrens($seeker_ids)
    {
        $this->db->from('tbl_seeker_form_rtps rtps');
        $this->db->join('tbl_form_rtps_rightful_claimants rtps_rightful', 'rtps.ID=rtps_rightful.form_ID');
        $this->db->where_in('kinship', [5, 6]);

        $this->db->where_in('rtps.seeker_ID', $seeker_ids);
        
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $key => $row) {
            $list[$row->seeker_ID][$row->job_id][] = $row;
        }

        return $list;
    }

    public function get_seeker_academic($seeker_ids)
    {
        $this->db->from('tbl_seeker_academic');
        $this->db->where_in('seeker_ID', $seeker_ids);
        
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $key => $row) {
            $list[$row->seeker_ID] = $row;
        }

        return $list;
    }

    private function get_seeker_experiences($seeker_ids)
    {
        $this->db->from('tbl_seeker_experience');
        $this->db->where_in('seeker_ID', $seeker_ids);
        
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $key => $row) {
            $list[$row->seeker_ID][] = $row;
        }

        return $list;
    }

    public function get_document_contract($seeker_ids)
    {
        $this->db->from('tbl_recruitment_contract_documents');
        $this->db->where_in('seeker_id', $seeker_ids);
       
        
        $results = $this->db->get()->result();

        $list = [];

        foreach ($results as $key => $row) {
            $list[$row->document_id][$row->seeker_id][] = $row;
        }

        return $list;
    }  
}
