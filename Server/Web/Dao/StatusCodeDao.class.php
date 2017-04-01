<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class StatusCodeDao
{
	/**
	 * 添加状态码
	 */
	public function addCode(&$productID, &$codeDesc, &$code)
	{
		$db = getDatabase();

		$db -> prepareExecute('INSERT INTO eo_api_store_status_code (eo_api_store_status_code.productID,eo_api_store_status_code.code,eo_api_store_status_code.codeDesc) VALUES (?,?,?);', array(
			$productID,
			$code,
			$codeDesc
		));
		if ($db -> getAffectRow() < 1)
			return FALSE;
		else
			return $db -> getLastInsertID();

	}

	/**
	 * 删除状态码
	 */
	public function deleteCode(&$codeID)
	{
		$db = getDatabase();

		$db -> prepareExecute('DELETE FROM eo_api_store_status_code WHERE eo_api_store_status_code.codeID = ?;', array($codeID));

		if ($db -> getAffectRow() < 1)
			return FALSE;
		else
			return TRUE;
	}

	/**
	 * 获取状态码列表
	 */
	public function getCodeList(&$productID)
	{
		$db = getDatabase();

		$result = $db -> prepareExecuteAll('SELECT eo_api_store_status_code.*,eo_api_store_product.productName FROM eo_api_store_status_code INNER JOIN eo_api_store_product ON eo_api_store_status_code.productID = eo_api_store_product.productID WHERE eo_api_store_status_code.productID = ? ORDER BY eo_api_store_status_code.code ASC;', array($productID));

		if (empty($result))
			return FALSE;
		else
			return $result;
	}

	/**
	 * 修改状态码
	 */
	public function editCode(&$codeID, &$code, &$codeDesc)
	{
		$db = getDatabase();

		$db -> prepareExecute('UPDATE eo_api_store_status_code SET eo_api_store_status_code.code = ? ,eo_api_store_status_code.codeDesc = ? WHERE codeID = ?;', array(
			$code,
			$codeDesc,
			$codeID
		));

		if ($db -> getAffectRow() < 1)
			return FALSE;
		else
			return TRUE;
	}

}
?>