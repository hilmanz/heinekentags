<?php 

class reportHelper {

	function __construct($apps){
		global $CONFIG,$logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshemaWeb =$CONFIG['DATABASE'][0]['DATABASE'];	
		$this->dbshema =$CONFIG['DATABASE'][0]['DATABASE'];	
	}

	function listoftemplates($start=null,$limit=5){
		global $CONFIG;
		//pr($this->uid);exit;
		$commonuser='';
		if($this->apps->user->type==1)
		{
			$commonuser=" AND tt.userid={$this->uid}";
		}
		
		$rs['result'] = false;
		$rs['total'] = 0;		

		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$sql = "SELECT COUNT(1) total 
				FROM {$this->dbshemaWeb}.tbl_template tt
				LEFT JOIN  {$this->dbshemaWeb}.social_member sm ON tt.userid = sm.id
				LEFT JOIN  {$this->dbshemaWeb}.my_profile mp ON tt.userid = mp.ownerid"; 
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
				
		$sql = "SELECT tt . * , sm.name, mp.brand
				FROM  {$this->dbshemaWeb}.tbl_template tt
				LEFT JOIN  {$this->dbshemaWeb}.social_member sm ON tt.userid = sm.id 
				LEFT JOIN  {$this->dbshemaWeb}.my_profile mp ON tt.userid = mp.ownerid
				WHERE 1 {$commonuser} LIMIT {$start}, {$limit}";
		//	pr($sql);exit;	
		$rsut = $this->apps->fetch($sql,1);		
		if(!$rsut){ return false;} 
		$no = 1;
		foreach ($rsut as $key => $val){
			$rsut[$key]['thanksbackground'][] = unserialize($val['thankyou_bg']);
			$rsut[$key]['loginbackground'][]= unserialize($val['login_bg']); 
			$rsut[$key]['no'] = $no++;
		}
		
		$rs['result'] = $rsut;
		$rs['total'] = intval($total['total']); 
		return $rs; 		
	}
	
	function insertnewsupdate($databg=false, $datatq=false){ 
		global $CONFIG;
	//	pr($_POST);exit;
		$bgPotrait = $_FILES['bgPotrait']; 
		$bgLandscape = $_FILES['bgLandscape']; 
		$tqpotrait = $_FILES['tqpotrait'];
		$tqlandscape = $_FILES['tqlandscape'];
						  
		$login_type = $this->apps->_p('login_type');
		
		$fbid = $this->apps->_p('fbid');
		
		$valfb='';
		$fieldfbid='';
		
		if($fbid <> '')
		{
		$fieldfbid=', `fb_id` ';
		$valfb=",'{$fbid}'";
		}
		
		
		$twitid=$this->apps->_p('twitid');
		$twitsec=$this->apps->_p('twitsec');
		$twitfol=$this->apps->_p('twitfol');
		
		if($login_type=='1')
		{
			if($fbid == '')
				{
					$fieldfbid=', `fb_id` ';
					$valfb=",'535051416622024'";
				}
		}
		else if($login_type=='2')
		{
			if($twitid == '')
				{
					$twitid=$this->apps->_p('twitid');
					$twitsec=$this->apps->_p('twitsec');
					
				}
			if($twitfol=='')
				{
					$twitfol='326592068';
				
				}
		}
		else
		{
			if($fbid == '')
				{
					$fieldfbid=', `fb_id` ';
					$valfb=",'535051416622024'";
				}
		
		}
		$redirecturi = $this->apps->_p('redirecturi');	
		$client_type = $this->apps->_p('client_type');	
		$uid = $this->apps->_g('id');	
		$submit_date = date('Y-m-d H:i:s');
		$modified_date = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO {$this->dbshemaWeb}.`tbl_template` (`userid`, `login_bg`, `thankyou_bg`, `login_type`, `redirect_url`, `submit_date`, `modified_date` {$fieldfbid} ,
		`twitter_id`,`twitter_secret`,`twitter_follow`,`n_status`)
				VALUES ('{$uid}', '{$databg}','{$datatq}','{$login_type}','{$redirecturi}', '{$submit_date}', '{$modified_date}' {$valfb},'{$twitid}','{$twitsec}','{$twitfol}', 1)";  
		//pr($sql);exit;
		$qData = $this->apps->query($sql);
		return $this->apps->getLastInsertId();;
	} 
	
