<?php
class Ubigeo extends CI_Model 
{
    public function get_all_records()
    {
        $this->db->from('tbl_ubigeos ubigeos');
        $this->db->order_by('ubigeos.order_administrative1', 'ASC');
        $this->db->order_by('ubigeos.order_administrative2', 'ASC');
        $this->db->order_by('ubigeos.order_administrative3', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_all_departments($country_id = 55)
    {
        if ($country_id == 55 || $country_id == 56) {
            $this->db->distinct();
            $this->db->select('department.order_administrative1 AS department');

            $this->db->from('tbl_ubigeos department');
            $this->db->order_by('department.order_administrative1', 'ASC');

            return $this->db->get()->result();
        }

        if ($country_id == 13) {

            $departaments = [
                'Amazonas',
               'Antioquia',
                'Arauca',
                'Atlántico',
                'Bolívar',
                'Boyacá',
                'Caldas',
                'Caquetá',
                'Casanare',
                'Cauca',
                'Cesar',
                'Chocó',
                'Córdoba',
                'Cundinamarca',
                'Guainía',
                'Guaviare',
                'Huila',
                'La Guajira',
                'Magdalena',
                'Meta',
                'Nariño',
                'Norte de Santander',
                'Putumayo',
                'Quindío',
                'Risaralda',
                'San Andrés y Providencia',
                'Santander',
                'Sucre',
                'Tolima',
                'Valle del Cauca',
                'Vaupés',
                'Vichada'
            ];

            $list_departaments = [];

            foreach ($departaments as $row) {
                $data_row = [
                    'department' => $row
                ];
                $list_departaments[] = (object)$data_row;
            }

            return $list_departaments;
        }
    }

    public function get_all_provinces()
    {
        $this->db->distinct();
        $this->db->select('province.order_administrative2 AS province');

        $this->db->from('tbl_ubigeos province');
        $this->db->order_by('province.order_administrative2', 'ASC');

        return $this->db->get()->result();
    }

    public function get_all_districts()
    {
        $this->db->distinct();
        $this->db->select('district.order_administrative3 AS district');

        $this->db->from('tbl_ubigeos district');
        $this->db->order_by('district.order_administrative3', 'ASC');

        return $this->db->get()->result();
    }

    public function search_suggestions($country, $search, $limit = 20)
    {   
        $pieces = explode(',', $search);
        
        $order1 = isset($pieces[0]) ? trim($pieces[0]) : "";
        $order2 = isset($pieces[1]) ? trim($pieces[1]) : "";
        $order3 = isset($pieces[2]) ? trim($pieces[2]) : "";

        $this->db->select('ubigeo.*');
        $this->db->from('tbl_ubigeos ubigeo');
      
        $this->db->join('tbl_countries country', 'country.iso_alfa2_code=ubigeo.code_country');
        $this->db->where('country.country_name', $country);

        if ($order1 != "" && $order2 != "" && $order3 != "") {

            $this->db->where("
                (ubigeo.order_administrative1 LIKE '%{$order1}%' AND 
                ubigeo.order_administrative2 LIKE '%{$order2}%' AND 
                ubigeo.order_administrative3 LIKE '%{$order3}%')"
            );
        } elseif ($order1 != "" && $order2 != "") {

            $this->db->where("
                (ubigeo.order_administrative1 LIKE '%{$order1}%' AND 
                ubigeo.order_administrative2 LIKE '%{$order2}%')"
            );
        } else {

            $this->db->where("
                (ubigeo.order_administrative1 LIKE '%{$search}%' OR 
                ubigeo.order_administrative2 LIKE '%{$search}%' OR 
                ubigeo.order_administrative3 LIKE '%{$search}%')"
            );
        }

        $this->db->limit($limit);
        
        $this->db->order_by("ubigeo.order_administrative1", "ASC");
        $this->db->order_by("ubigeo.order_administrative2", "ASC");
        $this->db->order_by("ubigeo.order_administrative3", "ASC");
        
        $result = $this->db->get();
        $suggestions = array();
       
        foreach($result->result() as $row) {
            $data = array(
                'value' => $row->order_administrative1 . ", " . $row->order_administrative2 . ", " . $row->order_administrative3,
            );

            $suggestions[] = $data;
        }

        $result->free_result();
        
        return $suggestions;
    }

    public function get_provinces_by($department = '')
    {
        $this->db->distinct();
        $this->db->select('province.order_administrative2 AS province');

        $this->db->from('tbl_ubigeos province');
        $this->db->order_by('province.order_administrative2', 'ASC');

        $this->db->where('province.order_administrative1', trim((string)$department));

        return $this->db->get()->result();
    }

    public function get_districts_by($province = '')
    {
        $this->db->distinct();
        $this->db->select('district.order_administrative3 AS district');

        $this->db->from('tbl_ubigeos district');
        $this->db->order_by('district.order_administrative3', 'ASC');

        $this->db->where('district.order_administrative2', trim((string)$province));

        return $this->db->get()->result();
    }

    public function get_all_by_country_id($country_id)
    {
        if ($country_id == 55 || $country_id == 56) {

            $this->db->select([
                'CONCAT(order_administrative1, ", ",order_administrative2,", ",order_administrative3) as ubigeo'
            ]);
            $this->db->from('tbl_ubigeos ubigeos');
            $this->db->order_by('ubigeos.order_administrative1', 'ASC');
            $this->db->order_by('ubigeos.order_administrative2', 'ASC');
            $this->db->order_by('ubigeos.order_administrative3', 'ASC');
    
            return $this->db->get()->result();
        }

        if ($country_id == 13) {

            $departaments = [
                'Amazonas',
               'Antioquia',
                'Arauca',
                'Atlántico',
                'Bolívar',
                'Boyacá',
                'Caldas',
                'Caquetá',
                'Casanare',
                'Cauca',
                'Cesar',
                'Chocó',
                'Córdoba',
                'Cundinamarca',
                'Guainía',
                'Guaviare',
                'Huila',
                'La Guajira',
                'Magdalena',
                'Meta',
                'Nariño',
                'Norte de Santander',
                'Putumayo',
                'Quindío',
                'Risaralda',
                'San Andrés y Providencia',
                'Santander',
                'Sucre',
                'Tolima',
                'Valle del Cauca',
                'Vaupés',
                'Vichada'
            ];

            $list_departaments = [];

            foreach ($departaments as $row) {
                $data_row = [
                    'ubigeo' => $row
                ];
                $list_departaments[] = (object)$data_row;
            }

            return $list_departaments;
        }

        return [];
    }
}
