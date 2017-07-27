<?php
class entourage extends ServiceAPI{

	
	function beforeFilter(){
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->entourageHelper = $this->useHelper('entourageHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
		$this->deviceMopHelper = $this->useHelper('deviceMopHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
	}
	
	 
	function synchenturage(){
		
		$data = $this->entourageHelper->synchenturage_batch();
	
		
		return true;
	}
	
	function synchenturage_batch(){
		
		$data = $this->entourageHelper->synchenturage_batch();
	
		
		return true;
	}
	
	function chart(){

		$data = $this->entourageHelper->getEntourageChartStat();
		return $data;
	}
	 
}
?>
