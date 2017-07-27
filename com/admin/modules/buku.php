<?php
class buku extends App{
		
	function beforeFilter(){ 
		global $LOCALE,$CONFIG; 
		$this->bukuHelper = $this->useHelper("bukuHelper");
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
		
	
		$listlocation = $this->bukuHelper->listbuku();
		
		$this->assign('list',$listlocation['result']);
		$this->assign('total',$listlocation['total']);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/listbuku.html');
	
		
	}
	
	
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
	//	pr($_POST);exit;
		if ($this->_p('ajax')){
			$ajax =	$listlocation = $this->bukuHelper->listcareer($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

	
	function addbuku(){
			global $LOCALE,$CONFIG; 
		//pr($_POST);exit;
		if(isset($_POST['submit'])==1)
		{			
			
			$images = $_FILES['photo'];
			
			$dataHalaman=array();
		
			$no=0;
			$jumlahHalaman = count($images['name']);
			
			for($i=0;$i<=$jumlahHalaman-1;$i++)
			{
				$img['name']=@$images['name'][$i];
				$img['type']=@$images['type'][$i];
				$img['tmp_name']=@$images['tmp_name'][$i];
				$img['error']=@$images['error'][$i];
				$img['size']=@$images['size'][$i];
				
				if($img['name'])
				{
						
					$path = $CONFIG['LOCAL_ASSET'].'gallery/buku/';
					$images['photo'] = $_FILES['photo']; 	 
					$uploadnews = $this->uploadHelper->uploadThisImage($img,$path,1000,false,false);
					$filephoto=$uploadnews['arrImage']['filename'];
				}
				else
				{
					$filephoto='';
				}
				if($i==0)
				{	
					$dataHalaman['halaman_cover']=$filephoto;
				}
				if($i<=1)
				{
					$dataHalaman['halaman_inti'][$i]=$filephoto;
				}
				$dataHalaman['halaman_buku'][$i]=$filephoto;
			}
				
			$halaman=serialize($dataHalaman);
			
			
			$listeducation = $this->bukuHelper->addbuku($halaman);
			if($listeducation){
					
					sendRedirect($CONFIG['ADMIN_DOMAIN']."buku");
				}
			
		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/new_buku.html');
	
	
	}
	
	function editbuku()
	{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		$selectupdatedata = $this->bukuHelper->selectupdatedata($id);
		
		if($this->_p('submit')==1){// echo "ss";exit;
			
			$images = $_FILES['photo'];
			
			$dataHalaman=array();
		
			$no=0;
			$jumlahHalaman = count($images['name']);
			
			for($i=0;$i<=$jumlahHalaman-1;$i++)
			{
				$img['name']=@$images['name'][$i];
				$img['type']=@$images['type'][$i];
				$img['tmp_name']=@$images['tmp_name'][$i];
				$img['error']=@$images['error'][$i];
				$img['size']=@$images['size'][$i];
				
				if($img['name'])
				{
						
					$path = $CONFIG['LOCAL_ASSET'].'gallery/buku/';
					$images['photo'] = $_FILES['photo']; 	 
					$uploadnews = $this->uploadHelper->uploadThisImage($img,$path,1000,false,false);
					$filephoto=$uploadnews['arrImage']['filename'];
				}
				else
				{
					
					$filephoto='';
				}
				if($i==0)
				{	
					
					if($filephoto=='')
					{
						
						$dataHalaman['halaman_cover'][$i]=@$selectupdatedata['halaman_foto'][$i]['nama'];
					}
					else
					{
						$dataHalaman['halaman_cover'][$i]=$filephoto;
					}
					
					//$dataHalaman['halaman_cover']=$filephoto;
				}
				if($i<=1)
				{
				
					if($filephoto=='')
					{
						
						$dataHalaman['halaman_inti'][$i]=@$selectupdatedata['halaman_foto'][$i]['nama'];
					}
					else
					{
						$dataHalaman['halaman_inti'][$i]=$filephoto;
					}
					//$dataHalaman['halaman_inti'][$i]=$filephoto;
					
				}
				if($filephoto=='')
				{
					
					$dataHalaman['halaman_buku'][$i]=@$selectupdatedata['halaman_foto'][$i]['nama'];
				}
				else
				{
					$dataHalaman['halaman_buku'][$i]=$filephoto;
				}
			}
				
			$halaman=serialize($dataHalaman);
			
			
			$listeducation = $this->bukuHelper->editbuku($id,$halaman);
			if($listeducation){
					
					sendRedirect($CONFIG['ADMIN_DOMAIN']."buku");
				}
			
			
		
		
		}
		
		$this->assign('load',$selectupdatedata); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/edit_buku.html');
	}
		function deletebuku()
		{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		
			$editeducation = $this->bukuHelper->deletebuku($id);
			
			
				sendRedirect($CONFIG['ADMIN_DOMAIN']."buku");
			
		}
	
	}
?>