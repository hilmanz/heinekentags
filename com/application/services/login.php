<?php
class login extends ServiceAPI{
		
	function beforeFilter(){
		$this->loginHelper = $this->useHelper('loginHelper');
	 
		
	}
	 
	 function account(){
	 
	 		global $CONFIG,$logger;
	
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
	 
			if($this->_p('username')&&$this->_p('password')){
			 
				$res = $this->loginHelper->loginSession();
				if($res){
						 
							return array(
							'status'=>true,
							'code'=>"1", 
							'msg'=>'welcomes',
							'login_count'=>@$this->session->getSession($CONFIG['SESSION_NAME'],"WEB")->login_count,							 
							'user_mail'=>@$this->session->getSession($CONFIG['SESSION_NAME'],"WEB")->email
							);
				}else{
						return array(
						'status'=>false, 
						'code'=>"0", 
						'msg'=>'Whoops! Please check your credentials and try again',
						'login_count'=>0,
						'user_mail'=>''
						);
				}
			}
		
				print  $this->error_404();exit;
		 
	 
	 }
	 
	function theme(){
	
		global $CONFIG;
			$data =array();
		$themes = strip_tags($this->_p('themes'));
		$brandid = strip_tags($this->_p('brandid'));
		$qBrand = "";
		if($themes!='') $qBrand = " AND codename='{$themes}' ";
		if($brandid!='') $qBrand = " AND id='{$brandid}' ";
		if($qBrand=='') return $data;
		$sql =" SELECT * FROM tbl_brand_master WHERE n_status = 1 {$qBrand} LIMIT 1"; 
		$branddata = $this->fetch($sql);
		$brand = 0;
		if($branddata){
			$brand = $branddata['id'];
		}else return $data;
		 
		if($brand==0) return $data;
				
	
		$sql ="SELECT * FROM customize_templates WHERE brand={$brand} AND n_status = 1 ";
		$qData = $this->fetch($sql,1);
		// pr($sql);
		if($qData) { 
		
			foreach($qData as $key => $val){
			 
				
				if($val['color']) $data[$val['sections']] = $val['color'];
				if($val['size']) $data[$val['sections']] = $val['size'];
				
				if($val['images']){
				
					if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}content/{$val['sections']}/{$val['images']}")) $val['images'] = false;
				
					if($val['images']) $qData[$key]['images_full_path'] = $CONFIG['PUBLIC_ASSETS_DOMAIN_PATH']."content/{$val['sections']}/". $val['images'];
					else $qData[$key]['images_full_path']= $CONFIG['PUBLIC_ASSETS_DOMAIN_PATH']."content/{$val['sections']}/default.jpg";					
					
					$data[$val['sections']] =$qData[$key]['images_full_path'];
				}
			}
			$data['id']=(string)$brand;
			return $data;
		
		}else return false;
		
		
	}
	
	function brandlist(){
	
			$sql =" SELECT id brandid,codename brandname FROM tbl_brand_master WHERE n_status = 1 ";
			$data = $this->fetch($sql,1);
	 
			return $data;
	}
	
	 
	  
	
}
?>
