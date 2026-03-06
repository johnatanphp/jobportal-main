<?php 
    $profile_id = $this->session->userdata('current_profile_id');
    if ($profile_id) {
        $this->load->view('employer/common/menu/menu_profile_' . $profile_id);
    }
?>