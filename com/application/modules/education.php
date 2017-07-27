<?php
class education extends App{
	
	
	function beforeFilter(){
	  
	
		$this->contentHelper = $this->useHelper("contentHelper");
		$this->educationHelper = $this->useHelper("educationHelper");
		
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
	
		$result = $this->contentHelper->listeducation();
		//pr($result);exit; 
		$this->assign('list',$result['result']);
		$this->assign('total',$result['total']);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listeducation.html');
		
	}
	function contentview_education()
	{
		global $LOCALE,$CONFIG;
		
		
		$id = intval($this->_request('id'));
		
	//	pr($id);exi
		
		$selectupdatedata = $this->educationHelper->selectupdatedata($id);
		//pr($selectupdatedata);exit;
		$this->assign('load',$selectupdatedata); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/content_view_education.html');
	}
	
	function callsheader($file='download-data'){
		 
		
		$filename = $file.date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); 
		print $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/downloaddata.html');
		exit;
	
	}
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
//	pr($_POST);exit;
		if ($this->_p('ajax')){
			$ajax =	$this->educationHelper->listeducation($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

}
?>