<?php
class contact extends App{
	
	
	function beforeFilter(){
	  
	global $LOCALE,$CONFIG;
		$this->contentHelper = $this->useHelper("contentHelper");
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		

		
	}
	
	function main(){
			
	
		global $LOCALE,$CONFIG; 

		if ($this->_p('submit')==1){
	
			//pr($_POST);exit;
			$emailval=$this->is_valid_email(strip_tags($this->_p('email')));
		
			
			$name=strip_tags($this->_p('name'));
		
			if (ctype_alpha(str_replace(' ', '', $name))==false||$name=='NAME'||$name=='')
			{
				$this->assign('nama_not',"Name is not correct");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/contact_us.html');
			}
			if(strip_tags($this->_p('email')=='') || !$emailval )
			{
				$this->assign('email_not',"Email is not correct ");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/contact_us.html');
			}
			$pesan=strip_tags($this->_p('pesan'));
			
			if (!ctype_alpha(str_replace(' ', '', $pesan)))
			{
				$this->assign('alamatnot',"Pesan is not correct");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/contact_us.html');
			}
			
			$checkEmailExist = $this->contentHelper->checkEmailExist(strip_tags($this->_p('email')));
		
			if($checkEmailExist){
				$this->assign('email_not',"Oops, something wasn't right. Your email has been registered.");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/contact_us.html');
			}

			// pr($files);exit;
			$insertnewcontact = $this->contentHelper->insertnewcontact();
			if($insertnewcontact){		
				$this->assign('success',"Data Has Been Successfully Insert");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/contact_us.html');
			}			
		}
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/contact_us.html');
		
	}

}
?>