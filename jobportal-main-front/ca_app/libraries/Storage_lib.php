<?php

use Aws\S3\S3Client;

//use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;

class Storage_lib
{
	private $file;

	// Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

	public function __construct()
	{
		$client = new S3Client([
		    'credentials' => [
				'key' => $this->config->item('aws_s3_key'), 
				'secret' => $this->config->item('aws_s3_secret')
		    ],
		    'region' => 'us-east-1',
		    'version' => 'latest',
		]);

		$adapter = new AwsS3V3Adapter($client, 'overall-portal-de-empleo');

		$this->file = new Filesystem($adapter);
	}

	public function put($path, $from_path)
	{
		$resource = fopen($from_path, 'r');

		if ($this->file->has($path)) {
			$this->file->updateStream($path, $resource);
		} else {
			$this->file->writeStream($path, $resource);
		}

		if (is_resource($resource)) {
			fclose($resource);
		}

		return $path;
	}

	public function get($path)
	{
		try {
			return $this->file->readStream($path);
		} catch (League\Flysystem\UnableToReadFile $e) {}

		return false;
	}

	public function delete($path)
	{
		try {
			$this->file->delete($path);
			return true;
		} catch (League\Flysystem\UnableToDeleteFile $exception) {}

		return false;
	}

	public function setVisibility($path, $visibility)
	{
		$this->file->setVisibility($path, $visibility);
	}

	public function has($path)
	{
		return $this->file->has($path);
	}

	public function getFileClass()
	{
		return $this->file;
	}
}
