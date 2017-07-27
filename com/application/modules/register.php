<?php
class register extends App{
	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
			
		$this->registerHelper = $this->useHelper('registerHelper');
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);		
		$this->assign('locale', $LOCALE[1]);
		
		
	}
	
	function main(){
			
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/home.html');		
	}
	
	function send(){
			
			$totalquery =  $this->registerHelper->addregister();
			
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/entourage.html');		
	}
 
	
	
	 
}
?>