<?php
class systemshin extends App{
		
	function beforeFilter(){ 
		global $LOCALE,$CONFIG; 
		$this->systemshinHelper = $this->useHelper("systemshinHelper");
		$this->uploadHelper = $this->useHelper('uploadHelper');
		
		
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));	

		
	}

	 
	function main(){
		

		$systemshin = $this->systemshinHelper->listsystemshin();
	//	pr($systemshin);exit;
		$this->assign('list',$systemshin['result']);
		$this->assign('total',$systemshin['total']);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/listsystemshin.html');
	
		
	}
	
	
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
	//	pr($_POST);exit;
		if ($this->_p('ajax')){
			$ajax =	$systemshin = $this->systemshinHelper->listsystemshin($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

	
	function addsystemshin(){
		global $LOCALE,$CONFIG; 
		//pr($_FILES);exit;
		if(isset($_POST['submit'])==1)
		{			
		$images = $_FILES['images'];
		$systemshin = $this->systemshinHelper->addsystemshin();
		//pr($images);exit;
		if($systemshin){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."systemshin/";
				//pr($path);exit;
				
				// upload image dulu
				$uploadsystemshin = $this->uploadHelper->uploadThisImage($images,$path,1000,false,false);
				// update data
				$updateEvent = $this->systemshinHelper->systemshinupdate($systemshin,$uploadsystemshin['arrImage']['filename']);
				sendRedirect($CONFIG['ADMIN_DOMAIN']."systemshin");
			}
		}
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/new_systemshin.html');
	
	
	}
	
	function editsystemshin()
	{
		global $LOCALE,$CONFIG;
		$path = $CONFIG['LOCAL_PUBLIC_ASSET']."systemshin/";
		$this->assign('path', $path);
		
		$id = intval($this->_request('id'));
		
		if($this->_p('submit')==1){ 
			$uploadsystemshin = false;
		// pr($_POST);exit;
			if (isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
				if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 2000000) {
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."systemshin/";
					//$files=NULL,$path=NULL,$maxSize=1000,$resizeOriginal=false,$createThumb=true
					$data = $this->uploadHelper->uploadThisImage($_FILES['images'],$path,1000,false,false);
					
					if ($data['arrImage']!=NULL) { 
						$uploadsystemshin = $data['arrImage']['filename'];
					}
				}else{
					echo '2';
				}
			}
			
		//	pr($uploadsystemshin);exit;
			$editsystemshin = $this->systemshinHelper->editsystemshin($id,$uploadsystemshin);
			
			if($editsystemshin == true){
			
				sendRedirect($CONFIG['ADMIN_DOMAIN']."systemshin");
			}
		
		}
		$selectupdatedata = $this->systemshinHelper->selectupdatedata($id);
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/edit_system.html');
	}
		function deletesystemshin()
		{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		
			$editeducation = $this->systemshinHelper->deletesystemshin($id);
			
			if($editeducation){
			//echo "ss";exit;
				sendRedirect($CONFIG['ADMIN_DOMAIN']."systemshin");
			}
		
		}
		
		function contentview(){
		global $LOCALE,$CONFIG;
	
		
		$id = intval($this->_request('id'));
		$selectupdatedata = $this->systemshinHelper->selectupdatedata($id);
		
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 

		$time['time'] = '%H:%M:%S';
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/content_view_system.html');
	
		
		}
	
	}
?>