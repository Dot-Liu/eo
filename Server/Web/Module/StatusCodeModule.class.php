<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class StatusCodeModule
{
	public function __construct()
	{
		@session_start();
	}

	/**
	 * 添加状态码
	 */
	public function addCode(&$productID, &$codeDesc, &$code)
	{
		$dao = new StatusCodeDao;
		return $dao -> addCode($productID, $codeDesc, $code);
	}

	/**
	 * 删除状态码
	 */
	public function deleteCode(&$codeID)
	{
		$dao = new StatusCodeDao;
		return $dao -> deleteCode($codeID);
	}

	/**
	 * 获取状态码列表
	 */
	public function getCodeList(&$productID)
	{
		$dao = new StatusCodeDao;
		return $dao -> getCodeList($productID);
	}

	/**
	 * 修改状态码
	 */
	public function editCode(&$codeID, &$code, &$codeDesc)
	{
		$dao = new StatusCodeDao;
		return $dao -> editCode($codeID, $code, $codeDesc);
	}

}
?>