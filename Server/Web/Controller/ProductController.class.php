<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class ProductController
{
	// 返回json类型
	private $returnJson = array('type' => 'product');

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
	 * 新建产品
	 */
	public function addProduct()
	{
		$productName = securelyInput('productName');
		$productNameLen = mb_strlen(quickInput('productName'), 'utf8');
		$productIntro = securelyInput('productIntro');
		$productTypeID = securelyInput('productTypeID');
		$productDesc = securelyInput('productDesc');
		$sceneID = securelyInput('sceneID');
		$productLogoID = securelyInput('productLogoID');
		$chargeList = json_decode($_POST['chargeList'], TRUE);
		if ($productNameLen > 30 || $productNameLen < 1)
		{
			$this -> returnJson['statusCode'] = '130002';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $productTypeID))
		{
			$this -> returnJson['statusCode'] = '130003';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $sceneID))
		{
			$this -> returnJson['statusCode'] = '130004';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $productLogoID))
		{
			$this -> returnJson['statusCode'] = '130005';
		}
		else
		{
			$server = new ProductModule;
			$result = $server -> addProduct($productName, $productIntro, $productTypeID, $productDesc, $sceneID, $productLogoID, $chargeList);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['productID'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '130006';
				$this -> returnJson['productID'] = $result;

			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 编辑产品
	 */
	public function editProduct()
	{
		$productID = securelyInput('productID');
		$productName = securelyInput('productName');
		$productNameLen = mb_strlen(quickInput('productName'), 'utf8');
		$productIntro = securelyInput('productIntro');
		$productType = securelyInput('productTypeID');
		$productDesc = securelyInput('productDesc');
		$sceneID = securelyInput('sceneID');
		$productLogoID = securelyInput('productLogoID');
		$chargeList = json_decode($_POST['chargeList'], TRUE);
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		if ($productNameLen > 30 || $productNameLen < 1)
		{
			$this -> returnJson['statusCode'] = '130002';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $productType))
		{
			$this -> returnJson['statusCode'] = '130003';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $sceneID))
		{
			$this -> returnJson['statusCode'] = '130004';
		}
		else
		if (!preg_match('/^[0-9]{1,11}$/', $productLogoID))
		{
			$this -> returnJson['statusCode'] = '130005';
		}
		else
		{
			$server = new ProductModule;
			$result = $server -> editProduct($productID, $productName, $productIntro, $productType, $productDesc, $sceneID, $productLogoID, $chargeList);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
				$this -> returnJson['statusCode'] = '130006';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 删除产品
	 */
	public function deleteProduct()
	{
		$productID = securelyInput('productID');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		{
			$server = new ProductModule;
			$result = $server -> deleteProduct($productID);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '130007';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取产品列表
	 */
	public function getProductList()
	{
		$server = new ProductModule;
		$result = $server -> getProductList($productID);
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['productList'] = $result;
		}
		else
		{
			$this -> returnJson['statusCode'] = '130008';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 上下架操作
	 */
	public function productOperation()
	{
		$productID = securelyInput('productID');
		$operateType = securelyInput('operateType');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		if (!preg_match('/^[0-9]{1}$/', $operateType))
		{
			$this -> returnJson['statusCode'] = '1300012';
		}
		else
		{
			$server = new ProductModule;
			$result = $server -> productOperation($productID, $operateType);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '130013';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取产品信息
	 */
	public function getProductInfo()
	{
		$productID = securelyInput('productID');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		{
			$server = new ProductModule;
			$result = $server -> getProductInfo($productID);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['productInfo'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '130009';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取logo列表
	 */
	public function getLogoList()
	{
		$server = new ProductModule;
		$result = $server -> getLogoList();
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['logoList'] = $result;
		}
		else
		{
			$this -> returnJson['statusCode'] = '130010';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取chanpin 分类列表
	 */
	public function getTypeList()
	{
		$server = new ProductModule;
		$result = $server -> getType();
		if ($result)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['typeList'] = $result;
		}
		else
		{
			$this -> returnJson['statusCode'] = '130011';
		}
		exitOutput($this -> returnJson);
	}

}
?>