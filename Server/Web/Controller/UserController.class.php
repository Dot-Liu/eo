<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class UserController
{
	// 返回json类型
	private $returnJson = array('type' => 'user');

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
	 * 退出登录
	 */
	public function logout()
	{
		@session_start();
		@session_destroy();
		$this -> returnJson['statusCode'] = '000000';
		exitOutput(json_encode($this -> returnJson));
	}

}
?>