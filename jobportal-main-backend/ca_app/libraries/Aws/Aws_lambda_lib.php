<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aws_lambda_lib
{    
    private $lambda_client = null;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct(){}

    public function get_client()
    {
        $lambda_client = null;

        try {
			$lambda_client = new \Aws\Lambda\LambdaClient([
				'region'      => 'us-east-2',
				'version'     => 'latest',
				'credentials' => [
					'key'    => $this->config->item('aws_lambda_sqs_key'),
					'secret' => $this->config->item('aws_lambda_sqs_secret'),
				],
			]);

		} catch (\Aws\Exception\AwsException $e) {}

        return $lambda_client;
    }
}
