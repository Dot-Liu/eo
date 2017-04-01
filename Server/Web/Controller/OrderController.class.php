<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class OrderController
{
	// 返回json类型
	private $returnJson = array('type' => 'order');

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
	 * 获取订单列表
	 */
	public function getOrderList()
	{
		$server = new OrderModule;
		$result = $server -> getOrderList();
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['orderList'] = $result;
		}
		else
		{
			$this -> returnJson['statusCode'] = '180001';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 添加订单
	 */
	public function addOrder()
	{
		$productID = securelyInput('productID');
		$income = securelyInput('income');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $income))
		{
			$this -> returnJson['statusCode'] = '180002';
		}
		else
		{
			$server = new OrderModule;
			$result = $server -> addOrder($productID, $income);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['ID'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '180003';
			}
		}
		exitOutput($this -> returnJson);

	}

}
?>