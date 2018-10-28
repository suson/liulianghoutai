<?php

 ################################################################################
 # Date: 2011-03-31																#
 # Author: Mr.Jackin															#
 # Description: Define the methods that the class CUrl.class must implement		#
 ################################################################################

interface IUrl {
	/*
	 * 定义函数 addURL 
	 * 添加网址, 参数$url 是包含以下成员的数组： name, url, tid(目标网址)
	 * 成功返回 网址详细信息  失败返回false
	 */
	public function addURL( array $url);
	
	/*
	 * 定义函数 cloneURL
	 * 克隆网址设置, 参数$url 是包含以下成员的数组: srcurlid, descurlids=array()
	 * 成功返回true, 失败返回false
	 */
	public function cloneURL( array $url);
	
	/*
	 * 定义函数 delURL
	 * 删除网址, 参数$urlid 是网址编号
	 * 成功返回true, 失败返回false
	 */
	public function delURL( int $urlid);
	
	/*
	 *  定义函数 setFree
	 *  设置分享, 参数$free 是包含以下成员的数组：free, urlid
	 *  成功返回true, 失败返回false
	 */
	public function setFree( array $free);
	
	/*
	 * 定义函数 setFlowLine 
	 * 流量曲线控制, 参数$url 是包含以下成员的数组： urlid, cid, hrs=array() 
	 * 成功返回true, 失败返回 false
	 */
	public function setFlowLine( array $url);
	
	/*
	 * 定义函数 getFlowLine
	 * 获取曲线控制信息, 参数$urlid 
	 * 成功返回hrs 信息(在表Jackin_flow),  失败返回false.
	 */
	public function getFlowLine( int $urlid);
}
?>