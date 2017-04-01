<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class OrderDao
{
	/**
	 * 获取订单列表
	 */
	public function getOrderList()
	{
		$db = getDatabase();
		$result = $db -> prepareExecuteAll('SELECT DISTINCT eo_api_store_order.*,eo_api_store_product.productName FROM eo_api_store_order INNER JOIN eo_api_store_product ON eo_api_store_product.productID = eo_api_store_product.productID;');
		if ($db -> getAffectRow() > 0)
		{
			return $result;
		}
		else
			return FALSE;
	}

	/**
	 * 添加订单
	 */
	public function addOrder(&$productID, &$income)
	{
		$server = new ProductDao;
		$createTime = date('Y-m-d H:i:s', time());
		$tableName = 'eo_api_store_order';
		$paramName = 'orderID';
		$orderID = $server -> getRandomKey($tableName, $paramName);
		$db = getDatabase();
		$db -> prepareExecute('INSERT INTO eo_api_store_order (eo_api_store_order.orderID,eo_api_store_order.productID,eo_api_store_order.income,eo_api_store_order.creatTime,eo_api_store_order.companyID,eo_api_store_order.userID) VALUES (?,?,?,?,0,0);', array(
			$orderID,
			$productID,
			$income,
			$createTime
		));
		if ($db -> getAffectRow() > 0)
		{
			return TRUE;
		}
		else
			return FALSE;
	}

}
?>