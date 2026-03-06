<?php
class Jobseeker_search_info_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }
    
    public function search($document_type, $document_number)
    {
        $document_type_list = [
            1 => 'dni',
            4 => 'ce'
        ];

        $document_type = isset($document_type_list[$document_type]) ? $document_type_list[$document_type] : null;

        if (!$document_type) {
            return [];
        }

        $document_number = trim($document_number);
        
        if (empty($document_number)) {
            return [];
        }   

        $token = $this->config->item('dniruc_api_token');
        $url = $this->config->item('dniruc_api_url') . "/search/$document_type/$document_number/$token";
    
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            return [];
        }

        $response = @json_decode($response);

        if (!$response->success) {
            return [];
        }
        
        //dd($response);

        return $this->build_response(
            $document_type, 
            $document_number, 
            $response->data
        );
    }

    private function build_response($document_type, $document_number, $data)
    {
       $data = (object)$data;

        $gender_list = [
            'masculino' => 1,
            'femenino' => 2
        ];

        $civil_status_list = [
            'soltero' => 1,
            'casado' => 2
        ];
        
        $document_type_id =  $document_type == 'dni' ? 1 : 4;
        $ubigeo_text = $data->departamento . ',' .  $data->provincia . ',' . $data->distrito;

        $ubigeo_list = explode(',', $ubigeo_text);

        $ubigeo_data = [
            isset($ubigeo_list[0]) ? ucfirst(mb_strtolower(trim($ubigeo_list[0]))) : '',
            isset($ubigeo_list[1]) ? ucfirst(mb_strtolower(trim($ubigeo_list[1]))) : '',
            isset($ubigeo_list[2]) ? ucfirst(mb_strtolower(trim($ubigeo_list[2]))) : '',   
        ];
        
        $ubigeo_text = join(', ', $ubigeo_data);

        $gender_id = isset($data->sexo) && isset($gender_list[mb_strtolower($data->sexo)]) ? $gender_list[mb_strtolower($data->sexo)] : null;
        $civil_status_id = isset($data->estadoCivil) && isset($civil_status_list[mb_strtolower($data->estadoCivil)]) ? $civil_status_list[mb_strtolower($data->estadoCivil)] : null;

        $gender = $this->db->get_where('tbl_genders', ['id' => $gender_id])->row();
        $civil_status = $this->db->get_where('tbl_civil_status', ['id' => $civil_status_id])->row();
        $document_type = $this->db->get_where('tbl_identity_document_types', ['id' => $document_type_id])->row();

        $ubigeo = $this->db->get_where('tbl_ubigeos', [
            'country_id' => 56,
            'order_administrative1' => isset($ubigeo_list[0]) ? ucfirst(mb_strtolower(trim($ubigeo_list[0]))) : '',
            'order_administrative2' => isset($ubigeo_list[1]) ? ucfirst(mb_strtolower(trim($ubigeo_list[1]))) : '',
            'order_administrative3' => isset($ubigeo_list[2]) ? ucfirst(mb_strtolower(trim($ubigeo_list[2]))) : '',
        ])->row();
    
        $seeker_data = [
            'document_type' => $document_type_id,
            'document_type_name' => $document_type ? $document_type->name : null,
            'document_number' => $document_number ? $document_number : null,
            'first_name' => isset($data->preNombres) ? $data->preNombres : null,
            'paternal_last_name' => isset($data->apePaterno) ? $data->apePaterno : null,
            'maternal_last_name' => isset($data->apeMaterno) ? $data->apeMaterno : null,
            'gender' => $gender_id,
            'gender_name' => $gender ? $gender->name : null,
            'dob' => isset($data->feNacimiento) ? date('Y-m-d', strtotime(str_replace('/', '-', $data->feNacimiento))) : null,
            'civil_status' => $civil_status_id,
            'civil_status_name' => $civil_status ? $civil_status->name : null,
            'present_address' => isset($data->desDireccion) ? trim($data->desDireccion) : null,
            'ubigeo_code' => null,
            'ubigeo_text' => $ubigeo_text,
            'department_id' => $ubigeo ? $ubigeo->order_administrative1_code : null,
            'department_name' => $ubigeo ? $ubigeo->order_administrative1 : null,
            'province_id' => $ubigeo ? $ubigeo->order_administrative2_code : null,
            'province_name' => $ubigeo ? $ubigeo->order_administrative2 : null,
            'district_id' => $ubigeo ? $ubigeo->order_administrative3_code : null,
            'district_name' => $ubigeo ? $ubigeo->order_administrative3 : null,
            'born_ubigeo_code' => null,
            'born_ubigeo_name' => null,
            'photo' => isset($data->foto) ? $data->foto : null,
            'source_data' => $data
        ];

        return json_decode(json_encode($seeker_data));
    }
}
