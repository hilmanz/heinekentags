<?php
class settings extends App{
	
	
	function beforeFilter(){
	  
		$this->reportHelper = $this->useHelper("reportHelper");
		$this->uploadHelper = $this->useHelper("uploadHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));  
		$this->assign('pages', $this->_g('page'));
		$this->assign('acts', $this->_g('act'));  
		$this->assign('user', $this->user);  
		
		
	}
	
	function main(){ 
		
		$listoftemplates = $this->reportHelper->listoftemplates(null,5);
		$joinuser = $this->reportHelper->joinuser();
		// pr($listoftemplates);exit;		
		$this->assign('total',intval($listoftemplates['total']));
		$this->assign('listoftemplates',$listoftemplates['result']);
		$this->assign('listofuser',$joinuser);
		
		
		if($this->user->type==666){
			$this->assign('user', $this->user);
			return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/project-list.html');
		}
		if($joinuser[0]['userid'] != ''){
			$this->assign('user', $this->user);
			return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/project-list.html');
		}
		if($joinuser[0]['userid'] == ''){
		global $CONFIG;  
		
		$clientname = $this->reportHelper->clientname(); 
		
		$this->assign('clientname', $clientname);  
		
		if ($this->_p('submit')){ 
			$images['bgPotrait'] = $_FILES['bgPotrait']; 
			$images['bgLandscape'] = $_FILES['bgLandscape']; 
			$images['tqpotrait'] = $_FILES['tqpotrait']; 
			$images['tqlandscape'] = $_FILES['tqlandscape'];  
			
			if($images['bgPotrait']){ 
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgPotrait'],$path,1000,false,false);
				
				$background['potrait'] = $uploadnews['arrImage']['filename'];
			}
			
			if($images['bgLandscape']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgLandscape'],$path,1000,false,false);
				 
				$background['landscape'] = $uploadnews['arrImage']['filename']; 

			}
			
			if($images['tqpotrait']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqpotrait'],$path,1000,false,false);
				$thanks['potrait'] = $uploadnews['arrImage']['filename'];
				
			}
			
			if($images['tqlandscape']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqlandscape'],$path,1000,false,false);
				$thanks['landscape'] = $uploadnews['arrImage']['filename'];
			} 				
							
			$databg = serialize($background);
			$datatq = serialize($thanks);	 
			
			$insertnewsupdate = $this->reportHelper->insertnewsupdate($databg, $datatq); 
			if($insertnewsupdate){		
							sendRedirect($CONFIG['DASHBOARD_DOMAIN']."settings");
				}
		}
		
		$this->assign('user', $this->user);
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/dashboard.html');
		}
		
		else{			
			$this->assign('user', $this->user);
			return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/dashboard.html');		
		}
		
	}
	
	function ajaxPaging()
	{
		
		$start = $this->_p('start');
		
		if ($this->_p('ajax')){
			$ajax = $this->reportHelper->listoftemplates($start);
		}
		// pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}
	
	function addnewtemplates(){
		global $CONFIG;  
		
		$clientname = $this->reportHelper->clientname(); 
		
		$this->assign('clientname', $clientname);  
		
		if ($this->_p('submit')){ 
		
	//	pr($_POST);exit;
		  // pr($_FILES);exit;
			$images['bgPotrait'] = $_FILES['bgPotrait']; 
			$images['bgLandscape'] = $_FILES['bgLandscape']; 
			$images['tqpotrait'] = $_FILES['tqpotrait']; 
			$images['tqlandscape'] = $_FILES['tqlandscape'];  
		//	pr($images['bgPotrait']);exit;
			if($images['bgPotrait']){ 
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgPotrait'],$path,1000,false,false);
				//pr($uploadnews['arrImage']['filename']);exit;
				$background['potrait'] = $uploadnews['arrImage']['filename'];
			}
			if($images['bgPotrait']['name']==""){ 
	
				$background['potrait'] = "bg-iphone.jpg";
			}
			
			if($images['bgLandscape']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgLandscape'],$path,1000,false,false);
				 
				$background['landscape'] = $uploadnews['arrImage']['filename']; 

			}
			if($images['bgLandscape']['name']==""){ 
	
				$background['potrait'] = "bg-iphone.jpg";
			}
			
			if($images['tqpotrait']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqpotrait'],$path,1000,false,false);
				$thanks['potrait'] = $uploadnews['arrImage']['filename'];
				
			}
				if($images['tqpotrait']['name']==""){ 
	
				$background['potrait'] = "bg-iphone.jpg";
			}
			
			if($images['tqlandscape']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqlandscape'],$path,1000,false,false);
				$thanks['landscape'] = $uploadnews['arrImage']['filename'];
			} 				
			if($images['tqlandscape']['name']==""){ 
	
				$background['potrait'] = "bg-iphone.jpg";
			}
							
			$databg = serialize($background);
			//pr($databg);exit;
			$datatq = serialize($thanks);	 
			
			$insertnewsupdate = $this->reportHelper->insertnewsupdate($databg, $datatq); 
			if($insertnewsupdate){		
							echo "<script>alert('Insert Data Success');</script>";
							sendRedirect($CONFIG['DASHBOARD_DOMAIN']."users");
				}
		}
		
		$this->assign('user', $this->user);
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/dashboard.html');
	
	}
	
	function hapus(){
		global $CONFIG;
	
		$cidStr = intval($this->_request('id'));
		
		$result = $this->reportHelper->getHapus($cidStr);
		if($result) {
			sendRedirect($CONFIG['DASHBOARD_DOMAIN']."settings");
			return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/dashboard.html');
						die();
		}
	}
	
	function edit(){
		global $CONFIG;
		//echo "ss";
		$cidStr = intval($this->_request('id'));
		
		$cekid = $this->reportHelper->cekid($cidStr); 
		
		//pr($cekid['result'][0]);exit;
		
		
		
		$load = $this->reportHelper->getProjectDetail();
		$clientname = $this->reportHelper->clientname(); 
		//pr($load);exit;
		$this->assign('load',$load);
		$this->assign('idnya',$cekid['result'][0]['n_status']);
		$this->assign('loginbg',$cekid['result'][0]['loginbackground'][0]['potrait']);
		
		
		$this->assign('idnyalg',$cidStr);
		$this->assign('clientname',$clientname);
		
		if ($this->_p('submit')){		
			$images['bgPotrait'] = $_FILES['bgPotrait']; 
			$images['bgLandscape'] = $_FILES['bgLandscape']; 
			$images['tqpotrait'] = $_FILES['tqpotrait']; 
			$images['tqlandscape'] = $_FILES['tqlandscape'];  
			
			if($images['bgPotrait']['name']){ 
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgPotrait'],$path,1000,false,false);
				
				$background['potrait'] = $uploadnews['arrImage']['filename'];
			}else{
				$background['potrait'] = $this->_p('bgPotraitold');
			}
			
			if($images['bgLandscape']['name']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgLandscape'],$path,1000,false,false);
				 
				$background['landscape'] = $uploadnews['arrImage']['filename']; 

			}else{
				$background['landscape'] = $this->_p('bgLandscapeold');
				//pr();
			}
			
			if($images['tqpotrait']['name']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqpotrait'],$path,1000,false,false);
				$thanks['potrait'] = $uploadnews['arrImage']['filename'];
				
			}else{
				$thanks['potrait'] = $this->_p('tqpotraitold');
			}
			
			if($images['tqlandscape']['name']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqlandscape'],$path,1000,false,false);
				$thanks['landscape'] = $uploadnews['arrImage']['filename'];
			}else{
				$thanks['landscape'] = $this->_p('tqlandscapeold');
			}			
							
			$databg = serialize($background);
			$datatq = serialize($thanks);	 
			
			$updateProject = $this->reportHelper->updateProject($databg, $datatq); 
			if($updateProject){
			
				sendRedirect($CONFIG['DASHBOARD_DOMAIN']."users");
				return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');die();
			
			}
		
		}
			if ($this->_p('add')){		
			$images['bgPotrait'] = $_FILES['bgPotrait']; 
			$images['bgLandscape'] = $_FILES['bgLandscape']; 
			$images['tqpotrait'] = $_FILES['tqpotrait']; 
			$images['tqlandscape'] = $_FILES['tqlandscape'];  
			
			if($images['bgPotrait']['name']){ 
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgPotrait'],$path,1000,false,false);
				
				$background['potrait'] = $uploadnews['arrImage']['filename'];
			}else{
				$background['potrait'] = $this->_p('bgPotraitold');
			}
			
			if($images['bgLandscape']['name']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/login/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['bgLandscape'],$path,1000,false,false);
				 
				$background['landscape'] = $uploadnews['arrImage']['filename']; 

			}else{
				$background['landscape'] = $this->_p('bgLandscapeold');
				//pr();
			}
			
			if($images['tqpotrait']['name']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/potrait/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqpotrait'],$path,1000,false,false);
				$thanks['potrait'] = $uploadnews['arrImage']['filename'];
				
			}else{
				$thanks['potrait'] = $this->_p('tqpotraitold');
			}
			
			if($images['tqlandscape']['name']){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contents/thanks/landscape/";
				 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['tqlandscape'],$path,1000,false,false);
				$thanks['landscape'] = $uploadnews['arrImage']['filename'];
			}else{
				$thanks['landscape'] = $this->_p('tqlandscapeold');
			}			
							
			$databg = serialize($background);
			$datatq = serialize($thanks);	 
			
			$updateProject = $this->reportHelper->addProject($databg, $datatq); 
			if($updateProject){
			
				sendRedirect($CONFIG['DASHBOARD_DOMAIN']."users");
				return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');die();
			
			}
		
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-templates.html');
	}
	
}
?>