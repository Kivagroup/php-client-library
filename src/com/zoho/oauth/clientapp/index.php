<h1>HELLO</h1>
<?php
include_once '../client/ZohoOAuth.php';
//require_once '../client/ZohoOAuthClient.php';

ZohoOAuth::initialize(null);
$oauthCli=ZohoOAuth::getClientInstance();
$grantToken="1000.184d0f0eae2b89082b9734b0c2a358bf.222cf729e3c21c20b099d39735d2ce97";
$tokens=$oauthCli->generateAccessToken($grantToken);
echo $tokens->getAccessToken();
echo $tokens->getRefreshToken();

?>