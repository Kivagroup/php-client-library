<?php
require_once 'ZCRMRestClient.php';
require_once '../../crud/ZCRMModule.php';
require_once '../../crud/ZCRMTax.php';
require_once '../../crud/ZCRMRecord.php';
require_once '../../crud/ZCRMInventoryLineItem.php';
require_once '../../crud/ZCRMRelatedListProperties.php';
require_once '../../crud/ZCRMCustomView.php';
require_once '../../setup/metadata/ZCRMOrganization.php';
require_once '../../setup/users/ZCRMUser.php';
require_once '../../setup/users/ZCRMProfile.php';
require_once '../../setup/users/ZCRMRole.php';
require_once '../../exception/Logger.php';

ZCRMRestClient::initialize();

/*
 * Code to generate refresh and access token from grant token 
 */
/*
$oAuthCli = ZohoOAuth::getClientInstance();
$grantToken = "1000.3ce98554115d63dc1532cebe19c0db20.a2dbb8cf38bdb8d3bd7ec7ef85a8f76f";
$oAuthTokens = $oAuthCli->generateAccessToken($grantToken);
$accessToken = $oAuthTokens->getAccessToken();
$refreshToken=$oAuthTokens->getRefreshToken();
*/

/*
 * Code to delete note
 */
/*
 try{
 $record=ZCRMRecord::getInstance("Leads",410405000001111007);
 $noteIns=ZCRMNote::getInstance($record,410405000001142033);
 //$noteIns->setTitle("Title_API1");
 //$noteIns->setContent("This is test content\n second line<b>here</b>");

 $response=$record->deleteNote($noteIns);
 var_dump($response->getData());
 var_dump($response->getResponseJSON());
 }
 catch (ZCRMException $e)
 {
 echo "in\n";
 echo $e->getMessage()."\n";
 echo $e->getExceptionCode()."\n";
 echo $e->getCode()."\n";
 }
 */

/*
 * Code to update note
 */
/*
 try{
 $record=ZCRMRecord::getInstance("Leads",410405000001111007);
 $noteIns=ZCRMNote::getInstance($record,410405000001143017);
 $noteIns->setTitle("Title_API22..");
 $noteIns->setContent("This is test content\n second line<b>here</b>");

 $response=$record->updateNote($noteIns);
 var_dump($response->getData());
 var_dump($response->getResponseJSON());
 }
 catch (ZCRMException $e)
 {
 echo "in\n";
 echo $e->getMessage()."\n";
 echo $e->getExceptionCode()."\n";
 }
 */

/*
 * Code to add note
 */

try{
	for ($i=0;$i<2;$i++)
	{
		$record=ZCRMRecord::getInstance("Leads",410405000001111007);
		$noteIns=ZCRMNote::getInstance($record);
		$noteIns->setTitle("Title_API".$i);
		$noteIns->setContent("This is test content".$i);
		
		$response=$record->addNote($noteIns);
		var_dump($response->getData());
		var_dump($response->getResponseJSON());
	}
	
}
catch (ZCRMException $e)
{
	echo "in\n";
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
}


/*
 * Code for download Attachment
 */
/*
try{
	$record=ZCRMRecord::getInstance("Leads",410405000001111007);
	$fileResponseIns=$record->downloadAttachment(410405000001153003);
	$fp=fopen("/Users/sumanth-3058/".$fileResponseIns->getFileName(),"w");
	$stream=$fileResponseIns->getFileContent();
	fputs($fp,$stream);
	fclose($fp);
	//file_put_contents("/Users/sumanth-3058/".$fileResponseIns->getFileName(),$fileResponseIns->getFileAsStream());
}catch (ZCRMException $e)
{
	echo "in\n";
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
	echo $e->getCode()."\n";
}
*/
/*
 * Code for upload Attachment
 */
/*
 try{
 	$record=ZCRMRecord::getInstance("Leads",410405000001076001);
 	$responseIns=$record->uploadAttachment('/Users/sumanth-3058/Downloads/androidfiletransfer.dmg');
 	$attchmentIns=$responseIns->getData();
 	echo $attchmentIns->getId().",";
 	var_dump($responseIns->getResponseJSON());
 }catch (ZCRMException $e)
 {
 echo "in\n";
 echo $e->getMessage()."\n";
 echo $e->getExceptionCode()."\n";
 echo $e->getCode()."\n";
 }
 */

/*
 * Code for get Attachments of a module
 */
