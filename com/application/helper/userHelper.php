<?php 
error_reporting(0);
class userHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) {
				$uid = intval($this->apps->_request('uid'));
				if($uid==0) $this->uid = intval($this->apps->user->id);
				else $this->uid = $uid;
		}

		$this->dbshema = "beat";	
		$this->topclass = array(100,4,6);
	}
  
	
	function getUserProfile(){
		global $CONFIG;
	
		$uid = intval($this->apps->_request('uid'));
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
			$sql = "
			SELECT 
			sm.id,sm.name,sm.last_name,sm.img,
			sm.sex,sm.username,sm.nickname, sm.email, 
			cityref.city as cityname,sm.small_img ,  
			pagestype.name role, pages.type roletype, pages.brand, sm.deviceid
			FROM social_member sm
			LEFT JOIN city_reference cityref ON sm.city = cityref.id
			LEFT JOIN my_profile pages ON sm.id = pages.ownerid
			LEFT JOIN my_profile_type pagestype ON pages.type = pagestype.id
			WHERE sm.id = {$uid} LIMIT 1";
			// pr($sql);
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);
			if(!$qData)return false;
	   
			$qData['customize_flow']['menu'] = $this->getCuztomizeEvents($qData['brand']);
		 	$qData['customize_templates'] = $this->getCustomizeCalls($qData['brand']); 
			
			if($qData['email']=='')$qData['email'] = $qData['username'];
			return $qData;
		}
		return false;
	}
	

	function getCuztomizeEvents($brand=false){
		if($brand==false) return array();
		//get header menu names
		$sql = "  SELECT COUNT(1) total FROM social_event_choicer WHERE userid =  {$this->uid} AND n_status = 1 GROUP BY id ";
		$qData = $this->apps->fetch($sql);
		$sql = "  SELECT * FROM customize_event WHERE parentid = 0 AND brand={$brand} AND n_status = 1 GROUP BY id ";
		// pr($sql);
		if($qData){
			if($qData['total']>0){
				$sql = "  
					SELECT e.* FROM customize_event e
					LEFT JOIN social_event_choicer c ON c.eventid=e.id
					WHERE e.parentid = 0 AND e.brand={$brand} AND e.n_status = 1 AND c.n_status = 1  GROUP BY e.id ";
			}
		}
		// $sql = "  SELECT * FROM customize_event WHERE parentid = 0 AND brand={$brand} AND n_status = 1 GROUP BY id ";
		
		$qData = $this->apps->fetch($sql,1);
		$newdata = array();
		if($qData){
			$n=0;
			foreach($qData as $key => $val){
				$newdata[$n]['menuid'] = strtoupper($val['id']) ;
				$newdata[$n]['name'] = strtoupper($val['name']) ;
				$childevent = $this->getChildEvent($val['id'],$val['brand']);
				
				if($childevent){
					foreach($childevent as $cval){
						$newdata[$n][$cval['name']]['status'] = false;
						if($cval['n_status'])$newdata[$n][$cval['name']]['status'] =true;
						if($cval['schemaid'])$newdata[$n][$cval['name']]['schemaid'] =$cval['schemaid'];
						if($cval['url'])$newdata[$n][$cval['name']]['url'] =$cval['url'];
						if($cval['files'])$newdata[$n][$cval['name']]['filename'] =$cval['files'];
						
						if($cval['name']=='games') {
							$getgamesid = $this->getGamesID($cval['schemaid']);
							$newdata[$n][$cval['name']]['gamesid'] = $getgamesid['appsid'];
						}
                        if($cval['name']=='thankyou') {							 
							$newdata[$n][$cval['name']]['header_message'] = 'THANK YOU';
							$newdata[$n][$cval['name']]['message'] = 'buat yang udah berani ambil pilihan 
                                                                      jangan lupa kunjungi www.goaheadpeople.com';
						}
						 
					} 
					
					if(array_key_exists('registration',$newdata[$n])){
						if($newdata[$n]['registration']['status']){
						$registrationfields = $this->getRegistrationFields($val['id']);
							if($registrationfields){
								$idxreg = 0;
								foreach($registrationfields as $rval){
									$newdata[$n]['registration']['dynamic_fields'][$idxreg]['deviceVar']=$rval['appsVariables']; 
									$newdata[$n]['registration']['dynamic_fields'][$idxreg]['mandatory']=$rval['mandatory']; 
									$idxreg++;
								}
							}else{
								$newdata[$n]['registration']['dynamic_fields'] = array();
							}
						}else{
								$newdata[$n]['registration']['dynamic_fields'] = array();
						}
					}else{
							$newdata[$n]['registration']['dynamic_fields'] = array();
					}
				}
				$n++;
			}
			
			
		
		}
		return $newdata;
		
	}
	
	function getChildEvent($parentid=false,$brand=false){
		if($brand==false) return array();
		if($parentid==false) return array();
		$sql = "  SELECT * FROM customize_event WHERE parentid = {$parentid} AND parentid<>0 AND brand={$brand} GROUP BY id";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
		
	}
	
	function getRegistrationFields($eventid=0){
		 
		if($eventid==false) return array();
		$sql = "  SELECT appsVariables,mandatory FROM `tbl_mandatory_field` WHERE eventid = {$eventid} AND n_status = 1 ORDER BY `index` ASC ";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
	}
	
	function getGamesID($schemaid=false){
	
		if($schemaid==false) return array();
	 
		$sql = "  SELECT * FROM tbl_apps_references WHERE schemaid = '{$schemaid}'  AND n_status= 1 LIMIT 1";
		$qData = $this->apps->fetch($sql);
		if($qData) return $qData;
		else return array();
		
	}
	
	function getCustomizeCalls($brand=4){
		global $CONFIG;
		$data =array();
		$sql =" SELECT * FROM customize_templates WHERE brand={$brand} AND n_status = 1 ";
		$qData = $this->apps->fetch($sql,1);
		if($qData) {
			 // pr($qData);
				
			foreach($qData as $key => $val){
			
				
				
				if($val['color']) $data[$val['sections']] = $val['color'];
				if($val['size']) $data[$val['sections']] = $val['size'];
				if($val['style']) $data[$val['sections']] = $val['style'];
				if($val['textfill']) $data[$val['sections']] = $val['textfill'];
				
				if($val['images']){ 
					if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}content/{$val['sections']}/{$val['images']}")) $val['images'] = false;
					
					if($val['images']) $qData[$key]['images_full_path'] = $CONFIG['PUBLIC_ASSETS_DOMAIN_PATH']."content/{$val['sections']}/". $val['images'];
					else $qData[$key]['images_full_path']= $CONFIG['PUBLIC_ASSETS_DOMAIN_PATH']."content/{$val['sections']}/default.jpg";					
					
					$data[$val['sections']] =$qData[$key]['images_full_path'];
				}
			}
			
			return $data;
		
		}else return false;
	}
	
	function checkuserpassword(){
		//default password
		$oldpass = '9e1137bcef141f7fd0661c971f52ec281e856fd5'; //beatbeat
 
		$sql = "SELECT * FROM social_member WHERE id={$this->uid} AND login_count=0 LIMIT 1";
		
		$rs = $this->apps->fetch($sql);
		 
		if($rs) return false;
		return true;

	}
	
	function changepassword(){
		global  $CONFIG;
		
		$data['result'] = false;
		$data['message'] = "wrong format password and your confirmed password not correct";
		$data['code'] = 0;
		
		$oldpass = strip_tags($this->apps->_p('oldpass'));
		$newpass = strip_tags($this->apps->_p('newpass'));
		$confirmnewpass = strip_tags($this->apps->_p('confirmnewpass'));
		// $this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);

		if($newpass!=$confirmnewpass){
			return $data;
		}
		// var_dump(preg_match("/^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/",$newpass));exit;
			// $this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);
		if(preg_match("/^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/",$newpass)) {	
			$sql = "SELECT * FROM social_member WHERE id={$this->uid} LIMIT 1";
			// $this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass);
			
			$rs = $this->apps->fetch($sql);


			if(!$rs) {
				$data['message'] = "sorry, we could not found your datas ";
				return $data;
			}
			
			$oldhashpass = sha1($oldpass.$rs['salt']);
			// var_dump($rs['password']);
			// var_dump($oldhashpass);exit;
			if($oldhashpass!=$rs['password']) {
				$data['message'] = " sorry your old password not correct ";
				return $data;
			}
				
			$hashpass = sha1($newpass.$rs['salt']);
					
			$sql ="UPDATE social_member SET password='{$hashpass}' WHERE id={$this->uid} LIMIT 1";
			$rs = $this->apps->query($sql);
			// pr($sql);exit;
			if($rs){
				$sql ="UPDATE social_member SET last_login=now(),login_count=login_count+1 WHERE id={$this->uid} LIMIT 1";
				$rs = $this->apps->query($sql);
				// pr($sql);exit;
				$data['result'] = true;
				$data['message'] = "success update password";
				$data['code'] = 1;
				return $data;
			}
		}
		$this->logger->log($oldpass.'-'.$newpass.'-'.$confirmnewpass.'-'.'not have secury password');
		$data['message'] = "wrong format password ";
		return $data;
		
	}
	
	function getsba(){
		
		$sql =" 
		SELECT sm.id,CONCAT(sm.name,' ',sm.last_name) names 
		FROM social_member sm
		LEFT JOIN my_pages mp On mp.ownerid = sm.id
		WHERE mp.type = 1
		
		";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return false;
	}
	
	function getgamestrack(){
		
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		$gamesid = intval($this->apps->_g('gamesid'));
			
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  mp.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
		
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
			$qDate = " AND DATE(g.datetimes) >= DATE('{$startdate}') AND DATE(g.datetimes) <= DATE('{$enddate}') ";
		}
		$limit = 10;
		$sql =" 
		SELECT COUNT(1) totalplay,
		SUM(win)totalwin,
		COUNT(1)-SUM(win) totallose,userid ,
		CONCAT(sm.name , ' ',sm.last_name) names,
		MAX(datetimes) datetimes
		FROM `my_games` g
		LEFT JOIN social_member sm On sm.id = g.userid
		LEFT JOIN my_pages  mp On mp.ownerid = g.userid 
		WHERE 1 {$qUser}  {$qcity} {$qDate} {$qGamesid}
		GROUP BY userid 
		LIMIT {$start},{$limit}
		";
		
		$qData = $this->apps->fetch($sql,1);
		if($start==0)$start=1;
		$no = 0+$start;
		if($qData){
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			}
			return $qData;
		
		}else return false;
	
	}
	
	
	function getgamestrackall(){
		
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		$gamesid = intval($this->apps->_g('gamesid'));
		
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  mp.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
			$qDate = " AND DATE(g.datetimes) >= DATE('{$startdate}') AND DATE(g.datetimes) <= DATE('{$enddate}') ";
		}
		 
		$sql =" 
		SELECT COUNT(1) totalplay,
		SUM(win)totalwin,
		COUNT(1)-SUM(win) totallose,
			( 
			SELECT COUNT(1) total 
			FROM ( 
				SELECT 1 
				FROM `my_games` g
				LEFT JOIN social_member sm On sm.id = g.userid
				LEFT JOIN my_pages  mp On mp.ownerid = g.userid 
			WHERE 1 {$qUser}  {$qcity} {$qDate} {$qGamesid}  GROUP BY registrantmail ) registrant ) totalunique
		FROM `my_games` g
		LEFT JOIN social_member sm On sm.id = g.userid
		LEFT JOIN my_pages  mp On mp.ownerid = g.userid 
		WHERE 1 {$qUser}  {$qcity} {$qDate}  {$qGamesid}
		";
		
		$qData = $this->apps->fetch($sql);
		 
		if($qData){
		 
			return $qData;
		
		}else return false;
	
	}
	function getgamestrackentourage(){
		
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		$gamesid = intval($this->apps->_g('gamesid'));
		
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  sm.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
			$qDate = " AND DATE(g.datetimes) >= DATE('{$startdate}') AND DATE(g.datetimes) <= DATE('{$enddate}') ";
		}
		$limit = 10;
		$sql =" 
		SELECT COUNT(1) totalplay,
		SUM(win)totalwin,
		COUNT(1)-SUM(win) totallose,registrantmail ,
		CONCAT(sm.name , ' ',sm.last_name) names,
		MAX(datetimes) datetimes
		FROM `my_games` g
		LEFT JOIN my_entourage sm On sm.email = g.registrantmail 
		WHERE 1 {$qUser}  {$qcity} {$qDate} {$qGamesid}
		AND registrantmail <> '0'
		GROUP BY registrantmail 
		LIMIT {$start},{$limit}
		";
		// pr($sql);
		
		$qData = $this->apps->fetch($sql,1);
		if($start==0)$start=1;
		$no = 0+$start;
		if($qData){
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			}
			return $qData;
		
		}else return false;
	
	}
	
	function getEntourageReport(){
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		$gamesid = intval($this->apps->_g('gamesid'));
		
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  sm.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
			$qDate = " AND DATE(g.datetimes) >= DATE('{$startdate}') AND DATE(g.datetimes) <= DATE('{$enddate}') ";
		}
		$limit = 10;
	
		$sql = "
			SELECT 
			CONCAT(e.name,' ',e.last_name) names,
			e.n_status,
			e.register_date registerdate,
			c.city cityname,
			r.subbrandname
			FROM `my_entourage` e 
			LEFT JOIN  `beat_city_reference`  c ON c.id = e.city
			LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
			ORDER BY e.id DESC LIMIT {$start},{$limit}";
		$qData = $this->apps->fetch($sql,1);
		if($start==0)$start=1;
		$no = 0+$start;
		if($qData){
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			}
			return $qData;
		
		}else return false;
	}
 
	function getEntourageAge( ){
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		$gamesid = intval($this->apps->_g('gamesid'));
		
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  sm.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
			$qDate = " AND DATE(g.datetimes) >= DATE('{$startdate}') AND DATE(g.datetimes) <= DATE('{$enddate}') ";
		}
		$limit = 10;
	
		$sql = "
			SELECT 
			CONCAT(e.name,' ',e.last_name) names,
			e.n_status,
			e.register_date registerdate,
			c.city cityname,
			r.subbrandname
			FROM `my_entourage` e 
			LEFT JOIN  `beat_city_reference`  c ON c.id = e.city
			LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
			ORDER BY e.id DESC LIMIT {$start},{$limit}";
		$qData = $this->apps->fetch($sql,1);
		if($start==0)$start=1;
		$no = 0+$start;
		if($qData){
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			}
			return $qData;
		
		}else return false;
	}
	
}

?>

