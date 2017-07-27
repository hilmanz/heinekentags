<?php
class track extends ServiceAPI{

	
	function beforeFilter(){
	
	 		$this->appsHelper = $this->useHelper('appsHelper');
	 		$this->userHelper = $this->useHelper('userHelper');
	 		$this->uploadHelper = $this->useHelper('uploadHelper'); 
	 		$this->newsHelper = $this->useHelper('newsHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
	}
	
	function activity(){
		$activity = $this->_p('activity');
		$this->log('surf',$activity);		 
		$data['status'] = true;
		$data['message'] = "success save activity";
		return $data;
	}
	function getUser(){
		 
		$user = $this->userHelper->getUserProfile(); 
		if($user){
			$profile['id'] = $user['id'];
			$profile['name'] = $user['name'];
			$profile['last_name'] = $user['last_name'];
		}else return false;
		$lastoken = $this->appsHelper->getLastOldToken(); 
		$data['profile'] = $profile;	
		$data['playingat'] = date("YmdHi").$lastoken;	
		return $data;
	}
	
	function games(){
			global $CONFIG;
			$data['status'] = false;
			$data['code'] = 0;
			$data['msg'] ="data not saved";
		 
		   
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contests/";
				$imagesfiles = false;
				$images = false;
				
				if (isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
					if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 20000000) {
				
						$images = $this->uploadHelper->uploadThisImage($_FILES['images'],$path);						
					} else {
						$success = false;
					}
				} else {
					$success = false;
				}
				$realfilename = @$_FILES['images']['name'];
				 				 
				if($images)$imagesfiles = $images['arrImage']['filename'];
				else $imagesfiles = false;
				 
				$data =  $this->appsHelper->uploadContents(false,$imagesfiles,$realfilename);  
			 
				return array('gamesstatus'=>$data);
	}
	
	 
}
?>