/*
try{
	$record=ZCRMRecord::getInstance("Leads",410405000001111007);
	$responseIns=$record->getAttachments();
	$bulkResponse=$responseIns->getData();
	foreach ($bulkResponse as $attchmentIns)
			{
				echo "\n";
				echo $attchmentIns->getId().",";
				echo $attchmentIns->getFileName().",";
				echo $attchmentIns->getFileType().",";
				echo $attchmentIns->getSize().",";
				echo $attchmentIns->getParentModule().",";
				$parentRecord=$attchmentIns->getParentRecord();
				echo $parentRecord->getEntityId().",";
				echo $attchmentIns->getParentName().",";
				echo $attchmentIns->getParentId().",";

				$createdBy=$attchmentIns->getCreatedBy();
				echo $createdBy->getId().",";
				echo $createdBy->getName().",";

				$modifiedBy=$attchmentIns->getModifiedBy();
				echo $modifiedBy->getId().",";
				echo $modifiedBy->getName().",";

				$owner=$attchmentIns->getOwner();
				echo $owner->getId().",";
				echo $owner->getName().",";

				echo $attchmentIns->getCreatedTime().",";
				echo $attchmentIns->getModifiedTime().",";
			}
	}catch (ZCRMException $e)
{
	echo "in\n";
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
	echo $e->getCode()."\n";
}
*/


/*
 * Code for get Notes of a module
 */
/*
try{
	$record=ZCRMRecord::getInstance("Leads",410405000001111007);
	$responseIns=$record->getNotes(1,5);
	$bulkResponse=$responseIns->getData();
	var_dump($responseIns->getInfo());
	foreach ($bulkResponse as $note)
	{
		echo "\n";
		echo $note->getId().",";
		echo $note->getTitle().",";
		echo $note->getContent().",";
		
		$parentRecord=$note->getParentRecord();
		echo $parentRecord->getEntityId().",";
		
		echo $note->getParentName().",";
		echo $note->getParentId().",";
		
		
		$createdBy=$note->getCreatedBy();
		echo $createdBy->getId().",";
		echo $createdBy->getName().",";
		
		$modifiedBy=$note->getModifiedBy();
		echo $modifiedBy->getId().",";
		echo $modifiedBy->getName().",";
		
		$owner=$note->getOwner();
		echo $owner->getId().",";
		echo $owner->getName().",";
		
		echo $note->getCreatedTime().",";
		echo $note->getModifiedTime().",";
		echo $note->isVoiceNote().",";
		echo $note->getSize().",";
		
		$attchments=$note->getAttachments();
		if($attchments!=null)
		{
			foreach ($attchments as $attchmentIns)
			{
				echo "\nATTACHMENTS::\n";
				echo $attchmentIns->getId().",";
				echo $attchmentIns->getFileName().",";
				echo $attchmentIns->getFileType().",";
				echo $attchmentIns->getSize().",";
				echo $attchmentIns->getParentModule().",";
				$parentRecord=$attchmentIns->getParentRecord();
				echo $parentRecord->getEntityId().",";
				
				echo $attchmentIns->getParentName().",";
				echo $attchmentIns->getParentId().",";
				
				$createdBy=$attchmentIns->getCreatedBy();
				echo $createdBy->getId().",";
				echo $createdBy->getName().",";
				
				$modifiedBy=$attchmentIns->getModifiedBy();
				echo $modifiedBy->getId().",";
				echo $modifiedBy->getName().",";
				
				$owner=$attchmentIns->getOwner();
				echo $owner->getId().",";
				echo $owner->getName().",";
				
				echo $attchmentIns->getCreatedTime().",";
				echo $attchmentIns->getModifiedTime().",";
			}
			echo "\nEND ATTACHMENTS\n";
		}


	}
}catch (ZCRMException $e)
{
	echo "in\n";
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
}
*/

/*
 * Code for getRelatedRecords
 */
