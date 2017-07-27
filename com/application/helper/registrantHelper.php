<?php 

class registrantHelper {

	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) {
				$uid = intval($this->apps->_request('uid'));
				if($uid==0) $this->uid = intval($this->apps->user->id);
				else $this->uid = $uid;
			}
			
			
		}
		
		$this->config = $CONFIG;
		$this->dbshema = "beat";	
		
		
		$sbaid = intval($this->apps->_p('uid'));
		if($sbaid!=0) $this->qSbaFilter = " AND pages.ownerid IN ({$sbaid}) ";
		else {
			if(@$this->apps->user->leaderdetail->type!=666){
			$auhtorminion = @$this->apps->user->badetail;
			 $auhtorarrid = false;
			if($auhtorminion){
				foreach($auhtorminion as $val){
						$auhtorarrid[$val->ownerid] = $val->ownerid;
				}
			}
		 
				if($auhtorarrid) {
					$sbaid = implode(',',$auhtorarrid);
					$this->qSbaFilter = " AND pages.ownerid IN ({$sbaid}) ";
				}else $this->qSbaFilter ="";


			}else {
				$this->qSbaFilter ="";
			}
		}
		
		$brandid = intval($this->apps->_g('brandid'));
		if($brandid!=0) $this->qBrandFilter = " AND ( pages.brandid IN ({$brandid}) OR pages.brandsubid IN ({$brandid}) ) ";
		else {
			
			$this->qBrandFilter = "   ";
		}
		
		$city = intval($this->apps->_g('city'));
		if($city!=0) $this->qAreaFilter = " AND pages.city IN ({$city}) ";
		else {
			
			$this->qAreaFilter = "   ";
		}
		
		
	}
	  
	function addEntourage($img=false,$signature=false,$signatureba=false){
		/**
			0 : please check all your parameter
			1 : success registration
			2 : update data successfull
			3 : wrong on param
			4 : failed to save data
		**/
		// if($this->apps->user->leaderdetail->type!=1) return false;
	 
		$data['result'] = false;
		$data['code'] =0;
		$data['message'][] = ' please check all your parameter ';
 
		$eventname=strip_tags($this->apps->_request("menuname"));
		$eventid=strip_tags($this->apps->_request("menuid"));				
		$fullname=strip_tags($this->apps->_request("name"));
		$lastname=strip_tags($this->apps->_request("lastname"));
		$nickname=strip_tags($this->apps->_request("nickname"));
		$email=strip_tags($this->apps->_request("email"));	
		$city=intval($this->apps->_request("city"));
		$state=strip_tags($this->apps->_request("state"));
		$giidnumber=strip_tags($this->apps->_request("giidnumber"));
		$giidtype=strip_tags($this->apps->_request("giidtype"));
		if($giidtype==1) $giidtype = "K";
		if($giidtype==2) $giidtype = "S";
		$companymobile=strip_tags($this->apps->_request("company_number"));
		$phone_number=strip_tags($this->apps->_request("phone_number"));
		$sex=strip_tags($this->apps->_request("sex"));
		$birthday=strip_tags($this->apps->_request("birthday"));
		$description=strip_tags($this->apps->_request("description"));		 
		$StreetName=strip_tags($this->apps->_request("StreetName"));		 
		$brand1=strip_tags($this->apps->_request("Brand1_ID"));
		
		$currentuserbrand = intval($this->apps->user->leaderdetail->brandid);
		 
		if($brand1=='') {
				$brand1 = "63";				 
		}	
		$brandsub1=strip_tags($this->apps->_request("Brand1SUB_ID"));
		if($brandsub1==''){
			$brandsub1 = "63"; 
		 		
		}		
		$facebookid=strip_tags($this->apps->_request("facebookid"));
		$twitterid=strip_tags($this->apps->_request("twitterid"));
		$instagramid=strip_tags($this->apps->_request("instagramid"));
		
		$ownerid = intval($this->uid);   
		
		$checkmandatory = false;
		
		// check all param
		if(!$twitterid){ 
			$checkmandatory[] = false;
			$data['message'][] = ' twitter not found ';
		}else{
			 
			 $checkmandatory[] = true;
		}
		
		// check event campaign
		if($eventname){
			$sql ="SELECT id FROM customize_event WHERE name='{$eventname}'  ORDER BY id DESC LIMIT 1 ";
			$qData = $this->apps->fetch($sql); 
			$eventid = intval($qData['id']);
			$checkmandatory[] = true;
		}else{			 
			$checkmandatory[] = true;
			$data['message'][] = ' wrong event name campaign code ';
		}
		if(!$eventid){		
			$checkmandatory[] = false;
			$data['message'][] = ' wrong event id campaign code ';
		}else $checkmandatory[] = true;
		if(!$fullname){ 
			$checkmandatory[] = false;
			$data['message'][] = ' full name empty ';
		}else $checkmandatory[] = true;		
		if(!$email){ 
			$checkmandatory[] = false;
			$data['message'][] = ' email empty ';
		}else $checkmandatory[] = true;
		if(!$giidnumber){ 
			$checkmandatory[] = true;
			$data['message'][] = ' giid number empty ';
		}else $checkmandatory[] = true;
		if(!$giidtype){ 
			$checkmandatory[] = true;
			$data['message'][] = ' giid type empty ';
		}else $checkmandatory[] = true;
		if(!$sex){ 
			$checkmandatory[] = true;
			$data['message'][] = ' gender empty ';
		}else $checkmandatory[] = true;
		if(!$ownerid){ 
			$checkmandatory[] = false;
			$data['message'][] = ' users not found  ';
		}else $checkmandatory[] = true;
		if(!$currentuserbrand){ 
			$checkmandatory[] = false;
			$data['message'][] = ' current users brand not found  ';
		}else $checkmandatory[] = true;
		if(!$birthday){ 
			$checkmandatory[] = true;
			$data['message'][] = ' birthday not found ';
		}else{
			 $birthday = date("Y-m-d H:i:s",strtotime($birthday));
			 $checkmandatory[] = true;
		}
		if(!$city){ 
			$checkmandatory[] = true;
			$data['message'][] = ' city not found ';
		}else{
			// check city
			$sql ="SELECT provinceid FROM city_reference WHERE id='{$city}' LIMIT 1";
			$citystat = $this->apps->fetch($sql);
			if($citystat) {
				  $checkmandatory[] = true;
			}else {
				$checkmandatory[] = true;
				$data['message'][] = ' city not found ';
			}
		}

		$updatedatavalidation=false;
		
		if($city!='') $updatedatavalidation[] = "city='{$city}'";
		if($eventid!='') $updatedatavalidation[] = "eventid='{$eventid}'";
		if($phone_number!='') $updatedatavalidation[] = "phone_number='{$phone_number}'";
		if($sex!='') $updatedatavalidation[] = "gender='{$sex}'";
		if($fullname!='') $updatedatavalidation[] = "name='{$fullname}'";
		if($nickname!='') $updatedatavalidation[] = "nickname='{$nickname}'";
		if($lastname!='') $updatedatavalidation[] = "last_name='{$lastname}'";
		if($birthday!='') $updatedatavalidation[] = "birthday='{$birthday}'";
		if($brand1!='') $updatedatavalidation[] = "BrandID='{$brand1}'";
		if($brandsub1!='') $updatedatavalidation[] = "BrandSubID='{$brandsub1}'";
		if($giidnumber!='') $updatedatavalidation[] = "giidnumber='{$giidnumber}'";
		if($giidtype!='') $updatedatavalidation[] = "giidtype='{$giidtype}'";
		if($facebookid!='') $updatedatavalidation[] = "facebookID='{$facebookid}'";
		if($twitterid!='') $updatedatavalidation[] = "twitterID='{$twitterid}'";
		if($instagramid!='') $updatedatavalidation[] = "instagramID='{$instagramid}'";
		if($StreetName!='') $updatedatavalidation[] = "address='{$StreetName}'";
		if($img) $updatedatavalidation[] = "img='{$img}'";  
		
		if($updatedatavalidation){
			$qInsertOnUpdateVerified = implode(',',$updatedatavalidation);
		}
		
		$n_status=1;
		$usertype=0; 
		$verified = 1; 
		$ownership = false;
		
		$sql = " SELECT COUNT(1) total,id,ownerid FROM registrant_data WHERE email='{$email}' GROUP BY email LIMIT 1 ";
		$checkregistrant = $this->apps->fetch($sql); 
		if($checkregistrant){
			if($checkregistrant['total']>0){
				$ownership = true;			
			}
		}
		$registrantstatus = 0;  
		// pr($checkmandatory);
		if(!$ownership){ 
				if(in_array(false,$checkmandatory)) {
					$data['code'] = 3;
					$this->logger->log(" checkmandatory : ".json_encode($checkmandatory));
					return $data;
				} 
		}else{
				$registrantstatus = 3; 
		}
		
		if($ownership) if($checkregistrant['ownerid']==$this->apps->user->id) $registrantstatus = 1; 
		
		if(in_array(false,$checkmandatory)) {
			$registrantstatus = 2;
			$entourageid = $checkregistrant['id'];		
		}else{
		
			$sql = " SELECT MAX(version) version FROM registrant_data LIMIT 1";		
			$latestversion = $this->apps->fetch($sql);
			if($latestversion) $versionlatest = intval($latestversion['version']);
			else $versionlatest = 0;
			
			$sql ="
			INSERT INTO registrant_data 
			(registerid ,name ,	nickname ,	email ,	register_date ,	img ,		city 	,gender ,	birthday ,	description, 	last_name ,	address, 	phone_number ,	n_status ,	BrandID ,	ownerid ,verified, usertype,giidnumber,giidtype,facebookid,twitterid,instagramid,BrandSubID,version, eventid) 
			VALUES
			('',\"{$fullname}\",\"{$nickname}\",\"{$email}\",NOW(),\"{$img}\",\"{$city}\",\"{$sex}\",\"{$birthday}\",\"{$description}\",\"{$lastname}\",\"{$StreetName}\",\"{$phone_number}\",{$registrantstatus},\"{$brand1}\",{$ownerid},{$verified},{$usertype},\"{$giidnumber}\",\"{$giidtype}\",\"{$facebookid}\",\"{$twitterid}\",\"{$instagramid}\",\"{$brandsub1}\",{$versionlatest}, \"{$eventid}\")	
			ON DUPLICATE KEY UPDATE {$qInsertOnUpdateVerified} , version={$versionlatest}+1
			";
			// pr($sql);
			 $this->logger->log($sql);
			$qData = $this->apps->query($sql); 
			$entourageid = false;
			if($this->apps->getLastInsertId())	{
				$registrantstatus = 1; 
				$entourageid = $this->apps->getLastInsertId(); 	 
			}else{
				$data['message'] = false;
				$data['code'] = 4;
				$data['message'][] = ' failed to save data ';
				$data['result'] = false;
				
				return $data;				
			}
	
		}
		
		if(!$entourageid){
				$data['message'] = false;
				$data['code'] = 5;
				$data['message'][] = ' not found register id ';
				$data['result'] = false;
				
				 $this->logger->log(json_encode($data));
				return $data;	 
		}
		
	 	$sql = " SELECT MAX(version) version FROM my_registrant LIMIT 1";		
		$latestversion = $this->apps->fetch($sql);
		if($latestversion) $versionlatest = intval($latestversion['version'])+1;
		else $versionlatest = 0;
		
		$sql = "
			INSERT INTO  `my_registrant` (  `userid`, `friendid`, `ftype`, `groupid`, `date_time`, `eventid`, `n_status`,version,brand)  
			VALUES ('{$this->apps->user->id}','{$entourageid}',0,0,NOW(),{$eventid},{$registrantstatus},{$versionlatest},{$currentuserbrand})  
			"; 
			
		$this->apps->query($sql); 
		//submission
		$sql = " SELECT MAX(version) version FROM my_submission LIMIT 1";		
		$latestversion = $this->apps->fetch($sql);
		if($latestversion) $versionlatest = intval($latestversion['version'])+1;
		else $versionlatest = 0;
		
		$sql = "
			INSERT INTO `my_submission` (userid,registrantid,eventid,submission,version,datetimes)
			VALUES ('{$this->apps->user->id}','{$entourageid}',{$eventid},1,{$versionlatest},NOW()) 
			ON DUPLICATE KEY UPDATE submission=submission+1
		";
		$this->apps->query($sql);
		if($ownership){
			$data['message'] = false;
			$data['code'] = 1;			 
			if($checkregistrant['ownerid']==$this->apps->user->id) {
				$data['message'][] = 'Success'; 
			}else $data['message'][] = 'updateDataSuccess';
			
			$data['result'] = true;		
		}else{
			$data['message'] = false;
			$data['code'] = 1;
			$data['message'][] = 'Success';
			$data['result'] = true;
		}
		 $this->logger->log(json_encode($data));
		return $data;	
		
				
	}
	
	function synchenturage(){
		
		 
			pr('no data');
			exit;
	 
	}
	
	
	function getSearchEntourage(){
		$limit = 16;
		$start= intval($this->apps->_request('start'));
		$searchKeyOn = array("name","email","last_name");
		$keywords = strip_tags($this->apps->_request('keywords'));	
		$keywords = rtrim($keywords);
		$keywords = ltrim($keywords);
		
		$realkeywords = $keywords;
		$keywords = '';
		
		if(strpos($keywords,' ')) $parseKeywords = explode(' ', $keywords);
		else $parseKeywords = false;
		
		if(is_array($parseKeywords)) $keywords = $keywords.'|'.trim(implode('|',$parseKeywords));
		else  $keywords = trim($keywords);
		
		if(!$realkeywords){
			if($keywords!=''){
				foreach($searchKeyOn as $key => $val){
					$searchKeyOn[$key] = " {$val} REGEXP '{$keywords}' ";
				}
				$strSearchKeyOn = implode(" OR ",$searchKeyOn);
				$qKeywords = " 	AND  ( {$strSearchKeyOn} )";
			}else $qKeywords = " ";
		}else{
			foreach($searchKeyOn as $key => $val){
				$searchKeyOn[$key] = " {$val} like '{$realkeywords}%' ";
				if($val=="email") $searchKeyOn[$key] = " {$val} = '{$realkeywords}' ";
				if($val=="last_name") $searchKeyOn[$key] = " {$val} like '%{$realkeywords}%' ";
				
			}
			$strSearchKeyOn = implode(" OR ",$searchKeyOn);
			$qKeywords = " 	AND  ( {$strSearchKeyOn} )";
		}
		$sql = "SELECT count(*) total FROM registrant_data WHERE n_status =1  {$qKeywords} ORDER BY name ASC ";
		$total = $this->apps->fetch($sql);
		if(!$total) return false;
		
		$sql = "SELECT id,name,img,email,IF(last_name IS NULL,'',last_name) last_name , ownerid FROM registrant_data WHERE n_status =1  {$qKeywords} ORDER BY name ASC, last_name ASC LIMIT {$start},{$limit}";
	
		$qData = $this->apps->fetch($sql,1);
	
		if(!$qData) return false;
		foreach($qData as $key => $val){
			$arrFriends[$val['id']] = $val['id']; 
			if($val['ownerid']==$this->uid) $qData[$key]['isFriends'] = true;
			else $qData[$key]['isFriends'] =false;
		}
		
		if($qData){
			$data['result'] = $qData;
			$data['total'] = $total['total'];
			$data['myid'] = intval($this->uid);
		}
		return $data;
		
	}
	
	
	function entouragestatistic($strentourage=null){
	
		// pr($this->apps->user);exit;
			if($strentourage==null) return false;
			global $CONFIG;
				
			//get enggement of entourage
			$sql ="
			SELECT *
				FROM
				(
					SELECT tags.*
						FROM {$this->dbshema}_news_content_tags tags
						LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
						WHERE  
							tags.n_status=1 
							AND tags.friendid IN ({$strentourage})
							AND tags.friendtype = 0  
							AND ( content.articleType=5 OR content.categoryid IN (2,3) ) 
							AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1  )
						GROUP BY tags.friendid , DATE(tags.date) ORDER BY tags.date ASC
					) a
				GROUP BY a.friendid, DATE(a.date) ORDER BY a.date DESC 
			";	
			$rqData = $this->apps->fetch($sql,1);
			$this->logger->log(" tags search : ".$sql);
			$strcid = false;
			// pr($rqData);
			if(!$rqData) return false;
				$arrfid = false;
			foreach($rqData as $val){
				$arrcid[$val['contentid']] = $val['contentid'];
			}
			if($arrcid) $strcid = implode(',',$arrcid);
			
			//get contentid detail
			$sql="
			SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid ,articleType,can_save
			FROM {$this->dbshema}_news_content anc
			WHERE id IN ({$strcid})";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			$this->logger->log(" content search : ".$sql);
			if(!$qData) return false;
			
			foreach($qData as $key => $val){
				$qData[$key]['imagepath'] = false;
				
				
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";					
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$qData[$key]['banner'] = false;
				else $qData[$key]['banner'] = true;
								
				//CHECK FILE SMALL
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$qData[$key]['imagepath']}/small_{$val['image']}")) $qData[$key]['image'] = "small_{$val['image']}";
				
				//PARSEURL FOR VIDEO THUMB
				$video_thumbnail = false;
				if($val['url']!='')	{
					//PARSER URL AND GET PARAM DATA
					$parseUrl = parse_url($val['url']);
					if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
					else $parseQuery = false;
					if($parseQuery) {
						if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					} 
					$qData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $qData[$key]['video_thumbnail'] = false;
				
				if($qData[$key]['imagepath']) $qData[$key]['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/".$qData[$key]['imagepath']."/".$qData[$key]['image'];
				else $qData[$key]['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/article/default.jpg";
				$contentdata[$val['id']] =  $qData[$key];
				
			}
			
			
			foreach($rqData as $key => $val){
				$arrfid[$val['friendid']][$key] = $val;
				if(array_key_exists($val['contentid'],$contentdata)) $arrfid[$val['friendid']][$key]['contentdetail'] = $contentdata[$val['contentid']];
				else  $arrfid[$val['friendid']][$key]['contentdetail']  = false;
			}
			if($arrfid) return $arrfid;
			
			return false;
	
			
		// i need check how many entourage of this BA
		// check how many times the entourage has engagement
	}
	
	function checkentourage(){
		global $CONFIG;
		$email= strip_tags($this->apps->_request('email'));
		$giid= strip_tags($this->apps->_request('giidnumber'));
		$filter = false;
		
		if($email) $filter[] = " email =\"{$email}\" ";
		if($giid) $filter[] = " giidnumber = \"{$giid}\" ";
		
		if($filter) $qFilter =	implode(" AND ",$filter);
		else $qFilter="";
		
		if($qFilter=="") return false;
		
		$sql = "SELECT * FROM registrant_data WHERE {$qFilter} LIMIT 1 ";		
				// pr($sql);
		$qData = $this->apps->fetch($sql);
		if($qData)	{
			$sql = "SELECT * FROM city_reference WHERE id='{$qData['city']}' LIMIT 1";
			$city = $this->apps->fetch($sql);		
			
			$qData["state"] = $city['provinceid'];
			$qData["city"] = $city['id']; 
			$qData['sex'] =  substr($qData['sex'],0,1);
			
			$brand1=strip_tags($qData["Brand1_ID"]);
			if($brand1=='') {
				$brand1 = "63"; 
			}
			// $brand1 = "0004";
			$brandsub1=strip_tags($qData["Brand1U_ID"]);
			if($brandsub1==''){
				$brandsub1 = "63";  		
			}
			  
			$sql = "SELECT id,code,preference,preferenceid,others FROM tbl_brand_preferences_references WHERE preferenceid IN ('{$brand1}','{$brandsub1}') GROUP BY preferenceid LIMIT 2";
			$rs = $this->apps->fetch($sql,1);		
			// pr($rs);
			$qData['brand1ref']= "63";
			$qData['brand1']= "31";	
			$qData['brandsub1ref']= "63";
			$qData['brandsub1']= "31";					
			if($rs){
				$brandarr = false;
				foreach($rs as $val){
					if($val['others']==1){
						$brandarr[$val['preferenceid']]['brand'] = "31";
						$brandarr[$val['preferenceid']]['preference'] = "63";
					}else{
						$brandarr[$val['preferenceid']]['brand'] = $val['id'];
						$brandarr[$val['preferenceid']]['preference'] = $val['preferenceid'];
					}
				}
			 		// pr($brandarr);
				if($brandarr){ 
					if(array_key_exists($brand1,$brandarr)){
						$qData['brand1ref']=$brandarr[$brand1]['preference'];
						$qData['brand1']=$brandarr[$brand1]['brand'];
					}  
					if(array_key_exists($brandsub1,$brandarr)){
						$qData['brandsub1ref']=$brandarr[$brandsub1]['preference'];
						$qData['brandsub1']=$brandarr[$brandsub1]['brand'];
					} 
				}
			}

				// pr($qData);
			
			if(is_file( $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/photo/small_".$qData['img'])) $qData['image_full_path']= $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/small_".$qData['img'];
			else $qData['image_full_path']=  $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/default.jpg";
			
			// $qData['entouragedata'] = $this->entouragestatistic($qData['id']);
		}
	
		
		if($qData) 	return array('result'=>true,'data'=>$qData);
		return array('result'=>false,'data'=>false);
	}
	
	function getAge($birthDate){
		
         $birthDate = explode("-", $birthDate);
         $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
        return $age;
	}
	
	
	function changephoto($img=false){
		
		if($this->apps->user->leaderdetail->type!=1) return false;
		
		if($img){
			$email=strip_tags($this->apps->_request("email"));
		 
			$sql ="
				UPDATE registrant_data SET img='{$img}',version=version+1 WHERE email=\"{$email}\" LIMIT 1
			";
			
			$this->logger->log($sql);
	 
			$qData = $this->apps->query($sql);
			if($qData) return true;
		}
		return false;	
		
				
	}
	
	
		
	function brandPref(){
		
		$sql = "SELECT code,brandtype FROM tbl_brand_preferences_references ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData)return false;
		$competitorarr = array();
		$pmiarr = array();
		$ourarr = array();
		foreach($qData as $val){
			if($val['brandtype']==0) $competitorarr[(string)$val['code']] =(string)$val['code'];
			if($val['brandtype']==1) $pmiarr[(string)$val['code']] =(string)$val['code'];
			if($val['brandtype']==2) $ourarr[(string)$val['code']] =(string)$val['code'];
		}
		
		$sql = "SELECT COUNT( * ) total, me.Brand1_ID
				FROM registrant_data me
				LEFT JOIN social_member sm ON me.ownerid = sm.id
				LEFT JOIN my_pages pages ON pages.ownerid = me.ownerid 
				WHERE me.n_status
				IN ( 1, 2 )
				AND sm.n_status =1 
				{$this->qSbaFilter}
				{$this->qAreaFilter}
				{$this->qBrandFilter}						 
				GROUP BY me.Brand1_ID
				ORDER BY total";
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		if(!$qData) return false;
		
		foreach($qData as $key => $val){
				$qData[$key]['brandname'] = "Our";
				if(in_array($val['Brand1_ID'],$competitorarr)) $qData[$key]['brandname'] = "Competitor";				
				if(in_array($val['Brand1_ID'],$pmiarr)) $qData[$key]['brandname'] = "PMI";
				if(in_array($val['Brand1_ID'],$ourarr)) $qData[$key]['brandname'] = "Our";
					
		}
		$data['Our'] = 0;
		$data['Competitor'] = 0;
		$data['PMI'] = 0;
		
		foreach($qData as $key => $val){
				if($qData[$key]['brandname']=='Our') $data[$qData[$key]['brandname']]+=$val['total'];
				if($qData[$key]['brandname']=='Competitor')$data[$qData[$key]['brandname']]+=$val['total'];
				if($qData[$key]['brandname']=='PMI')$data[$qData[$key]['brandname']]+=$val['total'];
		}
		// pr($data);
		return $data;
	
	}
	
	function genderPref(){
	
		$sql = "SELECT COUNT( * ) num, me.sex, DATE(me.register_date) dd
				FROM registrant_data me 
				LEFT JOIN social_member sm ON me.ownerid = sm.id
				LEFT JOIN my_pages pages ON pages.ownerid = me.ownerid 
				WHERE me.n_status IN ( 1, 2 )
				AND sm.n_status =1 AND me.sex NOT LIKE 'M' AND me.sex NOT LIKE 'F'
				{$this->qSbaFilter}
				{$this->qAreaFilter}
				{$this->qBrandFilter}	 
				GROUP BY me.sex
				ORDER BY num";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		
		foreach($qData as $val){
			
			$data[$val['sex']] = $val['num'];
		}
		// pr($qData);
		return $qData;
	
	}
	
	function agePref(){
		 
		$sql = "
				SELECT count( * ) num, DATE_FORMAT( me.register_date, '%Y-%m-%d' ) datetime, me.sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( me.birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( me.birthday, 5 ) ) AS age
				FROM registrant_data me
				LEFT JOIN social_member sm ON me.ownerid = sm.id
				LEFT JOIN my_pages pages ON pages.ownerid = me.ownerid 
				WHERE me.n_status IN ( 1, 2 )
				AND me.register_date <> '0000-00-00'
				AND me.register_date IS NOT NULL 
				{$this->qSbaFilter}
				{$this->qAreaFilter}
				{$this->qBrandFilter}
				GROUP BY age
				HAVING age <> '2013'
				AND age >= 0
				ORDER BY age ASC";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		$data['18 - 24']= 0;
		$data['25 - 29']= 0;
		$data['30 - above']= 0;
		foreach( $qData as $val ){
			if($val['age']==null ) $data['null']+= $val['num'];
			else{
			if($val['age']<=24 ) $data['18 - 24'] += $val['num']; 
			if($val['age']>=25 && $val['age']<=29 ) $data['25 - 29'] += $val['num'];
			if($val['age']>=30 ) $data['30 - above']+= $val['num'];
			}
			
		}		
		 
		return $data;	
	}
	
	function getallpmientourage(){
		
	 
		$data['Competitor'] = 0;
		$data['PMI'] = 0;
		
		$sql = "SELECT preferenceid,brandtype FROM tbl_brand_preferences_references ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return $data;
		$competitorarr = array();
		$pmiarr = array();
		 
		foreach($qData as $val){
			if($val['brandtype']==0) $competitorarr[(string)$val['preferenceid']] =(string)$val['preferenceid'];
			else $pmiarr[(string)$val['preferenceid']] =(string)$val['preferenceid'];
		 
		}
		
		
		$filter = strip_tags($this->apps->_p('filter'));
	  
		$qFilter =" me.n_status	IN ( 0,1,2 )	";
		if($filter=="pending") $qFilter = "   me.n_status = 0 ";
		if($filter=="accept") $qFilter = "   me.n_status = 1 ";
		if($filter=="reject") $qFilter = "   me.n_status = 2 ";
		
		 
		$sql = "SELECT COUNT( * ) total, me.Brand1_ID
				FROM registrant_data me 
				WHERE {$qFilter} 	 
				GROUP BY me.Brand1_ID
				ORDER BY total";
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		if(!$qData) return $data;
		
		foreach($qData as $key => $val){
				$qData[$key]['brandname'] = "PMI";
				if(in_array($val['Brand1_ID'],$competitorarr)) $qData[$key]['brandname'] = "Competitor";				
				if(in_array($val['Brand1_ID'],$pmiarr)) $qData[$key]['brandname'] = "PMI";
				 
					
		}
		
		
		foreach($qData as $key => $val){
				 
				if($qData[$key]['brandname']=='Competitor')$data[$qData[$key]['brandname']]+=$val['total'];
				if($qData[$key]['brandname']=='PMI')$data[$qData[$key]['brandname']]+=$val['total'];
		}
		// pr($data);
		return $data;
	}
	
	
	function brandpreflists(){
		$Brand_ID = (string)$this->apps->_p('Brand_ID');
		if($Brand_ID){
			$qPrefBrand = " AND id  = '{$Brand_ID}'" ;
			$qGroupBy = "  " ;
			$qSelected = " id Brand_ID,preferenceid SubBrand_ID,brand_name Brand_Name, subbrandname SubBrand_Name  " ; 
		}else{
			$qPrefBrand = "  " ;
			$qGroupBy = "  GROUP BY id " ;
			$qSelected = " id Brand_ID, brand_name Brand_Name " ; 
			/* kevin request di buka semua */
			$qGroupBy = "  " ;
			$qSelected = " *  " ; 
		}
		$sql = "
		SELECT {$qSelected} 
		FROM tbl_brand_preferences_references 
		WHERE 1 {$qPrefBrand} {$qGroupBy} ";
		$rs = $this->apps->fetch($sql,1);
		if($rs) return $rs;
		else return array();
	}
	
	function citylists(){
			
			$provinces = (string)$this->apps->_p('Province_ID');
			if($provinces){
				$qPrefBrand = "  AND provinceid='{$provinces}' " ;
				$qGroupBy = "  " ;
				$qSelected = " id City_ID,city City_Name" ; 
			}else{
				$qPrefBrand = "  " ;
				$qGroupBy = "  GROUP BY provinceid " ;
				$qSelected = " provinceName, provinceid Province_ID  " ; 
				/* kevin request di buka semua */
				$qGroupBy = "  " ;
				$qSelected = " *  " ; 
			}
			
			$sql = "SELECT {$qSelected} FROM city_reference WHERE 1  {$qPrefBrand} {$qGroupBy}  ";
			$rs = $this->apps->fetch($sql,1);	
			if($rs) return $rs;
			else return array();			
	}
	
	function getEntourageChartStat_just_oldLogic(){
		$data['result'] = false;
		$data['entourage']['1'] = "0";
		$data['entourage']['2'] ="0";
	   
	 
		$data['existing']['2'] ="0";
		$data['total'] ="0";
		
		$eSql = "";
		$eventid = intval($this->apps->_p('menuid'));
		$data['menuid'] ="{$eventid}";
		
	
		
		$user = $this->apps->userHelper->getUserProfile(); 
		// pr($user);exit;
		$data['email'] ="{$user['email']}";
		$data['brandid'] ="{$user['brand']}";
		if($eventid){
			$eSql = " AND eventid={$eventid} ";
		}	
	 
		$uSql = " AND ownerid={$this->uid} ";
		 			
		$sql = "SELECT COUNT(1) total,n_status FROM registrant_data WHERE n_status IN (1,2) AND usertype IN (0,1) {$eSql} {$uSql} GROUP BY n_status ";
		$rs = $this->apps->fetch($sql,1);	
		$sql = " SELECT COUNT(1) total,usertype FROM registrant_data WHERE n_status IN (1) {$eSql} {$uSql} GROUP BY usertype ";
		// pr($sql);
		$this->logger->log($sql);
		$rsusertype = $this->apps->fetch($sql,1);	
		 $total = 0;
		if($rs){
			foreach($rs as $val){
				$data['entourage'][$val['n_status']] = $val['total'];
				
			}
			$data['result'] = true;
		
		}
		
		if($rsusertype){
			foreach($rsusertype as $val){ 
					if($val['usertype']==2) $data['existing'][$val['usertype']] = $val['total'];
				
				
			}
			//$data['entourage']["1"] = (string)($total-$data['existing'][$val['usertype']]);
			
		}
		$total=$data['entourage']['1']+$data['entourage']['2']+$data['existing']['2'];
		
		 $data['total'] = (string)$total;
		$this->logger->log(json_encode($data));
		return $data;
	}
	
	function getEntourageChartStat(){
		$data['result'] = false;
		$data['entourage']['1'] = "0";
		$data['entourage']['2'] ="0";
		$data['submission']['total'] ="0";
		$data['submission']['version'] ="0";
		$data['submission']['lastdate'] =date("Y-m-d H:i:s");
	   
	 
		$data['existing']['2'] ="0";
		$data['total'] ="0";
		
		$eSql = "";
		$eventid = intval($this->apps->_p('menuid'));
		$data['menuid'] ="{$eventid}";
		
	
		
		$user = $this->apps->userHelper->getUserProfile(); 
		// pr($user);exit;
		$data['email'] ="{$user['email']}";
		$data['brandid'] ="{$user['brand']}";
		if($eventid){
			$eSql = " AND eventid={$eventid} ";
		}	
	 
		$uSql = " AND userid={$this->uid} ";
		 			
		$sql = "SELECT COUNT(1) total,n_status FROM my_registrant WHERE  1 {$eSql} {$uSql} GROUP BY n_status ";
		$rs = $this->apps->fetch($sql,1);	
		
		 	$data['result'] = true;
		 $total = 0;
		if($rs){
			foreach($rs as $val){
				if($val['n_status']==3) $data['existing']["2"] = $val['total'];
				if($val['n_status']==1) $data['entourage'][$val['n_status']] = $val['total'];
				if($val['n_status']==2) $data['entourage'][$val['n_status']] = $val['total'];
				// if($val['n_status']==0) $data['entourage'][$val['n_status']] = $val['total'];
				if($val['n_status']!=0)$total+= $val['total'];
			} 
			
		} 
		
			$sql = "SELECT COUNT(1) total,eventid,MAX(version) version,MAX(datetimes)datetimes FROM tbl_user_submission WHERE  1 {$eSql} {$uSql} GROUP BY eventid ";
			$this->logger->log($sql);
			$submission = $this->apps->fetch($sql);	
			if($submission){
				$data['submission']['total'] =(string)$submission['total'];
				$data['submission']['version'] =(string)$submission['version'];
				$data['submission']['lastdate'] =(string)$submission['datetimes'];
			}
	
		$data['total'] = (string)$total;
		$this->logger->log(json_encode($data));
		
		return $data;
	}
	
	function sendfreebadges($email='',$event = "EVENT FREE BADGES"){ 
		 
			if(!$email) return false;
		 	$data['email']	=$email;  
			$data['event']	=$event; 
			$days = date('D');
			if($days=='Sat'){
				$promotionalsite =  $this->config['NEVERSAYMAYBESITE']; 
				$this->curlPOST($promotionalsite."synch/offline",$data);
				return true;
			}else return false;
		
	}
	
	function curlPOST($url,$params="",$timeout=15){
		if($params) $data_string = http_build_query($params);
		$ipuser = sha1($_SERVER['REMOTE_ADDR']);
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		if($params) curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);           
		curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec ($ch);
		$info = curl_getinfo($ch); 		
		curl_close ($ch);
		$this->logger->log(json_encode($response));
		return $response;
	}
	
	function synchenturage_batch(){
		 
		pr('no data');
		 exit;
	}
	
	
	
	function getEntourageChartStatFromReport(){
		$data['result'] = false;
		$data['entourage']['1'] = "0";
		$data['entourage']['2'] ="0";
		$data['submission']['total'] ="0";
		$data['submission']['version'] ="0";
		$data['submission']['lastdate'] =date("Y-m-d H:i:s");
	   
	 
		$data['existing']['2'] ="0";
		$data['total'] ="0";
		
		$eSql = "";
		$eventid = intval($this->apps->_p('menuid'));
		$data['menuid'] ="{$eventid}";
		
	
		
		$user = $this->apps->userHelper->getUserProfile(); 
		// pr($user);exit;
		$data['email'] ="{$user['email']}";
		$data['brandid'] ="{$user['brand']}";
		if($eventid){
			$eSql = " AND eventid={$eventid} ";
		}	
	 
		$uSql = " AND userid={$this->uid} ";
		 			
		$sql = "
		SELECT 
			COUNT(1) total,n_status 
			FROM {$this->config['DATABASE'][0]['DATABASE']}.my_registrant 
		WHERE  1 {$eSql} {$uSql} 
		GROUP BY n_status ";
		$rs = $this->apps->fetch($sql,1);	
		// pr($sql);
		 	$data['result'] = true;
		 $total = 0;
		if($rs){
			foreach($rs as $val){
				if($val['n_status']==3) $data['existing']["2"] = $val['total'];
				if($val['n_status']==1) $data['entourage'][$val['n_status']] = $val['total'];
				if($val['n_status']==2) $data['entourage'][$val['n_status']] = $val['total'];
				// if($val['n_status']==0) $data['entourage'][$val['n_status']] = $val['total'];
				if($val['n_status']!=0)$total+= $val['total'];
			} 
			
		} 
		
			$sql = "
			SELECT COUNT(1) total,eventid,version,MAX(datetimes) latestdate
				FROM {$this->config['DATABASE'][0]['DATABASE']}.my_submission 
			WHERE  1 {$eSql} {$uSql} 
			GROUP BY event ";
			$this->logger->log($sql);
			$submission = $this->apps->fetch($sql);	
			if($submission){
				$data['submission']['total'] =(string)$submission['total'];
				$data['submission']['version'] =(string)$submission['version'];
				$data['submission']['lastdate'] =(string)$submission['latestdate'];
			}
	
		$data['total'] = (string)$total;
		$this->logger->log(json_encode($data));
		
		return $data;
	}
	
}

?>

