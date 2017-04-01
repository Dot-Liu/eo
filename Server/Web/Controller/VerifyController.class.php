<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class VerifyController
{
	// 返回json类型
	private $returnJson = array('type' => 'verify');

	/**
	 * 验证登录状态
	 */
	public function __construct()
	{
		// 身份验证
		$server = new GuestModule;
		if (!$server -> checkLogin())
		{
			$this -> returnJson['statusCode'] = '120005';
			exitOutput($this -> returnJson);
		}
	}

	/**
	 * 获取审核列表(资质)
	 */
	public function getAptitudeVerifyList()
	{
		$server = new VerifyModule;
		$result = $server -> getAptitudeVerifyList();
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['aptitudeVerifyList'] = $result;
		}
		else
		{
			$this -> returnJson['statusCode'] = '170001';
		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 获取审核列表(应用)
	 */
	public function getAppVerifyList()
	{
		$server = new VerifyModule;
		$result = $server -> getAppVerifyList();
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['appVerifyList'] = $result;
		}
		else
		{
			$this -> returnJson['statusCode'] = '170000';
		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 通过审核申请
	 */
	public function agreeVerifyRequest()
	{
		$verifyType = securelyInput('verifyType');
		$isPersonal = securelyInput('isPersonal');
		$userID = securelyInput('userID');
		$appID = securelyInput('appID');
		$productID = securelyInput('productID');

		if (!preg_match('/^[0-4]{1}$/', $verifyType))
		{
			$this -> returnJson['statusCode'] = '170002';
		}
		else
		if (!preg_match('/^[0-4]{1}$/', $isPersonal))
		{
			$this -> returnJson['statusCode'] = '170003';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $userID))
		{
			$this -> returnJson['statusCode'] = '170006';
		}
		else
		{

			if ($verifyType == 1)
			{
				if (!preg_match('/^[0-9]{1,11}$/', $appID))
				{
					$this -> returnJson['statusCode'] = '170007';
				}
				else
				if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
				{
					$this -> returnJson['statusCode'] = '130001';
				}
			}
			$IDList = array('appID' => $appID);
			$IDList['userID'] = $userID;
			$IDList['productID'] = $productID;

			$server = new VerifyModule;
			$result = $server -> agreeVerifyRequest($verifyType, $isPersonal, $IDList);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '170004';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 拒绝审核申请
	 */
	public function refuseVerifyRequest()
	{
		$verifyType = securelyInput('verifyType');
		$isPersonal = securelyInput('isPersonal');
		$userID = securelyInput('userID');
		$appID = securelyInput('appID');
		$productID = securelyInput('productID');

		if (!preg_match('/^[0-4]{1}$/', $verifyType))
		{
			$this -> returnJson['statusCode'] = '170002';
		}
		else
		if (!preg_match('/^[0-4]{1}$/', $isPersonal))
		{
			$this -> returnJson['statusCode'] = '170003';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $userID))
		{
			$this -> returnJson['statusCode'] = '170006';
		}
		else
		{

			if ($verifyType == 1)
			{
				if (!preg_match('/^[0-9]{1,11}$/', $appID))
				{
					$this -> returnJson['statusCode'] = '170007';
				}
				else
				if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
				{
					$this -> returnJson['statusCode'] = '130001';
				}
			}
			$IDList = array('appID' => $appID);
			$IDList['userID'] = $userID;
			$IDList['productID'] = $productID;

			$server = new VerifyModule;
			$result = $server -> refuseVerifyRequest($verifyType, $isPersonal, $IDList);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '170005';
			}
		}
		exitOutput($this -> returnJson);
	}

}
?>