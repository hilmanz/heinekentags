<?php
class home extends App{
		
	function beforeFilter(){ 
		global $LOCALE,$CONFIG; 
		$this->registerHelper = $this->useHelper("registerHelper");
		
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('locale', $LOCALE[1]);
		$this->assign('user', $this->user);
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));	
		
		$this->assign('projects', intval($this->_g('projects')));
		
		$cityList = $this->registerHelper->cityList(); 
		$this->assign('cityList',$cityList);
		$brandlist = $this->registerHelper->brandlist();
		
		$this->assign('brandlist', $brandlist);
	
		
		
	}
	function tgl_indo($tgl){
		$tanggal = substr($tgl,8,2);
		//$bulan = getBulan(substr($tgl,5,2));
		$bulan 	= substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;		 
		}	
	 
	function main(){
		
		
		$rdate = $this->registerHelper->sevendate();	
		//pr($rdate);exit;
		$time['time'] = '%H:%M:%S';
		
		$this->assign('highdate',$rdate['collected']);
		$this->assign('facebook',$rdate['fb']);
		$this->assign('twitter',$rdate['twit']);
		$this->assign('time',$time);
		$this->assign('notiftype',intval($this->_p('notiftype')));
		$this->assign('publishedtype',intval($this->_p('publishedtype')));
		$this->assign('search',strip_tags($this->_p('search')));
		$this->assign('startdate',$this->_p('startdate'));
		$this->assign('enddate',$this->_p('enddate'));
		$this->assign('getid',$this->_g('brandid'));
		$this->log('surf','users');
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/dashboard-report.html');
		
		
		
	}
	function hapus(){
		global $CONFIG;
	
		$cidStr = intval($this->_request('id'));
		
		$result = $this->registerHelper->getHapus($cidStr);
		if($result) {
			sendRedirect($CONFIG['DASHBOARD_DOMAIN']."users");
			return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
		}
	}
	
	function newreg(){
		global $CONFIG;
		$password = $this->_p('password');
		$repassword = $this->_p('repassword');
		
		
		if ($this->_p('submit')){
			
			$email = $this->_p('username');
			//pr($_POST);exit;
			
			// $startdate = date('Y-m-d', strtotime($this->_p('startdate')));
			// echo $startdate ;exit;
			$valid_email = $this->is_valid_email($email);
			
			if($this->_p('role')=="")
			{ 
				print json_encode(array('status'=>5,'msg'=>'* Please enter the role’s name'));
				exit;
			}
				if($this->_p('brandsubid')=="")
			{ 
				print json_encode(array('status'=>6,'msg'=>'* Please enter the brand Name'));
				exit;
			}
			
			if($this->_p('name')=="")
			{ 
			
				print json_encode(array('status'=>8,'msg'=>'* Please enter the name'));
				exit;
			}
			
			if($this->_p('city')=="")
			{ 
				print json_encode(array('status'=>7,'msg'=>'* Please enter the city'));
				exit;
			}
		
			if(!$valid_email)
			{ 
				print json_encode(array('status'=>2,'msg'=>'* Invalid email format'));
				exit;
			}
			
			if($this->_p('username')=="" )
			{ 
				print json_encode(array('status'=>2,'msg'=>'* Invalid email format'));
				exit;
			}
			if($this->_p('startdate')=="" )
			{ 
				print json_encode(array('status'=>9,'msg'=>'* Invalid Date'));
				exit;
			}
			if($this->_p('enddate')=="" )
			{ 
				print json_encode(array('status'=>0,'msg'=>'* Invalid Date'));
				exit;
			}
			if($password!=$repassword || $password==""){
				print json_encode(array('status'=>4,'msg'=>'* The passwords you entered did not match'));
				exit;
			}
			$valid_pass=preg_match('/\A(?=[\x20-\x7E]*?[A-Z])(?=[\x20-\x7E]*?[a-z])(?=[\x20-\x7E]*?[0-9])[\x20-\x7E]{6,}\z/', $password);	
				if(!$valid_pass){
					print json_encode(array('status'=>4,'msg'=>'* The password you entered doesn\'t meet the minimum security requirements'));
					exit;
				} 
			
			$insertnewdata = $this->registerHelper->insertnewdata();
		
			//pr($insertnewdata);exit;
			
			if($insertnewdata)
			{ 
				print json_encode(array('status'=>1,'msg'=>'New user has successfuly added','dataid'=>$insertnewdata));
				exit;
			}
			
			
		}  
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/create-user.html');
	
	}
	
	function registeredit()
	{
		global $CONFIG;
		
		
		$id = intval($this->_request('id'));
		if($this->_p('editit')=='ok'){ 
		//pr($_POST);exit;

		//check valid name
						
			$name=strip_tags($this->_p('name'));
			$username=strip_tags($this->_p('username'));
			$role=strip_tags($this->_p('role'));
			$brandsubid=strip_tags($this->_p('brandsubid'));
			$email = $this->_p('username');
			$startdate = strip_tags($this->_p('startdate'));  
			$enddate = strip_tags($this->_p('enddate'));   
			// pr($_POST);exit;
			$valid_email = $this->is_valid_email($email);
			
		
			
			
			
			if ($role=='')
			{
				$gros=array('msgr'=>'* Please enter the Brand name','name'=>$name,'user'=>$username,'role'=>$role,'brand'=>$brandsubid,'startdate'=>$startdate,'enddate'=>$enddate);
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			}
			if ($brandsubid=='')
			{
				$gros=array('msgb'=>'* Please enter the Brand name','name'=>$name,'user'=>$username,'role'=>$role,'brand'=>$brandsubid,'startdate'=>$startdate,'enddate'=>$enddate);
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			}
						
			//echo $name;exit;
			if ($name=='NAME'||$name=='')
			{
				$gros=array('msgn'=>'* Please enter the role’s name','user'=>$username,'role'=>$role,'brand'=>$brandsubid,'startdate'=>$startdate,'enddate'=>$enddate);
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			}
			if (!$valid_email || $email=='')
			{
				$gros=array('msgu'=>'* Invalid email format','user'=>$username,'role'=>$role,'brand'=>$brandsubid,'startdate'=>$startdate,'enddate'=>$enddate);
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			}
			if ($startdate=='')
			{
				$gros=array('msgs'=>'* Please enter the startdate','user'=>$username,'role'=>$role,'brand'=>$brandsubid,'enddate'=>$enddate);;
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			}
			if ($enddate=='')
			{
				$gros=array('msge'=>'* Please enter the enddate','user'=>$username,'role'=>$role,'brand'=>$brandsubid,'startdate'=>$startdate);
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			}
			
			$updatethedata = $this->registerHelper->updatethedata($id);  
			
			
			
			//pr($updatethedata);exit;
			if($updatethedata['status']==2) 
			{ 
				$grop=array('msgp'=>'* The password you entered doesn\'t meet the minimum security requirements','user'=>$username,'name'=>$name,'role'=>$role,'brandsubid'=>$brandsubid);
				$this->assign('status',$grop);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			
			}
			if($updatethedata['status']==3) 
			{ 
				$grop=array('msgp'=>'* The passwords you entered did not match','user'=>$username,'name'=>$name,'role'=>$role,'brandsubid'=>$brandsubid);
				$this->assign('status',$grop);
				return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
			
			}
		
			if($updatethedata==true){
				sendRedirect($CONFIG['DASHBOARD_DOMAIN']."users");
				return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
			}
		}	
		$selectupdatedata = $this->registerHelper->selectupdatedata($id);
		// pr($selectupdatedata);
		$this->assign('selectupdatedata',$selectupdatedata); 
		
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/register-edit.html');
	}
	
	function ajaxPaging(){
		
		$start = $this->_p('start');
		
		if ($this->_p('ajax')){
			$ajax = $this->registerHelper->registerList($start);
		}
		//pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}	

	function ajax(){
		$orderType = strip_tags($this->_p('orderType'));
		$orderBy = strip_tags($this->_p('orderBy'));
		$start = strip_tags($this->_p('start'));
		$search = strip_tags($this->_p('search'));
		if($search=="") $search=null;
		$limit = 20; 
		$res = $this->registerHelper->registerList($start,$limit);
		print json_encode($res);exit;
	}
	
	function edit(){
		
		$res = $this->registerHelper->userlists();
		if($res){
			foreach($res as $key => $val){
				$this->assign($key,$val);
			}
		}
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/edit-user.html');
	}
	
	function unusers(){
		global $CONFIG;
	
		$res = $this->registerHelper->unusers();
		sendRedirect( $CONFIG['BASE_DOMAIN']."register");
		return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
						die();
	}
}
?>