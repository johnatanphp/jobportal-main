<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_factor_valuations_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'TEXT',
            ],
            'level' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'factor_theme_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'factor_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
            'grade' => [
                'type' => 'INT'
            ],
            'score' => [
                'type' => 'INT'
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (factor_theme_id) REFERENCES tbl_factor_themes(id)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (factor_type_id) REFERENCES tbl_factor_types(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_factor_valuations');

        //Insert data
        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_factor_valuations');
    }

    public function insert_data()
    {
        $data[] = ['NO NECESITA EDUCACIÓN O SECUNDARIA COMPLETA', 	1	,30, 1, 1, null];
        $data[] = ['ESTUDIANTE TÉCNICO O UNIVERSITARIO',	2	, 60, 1, 1, null];
        $data[] = ['ESTUDIO TÉCNICO O UNIVERSITARIO CONCLUIDO',	3	, 90, 1, 1, null];
        $data[] = ['CON ESTUDIOS COMPLEMENTARIOS (CURSOS/DIPLOMADOS/ ESPECIALIZACIONES)',	4	, 120, 1, 1, null];
        $data[] = ['MAESTRÍAS/MBA',	5	, 150, 1, 1, null];

        $data[] = ['0 hasta 6 MESES', 1, 30, 1, 2, null];
        $data[] = ['Mayor de 6 A 12 MESES', 2, 60, 1, 2, null];
        $data[] = ['Mayor de 1 hasta 2 AÑOS', 3, 90, 1, 2, null];
        $data[] = ['Mayor de  2 hasta 4 AÑOS', 4, 120, 1, 2, null];
        $data[] = ['Mayor de  5 AÑOS', 5, 150, 1, 2, null];
        
        $data[] = ['CALIDAD EN EL TRABAJO,ALTA ENERGÍA,TRABAJO BAJO PRESIÓN,RESPONSABILIDAD,TRABAJO EN EQUIPO', 1, 20, 1, 3, null];
        $data[] = ['COMUNICACIÓN EFECTIVA,TRABAJO EN EQUIPO,AGILIDAD MENTAL,HABILIDAD DE REDACCIÓN,INTEGRIDAD,ESCUCHA ACTIVA,TRABAJO BAJO PRESIÓN', 2, 40, 1, 3, null];
        $data[] = ['AGRESIVIDAD COMERCIAL,ORIENTACIÓN AL CLIENTE,IMPACTO E INFLUENCIA,INTEGRIDAD,TRABAJO BAJO PRESIÓN,AGILIDAD MENTAL,COMUNICACIÓN EFECTIVA', 2, 40, 1, 3, null];
        $data[] = ['PENSAMIENTO ANALÍTICO,APTITUD MENTAL NUMÉRICA,APTITUD MENTAL VERBAL,CALIDAD DE TRABAJO,TRABAJO BAJO PRESIÓN,TRABAJO EN EQUIPO,FLEXIBILIDAD Y ADAPTABILIDAD', 3, 60, 1, 3, null];
        $data[] = ['HABILIDAD DE CONTACTO,PENSAMIENTO ANALÍTICO,RESOLUCIÓN DE PROBLEMAS,URGENCIA,ORIENTACIÓN AL CLIENTE,TRABAJO EN EQUIPO,ASERTIVIDAD', 3, 60, 1, 3, null];
        $data[] = ['POTENCIAL DE LIDERAZGO,PENSAMIENTO ESTRATÉGICO,TRABAJO EN EQUIPO,CAPACIDAD DE PLANIFICACIÓN,TRABAJO BAJO PRESIÓN,COMUNICACIÓN EFECTIVA,AGILIDAD MENTAL', 4, 80, 1, 3, null];
        $data[] = ['POTENCIAL DE LIDERAZGO,PENSAMIENTO ESTRATÉGICO,NEGOCIACIÓN EFECTIVA,DESARROLLO DE EQUIPO,RESOLUCIÓN DE PROBLEMAS,COMUNICACIÓN EFECTIVA,PENSAMIENTO ANALÍTICO', 5, 100, 1, 3, null];
        
        $data[] = ['ACTIVIDAD MENTAL QUE REQUIERE POCA CONCENTRACIÓN Y ESFUERZO COGNITIVO', 1, 30, 2, 4, 'BAJO'];
        $data[] = ['ACTIVIDAD MENTAL QUE REQUIERE CONCENTRACIÓN Y ESFUERZO COGNITIVO PROMEDIO', 2, 60, 2, 4, 'MEDIO'];
        $data[] = ['ACTIVIDAD MENTAL QUE REQUIERE DEMANDANTE CONCENTRACIÓN Y ESFUERZO COGNITIVO', 3, 90, 2, 4, 'MEDIO ALTO'];
        $data[] = ['ACTIVIDAD MENTAL QUE REQUIERE ALTA CONCENTRACIÓN Y ESFUERZO COGNITIVO', 4, 120, 2, 4, 'ALTO'];
        $data[] = ['ACTIVIDAD MENTAL QUE REQUIERE ALTOS NIVELES DE CONCENTRACIÓN Y ESFUERZO COGNITIVO', 5, 150, 2, 4, 'MUY ALTO'];
        
        $data[] = ['ACTIVIDAD FÍSICA QUE REQUIERE POCO MOVIMIENTO Y ESFUERZO', 1, 20, 2, 5, 'BAJO'];
        $data[] = ['ACTIVIDAD FÍSICA QUE REQUIERE  MOVIMIENTO Y ESFUERZO PROMEDIO', 2, 40, 2, 5, 'MEDIO'];
        $data[] = ['ACTIVIDAD FÍSICA QUE REQUIERE DEMANDANTE MOVIMIENTO Y ESFUERZO', 3, 60, 2, 5, 'MEDIO ALTO'];
        $data[] = ['ACTIVIDAD FÍSICA QUE REQUIERE DE ALTO ESFUERZO Y MOVIMIENTO', 4, 80, 2, 5, 'ALTO'];
        $data[] = ['ACTIVIDAD FÍSICA QUE REQUIERE DE ALTOS NIVELES DE ESFUERZO Y MOVIMIENTO', 5, 100, 2, 5, 'MUY ALTO'];

        $data[] = ['BAJA REPERCUSIÓN EN LOS RESULTADOS. POSIBILIDAD DE COMETER ERRORES QUE, DE NO SER DETECTADOS, PUEDEN AFECTAR A OTRAS ÁREAS, RESULTANDO UNA PERDIDA DE TIEMPO, MATERIALES O DEMORAS EN EL SERVICIO, HASTA UN GRADO LIMITADO. PUEDE SER DETECTADO ANTES DE QUE PRODUZCAN UN PERJUICIO IMPORTANTE', 1, 50, 3, 6, 'BAJA'];
        $data[] = ['MODERADA REPERCUSIÓN EN LOS RESULTADOS. TENER LA POSIBILIDAD DE COMETER ERRORES COMO LOS DESCRITOS EN EL GRADO ANTERIOR, PERO CON MAYOR TRASCENDENCIA, SIN SER LA MÁXIMA QUE SE PUEDA DAR EN LA EMPRESA.', 2, 100, 3, 6, 'MODERADA'];
        $data[] = ['MEDIA - ALTA REPERCUSIÓN EN LOS RESULTADOS. PUESTOS QUE LLEVAN CONSIGO LA POSIBILIDAD CONTINUADA DE COMETER ERRORES CON CONSECUENCIAS CONSIDERABLES, QUE NO PUEDEN RECUPERARSE FÁCILMENTE, AL EXISTIR CIERTA DIFICULTAD AL DETECTAR ERRORES A TIEMPO PARA REDUCIR SU EFECTO.', 3, 150, 3, 6, 'MEDIA ALTA'];
        $data[] = ['ALTA REPERCUSIÓN EN LOS RESULTADO. PUESTOS DE REPERCUSIÓN EN MAS DE UN ÁREA FUNCIONAL. TENER POSIBILIDAD DE COMETER ERRORES CON GRAVES CONSECUENCIAS. LOS ERRORES SOLO PUEDEN DETECTARSE, GENERALMENTE, UNA VEZ QUE LA ACTIVIDAD U OPERACIÓN SEA LLEVADA A CABO.', 4, 200, 3, 6, 'ALTA'];
        $data[] = ['MÁXIMA REPERCUSIÓN EN LOS RESULTADOS. ACTIVIDADES QUE TIENEN EFECTO EN MAS DE UN ÁREA FUNCIONAL O EN EL CONJUNTO DE LA EMPRESA, Y CUYA REPERCUSIÓN ECONÓMICA O LEGAL ES ELEVADA Y DE DIFÍCIL O IMPOSIBLE REPARACIÓN.', 5, 250, 3, 6, 'MÁXIMA'];

        $data[] = ['OFICINA / RUIDO BAJO / RIESGO BAJO', 1, 25, 4, 7, 'OFICINA / RUIDO BAJO / RIESGO BAJO'];
        $data[] = ['OFICINA / RUIDO BAJO / RIESGO MODERADO', 2, 50, 4, 7, 'OFICINA / RUIDO BAJO / RIESGO MODERADO'];
        $data[] = ['ZONA DE ALTA TRANSICIÓN / OFICINA / RUIDO MODERADO / RIESGO MODERADO', 3, 75, 4, 7, 'ZONA DE ALTA TRANSICIÓN / OFICINA / RUIDO MODERADO / RIESGO MODERADO'];
        $data[] = ['INSTALACIONES DE PLANTA / ALTO RUIDO / ZONA DE ALTA TRANSICIÓN / ALTO RIESGO', 4, 100, 4, 7, 'INSTALACIONES DE PLANTA / ALTO RUIDO / ZONA DE ALTA TRANSICIÓN / ALTO RIESGO'];

        foreach ($data as $key => $array) {

            $name = mb_strtoupper(trim($array[0]));

            $row = $this->db->get_where('tbl_factor_valuations', [
                'name' => $name
            ])->row();

            if ($row) {
                continue;
            }

            $insert = [
                'name' => $name,
                'grade' => $array[1],
                'score' => $array[2],
                'factor_theme_id' => $array[3],
                'factor_type_id' => $array[4],
                'level' => $array[5],
                'active' => 1
            ];
            $this->db->insert('tbl_factor_valuations', $insert);
        }
    }
}
