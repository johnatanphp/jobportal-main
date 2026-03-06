<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Institutes extends CI_Controller 
{	
	public function __construct()
    {
        parent::__construct();
    }

    public function search_suggestions()
    {
        $suggestions = $this->Institute->search_suggestions($this->input->get('term'), 10);
        echo json_encode($suggestions);
    }
}
