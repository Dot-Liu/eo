<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class ApiDao
{
	/**
	 * 添加api
	 */
	public function addApi(&$productID, &$apiName, &$apiDesc, &$apiProtocol, &$requestType, &$targetRequestType, &$targetURL, &$requestURL, &$apiSuccessMock, &$apiFailureMock, &$apiRequestParam, &$apiResultParam, &$apiRequestRaw, &$apiRequestParamType, &$updateTime, &$cacheJson)
	{
		$db = getDatabase();
		try
		{
			//开始事务
			$db -> beginTransaction();
			$db -> prepareExecute('INSERT INTO eo_api_store_api (eo_api_store_api.apiName,eo_api_store_api.productID,eo_api_store_api.apiDesc,eo_api_store_api.apiProtocol,eo_api_store_api.requestType,eo_api_store_api.targetRequestType,eo_api_store_api.apiSuccessMock,eo_api_store_api.apiFailureMock,eo_api_store_api.updateTime,eo_api_store_api.targetURL,eo_api_store_api.requestURL,eo_api_store_api.apiRequestRaw,eo_api_store_api.apiRequestParamType) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);', array(
				$apiName,
				$productID,
				$apiDesc,
				$apiProtocol,
				$requestType,
				$targetRequestType,
				$apiSuccessMock,
				$apiFailureMock,
				$updateTime,
				$targetURL,
				$requestURL,
				$apiRequestRaw,
				$apiRequestParamType
			));
			if ($db -> getAffectRow() < 1)
				throw new \PDOException("addApi error");
				
			if ($db -> getAffectRow() > 0)
			{
				$apiID = $db -> getLastInsertID();
				//插入api请求值信息
				if (!empty($apiRequestParam[0]))
				{
					foreach ($apiRequestParam as $param)
					{
						$db -> prepareExecute('INSERT INTO eo_api_store_request_param (eo_api_store_request_param.apiID,eo_api_store_request_param.paramName,eo_api_store_request_param.paramKey,eo_api_store_request_param.paramValue,eo_api_store_request_param.paramNotNull,eo_api_store_request_param.paramType) VALUES (?,?,?,?,?,?);', array(
							$apiID,
							$param['paramName'],
							$param['paramKey'],
							$param['paramValue'],
							$param['paramNotNull'],
							$param['paramType']
						));
						if ($db -> getAffectRow() < 1)
							throw new \PDOException("addRequestParam error");

						$paramID = $db -> getLastInsertID();
						if (!empty($param['paramValueList'][0]))
							foreach ($param['paramValueList'] as $value)
							{

								$db -> prepareExecute('INSERT INTO eo_conn_store_request_value (eo_conn_store_request_value.paramID,eo_conn_store_request_value.value,eo_conn_store_request_value.valueDesc) VALUES (?,?,?);', array(
									$paramID,
									$value['value'],
									$value['valueDesc']
								));

								if ($db -> getAffectRow() < 1)
									throw new \PDOException("addApi error");

							}
						;
					};
				}
				if (!empty($apiResultParam[0]))
				{
					//插入api返回值信息
					foreach ($apiResultParam as $param)
					{
						$db -> prepareExecute('INSERT INTO eo_api_store_result_param (eo_api_store_result_param.apiID,eo_api_store_result_param.paramName,eo_api_store_result_param.paramDesc) VALUES (?,?,?);', array(
							$apiID,
							$param['paramName'],
							$param['paramDesc'],
						));
						if ($db -> getAffectRow() < 1)
							throw new \PDOException("addResultParam error");

						$paramID = $db -> getLastInsertID();
						if (!empty($param['paramValueList'][0]))
							foreach ($param['paramValueList'] as $value)
							{
								$db -> prepareExecute('INSERT INTO eo_conn_store_result_value (eo_conn_store_result_value.resultID,eo_conn_store_result_value.value,eo_conn_store_result_value.valueDesc) VALUES (?,?,?);', array(
									$paramID,
									$value['value'],
									$value['valueDesc']
								));
								if ($db -> getAffectRow() < 1)
									throw new \PDOException("addApi error");

							}
						;

					};
				}

				//插入api缓存数据用于导出
				$db -> prepareExecute("INSERT INTO eo_api_store_api_cache (eo_api_store_api_cache.productID,eo_api_store_api_cache.apiID,eo_api_store_api_cache.apiJson) VALUES (?,?,?);", array(
					$productID,
					$apiID,
					$cacheJson,
				));
				if ($db -> getAffectRow() < 1)
				{
					throw new \PDOException("addApiCache error");
				}
				$db -> prepareExecute('UPDATE eo_api_store_product SET eo_api_store_product.apiSum = eo_api_store_product.apiSum+1 WHERE eo_api_store_product.productID = ? ', array($productID));
				$db -> commit();
				$result['apiID'] = $apiID;
				$result['productID'] = $productID;
				return $result;
			}
			else
			{
				throw new \PDOException("addApi error");
				return FALSE;
			}
		}
		catch(\PDOException $e)
		{
			$db -> rollBack();
			return FALSE;
		}
	}

	/**
	 * 修改api
	 */
	public function editApi(&$apiID, &$apiName, &$apiDesc, &$apiProtocol, &$requestType, &$targetRequestType, &$targetURL, &$requestURL, &$apiSuccessMock, &$apiFailureMock, &$apiRequestParam, &$apiResultParam, &$apiRequestRaw, &$apiRequestParamType, &$updateTime, &$cacheJson)
	{
		$db = getDatabase();
		try
		{
			$db -> beginTransaction();
			$db -> prepareExecute('UPDATE eo_api_store_api SET eo_api_store_api.apiName = ?,eo_api_store_api.apiDesc = ?,eo_api_store_api.apiProtocol = ? , eo_api_store_api.requestType = ?, eo_api_store_api.targetRequestType = ?,eo_api_store_api.apiSuccessMock = ?,eo_api_store_api.apiFailureMock = ?,eo_api_store_api.updateTime = ?,eo_api_store_api.targetURL = ?,eo_api_store_api.apiRequestRaw = ?, eo_api_store_api.requestURL = ? ,eo_api_store_api.apiRequestParamType = ? WHERE eo_api_store_api.apiID = ?;', array(
				$apiName,
				$apiDesc,
				$apiProtocol,
				$requestType,
				$targetRequestType,
				$apiSuccessMock,
				$apiFailureMock,
				$updateTime,
				$targetURL,
				$apiRequestRaw,
				$requestURL,
				$apiRequestParamType,
				$apiID
			));
			
			if ($db -> getAffectRow() < 1)
				throw new \PDOException("edit Api error");
			$db -> prepareExecuteAll('DELETE FROM eo_api_store_request_param WHERE eo_api_store_request_param.apiID = ?;', array($apiID));
			$db -> prepareExecuteAll('DELETE FROM eo_api_store_result_param WHERE eo_api_store_result_param.apiID = ?;', array($apiID));
			//插入api请求值信息
			if (!empty($apiRequestParam[0]))
				foreach ($apiRequestParam as $param)
				{
					$db -> prepareExecute('INSERT INTO eo_api_store_request_param (eo_api_store_request_param.apiID,eo_api_store_request_param.paramName,eo_api_store_request_param.paramKey,eo_api_store_request_param.paramValue,eo_api_store_request_param.paramNotNull,eo_api_store_request_param.paramType) VALUES (?,?,?,?,?,?);', array(
						$apiID,
						$param['paramName'],
						$param['paramKey'],
						$param['paramValue'],
						$param['paramNotNull'],
						$param['paramType']
					));

					if ($db -> getAffectRow() < 1)
						throw new \PDOException("addRequestParam error");

					$paramID = $db -> getLastInsertID();
					if (!empty($param['paramValueList'][0]))
						foreach ($param['paramValueList'] as $value)
						{

							$db -> prepareExecute('INSERT INTO eo_conn_store_request_value (eo_conn_store_request_value.paramID,eo_conn_store_request_value.value,eo_conn_store_request_value.valueDesc) VALUES (?,?,?);', array(
								$paramID,
								$value['value'],
								$value['valueDesc']
							));

							if ($db -> getAffectRow() < 1)
								throw new \PDOException("addApi error");

						}
					;
				}
			;

			//插入api返回值信息
			if (!empty($apiResultParam[0]))
				foreach ($apiResultParam as $param)
				{
					$db -> prepareExecute('INSERT INTO eo_api_store_result_param (eo_api_store_result_param.apiID,eo_api_store_result_param.paramName,eo_api_store_result_param.paramDesc) VALUES (?,?,?);', array(
						$apiID,
						$param['paramName'],
						$param['paramDesc'],
					));
					if ($db -> getAffectRow() < 1)
						throw new \PDOException("addResultParam error");

					$paramID = $db -> getLastInsertID();
					if (!empty($param['paramValueList'][0]))
						foreach ($param['paramValueList'] as $value)
						{
							$db -> prepareExecute('INSERT INTO eo_conn_store_result_value (eo_conn_store_result_value.resultID,eo_conn_store_result_value.value,eo_conn_store_result_value.valueDesc) VALUES (?,?,?);', array(
								$paramID,
								$value['value'],
								$value['valueDesc']
							));
							if ($db -> getAffectRow() < 1)
								throw new \PDOException("addApi error");

						}
					;

				}
			;

			$productID = $db -> prepareExecute('SELECT eo_api_store_api_cache.productID FROM eo_api_store_api_cache WHERE eo_api_store_api_cache.apiID = ?', array($apiID));
			$db -> prepareExecuteAll('DELETE FROM eo_api_store_api_cache WHERE eo_api_store_api_cache.apiID = ?;', array($apiID));

			//插入api缓存数据用于导出
			$db -> prepareExecute("INSERT INTO eo_api_store_api_cache (eo_api_store_api_cache.productID,eo_api_store_api_cache.apiID,eo_api_store_api_cache.apiJson) VALUES (?,?,?);", array(
				$productID['productID'],
				$apiID,
				$cacheJson,
			));

			if ($db -> getAffectRow() < 1)
			{
				throw new \PDOException("addApiCache error");
			}
			$db -> commit();
			$result['apiID'] = $apiID;
			return $result;

		}
		catch(\PDOException $e)
		{
			$db -> rollBack();
			return FALSE;
		}
	}

	/**
	 * 彻底删除api
	 * @param $apiID 接口ID
	 */
	public function deleteApi(&$apiID)
	{
		$db = getDatabase();
		try
		{

			$db -> beginTransaction();

			$db -> prepareExecute('DELETE FROM eo_api_store_api WHERE eo_api_store_api.apiID = ? ;', array($apiID));
			if ($db -> getAffectRow() < 1)
				throw new \PDOException("deleteApi error");
			$db -> prepareExecute('DELETE FROM eo_api_store_request_param WHERE eo_api_store_request_param.apiID = ?;', array($apiID));
			$db -> prepareExecute('DELETE FROM eo_api_store_result_param WHERE eo_api_store_result_param.apiID = ?;', array($apiID));
			$db -> prepareExecuteAll('DELETE FROM eo_api_store_api_cache WHERE eo_api_store_api_cache.apiID = ?;', array($apiID));
			$db -> commit();
			return TRUE;
		}
		catch(\PDOException $e)
		{
			$db -> rollBack();
			return FALSE;
		}
	}

	/**
	 * 获取api列表（按更新时间排序)
	 */
	public function getApiListByUpdateTime(&$productID)
	{

		$db = getDatabase();
		$result = $db -> prepareExecuteAll('SELECT eo_api_store_api.* FROM eo_api_store_api WHERE eo_api_store_api.productID = ? ORDER BY eo_api_store_api.updateTime ASC;', array($productID));

		if ($db -> getAffectRow() > 0)
		{
			foreach ($result as &$param)
			{
				$requestParamList = $db -> prepareExecuteAll('SELECT eo_api_store_request_param.* FROM eo_api_store_request_param WHERE eo_api_store_request_param.apiID = ? ;;', array($param['apiID']));
				foreach ($requestParamList as &$requestParam)
				{
					$requestValueList = $db -> prepareExecuteAll('SELECT eo_conn_store_request_value.* FROM eo_conn_store_request_value WHERE eo_conn_store_request_value.paramID = ? ', array($requestParam['paramID']));
					$requestParam['requestValueList'] = $requestValueList;
				}

				$resultParamList = $db -> prepareExecuteAll('SELECT eo_api_store_result_param.* FROM eo_api_store_result_param WHERE eo_api_store_result_param.apiID = ?;', array($param['apiID']));
				foreach ($resultParamList as &$resultParam)
				{
					$resultValueList = $db -> prepareExecuteAll('SELECT eo_conn_store_result_value.* FROM eo_conn_store_result_value WHERE eo_conn_store_result_value.resultID = ? ', array($resultParam['paramID']));
					$resultParam['resultValueList'] = $resultValueList;
				}

				$param['requestParamList'] = $requestParamList;
				$param['resultParamList'] = $resultParamList;
			}
			return $result;
		}
		else
		{
			return FAlSE;
		}

	}

	/**
	 * 获取api列表
	 */
	public function getApiList(&$productID)
	{
		$db = getDatabase();
		$result = $db -> prepareExecuteAll('SELECT eo_api_store_api.apiID,eo_api_store_api.apiName FROM eo_api_store_api WHERE eo_api_store_api.productID = ? ORDER BY eo_api_store_api.updateTime ASC;', array($productID));
		if ($db -> getAffectRow() > 0)
		{
			return $result;
		}
		else
		{
			return FAlSE;
		}
	}

	/**
	 * 获取api信息
	 */
	public function getApiInfo(&$apiID)
	{
		$db = getDatabase();

		$requestParamList = $db -> prepareExecuteAll('SELECT eo_api_store_request_param.* FROM eo_api_store_request_param WHERE eo_api_store_request_param.apiID = ? ;;', array($apiID));
		foreach ($requestParamList as &$requestParam)
		{
			$requestValueList = $db -> prepareExecuteAll('SELECT eo_conn_store_request_value.* FROM eo_conn_store_request_value WHERE eo_conn_store_request_value.paramID = ? ', array($requestParam['paramID']));
			$requestParam['requestValueList'] = $requestValueList;
		}

		$resultParamList = $db -> prepareExecuteAll('SELECT eo_api_store_result_param.* FROM eo_api_store_result_param WHERE eo_api_store_result_param.apiID = ?;', array($apiID));
		foreach ($resultParamList as &$resultParam)
		{
			$resultValueList = $db -> prepareExecuteAll('SELECT eo_conn_store_result_value.* FROM eo_conn_store_result_value WHERE eo_conn_store_result_value.resultID = ? ', array($resultParam['paramID']));
			$resultParam['resultValueList'] = $resultValueList;
		}

		$result['requestParamList'] = $requestParamList;
		$result['resultParamList'] = $resultParamList;
		if ($result)
			return $result;
		else
			return FALSE;
	}

	/**
	 * 获取缓存中的信息
	 */
	public function getApifoByCache(&$apiID)
	{
		$db = getDatabase();
		$apiInfo = $db -> prepareExecute('SELECT eo_api_store_api_cache.* FROM  eo_api_store_api_cache WHERE eo_api_store_api_cache.apiID = ?;', array($apiID));
		$apiJson = json_decode($apiInfo['apiJson'], TRUE);
		$apiJson['baseInfo']['apiID'] = $apiInfo['apiID'];
		$apiJson['baseInfo']['productID'] = $apiInfo['productID'];
		if ($db -> getAffectRow() > 0)
		{
			return $apiJson;
		}
		else
		{
			return FAlSE;
		}
	}

	/**
	 * 地址查重
	 */
	public function cnkiURL(&$urlType, &$url)
	{
		$db = getDatabase();
		$urlName = '';
		if ($urlType == 1)
		{
			$urlName = 'requestURL';
		}
		else
			$urlName = 'targetURL';
		$db -> prepareExecute('SELECT eo_api_store_api.* FROM  eo_api_store_api WHERE eo_api_store_api.' . $urlName . ' = ?', array($url));
		if ($db -> getAffectRow() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}
?>