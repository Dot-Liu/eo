<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class VerifyDao
{

	/**
	 * 获取审核列表
	 */
	public function getVerifyList()
	{

		$verifyList['personalList'] = $this -> getVerifyPersonalList();

		$verifyList['appListByPerson'] = $this -> getVerifyAppListByPerson();
		if ($verifyList['personalList'] == FALSE && $verifyList['appListByPerson'] == FALSE)
		{
			return FALSE;
		}
		else
			return $verifyList;

	}
	

	/**
	 * 通过审核申请
	 */
	public function agreeVerifyRequest(&$verifyType, &$isPersonal, &$IDList)
	{
		$db = getDatabase();
		$db -> beginTransaction();
		if ($verifyType == 0)
		{
			$db -> prepareExecute('UPDATE eo_api_store_apply_user SET eo_api_store_apply_user.applyStatus = 1 WHERE eo_api_store_apply_user.userID = ?;', array($IDList['userID']));
		}
		else
		{
			$tableName = 'eo_api_store_product_user';
			$paramName = 'productKey';
			$server = new ProductDao;
			$productKey = $server -> getRandomKey($tableName, $paramName);
			if (!$productKey)
			{
				return FALSE;
			}
			$db -> prepareExecute('UPDATE eo_api_store_product_user SET eo_api_store_product_user.productKey = ?,eo_api_store_product_user.productKeyStatus=0,eo_api_store_product_user.applyStatus = 1 WHERE eo_api_store_product_user.productID = ? AND eo_api_store_product_user.appID = ? AND eo_api_store_product_user.userID = ? AND eo_api_store_product_user.applyStatus = 0;', array(
				$productKey,
				$IDList['productID'],
				$IDList['appID'],
				$IDList['userID']
			));
		}
		if ($db -> getAffectRow() > 0)
		{
			$db -> commit();
			return TRUE;
		}
		else
		{
			$db -> rollback();
			return FALSE;
		}
	}

	/**
	 * 拒绝审核申请
	 */
	public function refuseVerifyRequest(&$verifyType, &$isPersonal, &$IDList)
	{
		$db = getDatabase();
		$db -> beginTransaction();
		if ($verifyType == 0)
		{
			$db -> prepareExecute('UPDATE eo_api_store_apply_user SET eo_api_store_apply_user.applyStatus = 2 WHERE eo_api_store_apply_user.userID = ?;', array($IDList['userID']));
		}
		else
		{
			$db -> prepareExecute('UPDATE eo_api_store_product_user SET eo_api_store_product_user.applyStatus = 2 WHERE eo_api_store_product_user.productID = ? AND eo_api_store_product_user.appID = ? AND eo_api_store_product_user.userID = ? AND eo_api_store_product_user.applyStatus = 0;', array(
				$IDList['productID'],
				$IDList['appID'],
				$IDList['userID']
			));
		}
		if ($db -> getAffectRow() > 0)
		{
			$db -> commit();
			return TRUE;
		}
		else
		{
			$db -> rollback();
			return FALSE;
		}
	}

	/**
	 * 获取资质审核列表(个人)
	 */
	public function getVerifyPersonalList()
	{
		$db = getDatabase();
		$result = $db -> prepareExecuteAll('SELECT eo_api_store_apply_user.* FROM eo_api_store_apply_user ORDER BY eo_api_store_apply_user.applyStatus AND eo_api_store_apply_user.applyTime;');
		if ($db -> getAffectRow() > 0)
		{
			return $result;
		}
		else
			return FALSE;
	}

	/**
	 * 获取应用审核列表（个人）
	 */
	public function getVerifyAppListByPerson()
	{
		$db = getDatabase();
		$result = $db -> prepareExecuteAll('SELECT eo_api_store_product_user.*,eo_api_store_app.*,eo_api_store_product.productName FROM eo_api_store_product_user INNER JOIN eo_api_store_app ON eo_api_store_product_user.appID = eo_api_store_app.appID INNER JOIN eo_api_store_product ON eo_api_store_product.productID = eo_api_store_product_user.productID;');
		if ($db -> getAffectRow() > 0)
		{
			foreach ($result as &$param)
			{
				$platformList = $db -> prepareExecuteAll('SELECT eo_api_store_platform_app.* FROM eo_api_store_platform_app WHERE eo_api_store_platform_app.appID = ?;', array($param['appID']));
				$param['platformList'] = $platformList;
			}
			return $result;
		}
		else
			return FALSE;
	}

}
?>