/*
try{
	$record=ZCRMRecord::getInstance("Leads",410405000001111007);
	$responseIns=$record->getRelatedListRecords("Products");
	$bulkResponse=$responseIns->getData();
	foreach ($bulkResponse as $record)
	{
		echo "\n";
		echo $record->getEntityId().",";
		echo $record->getModuleApiName().",";
		echo $record->getLookupLabel().",";
		
		echo $record->getCreatedBy()->getId().",";
		echo $record->getModifiedBy()->getId().",";
		echo $record->getOwner()->getId().",";
		echo $record->getCreatedTime().",";
		echo $record->getModifiedTime().",";
		
		$map=$record->getData();
		foreach ($map as $key=>$value)
		{
			echo $key.":".$value.",";
		}
		
		$lineItems=$record->getLineItems();
		echo "::LINEITEMS::";
		foreach ($lineItems as $lineItem)
		{
			echo $lineItem->getId().",";
			echo $lineItem->getListPrice().",";
			echo $lineItem->getQuantity().",";
			echo $lineItem->getDescription().",";
			echo $lineItem->getTotal().",";
			echo $lineItem->getDiscount().",";
			echo $lineItem->getDiscountPercentage().",";
			echo $lineItem->getTotalAfterDiscount().",";
			echo $lineItem->getTaxAmount().",";
			echo $lineItem->getNetTotal().",";
			echo $lineItem->getDeleteFlag().",";
			echo $lineItem->getProduct()->getEntityId().",";
			echo $lineItem->getProduct()->getLookupLabel().",";
			$linTaxs=$lineItem->getLineTax();
			echo "::lineTAXES::";
			foreach ($linTaxs as $lineTax)
			{
				echo $lineTax->getTaxName().",";
				echo $lineTax->getPercentage().",";
				echo $lineTax->getValue().",";
			}
		
		}
	}
}catch (ZCRMException $e)
{
	echo "in\n";
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
}
*/

/*
 * Code for deleteRecord
 */
/*
try{
	$record=ZCRMRecord::getInstance("Invoices",410405000001113018);
	$responseIns=$record->delete();
	var_dump($responseIns->getResponseJSON());
	echo "\n\n::";
	echo $responseIns->getCode().",";
	echo $responseIns->getMessage().",";
	echo $responseIns->getDetails().",";
	echo $responseIns->getStatus().",";
	echo $responseIns->getData().",";
	var_dump($responseIns->getData());
}catch (ZCRMException $e)
{
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
}
*/

/*
 * Code for deleting multiple records
 */
/*
 try{
 	$moduleIns=ZCRMModule::getInstance("Invoices");
 	$entityIds=array(410405000001164095,410405000001164067,410405000001164066,410405000001164065,410405000001164065,410405000001164063,410405000001164062);
 	$bulkResponse=$moduleIns->deleteRecords($entityIds);
 	$entityResponses=$bulkResponse->getEntityResponses();
 	foreach ($entityResponses as $entityResponse)
 	{
 		echo "\n\n";
 		echo "Status:".$entityResponse->getStatus().",";
 		echo "Message:".$entityResponse->getMessage().",";
 		echo "Code:".$entityResponse->getCode().",";
 		$recordIns=$entityResponse->getData();
 		echo "EntityID:".$recordIns->getEntityId().",";
 		echo "moduleAPIName:".$recordIns->getModuleAPIName().",";
 	}
 
 }catch (ZCRMException $e)
 {
 echo $e->getMessage()."\n";
 echo $e->getExceptionCode()."\n";
 }
*/

/*
 * Code for updateRecord
 */
/*
try{
	$record=ZCRMRecord::getInstance("Invoices",410405000001113030);
	$record->setFieldValue("Subject","Invoice3");
	$record->setFieldValue("Account_Name",410405000001016021);
	$lineItem=ZCRMInventoryLineItem::getInstance($record);
	$taxInstance1=ZCRMTax::getInstance("Sales Tax");
	$taxInstance1->setPercentage(12);
	$taxInstance1->setValue(100);
	$lineItem->addLineTax($taxInstance1);
	$taxInstance1=ZCRMTax::getInstance("Vat");
	$taxInstance1->setPercentage(10);
	$taxInstance1->setValue(50);
	$lineItem->addLineTax($taxInstance1);
	$lineItem->setProduct(ZCRMRecord::getInstance("Products",410405000001108011));
	$lineItem->setQuantity(101);
	$record->addLineItem($lineItem);
	
	$responseIns=$record->update();
	$responseRecord=$responseIns->getData();
	
	var_dump($responseRecord);
}
catch (ZCRMException $e)
{
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
}
*/


/*
 * Code to create a record
 */
 /*
try{
	$record=ZCRMRecord::getInstance("Invoices",null);
	$record->setFieldValue("Subject","Invoice4");
	$record->setFieldValue("Account_Name",410405000001016021);
	$lineItem=ZCRMInventoryLineItem::getInstance(410405000000773001);
	$taxInstance1=ZCRMTax::getInstance("Sales Tax");
	$taxInstance1->setPercentage(2);
	$taxInstance1->setValue(10);
	$lineItem->addLineTax($taxInstance1);
	$taxInstance1=ZCRMTax::getInstance("Vat");
	$taxInstance1->setPercentage(12);
	$taxInstance1->setValue(60);
	$lineItem->addLineTax($taxInstance1);
	$lineItem->setProduct(ZCRMRecord::getInstance("Products",410405000001108011));
	$lineItem->setQuantity(100);
	$record->addLineItem($lineItem);
	
	
	$responseIns=$record->create();
	$responseRecord=$responseIns->getData();
	
	var_dump($responseRecord);
}catch (ZCRMException $e)
{
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
}
*/


