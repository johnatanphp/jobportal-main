<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register_plm_employee extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
    }

    public function index()
    {
        $status = true;

        if ($status) {
            echo json_encode([
                "CODE" => "0", 
                "MESSAGE" => "OK", 
                "OBSERVACION" => null, 
                "PLM_MTRABAJADOR" => [
                    [
                        "NO_CIA" => "01", 
                        "COD_TRAB" => "0011111", 
                        "MSG" => "SE INSERTÓ EL TRABAJADOR" 
                    ] 
                ] 
            ]);
        } else {
            echo json_encode([
                "CODE" => "9999", 
                "MESSAGE" => "ORA-01403: No se ha encontrado ningún dato\nORA-06512: en \"EPLANI.PRC_INS_PLM_MTRABAJADOR_DEP\", línea 173\nORA-06512: en línea 1\n", 
                "OBSERVACION" => null, 
                "PLM_MTRABAJADOR" => null 
            ]);
        }
    }
}
