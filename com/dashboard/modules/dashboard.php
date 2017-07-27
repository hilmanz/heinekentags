<?php
class dashboard extends App{
		
	function beforeFilter(){
	
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		$this->log('globalAction','LOGIN');
		$this->registerHelper = $this->useHelper('registerHelper');
	
	}
	
	function main(){ 
	
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/dashboard-report.html');
	}
	
}
?>