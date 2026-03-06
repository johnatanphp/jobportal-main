<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Foto_perfil extends REST_Controller
{
    public function index_get()
    {
        $nro_doc_identidad = trim($this->get('nro_doc_identidad'));

        if (!$nro_doc_identidad) {
            $this->response(['error' => 'nro_doc_identidad debe ser ingresado', 'status' => false], 200);
            exit;
        }

        $this->db->select([
            'photo'
        ]);

        $this->db->from('tbl_job_seekers');
        $this->db->where('document_number', $nro_doc_identidad);
        //$this->db->where('document_number IS NOT NULL AND document_number != ""', false, false);
        $this->db->order_by('photo IS NULL, photo DESC', false, false);

        $this->db->limit(1);

        $row_seeker = $this->db->get()->row();

        $data_seeker = [];

        if ($row_seeker) {
            $data_seeker = [
                'url_foto_perfil' => img_pic_candidate($row_seeker->photo)
            ];
        }

        $this->response(['data' => $data_seeker], 200);
    }
}
