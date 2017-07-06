<?php
require_once realpath(dirname(__FILE__)."/../common/Helper.php");
require_once realpath(dirname(__FILE__)."/../Main.php");
require_once realpath(dirname(__FILE__)."/../../../../../com/zoho/crm/library/common/APIConstants.php");

class MetaDataAPIHandlerTest
{
	public static $moduleList=array();
	public static $moduleNameVsApiName=array();
	public static $moduleNameList=array();
	private static $filePointer=null;
	private $currentModule=null;
	public static $moduleVsFieldMap=array();
	public static $moduleVsLayoutMap=array();
	public static function test($fp)
	{
		$ins=new MetaDataAPIHandlerTest();
		self::$filePointer=$fp;
		$ins->testGetAllModules();
		$ins->testGetModule();
	}
	
	public function testGetAllModules()
	{
		try{
			Main::incrementTotalCount();
			$startTime=microtime(true)*1000;
			$endTime=0;
			$instance=ZCRMRestClient::getInstance();
			$moduleArr=$instance->getAllModules()->getData();
			$endTime=microtime(true)*1000;
			if($moduleArr==null || sizeof($moduleArr)<=0)
			{
				throw new ZCRMException("No Modules Received");
			}
			foreach ($moduleArr as $module)
			{
				if($module->getId()==null || $module->getModuleName()==null || $module->getAPIName()==null ||$module->getSingularLabel()==null ||$module->getPluralLabel()==null)
				{
					throw new ZCRMException("Some fields data is not fetched");
				}
				if($module->isApiSupported())
				{
					self::$moduleList[$module->getAPIName()]=$module->getModuleName();
					array_push(self::$moduleNameList,$module->getModuleName());
					self::$moduleNameVsApiName[$module->getModuleName()]=$module->getAPIName();
				}
			}
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRestClient','getAllModules',null,null,'success',($endTime-$startTime));
			
		}catch (ZCRMException $e)
		{
			$endTime=$endTime==0?microtime(true)*1000:$endTime;
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRestClient','getAllModules',$e->getMessage(),$e->getExceptionDetails(),'failure',($endTime-$startTime));
		}
		
	}
	
	public function testGetModule()
	{
		if(!sizeof(self::$moduleList)>0)
		{
			Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRestClient','getModule','Invalid Response','Module List is null or empty','failure',0);
			return;
		}
		foreach(self::$moduleList as $apiName=>$moduleName)
		{
			try{
				self::setCurrentModule($apiName);
				Main::incrementTotalCount();
				$startTime=microtime(true)*1000;
				$endTime=0;
				$instance=ZCRMRestClient::getInstance();
				$moduleResponse=$instance->getModule($apiName);
				$zcrmModule=$moduleResponse->getData();
				$endTime=microtime(true)*1000;
				if($moduleResponse==null || $moduleResponse->getHttpStatusCode()!=APIConstants::RESPONSECODE_OK || $zcrmModule==null)
				{
					Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRestClient','getModule','Invalid Response','Response status code is not as expected('.$moduleResponse->getHttpStatusCode().')','failure',($endTime-$startTime));
					continue;
				}
				if(!$zcrmModule->isCreatable())
				{
					continue;
				}
				$rawFieldArray=$zcrmModule->getFields();
				$fieldArray=array();
				foreach ($rawFieldArray as $field)
				{
					$fieldDetails=array();
					$dataType=$field->getDataType();
					$fieldPermissions=$field->getFieldLayoutPermissions();
					if('autonumber'==$dataType || !in_array('CREATE', $fieldPermissions) || $field->isReadOnly())
					{
						continue;
					}
					$fieldDetails['data_type']=$dataType;
					if($dataType=='lookup')
					{
						$fieldDetails['lookup']=$field->getLookupField();
					}
					else if($dataType=='picklist')
					{
						$fieldDetails['pick_list_values']=$field->getPickListFieldValues();
					}
					else if($dataType=='currency')
					{
						$fieldDetails['precision']=$field->getPrecision();
						$fieldDetails['rounding_option']=$field->getRoundingOption();
					}
					$fieldDetails['decimal_place']=$field->getDecimalPlace();
					$fieldDetails['length']=$field->getLength()+0;
					$fieldDetails['field_label']=$field->getFieldLabel();
					$fieldArray[$field->getApiName()]=$fieldDetails;
				}
				self::$moduleVsFieldMap[$apiName]=$fieldArray;
				if($zcrmModule->getLayouts()!=null)
				{
					$layouts=$zcrmModule->getLayouts();
					$layoutVsFields=array();
					foreach ($layouts as $layout)
					{
						$sections=$layout->getSections();
						if($sections==null)
						{
							continue;
						}
						$layoutFieldArray=array();
						foreach ($sections as $section)
						{
							$fields=$section->getFields();
							if($fields==null)
							{
								continue;
							}
							foreach ($fields as $field)
							{
								array_push($layoutFieldArray, $field->getApiName());
							}
						}
						$layoutVsFields[$layout->getId()]=$layoutFieldArray;
					}
					self::$moduleVsLayoutMap[$apiName]=$layoutVsFields;
				}
				Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRestClient','getModule('.$apiName.')',null,null,'success',($endTime-$startTime));
			}catch (ZCRMException $e)
			{
				$endTime=$endTime==0?microtime(true)*1000:$endTime;
				Helper::writeToFile(self::$filePointer,Main::getCurrentCount(),'ZCRMRestClient','getModule('.$apiName.')',$e->getMessage(),$e->getTraceAsString(),'failure',($endTime-$startTime));
			}
		}
	}

    /**
     * currentModule
     * @return String
     */
    public function getCurrentModule(){
        return $this->currentModule;
    }

    /**
     * currentModule
     * @param String $currentModule
     */
    public function setCurrentModule($currentModule){
        $this->currentModule = $currentModule;
    }

}
?>