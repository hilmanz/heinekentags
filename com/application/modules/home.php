<?php
class home extends App{
	
	
	function beforeFilter(){
	  
	
		$this->contentHelper = $this->useHelper("contentHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
	
		
		
	}
	
	function main(){
			
	
		global $LOCALE,$CONFIG; 
		$result = $this->contentHelper->listnews2();
		$result2 = $this->contentHelper->list2();
		//pr($result);exit;
		$this->assign('list',$result);
		 $class = array(
					'fadeInLeft',
					'fadeInDown',
					'fadeInRight',
					'fadeInUp');
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
	
		$time['time'] = '%H:%M:%S';
		$this->assign('animasiclass', $class);
		$this->assign('user', $this->user);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/dashboard.html');
		
	}
		function status(){
			
	
		global $LOCALE,$CONFIG; 
		
		//pr($_POST);exit;
		$status=$this->_p('status');
		$uid = $this->_p('idnya');
		$result = $this->contentHelper->changestatus($uid,$status);
		
	//	pr($result);exit;
			if ($result['status']==2){ 
			print json_encode(array('status'=>'2', 'data'=>$result));
			
		}else{ 
			print json_encode(array('status'=>'1', 'data'=>$result));
		}
		//pr($result);exit;
		exit;
				
	}
	
	function printer(){
			
	
		global $LOCALE,$CONFIG; 
		
		//pr('sadsa');exit;
		$status=$this->_p('Print');
		$uid = $this->_p('idnya');
		
		// pr($status);
		// pr($uid);
		// exit;
		$result = $this->contentHelper->printer($uid,$status,$CONFIG['LOCAL_PUBLIC_ASSET']);
		
		//pr($result);exit;
			if ($result['status']==2){ 
			print json_encode(array('status'=>'2', 'data'=>$result));
			
		}else{ 
			print json_encode(array('status'=>'1', 'data'=>$result));
		}
		//pr($result);exit;
		exit;
				
	}
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
		$ajax =	$this->contentHelper->listnews2();
		
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

}
?>