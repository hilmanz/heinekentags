<?php
class project extends App{

	function beforeFilter(){
		global $LOCALE,$CONFIG;
		
		$this->settingHelper = $this->useHelper('settingHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('user',$this->user);
	}
	
	function main(){
	
		$projectList = $this->settingHelper->projectList();
		// pr($sdklisttemplate);
		$this->assign('projectList',$projectList);
		
		$this->log('surf','registrant_management');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'/apps/project-list.html');
		 
	}

	
	function hapus(){
		global $CONFIG;
	
		$cidStr = intval($this->_request('id'));
		
		$result = $this->settingHelper->getHapus($cidStr);
		if($result) {
			sendRedirect($CONFIG['DASHBOARD_DOMAIN']."project");
			exit;
		}
	}
	
	
	function add(){
		
		global $CONFIG;
		
			if ($this->_p('submit')){
		// pr($_FILES);exit;
		// insert data dulu 
			
			$insertnewdata = $this->settingHelper->insertbrand();
			if($insertnewdata)
			{
		
				sendRedirect($CONFIG['DASHBOARD_DOMAIN']."project");
			}
			

		} 
	
			return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/add-new-brand.html');
	
	}
	
	function edit()
	{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		if($this->_p('editit')=='ok'){ 
			
			// pr($_FILES);
			// exit;
	
		
			$updatethebrand = $this->settingHelper->updatethebrand($id);  
			
			// exit;
			if($updatethebrand==true){
										sendRedirect($CONFIG['DASHBOARD_DOMAIN']."project");
									}
		}	
		
		
		$selectupdatebrand = $this->settingHelper->selectupdatebrand($id);
		// pr($selectupdatedata);
		$this->assign('selectupdatebrand',$selectupdatebrand); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-new-brand.html');
	}
	
	
}
?>