/*
 * Code to create multiple records
 */
/*
try{
	$recordsArr=array();
	for ($i=0;$i<10;$i++)
	{
		$record=ZCRMRecord::getInstance("Invoices",null);
		$record->setFieldValue("Subject","Invoice_API".$i);
		$record->setFieldValue("Account_Name","410405000001016021");
		$lineItem=ZCRMInventoryLineItem::getInstance(410405000000773001);
		$taxInstance1=ZCRMTax::getInstance("Sales Tax");
		$taxInstance1->setPercentage($i);
		$taxInstance1->setValue(10);
		$lineItem->addLineTax($taxInstance1);
		$taxInstance1=ZCRMTax::getInstance("Vat");
		$taxInstance1->setPercentage(++$i);
		$taxInstance1->setValue(60);
		$lineItem->addLineTax($taxInstance1);
		$lineItem->setProduct(ZCRMRecord::getInstance("Products",410405000001108011));
		$lineItem->setQuantity(100);
		$record->addLineItem($lineItem);
		array_push($recordsArr,$record);
	}
	$moduleIns=ZCRMModule::getInstance("Invoices");
	$bulkResponse=$moduleIns->createRecords($recordsArr);
	
	$entityResponses=$bulkResponse->getEntityResponses();
	foreach ($entityResponses as $entityResponse)
	{
		echo "\n\n";
		echo "Status:".$entityResponse->getStatus().",";
		echo "Message:".$entityResponse->getMessage().",";
		echo "Code:".$entityResponse->getCode().",";
		$recordIns=$entityResponse->getData();
		echo "EntityID:".$recordIns->getEntityId().",";
		echo "moduleAPIName:".$recordIns->getModuleAPIName().",";
		$lineItems=$record->getLineItems();
		var_dump($lineItems);
	}
}
catch (ZCRMException $e)
{
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
}
 */
 

/*
 * Code to uploadPhoto
 */
/*
try{
	$record=ZCRMRecord::getInstance("Leads",410405000001111007);
	$response=$record->uploadPhoto("/Users/sumanth-3058/sticky.png");
	echo $response->getMessage()."\n";
	echo $response->getStatus()."\n";
	echo $response->getCode()."\n";
	var_dump($response->getResponseJSON());
}catch(Exception $e)
{
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
	var_dump( $e->getExceptionDetails());
}
*/

/*
 * Code to downloadPhoto
 */
/*
try{
	$record=ZCRMRecord::getInstance("Leads",410405000001119015);
	$response=$record->downloadPhoto();
	echo $response->getMessage()."\n";
	echo $response->getStatus()."\n";
	echo $response->getCode()."\n";
	var_dump($response->getResponseJSON());
}catch(Exception $e)
{
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
	var_dump( $e->getExceptionDetails());
}
*/

/*
 * Code to convert lead
 */
/*
 try{
 $record=ZCRMRecord::getInstance("Leads",410405000001119015);

 $response=$record->convert();
 var_dump($response);
 
 $responseRecord=$responseIns->getData();
 var_dump($responseRecord);
 }catch(Exception $e)
 {
 echo $e->getMessage()."\n";
 echo $e->getExceptionCode()."\n";
 var_dump( $e->getExceptionDetails());
 }
 */

/*
 * Code to create record
 */

/*
try{
	$record=ZCRMRecord::getInstance("Leads",null);
	$record->setFieldValue("Phone","2345677779");
	$record->setFieldValue("Mobile","4104050008");
	$record->setFieldValue("Last_Name","Test Lead PHP2");
	$record->setFieldValue("Company","MSIT");
	
	
	$responseIns=$record->create();
	$responseRecord=$responseIns->getData();
	
	var_dump($responseRecord);
}catch(Exception $e)
{
	echo $e->getMessage()."\n";
	echo $e->getExceptionCode()."\n";
	var_dump( $e->getExceptionDetails());
}
*/

//$moduleArr=$restInstance->getModule('Leads')->getData();

//var_dump($moduleArr);
//var_dump($moduleArr->getFields());
//$fields=$moduleArr->getFields();

//$layouts=ZCRMModule::getInstance('Leads')->getFieldDetails(1386586000000002589)->getData();
//var_dump($layouts);

