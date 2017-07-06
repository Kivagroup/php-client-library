<?php

require_once 'ZohoOAuth.php';
require_once realpath(dirname(__FILE__)."/../common/OAuthLogger.php");
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthHTTPConnector.php");
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthConstants.php");
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthTokens.php");

class ZohoOAuthClient
{
	private $zohoOAuthParams;
	private static $zohoOAuthClient;
	
	private function __construct($params)
	{
		$this->zohoOAuthParams=$params;
	}
	public static function getInstance($params)
	{
		if(self::$zohoOAuthClient == null)
		{
			self::$zohoOAuthClient = new ZohoOAuthClient($params);
		}
		return self::$zohoOAuthClient;
	}
	
	public static function getInstanceWithOutParam()
	{
		return self::$zohoOAuthClient;
	}
	
	public function getAccessToken()
	{
		$persistence = ZohoOAuth::getPersistenceHandlerInstance();
		$tokens;
		try
		{
			$tokens = $persistence->getOAuthTokens();
		}
		catch(Exception $ex)
		{
			OAuthLogger::severe("Exception while retrieving tokens from persistence - ".$ex);
			throw new ZohoOAuthException($ex);
		}
		try
		{
			return $tokens->getAccessToken();
		}
		catch(ZohoOAuthException $ex)
		{
			OAuthLogger::info("Access Token has expired. Hence refreshing.");
			$tokens = self::refreshAccessToken($tokens->getRefreshToken());
			return $tokens->getAccessToken();
		}
	}
	
	public function requestGrantToken()
	{
		$conn = getZohoConnector(ZohoOAuth::getGrantURL());
		$conn->addParam(ZohoOAuthConstants::SCOPES, ZohoOAuth::getCRMScope());
		$conn->addParam(ZohoOAuthConstants::RESPONSE_TYPE, ZohoOAuthConstants::RESPONSE_TYPE_CODE);
		$conn->addParam(ZohoOAuthConstants::ACCESS_TYPE, ZohoOAuth::getAccessType());
		return $conn.get();
	}
	
	public function generateAccessToken($grantToken)
	{
		if($grantToken == null)
		{
			throw new ZohoOAuthException("Grant Token is not provided.");
		}
		try
		{
			$conn = self::getZohoConnector(ZohoOAuth::getTokenURL());
			$conn->addParam(ZohoOAuthConstants::GRANT_TYPE, ZohoOAuthConstants::GRANT_TYPE_AUTH_CODE);
			$conn->addParam(ZohoOAuthConstants::CODE, $grantToken);
			$resp = $conn->post();
			$responseJSON=json_decode($resp,true);
			if(array_key_exists(ZohoOAuthConstants::ACCESS_TOKEN,$responseJSON))
			{
				$tokens = self::getTokensFromJSON($responseJSON);
				ZohoOAuth::getPersistenceHandlerInstance()->saveOAuthData($tokens);
				return $tokens;
			}
			else
			{
				throw new ZohoOAuthException("Exception while fetching access token from grant token - " .$resp);
			}
		}
		catch (Exception $ex)
		{
			throw new ZohoOAuthException($ex);
		}
	}
	
	private function refreshAccessToken($refreshToken)
	{
		if($refreshToken == null)
		{
			throw new ZohoOAuthException("Refresh token is not provided.");
		}
		try
		{
			$conn = self::getZohoConnector(ZohoOAuth::getRefreshTokenURL());
			$conn->addParam(ZohoOAuthConstants::GRANT_TYPE, ZohoOAuthConstants::GRANT_TYPE_REFRESH);
			$conn->addParam(ZohoOAuthConstants::REFRESH_TOKEN, $refreshToken);
			$response = $conn->post();
			$responseJSON = json_decode($response,true);
			if (array_key_exists(ZohoOAuthConstants::ACCESS_TOKEN,$responseJSON))
			{
				$tokens = self::getTokensFromJSON($responseJSON);
				$tokens->setRefreshToken($refreshToken);
				ZohoOAuth::getPersistenceHandlerInstance()->saveOAuthData($tokens);
				return $tokens;
			}
			else
			{
				throw new ZohoOAuthException("Exception while fetching access token from refresh token - " . $response);
			}
		}
		catch (Exception $ex)
		{
			throw new ZohoOAuthException($ex);
		}
	}
	
	private function getZohoConnector($url)
	{
		$zohoHttpCon = new ZohoOAuthHTTPConnector();
		$zohoHttpCon->setUrl($url);
		$zohoHttpCon->addParam(ZohoOAuthConstants::CLIENT_ID, $this->zohoOAuthParams->getClientId());
		$zohoHttpCon->addParam(ZohoOAuthConstants::CLIENT_SECRET, $this->zohoOAuthParams->getClientSecret());
		$zohoHttpCon->addParam(ZohoOAuthConstants::REDIRECT_URL, $this->zohoOAuthParams->getRedirectURL());
		return $zohoHttpCon;
	}
	
	private function getTokensFromJSON($responseObj)
	{
		$oAuthTokens = new ZohoOAuthTokens();
		$expiresIn = $responseObj[ZohoOAuthConstants::EXPIRES_IN];
		$oAuthTokens->setExpiryTime($oAuthTokens->getCurrentTimeInMillis()+$expiresIn);
	
		$accessToken = $responseObj[ZohoOAuthConstants::ACCESS_TOKEN];
		$oAuthTokens->setAccessToken($accessToken);
		if (array_key_exists(ZohoOAuthConstants::REFRESH_TOKEN,$responseObj))
		{
			$refreshToken = $responseObj[ZohoOAuthConstants::REFRESH_TOKEN];
			$oAuthTokens->setRefreshToken($refreshToken);
		}
		return $oAuthTokens;
	}
	
	

    /**
     * zohoOAuthParams
     * @return unkown
     */
    public function getZohoOAuthParams(){
        return $this->zohoOAuthParams;
    }

    /**
     * zohoOAuthParams
     * @param unkown $zohoOAuthParams
     */
    public function setZohoOAuthParams($zohoOAuthParams){
        $this->zohoOAuthParams = $zohoOAuthParams;
    }

}
?>