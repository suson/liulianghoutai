<?php 
 ############################################################
 #	Date: 03/04/2011										#
 #  Author: Mr.Jackin										#
 #  Description: Define class CServiceConfig				#
 #		It get/set Service Config Infomation From Database.	#
 ############################################################
 
	class CServiceConfig{
		private $dbcn;
		private $data =array();
		public function __construct($mysqli){$this->dbcn = $mysqli;}
		public function __destruct(){
			$mysqli = $this->dbcn;
			$values = '';
			for($i = count($this->data); $i > 0; --$i){
			    list($key,$value)=each($this->data);
				$values .= sprintf("('%s','%s')",$key,$value);
				$values .= ',';
			}
			$values = substr($values,0,-1);
			if(0 != count($this->data)){
				$q = sprintf("REPLACE INTO `Jackin_sys_var`(`vname`,`vvalue`) VALUES %s",$values);
				if(!$mysqli->query($q)){
					throw new Exception($q.'<br/>Update `Jackin_sys_var` Failed! ERROR::'.$mysqli->error);
				}
			}
		}
		
		public function __set($property, $value){
			if(isset($this->data[$property])){
				$this->data[$property] = $value;
				return ;
			}
			if(!isset($this->$property)) return ;
			$this->data[$property] = $value;
		}
		public function __get($property){
			if(isset($this->data[$property])) return $this->data[$property];
			$mysqli = $this->dbcn;
			$q = sprintf("SELECT `vvalue` FROM `Jackin_sys_var` WHERE `vname`='%s';",$mysqli->real_escape_string($property));
			$result = $mysqli->query($q);
			if(!$result || 1 != $result->num_rows){
				throw new Exception('Get Undefined Service Config Option!');
			}
			$row = $result->fetch_assoc();
			$this->data[$property] = $row['vvalue'];
			return $this->data[$property];
		}
		public function __isset($property){
			if(isset($this->data[$property])) return true;
			$mysqli = $this->dbcn;
			$q = sprintf("SELECT `vvalue` FROM `Jackin_sys_var` WHERE `vname`='%s';",$mysqli->real_escape_string($property));
			$result = $mysqli->query($q);
			if(!$result || 1 != $result->num_rows) return false;
			$row = $result->fetch_assoc();
			$this->data[$property] = $row['vvalue'];
			return true;
		}
		public function __unset($property){
			if(0 == strcmp(strtolower($property),'admin')) return;
			if(isset($this->data[$property])) unset($this->data[$property]);
			$mysqli = $this->dbcn;
			$q = sprintf("DELETE FROM `Jackin_sys_var` WHERE `vname`='%s';",$mysqli->real_escape_string($property));
			$mysqli->query($q);
			return;
		}
	}
?>