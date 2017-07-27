<?php
class videocms extends App{
		
	function beforeFilter(){ 
		global $LOCALE,$CONFIG; 
		$this->videoHelper = $this->useHelper("videoHelper");
		
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpublic', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets', $CONFIG['PUBLIC_ASSET']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));	
		
	}

	 
	function main(){
		
	
		$listlocation = $this->videoHelper->listvideo();
		//pr($listlocation);exit;
		$this->assign('list',$listlocation['result']);
		$this->assign('total',$listlocation['total']);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/listvideo.html');
	
		
	}
	
	
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
	//	pr($_POST);exit;
		if ($this->_p('ajax')){
			$ajax =	$listlocation = $this->videoHelper->listcareer($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

	
	function addvideo(){
			global $LOCALE,$CONFIG; 
		//pr($_POST);exit;
		if(isset($_POST['submit'])==1)
		{			
			
			
			
			$listeducation = $this->videoHelper->addvideo();
			if($listeducation){
					
					sendRedirect($CONFIG['ADMIN_DOMAIN']."videocms");
				}
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/new_video.html');
	
	
	}
	
	function editvideo()
	{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		if($this->_p('submit')==1){// echo "ss";exit;
			
			$editlocation = $this->videoHelper->editvideo($id);
			
			
		
		}
		$selectupdatedata = $this->videoHelper->selectupdatedata($id);
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/edit_video.html');
	}
		function deletevideo()
		{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		
			$editeducation = $this->videoHelper->deletevideo($id);
			
			
				sendRedirect($CONFIG['ADMIN_DOMAIN']."videocms");
			
		}
	
	}
?>