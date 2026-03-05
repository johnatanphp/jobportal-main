<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_trigger_before_update_tbl_job_seekers_ubigeos_updater extends CI_Migration
{
    public function up()
    {
        $this->db->query("DROP TRIGGER IF EXISTS `bu_job_seekers_ubigeos_updater`");
        $this->db->query("CREATE TRIGGER `bu_job_seekers_ubigeos_updater` BEFORE UPDATE ON `tbl_job_seekers` FOR EACH ROW BEGIN
	
        DECLARE department_id, province_id, district_id VARCHAR(20);
        DECLARE city VARCHAR(70);

        IF (NEW.city <> OLD.city OR NEW.department_id <> OLD.department_id OR NEW.province_id <> OLD.province_id OR NEW.district_id <> OLD.district_id) THEN
        
	        IF (NEW.city <> OLD.city) THEN
	            
	            (SELECT order_administrative1_code, order_administrative2_code, order_administrative3_code INTO department_id, province_id, district_id FROM tbl_ubigeos WHERE CONCAT(order_administrative1, ', ', order_administrative2, ', ', order_administrative3) = trim(NEW.city) AND country_id = NEW.country LIMIT 1);
	            
                SET NEW.department_id = IFNULL(department_id, '');
                SET NEW.province_id = IFNULL(province_id, '');
                SET NEW.district_id = IFNULL(district_id, '');
	            
	        END IF;
	
	        IF (NEW.city = OLD.city) THEN
	            
	            (SELECT CONCAT(order_administrative1, ', ', order_administrative2, ', ', order_administrative3) AS city INTO city FROM tbl_ubigeos where country_id = NEW.country AND order_administrative1_code = NEW.department_id AND order_administrative2_code = NEW.province_id AND order_administrative3_code = NEW.district_id LIMIT 1);
	                
	            SET NEW.city = IFNULL(city, '');
	            
	        END IF;
        
        END IF;
   
END");
    }

    public function down(){
		$this->db->query("DROP TRIGGER IF EXISTS `bu_job_seekers_ubigeos_updater`");
	}
}
