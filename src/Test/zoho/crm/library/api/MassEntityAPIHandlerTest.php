<?php
require_once realpath(dirname(__FILE__)."/../../../../../com/zoho/crm/library/api/handler/MassEntityAPIHandler.php");
require_once 'MetaDataAPIHandlerTest.php';
require_once realpath(dirname(__FILE__)."/../common/TestUtil.php");

class MassEntityAPIHandlerTest
{
	public static $filePointer=null;
	public static $productId=null;
	public static $firstParnetId=null;
	public static $firstParnetModule=null;
	public static $moduleApiNameVsEntityIds=array();
	private static $createLimit=5;
	public static function test($fp)
	{
		self::$filePointer=$fp;
		self::testCreateRecords();
		self::testUpdateRecords();
		self::testGetRecords();
		self::testDeleteRecords();
	}
	public function testCreateRecords()
	{
		if(sizeof(MetaDataAPIHandlerTest::$moduleList)<=0)
		{
			throw new ZCRMException("No Modules fetched..");
		}
		
		$moduleList=TestUtil::moveModulePositions(true,array("Products"),MetaDataAPIHandlerTest::$moduleList);
		foreach ($moduleList as $apiName=>$moduleName)
		{
			$startTime=microtime(true)*1000;
			$endTime=0;
			try{
				if(in_array($moduleName, TestUtil::$nonSupportiveModules))
				{
					continue;
				}
				else if($moduleName=='Attachments' || $moduleName=='Notes')
				{
					continue;
				}
				self::setRecordFieldsAndValidate($apiName,$startTime,$endTime);
			}catch (ZCRMException $e)
			{
				$endTime=$endTime==0?microtime(true)*1000:$endTime;
				Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRecord','create',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
			}
		}
	}
	
	public function testUpdateRecords()
	{
		if(sizeof(MetaDataAPIHandlerTest::$moduleList)<=0)
		{
			throw new ZCRMException("No Modules fetched..");
		}
		try{
			foreach (MetaDataAPIHandlerTest::$moduleList as $apiName=>$moduleName)
			{
				$startTime=microtime(true)*1000;
				$endTime=0;
				try{
					if(in_array($moduleName, TestUtil::$nonSupportiveModules))
					{
						continue;
					}
					else if($moduleName=='Attachments' || $moduleName=='Notes')
					{
						continue;
					}
					self::updateRecordFieldsAndValidate($apiName,$startTime,$endTime);
				}catch (ZCRMException $e)
				{
					$endTime=$endTime==0?microtime(true)*1000:$endTime;
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRecord','update',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
				}
			}
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRecord','update',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
		}
	}
	
	public function testGetRecords()
	{
		if(sizeof(MetaDataAPIHandlerTest::$moduleList)<=0)
		{
			throw new ZCRMException("No Modules fetched..");
		}
		try{
			foreach (MetaDataAPIHandlerTest::$moduleList as $apiName=>$moduleName)
			{
				$startTime=microtime(true)*1000;
				$endTime=0;
				try{
					if(in_array($moduleName, TestUtil::$nonSupportiveModules))
					{
						continue;
					}
					Main::incrementTotalCount();
					self::validateGetRecordsResponse($apiName,$moduleName,$startTime,$endTime);
				}catch (ZCRMException $e)
				{
					$endTime=$endTime==0?microtime(true)*1000:$endTime;
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$apiName.')','testGetRecord',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
				}
					
			}
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$apiName.')','testGetRecord',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
		}
	}
	
	public function testDeleteRecords()
	{

		if(sizeof(MetaDataAPIHandlerTest::$moduleList)<=0)
		{
			throw new ZCRMException("No Modules fetched..");
		}
		try{
			$moduleList=TestUtil::moveModulePositions(true,array("Attachments","Notes"),MetaDataAPIHandlerTest::$moduleList);
			$moduleList=TestUtil::moveModulePositions(false,array("Products"),$moduleList);
			foreach ($moduleList as $apiName=>$moduleName)
			{
				$startTime=microtime(true)*1000;
				$endTime=0;
				try{
					if(in_array($moduleName, TestUtil::$nonSupportiveModules))
					{
						continue;
					}
					if($moduleName=='Attachments' || $moduleName=='Notes')
					{
						continue;
					}
					Main::incrementTotalCount();
					self::validateDeleteRecordsResponse($apiName,$startTime,$endTime);
				}catch (ZCRMException $e)
				{
					$endTime=$endTime==0?microtime(true)*1000:$endTime;
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$apiName.')','testDeleteRecord',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
				}
					
			}
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$apiName.')','testDeleteRecord',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
		}
	}
	
