<?php 

function enabled_logs_db()
{
    $ci =& get_instance( );
    $ci->log->save_database(true);
}
    