<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Spatie\UrlSigner\Sha256UrlSigner;

class Url_signer_lib 
{
    private $url_signer;
    
    public function __get($var)
    {
        return get_instance()->$var;
    }
    
    public function __construct()
    {
        $this->url_signer = new Sha256UrlSigner('1234');
    }
    
    public function sign($url)
    {
        $expiration_date = (new DateTime())->modify('1 hour');
        
        return $this->url_signer->sign($url, $expiration_date);
    }
    
    public function validate($url)
    {
        return  $this->url_signer->validate($url);
    }
}
