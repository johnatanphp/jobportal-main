<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Crear extends REST_Controller 
{   
    private $civil_status = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4
    ];

    private $genders = [
        1 => 1,
        2 => 2
    ];

    private $doc_iden_types = [
        1 => 1,
        4 => 4,
        7 => 7,
        12 => 12,
        26 => 26
    ];

    private $disabilities = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4
    ];

    public function index_post()
    {
        $this->load->model('Job_seeker');
        $this->load->model('Jobseeker_additional_info');

        if ($this->validate() === false) {
            return;
        }

        $current_date = date('Y-m-d H:i:s');
        
        $seeker = $this->db->get_where('tbl_job_seekers', [
            'email' => trim($this->post('email'))
        ])->row();

        if ($seeker) {
            $this->response([
                'status' => false, 
                'message' => '¡El email ya existe!', 
                'data' => [
                    'postulante' => ['id' => trim($seeker->ID)]
                ]
            ] , 200);
            exit;
        }

        $seeker = $this->db->get_where('tbl_job_seekers', [
            'document_number' => trim($this->post('doc_identidad_numero'))
        ])->row();

        if ($seeker) {
            $this->response([
                'status' => false, 
                'message' => '¡El número de documento ya existe!', 
                'data' => [
                    'postulante' => ['id' => trim($seeker->ID)]
                ]
            ] , 200);
            exit;
        }

        $password = trim($this->post('contrasena'));

        if (strlen($password) <= 7) {
            $this->response([
                'status' => false,
                'message' => 'La contraseña debe contener como mínimo 8 caracteres',
                'data' => []
            ], 200);
            return;
        }

        $city = trim($this->post('ubicacion'));
        $country = ucfirst(mb_strtolower(trim($this->post('pais'))));

        if ($country == 'Perú' || $country == 'Peru') {
            $city_data = explode(",", $city);

            $city_map_data = array_map(function($item){
                return ucfirst(mb_strtolower(trim($item)));
            }, $city_data);

            $city = join(", ", $city_map_data);
        }
        
        $nationality = mb_strtolower(trim($this->post('nacionalidad')));

        if ($country == 'Peru' || $country == 'Perú') {
            $nationality = '56';
            $country = '56';
        }

        $job_seeker_array = [
			'first_name' => trim($this->post('nombres')),
			'paternal_last_name' => trim($this->post('apellido_paterno')),
			'maternal_last_name' => trim($this->post('apellido_materno')),
			'last_name' => trim($this->post('apellido_paterno')) . ' ' . trim($this->post('apellido_materno')),
			'document_number' => trim($this->post('doc_identidad_numero')),
			'document_type' => isset($this->doc_iden_types[trim($this->post('doc_identidad_tipo'))]) ? $this->doc_iden_types[trim($this->post('doc_identidad_tipo'))] : null,
			'email' => trim($this->post('email')),
			'password' => do_hashing($password),
			'dob' => trim($this->post('fecha_nacimiento')),
			'mobile' => trim($this->post('movil_numero')),
			'country' => $country,
			'city' => $city,
			'nationality' => $nationality,
			'gender' => isset($this->genders[trim($this->post('sexo'))]) ? $this->genders[trim($this->post('sexo'))] : null,
			'ip_address' => '',
			'dated' => $current_date,
			'civil_status' => isset($this->civil_status[trim($this->post('estado_civil'))]) ? $this->civil_status[trim($this->post('estado_civil'))] : null,
            'disability' => isset($this->disabilities[trim($this->post('discapacidad'))]) ? $this->disabilities[trim($this->post('discapacidad'))] : null
		];
		
		$seeker_id = $this->Job_seeker->add_job_seekers($job_seeker_array);

        if (!$seeker_id) {
            $this->response([
                'status' => false, 
                'message' => '¡El registro no pudo ser procesado!',
                'data' => []
            ] , 200);
            exit;
        }

		$this->Jobseeker_additional_info->add(['seeker_ID' => $seeker_id]);
		$this->Job_seeker->accept_legal_terms($seeker_id);

		$this->db->insert('tbl_seeker_config', [
            'key' => 'receive_job_ads',
            'value' => 0,
            'seeker_ID' => $seeker_id
        ]);

        if ($this->post('email_notificacion') == 1) {
            $data_email = array(
                'jobseeker_name' => trim($this->post('nombres'))
            );
            
            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($this->input->post('email'));
            
            $mail_message = load_email_view('email/registration_jobseeker', $data_email);
            
            $this->email->subject('Registro postulante');
            $this->email->message($mail_message);     
            $this->email->send();
        }

        $this->response([
            'status' => true, 
            'message' => 'Postulante creado con éxito', 
            'data' => [
                'postulante' => ['id' => trim($seeker_id)]
            ]
        ] , 200);
    }

    private function validate()
    {
        $post_fields = $this->post();

        $fields = [
            'email',
            'contrasena',
            'doc_identidad_tipo',
            'doc_identidad_numero',
            'nombres',
            'apellido_paterno',
            'apellido_materno',
            'fecha_nacimiento',
            'estado_civil',
            'sexo',
            'pais',
            'nacionalidad',
            //'movil_numero',
            'ubicacion'
        ];

        foreach ($fields as $field) {

            if (!isset($post_fields[$field])) {
                $this->response(['status' => false, 'message' => '¡El campo ' . $field .' es requerido!', 'data' => []] , 200);
                return false;
            }

            if (trim($post_fields[$field]) == '') {
                $this->response(['status' => false, 'message' => '¡El campo ' . $field .' no puede estar vacío!', 'data' => []] , 200);
                return false;
            }
        }

        if (!$this->is_valid_email($this->post('email'))) {
            $this->response(['status' => false, 'message' => '¡El email es incorrecto!', 'data' => []] , 200);
            return false;
        }

        if (!$this->match('/^[0-9]*$/', trim($this->post('doc_identidad_numero')))) {
            $this->response(['status' => false, 'message' => '¡Número documento de identidad es incorrecto!', 'data' => []] , 200);
            return false;
        }

        if (strlen(trim($this->post('nombres'))) > 25) {
            $this->response(['status' => false, 'message' => '¡Nombre debe contener hasta 25 caracteres de longitud!', 'data' => []] , 200);
            return false;
        }

        if (strlen(trim($this->post('apellido_paterno'))) < 3 ) {
            $this->response(['status' => false, 'message' => '¡Apellido paterno debe contener mínimo 3 caracteres de longitud!', 'data' => []] , 200);
            return false;
        }

        if (strlen(trim($this->post('apellido_materno'))) < 3 ) {
            $this->response(['status' => false, 'message' => '¡Apellido materno debe contener mínimo 3 caracteres de longitud!', 'data' => []] , 200);
            return false;
        }

        if (strlen(trim($this->post('apellido_paterno'))) > 20) {
            $this->response(['status' => false, 'message' => '¡Apellido paterno debe contener hasta 20 caracteres de longitud!', 'data' => []] , 200);
            return false;
        }

        if (strlen(trim($this->post('apellido_materno'))) > 20) {
            $this->response(['status' => false, 'message' => '¡Apellido materno debe contener hasta 20 caracteres de longitud!', 'data' => []] , 200);
            return false;
        }

        if (!array_key_exists($this->post('doc_identidad_tipo'), $this->doc_iden_types)) {
            $this->response(['status' => false, 'message' => '¡Tipo documento de identidad es incorrecto!', 'data' => []] , 200);
            return false;
        }

        if (date('Y-m-d', strtotime($this->post('fecha_nacimiento'))) != $this->post('fecha_nacimiento')) {
            $this->response(['status' => false, 'message' => '¡Fecha nacimiento es incorrecta, formato debe ser YYYY-MM-DD!', 'data' => []] , 200);
            return false;
        }

        if (!array_key_exists($this->post('sexo'), $this->genders)) {
            $this->response(['status' => false, 'message' => '¡Sexo es incorrecto!', 'data' => []] , 200);
            return false;
        }

        if (!array_key_exists($this->post('estado_civil'), $this->civil_status)) {
            $this->response(['status' => false, 'message' => '¡Estado civil es incorrecto!', 'data' => []] , 200);
            return false;
        }

        if (trim($this->post('discapacidad')) != '' && !array_key_exists($this->post('discapacidad'), $this->disabilities)) {
            $this->response(['status' => false, 'message' => '¡Discapacidad es incorecta!', 'data' => []] , 200);
            return false;
        }

        return true;
    }

    private function match($exp, $str)
	{
		$match = [];
		preg_match($exp, $str, $match, PREG_OFFSET_CAPTURE);

		return $match;
	}

    private function is_valid_email($str)
    {
        return (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
    }
}
