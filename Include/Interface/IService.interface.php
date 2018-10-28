<?php

interface IService{
/*
 * 定义函数 setOptimize
 *  优化设置, 参数$cfg 包含: urlid,url,name,furls,turl,usefurl,useturl,usepop
 *  成功返回true, 失败返回false
 */
	public function setOptimize(array $cfg);
	
/*
 * 定义函数 startService
 *  开通服务, 参数$cfg = (urlid,odrid,etime,day,nowpay,svcid)
 *  失败返回 false, 成功返回 array(odrid,status,dayprice,paymoney,pday,btime,etime,sday,allmoney)
 */
	public function startService(array $cfg);
/*
 * 定义函数 getPayMoney
 *  计算支付费用, $cfg = (day,nowpay,odrid,etime,svcid)
 *  失败返回 false, 成功返回 array(paymoney,pday,btime,etime,allmoney) 
 */
	public function getPayMoney(array $cfg);
	
/*
 * 定义函数 prepay 
 *  充值, $cent 
 *  失败返回 false, 成功返回 array(to_score, to_level, pm, lm, aplink)
 */
	public function prepay(int $cent);

/*
 * 定义函数 getRecord 
 *  获取用户交易记录, 参数 $page= (page, pagect)
 *  失败返回：false, 成功返回: array(pages,paylog=array(otime	otype, svcid, urlid, val, bae, pm, lm))
 */
	public function getRecord(array $page);

/*
 * 定义函数： rebackService
 * 退订服务, 参数 $svc= (urlid	odrid	svcid)
 * 失败返回 false, 成功返回 array(bdelete,	etime, sday)
 */
	public function rebackService(array $svc);
}


























?>