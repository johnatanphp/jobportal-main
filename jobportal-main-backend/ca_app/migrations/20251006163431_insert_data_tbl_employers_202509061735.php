<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_employers_202509061735 extends CI_Migration
{
    public function up()
    {
        $this->load->model('Employer');

        $data[] = ['AGUIRRE DIAZ MELISSA SOLANGE',	'993721935',	'maguirre@overall.com.pe'];
        $data[] = ['ASTORAYME VARGAS MARIA LAURA',	'932054901',	'maria.astorayme@overall.com.pe'];
        $data[] = ['BAÑON JORGE SUSAN',	'970506246',	'susan.banon@overall.com.pe'];
        $data[] = ['BARTOLO TORRES PATRICIA', 	'980329221',	'patricia.bartolo@overall.com.pe'];
        $data[] = ['BLAS ESPINOZA KRISTEL SHANTALL',	'914238725',	'kristel.blas@overall.com.pe'];
        $data[] = ['CALDERON INGA ELIEL ALFONSO',	'948773168',	'ecalderon@overall.com.pe'];
        $data[] = ['CHÚ AGUERO MELISSA PAOLA',	'948735187',	'mchu@overall.com.pe'];
        $data[] = ['CHUNGA DANCUART FRANCESCA ANTONELLA',	'974669290',	'francesca.chunga@overall.com.pe'];
        $data[] = ['ESCOBAR ZAPATA JEFFERSON ANTONI',	'945266252',	'jescobar@overall.com.pe'];
        $data[] = ['GAMONAL VILLANUEVA NAYELI ANAHI',	'954161609',	'nayeli.gamonal@overall.com.pe'];
        $data[] = ['GOYBURO CHAVEZ SALVADOR JOSE',	'944711562',	'salvador.goyburo@overall.com.pe'];
        $data[] = ['GUTIERREZ VIDAL AILEEN THIRZA',	'953707136',	'aileen.gutierrez@overall.com.pe'];
        $data[] = ['IPANAQUE GARAY ANA ROSA BRIGITTE',	'987642521',	'ana.ipanaque@overall.com.pe'];
        $data[] = ['LARREA MACASSI SEBASTIAN PAOLO',	'950297967',	'sebastian.larrea@overall.com.pe'];
        $data[] = ['LEON DAVALOS ROCIO DEL PILAR',	'996293149',	'rocio.leon@overall.com.pe'];
        $data[] = ['LUCIANO RAMIREZ JEFRI LUIS',	'997742183',	'jefri.luciano@overall.com.pe'];
        $data[] = ['MARTINEZ AVELLANEDA DENIS CRISTHIAN',	'973005109',	'denis.martinez@overall.com.pe'];
        $data[] = ['MONTERO HUAYLINOS JHON ALBERTH',	'949376805',	'jhon.montero@overall.com.pe'];
        $data[] = ['NEYRA MORALES VALERITH',	'914238725',	'valerith.neyra@overall.com.pe'];
        $data[] = ['PACHECO BUSTOS HUBER ADOLFO',	'956276311',	'huber.pacheco@overall.com.pe'];
        $data[] = ['PARIONA SAMAME CLAUDIA MILAGROS',	'980900816',	'cpariona@overall.com.pe'];
        $data[] = ['PAZ MILIAN DENISSE DEL PILAR',	'969326233',	'denisse.paz@overall.com.pe'];
        $data[] = ['QUINTANA CEDRÓN MARIA FERNANDA',	'970503463',	'mquintana@overall.com.pe'];
        $data[] = ['QUIROZ MORALES KAREN STEFANIE',	'941113775',	'karen.quiroz@overall.com.pe'];
        $data[] = ['RAMOS BAUTISTA CARMEN ARACELI',	'932162388',	'carmen.ramos@overall.com.pe'];
        $data[] = ['ROJAS MORALES MARIELA ARIANA',	'960275313',	'mariela.rojas@overall.com.pe'];
        $data[] = ['ROJAS TRELLES ANALY DE LOS MILAGROS',	'994606232',	'analy.rojas@overall.com.pe'];
        $data[] = ['SAICO PASCUAL ANAIS LUCILA',	'980899389',	'anais.saico@overall.com.pe'];
        $data[] = ['SANCHEZ QUISPE GIANCARLO LINO',	'986835343',	'giancarlo.sanchez@overall.com.pe'];
        $data[] = ['SARAVIA BARRERA LESLIE JANETH',	'968210959',	'leslie.saravia@overall.com.pe'];
        $data[] = ['SUAREZ VIDAURRE JHOANA',	'989370500',	'jhoana.suarez@overall.com.pe'];
        $data[] = ['URBAY MONAGO WENDY CLERK',	'943395431',	'wurbay@overall.com.pe'];
        $data[] = ['VEGA FLORES CARMEN ROSA',	'948710952',	'carmen.vega@overall.com.pe'];
        $data[] = ['YAGUI YABIKU SUSUMU ALEJANDRO',	'914404093',	'alejandro.yagui@overall.com.pe'];

        foreach ($data as $row_employer) {

            $first_name = trim($row_employer[0]);
            $mobile =  '+51' . trim($row_employer[1]);
            $email = trim($row_employer[2]);

            $this->db->from('tbl_employers');
            $this->db->where('email', $email);
            $employer_info = $this->db->get()->row();

            if ($employer_info) {
                $user_id = $employer_info->ID;

                $this->db->replace('tbl_employer_profiles', [
                    'user_id' => $user_id,
                    'profile_id' => 1
                ]);

                $this->db->replace('tbl_employer_profiles', [
                    'user_id' => $user_id,
                    'profile_id' => 2
                ]);

                continue;
            }

            $password = '12345';
            $current_date_time = date('Y-m-d H:i:s');
            $company_id = 1;

            $employer_data = [
                'first_name' => $first_name,
                'email' => $email,
                'pass_code' => do_hashing($password),
                'mobile_phone' => $mobile,
                //'home_phone' => $this->input->post('home_phone'),
                'country' => 'Perú',
                'city' => 'Lima',
                //'position' => $this->input->post('position'),
                'ip_address' => '',
                'dated' => $current_date_time,
                'company_ID' => $company_id,
                'sts' => 'active',
                'verification_code' => 'register_batch',
                'top_employer' => 'no',
                'is_admin' => 'no',
                'created_at' => $current_date_time
            ];

            $user_id = $this->Employer->add_user($employer_data);

            if ($user_id) {

                $data_batch = [
                    [
                        'user_id' => $user_id,
                        'profile_id' => 1
                    ],
                    [
                        'user_id' => $user_id,
                        'profile_id' => 2
                    ]
                ];
                $this->db->insert_batch('tbl_employer_profiles', $data_batch);
            }
        }
    }

    public function down(){}
}
