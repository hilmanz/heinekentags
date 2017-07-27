<?php
class faq extends App{
	
	
	function beforeFilter(){
	  
	
		$this->contentHelper = $this->useHelper("contentHelper");
		$this->newsHelper = $this->useHelper("newsHelper");
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
		$this->assign('n_status', (string)$this->_g('n_status'));
		$this->assign('brandid', $this->_g('brandid'));
		
		// $this->assign('getCity',$loadCity);
		
		$this->assign('pages', $this->_g('page'));
		$this->assign('acts', $this->_g('act')); 
		
		
	}
	
	function main(){
			
	
		global $LOCALE,$CONFIG; 
	
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/faq.html');
		
	}

}
?>