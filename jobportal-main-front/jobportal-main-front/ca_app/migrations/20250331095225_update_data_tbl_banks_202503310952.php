<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_banks_202503310952 extends CI_Migration
{
    public function up()
    {
        $banks = [
            [
                'bank_name'      => 'BBVA Continental',
                'account_digits' => 20,
                'cci_digits'     => 20
            ],
            [
                'bank_name'      => 'Scotiabank Perú',
                'account_digits' => 10,
                'cci_digits'     => 20
            ],
            [
                'bank_name'      => 'Interbank',
                'account_digits' => 13,
                'cci_digits'     => 20
            ],
            [
                'bank_name'      => 'Banco de Crédito del Perú',
                'account_digits' => 14,
                'cci_digits'     => 20
            ]
        ];
    
        foreach ($banks as $row) {
    
            // Se busca si ya existe un registro con el nombre del banco
            $bank_row = $this->db->get_where('tbl_banks', [
                'bank_name' => $row['bank_name']
            ])->row();
    
            if ($bank_row) {
                $this->db->where('bank_name', $row['bank_name']);
                $this->db->update('tbl_banks', [
                    'account_digits' => $row['account_digits'],
                    'cci_digits'     => $row['cci_digits']
                ]);
            }
        }
    }

    public function down() {}
}
