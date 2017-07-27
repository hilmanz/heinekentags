<?php
class registrant extends ServiceAPI{

	
	function beforeFilter(){
	 
		$this->userHelper = $this->useHelper('userHelper');
		$this->registrantHelper = $this->useHelper('registrantHelper'); 
		$this->uploadHelper = $this->useHelper('uploadHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
	}
	 
	function register(){
		GLOBAL $CONFIG;
		$success = false;
		
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/photo/";
			$img = false;
			if (isset($_FILES['img'])&&$_FILES['img']['name']!=NULL) {
				if (isset($_FILES['img'])&&$_FILES['img']['size'] <= 20000000) {
					$img = $this->uploadHelper->uploadThisImage($_FILES['img'],$path);
						
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
		
		if($img)$filename = $img['arrImage']['filename'];
		else $filename = false;
		
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/signature/";
			$signature = false;
			if (isset($_FILES['signature'])&&$_FILES['signature']['name']!=NULL) {
				if (isset($_FILES['signature'])&&$_FILES['signature']['size'] <= 20000000) {
					$signature = $this->uploadHelper->uploadThisImage($_FILES['signature'],$path);						
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
	
	
		if($signature)$signature = $signature['arrImage']['filename'];
		else $signature = false;
		
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/signatureba/";
			$signatureba = false;
			if (isset($_FILES['signatureba'])&&$_FILES['signatureba']['name']!=NULL) {
				if (isset($_FILES['signatureba'])&&$_FILES['signatureba']['size'] <= 20000000) {
					$signatureba = $this->uploadHelper->uploadThisImage($_FILES['signatureba'],$path);						
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
	
		if($signatureba)$signatureba = $signatureba['arrImage']['filename'];
		else $signatureba = false;
		
		
		$data = $this->registrantHelper->addEntourage($filename,$signature,$signatureba);
		if($data['result']) $success = true;		
		else $success = false;
		
		return array("result"=>$success,'profileResult'=>$data);
	}
	
	function checkemail(){
		
		 
		
			$resultdata['result'] = 5;
			$resultdata['RegistrationID'] = "";
			$resultdata['FirstName'] = "";
			$resultdata['LastName'] = "";
			$resultdata['FullName'] = "";
			$resultdata['DateOfBirth'] = "";
			$resultdata['Email'] = "";
			$resultdata['GIIDType'] = "";
			$resultdata['GIIDNumber'] = "";
			$resultdata['Mobile'] ="";
			$resultdata['FacebookID'] = "";
			$resultdata['TwitterID'] = "";
			$resultdata['InstagramID'] = "";
			$resultdata['Gender'] = "";
			$resultdata['CityID'] = "";
			$resultdata['StateID'] = "";
			$resultdata['Brand1_ID'] = "";
			$resultdata['SubBrand1_ID'] = "";
			$resultdata['Brand2_ID'] = "";
			$resultdata['SubBrand2_ID'] = "";
		 
			$result = false;
			$data = $this->registrantHelper->checkentourage();	
			
			if($data['result']){
				$result = $data;
				$resultdata['result'] = 1;
				$resultdata['RegistrationID'] = $result['data']['registerid'];
				$resultdata['FirstName'] = $result['data']['name'];
				$resultdata['LastName'] = $result['data']['last_name'];
				$resultdata['FullName'] = $result['data']['name']." ".$result['data']['last_name'];
				$resultdata['DateOfBirth'] = $result['data']['birthday'];
				$resultdata['Email'] = $result['data']['email'];
				$resultdata['GIIDType'] = $result['data']['giidtype'];
				$resultdata['GIIDNumber'] = $result['data']['giidnumber'];
				$resultdata['Mobile'] = $result['data']['phone_number'];
				$resultdata['FacebookID'] = $result['data']['facebookID'];
				$resultdata['TwitterID'] = @$result['data']['twitterID'];
				$resultdata['InstagramID'] = @$result['data']['instagramID'];
				$resultdata['Gender'] = $result['data']['gender'];
				$resultdata['CityID'] = $result['data']['city'];
				$resultdata['StateID'] = $result['data']['state'];
				$resultdata['Brand1_ID'] = $result['data']['brand1'];
				$resultdata['SubBrand1_ID'] = $result['data']['brand1ref'];
				$resultdata['Brand2_ID'] =$result['data']['brandsub1'];
				$resultdata['SubBrand2_ID'] =$result['data']['brandsub1ref'];					
			} 
			if(!$result) {
				$result['result'] = false;
				$result['data'] = array();
				$resultdata['Email'] = strip_tags($this->_p('email'));
				
			} 
			return $resultdata;
	}
	
	function checkgiid(){
			// return false;
			$result = false;
			
			$resultdata['result'] = 5;
			$resultdata['RegistrationID'] = "";
			$resultdata['FirstName'] = "";
			$resultdata['LastName'] = "";
			$resultdata['FullName'] ="";
			$resultdata['DateOfBirth'] = "";
			$resultdata['Email'] = "";
			$resultdata['GIIDType'] = "";
			$resultdata['GIIDNumber'] = "";
			$resultdata['Mobile'] ="";
			$resultdata['FacebookID'] = "";
			$resultdata['TwitterID'] = "";
			$resultdata['InstagramID'] = "";
			$resultdata['Gender'] = "";
			$resultdata['CityID'] = "";
			$resultdata['StateID'] = "";
			$resultdata['Brand1_ID'] = "";
			$resultdata['SubBrand1_ID'] = "";
			$resultdata['Brand2_ID'] = "";
			$resultdata['SubBrand2_ID'] = "";
			
			$data = $this->registrantHelper->checkentourage();	
			 
			if($data['result']) {
				$result = $data;
				$resultdata['result'] = 1;
				$resultdata['RegistrationID'] = $result['data']['registerid'];
				$resultdata['FirstName'] = $result['data']['name'];
				$resultdata['LastName'] = $result['data']['last_name'];
				$resultdata['FullName'] = $result['data']['name']." ".$result['data']['last_name'];
				$resultdata['DateOfBirth'] = $result['data']['birthday'];
				$resultdata['Email'] = $result['data']['email'];
				$resultdata['GIIDType'] = $result['data']['giidtype'];
				$resultdata['GIIDNumber'] = $result['data']['giidnumber'];
				$resultdata['Mobile'] = $result['data']['phone_number'];
				$resultdata['FacebookID'] = $result['data']['facebookID'];
				$resultdata['TwitterID'] = @$result['data']['twitterID'];
				$resultdata['InstagramID'] = @$result['data']['instagramID'];
				$resultdata['Gender'] = $result['data']['gender'];
				$resultdata['CityID'] = $result['data']['city'];
				$resultdata['StateID'] = $result['data']['state'];
				$resultdata['Brand1_ID'] = $result['data']['brand1'];
				$resultdata['SubBrand1_ID'] = $result['data']['brand1ref'];
				$resultdata['Brand2_ID'] =$result['data']['brandsub1'];
				$resultdata['SubBrand2_ID'] =$result['data']['brandsub1ref'];		
			} 
			
			if(!$result) {
				$result['result'] = false;
				$result['data'] = array();
				$resultdata['GIIDNumber'] = strip_tags($this->_p('giidnumber'));
				
			}
			return $resultdata;
	}
	
	function getCity(){
		$result['result'] = false;		
		$result['data'] = array();
		$data = $this->registrantHelper->citylists();	
		if($data) $result['result'] = true;		
		$result['data'] = $data;
		return $result;
		
	}
	
	function getBrandPref(){
		$result['result'] = false;		
		$result['data'] = array();
		$data = $this->registrantHelper->brandpreflists();	
		if($data) $result['result'] = true;		
		$result['data'] = $data;
		return $result;
	}
	
	
	function chart_old_version(){

		$data = $this->registrantHelper->getEntourageChartStat();
		return $data;
	}
	
	function chart(){

		$data = $this->registrantHelper->getEntourageChartStatFromReport();
		return $data;
	}
}
?>
