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
	
	function getsba(){ 
		
		$qBrand = '';
		
		if($this->apps->user->type<=100) $qBrand = " WHERE  mp.brand = {$this->apps->user->brand} " ;
		if($this->apps->user->type==1) $qBrand = " WHERE  mp.ownerid = {$this->apps->user->id} " ;
	 
		
		$sql ="  
		SELECT sm.id, sm.name 
		FROM {$this->dbshema}.`social_member`  sm
		LEFT JOIN {$this->dbshema}.`my_profile`  mp ON sm.id = mp.ownerid
		{$qBrand}
		ORDER BY sm.name ASC
		"; 
		
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return false;
	}
	
	function loadCity(){
	 	global $CONFIG;
	
		$sql = "SELECT m.city as cityid, cr.city as area FROM {$this->dbshema}.my_profile m 
				LEFT JOIN {$this->dbshema}.city_reference cr ON m.city = cr.id GROUP BY m.city ";
		$rs = $this->apps->fetch($sql,1);
	 
		if($rs) return $rs;
		return false;
	}
	
	function getBrand(){
		global $CONFIG;
		
			$qBrand = '';
		
		if($this->apps->user->type!=100)$qBrand = " AND m.id = {$this->apps->user->brand} " ;
		
		$sql = "
		SELECT m.id,e.name brandname 
			FROM {$this->dbshema}.tbl_brand_master m
			LEFT JOIN {$this->dbshema}.customize_event e ON e.brand = m.id
		WHERE 1
		AND  e.brand = m.id AND e.parentid = 0
		{$qBrand} ";		
		
		$rs = $this->apps->fetch($sql,1);
	 
		if($rs) return $rs;
		return false;
	}
	/*
	function headerReport(){
		
		$status = array('pending','new','reject','existing');
		
		$data['new']['total']  = 0;
		$data['existing']['total']= 0;
		$data['reject']['total']= 0;
		$data['pending']['total'] = 0;
		$qStatus = "";
		$n_status = strip_tags($this->apps->_g('n_status'));
		if($n_status=='') $qStatus = "";
		if($n_status=='0') $qStatus = " AND n_status = 0 ";
		if($n_status=='1')$qStatus = " AND n_status IN (1,3) ";
		if($n_status=='2')$qStatus = " AND n_status  = 2 ";
		
		$sql = "
			SELECT COUNT(1) total,n_status 
			FROM {$this->dbshema}.`my_registrant` 
			WHERE 1 {$qStatus}
			GROUP BY n_status
		";
		$qData = $this->apps->fetch($sql,1);
		if($qData){
			foreach($qData as $val){
				if(array_key_exists($val['n_status'],$status)) $data[$status[$val['n_status']]]['total']  = $val['total']; 
			}
		}
				
		return $data;
	}
	*/
	
	function headerReport(){
		global $CONFIG;
		// pr('masuk');
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid'));  
		$n_status = $this->apps->_g('n_status');
		if($n_status =='') $n_status = -1; 
		$brandid = intval($this->apps->_g('brandid'));		
		if($this->apps->user->type<=100)$brandid = intval($this->apps->user->brand);
		else $brandid = "";
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  rd.ownerid ={$userid} ";
		if($this->apps->user->type==1) $qUser = " 	AND  rd.ownerid ={$this->apps->user->id} " ;
	 
		if($city) $qcity = " AND  rd.city='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  mp.brand={$brandid} "; 
		if($n_status>-1) $qn_status = " 	AND  rd.n_status={$n_status} "; 
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('enddate'));
		$enddate = strip_tags($this->apps->_g('startdate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		// $qLimit = " LIMIT {$start},{$limit}";
		// if($limitstatus==false) $qLimit = " ";
		// if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
		 
			$qDate = " AND DATE( rd.register_date) >= DATE('{$startdate}') AND DATE( rd.register_date) <= DATE('{$enddate}') ";
		}
		
		$sql = "SELECT count(1) total 	 
				FROM {$this->dbshemaWeb}.registrant_data rd  
				LEFT JOIN {$this->dbshemaWeb}.my_profile mp ON mp.ownerid = rd.ownerid 
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
				ORDER BY rd.register_date DESC
		"; 
		// pr($sql);
		$total = $this->apps->fetch($sql);		
		if(intval($total)) $start = 0;
		return $total;
		// if(intval($total['total'])<=$limit) $start = 0;
		
	}
					
	
	function getEntourageReport($start=null,$limit=20,$limitstatus=true){
	
		// pr('masuk');
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));		
		if($this->apps->user->type<=100)$brandid = intval($this->apps->user->brand);
		else $brandid = "";
		$n_status = $this->apps->_g('n_status');
		if($n_status =='') $n_status = -1;
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  rd.ownerid ={$userid} ";
		if($this->apps->user->type==1) $qUser = " 	AND  rd.ownerid ={$this->apps->user->id} " ;
		
		if($city) $qcity = " AND  rd.city='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  mp.brand={$brandid} "; 
		if($n_status>-1) $qn_status = " 	AND  rd.n_status={$n_status} "; 
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		$qLimit = " LIMIT {$start},{$limit}";
		if($limitstatus==false) $qLimit = " ";
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
		 
			$qDate = " AND DATE(register_date) >= DATE('{$startdate}') AND DATE(register_date) <= DATE('{$enddate}') ";
		}
		
		//GET TOTAL
		$sql = "SELECT count(1) total 	
				FROM {$this->dbshema}.my_registrant				 
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}  
				 ";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
			// pr($total);
		$sql = "
			SELECT 
			registrantname names,
			n_status,
			DATE_FORMAT(register_date,'%d/%m/%Y %H:%i:%S') registerdate,
			registrantcity cityname,
			brandname subbrandname
			FROM {$this->dbshema}.my_registrant
			WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
			ORDER BY register_date DESC {$qLimit}";
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		$this->logger->log($sql);
		if($start==0)$start=1;
		$no = 0+$start;
		if($qData){
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
				if($val['n_status']==0)$qData[$key]['n_status'] = 'pending';
				if($val['n_status']==1)$qData[$key]['n_status'] =  'approved';
				if($val['n_status']==2)$qData[$key]['n_status'] = 'rejected';
				if($val['n_status']==3)$qData[$key]['n_status'] = 'existing';
			}
			// return $qData;
			$result['result'] = $qData;
			$result['total'] = intval($total['total']);
			return $result;
		}
		return false;
		
		
	}
	
	
	function getEntourageStat($start=null,$limit=20,$limitstatus=true){
	
		// pr('masuk');
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));
		$n_status = $this->apps->_g('n_status');
		if($n_status =='') $n_status = -1;
		$result['result'] = false; 
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		$branddata['Our'] = 0;
		$branddata['Competitor'] = 0;
		$branddata['PMI'] = 0;
		
		$genderdata["M"] = 0;
		$genderdata["F"]=  0;
		
		$agedata['null']= 0;
		$agedata['young']= 0;
		$agedata['mature']= 0;
		$agedata['olde']= 0;
		
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  dstid={$userid} ";
		if($city) $qcity = " AND  registrantcity='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  dstbrandid={$brandid} ";
		else $qBrandid = " 	AND  dstbrandid IN (4,5) ";
		if($n_status>-1) $qn_status = " 	AND  n_status={$n_status} ";
		else $qn_status = " 	AND n_status IN (0,1,2,3) ";	
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		$qLimit = " LIMIT {$start},{$limit}";
		if($limitstatus==false) $qLimit = " ";
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
		 
			$qDate = " AND DATE(register_date) >= DATE('{$startdate}') AND DATE(register_date) <= DATE('{$enddate}') ";
		}
		 
		$sql = " SELECT subbrandname  ,brandtype FROM {$this->dbshema}.tbl_brand_preferences_references ";
		$qData = $this->apps->fetch($sql,1);
			// pr($qData);
		if(!$qData)return false;
		$competitorarr = array();
		$pmiarr = array();
		$ourarr = array();
		foreach($qData as $val){
			if($val['brandtype']==0) $competitorarr[(string)$val['subbrandname']] =(string)$val['subbrandname'];
			if($val['brandtype']==1) $pmiarr[(string)$val['subbrandname']] =(string)$val['subbrandname'];
			if($val['brandtype']==2) $ourarr[(string)$val['subbrandname']] =(string)$val['subbrandname'];
		} 
		 
		 
		$sql = "
			SELECT COUNT( 1 ) total, gender
			FROM {$this->dbshema}.summary_report_registrant
			WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
			GROUP BY gender  ";
		$qGenderData = $this->apps->fetch($sql,1);
				
		if($qGenderData){
			foreach($qGenderData as  $val){
				$genderdata[$val['gender']] =$val['total'];
			}
		}
		
		$sql = "
			SELECT COUNT( 1 ) total, age
			FROM {$this->dbshema}.summary_report_registrant
			WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
			GROUP BY age  ";
		$qAgeData = $this->apps->fetch($sql,1);
		
		if($qAgeData){
			foreach($qAgeData as  $val){
				if($val['age']==null ) $agedata['null']+=$val['total'];
				else{
				if($val['age']<=24 ) $agedata['young']+=$val['total'];
				if($val['age']>=25 && $val['age']<=29 ) $agedata['mature']+=$val['total'];
				if($val['age']>=30 ) $agedata['olde']+=$val['total'];
				}
			}
		}
		
	 	$sql = "
			SELECT COUNT( 1 ) total, brandname subbrandname
			FROM {$this->dbshema}.summary_report_registrant
			WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
			GROUP BY subbrandname  ";
		$qBrandData = $this->apps->fetch($sql,1);
		
		if($qBrandData){
		
			foreach($qBrandData as $key => $val){
				$qBrandData[$key]['brandname'] = "Our";
				if(in_array($val['subbrandname'],$competitorarr)) $qBrandData[$key]['brandname'] = "Competitor";				
				if(in_array($val['subbrandname'],$pmiarr)) $qBrandData[$key]['brandname'] = "PMI";
				if(in_array($val['subbrandname'],$ourarr)) $qBrandData[$key]['brandname'] = "Our";
					
			} 
		 
			foreach($qBrandData as $key => $val){
				
				if($qBrandData[$key]['brandname']=='Our') $branddata[$qBrandData[$key]['brandname']]+=$val['total'];
				if($qBrandData[$key]['brandname']=='Competitor')$branddata[$qBrandData[$key]['brandname']]+=$val['total'];
				if($qBrandData[$key]['brandname']=='PMI')$branddata[$qBrandData[$key]['brandname']]+=$val['total'];
				 
				
			}
		}	
			
		
	
		$result['result']['brand'] = $branddata;
		$result['result']['gender'] = $genderdata;
		$result['result']['age'] = $agedata;
	 	// pr($qData);
		return $result;
		
		
	}
	
	
	function locationRegistrnt($limit=10,$limitstatus=false){
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));
		$n_status = $this->apps->_g('n_status');
		if($n_status =='') $n_status = -1;
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  reg.dstid={$userid} ";
		if($city) $qcity = " AND  reg.registrantcity='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  reg.dstbrandid={$brandid} ";
		else $qBrandid = " 	AND  reg.dstbrandid IN (4,5) ";
		if($n_status>-1) $qn_status = " 	AND  reg.n_status={$n_status} ";
		else $qn_status = " 	AND reg.n_status IN (0,1,2,3) ";	
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		$qLimit = " LIMIT {$start},{$limit}";
		if($limitstatus==false) $qLimit = " ";
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
		 
			$qDate = " AND DATE(reg.register_date) >= DATE('{$startdate}') AND DATE(reg.register_date) <= DATE('{$enddate}') ";
		}
		
		
		$limit = 10;
		
		if($city){
			/* count entourage */
			$sql = "
			SELECT 
				COUNT(1) total,
				user.name cityname ,
				reg.n_status   
			FROM {$this->dbshema}.summary_report_registrant reg
			LEFT JOIN {$this->dbshema}.summary_dst_user user ON user.id = reg.dstid
			WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
			GROUP BY reg.dstid,reg.n_status DESC  ORDER BY total ASC "; 
			
			 
			$qData = $this->apps->fetch($sql,1);
		}else{		
		
			$sql = "
			SELECT 
				COUNT(1) total,
				reg.registrantcity cityname ,
				n_status   
			FROM {$this->dbshema}.summary_report_registrant reg
			WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
			GROUP BY cityname,reg.n_status DESC  ORDER BY cityname ASC "; 
		 
			$qData = $this->apps->fetch($sql,1);
		}
		// pr($sql);
		if($start==0)$start=1;
		$no = 0+$start;
		$data['cityid'] = array();
		$data['total'] = array();
		if($qData){
			foreach($qData as $key => $val){
					$data['cityid'][$val['n_status']][$key] =$val['cityname'];
					$data['total'][$val['n_status']][$key] =intval($val['total']);
			}
		// pr($data);
			return $data;
		}else return $data;
		
	
	}

	function dataphotoentourage($start=null,$limit=20,$limitstatus=true){
		global $CONFIG;
		// pr('masuk');
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));	
		
		if($this->apps->user->type<=100)$brandid = intval($this->apps->user->brand);
		else $brandid  = "";
		$n_status = $this->apps->_g('n_status');
		if($n_status =='') $n_status = -1;
		$result['result'] = false;
		$result['total'] = 0;
		$postBrand = intval($this->apps->_p('brandid'));
		if($postBrand)
		{
			$brandid = $postBrand;	
		}
		//echo $this->apps->_p('brandid');
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  rd.ownerid={$userid} ";
		if($this->apps->user->type==1) $qUser = " 	AND  rd.ownerid ={$this->apps->user->id} " ;
		
		if($city) $qcity = " AND  rd.city='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  mp.brand={$brandid} "; 
		if($n_status>-1) $qn_status = " 	AND  rd.n_status={$n_status} "; 
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('enddate'));
		$enddate = strip_tags($this->apps->_g('startdate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		$qLimit = " LIMIT {$start},{$limit}";
		if($limitstatus==false) $qLimit = " ";
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
		 
			$qDate = " AND DATE(rd.register_date) >= DATE('{$startdate}') AND DATE(rd.register_date) <= DATE('{$enddate}') ";
		}
		
		//GET TOTAL
		$sql = "
				SELECT COUNT(1) total FROM (
					SELECT count(1) total 	
					FROM {$this->dbshemaWeb}.registrant_data rd
					LEFT JOIN {$this->dbshemaWeb}.my_games g ON g.registrantmail = rd.email 
					LEFT JOIN {$this->dbshemaWeb}.my_profile mp ON mp.ownerid = rd.ownerid 
					WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}   
					GROUP BY rd.email
					ORDER BY g.datetimes 
				) subbed
				 ";
		// pr($sql);
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
			// pr($total);
		$sql = "SELECT g.images, rd.register_date, rd.name, rd.email, rd.phone_number, 
				rd.facebookID, rd.twitterID, rd.instagramID, mp.brand, rd.n_status, rd.ownerid
				FROM {$this->dbshemaWeb}.registrant_data rd
				LEFT JOIN {$this->dbshemaWeb}.my_games g ON g.registrantmail = rd.email 
				LEFT JOIN {$this->dbshemaWeb}.my_profile mp ON mp.ownerid = rd.ownerid 
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}  
				GROUP BY rd.email
				ORDER BY rd.register_date DESC,g.datetimes DESC {$qLimit} ";
		$qData = $this->apps->fetch($sql,1);
		 //pr($sql);exit;
		$this->logger->log($sql);
		if($start==0)$start=1;
		$no = 0+$start;
		if($qData){
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++;
			 
			}
			// return $qData;
			$result['result'] = $qData;
			$result['total'] = intval($total['total']);
			return $result;
		}
		return false;
	}	
	function datatouchbaseHome(){
		//get Total registrant
		$user =$this->apps->user;
		
		
		
		$brandid =$this->apps->_p('brandid');
		if($brandid)
		{
			$brand ="brand='{$brandid}'";
			$id ="id={$brandid}";
		}
		else
		{
			$brand ="brand='{$user->brand}'";
			$id ="id={$user->brand}";
		}
		$sqltotlRegistran = "Select count(*) as total from my_registrant where {$brand} ";
		$rstotlRegistran= $this->apps->fetch($sqltotlRegistran);
		
		$result['totalRegistran']=$rstotlRegistran['total'];
		
		
		$sqltotlKPI = "Select kpi_total as total from tbl_kpi where {$brand} ";
		$rstotlKPI= $this->apps->fetch($sqltotlKPI);
		
		$result['totalKPI']=$rstotlKPI['total'];
		
		
		//get user App
		$sqluserApp = "SELECT COUNT(*) as total,sm.name FROM social_member sm 
						LEFT JOIN my_registrant reg ON reg.userid=sm.id
						WHERE {$brand} GROUP BY reg.userid ";
						
		$rssqluserApp= $this->apps->fetch($sqluserApp,1);
		if($rssqluserApp)
		{
			$result['userApp']['status'] ='1';
			$result['userApp']['data'] =$rssqluserApp;
		}
		else
		{
			$result['userApp']['status'] ='0';
			$result['userApp']['data'] ='';
		
		}
		//get event 
		$sqlevent = "SELECT COUNT(*) as total,name,brandname  FROM customize_event event
					LEFT JOIN my_registrant reg ON reg.eventid=event.id
					LEFT JOIN tbl_brand_master brnd ON brnd.id=reg.brand
					WHERE reg.{$brand} GROUP BY reg.eventid
		";
		//pr($sqlevent);exit;
		$rsevent= $this->apps->fetch($sqlevent,1);
		
		$sql = "SELECT tbm.*, tp.closed_date, tp.subscribe_type
				FROM tbl_brand_master tbm
				LEFT JOIN my_profile tp
				ON tp.brand = tbm.id
				WHERE tbm.{$id} LIMIT 1";
		$rs = $this->apps->fetch($sql);
		$now = strtotime(date('Y-m-d'));
		$valid = strtotime($rs['closed_date']);
		//pr($sql);exit;
		if($now>$valid){
			$rs['closed_date'] = "Already Expired On: ".date('d M Y',$valid);
		}else{
			$rs['closed_date'] = "Valid Until: ".date('d M Y',$valid);
		}
		if($rs['n_status']==1) $rs['n_status'] = "Active";
		else $rs['n_status'] = "Unactive";
		

		$result['detail'] = $rs;

		if($rsevent)
		{
			foreach($rsevent as $value)
			{
			
				$result['event']['categorry'][] = $value['name'];
				$result['event']['data'][] = $value['total'];
			}
			
			$result['event']['status'] ='1';
			$result['event']['brandname'] =$rsevent[0]['brandname'];
			$result['event']['total'] =count($rsevent);
		}
		else
		{
			$result['event']['status'] ='0';
			$result['event']['brandname'] ='';
			$result['event']['total'] ='0';
		
		}
		//pr($this->apps->user);
		return $result;
	}
	function getBranAll(){
		$sql = "SELECT * FROM tbl_brand_master ";
						
		$result= $this->apps->fetch($sql,1);
		
		return $result;
	}
}

?>

