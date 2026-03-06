<?php

class Mpdf_lib extends \Mpdf\Mpdf
{
    public function __construct()
    {
        parent::__construct([
                'c', 
                'A4',
                'default_font_size' => 12,
                'default_font' => 'rubik'
            ]
        );
    }
}
