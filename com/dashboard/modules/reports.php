<?php
class reports extends App{	
	
	function beforeFilter(){
		$this->projectsHelper = $this->useHelper("projectsHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);				
		$this->assign('locale', $LOCALE[1]);  		
		
		$brandlist = $this->projectsHelper->brandlist();
		$this->assign('brandlist', $brandlist);
		$this->assign('pages', $this->_g('page'));
		$this->assign('acts', $this->_g('act'));
		$this->assign('user', $this->user);   
	}
	
	function main(){  
	
	
		
		$reportquerylist = $this->projectsHelper->reportquerylist(null,10);
		// pr($reportquerylist);				
		$this->assign('total',intval($reportquerylist['total']));
		$this->assign('reportquerylist',$reportquerylist['result']);
		
		if ($this->_g('brandid'))
		{
			$reportquerylist = $this->projectsHelper->reportquerylist(null,10);
		}		
		
		
		
		if(strip_tags($this->_g('download'))=='report'){

			$this->callsheader();

		}
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/report-pages.html');	
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
	
	function ajaxPaging()
	{
		
		$start = $this->_g('start');
		
		if ($this->_p('ajax')){
			$ajax = $this->projectsHelper->reportquerylist($start);
		}
		// pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}
	
}

?>