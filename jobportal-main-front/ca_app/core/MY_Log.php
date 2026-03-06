<?php
if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class MY_Log extends CI_Log
{
    protected $_log_db;

    public function save_database( $status = false )
    {
        $this->_log_db = $status;
    }

    public function write_log( $level, $msg )
    {
        parent::write_log($level, $msg);

        $level = strtoupper($level);

        if ( ( !isset( $this->_levels[ $level ] ) || ( $this->_levels[ $level ] > $this->_threshold ) ) && !isset( $this->_threshold_array[ $this->_levels[ $level ] ] ) ) {
            return false;
        }

        if ( $this->_log_db !== true ) {
            return;
        }

        $ci =& get_instance();
        $ci->load->database();

        $data = [
            'type' => 'error',
            'message' => trim($msg),
            'created_at' => date( 'Y-m-d H:i:s' ),
            'server_data' => isset($_SERVER) && count($_SERVER) > 0 ? json_encode($_SERVER) : null,
            'session_data' => isset($_SESSION) && count($_SESSION) > 0 ? json_encode($_SESSION) : null,
            'get_data' => isset($_GET) && count($_GET) > 0 ? json_encode($_GET) : null,
            'post_data' => isset($_POST) && count($_POST) > 0 ? json_encode($_POST) : null,
            //'ip_address' => $_SERVER['REMOTE_ADDR'],
        ];

        $ci->db->insert( 'tbl_logs', $data );
    }
}
