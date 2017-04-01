<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class ApiModule
{
	/**
	 * 添加api
	 */
	public function addApi(&$productID, &$apiName, &$apiDesc, &$apiProtocol, &$requestType, &$targetRequestType, &$targetURL, &$requestURL, &$apiSuccessMock, &$apiFailureMock, &$apiRequestParam, &$apiResultParam, &$apiRequestRaw, &$apiRequestParamType)
	{
		//判断部分请求参数是否为空，如果为空值则赋值成为空字符串
		if (empty($apiSuccessMock))
		{
			$apiSuccessMock = '';
		}
		if (empty($apiFailureMock))
		{
			$apiFailureMock = '';
		}
		if (empty($apiRequestParam))
		{
			$apiSuccessMock = '';
		}
		if (empty($apiResultParam))
		{
			$apiFailureMock = '';
		}
		$apiDao = new ApiDao;
		$updateTime = date('Y-m-d H:i:s', time());
		//生成缓存数据
		$cacheJson['baseInfo']['apiName'] = $apiName;
		$cacheJson['baseInfo']['requestURL'] = $requestURL;
		$cacheJson['baseInfo']['targetURL'] = $targetURL;
		$cacheJson['baseInfo']['apiDesc'] = $apiDesc;
		$cacheJson['baseInfo']['apiProtocol'] = $apiProtocol;
		$cacheJson['baseInfo']['apiSuccessMock'] = $apiSuccessMock;
		$cacheJson['baseInfo']['apiFailureMock'] = $apiFailureMock;
		$cacheJson['baseInfo']['requestType'] = $requestType;
		$cacheJson['baseInfo']['targetRequestType'] = $targetRequestType;
		$cacheJson['baseInfo']['apiUpdateTime'] = $updateTime;
		$cacheJson['baseInfo']['apiRequestRaw'] = $apiRequestRaw;
		$cacheJson['baseInfo']['apiRequestParamType'] = $apiRequestParamType;
		$cacheJson['requestInfo'] = $apiRequestParam;
		$cacheJson['resultInfo'] = $apiResultParam;
		$cacheJson = json_encode($cacheJson);
		//				return $cacheJson;
		return $apiDao -> addApi($productID, $apiName, $apiDesc, $apiProtocol, $requestType, $targetRequestType, $targetURL, $requestURL, $apiSuccessMock, $apiFailureMock, $apiRequestParam, $apiResultParam, $apiRequestRaw, $apiRequestParamType, $updateTime, $cacheJson);
	}

	/**
	 * 修改api
	 */
	public function editApi(&$apiID, &$apiName, &$apiDesc, &$apiProtocol, &$requestType, &$targetRequestType, &$targetURL, &$requestURL, &$apiSuccessMock, &$apiFailureMock, &$apiRequestParam, &$apiResultParam, &$apiRequestRaw, &$apiRequestParamType)
	{
		//判断部分请求参数是否为空，如果为空值则赋值成为空字符串
		if (empty($apiSuccessMock))
		{
			$apiSuccessMock = '';
		}
		if (empty($apiFailureMock))
		{
			$apiFailureMock = '';
		}
		$apiDao = new ApiDao;
		$updateTime = date('Y-m-d H:i:s', time());
		//生成缓存数据
		$cacheJson['baseInfo']['apiName'] = $apiName;
		$cacheJson['baseInfo']['requestURL'] = $requestURL;
		$cacheJson['baseInfo']['targetURL'] = $targetURL;
		$cacheJson['baseInfo']['apiDesc'] = $apiDesc;
		$cacheJson['baseInfo']['apiProtocol'] = $apiProtocol;
		$cacheJson['baseInfo']['apiSuccessMock'] = $apiSuccessMock;
		$cacheJson['baseInfo']['apiFailureMock'] = $apiFailureMock;
		$cacheJson['baseInfo']['requestType'] = $requestType;
		$cacheJson['baseInfo']['targetRequestType'] = $targetRequestType;
		$cacheJson['baseInfo']['apiUpdateTime'] = $updateTime;
		$cacheJson['baseInfo']['apiRequestRaw'] = $apiRequestRaw;
		$cacheJson['baseInfo']['apiRequestParamType'] = $apiRequestParamType;
		$cacheJson['requestInfo'] = $apiRequestParam;

		$cacheJson['resultInfo'] = $apiResultParam;
		$cacheJson = json_encode($cacheJson);
		//		return $cacheJson;
		return $apiDao -> editApi($apiID, $apiName, $apiDesc, $apiProtocol, $requestType, $targetRequestType, $targetURL, $requestURL, $apiSuccessMock, $apiFailureMock, $apiRequestParam, $apiResultParam, $apiRequestRaw, $apiRequestParamType, $updateTime, $cacheJson);
	}

	/**
	 * 彻底删除api
	 */
	public function deleteApi(&$apiID)
	{
		$dao = new ApiDao;
		return $dao -> deleteApi($apiID);
	}

	/**
	 * 获取api列表（按更新时间排序)
	 */
	public function getApiListByUpdateTime(&$productID)
	{
		$dao = new ApiDao;
		return $dao -> getApiListByUpdateTime($productID);
	}

	/**
	 * 获取api列表
	 */
	public function getApiList(&$productID)
	{
		$dao = new ApiDao;
		return $dao -> getApiList($productID);
	}

	/**
	 * 获取api信息
	 */
	public function getApiInfo(&$apiID)
	{
		return TRUE;
		$dao = new ApiDao;
		return $dao -> getApiInfo($apiID);
	}

	/**
	 * 获取缓存中的api信息
	 */
	public function getApiInfoByCache(&$apiID)
	{
		$dao = new ApiDao;
		return $dao -> getApifoByCache($apiID);
	}

	/**
	 * 地址查重
	 */
	public function cnkiURL(&$urlType, &$url)
	{
		$dao = new ApiDao;
		return $dao -> cnkiURL($urlType, $url);
	}

}
?>