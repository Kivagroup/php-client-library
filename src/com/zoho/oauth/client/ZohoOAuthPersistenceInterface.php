<?php
interface ZohoOAuthPersistenceInterface
{
	public function saveOAuthData($zohoOAuthTokens);
	public function getOAuthTokens();
	public function deleteOAuthTokens();
}
?>