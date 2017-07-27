<?php
class entouragePhoto extends App{
	
	
	function beforeFilter(){
	
	
		$this->reportHelper = $this->useHelper("reportHelper");
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
		$this->assign('pages', $this->_g('page'));
		$this->assign('acts', $this->_g('act')); 
		
		
	}
	
	function main(){
			$this->assign('uid',strip_tags($this->_g('uid')));
			$this->assign('areaid',strip_tags($this->_g('areaid')));
			$this->assign('startdate',strip_tags($this->_g('startdate')));
			$this->assign('enddate',strip_tags($this->_g('enddate')));
							
				$enturagereportstat =  $this->reportHelper->dataphotoentourage($start=null,$limit=20,$limitstatus=true); 
				// pr($enturagereportstat);
 				$this->assign('list',  $enturagereportstat['result']);
				$this->assign('total',  $enturagereportstat['total']); 
				
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/entourage-photo.html');
		
	}
	
	function callsheader($file='download-data'){
		 
		
		$filename = $file.date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); 
		print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'widgets/downloaddata.html');
		exit;
	
	}
}
?>