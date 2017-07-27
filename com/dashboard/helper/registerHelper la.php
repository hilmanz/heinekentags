<?php
class registerHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
	}
	
	
	function registerPhase(){
		$ok = false;
		global $CONFIG;
		
		if($this->apps->_p('register')==1){
		
			$this->logger->log('can register');
			$reg = $this->doRegister();
			return $reg;
		}
		$this->logger->log('can not register');
		return false;
	}
	
	
	function roleList()
	{
		global $CONFIG;
		 
		$qtype = "   AND id <= {$this->apps->user->type} ";
		 
		$rolelist = "
		SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_profile_type WHERE 1  {$qtype} ";
			// pr($rolelist);exit;
		 
		$rqData = $this->apps->fetch($rolelist,1);
	
		return $rqData;
		
	}
		function sevendate()
	{
		global $CONFIG;
		// echo "ss";exit;
		
		
		$brandid = $this->apps->_g('brandid');
		
		
		$qbjoin = "";
		$qbwhere = ""; 
		if($brandid != '')
			{		
				$qbjoin = "left join brandifi_2014.tbl_template as tt on tt.id=tr.tpl_id";
				$qbwhere = "and tt.userid='{$brandid}'"; 
				//pr($qBrandid);exit;
			}
	
		$querytgl = "select DATE_FORMAT(date(collected_date),'%d/%m/%Y') as dateok,count(date(collected_date)) as forcount from {$CONFIG['DATABASE_WEB']}.tbl_reporting  as tr  {$qbjoin} where fb_id <> '' {$qbwhere} group by date(collected_date)";
		//pr($querytgl);exit;
		$rqData = $this->apps->fetch($querytgl,1);
	
		//pr($rqData);exit;
		
		$querytgl2 = "select DATE_FORMAT(date(collected_date),'%d/%m/%Y') as dateok,count(date(collected_date)) as forcount from {$CONFIG['DATABASE_WEB']}.tbl_reporting  as tr {$qbjoin} where twitter_id <> '' {$qbwhere} group by date(collected_date)";
		//pr($querytgl);exit;
		$twitdata = $this->apps->fetch($querytgl2,1);
		
		$tgl='';
		$i=0;
		$tgl['twit'] ='';
		foreach($twitdata as $key => $val){
		if ($i==0)
				{
				$tgl['twit'].=$val['forcount'];
				}else
				{
		
				$tgl['twit'].=",".$val['forcount'];
				}
				$i++;
		}
	
		$j=0;
		$tgl['collected'] ='';
		$tgl['fb'] ='';
		foreach($rqData as $key => $val){
		if ($j==0)
				{
				$tgl['collected'].="'".$val['dateok']."'";
				$tgl['fb'].=$val['forcount'];
				}else
				{
				$tgl['collected'].=",'".$val['dateok']."'";
				$tgl['fb'].=",".$val['forcount'];
				}
				$j++;
		}
		
		//pr($tgl);exit;
		return $tgl;
		
	}
	
	
	function brandlist(){
		global $CONFIG;
		
		$sql = "SELECT id, name FROM  {$CONFIG['DATABASE'][0]['DATABASE']}.social_member WHERE 1 GROUP BY name";
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	
	}

	function cityList()
	{
		global $CONFIG;
		$citylist = "
		SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.city_reference";
			//pr($sql);exit;
		// pr($sql);
		$cityData = $this->apps->fetch($citylist,1);
	
		return $cityData;
		
	}
	
	
	
	function registerList($start=null,$limit=10)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
	  
		// $projectid = intval($this->apps->_g('projects'));
		$qProject = "  ";
		if($this->apps->user->type==1){
			$qProject = " AND mp.brand = '{$this->apps->user->brand}' ";
		}
		$excludeGod="";
		if($this->apps->user->type!=666){
			$excludeGod = "AND mp.type <> 666";
		}
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (name LIKE '%{$search}%' )";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND postdate >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND postdate < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.social_member  sm
			LEFT JOIN {$CONFIG['DATABASE'][0]['DATABASE']}.my_profile mp ON sm.id=mp.ownerid 
	
			WHERE 1 AND mp.type=1 {$qProject} {$excludeGod} ";
		$total = $this->apps->fetch($sql);		
		
	//pr($sql);exit;
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *,sm.name as nama ,(select Definition from my_profile_type where id=mp.type) as types
			,DATE_FORMAT(sm.register_date,'%d/%m/%Y') as regisdate,DATE_FORMAT(mp.created_date,'%d/%m/%Y') as createdate,DATE_FORMAT(mp.closed_date,'%d/%m/%Y') as closeddate,sm.id as smname FROM  {$CONFIG['DATABASE'][0]['DATABASE']}.social_member  sm
			LEFT JOIN {$CONFIG['DATABASE'][0]['DATABASE']}.my_profile mp ON sm.id=mp.ownerid 
			WHERE 1 AND mp.type=1 {$qProject} {$excludeGod}
			ORDER BY sm.id DESC,sm.id DESC LIMIT {$start},{$limit}
				
	"; 
		//pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);

		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;

				$sql = "SELECT COUNT(*) total_data
						FROM {$CONFIG['DATABASE'][0]['DATABASE']}.registrant_data
						WHERE ownerid = {$val['ownerid']} AND verified = 1 LIMIT 1";
				// if($val['ownerid']==47){
				// 	pr($sql);
				//  	pr(intval($this->apps->fetch($sql)));exit;
				//  }
				$total_registrant = $this->apps->fetch($sql);
				$rqData[$key]['total_registrant'] = intval($total_registrant['total_data']);
			}
			//pr($rqData);exit;
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	}
	
	function getHapus($cid){
		global $CONFIG;
		
		if($cid){
			$sql = "delete social_member,my_profile FROM social_member LEFT JOIN my_profile ON social_member.id=my_profile.ownerid  WHERE social_member.id={$cid} ";
	
			//pr($sql);exit;
			$qdata  =  $this->apps->query($sql);

					if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
	protected function encrypt($string)
	{	
		$ENC_KEY='youknowwho2014';
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($ENC_KEY), $string, MCRYPT_MODE_CBC, md5(md5($ENC_KEY))));
	}
		protected function decrypt($encrypted)
	{
		$ENC_KEY='youknowwho2014';
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($ENC_KEY), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($ENC_KEY))), "\0");
	}
	function insertnewdata(){
		
		$password = trim($this->apps->_p('password'));
		$repassword = $this->apps->_p('repassword');
			global $CONFIG;
		//pr($_POST);
		$role = $this->apps->_p('role'); 
		$o_brand = $this->apps->_p('brandsubid');   
		$area_l = $this->apps->_p('areaid');   
		$name = $this->apps->_p('name');       
		$nickname = $this->apps->_p('nickname');  
		$username = $this->apps->_p('username');  
		$startdate =  date('Y-m-d', strtotime($this->apps->_p('startdate'))); 
		$enddate = date('Y-m-d', strtotime($this->apps->_p('enddate'))); 

		$brand = $this->apps->_p('brandid');  
		$city = $this->apps->_p('city');  
		$pl = $this->apps->_p('otherid'); 
		$last_name = $this->apps->_p('lastname');  
		$gender = $this->apps->_p('sex');  
		$email = $this->apps->_p('email'); 
	
		$master = $this->apps->_p('masterrole');  
		$created_date = date("Y-m-d H:i:s");
		$closed_date = date("Y-m-d H:i:s");
		
		$saltnya='12345678';
		
		$hash = $this->encrypt($password);
		
		if ($password==!NULL&&$name==!NULL)
		{
			if ($password==$repassword)
			{
			
			
				$submit = $this->apps->_p('submit');
				 //var_dump($submit);exit;
				if($submit){
					$sql1 = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.social_member (`name`, `nickname`,`email`,`username`,`sex`,`last_name`,`salt`,`password`,n_status,deviceid) 
							VALUES ('{$name}', '{$nickname}', '{$username}','{$username}','{$gender}','{$last_name}','{$saltnya}','{$hash}',1,'host@touchbaseconnect.com')";
				  	
					$res = $this->apps->query($sql1);
					$lastID = $this->apps->getLastInsertId();
					if($lastID>0)  { 
						$sql2 = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.my_profile (`name`, `created_date`,`closed_date`,`type`,`brand`,`city`,ownerid,n_status) 
							VALUES ('{$name}', '{$startdate}', '{$enddate}', '{$role}', '{$o_brand}', '{$city}', '{$lastID}',1)";
						$res = $this->apps->query($sql2);
					}
					return $lastID;
				}
				
				return false;
				
			} else return false;
			
		}else return false;
		
		return false;
	}
	
	
	
	
	 
	//untuk select data yang ada di social member dan my_profile
	function selectupdatedata($id=NULL)
	{
		
		global $CONFIG;
		$sql = "select * ,social_member.id as idola,social_member.name as nama
				,date(my_profile.created_date) as awaldate,date(my_profile.closed_date) as akhirdate
				FROM {$CONFIG['DATABASE'][0]['DATABASE']}.social_member LEFT JOIN 
				{$CONFIG['DATABASE'][0]['DATABASE']}.my_profile ON {$CONFIG['DATABASE'][0]['DATABASE']}.social_member.id={$CONFIG['DATABASE'][0]['DATABASE']}.my_profile.ownerid
				where {$CONFIG['DATABASE'][0]['DATABASE']}.social_member.id={$id} 
				
				";
		
		//pr($sql);exit;
		// fetch()
		$qData = $this->apps->fetch($sql);
		return $qData;
	
	}
	
	function updatethedata($id=NULL){
		global $CONFIG;
		//pr($_POST);exit;
		$role = $this->apps->_p('role'); 
		$o_brand = $this->apps->_p('brandsubid');   
		$area_l = $this->apps->_p('areaid');   
		$name = $this->apps->_p('name');       
		$nickname = $this->apps->_p('nickname');  
		$username = $this->apps->_p('username');  
		$startdate = $this->apps->_p('startdate');  
		$enddate = $this->apps->_p('enddate');   
		$password = trim(strip_tags($this->apps->_p('password'))); 
		$brand = $this->apps->_p('brandid');  
		$city = $this->apps->_p('city');  
		$pl = $this->apps->_p('otherid'); 
		$last_name = $this->apps->_p('lastname');  
		$gender = $this->apps->_p('sex');  
		$email = $this->apps->_p('email');
		$repassword = $this->apps->_p('confirm');
		$master = $this->apps->_p('masterrole');  
		
		// echo $repassword;
		// echo $password;exit;
		
		$ubahpassword="";
		if($password!='&bull;&bull;&bull;&bull;&bull;'){
		// echo $repassword;
		// echo "<br>";
		// echo $password;exit;
			if($password<>$repassword || $password=="" ){
				
				return array('status'=>3);
				exit;
			}
			else{
	
				//check valid Password
				$valid_pass=preg_match('/\A(?=[\x20-\x7E]*?[A-Z])(?=[\x20-\x7E]*?[a-z])(?=[\x20-\x7E]*?[0-9])[\x20-\x7E]{6,}\z/', $password);
				if(!$valid_pass)
				return array('status'=>2);
				$saltnya='12345678';
				$hash = $this->encrypt($password);
				$ubahpassword=",`salt`='{$saltnya}',`password`='{$hash}'";
			}
		}
		
		$created_date = date("Y-m-d H:i:s");
		$closed_date = date("Y-m-d H:i:s");
		
		
		$submit = $this->apps->_p('submit');
		// var_dump($submit);
				  
		$submit = $this->apps->_p('submit');
	
		if(isset($submit)){
		
			//$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.social_member SET `name` = '{$name}',`nickname`='{$nickname}',
			//		`username`='{$username}',`last_name`='{$last_name}',`sex`='{$gender}',email='{$email}' WHERE id = {$id} "; 
			$sql2 = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.my_profile SET `type`='{$role}',`brand`='{$o_brand}',`city`='{$city}',`n_status`='1' where ownerid= {$id} ";		
			$res['update'] = $this->apps->query($sql2);
			//pr($sql2);exit;
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.social_member
					SET `name`='{$name}',`username`='{$username}',email='{$username}' {$ubahpassword} WHERE id = {$id}";
//pr($sql);exit;
			$res = $this->apps->query($sql);
			if($this->apps->user->type == 666){
				$res = $this->apps->query($sql2);
			}
			
			return $res;
		}
		
		return false;
	}
	
	

	function doRegister(){
		global $CONFIG;
		$this->logger->log('do register');
		
		
		 $notsaved = "not save";
		 $saved = "saved";
		// user stat
		$masterrole = intval($this->apps->_p('masterrole'));
		$name = strip_tags($this->apps->_p('name'));
		$last_name = strip_tags($this->apps->_p('lastname'));
		$nickname = strip_tags($this->apps->_p('nickname'));
		
		$password = trim(strip_tags($this->apps->_p('password')));
		$repassword = trim($this->apps->_p('repassword'));
		$nickname = strip_tags($this->apps->_p('name'));
		//if edit
		$edit = $this->apps->_p('edit');
		$email = null;
		$email = trim(strip_tags($this->apps->_p('email')));
		if($edit!=1){
			
			$username = strip_tags($this->apps->_p('username'));
		}
		$gender = trim(strip_tags($this->apps->_p('gender')));
		
		//page stat
		// 	ownerid 	name 	description 	type 	img 	otherid	brandid 	brandsubid 	areaid 	city 	created_date 	closed_date 	n_statu
		$role = explode("_",strip_tags($this->apps->_p('role')));
		$ownerid = intval($this->apps->_p('ownerid'));
		$rolesname = $name.' '.$last_name;
		$description = strip_tags($this->apps->_p('description'));
		$type =$role[1];
		$img = strip_tags($this->apps->_p('img'));
		$otherid = intval($this->apps->_p('otherid'));
		$brandid = intval($this->apps->_p('brandid'));
		$brandsubid = intval($this->apps->_p('brandsubid'));
		$areaid = intval($this->apps->_p('areaid'));
		$city = strip_tags($this->apps->_p('city'));
		$created_date = date("Y-m-d H:i:s");
		$closed_date = date("Y-m-d H:i:s");
		$n_status = 1;
		
		if($email==''||$email==null){
			$this->logger->log('form registration not complete.');
			return   "form registration not complete. email not found";
		}
			
		$hashPass = sha1($password.$CONFIG['salt']);
		$sql = "SELECT * FROM {$this->config['DATABASE'][0]['DATABASE']}.social_member WHERE email='{$email}' LIMIT 1 ";
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if($email==''||$email==null){
				$this->logger->log('form registration not complete.');
				return  "form registration not complete. email not found";
			}
			if($password!=''||$password!=null){
				if($password!=$repassword) {
					$this->logger->log('pass and re pass not match');
					return 'Please make sure confirm password is same as the password.';
				}else{
					$updatepass=",password='{$hashPass}'";
				}
			}

			$sql = "
				UPDATE {$this->config['DATABASE'][0]['DATABASE']}.social_member SET name='{$name}',gender='{$gender}',nickname='{$nickname}',last_name='{$last_name}' , n_status = 1,try_to_login=0{$updatepass}
					WHERE id = {$qData['id']} LIMIT 1				
				
			";
			//pr($sql);exit;
			$this->apps->query($sql);
			$sql = "SELECT ownerid FROM {$this->config['DATABASE'][0]['DATABASE']}.my_profile WHERE ownerid={$qData['id']} LIMIT 1 ";
			$rqData = $this->apps->fetch($sql);
			
			if($rqData){
				$dataupdate = false;
				if($rolesname!='') $dataupdate[] = "name='{$rolesname}'";
				if($type!='') $dataupdate[] = "type='{$type}'";
				if($img!='') $dataupdate[] = "img='{$img}'";
				if($otherid!='') $dataupdate[] = "otherid='{$otherid}'";
				$dataupdate[] = "brandid='{$brandid}'";
				$dataupdate[] = "brandsubid='{$brandsubid}'";
				if($areaid!='') $dataupdate[] = "areaid='{$areaid}'";
				if($city!='') $dataupdate[] = "city='{$city}'";
				if($masterrole!='') $dataupdate[] = "masterrole='{$masterrole}'";
				$qUpdateData = "";
				if($dataupdate){
					$qUpdateData = implode(',',$dataupdate);
				}else return $saved;
				



				$sql = "
						UPDATE {$this->config['DATABASE'][0]['DATABASE']}.my_profile SET 
						{$qUpdateData} 
						WHERE ownerid = {$qData['id']} LIMIT 1				
				"; 
				
				$this->apps->query($sql);
				 
				return "Update Success. ";
			}else{
				$sql = "
					INSERT INTO {$this->config['DATABASE'][0]['DATABASE']}.my_profile (ownerid ,	name, 	description ,	type 	,img ,	otherid,	brandid 	,brandsubid ,	areaid ,	city 	,created_date ,	closed_date,n_status,masterrole) 
					VALUES ('{$qData['id']}','{$rolesname}','',{$type},'{$img}',{$otherid},{$brandid},{$brandsubid},{$areaid},'{$city}',NOW(),DATE_ADD(NOW(),INTERVAL 5 YEAR),1,'{$masterrole}')
				";
				// pr($sql);
				 $this->apps->query($sql);
					if($this->apps->getLastInsertId()>0)  { 
							 
						return 'Update Successs.';
					}
			
			}
		}else{
			if($email==''||$email==null||$password==''){
				$this->logger->log('form registration not complete.');
				return   "form registration not complete. email not found";
			}
			if($password!=$repassword) {
				$this->logger->log('pass and re pass not match');
				return 'Please make sure confirm password is same as the password.';
			}

			$sql = "
				INSERT INTO {$this->config['DATABASE'][0]['DATABASE']}.social_member (password,email,register_date,salt,n_status,name,nickname,username,last_name,gender) 
				VALUES ('{$hashPass}','{$email}',NOW(),'{$CONFIG['salt']}',1,'{$name}','{$nickname}','{$username}','{$last_name}','{$gender}')			
			";
			$this->apps->query($sql);
			$lastID = $this->apps->getLastInsertId();
			if($lastID>0) {
				$sql = "
					INSERT INTO {$this->config['DATABASE'][0]['DATABASE']}.my_profile (ownerid ,	name, 	description ,	type 	,img ,	otherid,	brandid 	,brandsubid ,	areaid ,	city 	,created_date ,	closed_date,n_status,masterrole) 
					VALUES ('{$lastID}','{$rolesname}','',{$type},'{$img}',{$otherid},{$brandid},{$brandsubid},{$areaid},'{$city}',NOW(),DATE_ADD(NOW(),INTERVAL 5 YEAR),1,'{$masterrole}')
				";
				// pr($sql);exit;
				 $this->apps->query($sql);
					if($this->apps->getLastInsertId()>0)  { 
						return  'Registration Complete.';
					}
			}		
		}
 		return  "Failed";
	
	}
	

	function getLeader($type=2){
		$qmasterquery = " ";
		if($type==2)$qmasterquery = " AND   sm.n_status  IN (1,9)";
		if($type==3)$qmasterquery = " AND masterrole = 1 AND sm.n_status  IN (1,9)";
		if($type==5)$qmasterquery = " AND masterrole = 1 ";
		$sql  = "
			SELECT sm.id,pages.name pagename,sm.name, sm.last_name 
			FROM {$this->config['DATABASE'][0]['DATABASE']}.social_member sm
			LEFT JOIN {$this->config['DATABASE'][0]['DATABASE']}.my_profile pages ON pages.ownerid = sm.id
			WHERE type={$type} {$qmasterquery}   ";
		
		$qData = $this->apps->fetch($sql,1);
		
		return $qData;
	}
	
	
	function userlists($orderBy='name',$orderType='ASC',$start=0,$limit=20,$search=null){
		$searchFilter="";
		if($search){
			$searchFilter="AND (sm.name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		}

		//create user list per hirarki
		$uid = intval($this->apps->_request('uid'));
		if($uid!=0) $qUsers = " AND sm.id = {$uid} ";
		else  $qUsers = "";
		mysql_query('SET CHARACTER SET utf8');
		$sql  = "
			SELECT 
			sm.id,sm.name, sm.last_name ,sm.email,sm.username,sm.password,sm.birthday,sm.phone_number,sm.nickname,sm.gender,
			ptype.name pagename,ptype.id pagetype,pages.brandid,pages.brandsubid,pages.areaid,pages.otherid,pages.city,CONCAT(UCASE(LEFT(cref.city, 1)),SUBSTRING(cref.city, 2)) cityname
			FROM {$this->config['DATABASE'][0]['DATABASE']}.social_member sm
			LEFT JOIN {$this->config['DATABASE'][0]['DATABASE']}.my_profile pages ON pages.ownerid = sm.id
			LEFT JOIN {$this->config['DATABASE'][0]['DATABASE']}.my_profile_type ptype ON ptype.id = pages.type
			LEFT JOIN beat_city_reference cref ON cref.id = pages.city
			WHERE sm.n_status IN (1,9) {$searchFilter}
			{$qUsers}
			ORDER BY {$orderBy} {$orderType}
			LIMIT {$start},{$limit} ";
		// pr($sql);exit;
		if($uid!=0) {
			$qData = $this->apps->fetch($sql);
			return $qData;
		}else{
		 	$qData = $this->apps->fetch($sql,1);

		 	$sql  = "
					SELECT 
					COUNT(sm.id) AS total
					FROM {$this->config['DATABASE'][0]['DATABASE']}.social_member sm
					LEFT JOIN {$this->config['DATABASE'][0]['DATABASE']}.my_profile pages ON pages.ownerid = sm.id
					LEFT JOIN {$this->config['DATABASE'][0]['DATABASE']}.my_profile_type ptype ON ptype.id = pages.type
					LEFT JOIN beat_city_reference cref ON cref.id = pages.city
					WHERE sm.n_status IN (1,9) {$searchFilter}";
				//pr($qData);	
		 	$total_row = $this->apps->fetch($sql);
		 	//pr($qData);exit;
		 	return array('data'=>$qData,'total'=>$total_row['total']);
		}
		
	}
	
	function unusers(){
		//create user list per hirarki
		$uid = intval($this->apps->_request('uid'));
		$sql = "
				UPDATE {$this->config['DATABASE'][0]['DATABASE']}.social_member SET n_status = 3
				WHERE id = {$uid} LIMIT 1				
			";
			
		$this->apps->query($sql);
		
		return true;
	}
	 
	
	function getMasterData($type=0){
	
	if($type==0)return false;
	
	$sql =" 
		SELECT sm.id,sm.name FROM {$this->config['DATABASE'][0]['DATABASE']}.social_member sm
		LEFT JOIN {$this->config['DATABASE'][0]['DATABASE']}.my_profile p ON p.ownerid = sm.id
		WHERE p.masterrole = 1 AND p.type= {$type} 
	";
	$qData = $this->apps->fetch($sql,1);
	if($qData )return $qData;
	return false;
		
	} 
	
	function getMasterCity(){
	   
		$sql="SELECT *
				FROM city_reference
				WHERE n_status = 1 ";
		$rs = $this->apps->fetch($sql,1);

		if($rs) return $rs;
		return false;
	}
	
	function getMasterRole(){
	 
		$sql="SELECT *
				FROM {$this->config['DATABASE'][0]['DATABASE']}.my_profile_type
				WHERE id <=100 ";
		$rs = $this->apps->fetch($sql,1);

		if($rs) return $rs;
		return false;
	}

}
	