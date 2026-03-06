<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_tbl_countries_202510310935 extends CI_Migration
{
    public function up()
    {
        $flag_icon_base64 = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDM2IDM2IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiBhcmlhLWhpZGRlbj0idHJ1ZSIgcm9sZT0iaW1nIiBjbGFzcz0iaWNvbmlmeSBpY29uaWZ5LS10d2Vtb2ppIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWlkWU1pZCBtZWV0Ij48cGF0aCBmaWxsPSIjRDkxMDIzIiBkPSJNNCA1YTQgNCAwIDAgMC00IDR2MThhNCA0IDAgMCAwIDQgNGg4VjVINHoiPjwvcGF0aD48cGF0aCBmaWxsPSIjRUVFIiBkPSJNMTIgNWgxMnYyNkgxMnoiPjwvcGF0aD48cGF0aCBmaWxsPSIjRDkxMDIzIiBkPSJNMzIgNWgtOHYyNmg4YTQgNCAwIDAgMCA0LTRWOWE0IDQgMCAwIDAtNC00eiI+PC9wYXRoPjwvc3ZnPg==';
        $this->db->where('ID', '56'); //Peru
        $this->db->update('tbl_countries', [
            'flag_icon' => $flag_icon_base64
        ]);
    }

    public function down(){}
}
