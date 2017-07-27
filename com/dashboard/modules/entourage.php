<?php
class entourage extends App{
	
	
	function beforeFilter(){
	
	
		$this->reportHelper = $this->useHelper("reportHelper");
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
		$this->assign('badetail',$this->reportHelper->getsba());
		$loadCity = $this->reportHelper->loadCity();
		
		$this->assign('getCity',$loadCity);
		$this->assign('pages', $this->_g('page'));
		$this->assign('acts', $this->_g('act')); 
		
		
	}
	
	function main(){
			$this->assign('uid',strip_tags($this->_g('uid')));
			$this->assign('areaid',strip_tags($this->_g('areaid')));
			$this->assign('startdate',strip_tags($this->_g('startdate')));
			$this->assign('enddate',strip_tags($this->_g('enddate')));
			
		
				$headerReport =  $this->reportHelper->headerReport();		 
				$this->assign('new_registrant_count', $headerReport['new']);  	
 				$this->assign('recontact_count', $headerReport['existing']);  
 				$this->assign('rejectedregistrant', $headerReport['reject']);  
 				$this->assign('pendingregistrant', $headerReport['pending']); 
				
				$allRegis_count['total'] = $headerReport['new']['total'] +$headerReport['existing']['total']  + $headerReport['reject']['total'];
 				$this->assign('allRegis_count', $allRegis_count); 
				
				
				$enturagereportstat =  $this->reportHelper->getEntourageStat(); 
 				$this->assign('enturagereportAge',  $enturagereportstat['result']['age']);
				$this->assign('genderPref',  $enturagereportstat['result']['gender']); 
 				$this->assign('brandPref', $enturagereportstat['result']['brand']); 
				
				$locationRegistrnt =  $this->reportHelper->locationRegistrnt();		 
 				$this->assign('locationRegistrnt', $locationRegistrnt); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/entourage-report.html');
		
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