<?php

class Medical_center extends CI_Model
{
    public function find($id)
    {
        $this->db->from('tbl_medical_centers');
        $this->db->where('code', $id);

        return $this->db->get()->row();
    }

    public function add($data)
    {
        return $this->db->insert('tbl_medical_centers', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('code', $id);
        return $this->db->update('tbl_medical_centers', $data);
    }

    public function get_all()
    {
        $this->db->from('tbl_medical_centers');
        return $this->db->get()->result();
    }

    public function create_from_data($data)
    {
        $data_medical_center = explode('|', $data);

        $medical_center = $this->find($data_medical_center[0]);

        if ($medical_center) {
            $data_update = [
                'name' => $data_medical_center[1],
                'city' => $data_medical_center[2]
            ];

            $this->update($medical_center->code, $data_update);
        } else {

            $data = [
                'code' => $data_medical_center[0],
                'name' => $data_medical_center[1],
                'city' => $data_medical_center[2]
            ];

            $this->add($data);
        }

        return $this->find($data_medical_center[0]);
    }
}
