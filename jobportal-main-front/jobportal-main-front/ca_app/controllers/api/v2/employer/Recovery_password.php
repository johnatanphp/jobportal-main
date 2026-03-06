<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_authbasic_Controller.php';
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class Recovery_password extends Api_v2_authbasic_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function recover_post()
    {
        $params = $this->post();

        $email = isset($params['email']) ? trim($params['email']) : '';

        if ($email == '') {
            $this->response(
                apiv2_response(
                    false, 
                    'El campo email es requerido'
                ),
                200
            );
            return;
        }

        $employer = $this->Employer->authenticate_employer_by_email($email);

        if (!$employer) {
            $this->response(
                apiv2_response(
                    false, 
                    'Email ingresado no fue encontrado'
                ),
                200
            );
            return;
        }

        if ($employer->sts != 'active') {
            this->response(
                apiv2_response(
                    false, 
                    'La cuenta del email no esta activa'
                ),
                200
            );
            return;
        }

        //Generar contraseña 10 caracteres
        $generator = new ComputerPasswordGenerator();

		$generator
			->setUppercase()
			->setLowercase()
			->setNumbers()
			->setSymbols(true)
			->setLength(10);

		$password = $generator->generatePassword();

        $update_sts = $this->Employer->update($employer->ID, [
            'pass_code' => do_hashing($password)
        ]);

        if (!$update_sts) {
            $this->response(
                apiv2_response(
                    false, 
                    'No se pudo recuperar la contraseña'
                ),
                200
            );
            return;
        }

        $data_email = [
            'employer_name' => $employer->first_name, 
            'password' => $password
        ];

        //Enviar email de notificacion
        $config = $this->Email_drafts->email_configuration();
        
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($employer->email);

        $mail_message = load_email_view('email/employer/recovery_password', $data_email);
        $this->email->subject('Recuperar contraseña');
        $this->email->message($mail_message);     
        $email_sts = $this->email->send();

        if ($email_sts === false) {
            $this->response(
                apiv2_response(
                    false, 
                    'No se pudo recuperar la contraseña email no pudo ser enviado'
                ),
                200
            );
            return;
        }

        $this->response(
            apiv2_response(true, 'Contraseña ha sido enviada a su correo'),
            200
        );
    }
}
