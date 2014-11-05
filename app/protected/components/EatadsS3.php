<?php

// ref - http://docs.aws.amazon.com/aws-sdk-php/guide/latest/service-s3.html
require_once  'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Enum\CannedAcl;

class EatadsS3 {

    public $accessKey;		// AWS Access key	
	public $secretKey;		// AWS Secret key
	public $bucket;
	public $lastError = "";
    public $client;

	/**
	 * Instance the S3 object
	 */
	public function __construct($bucket=null) {
        
		$this->client = S3Client::factory(array('key' => Yii::app()->params['awss3']['accessKey'], 'secret' => Yii::app()->params['awss3']['secretKey']));
            
            if($bucket!=null) {
			$this->bucket = $bucket;	
        } else {
            $this->bucket = Yii::app()->params['awss3']['s3Bucket'];
        }
	}

	/*
	 * download bucket to a local directory
	 */
	public function downloadBucket($downloadDirPath) {
		$this->client -> downloadBucket($downloadDirPath, $this -> bucket);
	}

	/*
	 * return list of buckets
	 */
	public function listBuckets() {
		$result = $this->client -> listBuckets();
		return $result['Buckets'];
	}

	/*
	 * Create bucket
	 */
	 public function createBucket($bucket)
	 {
	 	$this->client->createBucket(array('Bucket' => $bucket));
	 } 

	/*
	 Buckets cannot be deleted unless they're empty. With the AWS SDK for PHP, you
	 have two options:
	
	  - Use the clearBucket helper:
	      http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Aws.S3.S3Client.html#_clearBucket
	  - Or individually delete all objects.
	
	 Since this sample created a new unique bucket and uploaded a single object,
	 we'll just delete that object.
	*/
	public function deleteBuckets() {
		/*
		 // Delete the objects in the bucket before attempting to delete the bucket
		 $clear = new ClearBucket($this->client, $this->bucket);
		 $clear->clear();

		 // Delete the bucket
		 $this->client->deleteBucket(array('Bucket' => $this->bucket));

		 // Wait until the bucket is not accessible
		 $this->client->waitUntilBucketNotExists(array('Bucket' => $this->bucket));
		 */
	}

	/*
	 *
	 */
	public function getBucketObjects($bucket = null) {
		if ($bucket != null) {
			$this -> bucket = $bucket;
		}        
        $iterator = $this->client->getIterator('ListObjects', array('Bucket' => $this -> bucket));

		//return $iterator;
        $objects = array();
		foreach ($iterator as $object) {
            array_push($objects, $object['Key']);
		}
        return $objects;
	}

	/*
	 * get object
	 */
	public function getObject($fileName) {
		// Get an object using the getObject operation
		$result = $this->client -> getObject(array('Bucket' => $this -> bucket, 'Key' => $fileName));
		return $result;
	}

	/*
	 * upload file
	 * normal file & file with permissions
	 */
	public function uploadFile($fileName, $newFileName) {
		//$this->client->putObjectFile($fileName, $this->bucket);
		$result = $this->client->putObject(array('SourceFile' => $fileName, 'Bucket' => $this->bucket, 'Key' => $newFileName,
		'ACL' => CannedAcl::PUBLIC_READ
		));
	}

	/*
	 * get file url
	 */
	public function getFileUrl($fileName, $duration = null) {
		if (is_numeric($duration)) {
			// duration in minutes
			$minutes = "+" . $duration . " minutes";
			// Get a pre-signed URL for an Amazon S3 object
			return $signedUrl = $this->client -> getObjectUrl($this -> bucket, $fileName, $minutes);
		} else {
			// Get a plain URL for an Amazon S3 object
			return $plainUrl = $this->client -> getObjectUrl($this -> bucket, $fileName);
		}
	}

	/*
	 * delete a file
	 */
	public function deleteFile($fileName) 
    {                
		$result = $this->client -> deleteObject(array(
                'Bucket' => $this -> bucket, 
                'Key' => $fileName));
	}
    
    /*
	 * delete a file
	 */
	public function deleteMultiFiles($fileNamesArray=array()) 
    {        
        $files = array();
        if(count($fileNamesArray)) {
            foreach($fileNamesArray as $fileName) {
                array_push($files, array('Key'=>$fileName));
            }
        }        
		$result = $this->client -> deleteObjects(array(
                'Bucket' => $this -> bucket, 
                'Objects' => $files));
	}

	/*
	 * poll bucket until it is accessible
	 */ 
	 public function pollBucket($bucket=null)
	 {
	 	if ($bucket != null) {
			$this->bucket = $bucket;
		}
		$this->client->waitUntilBucketExists(array('Bucket' => $this->bucket));
	 }
	 

}
?>