/*$arr=array("one"=>"1","two"=>"2");
$str=array("key1"=>$arr,"key2"=>array("10","20"));
$str['key3']=array("20","30");
echo json_encode(array("modules"=>$str));*/

/*
$long1=23459087654234;
if(is_bool($long1))
{
	echo "in\n\n";
}else {
	echo "not\n";
}
*/


/*
 * Code to Mass update a field
 */
/*
$idList=array(410405000001076001,410405000001021027,410405000000497012);
$response=ZCRMModule::getInstance("Leads")->updateRecords($idList,"City","Chennai");
var_dump($response->getResponseJSON());
*/

/*
 * Code to searchRecords
 */

/*
try{
	$response=ZCRMModule::getInstance("Leads")->searchRecords("Lead");
	$records=$response->getData();
	foreach ($records as $record){
		echo "\n";
		echo $record->getEntityId().",";
		echo $record->getModuleApiName().",";
		echo $record->getLookupLabel().",";

		echo $record->getCreatedBy()->getId().",";
		echo $record->getModifiedBy()->getId().",";
		echo $record->getOwner()->getId().",";
		echo $record->getCreatedTime().",";
		echo $record->getModifiedTime().",";

		$map=$record->getData();
		foreach ($map as $key=>$value)
		{
			echo $key.":".$value.",";
		}

		$lineItems=$record->getLineItems();
		echo "::LINEITEMS::";
		foreach ($lineItems as $lineItem)
		{
			echo $lineItem->getId().",";
			echo $lineItem->getListPrice().",";
			echo $lineItem->getQuantity().",";
			echo $lineItem->getDescription().",";
			echo $lineItem->getTotal().",";
			echo $lineItem->getDiscount().",";
			echo $lineItem->getDiscountPercentage().",";
			echo $lineItem->getTotalAfterDiscount().",";
			echo $lineItem->getTaxAmount().",";
			echo $lineItem->getNetTotal().",";
			echo $lineItem->getDeleteFlag().",";
			echo $lineItem->getProduct()->getId().",";
			echo $lineItem->getProduct()->getLookupLabel().",";
			$linTaxs=$lineItem->getLineTax();
			echo "::lineTAXES::";
			foreach ($linTaxs as $lineTax)
			{
				echo $lineTax->getTaxName().",";
				echo $lineTax->getPercentage().",";
				echo $lineTax->getValue().",";
			}
				
		}
	}
}catch (ZCRMException $ex)
{
	echo $ex->getMessage();
	echo "\n\n::";
	echo $ex->getTraceAsString();
	echo "\n\n::";
	echo $ex->getExceptionCode();
	echo "\n\n::";
	echo $ex->getFile();
	echo "\n\n::";
}
*/

/*
 * Code to getRecords
 */

/*
try{
	$response=ZCRMModule::getInstance("Sales_Orders")->getRecords();
	$records=$response->getData();
	var_dump($response->getInfo());
	foreach ($records as $record){
		echo "\n\n";
		echo $record->getEntityId().",";
		echo $record->getModuleApiName().",";
		echo $record->getLookupLabel().",";

		echo $record->getCreatedBy()->getId().",";
		echo $record->getModifiedBy()->getId().",";
		echo $record->getOwner()->getId().",";
		echo $record->getCreatedTime().",";
		echo $record->getModifiedTime().",";

		$map=$record->getData();
		foreach ($map as $key=>$value)
		{
			if($value instanceof ZCRMRecord)
			{
				echo "\n".$value->getEntityId().":".$value->getModuleApiName().":".$value->getLookupLabel()."\n";
			}
			else
			{
				echo $key.":".$value.",";
			}
		}
		$properties=$record->getAllProperties();
		echo "\nPROPERTIES($)::\n";
		foreach ($properties as $key=>$value)
		{
			if(is_object($value))
			{
				echo "\n\n\n(in)";
			}
			else if(is_array($value))
			{
				echo "KEY::".$key."=";
				foreach ($value as $key1=>$value1)
				{
					if(is_array($value1))
					{
						foreach ($value1 as $key2=>$value2)
						{
							echo $key2.":".$value2.",";
						}
					}
					else {
						echo $key1.":".$value1.",";
					}
				}
			}
			else {
				echo $key.":".$value.",";
			}
		}

		$lineItems=$record->getLineItems();
		echo "::LINEITEMS::";
		foreach ($lineItems as $lineItem)
		{
			echo $lineItem->getId().",";
			echo $lineItem->getListPrice().",";
			echo $lineItem->getQuantity().",";
			echo $lineItem->getDescription().",";
			echo $lineItem->getTotal().",";
			echo $lineItem->getDiscount().",";
			echo $lineItem->getDiscountPercentage().",";
			echo $lineItem->getTotalAfterDiscount().",";
			echo $lineItem->getTaxAmount().",";
			echo $lineItem->getNetTotal().",";
			echo $lineItem->getDeleteFlag().",";
			echo $lineItem->getProduct()->getEntityId().",";
			echo $lineItem->getProduct()->getLookupLabel().",";
			$linTaxs=$lineItem->getLineTax();
			echo "::lineTAXES::";
			foreach ($linTaxs as $lineTax)
			{
				echo $lineTax->getTaxName().",";
				echo $lineTax->getPercentage().",";
				echo $lineTax->getValue().",";
			}
				
		}
	}
}catch (ZCRMException $ex)
{
	echo $ex->getMessage();
	echo "\n::";
	echo $ex->getExceptionCode();
	echo "\n\n::";
	echo $ex->getFile();
	echo "\n\n::";
	//echo $ex->getTraceAsString();
	//echo "\n\n::";
}
*/

