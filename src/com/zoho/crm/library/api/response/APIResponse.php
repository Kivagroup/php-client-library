<?php
require_once realpath(dirname(__FILE__)."/../../common/APIConstants.php");
require_once realpath(dirname(__FILE__)."/../../exception/ZCRMException.php");
require_once 'CommonAPIResponse.php';

class APIResponse extends CommonAPIResponse
{
	private $data=null;
	private $status=null;
	
	
	public function __construct($httpResponse,$httpStatusCode,$apiName=null)
	{
		parent::__construct($httpResponse,$httpStatusCode,$apiName);
	}
	
	public function setData($data)
	{
		$this->data=$data;
	}
	public function getData()
	{
		return $this->data;
	}
	/**
	 * Get the response status
	 * @return String
	 */
	public function getStatus(){
		return $this->status;
	}
	
	/**
	 * Set the response status
	 * @param String $status
	 */
	public function setStatus($status){
		$this->status = $status;
	}

	public function handleForFaultyResponses()
	{
		$statusCode=self::getHttpStatusCode();
		if(in_array($statusCode,APIExceptionHandler::getFaultyResponseCodes()))
		{
			if($statusCode==APIConstants::RESPONSECODE_NO_CONTENT)
			{
				$exception=new ZCRMException(APIConstants::INVALID_DATA."-".APIConstants::INVALID_ID_MSG,$statusCode);
				$exception->setExceptionCode("No Content");
				throw $exception;
			}
			else
			{
				$responseJSON=$this->getResponseJSON();
				$exception=new ZCRMException($responseJSON['message'],$statusCode);
				$exception->setExceptionCode($responseJSON['code']);
				$exception->setExceptionDetails($responseJSON['details']);
				throw $exception;
			}
		}
	}
    public function processResponseData()
    {
    	$responseJSON=$this->getResponseJSON();
    	if($responseJSON==null){
    		return;
    	}
    	if(array_key_exists("data",$responseJSON))
    	{
    		$responseJSON=$responseJSON['data'][0];
    	}
    	else if(array_key_exists("users",$responseJSON))
    	{
    		$responseJSON=$responseJSON['users'][0];
    	}
    	if(isset($responseJSON['status']) && $responseJSON['status']==APIConstants::CODE_ERROR)
    	{
    		$exception=new ZCRMException($responseJSON['message'],self::getHttpStatusCode());
    		$exception->setExceptionCode($responseJSON['code']);
    		$exception->setExceptionDetails($responseJSON['details']);
    		throw $exception;
    	}
    	elseif (isset($responseJSON['status']) && $responseJSON['status']==APIConstants::CODE_SUCCESS)
    	{
    		self::setCode($responseJSON['code']);
    		self::setStatus($responseJSON['status']);
    		self::setMessage($responseJSON['message']);
    		self::setDetails($responseJSON['details']);
    	}
    	
    	/*if($statusCode!=APIConstants::RESPONSECODE_OK)
    	{
    		$content_after_decode=json_decode($content,true);
    		if(self::getApiKey()!=null && array_key_exists(self::getApiKey(),$content_after_decode))
    		{
    			$response=$content_after_decode[self::getApiKey()];
    		}else
    		{
    			$response=$content_after_decode;
    		}
    		$apiResponse->setCode($response['code']);
    		$apiResponse->setStatus($response['status']);
    		$apiResponse->setMessage($response['message']);
    		$apiResponse->setDetails($response['details']);
    	}*/
    	
    	
    	/*if($responseInfo['http_code']==APIConstants::RESPONSECODE_OK && array_key_exists("data",$jsonResponse))
    	{
    		$responseData=$jsonResponse['data'][0];
    		if($responseData['status']==APIConstants::CODE_ERROR)
    		{
    			$exception=new ZCRMException($responseData['message']);
    			$exception->setExceptionCode($responseData['code']);
    			throw $exception;
    		}
    		$apiResponse->setCode($responseData['code']);
    		 $apiResponse->setStatus($responseData['status']);
    		 $apiResponse->setMessage($responseData['message']);
    		 $apiResponse->setDetails($responseData['details']);
    	}*/
    	
    	/*$messageJSON=self::getResponseJSON();
    	if($messageJSON!=null && array_key_exists("data",$messageJSON))
    	{
    		$messageJSON=$messageJSON['data'][0];
    	}
    	if(array_key_exists("status",$messageJSON) && $messageJSON['status']==APIConstants::CODE_ERROR)
    	{
    		$exception=new ZCRMException($messageJSON['message']);
    		$exception->setExceptionCode($messageJSON['code']);
    		throw $exception;
    	}
    	if($statusCode==APIConstants::RESPONSECODE_OK || $statusCode==APIConstants::RESPONSECODE_ACCEPTED)
    	{
    		return;
    	}
    	else if($statusCode==APIConstants::RESPONSECODE_NO_CONTENT)
    	{
    		$exception=new ZCRMException(APIConstants::INVALID_ID_MSG);
    		$exception->setExceptionCode("No Content");
    		throw $exception;
    	}
    	else
    	{
    		$exception=new ZCRMException(self::getMessage());
    		$exception->setExceptionCode(self::getCode());
    		throw $exception;
    	}*/
    }

}
?>