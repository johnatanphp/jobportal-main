<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_job_seeker_login
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }
    
    public function login($email, $password)
    {   
        $email = trim((string)$email);
        $password = trim((string)$password);

        if ($email == '' || $password == '') {
            return [
                'success' => false,
                'message' => 'Debe ingresar usuario y contraseña'
            ];
        }

        $max_login_attempts = 6;
        $user = $this->Job_seeker->authenticate_job_seeker_email_address($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }

        $login_attempts = $user->login_attempts;
        $sts = $user->sts;

        if ($sts != 'active') {
            return [
                'success' => false,
                'message' => 'Cuenta no esta activa'
            ];
        } 

        //Verificar si han pasado 30 minutos
        //para desbloquear usuarios
        if ($user->blocked_at) {
            $interval = abs(strtotime("now") - strtotime($user->blocked_at));
        
            if (round($interval / 60) <= 30) {
                return [
                    'success' => false,
                    'message' => 'Su cuenta está bloqueada, por favor póngase en contacto con el administrador del sitio o espere 30 minutos para que intente de nuevo'
                ];
            }

            $this->db->where('ID', $user->ID);
            $this->db->update('tbl_job_seekers', [
                'login_attempts' => 0,
                'blocked_at' => null
            ]); 

            $login_attempts = 0;
        }

        if (!$this->verify_password($password, $user->password)) {

            $login_attempts = $login_attempts + 1;

            $data_update['login_attempts'] = $login_attempts;

            if ($login_attempts > 5) {
                $data_update['blocked_at'] = date('Y-m-d H:i:s');
            }

            $this->db->where('ID', $user->ID);
            $this->db->update('tbl_job_seekers', $data_update);

            if ($login_attempts >= $max_login_attempts) {
                return [
                    'success' => false,
                    'message' => '¡Máximo intentos de inicio de sesión, por favor espere 30 minutos para que intente de nuevo!'
                ];
            }
    
            if ($max_login_attempts - $login_attempts > 0) {
                $message = '<b>Credenciales incorrectas</b> <br> Usted tiene como máximo ' . $max_login_attempts . ' 
                            intentos, de lo contrario su acceso será bloqueado por medidas de seguridad.
                            Intentos restantes (' . ($max_login_attempts - $login_attempts) . ').';
                return [
                    'success' => false,
                    'message' => $message
                ];
            }
        }

        $data_user_update = [
            'login_attempts' => 0,
            'blocked_at' => null
        ];

        if (empty($user->first_login_date)) {
        }

        $this->db->where('ID', $user->ID);
        $this->db->update('tbl_job_seekers', $data_user_update);

        return [
            'success' => true,
            'message' => 'OK',
            'data' => [
                'seeker_id' => $user->ID
            ]
        ];
    }

    private function verify_password($password_verify, $password)
    {
        return (substr($password, 0, 7) == '$2y$10$' && password_verify($password_verify, $password)) || 
               (substr($password, 0, 7) != '$2y$10$' && $password == $password_verify);        
    }
}