/*
 * Code to getRecord By Id
 */

/*
try{
	$response=ZCRMModule::getInstance("Leads")->getRecord(410405000001111007);
	$records=array($response->getData());
	foreach ($records as $record){
		echo "\n";
		echo $record->getEntityId().",";
		echo $record->getModuleApiName().",";
		echo $record->getLookupLabel().",";
		
		echo $record->getCreatedBy()->getId().",";
		echo $record->getModifiedBy()->getId().",";
		echo $record->getOwner()->getId().",";
		echo $record->getCreatedTime().",";
		echo $record->getModifiedTime().",";
		
		$map=$record->getData();
		foreach ($map as $key=>$value)
		{
			echo $key.":".$value.",";
		}
		
		$lineItems=$record->getLineItems();
		echo "::LINEITEMS::";
		foreach ($lineItems as $lineItem)
		{
			echo $lineItem->getId().",";
			echo $lineItem->getListPrice().",";
			echo $lineItem->getQuantity().",";
			echo $lineItem->getDescription().",";
			echo $lineItem->getTotal().",";
			echo $lineItem->getDiscount().",";
			echo $lineItem->getDiscountPercentage().",";
			echo $lineItem->getTotalAfterDiscount().",";
			echo $lineItem->getTaxAmount().",";
			echo $lineItem->getNetTotal().",";
			echo $lineItem->getDeleteFlag().",";
			echo $lineItem->getProduct()->getEntityId().",";
			echo $lineItem->getProduct()->getLookupLabel().",";
			$linTaxs=$lineItem->getLineTax();
			echo "::lineTAXES::";
			foreach ($linTaxs as $lineTax)
			{
				echo $lineTax->getTaxName().",";
				echo $lineTax->getPercentage().",";
				echo $lineTax->getValue().",";
			}
			
		}
	}
}catch (ZCRMException $ex)
{
	echo $ex->getMessage();
	echo "\n::";
	echo $ex->getExceptionCode();
	echo "\n\n::";
	echo $ex->getFile();
	echo "\n\n::";
	//echo $ex->getTraceAsString();
	//echo "\n\n::";
}
*/

/*
 * Code to create users
 */

/*
$userArray=array();
$user1=ZCRMUser::getInstance(null,null);
$user1->setLastName("user1");
$user1->setEmail("sumanth.chilka+user1@zohocorp.com");
$profile=ZCRMProfile::getInstance(410405000000015975,"Standard");
$user1->setProfile($profile);
$role=ZCRMRole::getInstance(410405000000015969,"Manager");
$user1->setRole($role);
$user1->setCity("Hyd");
array_push($userArray,$user1);
$user1=ZCRMUser::getInstance(null,null);
$profile=ZCRMProfile::getInstance(410405000000015975,"Standard");
$user1->setProfile($profile);
$role=ZCRMRole::getInstance(410405000000015969,"Manager");
$user1->setRole($role);
$user1->setCity("Nalgonda");
$user1->setLastName("user2");
$user1->setEmail("sumanth.chilka+user2@zohocorp.com");
array_push($userArray,$user1);

$restInstance=ZCRMOrganization::getInstance()->createUsers($userArray);
*/

/*
 * Code to get All roles
 */
