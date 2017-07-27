<?php
class career extends App{
	
	
	function beforeFilter(){
	  
	global $LOCALE,$CONFIG;
		$this->contentHelper = $this->useHelper("contentHelper");
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		

		
	}
	
	function main(){
			
	global $LOCALE,$CONFIG; 

	if ($this->_p('submit')==1){
	
			$name=strip_tags($this->_p('name'));
			if (ctype_alpha(str_replace(' ', '', $name))==false||$name=='NAME'||$name=='')
			{
				$this->assign('nama_not',"Name is not correct");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listcareer.html');
			}
			
			$alamat=strip_tags($this->_p('alamat'));
			if (ctype_alpha(str_replace(' ', '', $alamat))==false|| $alamat=='')
			{
				$this->assign('alamat_not',"Alamat is not correct");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listcareer.html');
			}
			$telp=intval($this->_p('telp'));
			if ( $telp=='')
			{
				$this->assign('telp_not',"telp is not correct");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listcareer.html');
			}
			
			$emailval=$this->is_valid_email(strip_tags($this->_p('email')));
			if(strip_tags($this->_p('email')=='') || !$emailval )
			{
				$this->assign('email_not',"Email is not correct ");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listcareer.html');
			}
			
			$files = $_FILES['file'];
			
			$insertnewcareer = $this->contentHelper->insertnewcareer();
			if($insertnewcareer){		
				$path = $CONFIG['LOCAL_PUBLIC_ASSET']."career/";
				
				$uploadcareer= $this->uploadHelper->uploadThisFile($files,$path);
				$updatecareer = $this->contentHelper->careerupdate($listeducation,$uploadnews['arrImage']['filename']);
				
				$this->assign('success',"Data Has Been Successfully Insert");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listcareer.html');
			}			
	}
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listcareer.html');
		
	}

}
?>