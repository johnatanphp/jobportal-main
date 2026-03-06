<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Record_video extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
    }

    public function record($key_access = '')
    {
        $video_interview = $this->db->get_where(
            'tbl_recruitment_interview_videos', [
                'key_access' => $key_access
        ])->row();

        if (!$video_interview || $video_interview->video_path) {
            show_404();
        }

        $data['ads_row'] = $this->ads;
        $data['title'] = "Grabar video";
        $data['seeker'] = $this->Job_seeker->get_job_seeker_by_id($video_interview->seeker_id);
        $data['video_interview'] = $video_interview;

        $this->load->view('jobseeker/record_video/record', $data);
    }

    public function upload($key_access = '')
    {
        //set_time_limit(0);
		ini_set('max_execution_time', 0); //0=NOLIMIT

        $video_interview = $this->db->get_where(
            'tbl_recruitment_interview_videos', [
                'key_access' => $key_access
        ])->row();

        if (!$video_interview || $video_interview->video_path) {
            exit(json_encode(
                ['error' => 'Error al cargar el video']
            ));
        }

        $file = isset($_FILES['video']) ? $_FILES['video'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el video - Video no cargado']
            ));
        }

        $file_name = md5(uniqid($video_interview->seeker_id, true)) . ".webm";

        try {
            $this->load->library('storage_lib');
            $path = 'employer/recruitment_selection_documents/' . $file_name;
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el video - No se pudo subir'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $data_video = [
            'video_path' => $path,
        ];

        $this->db->where('key_access', $video_interview->key_access);
        $trans_status = $this->db->update(
            'tbl_recruitment_interview_videos',
            $data_video
        );

        if (!$trans_status) {
            exit(json_encode(
                array('error' => 'Error al guardar el video')
            ));
        }

        echo json_encode([
            'success' => true
        ]);
    }

    public function show($share_token = '')
    {
        $video = $this->db->get_where(
            'tbl_recruitment_interview_videos', [
                'share_token' => $share_token
        ])->row();

        if (!$video || !$video->video_path) {
            show_404();
        }

        $data['ads_row'] = $this->ads;
        $data['title'] = "Video grabado";
        $data['seeker'] = $this->Job_seeker->get_job_seeker_by_id($video->seeker_id);
        $data['video'] = $video;

        $this->load->view('jobseeker/record_video/show', $data);
    }

    public function save_comment($share_token = '')
    {
        $video = $this->db->get_where(
            'tbl_recruitment_interview_videos', [
                'share_token' => $share_token
        ])->row();

        if (!$video || !$video->video_path) {
            show_404();
        }

        $data = [
            'comment' => $this->input->post('comment'),
            'qualification' => $this->input->post('qualification')
        ];

        $this->db->where('id', $video->id);
        $this->db->update('tbl_recruitment_interview_videos', $data);

        echo json_encode([
            'success' => true
        ]);
    }
}
