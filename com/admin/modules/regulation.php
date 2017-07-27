<?php
class regulation extends App{
		
	function beforeFilter(){ 
		global $LOCALE,$CONFIG; 
		$this->regulationHelper = $this->useHelper("regulationHelper");
		$this->uploadHelper = $this->useHelper('uploadHelper');
		
		
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));	

		
	}

	 
	function main(){
		

		$listeducation = $this->regulationHelper->listregulation();
	//	pr($listeducation);exit;
		$this->assign('list',$listeducation['result']);
		$this->assign('total',$listeducation['total']);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/listregulation.html');
	
		
	}
	
	
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
	//	pr($_POST);exit;
		if ($this->_p('ajax')){
			$ajax =	$listeducation = $this->regulationHelper->listregulation($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

	
	function addregulation(){
		global $LOCALE,$CONFIG; 
		//pr($_FILES);exit;
		if(isset($_POST['submit'])==1)
		{			
		$images = $_FILES['images'];
		$listeducation = $this->regulationHelper->addregulation();
		//pr($images);exit;
		if($listeducation){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."regulation/";
				//pr($path);exit;
				
				// upload image dulu
				$uploadregulation = $this->uploadHelper->uploadThisImage($images,$path,1000,false,false);
				// update data
				$updateEvent = $this->regulationHelper->regulationupdate($listeducation,$uploadregulation['arrImage']['filename']);
				sendRedirect($CONFIG['ADMIN_DOMAIN']."regulation");
			}
		}
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/new_regulation.html');
	
	
	}
	
	function editregulation()
	{
		global $LOCALE,$CONFIG;
		$path = $CONFIG['LOCAL_PUBLIC_ASSET']."regulation/";
		$this->assign('path', $path);
		
		$id = intval($this->_request('id'));
		
		if($this->_p('submit')==1){ 
			$uploadregulation = false;
		// pr($_POST);exit;
			if (isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
				if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 2000000) {
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."regulation/";
					//$files=NULL,$path=NULL,$maxSize=1000,$resizeOriginal=false,$createThumb=true
					$data = $this->uploadHelper->uploadThisImage($_FILES['images'],$path,1000,false,false);
					
					if ($data['arrImage']!=NULL) { 
						$uploadregulation = $data['arrImage']['filename'];
					}
				}else{
					echo '2';
				}
			}
			
		//	pr($uploadregulation);exit;
			$editregulation = $this->regulationHelper->editregulation($id,$uploadregulation);
			
			if($editregulation == true){
			
				sendRedirect($CONFIG['ADMIN_DOMAIN']."regulation");
			}
		
		}
		$selectupdatedata = $this->regulationHelper->selectupdatedata($id);
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/edit_regulation.html');
	}
		function deleteregulation()
		{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		
			$editeducation = $this->regulationHelper->deleteregulation($id);
			
			if($editeducation){
			//echo "ss";exit;
				sendRedirect($CONFIG['ADMIN_DOMAIN']."regulation/listeducation");
			}
		
		}
		
		function contentview(){
		global $LOCALE,$CONFIG;
	
		
		$id = intval($this->_request('id'));
		$selectupdatedata = $this->regulationHelper->selectupdatedata($id);
		
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 

		$time['time'] = '%H:%M:%S';
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/content_view_regulation.html');
	
		
		}
	
	}
?>