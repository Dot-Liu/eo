<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class ApiController
{
	// 返回json类型
	private $returnJson = array('type' => 'api');

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
	 * 添加api
	 */
	public function addApi()
	{
		$productID = securelyInput('productID');
		$apiName = securelyInput('apiName');

		$apiNameLen = mb_strlen(quickInput('apiName'), 'utf8');
		$apiDesc = securelyInput('apiDesc');
		$targetURL = securelyInput('targetURL');
		$requestURL = securelyInput('requestURL');
		$apiRequestRaw = securelyInput('apiRequestRaw');
		$apiRequestParamType = securelyInput('apiRequestParamType');
		$apiProtocol = securelyInput('apiProtocol');
		$requestType = securelyInput('requestType');
		$targetRequestType = securelyInput('targetRequestType');
		$apiRequestParam = json_decode($_POST['apiRequestParam'], TRUE);
		$apiResultParam = json_decode($_POST['apiResultParam'], TRUE);
		$apiSuccessMock = quickInput('apiSuccessMock');
		$apiFailureMock = quickInput('apiFailureMock');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		if ($apiNameLen < 1 || $apiNameLen > 30)
		{
			$this -> returnJson['statusCode'] = '140001';
		}
		else
		if (!preg_match('/^[0-9]{1}$/', $apiProtocol))
		{
			$this -> returnJson['statusCode'] = '140002';
		}
		else
		if (!preg_match('/^[0-9]{1}$/', $requestType))
		{
			$this -> returnJson['statusCode'] = '140003';
		}
		else
		if (!preg_match('/^[0-9]{1}$/', $apiRequestParamType))
		{
			$this -> returnJson['statusCode'] = '140004';
		}
		else
		{
			$server = new ApiModule;
			$result = $server -> addApi($productID, $apiName, $apiDesc, $apiProtocol, $requestType, $targetRequestType, $targetURL, $requestURL, $apiSuccessMock, $apiFailureMock, $apiRequestParam, $apiResultParam, $apiRequestRaw, $apiRequestParamType);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['apiID'] = $result['apiID'];
				$this -> returnJson['productID'] = $result['productID'];
			}
			else
			{
				$this -> returnJson['statusCode'] = '140005';
				$this -> returnJson['result'] = $result;
			}
		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 修改api
	 */
	public function editApi()
	{
		$apiID = securelyInput('apiID');
		$apiName = securelyInput('apiName');
		$apiNameLen = mb_strlen(quickInput('apiName'), 'utf8');
		$apiDesc = securelyInput('apiDesc');
		$targetURL = securelyInput('targetURL');
		$requestURL = securelyInput('requestURL');
		$apiRequestRaw = securelyInput('apiRequestRaw');
		$apiRequestParamType = securelyInput('apiRequestParamType');
		$apiProtocol = securelyInput('apiProtocol');
		$requestType = securelyInput('requestType');
		$targetRequestType = securelyInput('targetRequestType');
		$apiRequestParam = json_decode($_POST['apiRequestParam'], TRUE);
		$apiResultParam = json_decode($_POST['apiResultParam'], TRUE);
		$apiSuccessMock = quickInput('apiSuccessMock');
		$apiFailureMock = quickInput('apiFailureMock');
		if (!preg_match('/^[0-9]{1,11}$/', $apiID))
		{
			$this -> returnJson['statusCode'] = '140006';
		}
		else
		if ($apiNameLen < 1 || $apiNameLen > 30)
		{
			$this -> returnJson['statusCode'] = '140001';
		}
		else
		if (!preg_match('/^[0-9]{1}$/', $apiProtocol))
		{
			$this -> returnJson['statusCode'] = '140002';
		}
		else
		if (!preg_match('/^[0-9]{1}$/', $requestType))
		{
			$this -> returnJson['statusCode'] = '140003';
		}
		else
		if (!preg_match('/^[0-9]{1}$/', $apiRequestParamType))
		{
			$this -> returnJson['statusCode'] = '140004';
		}
		else
		{
			$service = new ApiModule;
			$result = $service -> editApi($apiID, $apiName, $apiDesc, $apiProtocol, $requestType, $targetRequestType, $targetURL, $requestURL, $apiSuccessMock, $apiFailureMock, $apiRequestParam, $apiResultParam, $apiRequestRaw, $apiRequestParamType);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['apiID'] = $result['apiID'];
			}
			else
			{
				$this -> returnJson['statusCode'] = '140007';
			}
		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 彻底删除api
	 * @param $apiID 接口ID
	 */
	public function deleteApi()
	{
		$apiID = securelyInput('apiID');
		if (!preg_match('/^[0-9]{1,11}$/', $apiID))
		{
			$this -> returnJson['statusCode'] = '140006';
		}
		else
		{
			$service = new ApiModule;
			$result = $service -> deleteApi($apiID);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '140008';
			}
		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 获取api列表（按更新时间排序)
	 */
	public function getApiListByUpdateTime()
	{
		$productID = securelyInput('productID');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		{
			$service = new ApiModule;
			$result = $service -> getApiListByUpdateTime($productID);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['ApiList'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '140009';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取api列表
	 */

	/**
	 * 获取api信息
	 */
	public function getApiInfo()
	{
		$apiID = securelyInput('apiID');
		if (!preg_match('/^[0-9]{1,11}$/', $apiID))
		{
			$this -> returnJson['statusCode'] = '140006';
		}
		else
		{
			$service = new ApiModule;
			$result = $service -> getApiInfoByCache($apiID);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['apiInfo'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '140010';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取api列表
	 */
	public function getApiList(&$productID)
	{
		$productID = securelyInput('productID');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		{
			$service = new ApiModule;
			$result = $service -> getApiList($productID);
			;
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['ApiList'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '140011';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取缓存中的api信息
	 */
	public function getApiInfoByCache(&$apiID)
	{
		$apiID = securelyInput('apiID');
		if (!preg_match('/^[0-9]{1,11}$/', $apiID))
		{
			$this -> returnJson['statusCode'] = '140006';
		}
		else
		{
			$service = new ApiModule;
			$result = $service -> getApiInfoByCache($apiID);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['apiInfo'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '140012';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 地址查重
	 */
	public function cnkiURL()
	{
		$urlType = securelyInput('urlType');
		$url = securelyInput('url');
		if (!preg_match('/^[01]{1}$/', $urlType))
		{
			$this -> returnJson['statusCode'] = '140013';
		}
		$service = new ApiModule;
		$result = $service -> cnkiURL($urlType, $url);
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['isExist'] = '1';
		}
		else
		{
			$this -> returnJson['statusCode'] = '140014';
			$this -> returnJson['isExist'] = '0';
		}
		exitOutput($this -> returnJson);
	}

}
?>