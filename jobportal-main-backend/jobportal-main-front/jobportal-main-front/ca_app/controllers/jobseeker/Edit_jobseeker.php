<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_Jobseeker extends CI_Controller {
	
	public function index(){
		echo "you are not allow to access this page directly";
		exit;
	}
	
	public function profile()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($this->session->userdata('user_id'));
				
		$this->form_validation->set_rules('full_name', 'full name', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('mobile', 'mobile', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('country', 'country', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('city', 'city', 'trim|required|strip_all_tags');
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}
		$profile_array = array(
							'first_name'		=> $this->input->post('full_name'),
							'last_name'			=> '',
							'mobile'			=> $this->input->post('mobile'),
							'country' 			=> $this->input->post('country'),
							'city' 				=> $this->input->post('city'),
		);
		$this->Job_seeker->update($this->session->userdata('user_id'), $profile_array);
		$this->session->set_userdata('first_name',$this->input->post('full_name'));
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> El perfil se ha actualizado con éxito. </div>');
		echo "done";
	}
	
	public function summary()
	{
		if (!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('content', 'summary', 'trim|required|strip_all_tags');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$summary_array = array(
			'summary' => $this->input->post('content')
		);
		
		$row = $this->Jobseeker_additional_info->get_record_by_userid($this->session->userdata('user_id'));

		if ($row){
			$this->Jobseeker_additional_info->update($row->ID, $summary_array);
		} else {
			$summary_array['seeker_ID'] = $this->session->userdata('user_id');
			$this->Jobseeker_additional_info->add($summary_array);
		}
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Resumen profesional ha sido actualizado con éxito. </div>');
		echo "done";
	}
	
	public function delete_applied_job()
	{	
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('id', 'id', 'trim|required|strip_all_tags|numeric');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}
		
		$this->Applied_jobs->delete_applied_job_by_id_seeker_id($this->input->post('id'), $this->session->userdata('user_id'));
		echo "done";
		
	}
	
	/*
	public function upload_photo()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		if (!empty($_FILES['upload_pic']['name'])) {
		
			$obj_row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));
			$company_name_for_file = strtolower($this->input->post('company_name'));
			$real_path = realpath(APPPATH . '../public/uploads/candidate/');
			$config['upload_path'] = $real_path;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['overwrite'] = true;
			$config['max_size'] = 4000;
			$config['encrypt_name'] = true;

			$this->upload->initialize($config);
	
			if ($this->upload->do_upload('upload_pic')) {
				if($obj_row->photo){
					@unlink($real_path.'/'.$obj_row->photo);	
					@unlink($real_path.'/thumb/'.$obj_row->photo);
				}
			} else{
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('msg', '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> '.strip_tags($error['error']).' </div>');
				redirect(base_url('jobseeker/dashboard'));
				exit;
			}

			$image = array('upload_data' => $this->upload->data());	
			$image_name = $image['upload_data']['file_name'];
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = $real_path.'/'.$image_name;
			$thumb_config['new_image'] = $real_path.'/thumb/'.$image_name;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['height']	= 200;
			$thumb_config['width'] = 200;
			
			$this->image_lib->initialize($thumb_config);
			$this->image_lib->resize();
			
			$photo_array = array('photo' => $image_name);
			$this->Job_seeker->update($this->session->userdata('user_id'), $photo_array);
			$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Foto cargada con éxito. </div>');
		}
		redirect(base_url('jobseeker/dashboard'));
	}

	public function upload_photo()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		if (!empty($_FILES['upload_pic']['name'])) {
		
			$obj_row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));
			$company_name_for_file = strtolower($this->input->post('company_name'));
			$real_path = realpath(APPPATH . '../public/uploads/candidate/');
			$config['upload_path'] = $real_path;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['overwrite'] = true;
			$config['max_size'] = 4000;
			$config['encrypt_name'] = true;

			$this->upload->initialize($config);
	
			if ($this->upload->do_upload('upload_pic')) {
				if($obj_row->photo){
					@unlink($real_path.'/'.$obj_row->photo);	
					@unlink($real_path.'/thumb/'.$obj_row->photo);
				}
			} else{
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('msg', '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> '.strip_tags($error['error']).' </div>');
				redirect(base_url('jobseeker/dashboard'));
				exit;
			}

			$image = array('upload_data' => $this->upload->data());	
			$image_name = $image['upload_data']['file_name'];
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = $real_path.'/'.$image_name;
			$thumb_config['new_image'] = $real_path.'/thumb/'.$image_name;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['height']	= 200;
			$thumb_config['width'] = 200;
			
			$this->image_lib->initialize($thumb_config);
			$this->image_lib->resize();
			
			$photo_array = array('photo' => $image_name);
			$this->Job_seeker->update($this->session->userdata('user_id'), $photo_array);
			$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Foto cargada con éxito. </div>');
		}
		redirect(base_url('jobseeker/dashboard'));
	}
*/

	public function upload_photo()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		$file = isset($_FILES['upload_pic']) ? $_FILES['upload_pic'] : null;

        if (is_null($file)) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error</strong> 
        		 		¡Debe proporcionar una imagen!
        		 </div>'
        	);

			redirect('jobseeker/cv_manager');
			exit;
        }

        $allowed = [
			'image/gif',
			'image/jpeg',
			'image/png'
        ];

        if (!in_array($file['type'], $allowed)) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error</strong> 
        		 		¡Tipo de archivo no es válido!
        		 </div>'
        	);
			
			redirect('jobseeker/cv_manager');
			exit;
        }

        if ($file['size'] > (4 * 1048576)) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error</strong> 
        		 		¡El archivo a subir debe ser menor o igual a 4MB!
        		 </div>'
        	);
			
			redirect('jobseeker/cv_manager');
			exit;
        }

        $seeker_id = $this->session->userdata('user_id');

        $file_previus = $this->Job_seeker->get_job_seeker_by_id(
            $seeker_id
        );

        $real_path = sys_get_temp_dir();

		$file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';
		$file_name = md5(uniqid($seeker_id, true)) . $file_ext;

		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = $file['tmp_name'];
		$thumb_config['new_image'] = $real_path . '/candidate_pic_' . $file_name;
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['height']	= 200;
		$thumb_config['width'] = 200;

		$this->image_lib->initialize($thumb_config);
		$this->image_lib->resize();

        try {
            $this->load->library('storage_lib');

            $path_image = 'candidate/pic/' . $file_name;
            $path = $this->storage_lib->put($path_image, $real_path . '/candidate_pic_' . $file_name);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> 
        		 		Error enviar la imagen al servidor
        		 </div>'
        	);
			
			redirect('jobseeker/cv_manager');
			exit;
        }

        $this->storage_lib->setVisibility($path, 'public');

		$photo_array = ['photo' => $path];
		$this->Job_seeker->update($this->session->userdata('user_id'), $photo_array);

	    if ($file_previus && 
            $file_previus->photo &&
            $this->storage_lib->has($file_previus->photo)) {
            $this->storage_lib->delete($file_previus->photo);
        }
		
		$this->session->set_flashdata(
			'msg', 
			'<div class="alert alert-success"> 
			    <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo</strong> 
			    ¡Foto cargada con éxito! 
			</div>'
		);

		redirect('jobseeker/cv_manager');
	}

	public function delete_photo()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		$obj_row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));
		$this->Job_seeker->update($this->session->userdata('user_id'), array('photo'=>''));
		$real_path = realpath(APPPATH . '../public/uploads/candidate/');
		@unlink($real_path.'/'.$obj_row->photo);	
		@unlink($real_path.'/thumb/'.$obj_row->photo);
		echo "done";
	}
	
	public function is_image($path){
		$a = getimagesize($path);
		$image_type = $a[2];
		 
		if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
		{
			return true;
		}
		return false;
	}
	
	/*
	public function upload_cv()
	{
		if (!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
	
		if (!empty($_FILES['upload_resume']['name'])){
			$obj_row = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));
			$real_path = realpath(APPPATH . '../public/uploads/candidate/resumes/');
			$config['upload_path'] = $real_path;
			$config['allowed_types'] = 'doc|docx|pdf';
			$config['overwrite'] = true;
			$config['max_size'] = 4000;
			$config['file_name'] = make_slug($obj_row->first_name).'-JOBPORTAL-'.$obj_row->ID.time();
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('upload_resume')){
				
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('msg', '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> '.strip_tags($error['error']).' </div>');
				redirect(base_url('jobseeker/cv_manager'));
				exit;
			}
			
			$resume = ['upload_data' => $this->upload->data()];	
			$resume_file_name = $resume['upload_data']['file_name'];
			$resume_array = [
				'seeker_ID' => $obj_row->ID,
				'file_name' => $resume_file_name,
				'dated' => date("Y-m-d H:i:s"),
				'is_uploaded_resume' => 'yes'			
			];
			$this->Resume->add($resume_array);	

			$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> CV cargado con éxito. </div>');
		}
		redirect(base_url('jobseeker/cv_manager'));
	}
*/
	public function upload_cv()
	{
		$file = isset($_FILES['upload_resume']) ? $_FILES['upload_resume'] : null;

        if (is_null($file)) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> 
        		 		Error al cargar el documento - Documento no cargado
        		 </div>');
			
			redirect('jobseeker/cv_manager');
			exit;
        }

        $allowed = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text'
        ];

        if (!in_array($file['type'], $allowed)) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> 
        		 		Error al cargar el documento - Tipo de archivo no es válido
        		 </div>'
        	);
			
			redirect('jobseeker/cv_manager');
			exit;
        }

        if ($file['size'] > (4 * 1048576)) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> 
        		 		Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB
        		 </div>'
        	);
			
			redirect('jobseeker/cv_manager');
			exit;
        }

        $seeker_id = $this->session->userdata('user_id');
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/resumes/' . md5(uniqid($seeker_id, true)) . $file_ext;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {

        	$this->session->set_flashdata(
        		'msg', 
        		'<div class="alert alert-danger"> 
        		     <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> 
        		 		Error al cargar el documento - No se pudo subir el documento
        		 </div>'
        	);
			
			redirect('jobseeker/cv_manager');
			exit;
        }

        $this->storage_lib->setVisibility($path, 'public');

		$resume_array = [
			'seeker_ID' => $seeker_id,
			'file_name' => $path,
			'dated' => date("Y-m-d H:i:s"),
			'is_uploaded_resume' => 'yes'			
		];

		$this->Resume->add($resume_array);	
    
  		$this->session->set_flashdata(
  			'msg', 
  			'<div class="alert alert-success"> 
  			    <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> 
  			    CV cargado con éxito. 
  			</div>'
  		);

  		redirect('jobseeker/cv_manager');
	}
}
