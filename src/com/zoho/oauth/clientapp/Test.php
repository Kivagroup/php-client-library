<?php
require_once "../common/ZohoOAuthConstants.php";
require_once "../common/ZohoOAuthException.php";
require_once "../common/ZohoOAuthHTTPConnector.php";
require_once "../common/ZohoOAuthTokens.php";
require_once "../common/OAuthLogger.php";
require_once "./ZohoOAuthPersistenceHandler.php";

echo dirname(__FILE__);
echo "\nroot:".$_SERVER['DOCUMENT_ROOT']."\n\n";
/*try{
	throw new ZohoOAuthException("Test exception");
}catch(ZohoOAuthException $ex)
{
	echo "Exception occured:",$ex;
}*/
$arr=array();
$arr['aa']="aaa";
$arr['bb']="bbb";
$arr['bb']="ccc";
$key='bb';
foreach ($arr as $key=>$value)
{
	echo $key.":".$value.'\n';
}
echo $arr[$key];

$logger=new OAuthLogger();
$logger->info("Info");
OAuthLogger::warn("Exception in my method2");		
echo ZohoOAuthConstants::STATE;
echo ZohoOAuthConstants::DISPATCH_TO."\n";

$json='{"key1":"val1","key2":"val2"}';
echo $json;
$obj=json_decode($json);
if(array_key_exists("key11",$obj)){
echo $obj->{'key1'};}


$accesstoken="1000.1c8299c53bcd721bcd4d9f1aed8e3aa2.1c64a605fcce0bf7d8a37ada733c975f";
$refreshtoken="1000.6ac3db92da02af5bb5810eace5864ce2.c0d77f85965e20b0328dba01926544c6";
$expiresin=3600000;
$tokens=new ZohoOAuthTokens();
$tokens->setExpiryTime($tokens->getCurrentTimeInMillis()+$expiresin);
$tokens->setRefreshToken($refreshtoken);
$tokens->setAccessToken($accesstoken);

$handler=new ZohoOAuthPersistenceHandler();
$token=$handler->saveOAuthData($tokens);


//echo $token->getAccessToken()."\n";
//echo $token->getRefreshToken()."\n";
//echo $token->getExpiryTime()."\n";

/*$url="http://raghu-2264.csez.zohocorpin.com:8081/crm/internal/xml/Leads/getRecords";

$httpCon=new ZohoHTTPConnector();
$httpCon->setUrl($url);
$httpCon->addParam("authtoken","22aa8f47036315e7627e078bd9693e15");
$httpCon->addParam("params[]","1");
$httpCon->addParam("params[]","2");
echo $httpCon->post();*/

/*$url="http://localhost:8080/crm/private/xml/Leads/getRecords";

$httpCon=new ZohoHTTPConnector();
$httpCon->setUrl($url);
$httpCon->addParam("authtoken","25ad26e6304099093741c21dde18bcb5");
$httpCon->addParam("params[]","1");
$httpCon->addParam("params[]","2");
$httpCon->addParam("params[]","3");
$httpCon->addParam("params","4");
echo $httpCon->post();*/

?>