<?php
/*
 *主模块
 */
	session_start();
	
	require_once './db.php';
	require_once './alipay/alipayto.php';
	require_once "./alipay/myAlipaycfg.php";
	
	$f=$_POST['f'];
	if(isset($_POST['i'])){
		$x=stripcslashes($_POST['i']);
		$i= json_decode($x);
	}
	
	if(isset($_SESSION['userid'])){
		$reg=new DB($_SESSION['userid']);
	}else if($f<7){
		$reg=new DB(0);
	}else{
		die('{"error":-1}');
	}
	
	$response=array();
	switch ($f){
		case 1:
			//获取验证码
			if(isset($_POST['i'])){
				$len=$i->clen;
			}else{
				$len=6;
			} 
			$response=$reg->loadImageCheck($len);
			$_SESSION['v']=$response['ccmd5'];
			$response['ccmd5']=md5(strtoupper($_SESSION['v']));
			break;
		
		case 2:
			//注册新用户
			$cc=strtoupper($i->cc);
			$cc=md5($cc);
			if($cc==md5($_SESSION['v'])){
				$response=$reg->regNewUser($i->name,$i->pwd,$i->email,$i->question,$i->answer);
				$response['i']=$_POST['i'];
			}else{
				$response["error"]=-1;
				$response['i']=$_POST['i'];
			}
			break;
		
		case 3:
			//查询用户是否存在
			$response=$reg->checkUserExist($i->name);
			break;
		
		case 4:
			//用户登入
			
			$response=$reg->userLogin($i->name,$i->chkpwd);
			$_SESSION['userid']=$response['userid'];
			break;
		
		case 5:
			//获取密码保护问题
			$response=$reg->getQuestion($i->name,$i->email);
			break;
		case 6:
			//密码重置
			$cc=strtoupper($i->cc);
			$cc=md5($cc);
			if($cc==md5($_SESSION['v'])){
				$response=$reg->resetPwd($i->name,$i->pwd,$i->email,$i->answer,$i->question);
			}else{
				$response=array("error"=>-2,);
			}
			break;
		case 7:
			//加载用户信息
			if(!isset($_SESSION['userid'])){
				die('{"error":-1}');
			}
			$response=$reg->loadUserInfo();	
			$_SESSION['level']=$response['level'];
			break;
		
		//注销登入
		case 8:
			unset($_SESSION['userid']);
			$response['error']=0;
			break;
		case 9:
			//修改密码
			$cc=strtoupper($i->cc);
			$cc=md5($cc);
			if($cc == md5($_SESSION['v'])){
				$response=$reg->modPassword($i->newpwd,$i->oldpwd);
			}else{
				$response['error']=-1;
			}
			break;
		case 18:
			//获取充值信息
			//返回 aplink
			$response=$reg->getMoney($i->cent);
			$alipay=alipayto();
			$response['error']=$alipay['error'];
			$response['aplink']=$alipay['url'];
			break;
		
		case 20:
			//加载URL信息
			$response=$reg->loadUrlInfo();	
			break;
		
		case 21:
			
			$response=$reg->addUrlOnline($i->url,$i->name,$i->tid, $_SESSION['level']);		
			break;
		case 22:
			$response=$reg->setOptimize($i->urlid,$i->usepop,$i->turl,$i->useturl,$i->furls,$i->usefurl);			
			break;
		
		case 23:
			$response= $reg->doDelect($i->urlid);			
			break;
		
		case 24:
			$response= $reg->controlUrlOnline($i->free,$i->urlid);	
			break;
		
		case 25:
			$response= $reg->setFlowLine($i->urlid, $i->cid, $i->hrs);
			break;
			
		case 26:
			$response= $reg->getControUrl($i->urlid);
			break;
		
		case 27:
			$response=$reg->cloneSetInfo($i->srcurlid, $i->descurlids[0]);
			
			break;
		
		case 31:
			$response=$reg->calculationPay($i->day,$i->nowpay,$i->odrid,$i->etime,$i->svcid);
			break;
		
		case 32:
			$response=$reg->startOptimize($i->urlid,$i->odrid,$i->etime,$i->day,$i->nowpay,$i->svcid);
			break;
		
		case 33:
			$response=$reg->trustReback($i->urlid,$i->odrid,$i->svcid);
			break;
		
		case 34:
			//获取用户的充值记录
			$response=$reg->userRecord($i->page,$i->pagect);
			break;
	
	}
	header('content="text/html; charset=utf-8"');
	echo json_encode($response);
?>
