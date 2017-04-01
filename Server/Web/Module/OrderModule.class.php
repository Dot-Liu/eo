<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class OrderModule
{
	/**
	 * 获取订单列表
	 */
	public function getOrderList()
	{
		$dao = new OrderDao;
		return $dao -> getOrderList();
	}

	/**
	 * 添加订单
	 */
	public function addOrder(&$productID, &$income)
	{
		$dao = new OrderDao;
		return $dao -> addOrder($productID, $income);
	}

}
?>