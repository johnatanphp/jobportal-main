<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Ubigeos extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function departaments_get()
    {
        $params = $this->get();

        $this->db->select([
            'dep.order_administrative1_code AS dep_id',
            'dep.order_administrative1 AS dep_name',
            'dep.country_id AS dep_country_id'
        ]);
        $this->db->from('tbl_ubigeos dep');
        
        if (isset($params['country_id'])) {
            $this->db->where('dep.country_id', trim($params['country_id']));
        }

        $this->db->group_by('dep.order_administrative1');

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->dep_id,
                'name' => $row->dep_name
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }

    public function provinces_get()
    {
        $params = $this->get();

        $this->db->select([
            'prov.order_administrative2_code AS prov_id',
            'prov.order_administrative2 AS prov_name',
            'prov.country_id AS dep_country_id',
            'prov.order_administrative1_code AS departament_id',
            'prov.description_zone AS description_zone'

        ]);
        $this->db->from('tbl_ubigeos prov');
        
        if (isset($params['country_id'])) {
            $this->db->where('prov.country_id', trim($params['country_id']));
        }

        if (isset($params['departament_id'])) {
            $this->db->where('prov.order_administrative1_code', trim($params['departament_id']));
        }

        if (isset($params['zone_id'])) {
            $this->db->where('prov.code_zone', trim($params['zone_id']));
        }

        $this->db->group_by('prov.order_administrative2');

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->prov_id,
                'name' => $row->prov_name,
                'departament_id' => $row->departament_id,
                'description_zone' => $row->description_zone
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }

    public function districts_get()
    {
        $params = $this->get();

        $this->db->select([
            'dist.order_administrative3_code AS dist_id',
            'dist.order_administrative3 AS dist_name',
            'dist.country_id AS dist_country_id',
            'dist.order_administrative1_code AS departament_id',
            'dist.order_administrative2_code AS province_id',
            'dist.lat AS dist_lat',
            'dist.lng AS dist_lng'
        ]);
        $this->db->from('tbl_ubigeos dist');
        
        if (isset($params['country_id'])) {
            $this->db->where('dist.country_id', trim($params['country_id']));
        }

        if (isset($params['departament_id'])) {
            $this->db->where('dist.order_administrative1_code', trim($params['departament_id']));
        }

        if (isset($params['province_id'])) {
            $this->db->where('dist.order_administrative2_code', trim($params['province_id']));
        }

        $this->db->group_by('dist.order_administrative3');

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->dist_id,
                'name' => $row->dist_name,
                'departament_id' => $row->departament_id,
                'province_id' => $row->province_id,
                'lat' => $row->dist_lat,
                'lng' => $row->dist_lng,
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }

    public function list_get()
    {
        $params = $this->get();

        // Validar que el parámetro de búsqueda 'query' tenga al menos 3 caracteres
        if (!isset($params['query']) || strlen(trim($params['query'])) < 3) {
            $data = [
                'status' => false,
                'message' => 'Debe ingresar al menos 3 caracteres para la búsqueda.',
                'data' => [],
            ];
            $this->response($data, self::HTTP_OK);
            return;
        }

        $this->db->select("
            CASE
                WHEN (order_administrative2 IS NULL OR order_administrative2 = '') 
                    AND (order_administrative3 IS NULL OR order_administrative3 = '') THEN LEFT(order_administrative1_code, 2)
                WHEN (order_administrative3 IS NULL OR order_administrative3 = '') THEN LEFT(order_administrative2_code, 4)
                ELSE LEFT(order_administrative3_code, 6)
            END AS id,

            CASE
                WHEN (order_administrative2 IS NULL OR order_administrative2 = '') 
                    AND (order_administrative3 IS NULL OR order_administrative3 = '') THEN CONCAT(order_administrative1, ' (Departamento)') 
                WHEN (order_administrative3 IS NULL OR order_administrative3 = '') THEN CONCAT(order_administrative1, ', ', order_administrative2, ' (Provincia)')
                ELSE CONCAT(
                    order_administrative1, 
                    IF(order_administrative2 IS NOT NULL AND order_administrative2 != '', CONCAT(', ', order_administrative2), ''), 
                    IF(order_administrative3 IS NOT NULL AND order_administrative3 != '', CONCAT(', ', order_administrative3), ''), 
                    ' (Distrito)'
                )
            END AS name
        ");
        
        $this->db->from('tbl_ubigeos');
        $this->db->where("
            CASE
                WHEN (order_administrative2 IS NULL OR order_administrative2 = '') 
                    AND (order_administrative3 IS NULL OR order_administrative3 = '') 
                    THEN LEFT(order_administrative1_code, 2)
                WHEN (order_administrative3 IS NULL OR order_administrative3 = '') 
                    THEN LEFT(order_administrative2_code, 4)
                ELSE LEFT(order_administrative3_code, 6)
            END IS NOT NULL
            AND 
            CASE
                WHEN (order_administrative2 IS NULL OR order_administrative2 = '') 
                    AND (order_administrative3 IS NULL OR order_administrative3 = '') 
                    THEN LEFT(order_administrative1_code, 2)
                WHEN (order_administrative3 IS NULL OR order_administrative3 = '') 
                    THEN LEFT(order_administrative2_code, 4)
                ELSE LEFT(order_administrative3_code, 6)
            END != ''
        ");

        // Filtro por 'country_id'
        if (isset($params['country_id'])) {
            $this->db->where('country_id', trim($params['country_id']));
        }

        // Filtro dinámico por 'query' utilizando LIKE
        $search = trim($params['query']);
        $this->db->group_start();
        $this->db->like('order_administrative1', $search);
        $this->db->or_like('order_administrative2', $search);
        $this->db->or_like('order_administrative3', $search);
        $this->db->group_end();
        $this->db->order_by('id');

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->id,
                'name' => $row->name
            ];
        }

        $data = [
            'status' => true,
            'message' => 'OK',
            'data' => $response_data,
        ];
        $this->response($data, self::HTTP_OK);
    }
    
    public function zones_get()
    {
        $params = $this->get();

        $this->db->select([
            'zones.id AS zone_id',
            'zones.name AS zone_name',
            'prov.order_administrative2_code AS province_id'
        ]);
        $this->db->from('tbl_ubigeo_zones AS zones');
        $this->db->join('tbl_ubigeos AS prov', 'prov.code_zone=zones.id', 'left');

        if (isset($params['province_id'])) {
            $this->db->where('prov.order_administrative2_code', trim($params['province_id']));
        }
        
        $this->db->order_by('zones.id', 'ASC');

        $this->db->group_by(['zones.id']);
        $zones = $this->db->get()->result();

        $response_data = [];
        
        foreach ($zones as $zone) {
            $this->db->select([
                'prov.order_administrative3_code AS district_id',
                'prov.order_administrative3 AS district_name'
            ]);
            $this->db->from('tbl_ubigeos prov');
            $this->db->where('prov.order_administrative3_code!=', null);
            $this->db->where('prov.order_administrative3_code!=', '');
            $this->db->where('prov.code_zone', $zone->zone_id);
            $districts = $this->db->get()->result();

            // Formatear la información para la respuesta
            $response_data[] = [
                'zone_id' => $zone->zone_id,
                'zone_name' => $zone->zone_name,
                'districts' => array_map(function($district) {
                    return [
                        'district_id' => $district->district_id,
                        'district_name' => $district->district_name
                    ];
                }, $districts)
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
