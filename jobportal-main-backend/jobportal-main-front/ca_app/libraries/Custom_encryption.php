<?php
class Custom_encryption
{
	private $key;
	
    public function __get($var)
    {
        return get_instance()->$var;
    }
    
	public function __construct()
	{
	    $this->key = $this->config->item('custom_encryption_secret_key');
	}
	
	public function encrypt_data($value, $type = 0)
	{
		if ($type == 0) {
			return bin2hex(str_replace(Custom_Encryption::character_input(), Custom_Encryption::codes(), $value));
		}

		if ($type == 1) {
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
			
			$value = is_array($value) ? json_encode($value) : $value;
				
			// Encriptamos el dato
			$encrypted = openssl_encrypt($value, 'aes-256-cbc', $this->key, 0, $iv);

			// Devolvemos el IV y el dato encriptado codificados en base64 para que sean seguros de almacenar
			return base64_url_encode(base64_encode($encrypted . '[-sd0-]' . $iv));
		}
	}
	
	public function decrypt_data($value, $type = 0)
	{
		if ($type == 0) {
			return str_replace(Custom_Encryption::codes(), Custom_Encryption::character_input(), $this->hex_2_bin($value));
		}

		if ($type == 1) {
		  					
			$value = base64_url_decode($value);
		
			// Decodificamos el dato completo
			list($encrypted_data, $iv) = explode('[-sd0-]', base64_decode($value), 2);

			// Desencriptamos y devolvemos el resultado
			$data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $this->key, 0, $iv);
		
			$data = @json_decode($data, true);
			
			if (is_array($data)) {  
                return $data;
            } 
            
			return strval($data);
		}
	}
	
	private static function codes()
	{
		return array('[c^','|fY','F_=','IO','bY','xE','<wA','Vy','gP!','M>*');	
	}
	
	private static function character_input()
	{
		return array('0','1','2','3','4','5','6','7','8','9');	
	}
	
	private function hex_2_bin($h)
	{
		if (!is_string($h)) 
			return null;
		$r = '';
		for ($a = 0; $a < strlen($h); $a+=2) { 
			$r.=@chr(hexdec($h[$a] . $h[($a + 1)])); 
		}
		return $r;
  }
	
}
