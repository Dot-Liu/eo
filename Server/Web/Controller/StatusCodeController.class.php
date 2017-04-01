<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class StatusCodeController
{
	// 返回json类型
	private $returnJson = array('type' => 'statusCode');

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
	 * 添加状态码
	 */
	public function addCode()
	{
		$codeLen = mb_strlen(quickInput('code'), 'utf8');
		$codeDescLen = mb_strlen(quickInput('codeDesc'), 'utf8');
		$productID = securelyInput('productID');
		$code = securelyInput('code');
		$codeDesc = securelyInput('codeDesc');
		if (!preg_match('/^[a-np-zA-NP-Z1-9]{16}$/', $productID))
		{
			$this -> returnJson['statusCode'] = '130001';
		}
		elseif (!($codeLen >= 1 && $codeLen <= 255))
		{
			$this -> returnJson['statusCode'] = '190002';
		}
		elseif (!($codeDescLen >= 1 && $codeDescLen <= 255))
		{
			$this -> returnJson['statusCode'] = '190003';
		}
		else
		{
			$service = new StatusCodeModule;
			$result = $service -> addCode($productID, $codeDesc, $code);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '190004';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 删除状态码
	 */
	public function deleteCode()
	{
		$codeID = securelyInput('codeID');

		if (!preg_match('/^[0-9]{1,11}$/', $codeID))
		{
			//状态码ID格式不合法
			$this -> returnJson['statusCode'] = '190001';
		}
		else
		{
			$service = new StatusCodeModule;
			$result = $service -> deleteCode($codeID);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '190006';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取状态码列表
	 */
	public function getCodeList()
	{
		$productID = securelyInput('productID');

		if (!preg_match('/^[a-zA-Z1-9]{16}$/', $productID))
		{
			//项目ID格式不合法
			$this -> returnJson['statusCode'] = '130001';
		}
		else
		{
			$service = new StatusCodeModule;
			$result = $service -> getCodeList($productID);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['codeList'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '190007';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 修改状态码
	 */
	public function editCode()
	{
		$codeLen = mb_strlen(quickInput('code'), 'utf8');
		$codeDescLen = mb_strlen(quickInput('codeDesc'), 'utf8');
		$codeID = securelyInput('codeID');
		$code = securelyInput('code');
		$codeDesc = securelyInput('codeDesc');

		if (!preg_match('/^[0-9]{1,11}$/', $codeID))
		{
			//状态码ID格式非法
			$this -> returnJson['statusCode'] = '190001';
		}
		else
		if (!($codeLen >= 1 && $codeLen <= 255))
		{
			//状态码格式非法
			$this -> returnJson['statusCode'] = '190002';
		}
		else
		if (!($codeDescLen >= 1 && $codeDescLen <= 255))
		{
			//状态码描述格式非法
			$this -> returnJson['statusCode'] = '190003';
		}
		else
		{
			$service = new StatusCodeModule;
			$result = $service -> editCode($codeID, $code, $codeDesc);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				$this -> returnJson['statusCode'] = '190008';
			}
		}
		exitOutput($this -> returnJson);
	}

}
?>