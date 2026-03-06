<?php 
class Entry_form_data_show_mx_service
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        $this->load->model('Entry_form');
        $this->load->model('Entry_form_mx');
        $this->load->model('Recruitment_process');
    }

    public function get_data($process_id, $seeker_id)
    {
        $data['title'] = "Ficha de datos del trabajador - " . SITE_NAME;
        
        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);

        $entry_form = $this->Entry_form->find([
            'process_id' => $process_id,
            'seeker_id' => $seeker_id
        ]);

        $entry_form_mx = $this->Entry_form_mx->get_by_entry_form_id($entry_form->id);
        $rightful_claimants = $this->Entry_form_mx->get_rightful_claimants($entry_form->id);

        $data = [
            'entry_form' => $entry_form_mx,
            'rightful_claimants' => $rightful_claimants,
            'process_country' => $process_country,
            'process_id' => $process_id
        ];
        
        return $data;
    }
}
