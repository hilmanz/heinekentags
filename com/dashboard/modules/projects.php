<?php
class projects extends App{	
	
	function beforeFilter(){
		global $CONFIG;
		$this->settingHelper = $this->useHelper('settingHelper');
		$this->projectsHelper = $this->useHelper('projectsHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);		
		$this->assign('app_assets', $CONFIG['ASSETS_DOMAIN_APP']);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('user',$this->user);
	}
	
	function main(){
		$projectList = $this->settingHelper->projectList();
		//pr($projectList);exit;
		$this->assign('projectList',$projectList);
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'/apps/projects.html');	
	}

	function add(){
		global $CONFIG;
		//var_dump($this->_p('submit'));
		if ($this->_p('submit')){		
			$insertnewdata = $this->settingHelper->insertbrand();
			//pr($insertnewdata);exit;
			if($insertnewdata['status']==1){
		
				sendRedirect($CONFIG['DASHBOARD_DOMAIN']."projects");
				return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
			}else{
				$this->assign('status',$insertnewdata);
			}	
		} 
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/add-new-projects.html');
	}

	function edit(){
		global $CONFIG;
		$load = $this->settingHelper->getProjectDetail();
		$this->assign('load',$load);
		
		if ($this->_p('submit')){		
			$insertnewdata = $this->settingHelper->updateProject();
			//pr($insertnewdata);exit;
			if($insertnewdata['status']!=2){		
				sendRedirect($CONFIG['DASHBOARD_DOMAIN']."projects");
				return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
			}else{
				$this->assign('status',$insertnewdata);
			}		
		} 
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-new-projects.html');
	}
	
	function event(){
		$result = $this->settingHelper->getEvent(true);
			
		$dataapps = $this->settingHelper->getAppsID();
		
		
		$this->assign('datacustomize',$result); 
		$this->assign('dataapps',$dataapps);

		$field_list = $this->projectsHelper->getMandatoriFieldList();
		$this->assign('field_list',$field_list);

		$plugin_list = $this->projectsHelper->getPlugin();
		$this->assign('plugin_list',$plugin_list); 
		//pr($plugin_list);exit;
		$this->assign('id',intval($this->_g('id'))); 
		
	  	return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/add-new-events.html');
	}
	
	function eventList(){
		
		$getEventList = $this->settingHelper->getEventList($start=null,$limit=5);		
		// pr($getEventList);
		$this->assign('id',intval($this->_g('id')));
		$this->assign('total',intval($getEventList['total']));
		$this->assign('list',$getEventList['result']);
		$this->assign('userid',intval($this->_g('uid'))); 
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/events-list.html');
	}
	
	function editevent(){	
	// pr($_GET);exit;

		$getprocessupdate= $this->settingHelper->getprocessupdate();
		$plugin_list = $this->projectsHelper->getPlugin();
		$field_list = $this->projectsHelper->getMandatoriFieldList();
		// pr($plugin_list);exit;
		$this->assign('field_list',$field_list);
		$this->assign('plugin_list',$plugin_list);
		$this->assign('getprocessupdate',$getprocessupdate);		
		$this->assign('id',intval($this->_g('id'))); 
		$this->assign('brand',intval($this->_g('brand')));
		
		if($this->_p('submit')){
		
		//pr($_POST['plugin']);exit;		
			$name=strip_tags($this->_p('event_name'));

		
	
			if (ctype_alpha(str_replace(' ', '', $name))==false||$name=='NAME'||$name=='')
			{
				$gros=array('msgn'=>'* Please enter your event name to continue');;
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-events.html');
			}
			
			if(!isset($_POST['reg']))
			{
					$gros=array('reg'=>'* Please Check Registration Field');;
					$this->assign('status',$gros);
					return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-events.html');
			}
			// if(!isset($_POST['plugin']))
			// {
					// $gros=array('addon'=>'* Please Check AddOnn Field');;
					// $this->assign('status',$gros);
					// return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-events.html');
			// }
			
			
			
			
			$updatedataevent = $this->settingHelper->updatedataevent();
		
			sendRedirect($CONFIG['DASHBOARD_DOMAIN']."eventList?id={$this->_g('brand')}");
			return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
		}
		  			
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-events.html');
	}
	
	function eventdelete(){
		global $CONFIG;
		
		$result = $this->settingHelper->deletedataevent();
		// var_dump($result);exit;

		sendRedirect($CONFIG['DASHBOARD_DOMAIN']."projects");exit;
		return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();

	}
	
