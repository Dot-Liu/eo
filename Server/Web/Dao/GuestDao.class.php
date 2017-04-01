<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class GuestDao
{
	/**
	 * 用户登录
	 */
	public function getLoginInfo(&$loginName)
	{
		$db = getDatabase();
		$result = $db -> prepareExecute('SELECT eo_api_store_admin.* FROM eo_api_store_admin WHERE eo_api_store_admin.loginName = ? ;',array($loginName));
		if($db -> getAffectRow()>0)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
	
}
?>