/*
$response=ZCRMOrganization::getInstance()->getAllRoles();
$roles=$response->getData();
foreach ($roles as $role)
{
	echo $role->getId().",";
	echo $role->getName().",";
	echo $role->getLabel().",";
	echo $role->isAdminRole().",";
	$reportingTo=$role->getReportingTo();
	if($reportingTo!=null)
	{
		echo $reportingTo->getId().",";
		echo $reportingTo->getName().",";
	}
	echo "\n\n";
}

$crmRoleInstance=ZCRMRole::getInstance($roleDetails['id']+0,$roleDetails['name']);
$crmRoleInstance->setLabel($roleDetails['label']);
$crmRoleInstance->setAdminRole((boolean)$roleDetails['admin_user']);
$crmRoleInstance->setReportingTo
*/

/*
 *Code to get All Profiles 
 */

/*
$response=ZCRMOrganization::getInstance()->getAllProfiles();
$profiles=$response->getData();
foreach ($profiles as $profile)
{
	echo $profile->getId().",";
	echo $profile->getName().",";
	echo $profile->getCreatedTime().",";
	echo $profile->getModifiedTime().",";
	echo $profile->getDescription().",";
	echo $profile->getCategory().",";
	$createdBy=$profile->getCreatedBy();
	if($createdBy!=null)
	{
		echo $createdBy->getId().",";
		echo $createdBy->getName().",";
	}
	$modifiedBy=$profile->getModifiedBy();
	if($modifiedBy!=null)
	{
		echo $modifiedBy->getId().",";
		echo $modifiedBy->getName().",";
	}
	echo "\n\n";
}
*/

/*
 * Code to get All Users
 */

/*
$response=ZCRMOrganization::getInstance()->getUser(410405000000085001);
$users=array($response->getData());
foreach($users as $userInstance)
{
	echo "\n";
	echo $userInstance->getCountry().",";
	
	$roleInstance=$userInstance->getRole();
	echo $roleInstance->getId().",";
	echo $roleInstance->getName().",";
	
	$customizeInstance=$userInstance->getCustomizeInfo();
	if($customizeInstance!=null)
	{
		echo $customizeInstance->getNotesDesc().",";
		echo $customizeInstance->getUnpinRecentItem().",";
		echo $customizeInstance->isToShowRightPanel().",";
		echo $customizeInstance->isBcView().",";
		echo $customizeInstance->isToShowHome().",";
		echo $customizeInstance->isToShowDetailView().",";
	}
	
	echo $userInstance->getCity().",";
	echo $userInstance->getSignature().",";
	
	echo $userInstance->getNameFormat().",";
	echo $userInstance->getLanguage().",";
	echo $userInstance->getLocale().",";
	echo $userInstance->isPersonalAccount().",";
	echo $userInstance->getDefaultTabGroup().",";
	echo $userInstance->getAlias().",";
	echo $userInstance->getStreet().",";
	
	$themeInstance=$userInstance->getTheme();
	if($themeInstance!=null)
	{
		echo $themeInstance->getNormalTabFontColor().",";
		echo $themeInstance->getNormalTabBackground().",";
		echo $themeInstance->getSelectedTabFontColor().",";
		echo $themeInstance->getSelectedTabBackground().",";
	}
	
	echo $userInstance->getState().",";
	echo $userInstance->getCountryLocale().",";
	echo $userInstance->getFax().",";
	echo $userInstance->getFirstName().",";
	echo $userInstance->getEmail().",";
	echo $userInstance->getZip().",";
	echo $userInstance->getDecimalSeparator().",";
	echo $userInstance->getWebsite().",";
	echo $userInstance->getTimeFormat().",";
	
	$profile= $userInstance->getProfile();
	echo $profile->getId().",";
	echo $profile->getName().",";
	
	echo $userInstance->getMobile().",";
	echo $userInstance->getLastName().",";
	echo $userInstance->getTimeZone().",";
	echo $userInstance->getZuid().",";
	echo $userInstance->isConfirm().",";
	echo $userInstance->getFullName().",";
	echo $userInstance->getPhone().",";
	echo $userInstance->getDob().",";
	echo $userInstance->getDateFormat().",";
	echo $userInstance->getStatus().",";
	echo "\n\n";
}
*/

/*
 * Code to update Custom view settings
 *
 */
/*
$moduleInstance=ZCRMModule::getInstance("Leads");
$customViewInstance=ZCRMCustomView::getInstance(410405000001077005);
$customViewInstance->setSortBy("City");
$customViewInstance->setSortOrder("desc");
$responseIns=$moduleInstance->updateCustomView($customViewInstance);
var_dump($responseIns->getResponseJSON());
 */
 

/*
 * Code to update module settings
 * 
 */
