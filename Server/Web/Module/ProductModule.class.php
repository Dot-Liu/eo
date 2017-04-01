<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class ProductModule
{
	public function __construct()
	{
		@session_start();
	}

	/**
	 * 新建产品
	 */
	public function addProduct(&$productName, &$productIntro, &$productType, &$productDesc, &$sceneID, &$productLogoID, &$chargeList)
	{
		$dao = new ProductDao;
		return $dao -> addProduct($productName, $productIntro, $productType, $productDesc, $sceneID, $productLogoID, $chargeList);
	}

	/**
	 * 编辑产品
	 */
	public function editProduct(&$productID, &$productName, &$productIntro, &$productType, &$productDesc, &$sceneID, &$productLogoID, &$chargeList)
	{
		$dao = new ProductDao;
		return $dao -> editProduct($productID, $productName, $productIntro, $productType, $productDesc, $sceneID, $productLogoID, $chargeList);
	}

	/**
	 * 删除产品
	 */
	public function deleteProduct(&$productID)
	{
		$dao = new ProductDao;
		return $dao -> deleteProduct($productID);
	}

	/**
	 * 编辑产品
	 */
	public function productOperation(&$productID, &$operateType)
	{
		$dao = new ProductDao;
		return $dao -> productOperation($productID, $operateType);
	}

	/**
	 * 获取分类
	 */
	public function getType()
	{
		$dao = new ProductDao;
		return $dao -> getType();
	}

	/**
	 * 获取产品列表
	 */
	public function getProductList()
	{
		$dao = new ProductDao;
		return $dao -> getProductList();
	}

	/**
	 * 获取产品信息
	 */
	public function getProductInfo(&$productID)
	{
		$dao = new ProductDao;
		return $dao -> getProductInfo($productID);
	}

	/**
	 * 获取logo列表
	 */
	public function getLogoList()
	{
		$dao = new ProductDao;
		return $dao -> getLogoList();
	}

}
?>