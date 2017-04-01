<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class GuestModule
{
	/**
	 * 登录
	 * @param $loginName 登录用户名
	 * @param $loginPassword 登录密码
	 */
	public function login(&$loginName, &$loginPassword)
	{
		$dao = new GuestDao;
		$userInfo = $dao -> getLoginInfo($loginName);
		if (md5($loginPassword) == $userInfo['loginPassword'])
		{
			@session_start();
			$_SESSION['adminID'] = $userInfo['adminID'];
			$_SESSION['loginName'] = $userInfo['loginName'];
			return TRUE;
		}
		else
			return FALSE;
	}

	/**
	 * 验证登录状态
	 */
	public function checkLogin()
	{
		@session_start();
		if (isset($_SESSION['adminID']))
			return TRUE;
		else
			return FALSE;
	}

}
?>