	function ajaxPaging()
	{
		
		$start = $this->_p('start');
		
		if ($this->_p('ajax')){
			$ajax = $this->settingHelper->getEventList($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}

	function publish(){
		global $CONFIG;
		$proID = intval($this->_g('id'));


		$rr = $this->settingHelper->projectPublish($proID);
		//var_dump($rr);exit;
		sendRedirect($CONFIG['DASHBOARD_DOMAIN']."projects");
		return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
		//return $this->View->toString(TEMPL ATE_DOMAIN_DASHBOARD .'/apps/projects.html');
	}

	function create_event(){
		global $CONFIG;
		if(intval($this->_p('create_event'))){
			$id = intval($this->_g('id'));
			$create= $this->settingHelper->createEvent();

			if($create){
				sendRedirect($CONFIG['DASHBOARD_DOMAIN'].'projects');
				return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
			}
		}
	}

	function design(){
		$datatemplate= $this->settingHelper->getDesign();
  		
		$data = array();
		foreach ($datatemplate as $key => $value) {
			if($value['sections']){
				$keys = str_replace("-","_",$value['sections']);
				$data[$keys]=array('id'=>$value['id'],'brand'=>$value['brand'],'sections'=>$value['sections'],'type'=>$value['type'],'values'=>$value['values'],'field'=>$value['updateon']);
			}
			
		}
		//pr($data);exit;
		$this->assign('data',$data);

		$this->assign('id',intval($this->_g('id'))); 
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/template-project.html');
	}

	function hapus(){
		global $CONFIG;
	
		$cidStr = intval($this->_request('id'));
		
		$result = $this->settingHelper->getHapus($cidStr);
		if($result) {
			sendRedirect($CONFIG['DASHBOARD_DOMAIN']."projects");
			return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
		}
	}

	function save_design(){
		global $CONFIG; 
	 
		$data['result'] = false;
		$data['message'] = "halt!!";
		$data['filename']='';
		
		$images = array();
		$filenames = false;
		$brand = $this->_p('brand');	 
		//error_reporting(E_ALL);
		//var_dump($_FILES['home_bg']);exit;
		
		if(isset($_FILES['home_bg'])&&$_FILES['home_bg']['name']!=NULL) {

			if (isset($_FILES['home_bg'])&&$_FILES['home_bg']['size'] <= 20000000) {
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/home/";
					$images = $this->uploadHelper->uploadThisImage($_FILES['home_bg'],$path);
				 	
					
					$filenames['home_bg']= $images['arrImage']['filename'];
					//$data['filename']= $CONFIG['BASE_DOMAIN_PATH']."public_assets/content/{$sections}/{$images['arrImage']['filename']}";
									
			}
		}
		if(isset($_FILES['main_bg'])&&$_FILES['main_bg']['name']!=NULL) {
			if (isset($_FILES['main_bg'])&&$_FILES['main_bg']['size'] <= 20000000) {
					 $path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/global/";
					$images = $this->uploadHelper->uploadThisImage($_FILES['main_bg'],$path);
				 
					
					$filenames['main_bg']= $images['arrImage']['filename'];
									
			}
		}

		if(isset($_FILES['button-menu'])&&$_FILES['button-menu']['name']!=NULL) {
			if (isset($_FILES['button-menu'])&&$_FILES['button-menu']['size'] <= 20000000) {
					 $path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/button-menu/";
					$images = $this->uploadHelper->uploadThisImage($_FILES['button-menu'],$path);				 
					$filenames['button-menu']= $images['arrImage']['filename'];
			}
		}
		if(isset($_FILES['button-submit'])&&$_FILES['button-submit']['name']!=NULL) {
			if (isset($_FILES['button-submit'])&&$_FILES['button-submit']['size'] <= 20000000) {
					 $path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/button-submit/";
					$images = $this->uploadHelper->uploadThisImage($_FILES['button-submit'],$path);				 
					$filenames['button-submit']= $images['arrImage']['filename'];
			}
		}
		if(isset($_FILES['button-submit-disable'])&&$_FILES['button-submit-disable']['name']!=NULL) {
			if (isset($_FILES['button-submit-disable'])&&$_FILES['button-submit-disable']['size'] <= 20000000) {
					 $path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/button-submit-disable/";
					$images = $this->uploadHelper->uploadThisImage($_FILES['button-submit-disable'],$path);				 
					$filenames['button-submit-disable']= $images['arrImage']['filename'];
			}
		}

		// if(isset($_FILES['search_bg'])&&$_FILES['search_bg']['name']!=NULL) {
		// 	if (isset($_FILES['search_bg'])&&$_FILES['search_bg']['size'] <= 20000000) {
		// 			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/search/";
		// 			$images = $this->uploadHelper->uploadThisImage($_FILES['search_bg'],$path);
				 
					
		// 			$filenames['search_bg']= $images['arrImage']['filename'];
									
		// 	}
		// }
		// exit;
		
		$result = $this->settingHelper->updateDesignRow2($filenames);

		if($this->user->type<666) $brand = $this->user->brand;
		else $brand = $this->_p('brand');
			sendRedirect($CONFIG['DASHBOARD_DOMAIN']."projects/design?id={$brand}");
			return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
		
	}
}

?>