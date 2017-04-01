<?php
/**
 * @update:2017/3/19
 * @author:AspireL
 */

class GuestController
{
	private $returnJson = array('type' => 'guest');
	
	/**
	 * 登录
	 */
	public function login()
	{
		$loginName = securelyInput('loginName');
		$loginPassword = securelyInput('loginPassword');
		$server = new GuestModule;
		if (preg_match('/^[0-9a-zA-Z]{32}$/', $loginPassword))
		{
			if (preg_match('/^[a-zA-Z][0-9a-zA-Z_]{3,15}$/', $loginName))
			{
				$result = $server -> login($loginName, $loginPassword);

				if ($result)
				{
					$this -> returnJson['statusCode'] = '000000';
					$this -> returnJson['adminID'] = $_SESSION['adminID'];
 				}
				else
					$this -> returnJson['statusCode'] = '120004';
			}
			else
			{
				$this -> returnJson['statusCode'] = '120001';
			}
		}
		else
		{
			//密码非法
			$this -> returnJson['statusCode'] = '120002';
		}
		exitOutput(json_encode($this -> returnJson));
	}
}

?>