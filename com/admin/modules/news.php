<?php
class news extends App{
		
	function beforeFilter(){ 
		global $LOCALE,$CONFIG; 
		$this->newsHelper = $this->useHelper("newsHelper");
		$this->uploadHelper = $this->useHelper('uploadHelper');
		
		
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));	

		
	}

	 
	function main(){
		

		$listeducation = $this->newsHelper->listnews();
	//	pr($listeducation);exit;
		$this->assign('list',$listeducation['result']);
		$this->assign('total',$listeducation['total']);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/listnews.html');
	
		
	}
	
	
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
	//	pr($_POST);exit;
		if ($this->_p('ajax')){
			$ajax =	$listeducation = $this->newsHelper->listnews($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

	
	function addnews(){
		global $LOCALE,$CONFIG; 
		//pr($_FILES);exit;
		if(isset($_POST['submit'])==1)
		{			
		$images = $_FILES['images'];
		$listeducation = $this->newsHelper->addnews();
		//pr($images);exit;
		if($listeducation){
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."news/";
				//pr($path);exit;
				
				// upload image dulu
				$uploadnews = $this->uploadHelper->uploadThisImage($images,$path,1000,false,false);
				// update data
				$updateEvent = $this->newsHelper->newsupdate($listeducation,$uploadnews['arrImage']['filename']);
				sendRedirect($CONFIG['ADMIN_DOMAIN']."news");
			}
		}
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/new_news.html');
	
	
	}
	
	function editnews()
	{
		global $LOCALE,$CONFIG;
		$path = $CONFIG['LOCAL_PUBLIC_ASSET']."news/";
		$this->assign('path', $path);
		
		$id = intval($this->_request('id'));
		
		if($this->_p('submit')==1){ 
			$uploadnews = false;
		// pr($_POST);exit;
			if (isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
				if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 2000000) {
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."news/";
					//$files=NULL,$path=NULL,$maxSize=1000,$resizeOriginal=false,$createThumb=true
					$data = $this->uploadHelper->uploadThisImage($_FILES['images'],$path,1000,false,false);
					
					if ($data['arrImage']!=NULL) { 
						$uploadnews = $data['arrImage']['filename'];
					}
				}else{
					echo '2';
				}
			}
			
		//	pr($uploadnews);exit;
			$editnews = $this->newsHelper->editnews($id,$uploadnews);
			
			if($editnews == true){
			
				sendRedirect($CONFIG['ADMIN_DOMAIN']."news");
			}
		
		}
		$selectupdatedata = $this->newsHelper->selectupdatedata($id);
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/edit_news.html');
	}
		function deletenews()
		{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		
			$editeducation = $this->newsHelper->deletenews($id);
			
			if($editeducation){
			//echo "ss";exit;
				sendRedirect($CONFIG['ADMIN_DOMAIN']."news/listeducation");
			}
		
		}
		
		function contentview(){
		global $LOCALE,$CONFIG;
	
		
		$id = intval($this->_request('id'));
		$selectupdatedata = $this->newsHelper->selectupdatedata($id);
		
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 

		$time['time'] = '%H:%M:%S';
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/content_view.html');
	
		
		}
	
	}
?>