	public function validateDeleteRecordsResponse($moduleAPIName,$startTime,$endTime)
	{
		try{
			if(self::$moduleApiNameVsEntityIds[$moduleAPIName]==null)
			{
				$endTime=$endTime==0?microtime(true)*1000:$endTime;
				Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateGetRecordResponse',"Unable to process","Record creation failed so get can't be done",'failure',($endTime-$startTime));
			}
			$startTime=microtime(true)*1000;
			$moduleIns=ZCRMModule::getInstance($moduleAPIName);
			$bulkResponseIns=$moduleIns->deleteRecords(self::$moduleApiNameVsEntityIds[$moduleAPIName]);
			$endTime=microtime(true)*1000;
			$entityResponses=$bulkResponseIns->getEntityResponses();
			if($bulkResponseIns->getHttpStatusCode()!=APIConstants::RESPONSECODE_OK)
			{
				Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateDeleteRecordsResponse',"Record deletion Failed",$bulkResponseIns->getMessage(),'failure',($endTime-$startTime));
				return;
			}
			$deletedIdList=array();
			foreach ($entityResponses as $entityResponseIns)
			{
				if(APIConstants::CODE_SUCCESS!=$entityResponseIns->getStatus() || "record deleted"!=$entityResponseIns->getMessage())
				{
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateDeleteRecordsResponse',"Record deletion Failed",json_encode($entityResponseIns->getResponseJSON()),'failure',($endTime-$startTime));
				}
				array_push($deletedIdList,$entityResponseIns->getData()->getEntityId());
			}
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMModule('.$moduleAPIName.")",'deleteRecords()',"Records deleted Successfully","ID List:".json_encode($deletedIdList),'success',($endTime-$startTime));
			unset($deletedIdList);
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateDeleteRecordResponse',$e->getMessage().",id:".self::$moduleApiNameVsEntityIds[$moduleAPIName],$e->getTraceAsString(),'failure',($endTime-$startTime));
		}
	}
	
	public function validateGetRecordsResponse($moduleAPIName,$moduleName,$startTime,$endTime)
	{
		try {
			$moduleIns=ZCRMModule::getInstance($moduleAPIName);
			$bulkResponseIns=$moduleIns->getRecords();
			$endTime=microtime(true)*1000;
			$fetchCount=-1;
			if($moduleName=="Notes" || $moduleName=='Attachments')
			{
				if($bulkResponseIns->getHttpStatusCode()!=APIConstants::RESPONSECODE_OK && $bulkResponseIns->getHttpStatusCode()!=APIConstants::RESPONSECODE_NO_CONTENT)
				{
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateGetRecordsResponse',"Record Get failed",$bulkResponseIns->getMessage(),'failure',($endTime-$startTime));
					return;
				}
				$records=$bulkResponseIns->getData();
				$fetchCount=sizeof($records);
			}
			else 
			{
				if($bulkResponseIns->getHttpStatusCode()!=APIConstants::RESPONSECODE_OK )
				{
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateGetRecordsResponse',"Record Get failed",$bulkResponseIns->getMessage(),'failure',($endTime-$startTime));
					return;
				}
				$records=$bulkResponseIns->getData();
				$getCount=sizeof($records);
				$insertCount=sizeof(self::$moduleApiNameVsEntityIds[$moduleAPIName]);
				if($getCount<$insertCount)
				{
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateGetRecordsResponse',"Record Get failed","Insert and Get records count mismatched;;insertCount=".$insertCount.",getCount=".$getCount,'failure',($endTime-$startTime));
					return;
				}
				$fetchCount=$getCount;
			}
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMModule('.$moduleAPIName.")",'getRecords()',"Bulk Records fetched successfully","Fetch Count::".$fetchCount,'success',($endTime-$startTime));
			unset($fetchedIds);
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateGetRecordResponse',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
		}
	}
	
	public function updateRecordFieldsAndValidate($moduleAPIName,$startTime,$endTime)
	{
		try
		{
			$moduleFields=MetaDataAPIHandlerTest::$moduleVsFieldMap[$moduleAPIName];
			$layoutIds = array_keys( MetaDataAPIHandlerTest::$moduleVsLayoutMap[$moduleAPIName]);
			$moduleName=MetaDataAPIHandlerTest::$moduleList[$moduleAPIName];
			foreach ($layoutIds as $layoutId)
			{
				Main::incrementTotalCount();
				if(self::$moduleApiNameVsEntityIds[$moduleAPIName]==null)
				{
					$endTime=$endTime==0?microtime(true)*1000:$endTime;
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'updateRecordFieldsAndValidate',"Unable to process","Record creation failed so update can't be done",'failure',($endTime-$startTime));
				}
				$layoutFields=MetaDataAPIHandlerTest::$moduleVsLayoutMap[$moduleAPIName][$layoutId];
				$fieldAPINameForUpdate=null;
				foreach ($moduleFields as $fieldAPIName=>$fieldDetails)
				{
					$inputData=array();
					if(!in_array($fieldAPIName, $layoutFields))
					{
						continue;
					}
					$fieldLabel=$fieldDetails['field_label'];
					$dataType=$fieldDetails['data_type'];
					if($dataType=='text')
					{
						$fieldAPINameForUpdate=$fieldAPIName;
						break;
					}
				}
				$isValid=self::validateUpdateResponse($fieldAPINameForUpdate,"Updated Text",$moduleAPIName, $startTime, $endTime);
				if($isValid)
				{
					$endTime=$endTime==0?microtime(true)*1000:$endTime;
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),"ZCRMModule(".$moduleAPIName.")",'updateRecords()',"Records Updated Successfully","EntityId List::".json_encode(self::$moduleApiNameVsEntityIds[$moduleAPIName]),'success',($endTime-$startTime));
				}
			}
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'updateRecordFields',$e->getMessage().",id=".self::$moduleApiNameVsEntityIds[$moduleAPIName],$e->getTraceAsString(),'failure',($endTime-$startTime));
		}
	}
	
	public function validateUpdateResponse($fieldAPIName,$fieldValue,$moduleAPIName,$startTime,$endTime)
	{
		try{
			$moduleIns=ZCRMModule::getInstance($moduleAPIName);
			$bulkResponseIns=$moduleIns->updateRecords(self::$moduleApiNameVsEntityIds[$moduleAPIName], $fieldAPIName,$fieldValue);
			$endTime=microtime(true)*1000;
			$updatedRecords=$bulkResponseIns->getData();
			$entityResponses=$bulkResponseIns->getEntityResponses();
			foreach ($entityResponses as $entityResponseIns)
			{
				if(APIConstants::CODE_SUCCESS!=$entityResponseIns->getStatus() || "record updated"!=$entityResponseIns->getMessage())
				{
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateUpdateResponse',"Record update Failed",json_encode($entityResponseIns->getResponseJSON()),'failure',($endTime-$startTime));
				}
			}
			if($bulkResponseIns->getHttpStatusCode()!=APIConstants::RESPONSECODE_OK)
			{
				return false;
			}
			
			return true;
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateUpdateResponse',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
			return false;
		}
	}
	
	public function setRecordFieldsAndValidate($moduleAPIName,$startTime,$endTime)
	{
		try{
			$moduleFields=MetaDataAPIHandlerTest::$moduleVsFieldMap[$moduleAPIName];
			$layoutIds = array_keys( MetaDataAPIHandlerTest::$moduleVsLayoutMap[$moduleAPIName]);
			$moduleName=MetaDataAPIHandlerTest::$moduleList[$moduleAPIName];
			foreach ($layoutIds as $layoutId)
			{
				$recordsArray=array();
				Main::incrementTotalCount();
				for($index=0;$index<self::$createLimit;$index++)
				{
					$zcrmrecord=ZCRMRecord::getInstance($moduleAPIName, null);
					$layoutFields=MetaDataAPIHandlerTest::$moduleVsLayoutMap[$moduleAPIName][$layoutId];
					foreach ($moduleFields as $fieldAPIName=>$fieldDetails)
					{
						$inputData=array();
						if(!in_array($fieldAPIName, $layoutFields))
						{
							continue;
						}
						if($fieldAPIName=='Layout')
						{
							$zcrmrecord->setFieldValue("Layout",$layoutId);
							continue;
						}
						$fieldLabel=$fieldDetails['field_label'];
						$dataType=$fieldDetails['data_type'];
						if($dataType=='lookup' || ($fieldLabel=='Repeat' && $moduleName=='Events'))
						{
							continue;
						}
						elseif($dataType=='picklist')
						{
							$pickListValues=$fieldDetails['pick_list_values'];
							if(sizeof($pickListValues)>1)
							{
								$zcrmrecord->setFieldValue($fieldAPIName,$pickListValues[1]->getDisplayValue());
							}
							else
							{
								$zcrmrecord->setFieldValue($fieldAPIName,$pickListValues[0]->getDisplayValue());
							}
						}
						elseif($dataType=='currency')
						{
							$length=$fieldDetails['length']+0;
							$currencyVal="";
							for($i=0;$i<$length;$i++)
							{
							$currencyVal=$currencyVal.($i+1)%10;
							}
							$currencyVal=str_replace(".", "", $currencyVal);
							$currencyVal=str_replace("e+", "", $currencyVal);
									$zcrmrecord->setFieldValue($fieldAPIName,$currencyVal);
						}
						elseif($dataType=='double')
						{
							$val=TestUtil::$dataTypeVsValue[$dataType];
							$length=$fieldDetails['length']+0;
							if($length<strlen($val))
							{
								$val=substr($val, 0,$length);
							}
							$zcrmrecord->setFieldValue($fieldAPIName,$val+0);
						}
						elseif ($dataType=='text')
						{
							$val=$fieldLabel.rand(1,19);
							$zcrmrecord->setFieldValue($fieldAPIName,$val);
						}
						elseif($dataType=='datetime')
						{
							$dateTime=TestUtil::getDateTimeISO();
							$zcrmrecord->setFieldValue($fieldAPIName,$dateTime);
						}
						else
						{
							$length=$fieldDetails['length']+0;
							$value=isset(TestUtil::$dataTypeVsValue[$dataType])?TestUtil::$dataTypeVsValue[$dataType]:null;
							if($length<strlen($value))
							{
								$value=substr($value, 0,$length);
							}
							$zcrmrecord->setFieldValue($fieldAPIName,$value);
						}
								
						if($moduleName=='PriceBooks' && $fieldLabel=='Pricing Details')
						{
							$pricingDetails=array("from_range"=>1,"to_range"=>100,"discount"=>5);
							$zcrmrecord->setFieldValue($fieldAPIName,array($pricingDetails));
						}
						elseif($fieldLabel=='Product Details' && ($moduleName=='Quotes' || $moduleName=='SalesOrders' || $moduleName=='PurchaseOrders'|| $moduleName='Invoices'))
						{
							$productObj=array("id"=>self::$productId);
							$productDetailsObj=array("product"=>$productObj,"quantity"=>150);
							$zcrmrecord->setFieldValue($fieldAPIName,array($productDetailsObj));
						}
						elseif($fieldLabel=='Call Duration' && $moduleName=='Calls')
						{
							$zcrmrecord->setFieldValue($fieldAPIName,"10");
						}
						elseif($fieldLabel=='Participants' && $moduleName=='Events')
						{
							$participantObj=array("type"=>"user","participant"=>UserAPIHandlerTest::$userIdList[0]);
							$zcrmrecord->setFieldValue($fieldAPIName,array($participantObj));
						}
					}
					array_push($recordsArray, $zcrmrecord);
					unset($zcrmrecord);
				}
				$isValid=self::validateCreateResponse($recordsArray, $moduleAPIName, $startTime, $endTime);
				if($isValid)
				{
					$endTime=$endTime==0?microtime(true)*1000:$endTime;
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMModule('.$moduleAPIName.")",'createRecords',"Records Created Successfully","Entity Id List::".json_encode(self::$moduleApiNameVsEntityIds[$moduleAPIName]),'success',($endTime-$startTime));
				}
				unset($recordsArray);
			}
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'setRecordFields',$e->getMessage().",id=".self::$moduleApiNameVsEntityIds[$moduleAPIName],$e->getTraceAsString(),'failure',($endTime-$startTime));
		}
	}
	
	public function validateCreateResponse($recordsArray,$moduleAPIName,$startTime,$endTime)
	{
		try{
			$moduleIns=ZCRMModule::getInstance($moduleAPIName);
			$bulkResponseIns=$moduleIns->createRecords($recordsArray);
			$endTime=microtime(true)*1000;
			$createdRecords=$bulkResponseIns->getData();
			$entityResponses=$bulkResponseIns->getEntityResponses();
			$creationSuccess=true;
			foreach ($entityResponses as $entityResponseIns)
			{
				if(APIConstants::CODE_SUCCESS!=$entityResponseIns->getStatus() || "record added"!=$entityResponseIns->getMessage())
				{
					$creationSuccess=false;
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateCreateResponse',"Record Creation Failed",json_encode($entityResponseIns->getResponseJSON()),'failure',($endTime-$startTime));
					continue;
				}
			}
			$successIds=array();
			foreach ($createdRecords as $record)
			{
				array_push($successIds,$record->getEntityId());
			}
			if(MetaDataAPIHandlerTest::$moduleList[$moduleAPIName]=='Products')
			{
				self::$productId=$successIds[0];
			}
			self::$moduleApiNameVsEntityIds[$moduleAPIName]=$successIds;
			return $creationSuccess;
		}
		catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'MassEntityAPIHandlerTest('.$moduleAPIName.")",'validateCreateResponse',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
			return false;
		}
	}
	
}
?>