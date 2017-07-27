<?php
class buku extends App{
	
	function beforeFilter(){
		global $LOCALE,$CONFIG;
			
		$this->bukuHelper = $this->useHelper('bukuHelper');
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);		
		$this->assign('locale', $LOCALE[1]);
		
		
	}
	
	function main(){
			$buku=$this->bukuHelper->listbuku();
		
		
		
		$this->assign('list',$buku['result']);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/listbuku.html');		
	}
	function bukuDetail(){
		$id=$this->_g('id');
		$buku=$this->bukuHelper->getBukuView($id);
		//pr($buku);die;
		$this->assign('list',$buku);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/buku_view.html');		
	}
	
	
 
	
	
	 
}
?>