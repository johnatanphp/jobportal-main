<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gantt_activities extends CI_Controller
{	
    public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        //Load models`
		$this->load->model('Gantt_activity'); 
		$this->load->model('Gantt_type'); 
		$this->load->model('Staff_request_gantt');
		$this->load->model('Staff_request_gantt_activity');

		//Load libraries
		$this->load->library(
			'Notification/Email/Notification_email_staff_request_lib'
		);
    }

	public function index($request_id, $type_id)
	{
		$staff_request = $this->Staff_request->find($request_id);

		if (!$staff_request) {
			show_404();
		}

		$gantt_type = $this->Gantt_type->find($type_id);

		if (!$gantt_type) {
			show_404();
		}

		$this->form_validation->set_rules('gantt_tasks[]', 'Actividades del Gantt', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

		$gantt_id = null;
		$gantt = $this->Staff_request_gantt->find(['request_ID' => $request_id, 'type_id' => $type_id]);

		if ($gantt) {
			$gantt_id = $gantt->ID;
		}

		if ($this->form_validation->run() == FALSE) {

			$data['title'] = 'Gantt de actividades de la solicitud - ' . SITE_NAME;
			$data['gantt_activities'] = $this->Gantt_activity->get_active_activities();
			$data['gantt_type'] = $gantt_type;
			$data['request_gantt'] = $gantt;
			$data['data_gantt_chart'] = $this->Staff_request_gantt_activity->get_gantt_activities($gantt_id);
			$data['type_id'] = $type_id;
			$data['request'] = $staff_request;
			$data['ads_row'] = $this->ads;
		
			$this->load->view('employer/staff_request/create_gantt_view', $data);
			return;
		}
		
		$activities = $this->input->post('gantt_tasks');
		$ignore_weekend = $this->input->post('ignore_weekend');
		
		$this->db->trans_status();

		if ($gantt) {
			$gantt_id = $gantt->ID;
			$data_gantt = [
				'ignore_weekend' => $ignore_weekend ? 1 : 0,
			];

			$this->db->where('ID', $gantt_id);
			$this->db->update('tbl_staff_request_gantt', $data_gantt);
		} else {
			$data_gantt = [
				'creation_date' => date('Y-m-d H:i:s'),
				'ignore_weekend' => $ignore_weekend ? 1 : 0,
				'request_ID' => $request_id,
				'type_id' => $type_id
			];
			$this->db->insert('tbl_staff_request_gantt', $data_gantt);

			$gantt_id = $this->db->insert_id();
			$gantt = $this->Staff_request_gantt->find($gantt_id);
		}

		$this->db->where('gantt_id', $gantt_id);
		$this->db->delete('tbl_staff_request_gantt_activities');
	
		$i = 0;
		$activity_data = [];

		foreach ($activities as $task) {
			
			$activity_id = trim($task['activity_id']);
			$start_datetime = strtotime(str_replace('/', '-', trim($task['start_date'])));
			$end_datetime = strtotime(str_replace('/', '-', trim($task['end_date'])));
			$other_activity =  trim((string)$task['other_activity']);

			if (empty($activity_id) ||
				empty($start_datetime) || 
				empty($end_datetime) || 
				$end_datetime < $start_datetime) {
				continue;
			}

			$activity_data[] = [
				'activity_ID' => $activity_id,
				'start_date' => date('Y-m-d', $start_datetime),
				'end_date' => date('Y-m-d', $end_datetime),
				'position' => (++$i), 
				'other_activity' => $other_activity,
				'gantt_id' => $gantt_id
			];
		}

		if (count($activity_data) == 0) {
			flash_message('danger', 'No hay actividades que registrar');
			redirect('employer/staff_request/gantt_activities/' . $request_id . '/'. $type_id);
			return;
		}

		$this->db->insert_batch('tbl_staff_request_gantt_activities', $activity_data);
		$this->db->trans_complete();

		$trans_status = $this->db->trans_status();
		
		if ($trans_status) {
			flash_message('success', 'Se ha guardado el Gantt de actividades con éxito.');
			$send_status = $this->notification_email_staff_request_lib->notify_creation_gantt_activities(
				$gantt_id
			);
		} else {
			flash_message('danger', 'Error al guardar el Gantt de actividades.');
		}
		
		redirect('employer/staff_request/gantt_activities/' . $request_id . '/'. $type_id);
	}

    public function send_by_email()
    {
    	$gantt_id = $this->input->post('gantt_id');
    	$recipients = (array)$this->input->post('recipients');

		$send_status = $this->notification_email_staff_request_lib->notify_creation_gantt_activities(
			$gantt_id, 
			$recipients
		);

		echo json_encode([
			'success' => $send_status
		]);
    }

	public function export($gantt_id)
	{
		$gantt = $this->Staff_request_gantt->find($gantt_id);

		$this->load->library(
            'Exports/Staff_request_Gantt_activities_export', 
            null, 
            'Staff_request_Gantt_activities_export'
        );

		$this->Staff_request_Gantt_activities_export->build_gantt($gantt->ID);
		$this->Staff_request_Gantt_activities_export->download();		
	}
}