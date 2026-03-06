<?php

class Posted_job_list_export
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

    private function build_header()
    {
        $header[] = 'Empleo';	
        $header[] = 'Fecha de publicación';
        $header[] = 'Link';	
        $header[] = 'Publicado por';	

        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }

    private function data()
    {
        $filter = $this->filters;

        $this->db->select([
            'jobs.ID AS job_id',
            'jobs.job_title AS job_title', 
            'jobs.dated AS job_dated', 
            'jobs.job_slug AS job_slug', 
            'job_created_by.first_name AS created_by_first_name'
        ]);

        $this->db->from('tbl_post_jobs jobs');
        $this->db->join('tbl_employers job_created_by', 'job_created_by.ID=jobs.employer_ID');
        $this->db->where('jobs.job_ignore', 0);

        if (isset($this->filters['employer_id']) && trim($this->filters['employer_id']) != '') {
            $this->db->where('jobs.employer_ID', trim($this->filters['employer_id']));
        }

        if (isset($this->filters['status']) && $this->filters['status']) {
            $this->db->where('jobs.sts', 'active');
        }

        if (isset($this->filters['status']) && $this->filters['status'] == 0) {
            $this->db->where('jobs.sts', 'inactive');
        }

        if (isset($this->filters['company_id'])) {
            $this->db->where('jobs.company_ID', $this->filters['company_id']);
        }

        if (isset($this->filters['expired']) && $this->filters['expired'] == 0) {
            $this->db->where('jobs.last_date>', date('Y-m-d'));
        }

        if (isset($this->filters['expired']) && $this->filters['expired']) {
            $this->db->where('pj.last_date<', date('Y-m-d'));
        }

        return $this->db->get()->result();
    }

    public function build_data()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $requests = $this->data();

        foreach ($requests as $index => $row) {
            $col = [];
            $col[] = $row->job_title;
            $col[] = $row->job_dated;
            $col[] = site_url('jobs/' . $row->job_slug);
            $col[] = $row->created_by_first_name;

            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );
        }

        return true;
    }

    public function download($filename = 'reporte-excel.xlsx')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename .'"');
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
}
