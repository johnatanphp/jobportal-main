<?php
class App_console extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();

        if (!is_cli() && ENVIRONMENT !== 'development') {
            show_error('No se puede acceder a este recurso', 404, 'Error de acceso');
        }   
    }
}
    