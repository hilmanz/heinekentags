<?php
class video extends App{
	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
			
		$this->videoHelper = $this->useHelper('videoHelper');
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);		
		$this->assign('locale', $LOCALE[1]);
		
		
	}
	
	function main(){
			$video=$this->videoHelper->listvideo();
		
		
		
		$this->assign('list',$video['result']);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listvideo.html');		
	}
	function videoDetail(){
		$id=$this->_g('id');
		$video=$this->videoHelper->getVideoview($id);
		
		$this->assign('list',$video);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/video_view.html');		
	}
	
	
 
	
	
	 
}
?>