<?php

	session_start();
	require_once '../dbcfg.php';
	
	if(isset($_GET['login']) && $_GET['login'] == 'false'){
		$_SESSION['online']=false;
		unset($_SESSION['admin']);
		unset($_SESSION['online']);
		session_destroy();
		exit(0);
	}
	
if(isset($_SESSION['admin']) && isset($_SESSION['online']) && $_SESSION['online']){
	echo '{"success":true}';
}else{
	if(isset($_POST['user']) && isset($_POST['pwd']) && $_POST['user']=="admin"){
		$user=$_POST['user'];
		$pwd=$_POST['pwd'];
		preg_match('/^([0-9a-zA-z_]+)$/',$user,$match);
		if(0 != count($match)){
			$link=@mysql_connect(HOST,USER,PWD);
			@mysql_select_db(DBNAME,$link);
			$qGetAdminField="name,rtime,ltime,countexp,countrechange,countuser,counturl";
			$q="SELECT $qGetAdminField FROM admins WHERE name='$user' AND pwd='".md5($pwd)."';";
			$result=mysql_query($q,$link);
			if(mysql_num_rows($result) ==1 ){
				//获取 管理员信息
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				echo json_encode(array("success"=>true,
						"name"=>$row['name'],
						"rtime"=>$row['rtime'],
						"ltime"=>$row['ltime'],
						"countexp"=>$row['countexp'],
						"countrechange"=>$row['countrechange'],
						"countuser"=>$row['countuser'],
						"couneurl"=>$row['counturl']));
				mysql_query("UPDATE admins SET ltime=now() WHERE name='$user';",$link);
				$_SESSION['online']=true;
				$_SESSION['admin']=$user;
			}else{
				echo '{"success":false,"msg":"用户名或密码错误"}';
			}
		}else{
			echo '{"success":false,"msg":"用户名不合法！"}';
		}
	}else{
		echo '{"success":false,"msg":"请从正确的客户端登入！"}';
	}
}
?>