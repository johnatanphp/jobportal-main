<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_function_recruitment_tray_doc_percentage_progress extends CI_Migration
{
    public function up()
    {
      $this->db->query('DROP FUNCTION IF EXISTS recruitment_tray_doc_percentage_progress');
      $this->db->query('CREATE FUNCTION recruitment_tray_doc_percentage_progress(seeker_id INT, job_id INT)
        RETURNS INT
        READS SQL DATA
        DETERMINISTIC
        BEGIN
            DECLARE total_percentage INT;
            DECLARE seeker_document_type_id INT;
            
            select document_type into @seeker_document_type_id from tbl_job_seekers where ID = seeker_id;
            
            select ROUND((sum(result_percentage.point) * 100) / count(distinct(result_percentage.doc_id))) into total_percentage  FROM 
                -- Buscar si el postulante tiene documento de identidad cargado, si lo tiene se devuelve 1 punto
                ((select count(doc.seeker_id) AS point, "doc_number" AS doc_id from tbl_seeker_identification_documents doc where doc.seeker_ID = seeker_id LIMIT 1) 
                UNION ALL 
                -- Buscar si el postulante tiene foto cargada, si lo tiene se devuelve 1 punto
                (select count(doc.seeker_id) AS point, "doc_2" AS doc_id  from tbl_recruitment_contract_documents doc where doc.seeker_id = seeker_id AND doc.document_id = 2 LIMIT 1)
                
                UNION ALL 
                -- Buscar si el postulante tiene la ficha RTPS registrada, si lo tiene devuelve 0.5 punto
                (select  IF(count(doc.seeker_id) > 0, 0.5, 0) AS point, "rtps" AS doc_id  from tbl_seeker_form_rtps doc where doc.seeker_ID = seeker_id AND doc.job_id = job_id LIMIT 1)
                
                UNION ALL 
                -- Buscar si el postulante tiene la ficha RTPS firmada, si lo tiene devuelve 0.5 punto
                (select IF(count(doc.seeker_id) > 0, 0.5, 0) AS point, "rtps" AS doc_id   from tbl_seeker_form_rtps doc where doc.seeker_ID = seeker_id AND doc.job_id = job_id AND doc.evicertia_status = 3 LIMIT 1)
                
                UNION ALL 
                -- Buscar si el postulante tiene documento Record migratorio vigente (Solo para extranjeros) cargado, si lo tiene se devuelve 1 punto
                (select IF (doc.seeker_id <> null, 1, 0) AS point, "doc_14" AS doc_id  from tbl_recruitment_contract_documents doc where (doc.seeker_id = seeker_id AND doc.document_id = 14 AND @seeker_document_type_id <> 1) OR (@seeker_document_type_id <> 1) LIMIT 1)
                
                UNION ALL 
                -- Buscar si el postulante tiene documento Verificación de residencia (Solo para extranjeros) cargado, si lo tiene se devuelve 1 punto
                (select IF (doc.seeker_id <> null, 1, 0) AS point, "doc_15" AS doc_id  from tbl_recruitment_contract_documents doc where (doc.seeker_id = seeker_id AND doc.document_id = 15 AND @seeker_document_type_id <> 1) OR (@seeker_document_type_id <> 1) LIMIT 1)
            ) AS result_percentage;
            
        RETURN total_percentage;
        END');
    }

    public function down()
    {
        $this->db->query('DROP FUNCTION IF EXISTS recruitment_tray_doc_percentage_progress');
    }
}
