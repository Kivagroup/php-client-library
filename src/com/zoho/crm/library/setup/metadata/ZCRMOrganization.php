<?php
require_once realpath(dirname(__FILE__).'/../../api/handler/UserAPIHandler.php');

/**
 * Purpose of this method is to call the Organization level APIs like users, profiles, roles, ..etc 
 * @author sumanth-3058
 *
 */
class ZCRMOrganization
{
	private function __construct()
	{
		
	}
	
	public static function getInstance()
	{
		return new ZCRMOrganization();
	}
	
	public function getUser($userId)
	{
		return UserAPIHandler::getInstance()->getUser($userId);
	}
	public function getAllUsers()
	{
		return UserAPIHandler::getInstance()->getAllUsers();
	}
	
	public function getAllActiveUsers()
	{
		return UserAPIHandler::getInstance()->getAllActiveUsers();
	}
	
	public function getAllDeactiveUsers()
	{
		return UserAPIHandler::getInstance()->getAllDeactiveUsers();
	}
	
	public function getAllConfirmedUsers()
	{
		return UserAPIHandler::getInstance()->getAllConfirmedUsers();
	}
	
	public function getAllNotConfirmedUsers()
	{
		return UserAPIHandler::getInstance()->getAllNotConfirmedUsers();
	}
	
	public function getAllDeletedUsers()
	{
		return UserAPIHandler::getInstance()->getAllDeletedUsers();
	}
	
	public function getAllActiveConfirmedUsers()
	{
		return UserAPIHandler::getInstance()->getAllActiveConfirmedUsers();
	}
	
	public function getAllAdminUsers()
	{
		return UserAPIHandler::getInstance()->getAllAdminUsers();
	}
	
	public function getAllActiveConfirmedAdmins()
	{
		return UserAPIHandler::getInstance()->getAllActiveConfirmedAdmins();
	}
	
	public function getCurrentUser()
	{
		return UserAPIHandler::getInstance()->getCurrentUser();
	}
	
	
	public function getAllProfiles()
	{
		return UserAPIHandler::getInstance()->getAllProfiles();
	}
	
	public function getProfile($profileId)
	{
		return UserAPIHandler::getInstance()->getProfile($profileId);
	}
	
	public function getAllRoles()
	{
		return UserAPIHandler::getInstance()->getAllRoles();
	}
	
	public function getRole($roleId)
	{
		return UserAPIHandler::getInstance()->getRole($roleId);
	}
	
	public function createUser($userInstance)
	{
		return UserAPIHandler::getInstance()->createUser($userInstance);
	}
}
?>