/*
$moduleInstance=ZCRMModule::getInstance("Leads");
$businessCards=array("Email","Phone","Fax","Mobile","Industry");
$moduleInstance->setBusinessCardFields($businessCards);

$moduleInstance->setPerPage(20);
$moduleInstance->setDefaultCustomViewId(410405000001077005);

$relatedListProp=ZCRMRelatedListProperties::getInstance();
$relFields=array("First_Name","Last_Name","Company","Email","Lead_Source","Lead_Status","Phone");
$relatedListProp->setFields($relFields);
$relatedListProp->setSortOrder('asc');
$relatedListProp->setSortBy('Company');
$moduleInstance->setRelatedListProperties($relatedListProp);

$responseIns=$moduleInstance->updateModuleSettings($moduleInstance);
*/

/*
 *Code to get related list details of a module 
 */
/*
$moduleInstance=ZCRMModule::getInstance("Leads");
$responseIns=$moduleInstance->getRelatedListDetails(410405000000002730);
$relatedList=$responseIns->getData();
echo $relatedList->getModule().",";
echo $relatedList->getId().",";
echo $relatedList->getName().",";
echo $relatedList->getDisplayLabel().",";
echo $relatedList->getApiName().",";
echo $relatedList->isVisible().",";
echo $relatedList->getHref().",";
echo $relatedList->getType().",";
echo "\n";


foreach($relatedLists as $relatedList)
{
	echo $relatedList->getModule().",";
	echo $relatedList->getId().",";
	echo $relatedList->getName().",";
	echo $relatedList->getDisplayLabel().",";
	echo $relatedList->getApiName().",";
	echo $relatedList->isVisible().",";
	echo $relatedList->getHref().",";
	echo $relatedList->getType().",";
	echo "\n";
}


echo $responseIns->getCode()."\n";
echo $responseIns->getMessage()."\n";
var_dump( $responseIns->getDetails());echo "\n";
echo $responseIns->getStatus()."\n";

$moduleArr=array();
$fields=array();
foreach ($fields as $field)
{
	echo $field->getApiName().", ";
	echo $field->getLength().", ";
	echo $field->IsVisible().", ";
	echo $field->getFieldLabel().", ";
	echo $field->getCreatedSource().", ";
	echo $field->isMandatory().", ";
	echo $field->getSequenceNumber().", ";
	echo $field->isReadOnly().", ";
	echo $field->getDataType().", ";
	echo $field->getId().", ";
	echo $field->isCustomField().", ";
	echo $field->isBusinessCardSupported().", ";
	$fieldLayoutPerm=$field->getFieldLayoutPermissions();
	foreach ($fieldLayoutPerm as $perm)
	{
		echo $perm.", ";
	}
	$lookupField=$field->getLookupField();
	if($lookupField!=null)
	{
		echo $lookupField->getModule().", ";
		echo $lookupField->getDisplayLabel().", ";
		echo $lookupField->getId().", ";
	}
	$pickListFields=$field->getPickListFieldValues();
	foreach ($pickListFields as $pickList)
	{
		echo $pickList->getDisplayValue().", ";
		echo $pickList->getSequenceNumber().", ";
		echo $pickList->getActualValue().", ";
		echo $pickList->getMaps().", ";
	}
	echo $field->isUniqueField().", ";
	echo $field->isCaseSensitive().", ";
	
	echo $field->isCurrencyField().", ";
	echo $field->getPrecision().", ";
	echo $field->getRoundingOption().", ";
	echo $field->isFormulaField().", ";
	echo $field->getFormulaReturnType().", ";
	echo $field->getFormulaExpression().", ";
	
	echo $field->isAutoNumberField().", ";
	echo $field->getPrefix().", ";
	echo $field->getSuffix().", ";
	echo $field->getStartNumber().", ";
	echo $field->getDecimalPlace().", ";
	$convertMap=$field->getConvertMapping();
	foreach ($convertMap as $key=>$value)
	{
		echo $key.":".$value.",";
	}
	
	echo "\n";
}


foreach ($moduleArr as $module)
{
	$fields=$moduleArr->getFields();
	foreach ($fields as $field)
	{
		echo $field->getApiName().", ";
		echo $field->getLength().", ";
		echo $field->IsVisible().", ";
		echo $field->getFieldLabel().", ";
		echo $field->getCreatedSource().", ";
		echo $field->isMandatory().", ";
		echo $field->getSequenceNumber().", ";
		echo $field->isReadOnly().", ";
		echo $field->getDataType().", ";
		echo $field->getId().", ";
		echo $field->isCustomField().", ";
		echo $field->isBusinessCardSupported().", ";
		echo $field->getDefaultValue().", ";
		echo "\n";
	}
}
*/

?>