<?php
class gallerycms extends App{
		
	function beforeFilter(){ 
		global $LOCALE,$CONFIG; 
		$this->galleryHelper = $this->useHelper("galleryHelper");
		$this->uploadHelper = $this->useHelper("uploadHelper");
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpublic', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets', $CONFIG['PUBLIC_ASSET']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));	
		
	}

	 
	function main(){
		
	
		$listlocation = $this->galleryHelper->listgallery();
		//pr($listlocation);exit;
		$this->assign('list',$listlocation['result']);
		$this->assign('total',$listlocation['total']);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/listgallery.html');
	
		
	}
	
	
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
	//	pr($_POST);exit;
		if ($this->_p('ajax')){
			$ajax =	$listlocation = $this->galleryHelper->listcareer($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

	
	function addgallery(){
			global $LOCALE,$CONFIG; 
		//pr($_POST);exit;
		if(isset($_POST['submit'])==1)
		{			
			$idfolder = intval($this->_p('folder')); 
			$getFolder= $this->galleryHelper->folder($idfolder);
			
			$path = $CONFIG['LOCAL_ASSET'].'gallery/'.$getFolder['name_folder']."/";
			$images['photo'] = $_FILES['photo']; 	 
			$uploadnews = $this->uploadHelper->uploadThisImage($images['photo'],$path,1000,false,false);
			
			$filephoto=$uploadnews['arrImage']['filename'];
			$listeducation = $this->galleryHelper->addgallery($filephoto);
			if($listeducation){
					
					sendRedirect($CONFIG['ADMIN_DOMAIN']."gallerycms");
				}
		}
		$folder = $this->galleryHelper->getFolder();
		
		$this->assign('folder',$folder);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/new_gallery.html');
	
	
	}
	function addFolder(){
			global $LOCALE,$CONFIG; 
		if($this->_p('submit')==1)
		{	
			$nameFolder =$this->_p('nameFolder');
			
			$structure = $CONFIG['LOCAL_ASSET'].'gallery/'.$nameFolder;
			
			if (mkdir($structure, 0777, true)) {
						
						$listeducation = $this->galleryHelper->addFolder();
			}
			
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/new_folder.html');
	
	
	}
	function editphoto()
	{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		if($this->_p('submit')==1){// echo "ss";exit;
			$idfolder = intval($this->_p('folder')); 
			$getFolder= $this->galleryHelper->folder($idfolder);
			$filephoto='';
			
			if($_FILES['photonew']['name']!='')
			{
				$path = $CONFIG['LOCAL_ASSET'].'gallery/'.$getFolder['name_folder']."/";
				$images['photo'] = $_FILES['photonew']; 	 
				$uploadnews = $this->uploadHelper->uploadThisImage($images['photo'],$path,1000,false,false);
				
				$filephoto=$uploadnews['arrImage']['filename'];
			}
			$editlocation = $this->galleryHelper->editgallery($id,$filephoto);
			
			if($editlocation){
				//echo "ss";exit;
				//sendRedirect($CONFIG['ADMIN_DOMAIN']."gallerycms/editphoto");
			}
		
		}
		$selectupdatedata = $this->galleryHelper->selectupdatedata($id);
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 
		$folder = $this->galleryHelper->getFolder();
		//pr($folder);exit;
		$this->assign('folder',$folder);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/edit_gallery.html');
	}
		function deletephoto()
		{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		
			$editeducation = $this->galleryHelper->deletegallery($id);
			
			
				sendRedirect($CONFIG['ADMIN_DOMAIN']."gallerycms");
			
		}
	
	}
?>