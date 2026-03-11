<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_employer_login
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function login($email, $password)
    {
        $this->load->model('Employer_profile');

        $user_type = 'app_user';
        $max_login_attempts = 6;

        $user = $this->Employer->authenticate_employer_by_email($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }

        if ($user->sts == 'pending') {
            return [
                'success' => false,
                'message' => 'Cuenta pendiente por verificación'
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
            $this->db->update('tbl_employers', [
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
            $this->db->update('tbl_employers', $data_update);

            if ($login_attempts >= $max_login_attempts) {
                return [
                    'success' => false,
                    'message' => '¡Máximo intentos de inicio de sesión, por favor espere 30 minutos para que intente de nuevo!'
                ];
            }
    
            if ($max_login_attempts - $user->login_attempts > 0) {
                $message = '<b>Credenciales incorrectas.</b> <p>Usted tiene como máximo ' . $max_login_attempts . ' 
                            intentos para iniciar sesión correctamente, de lo contrario su acceso 
                            será bloqueado por medidas de seguridad.
                            Intentos restantes <b>(' . ($max_login_attempts - $login_attempts) . ')</b>.</p>';
                return [
                    'success' => false,
                    'message' => $message
                ];
            }
        }

        // Verificación de empresa - Verificar status del usuario es suficiente
        // Comentado para simplificar el flow de login
        
        // $company = $this->Company->find($user->company_id);
        // if (!$company) { ... }

        $active_profiles = $user ? $this->Employer_profile->get_active_profiles($user->ID) : [];
        
        if (count($active_profiles) == 0) {
            return [
                'success' => false,
                'message' => 'La cuenta no tiene perfiles activos'
            ];
        }

        $data_user_update = [
            'last_login_date' => date('Y-m-d H:i:s'), 
            'login_attempts' => 0,
            'blocked_at' => null
        ];

        if (empty($user->first_login_date)) {
            $data_user_update['first_login_date'] = date('Y-m-d H:i:s'); 
        }

        $this->db->where('ID', $user->ID);
        $this->db->update('tbl_employers', $data_user_update);

        return [
            'success' => true,
            'message' => 'OK',
            'data' => [
                'user_id' => $user->ID
            ]
        ];
    }
    
    private function verify_password($password_verify, $password)
    {
        return (substr($password, 0, 7) == '$2y$10$' && password_verify($password_verify, $password)) || 
               (substr($password, 0, 7) != '$2y$10$' && $password == $password_verify);        
    }
}
