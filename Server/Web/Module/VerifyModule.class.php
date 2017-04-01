<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class VerifyModule
{
	public function __construct()
	{
		@session_start();
	}

	/**
	 * 获取审核列表
	 */
	public function getVerifyList()
	{
		$dao = new VerifyDao;
		return $dao -> getVerifyList();
	}

	/**
	 * 通过审核申请
	 */
	public function agreeVerifyRequest(&$verifyType, &$isPersonal, &$IDList)
	{

		$dao = new VerifyDao;
		return $dao -> agreeVerifyRequest($verifyType, $isPersonal, $IDList);
	}

	/**
	 * 拒绝审核申请
	 */
	public function refuseVerifyRequest(&$verifyType, &$isPersonal, &$IDList)
	{
		$dao = new VerifyDao;
		return $dao -> refuseVerifyRequest($verifyType, $isPersonal, $IDList);
	}

	/**
	 * 获取资质列表
	 */
	public function getAptitudeVerifyList()
	{
		$dao = new VerifyDao;
		return $dao -> getVerifyPersonalList();
	}

	/**
	 * 获取应用审核列表
	 */
	public function getAppVerifyList()
	{
		$dao = new VerifyDao;
		return $dao -> getVerifyAppListByPerson();
	}

}
?>