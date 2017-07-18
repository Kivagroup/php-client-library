<?php
require_once realpath(dirname(__FILE__).'/../../../../com/zoho/crm/library/setup/restclient/ZCRMRestClient.php');
require_once './api/MetaDataAPIHandlerTest.php';
require_once './api/EntityAPIHandlerTest.php';
require_once './api/ModuleAPIHandlerTest.php';
require_once './api/RelatedListAPIHandlerTest.php';
require_once './api/OrganizationAPIHandlerTest.php';
require_once './api/MassEntityAPIHandlerTest.php';
require_once './common/Helper.php';

class Main
{
	public static $totalCallsCount=0;
	public static $failureCount=0;
	public static $successCount=0;
	public static $totalCount=0;
	public function startAutomation()
	{
		try{
			ZCRMRestClient::initialize();
			
			/*$oAuthCli = ZohoOAuth::getClientInstance();
			$grantToken = "1000.8d21e64ba6f9834c7abeaa19e11651bc.9ec9e6a4f9e686169d3d20e19343010a";
			$oAuthTokens = $oAuthCli->generateAccessToken($grantToken);
			$accessToken = $oAuthTokens->getAccessToken();
			$refreshToken=$oAuthTokens->getRefreshToken();
			*/
			$startTime=microtime(true)*1000;
			$fileName="automationReport.html";
			$fp=fopen($fileName, "w+");
			$header="<html><head>Report</head><body><h1><center><b><i> PHP Client Library Test Report</i></b></center></h1><hr><br><table border=\"1\" width=\"100%\" cellspacing=\"2\" cellpadding=\"10\"><tr bgcolor=\"B7B2B2\"><td><b><center>SL no.</center></b></td><td><b><center>Class Name</center></b></td><td><b><center>Method Name</center></b></td><td><b><center>Message</center></b></td><td><b><center>Exception</center></b></td><td><b><center>Status</center></b></td><td><b><center>Time Taken(in milliseconds)</center></b></td></tr>";
			fwrite($fp, $header);
			OrganizationAPIHandlerTest::test($fp);
			MetaDataAPIHandlerTest::test($fp);
			ModuleAPIHandlerTest::test($fp);
			EntityAPIHandlerTest::test($fp);
			MassEntityAPIHandlerTest::test($fp);
			
			$endTime=microtime(true)*1000;
			$duration=$endTime-$startTime;
			$duration=round($duration)/1000;
			$duration=round($duration);
			fwrite($fp, Helper::TROPEN.'<td colspan="2"><font color="blue"><h2>Total Count</h1></font>'.Helper::TDCLOSE.'<td colspan="6"><h2>'.self::$totalCount.Helper::TDTRCLOSE);
			fwrite($fp, Helper::TROPEN.'<td colspan="2"><font color="red"><h2>Failure Count</h1></font>'.Helper::TDCLOSE.'<td colspan="6"><h2><font color="red">'.self::$failureCount.Helper::TDTRCLOSE);
			fwrite($fp, Helper::TROPEN.'<td colspan="2"><font color="green"><h2>Success Count</h1></font>'.Helper::TDCLOSE.'<td colspan="6"><h2><font color="green">'.self::$successCount.Helper::TDTRCLOSE);
			fwrite($fp, Helper::TROPEN.'<td colspan="2"><font color="grey"><h2>Run Duration (in min)</h1></font>'.Helper::TDCLOSE.'<td colspan="6"><h2>'.($duration/60).Helper::TDTRCLOSE);
			fclose($fp);
				
		}
		catch (Exception $e)
		{
			echo $e;
		}
	}
	
	public static function incrementTotalCount()
	{
		self::$totalCallsCount++;
	}
	
	public static function getCurrentCount()
	{
		return self::$totalCallsCount;
	}
	
}
$instance=new Main();
$instance->startAutomation();
?>