	function clientname(){
		global $CONFIG; 
		
		$sql = "SELECT * FROM {$this->dbshema}.social_member WHERE 1 GROUP BY name ";		
		$rs = $this->apps->fetch($sql,1);
		return $rs;
	
	}
	function cekid($cidStr){
		
		global $CONFIG; 
		$sql = "SELECT * FROM {$this->dbshema}.tbl_template WHERE userid={$cidStr} GROUP BY userid";
		//pr($sql);exit;
				$rsut = $this->apps->fetch($sql,1);
		if($rsut)
		{
		foreach ($rsut as $key => $val){
			$rsut[$key]['thanksbackground'][] = unserialize($val['thankyou_bg']);
			$rsut[$key]['loginbackground'][]= unserialize($val['login_bg']); 
			
		}
		}
		
		$rs['result']=$rsut;
		//pr($rs);exit;
		return $rs;
	
	}
	
	
	function getHapus($cidStr){
		global $CONFIG;
		
		if($this->apps->user->type<666) return false;
		
		if($cidStr){
			$sql = "delete {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_template from tbl_template where id={$cidStr} ";
	
			// pr($sql);exit;
			$qdata  =  $this->apps->query($sql);

					if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
	
	function getProjectDetail(){
		global $CONFIG;
		$id = intval($this->apps->_g('id'));
		if(!$id) $id = $this->apps->user->id;
		$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_template WHERE userid={$id}";
		// pr($sql);
		$rs = $this->apps->fetch($sql,1);
		if($rs)
		{
		foreach ($rs as $key => $val){
			$rs[$key]['thanksbackground'][] = unserialize($val['thankyou_bg']);
			$rs[$key]['loginbackground'][]= unserialize($val['login_bg']); 
		}
		}
		// pr($rs);
		return $rs;
	}		
	
	function updateProject($databg=false, $datatq=false){ 
		global $CONFIG;
		$tid = $this->apps->_g('id');
		
		$bgPotrait = $_FILES['bgPotrait']; 
		$bgLandscape = $_FILES['bgLandscape']; 
		$tqpotrait = $_FILES['tqpotrait'];
		$tqlandscape = $_FILES['tqlandscape'];
		
		$login_type = $this->apps->_p('login_type');
		$redirecturi = $this->apps->_p('redirecturi');	
		$client_type = $this->apps->_p('client_type');
		$uid = $this->apps->_g('id');		
		$submit_date = date('Y-m-d H:i:s');
		$modified_date = date('Y-m-d H:i:s');
		$fbid = $this->apps->_p('fbid');
	    $twitid=$this->apps->_p('twitid');
		$twitsec=$this->apps->_p('twitsec');
		$twitfol=$this->apps->_p('twitfol');
		
		 if($login_type=='1')
		{
			if($fbid == '')
				{
					$fbid="535051416622024";
				}
		}
		else if($login_type=='2')
		{
			if($twitid == '')
				{
					$twitid='GubKfgkx8q976eTJZpLYXkIA1';
					$twitsec='w9A6jiLrzc5OpBjtlSMN3RwvQwp2QyTDWnzf3KCV3eIcvhyZd5';
					
				}
			if($twitfol=='')
				{
					$twitfol='326592068';
				
				}
		}
		else
		{
			if($fbid == '')
				{
					
					$fbid="535051416622024";
				}
		
		}		
		$sql = "UPDATE {$this->dbshemaWeb}.`tbl_template` 
				SET `userid`= '{$uid}',
					`login_bg`= '{$databg}', 			
					`thankyou_bg`= '{$datatq}',
					`login_type`='{$login_type}',		
					`redirect_url`='{$redirecturi}',
					`submit_date`='{$submit_date}',
					`fb_id`='{$fbid}',
					`twitter_id`='{$twitid}',
					`twitter_secret`='{$twitsec}',
					`twitter_follow`='{$twitfol}',
					`modified_date`='{$modified_date}',
					`n_status`= 1 WHERE userid = {$tid} ";
		
		//pr($sql);exit;
		$qData = $this->apps->query($sql);
		return $qData;
	}
	function addProject($databg=false, $datatq=false){ 
		global $CONFIG;
		$tid = $this->apps->_g('id');
		
		$bgPotrait = $_FILES['bgPotrait']; 
		$bgLandscape = $_FILES['bgLandscape']; 
		$tqpotrait = $_FILES['tqpotrait'];
		$tqlandscape = $_FILES['tqlandscape'];
		
		$login_type = $this->apps->_p('login_type');
		$redirecturi = $this->apps->_p('redirecturi');	
		$client_type = $this->apps->_p('client_type');
		$uid = $this->apps->_g('id');		
		$submit_date = date('Y-m-d H:i:s');
		$modified_date = date('Y-m-d H:i:s');
		
		 		
		$sql = "insert into {$this->dbshemaWeb}.`tbl_template` 
				SET `userid`= '{$uid}',
					`login_bg`= '{$databg}', 			
					`thankyou_bg`= '{$datatq}',
					`login_type`='{$login_type}',		
					`redirect_url`='{$redirecturi}',
					`submit_date`='{$submit_date}',
					`modified_date`='{$modified_date}',
					`n_status`= 1";
		
		// pr($sql);exit;
		$qData = $this->apps->query($sql);
		return $qData;
	}
	
	function joinuser(){
		global $CONFIG;

		$sql = "select sm.id , mp.ownerid, tp.userid,sm.username, mp.type from social_member as sm 
				left join my_profile as mp on sm.id=mp.ownerid 
				left join tbl_template as tp on sm.id=tp.userid
				where sm.id={$this->uid}";
				//pr($sql);exit;
		$rs = $this->apps->fetch($sql,1);
		//pr($sql);exit;
		return $rs;
	}
	
}

?>

