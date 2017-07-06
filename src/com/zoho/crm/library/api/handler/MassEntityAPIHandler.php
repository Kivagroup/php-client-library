<?php
require_once realpath(dirname(__FILE__).'/../../crud/ZCRMRecord.php');
require_once 'EntityAPIHandler.php';
require_once 'APIHandler.php';

class MassEntityAPIHandler extends APIHandler
{
	private $module=null;
	
	public function __construct($moduleInstance)
	{
		$this->module=$moduleInstance;
	}
	
	public static function getInstance($moduleInstance)
	{
		return new MassEntityAPIHandler($moduleInstance);
	}
	public function createRecords($records)
	{
		if(sizeof($records) > 100)
		{
			throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG,APIConstants::RESPONSECODE_BAD_REQUEST);
		}
		try{
			$this->urlPath=$this->module->getAPIName();
			$this->requestMethod=APIConstants::REQUEST_METHOD_POST;
			$this->addHeader("Content-Type","application/json");
			$requestBodyObj = array();
			$dataArray = array();
			foreach ($records as $record)
			{
				if($record->getEntityId()==null)
				{
					array_push($dataArray,EntityAPIHandler::getInstance($record)->getZCRMRecordAsJSON());
				}
				else
				{
					throw new ZCRMException("Entity ID MUST be null for create operation.",APIConstants::RESPONSECODE_BAD_REQUEST);
				}
			}
			$requestBodyObj["data"]=$dataArray;
			$this->requestBody = $requestBodyObj;
			
			//Fire Request
			$bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
			$createdRecords=array();
			$responses=$bulkAPIResponse->getEntityResponses();
			$size=sizeof($responses);
			for($i=0;$i<$size;$i++)
			{
				$entityResIns=$responses[$i];
				if(APIConstants::CODE_SUCCESS===$entityResIns->getStatus())
				{
					$responseData = $entityResIns->getResponseJSON();
					$recordDetails = $responseData["details"];
					$newRecord = $records[$i];
					EntityAPIHandler::getInstance($newRecord)->setRecordProperties($recordDetails);
					array_push($createdRecords,$newRecord);
					$entityResIns->setData($newRecord);
				}
				else
				{
					$entityResIns->setData(null);
				}
			}
			$bulkAPIResponse->setData($createdRecords);
			return $bulkAPIResponse;
		}catch (ZCRMException $e){
			throw $e;
		}
	}
	
	public function deleteRecords($entityIds)
	{
		if(sizeof($entityIds) > 100)
		{
			throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG,APIConstants::RESPONSECODE_BAD_REQUEST);
		}
		try
		{
			$this->urlPath=$this->module->getAPIName();
			$this->requestMethod=APIConstants::REQUEST_METHOD_DELETE;
			$this->addHeader("Content-Type","application/json");
			$this->addParam("ids", implode(",", $entityIds));//converts array to string with specified seperator
			
			//Fire Request
			$bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
			$responses=$bulkAPIResponse->getEntityResponses();
				
			foreach ($responses as $entityResIns)
			{
				$responseData = $entityResIns->getResponseJSON();
				$responseJSON = $responseData["details"];
				$record = ZCRMRecord::getInstance($this->module->getAPIName(), $responseJSON["id"]+0);
				$entityResIns->setData($record);
			}
			return $bulkAPIResponse;
		}catch(ZCRMException $exception)
		{
			APIExceptionHandler::logException($exception);
			throw $exception;
		}
	}
			
	public function getRecords($cvId, $sortByField, $sortOrder, $page,$perPage)
	{
		try{
			$this->urlPath=$this->module->getAPIName();
			$this->requestMethod=APIConstants::REQUEST_METHOD_GET;
			$this->addHeader("Content-Type","application/json");
			if($cvId!=null)
			{
				$this->addParam("cvid",$cvId+0);
			}
			if($sortByField!=null)
			{
				$this->addParam("sort_by",$sortByField);
			}
			if($sortOrder!=null)
			{
				$this->addParam("sort_order",$sortOrder);
			}
			$this->addParam("page",$page+0);
			$this->addParam("per_page",$perPage+0);
			
			$responseInstance=APIRequest::getInstance($this)->getBulkAPIResponse();
			$responseJSON=$responseInstance->getResponseJSON();
			$records=$responseJSON["data"];
			$recordsList=array();
			foreach ($records as $record)
			{
				$recordInstance = ZCRMRecord::getInstance($this->module->getAPIName(), $record["id"]+0);
				EntityAPIHandler::getInstance($recordInstance)->setRecordProperties($record);
				array_push($recordsList,$recordInstance);
			}
				
			$responseInstance->setData($recordsList);
			
			return $responseInstance;
		}catch (ZCRMException $exception)
		{
			APIExceptionHandler::logException($exception);
			throw $exception;
		}
	}
	
	public function searchRecords($searchWord,$page,$perPage)
	{
		try{
			$this->urlPath=$this->module->getAPIName()."/search";
			$this->requestMethod=APIConstants::REQUEST_METHOD_GET;
			$this->addHeader("Content-Type","application/json");
			$this->addParam("word",$searchWord);
			$this->addParam("page",$page+0);
			$this->addParam("per_page",$perPage+0);
			$responseInstance=APIRequest::getInstance($this)->getBulkAPIResponse();
			$responseJSON=$responseInstance->getResponseJSON();
			$records=$responseJSON["data"];
			$recordsList=array();
			foreach ($records as $record)
			{
				$recordInstance = ZCRMRecord::getInstance($this->module->getAPIName(), $record["id"]+0);
				EntityAPIHandler::getInstance($recordInstance)->setRecordProperties($record);
				array_push($recordsList,$recordInstance);
			}
		
			$responseInstance->setData($recordsList);
				
			return $responseInstance;
		}catch (ZCRMException $exception)
		{
			APIExceptionHandler::logException($exception);
			throw $exception;
		}
	}
	
	public function updateRecords($idList,$apiName,$value)
	{
		if(sizeof($idList)>100)
		{
			throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG,APIConstants::RESPONSECODE_BAD_REQUEST);
		}
		try{
			$inputJSON=self::constructJSONForMassUpdate($idList,$apiName,$value);
			$this->urlPath=$this->module->getAPIName();
			$this->requestMethod=APIConstants::REQUEST_METHOD_PUT;
			$this->addHeader("Content-Type","application/json");
			$this->requestBody=$inputJSON;
			$this->apiKey='data';
			$bulkAPIResponse=APIRequest::getInstance($this)->getBulkAPIResponse();
			
			$updatedRecords=array();
			$responses=$bulkAPIResponse->getEntityResponses();
			$size=sizeof($responses);
			for($i=0;$i<$size;$i++)
			{
				$entityResIns=$responses[$i];
				if(APIConstants::CODE_SUCCESS===$entityResIns->getStatus())
				{
					$responseData = $entityResIns->getResponseJSON();
					$recordJSON = $responseData["details"];
					
					$updatedRecord = ZCRMRecord::getInstance($this->module->getAPIName(), $recordJSON["id"]+0);
					EntityAPIHandler::getInstance($updatedRecord)->setRecordProperties($recordJSON);
					array_push($updatedRecords,$updatedRecord);
					$entityResIns->setData($updatedRecord);
				}
				else
				{
					$entityResIns->setData(null);
				}
			}
			$bulkAPIResponse->setData($updatedRecords);
				
			return $bulkAPIResponse;
		}catch (ZCRMException $exception)
		{
			APIExceptionHandler::logException($exception);
			throw $exception;
		}
	}
	
	public function constructJSONForMassUpdate($idList,$apiName,$value)
	{
		$massUpdateArray=array();
		foreach ($idList as $id)
		{
			$updateJson=array();
			$updateJson["id"]="".$id;
			$updateJson[$apiName]=$value;
			array_push($massUpdateArray,$updateJson);
		}
		
		return json_encode(array("data"=>$massUpdateArray));
	}
}
?>