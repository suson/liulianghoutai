<?php
 ################################################################################
 # Date: 2011-03-30																#
 # Author: Mr.Jackin															#
 # Description: Define the methods that the class CUser.class must implement	#
 ################################################################################
 
interface IUser{
	/*
	 * 定义函数 regist 
	 * 用户注册函数 ,参数  $info是个必须包含以下成员的数组: name, pwd, email, question, answer, cc(验证码)
	 * 	如果验证码正确且用户名不存在则 将answer和 pwd 用MD5加密, 将 email 转换为小写再将 $info 保存予数据库
	 * 	成功返回true, 失败返回false
	 */
	public function regist($info=array());
	
	/*
	 * 定义函数 resetPWD
	 * 密码重置, 参数$info 是个必须包含以下成员的数组: name, pwd, email, question, answer, cc(验证码)
	 * 如果验证码正确且用户名存在 且answer正确
	 * 成功返回true, 失败返回false
	 */
	public function resetPWD($info=array());
	
	/*
	 * 定义函数getQuestion
	 * 获取密码保护问题   参数$info 是个必须包含email, name 的数组
	 * 如果name和email存在 则返回 密码保护问题, 否则返回 fasle
	 */
	public function getQuestion($info=array());
	
	/*
	 * 定义函数 modPWD
	 * 修改密码, 参数$info 是个必须包含以下成员的数组: newpwd, oldpwd, cc(验证码)
	 * 成功返回true, 失败返回false
	 */
	public function modPWD($info=array());
	
	/*
	 * 定义函数 login 
	 * 用户登入, 参数 $info 是个必须包含以下成员的数组:name, chkpwd
	 * 成功返回 true 且将用户的详细信息保存予$_SESSION中, 失败返回 false
	 */
	public function login($info=array());
	
	/*
	 * 定义函数 nameExist
	 * 检查name 是否在数据库中存在
	 * 存在返回true, 不存在返回false
	 */
	public function nameExist($name);
	
	/*
	 * 定义函数 logout
	 * 注销登入   销毁当前 $_SESSION=array()
	 */
	public function logout();
}
?>