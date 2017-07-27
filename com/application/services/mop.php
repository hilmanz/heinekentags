<?php
class mop  extends ServiceAPI{
	
	function beforeFilter(){
		$this->deviceMopHelper= $this->useHelper('deviceMopHelper');
		$this->loginHelper= $this->useHelper('loginHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);		

	}
	
	function login(){
		
		$user = $this->deviceMopHelper->loginAdminMop(); 
		if(!$user) {
			$data['Result'] = "0";
			$data['SessionID'] = "0";		
			$data['Message'] = "your device not registered";		
			return $data;
		}else{
			$data['Result'] = (String)intval($user->Result[0]);
			$data['SessionID'] = (String)intval($user->SessionID[0]);		
			
			$data['Message'] = "wrong credential";				
			if(intval($user->Result[0])==1) $data['Message'] = "welcome";	
			if(intval($user->Result[0])==2) $data['Message'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
			if(intval($user->Result[0])==3) $data['Message'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
			if(intval($user->Result[0])==4) $data['Message'] = "your account blocked";	
			if(intval($user->Result[0])==100) $data['Message'] = "something wrong on MOP";	
			if(intval($user->Result[0])==99) $data['Message'] = "your session MOP expired";	
			return $data;
		}
		
	}
	
	function endsessionmop(){
		$user = $this->deviceMopHelper->AdminEndSession(); 
		return $user;
		
	}
	
	function checkreferral(){
		$user = $this->deviceMopHelper->checkReferralMop(); 
		return $user;
		
	}
	
	function registeruser(){
		$user = $this->deviceMopHelper->syncAdminUserRegistrant("AdminRegisterProfile"); 
		return $user;
		
	}
	
	function changeuserprofile(){
		$user = $this->deviceMopHelper->syncAdminUserRegistrant("AdminUpdateProfile"); 
		return $user;
		
	}
	
	function registeruserdeduplicate(){
		$user = $this->deviceMopHelper->syncAdminUserRegistrant("AdminRegisterProfileDeDuplication"); 
		return $user;
		
	}
	
	function searchuser(){
		$user = $this->deviceMopHelper->searchProfileUser(); 
		return $user;
		
	}
	
	function searchusergiid(){
		$user = $this->deviceMopHelper->AdminGetProfileonGiid(); 
		return $user;
		
	}
	function getuser(){
		$user = $this->deviceMopHelper->getProfileUser(); 
		return $user;
		
	}
	
	function registerdevice(){
		$user = $this->deviceMopHelper->registerDeviceAdmin(); 
		return $user;
		
	}
	
	function secrettoken(){
	
			$headers = $this->deviceMopHelper->gettokensecret();
			return array('usethis' => $headers);
	}
	
	
	function getAccountReferral(){
		$data['Result'] = "0";
		$data['UserAccount'] = "";
		$data['SessionID'] = "0";		
		$data['Message'] = "your device not registered";	
		$hasaccount = $this->loginHelper->checkavailableaccount();
	  // pr($hasaccount);
		if($hasaccount) {
			$data['UserAccount'] = $hasaccount;
			$user = $this->deviceMopHelper->loginAdminMop();
		
			if(!$user) {
					
				return $data;
			}else{
			
				
				$data['Result'] = (String)intval($user->Result[0]);
				$data['SessionID'] = (String)intval($user->SessionID[0]);		
				
				$data['Message'] = "wrong credential";		
				$this->loginHelper->startsessionaccount($hasaccount,$data['SessionID']);				
				if(intval($user->Result[0])==1){ 
						$data['Message'] = "welcome";	
				}
				if(intval($user->Result[0])==2) $data['Message'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
				if(intval($user->Result[0])==3) $data['Message'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
				if(intval($user->Result[0])==4) $data['Message'] = "your account blocked";	
				if(intval($user->Result[0])==100) $data['Message'] = "something wrong on MOP";	
				if(intval($user->Result[0])==99) $data['Message'] = "your session MOP expired";	
				
				
			 
				return $data;
			}
		}else{
			$data['Result'] = "0";
			$data['UserAccount'] = "";
			$data['SessionID'] = "0";		
			$data['Message'] = " no accounts available yet ";	
		}
	
		return $data;
	}
	
	function endsession(){
		$data['Result'] = false; 	
		$data['Message'] =" please insert session id";
		$sessionid = $this->_p('sessionid');
		// if(!$sessionid) return $data;
		$user = $this->deviceMopHelper->AdminEndSession($sessionid); 
		
		if($this->loginHelper->endsessionaccount()){
			$data['Result'] = true; 	
			$data['Message'] =" success end session ";
			
		}
		
		return $data;
	}
	
	
	
}
?>