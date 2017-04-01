<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class ProductDao
{
	/**
	 * 新建产品
	 */
	public function addProduct(&$productName, &$productIntro, &$productType, &$productDesc, &$sceneID, &$productLogoID, &$chargeList)
	{
		$db = getDatabase();
		$tableName = 'eo_api_store_product';
		$paramName = 'productID';
		$productID = $this -> getRandomKey($tableName, $paramName);
		$applyTime = date('Y-m-d H:i:s', time());

		if (!$productID)
		{
			return FALSE;
		}
		else
		{
			$db -> beginTransaction();

			foreach ($chargeList as $param)
			{
				$db -> prepareExecute('INSERT INTO eo_api_store_charge (eo_api_store_charge.productID,eo_api_store_charge.chargeType,eo_api_store_charge.chargeAmount,eo_api_store_charge.invokeCount) VALUES (?,?,?,?);', array(
					$productID,
					$param['chargeType'],
					$param['chargeAmount'],
					$param['invokeCount']
				));
			}
			$db -> prepareExecute('INSERT INTO eo_api_store_product (eo_api_store_product.productID,eo_api_store_product.productName,eo_api_store_product.productIntro,eo_api_store_product.productTypeID,eo_api_store_product.productDesc,eo_api_store_product.sceneID,eo_api_store_product.productLogoID,eo_api_store_product.applyTime,eo_api_store_product.applyStatus) VALUES (?,?,?,?,?,?,?,?,1);', array(
				$productID,
				$productName,
				$productIntro,
				$productType,
				$productDesc,
				$sceneID,
				$productLogoID,
				$applyTime
			));
			if ($db -> getAffectRow() > 0)
			{
				$db -> commit();
				return $productID;
			}
			else
			{
				$db -> rollback();
				return FALSE;
			}
		}

	}

	/**
	 * 编辑产品
	 */
	public function editProduct(&$productID, &$productName, &$productIntro, &$productType, &$productDesc, &$sceneID, &$productLogoID, &$chargeList)
	{
		$db = getDatabase();
		$db -> beginTransaction();
		$db -> prepareExecute('UPDATE eo_api_store_product SET eo_api_store_product.productName = ?,eo_api_store_product.productIntro = ?,eo_api_store_product.productTypeID = ?,eo_api_store_product.productDesc = ?,eo_api_store_product.sceneID = ?,eo_api_store_product.productLogoID = ? WHERE eo_api_store_product.productID = ? ;', array(
			$productName,
			$productIntro,
			$productType,
			$productDesc,
			$sceneID,
			$productLogoID,
			$productID
		));
		$db -> prepareExecuteAll('DELETE FROM eo_api_store_charge WHERE eo_api_store_charge.productID = ?;', array($productID));
		foreach ($chargeList as $param)
		{
			$db -> prepareExecute('INSERT INTO eo_api_store_charge (eo_api_store_charge.productID,eo_api_store_charge.chargeType,eo_api_store_charge.chargeAmount,eo_api_store_charge.invokeCount) VALUES (?,?,?,?);', array(
				$productID,
				$param['chargeType'],
				$param['chargeAmount'],
				$param['invokeCount']
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
	 * 删除产品
	 */
	public function deleteProduct(&$productID)
	{
		$db = getDatabase();
		$db -> prepareExecute('DELETE FROM eo_api_store_product WHERE eo_api_store_product.productID = ? ;', array($productID));

		if ($db -> getAffectRow() > 0)
		{
			return TRUE;
		}
		else
			return FALSE;
	}

	/**
	 * 获取产品列表
	 */
	public function getProductList()
	{
		$db = getDatabase();
		$result = $db -> prepareExecuteAll('SELECT eo_api_store_product.productID,eo_api_store_product.productName,eo_api_store_product.totalIncome,eo_api_store_product.applyTime,eo_api_store_product.productStatus FROM eo_api_store_product ORDER BY eo_api_store_product.productStatus,eo_api_store_product.applyTime DESC;');
		if ($db -> getAffectRow() > 0)
		{
			return $result;
		}
		else
			return FALSE;
	}

	/**
	 * 上架
	 */
	public function productOperation(&$productID, &$operateType)
	{
		$db = getDatabase();
		$db -> prepareExecute('UPDATE eo_api_store_product SET eo_api_store_product.productStatus = ? WHERE eo_api_store_product.productID = ?;', array(
			$operateType,
			$productID
		));
		if ($db -> getAffectRow() > 0)
		{
			return TRUE;
		}
		else
			return FALSE;
	}

	/**
	 * 获取产品信息
	 */
	public function getProductInfo(&$productID)
	{
		$db = getDatabase();

		$re = $db -> prepareExecute('SELECT eo_api_store_product_type.typeName AS sceneName FROM eo_api_store_product_type INNER JOIN eo_api_store_product ON eo_api_store_product.sceneID = eo_api_store_product_type.typeID WHERE eo_api_store_product.productID = ?;', array($productID));
		$result = $db -> prepareExecute('SELECT eo_api_store_product.*,eo_api_store_product_type.typeName AS productTypeName ,eo_api_store_product_logo.logoURL FROM eo_api_store_product LEFT JOIN eo_api_store_product_type ON eo_api_store_product.productTypeID = eo_api_store_product_type.typeID LEFT JOIN eo_api_store_product_logo ON eo_api_store_product_logo.logoID = eo_api_store_product.productLogoID WHERE eo_api_store_product.productID = ?;', array($productID));
		$result['sceneName'] = $re['sceneName'];
		if ($db -> getAffectRow() > 0)
		{
			$res = $db -> prepareExecuteAll('SELECT eo_api_store_charge.chargeType,eo_api_store_charge.chargeAmount,eo_api_store_charge.invokeCount FROM eo_api_store_charge WHERE eo_api_store_charge.productID = ? ;', array($productID));
			$result['chargeList'] = $res;
			return $result;
		}
		else
			return FALSE;
	}

	/**
	 * 获取分类
	 */
	public function getType()
	{
		$db = getDatabase();
		$result = $db -> prepareExecuteAll('SELECT eo_api_store_product_type.typeID AS productTypeID,eo_api_store_product_type.typeName AS productTypeName FROM eo_api_store_product_type WHERE eo_api_store_product_type.isChild = 0');
		if ($db -> getAffectRow() > 0)
		{
			foreach ($result as &$param)
			{
				$res = $db -> prepareExecuteAll('SELECT eo_api_store_product_type.typeID AS sceneID,eo_api_store_product_type.typeName AS sceneName FROM eo_api_store_product_type WHERE eo_api_store_product_type.parentTypeID = ? AND eo_api_store_product_type.isChild = 1', array($param[productTypeID]));
				$param['sceneList'] = $res;
			}
			return $result;
		}
		else
			return FALSE;
	}

	/**
	 * 随机生成Key
	 */
	public function getRandomKey(&$tableName, &$paramName)
	{
		$db = getDatabase();
		do
		{
			$count++;
			//获取随机16位字符串
			$randomID = '';
			$strPool = 'qwertyuipasdfghjklzxcvbnmZXCVBNMASDFGHJKLQWERTYUIP123456789';
			for ($i = 0; $i <= 15; $i++)
			{
				$productID .= $strPool[rand(0, 58)];
			}
			//查重
			$result = $db -> prepareExecute('SELECT ' . $tableName . '.* FROM ' . $tableName . ' WHERE ' . $tableName . '.' . $paramName . ' = ?', array($productID));
		}
		while(($db -> getAffectRow() >0) &&$count < 5);

		if ($db -> getAffectRow() > 0)
		{
			return FALSE;
		}
		else
			return $productID;
	}

	/**
	 * 获取logo列表
	 */
	public function getLogoList()
	{
		$db = getDatabase();

		$result = $db -> prepareExecuteAll('SELECT eo_api_store_product_logo.* FROM eo_api_store_product_logo ;');
		if ($result)
		{
			return $result;
		}
		else
			return FALSE;
	}

}
?>