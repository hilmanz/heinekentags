<?php 

class userHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "athreesix";	
	}

	function getUserProfile(){
		$uid = intval($this->apps->_request('uid'));
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
			$sql = "
			SELECT sm.*,cityref.city as cityname FROM social_member sm
			LEFT JOIN {$this->dbshema}_city_reference cityref ON sm.city = cityref.id
			WHERE sm.id = {$uid} LIMIT 1";
			// pr($sql);
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);
			if(!$qData)return false;
			$sql ="
			SELECT ranktable.*
			FROM my_rank mrank
			LEFT JOIN {$this->dbshema}_rank_table ranktable ON ranktable.id = mrank.rank
			WHERE userid = {$uid} 
			AND n_status = 1 LIMIT 1		
			";
			
			$qRankData = $this->apps->fetch($sql);	
		
			if($qRankData){
			
						$qData['rank'] = $qRankData['rank'];
				
			}
			return $qData;
		}
		return false;
	}
	
	function getUserAttribute(){		
		$sql = "
		SELECT sum(ancr.point) rank,categoryid ,category
		FROM axis_news_content_rank ancr
		LEFT JOIN axis_news_content_category ancc ON ancc.id= ancr.categoryid
		WHERE userid={$this->uid} 
		GROUP BY categoryid ORDER BY rank DESC LIMIT 5 ";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
	
		if($qData){
			$mostLike = null;
			foreach($qData as $val){
				$mostLike[] = $val['category'];		
			}
			$userLikeCategory = implode(' , ',$mostLike);
		}
		$sql = "
			SELECT art.rank titleRank,art.id levelRank FROM my_rank sr
			LEFT JOIN social_media_account sma ON sma.userid=sr.userid
			LEFT JOIN axis_rank_table art ON art.id=sr.rank
			WHERE sr.userid = {$this->uid} AND sr.n_status = 1 limit 1		
		";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(isset($userLikeCategory)) $qData['userlike'] = $userLikeCategory;
		if($qData)	return $qData;
		else return false;
	
	}
	
	function getRankUser(){
		$sql ="
			SELECT * 
			FROM my_rank 
			WHERE userid = {$this->uid} 
			AND n_status = 1 LIMIT 1		
			";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);	
	
		if($qData){
			$lastPoint = $qData['point'];
			$lastDate  = $qData['date'];
	
			$qData = null;
			//cek new point // > tanggal
			$sql ="
				SELECT SUM(score) total 
				FROM tbl_exp_point 
				WHERE user_id = {$this->uid} AND date_time > '{$lastDate}'
				";
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);	
			$point = $qData['total'];
			$qData = null;
					
			//klo ada point baru, setelah penginsert-an point sebelum nya , tambah point nya
			if($point==0)	return false;
				
			$newPoint = $lastPoint+$point;
					
			$sql = "
				SELECT id FROM {$this->dbshema}_rank_table 
				WHERE minPoint <= {$newPoint} AND maxPoint > {$newPoint} LIMIT 1";
			$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);	
			$rank = $qData['id'];
			$qData = null;
			
			if($rank){
				$sql="INSERT INTO my_rank (userid,date,date_ts,rank,point,n_status) VALUES ({$this->uid},NOW(),".time().",{$rank},{$newPoint},1) ";
				$this->logger->log($sql);
				$qData = $this->apps->query($sql);
				$lastID = $this->apps->getLastInsertId();
				$qData = null;
				if($lastID!=0 || $lastID!=null){
				
					$sql="UPDATE my_rank SET n_status = 0 WHERE userid={$this->uid} AND id <> {$lastID}  ";
					$this->logger->log($sql);
					$qData = $this->apps->query($sql);
					$qData = null;
				}else {
					//cek data if n_status 1 have duplicate value
					$sql = "
						SELECT count(*) total, id FROM my_rank 
						WHERE n_status = 1 AND userid={$this->uid} ORDER BY id DESC LIMIT 2";
						$this->logger->log($sql);
					$qData = $this->apps->fetch($sql);	
					
					if($qData['total']>=2){
						$qData = null;
						$sql = "
						SELECT id FROM my_rank 
						WHERE n_status = 1 AND userid={$this->uid} ORDER BY id DESC LIMIT 1";
						$this->logger->log($sql);
						$qData = $this->apps->fetch($sql);	
						$usingIDRank = intval($qData['id']);
						$qData = null;
						if($usingIDRank!=0){
							$sql="UPDATE my_rank SET n_status = 0 WHERE id <> {$usingIDRank} AND userid={$this->uid} ";
							$this->logger->log($sql);
							$qData = $this->apps->query($sql);
							$qData = null;
						} 
					}else return true;
				
				
				}
			}
			return false;
			
		}else{
			
			//cek klo uda ada activity brarti rollback rank nya
			$sql ="
					SELECT count(*) total 
					FROM tbl_exp_point 
					WHERE user_id = {$this->uid} 
					LIMIT 1	
					";
				$this->logger->log($sql);
			$qData = $this->apps->fetch($sql);	
			
			if($qData['total']<=0){
				//klo ga ada. insert ke social rank newbie
				$sql="INSERT INTO my_rank (userid,date,date_ts,rank,point,n_status) VALUES ({$this->uid},NOW(),".time().",1,0,1) ";
				$this->logger->log($sql);
				$qData = $this->apps->query($sql);	
			}else{
				$qData = null;
				$sql ="
					SELECT SUM(score) total 
					FROM tbl_exp_point 
					WHERE user_id = {$this->uid} 
					";
					$this->logger->log($sql);
				$qData = $this->apps->fetch($sql);	
				$point = intval($qData['total']);
				$qData = null;
			
				$sql = "
					SELECT id FROM {$this->dbshema}_rank_table
					WHERE minPoint <= {$point} AND maxPoint >= {$point} LIMIT 1";
					$this->logger->log($sql);
				$qData = $this->apps->fetch($sql);	
				$rank = $qData['id'];
					
				if($rank!=0|| $rank!=null){
					$sql="INSERT INTO my_rank (userid,date,date_ts,rank,point,n_status) VALUES ({$this->uid},NOW(),".time().",{$rank},{$point},1) ";
					$this->logger->log($sql);
					$qData = $this->apps->query($sql);		
					return true;					
				}
			}
		return false;
		}
		
	
	}
	
	
	function getPreferenceThemeUser(){
		$sql =" SELECT * FROM social_preference_page WHERE userid={$this->uid} AND n_status=1 LIMIT 1";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		// print_r( unserialize($qData['apperances']));exit;
		if($qData) return unserialize($qData['apperances']);
		else return false;
	}
	
	function savePreferenceThemeUser(){
		$data = $this->getPreferenceThemeUser();
		if($this->apps->Request->getPost('bodyColor')) $data['body']['color'] = $this->apps->Request->getPost('bodyColor');
		// if($this->apps->Request->getPost('bodyImage')) $data['body']['image'] = $this->apps->Request->getPost('bodyImage');
		// $data['content']['color'] = $this->apps->Request->getPost('contentColor');
		// $data['border']['color'] = $this->apps->Request->getPost('borderColor');
		// $data['header']['font']['family'] = $this->apps->Request->getPost('headerFontFamily');
		// $data['header']['font']['size'] = $this->apps->Request->getPost('headerFontSize');
		// $data['header']['font']['color'] = $this->apps->Request->getPost('headerFontColor');
		if( $this->apps->Request->getPost('contentFontFamily')) $data['content']['font']['family'] = $this->apps->Request->getPost('contentFontFamily');
		if( $this->apps->Request->getPost('contentFontSize')) $data['content']['font']['size'] = $this->apps->Request->getPost('contentFontSize');
		if( $this->apps->Request->getPost('contentFontColor')) $data['content']['font']['color'] = $this->apps->Request->getPost('contentFontColor');
				
		$dataPreference = serialize($data);
		
		$sql="INSERT INTO 
		social_preference_page (userid,apperances,date,n_status) VALUES ({$this->uid},'{$dataPreference}',NOW(),1) 
		ON DUPLICATE KEY UPDATE
		apperances = VALUES(apperances)
		";
		$this->logger->log($sql);
		$qData = $this->apps->query($sql);	
		
		
	}
	
	
	function updateUserProfile(){
	
		$loginHelper = $this->apps->useHelper('loginHelper');
		
		$this->logger->log('can update profile');
		//cek token valid

		$tokenize = strip_tags($this->apps->_p('tokenize'));
		$accepttoken = cektokenize($tokenize,$this->uid);		
		if(!$accepttoken) return false;
		
		//get user
		$sql = "SELECT * FROM social_member WHERE n_status=1 AND id={$this->uid} LIMIT 1";
		$this->logger->log($sql);
		$rs = $this->apps->fetch($sql);
		if(!$rs)return false;
		$rs = null;
		$name = strip_tags($this->apps->_p('name'));
		$influencer = strip_tags($this->apps->_p('influencer'));
		$StreetName = strip_tags($this->apps->_p('StreetName'));
		$sex = strip_tags($this->apps->_p('sex'));
		$birthday = strip_tags($this->apps->_p('birthday'));
		$description = strip_tags($this->apps->_p('description'));
		if($name!='') $arrQuery[] = " name='{$name}' ";
		if($influencer!='') $arrQuery[] = " influencer='{$influencer}' ";
		if($StreetName!='') $arrQuery[] = " StreetName='{$StreetName}' ";
		if($sex!='') $arrQuery[] = " sex='{$sex}' ";
		if($birthday!='') $arrQuery[] = " birthday='{$birthday}' ";
		if($description!='') $arrQuery[] = " description='{$description}' ";

			$strQuery = implode(',',$arrQuery);
			if(!$strQuery) return false;
			$this->logger->log($strQuery);
			
			$sql = "
			UPDATE social_member 
			SET {$strQuery} 
			WHERE id={$this->uid} LIMIT 1
			";
			// pr($influencer);exit;
			$this->logger->log($sql);

			$qData = $this->apps->query($sql);
			if($qData) {
					$sql = "
					SELECT *
					FROM social_member 
					WHERE 
					n_status=1 AND 
					id={$this->uid}
					LIMIT 1";
				$this->logger->log($sql);
				$rs = $this->apps->fetch($sql);
				if($rs) $loginHelper->setdatasessionuser($rs); 
				else return false;
				return true;
			}else return false;
		
			
	
			
		}	
	
	function saveImage(){
			global $CONFIG;
			$filename="";
		// return array('result'=>true,'arrImage'=> $arrImageData);
			if($_FILES['myImage']['error']==0)	{
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."user/photo/";	
					$dataImage  = $this->apps->uploadHelper->uploadThisImage(@$_FILES['myImage'],$path);
					if($dataImage['result']){
					
						$sql = "
						UPDATE social_member 
						SET  img = '{$dataImage['arrImage']['filename']}'
						WHERE id={$this->uid} LIMIT 1
						";
						$this->logger->log($sql);
						
						$qData = $this->apps->query($sql);
						if($qData)	$filename = @$dataImage['arrImage']['filename'];
					}
			}
			return $filename;
	}
	
	
	
	function saveCropImage(){
				global $CONFIG;
				
				$loginHelper = $this->apps->useHelper('loginHelper');
				
				$files['source_file'] = $this->apps->_p("imageFilename");
				$files['url'] = "{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/";
				$arrFilename = explode('.',$files['source_file']);
				if($files==null) return false;
				$targ_w = $this->apps->_p('w');
				$targ_h =$this->apps->_p('h');
				$jpeg_quality = 90;
				
				if($files['source_file']=='') return false;
				
				$src = 	$files['url'].$files['source_file'];
				try{
					$img_r = false;
					if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) $img_r = imagecreatefromjpeg($src);
					if($arrFilename[1]=='png' ) $img_r = imagecreatefrompng($src);
					if($arrFilename[1]=='gif' ) $img_r = imagecreatefromgif($src);
					if(!$img_r) return false;
					$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

					imagecopyresampled($dst_r,$img_r,0,0,$this->apps->_p('x'),$this->apps->_p('y'),	$targ_w,$targ_h,$this->apps->_p('w'),$this->apps->_p('h'));

					// header('Content-type: image/jpeg');
					if($arrFilename[1]=='jpg' || $arrFilename[1]=='jpeg' ) imagejpeg($dst_r,$files['url'].$files['source_file'],$jpeg_quality);
					if($arrFilename[1]=='png' ) imagepng($dst_r,$files['url'].$files['source_file']);
					if($arrFilename[1]=='gif' ) imagegif($dst_r,$files['url'].$files['source_file']);
					
				}catch (Exception $e){
					return false;
				}
				include_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
					
				try{
					$thumb = PhpThumbFactory::create($files['url'].$files['source_file']);
				}catch (Exception $e){
					// handle error here however you'd like
				}
				list($width, $height, $type, $attr) = getimagesize($files['url'].$files['source_file']);
				$maxSize = 400;
				if($width>=$maxSize){
					if($width>=$height) {
						$subs = $width - $maxSize;
						$percentageSubs = $subs/$width;
					}
				}
				if($height>=$maxSize) {
					if($height>=$width) {
						$subs = $height - $maxSize;
						$percentageSubs = $subs/$height;
					}
				}
				if(isset($percentageSubs)) {
				 $width = $width - ($width * $percentageSubs);
				 $height =  $height - ($height * $percentageSubs);
				}
				
				$w_small = $width - ($width * 0.5);
				$h_small = $height - ($height * 0.5);
				$w_tiny = $width - ($width * 0.7);
				$h_tiny = $height - ($height * 0.7);
				
				//resize the image
				$thumb->adaptiveResize($width,$height);
				$big = $thumb->save(  "{$files['url']}".$files['source_file']);
				$thumb->adaptiveResize($width,$height);
				$prev = $thumb->save(  "{$files['url']}prev_".$files['source_file']);
				$thumb->adaptiveResize($w_small,$h_small);
				$small = $thumb->save( "{$files['url']}small_".$files['source_file'] );
				$thumb->adaptiveResize($w_tiny,$h_tiny);
				$tiny = $thumb->save( "{$files['url']}tiny_".$files['source_file']);
								
				if(file_exists($files['url'].$files['source_file'])){
					//saveit
					$sql = "
					UPDATE social_member 
					SET  img = '{$files['source_file']}'
					WHERE id={$this->uid} LIMIT 1
					";
					$this->logger->log($sql);
					
					$qData = $this->apps->query($sql);
					if($qData){
							$sql = "
							SELECT *
							FROM social_member 
							WHERE 
							n_status=1 AND id={$this->uid} LIMIT 1 ";
						$rs = $this->apps->fetch($sql);	
						if(!$rs)return false;
						$rs['img'] = $files['source_file'];
						//how to update the session on on fly
						if($rs) $loginHelper->setdatasessionuser($rs); 
						else return false;
						return $files['source_file'];
					}else return false;
					
				}else return false;
				
	}
	
	function isFriends($fid=null,$all=false){
		$fid = strip_tags($fid);
		if($fid=='') return false;
		if($this->uid==0) return false;
			
		$sql = "SELECT * FROM my_circle WHERE userid= {$this->uid} AND friendid IN ({$fid}) AND n_status<>0";

		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data['total'] = count($qData);
		$data['result'] = $qData;
		
		if($data['total']>0) {
			if(!$all)return true;
			else return $data['result'];
		}else return false;
		
	}
	
	function getGroupUser(){
			$uid = strip_tags($this->apps->_request('uid'));
			if(!$uid) $uid = intval($this->uid);
			if($uid!=0 || $uid!=null) {
				$sql = "SELECT * FROM my_circle_group WHERE userid IN ({$uid}) AND n_status = 1";
				
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					foreach($qData as $val){
						$groupCircle[$val['id']] = $val['name'];
					}	
					if($groupCircle)	return $groupCircle;
					else return false;
				}
				
				
			}
			return false;
	}
	function getFriends($all=true,$limit=8,$start=0){
	//global user id, for list of friend of friend : 21,23,1,5,3
		$uid = strip_tags($this->apps->_request('uid'));
		$start = intval($this->apps->_p('start'));
		$group = intval($this->apps->_request('groupid'));
	
		if(!$uid) $uid = intval($this->uid);
		
		if($uid!=0 || $uid!=null) {
		
				
			//get circle group
		
				$groupdata = $this->getGroupUser($uid);
				$arrGroupId = array();
				if($groupdata) {
					
					foreach($groupdata as $key => $val){			
						$arrGroupId[] = $key;										
					}
						
				}
				
			
			if($group!=0){			
				$strGroupid = $group;
			}else {	
				array_push($arrGroupId,0);
				$strGroupid = implode(',',$arrGroupId);
			}
			// get all friend of this user
			$sql =	" SELECT count(*) total FROM ( SELECT friendid FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 GROUP BY friendid ) a";
			
			$friends = $this->apps->fetch($sql);

			if(!$friends) return false;
			if($friends['total']==0) return false;
			
			if($all) $qAllQData = " LIMIT {$start},{$limit} ";
			else  $qAllQData = "";
			
			//get circle
			$sql =	" SELECT * FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 GROUP BY friendid  ORDER BY id DESC {$qAllQData}";
		
			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
		
			foreach($qData as $val){			
				$arrFriendId[ $val['friendid']] = $val['friendid'];
				$circledata[]= $val;
			}
			
			if(!$arrFriendId) return false;
			$strFriendId = implode(',',$arrFriendId);
			if($all) $qAllQData = " LIMIT {$limit} ";
			else  $qAllQData = "";
			//get friend on groups
			$sql =	" SELECT * FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 ";
			$qData = $this->apps->fetch($sql,1);
			//catch array
			if(!$qData) return false;
			$arrFriendinGroup = false;
			foreach($qData as $key => $val){
				$arrFriendinGroup[$val['friendid']][] = $val['groupid'];
			}
				
			//get friend detail
			$sql =	" SELECT id,name,img,sex,last_name FROM social_member WHERE id IN ({$strFriendId}) AND  n_status = 1 {$qAllQData} ";
			
			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
			foreach($qData as $key => $val){
			
				//merge groups to friends
				if($arrFriendinGroup){
					if(array_key_exists($val['id'],$arrFriendinGroup)) $qData[$key]['groups'] = $arrFriendinGroup[$val['id']];
					else $qData[$key]['groups'] = false;
				}else $qData[$key]['groups'] = false;
				
				$frienduser[$val['id']] = $qData[$key];
				
			}
		
			if(!$circledata&&!$frienduser) return false;
			
			//merge data
			foreach($circledata as $key => $val){
				if(array_key_exists($val['friendid'],$frienduser)) $circledata[$key]['frienddetail'] = $frienduser[$val['friendid']];
				else  $circledata[$key]['frienddetail'] = false;
				
				
			}
			
			//create new array
			foreach($circledata as $key => $val){
				$circle[$val['userid']][$val['groupid']][] = $val;
			}
			
			if(!$circle) return false;
			
			// pr($circle);
			$data['result'] = $circle;
			$data['total'] = $friends['total'];	
			
		// pr($data);
			return $data;
			
			
		}
		return false;
	}
	
	function getCircleUser($all=true,$limit=8,$start=0){
		//global user id, for list of friend of friend : 21,23,1,5,3
		$uid = strip_tags($this->apps->_request('uid'));
		$start = intval($this->apps->_request('start'));

		
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
		
				
			//get circle group
		
				$groupdata = $this->getGroupUser($uid);
				$arrGroupId = array();
				if($groupdata) {
						
					foreach($groupdata as $key => $val){			
						$arrGroupId[] = $key;										
					}
						
				}else array_push($arrGroupId,0);
		
				
				$strGroupid = implode(',',$arrGroupId);
			
			// get all friend of this user
			$sql =	" SELECT count(*) total FROM ( SELECT friendid FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 GROUP BY friendid ) a";
		
			$friends = $this->apps->fetch($sql);
			if(!$friends) return false;
			
			//get circle
			$sql =	" SELECT * FROM my_circle WHERE groupid IN ({$strGroupid}) AND userid IN ({$uid}) AND n_status = 1 ORDER BY id DESC  ";

			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
			
			foreach($qData as $val){			
				$arrFriendId[ $val['friendid']] = $val['friendid'];
				$circledata[]= $val;
			}
		
			if(!$arrFriendId) return false;
			$strFriendId = implode(',',$arrFriendId);
			if($all) $qAllQData = " LIMIT {$limit} ";
			else  $qAllQData = "";
			//get friend detail
			$sql =	" SELECT id,name,img,sex,last_name FROM social_member WHERE id IN ({$strFriendId}) AND  n_status = 1 {$qAllQData} ";
			
			$qData = $this->apps->fetch($sql,1);
			if(!$qData) return false;
			foreach($qData as $val){
				$frienduser[$val['id']] = $val;
			}
			
			if(!$circledata&&!$frienduser) return false;
			
			//merge data
			foreach($circledata as $key => $val){
				if(array_key_exists($val['friendid'],$frienduser)) $circledata[$key]['frienddetail'] = $frienduser[$val['friendid']];
				else  $circledata[$key]['frienddetail'] = false;			
			}
			
			//create new array
			foreach($circledata as $key => $val){
				$circle[$val['userid']][$val['groupid']][] = $val;
			}
			
			if(!$circle) return false;
			
			// pr($circle);
			$data['result'] = $circle;
			$data['total'] = $friends['total'];	
			
		// pr($data);
			return $data;
			
			
		}
		return false;
	
	}
	
	function createCircleUser(){
		$name = preg_replace("/_/i"," ",strip_tags($this->apps->_request('name')));
		$groupid = intval($this->apps->_p('groupid'));
		if($name=='') return false;
		if($groupid!=0){
			$sql = "
			UPDATE my_circle_group SET name=\"{$name}\"
			WHERE id={$groupid} LIMIT 1;
			";
			// pr($sql);
			$this->apps->query($sql);
			return true;
		}else{
			$sql = "
			INSERT INTO my_circle_group (name,userid,datetime,n_status)
			VALUES ('{$name}',{$this->uid},NOW(),1)
			ON DUPLICATE KEY UPDATE n_status=1;
			";		
			$this->apps->query($sql);
			if($this->apps->getLastInsertId()) return array("result"=>true,"content"=>$this->apps->getLastInsertId());
			else return false;
		}

		
	
	}
	
	function uncreateCircleUser(){
		$circleid = strip_tags($this->apps->_p('circleid'));
		$name = str_replace("_"," ",strip_tags($this->apps->_request('name')));
		$sql = "
		UPDATE my_circle_group SET n_status=0
		WHERE id= {$circleid} AND userid={$this->uid}
		AND name = '{$name}'
		LIMIT 1
		";
		// pr($sql);
		$result = $this->apps->query($sql);
		if($result) {
			$sql = "
			UPDATE my_circle SET groupid = 0
			WHERE userid = {$this->uid} AND groupid={$circleid}
			";
			$result = $this->apps->query($sql);
			
			if($result)return true;
			else return false;
		}else return false;
	
	}
	
	function addCircleUser(){
		$uid = intval($this->apps->_request('uid'));
		$groupid = intval($this->apps->_request('groupid'));

		//cek default circle , friends on circle
		if($this->uid==$uid) return false;
		$sql = "SELECT count(*) total, id FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid=0 LIMIT 1";
			
		$qData = $this->apps->fetch($sql);
		
		if(!$qData) return false;
		if($qData['total']>0){
		$oldid = $qData['id'];
		//if found, use update to move friend
			//check they have other group
				$sql = "SELECT count(*) total, id FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid = {$groupid} LIMIT 1";
				$qData = $this->apps->fetch($sql);
			
				if(!$qData) return false;
				if($qData['total']>0){
				//if found, update the status to true
					$sql = "
					UPDATE my_circle SET n_status = 1
					WHERE userid = {$this->uid} AND friendid={$uid} AND id={$qData['id']} LIMIT 1
					";
					
					$result = $this->apps->query($sql);	
					if($result) return true;
					else return false;
				}else{
					$sql = "
					UPDATE my_circle SET groupid = {$groupid} , n_status = 1
					WHERE userid = {$this->uid} AND friendid={$uid} AND id={$oldid} LIMIT 1
					";
					$result = $this->apps->query($sql);	
					if($result) return true;
				}
		}else{
		//if not found, re-check other id
			$sql = "SELECT count(*) total, id FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid = {$groupid} LIMIT 1";
			$qData = $this->apps->fetch($sql);
			if(!$qData) return false;
			if($qData['total']>0){
				//if found, update the status to true
				$sql = "
				UPDATE my_circle SET n_status = 1
				WHERE userid = {$this->uid} AND friendid={$uid} AND id={$qData['id']} LIMIT 1
				";
				
				$result = $this->apps->query($sql);	
				if($result) return true;
				else return false;
				
			}else{
				//if really not found, then use insert
				$sql = "
				INSERT INTO my_circle (friendid,userid,groupid,date_time,n_status)
				VALUES ('{$uid}',{$this->uid},{$groupid},NOW(),1)
				ON DUPLICATE KEY UPDATE groupid = {$groupid}, n_status=1
				";
				
				$this->apps->query($sql);
				
				if($this->apps->getLastInsertId()) return true;
				else return false;
			}
		}		
		
		return false;
		
	
	}
	
	function deGroupCircleUser(){
		$uid = intval($this->apps->_request('uid'));
		$groupid = intval($this->apps->_request('groupid'));
		//cek friend on circle
		$sql = "SELECT count(*) total FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid} AND groupid={$groupid} LIMIT 1";
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if($qData['total']>0){
		//if found, use update to move friend
			$sql = "
			UPDATE my_circle SET n_status = 0
			WHERE userid = {$this->uid} AND friendid={$uid} AND groupid={$groupid} LIMIT 1
			";
			$result = $this->apps->query($sql);	
			if($result) return true;
			else return false;
		
		}else return false;
		
		
	
	}
	
	function unAddCircleUser(){
		$uid = intval($this->apps->_request('uid'));
		$groupid = intval($this->apps->_request('groupid'));
		//cek friend on circle
		$sql = "SELECT count(*) total FROM my_circle WHERE userid= {$this->uid} AND friendid={$uid}  LIMIT 1";
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if($qData['total']>0){
		//if found, use update to move friend
			$sql = "
			UPDATE my_circle SET n_status = 0
			WHERE userid = {$this->uid} AND friendid={$uid} 
			";
			$result = $this->apps->query($sql);	
			if($result) return true;
			else return false;
		}else return false;
		
		
	
	}
	
	function attending($attendartype=0){
		
		$contentid = intval($this->apps->_request('contentid'));
		if($contentid==0) return false;
		
		if($attendartype!=0) {
			
			//select to my_pages_type as what
			$otherid = 0;
		}else $otherid = $this->uid;
		if($otherid==0) return false;
	
		$sql = "SELECT count(*) total FROM my_contest WHERE otherid={$otherid}  AND  mypagestype={$attendartype} AND contestid={$contentid} LIMIT 1";
		
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if($qData['total']>0) return false;
			
		$sql = "INSERT INTO my_contest (contestid,otherid,mypagestype,join_date,n_status) VALUES ({$contentid},{$otherid},{$attendartype},NOW(),1)";
	
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()) return true;
		return false;
		
	}
	
	function getUserFavorite(){
		
		$uid = strip_tags($this->apps->_request('uid'));
		$start = intval($this->apps->_request('start'));	
		$limit = 9;
		if(!$uid) $uid = intval($this->uid);
		if($uid!=0 || $uid!=null) {
				$sql ="
					SELECT contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 AND userid IN ({$uid})  GROUP BY contentid
					";
		
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					foreach($qData as $val){
						$favoriteData[$val['contentid']]=$val['contentid'];
					}
					
				if(!$favoriteData) return false;
				$strContentId = implode(',',$favoriteData);
				
					$sql = "
						SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid 
						FROM {$this->dbshema}_news_content 
						WHERE AND n_status<>3  AND id IN ({$strContentId}) 
						ORDER BY posted_date DESC , id DESC
						LIMIT {$start},{$limit}";
					
					$rqData = $this->apps->fetch($sql,1);
					if(!$rqData) return false;
					//cek detail image from folder
						//if is article, image banner do not shown
					foreach($rqData as $key => $val){
						if(file_exists("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) $rqData[$key]['banner'] = false;
						else $rqData[$key]['banner'] = true;		
					}
				
				if($rqData) $qData=	$this->getStatistictArticle($rqData);
				
				return $qData;
				}
		}
		return false;
	}
	
	
	
	function getSearchFriends(){
		$limit = 16;
		$start= intval($this->apps->_request('start'));
		$searchKeyOn = array("name","email");
		$keywords = strip_tags($this->apps->_p('keywords'));	
		$keywords = rtrim($keywords);
		$keywords = ltrim($keywords);
		if(strpos($keywords,' ')) $parseKeywords = explode(' ', $keywords);
		else $parseKeywords = false;
		
		if(is_array($parseKeywords)) $keywords = $keywords.'|'.trim(implode('|',$parseKeywords));
		else  $keywords = trim($keywords);
			
			if($keywords!=''){
				foreach($searchKeyOn as $key => $val){
					$searchKeyOn[$key] = "{$val} REGEXP '{$keywords}'";
				}
				$strSearchKeyOn = implode(" OR ",$searchKeyOn);
				$qKeywords = " 	AND  ( {$strSearchKeyOn} )";
			}else $qKeywords = "";
			
		$sql = "SELECT id,name,img,email FROM social_member WHERE n_status =1  {$qKeywords} ORDER BY name ASC LIMIT {$start},{$limit}";
		$qData = $this->apps->fetch($sql,1);
	
		if(!$qData) return false;
		foreach($qData as $key => $val){
			$arrFriends[$val['id']] = $val['id']; 
		}
		// search friends
		if(!$arrFriends) return false;
		$strFriends = implode(',',$arrFriends);
		$friendsData = $this->isFriends($strFriends,true);
		$arrFriends = false;
		if($friendsData){
			foreach($friendsData as $val){
				$arrFriends[$val['friendid']] = $val['friendid'];
			}
		}
		foreach($qData as $key => $val){
			$qData[$key]['isFriends'] =false;
			if($arrFriends) {
				if(array_key_exists($val['id'],$arrFriends))$qData[$key]['isFriends'] = true;
			}
			
		}
		
		return $qData;
		
	}
	
	
	function getsba(){
	
		
		
		$sql =" 
		SELECT sm.id,CONCAT(sm.name,' ',sm.last_name) names 
		FROM social_member sm
		LEFT JOIN my_pages mp On mp.ownerid = sm.id
		WHERE mp.type = 7 AND sm.name NOT LIKE '%rifky%'
		AND sm.name NOT LIKE '%ainul%' AND sm.name NOT LIKE '%sigit%' 
		ORDER BY sm.name ASC , sm.last_name ASC
		"; 
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return false;
	}
	
	function getgamestrack($gamesid=0){
		
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		if(!$gamesid)$gamesid = intval($this->apps->_g('gamesid'));
			
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  mp.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
		
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
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
	
	
	function getgamestrackall($gamesid=0){
		
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		if(!$gamesid)$gamesid = intval($this->apps->_g('gamesid'));
		
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  mp.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
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
	
	function getgamestrackentourage($gamesid=0){
		
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = intval($this->apps->_g('areaid'));
		if(!$gamesid)$gamesid = intval($this->apps->_g('gamesid'));
		
		$qUser = "";
		$qcity = "";
		$qGamesid = "";
		if($userid) $qUser = " 	AND  g.userid={$userid} ";
		if($city) $qcity = " 	AND  sm.city={$city} ";
		if($gamesid) $qGamesid = " 	AND  g.gamesid={$gamesid} ";
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
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
	
	function getEntourageReport($start=null,$limit=20,$limitstatus=true){
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
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		else $qBrandid = " 	AND  p.brand IN (4,5) ";
		if($n_status>-1) $qn_status = " 	AND  c.n_status={$n_status} ";
		else $qn_status = " 	AND  c.n_status IN (0,1,2,3) ";	
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		$qLimit = " LIMIT {$start},{$limit}";
		if($limitstatus==false) $qLimit = " ";
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
		 
			$qDate = " AND DATE(e.register_date) >= DATE('{$startdate}') AND DATE(e.register_date) <= DATE('{$enddate}') ";
		}
		
		//GET TOTAL
		$sql = "SELECT count(1) total 	
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid 
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} AND c.ftype=0
				ORDER BY e.id DESC";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
			// pr($sql);
		$sql = "
			SELECT 
			CONCAT(e.name,' ',e.last_name) names,
			c.n_status,
			DATE_FORMAT(e.register_date,'%d/%m/%Y %H:%i:%S') registerdate,
			m.area cityname,
			r.subbrandname
			FROM my_circle c
			LEFT JOIN `my_entourage` e  ON e.id = c.friendid
			LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
			LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
			LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
			WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} AND c.ftype=0
			ORDER BY e.id DESC {$qLimit}";
		$qData = $this->apps->fetch($sql,1);
		
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
	
	
	function getEntourageAge($limit=10){
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
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " 	AND  m.area='{$city}'"; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		if($n_status>-1) $qn_status = " 	AND  c.n_status={$n_status} ";
		else $qn_status = " 	AND  c.n_status IN (0,1,2,3) ";
		
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		 
			$qDate = " AND DATE(me.register_date) >= DATE('{$startdate}') AND DATE(me.register_date) <= DATE('{$enddate}') ";
		}
		
		
		$sql = "
				SELECT count( 1 ) num, DATE_FORMAT( me.register_date, '%Y-%m-%d' ) datetime, me.sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( me.birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( me.birthday, 5 ) ) AS age
					FROM my_circle c
				LEFT JOIN `my_entourage` me  ON me.id = c.friendid  
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
				WHERE  me.register_date <> '0000-00-00'
				AND me.register_date IS NOT NULL  
				{$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}
				GROUP BY age
				HAVING age <> '2013'
				AND age >= 0
				
				ORDER BY age ASC";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
			$data = false;
			$data['young']= 0;
			$data['mature']= 0;
			$data['olde']= 0;
			foreach( $qData as $val ){
				if($val['age']==null ) $data['null']+= $val['num'];
				else{
				if($val['age']<=24 ) $data['young'] += $val['num']; 
				if($val['age']>=25 && $val['age']<=29 ) $data['mature'] += $val['num'];
				if($val['age']>=30 ) $data['olde']+= $val['num'];
				}
				
			}		
			
		return $data;
		 
		 
	}
	
	
	function genderPref($limit=10){
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
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " 	AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		if($n_status>-1) $qn_status = " 	AND  c.n_status={$n_status} ";
		else $qn_status = " 	AND  c.n_status IN (0,1,2,3) ";
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
			 
			
			$qDate = " AND DATE(me.register_date) >= DATE('{$startdate}') AND DATE(me.register_date) <= DATE('{$enddate}') ";
		}
		
		$sql = "SELECT COUNT( 1 ) num, me.sex, DATE(me.register_date) dd
					FROM my_circle c
				LEFT JOIN `my_entourage` me  ON me.id = c.friendid  
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
				WHERE c.n_status IN ( 1, 2 ,0,3)  
				{$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}
				GROUP BY me.sex
				ORDER BY num";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data["M"] = "0";
		$data["F"]= "0";
		foreach($qData as $val){
			
			$data[$val['sex']] = $val['num'];
		}
		// pr($qData);
		return $data;
	
	}
	
	function getEntourageStat($limit=10){
		
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
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " 	AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		if($n_status>-1) $qn_status = " 	AND  c.n_status={$n_status} ";
		else $qn_status = " 	AND  c.n_status IN (0,1,2,3) ";	
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
			
		 
			$qDate = " AND DATE(me.register_date) >= DATE('{$startdate}') AND DATE(me.register_date) <= DATE('{$enddate}') ";
		}
		
		$sql = "SELECT COUNT( 1 ) num, c.n_status, DATE(me.register_date) dd
				FROM my_circle c
				LEFT JOIN `my_entourage` me  ON me.id = c.friendid  
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
				WHERE c.n_status IN ( 1, 2,0,3 ) 
				{$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}	
				GROUP BY c.n_status
				ORDER BY num";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data[0] = "0";
		$data[1]= "0";
		$data[2]= "0";
		foreach($qData as $val){
			
			$data[$val['n_status']] = $val['num'];
		}
		// pr($qData);
		return $data;
	
	}
	
	
	function brandPref($limit=10){
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
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " 	AND  pages.city='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  pages.brand={$brandid} ";
		if($n_status>-1) $qn_status = " 	AND  c.n_status={$n_status} ";
		else $qn_status = " 	AND  c.n_status IN (0,1,2,3) ";	
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
			 
			
			$qDate = " AND DATE(me.register_date) >= DATE('{$startdate}') AND DATE(me.register_date) <= DATE('{$enddate}') ";
		}
		
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
		
		$sql = "SELECT COUNT( * ) total, me.Brand1_ID, me.register_date
				FROM my_circle c
				LEFT JOIN `my_entourage` me  ON me.id = c.friendid 
				LEFT JOIN social_member sm ON c.userid = sm.id
				LEFT JOIN my_pages pages ON pages.ownerid =c.userid
				WHERE c.n_status
				IN ( 0,1, 2,3 )
				AND sm.n_status =1  			
				{$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}	
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
	
	function locationRegistrnt($limit=10){
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
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " 	AND  m.area='{$city}'"; 
		if($brandid) $qBrandid = " 	AND  pages.brand={$brandid} ";
		if($n_status>-1) $qn_status = " 	AND  c.n_status={$n_status} ";
		else $qn_status = " 	AND  c.n_status IN (0,1,2.3) ";	
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
		 
		
			$qDate = " AND DATE(e.register_date) >= DATE('{$startdate}') AND DATE(e.register_date) <= DATE('{$enddate}') ";
		}
		$limit = 10;
		
		if($city){
			/* count entourage */
			$sql = "SELECT COUNT(1) total,
				sm.name cityname, c.n_status
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid 
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
				LEFT JOIN  `my_pages`  pages ON pages.ownerid = c.userid
				LEFT JOIN  `social_member`  sm ON sm.id = c.userid
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} AND m.area IS NOT NULL AND m.area <> ''
				AND pages.type = 7
				GROUP BY m.userid, c.n_status ORDER BY total DESC";
			$qData = $this->apps->fetch($sql,1);
		}else{		
			$sql = "
				SELECT 
				COUNT(1) total,
				e.city,
				m.area cityname, c.n_status
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid 
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN my_pages pages ON pages.ownerid = c.userid
				WHERE 1	{$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} 
				AND m.area IS NOT NULL AND m.area <> ''
				GROUP BY m.area , c.n_status ORDER BY m.area ASC ";
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
	
	/* header count query */
	
	function new_registrant_count(){
	
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));
		$n_status = $this->apps->_g('n_status');
		if($n_status ==='') $n_status = 1;
		
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		else $qBrandid = "AND  p.brand IN (4,5)"; 
		
		if(intval($n_status)===1) $qn_status = " 	AND  c.n_status = 1 ";
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
			// $y = explode('/',$startdate);
			// $x = explode('/',$enddate);
			
			// $startdate = $x[2].'-'.$x[0].'-'.$x[1];
			// $enddate = $y[2].'-'.$y[0].'-'.$y[1];
			
			$qDate = " AND DATE(e.register_date) >= DATE('{$startdate}') AND DATE(e.register_date) <= DATE('{$enddate}') ";
		}		
		$sql = "SELECT COUNT( 1 ) regiscount
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
				LEFT JOIN  `my_pages`  p ON p.ownerid = m.userid
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}  
				";
		// pr($sql);
		if($n_status!=1){
			$data['regiscount']=0;
			return $data;
		}		
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		return $qData;
		
	}
	
	function recontact_count(){
	
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));
		$n_status = $this->apps->_g('n_status');
		if($n_status =='') $n_status = 3;
		
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		else $qBrandid = "AND  p.brand IN (4,5)";
		// if($n_status==1) $qn_status = " 	AND  e.n_status={$n_status} ";
		if(intval($n_status)==3) $qn_status = " 	AND  c.n_status = 3 ";
		// if(intval($n_status)==1) $qn_status = " 	AND  c.n_status = 3 ";
		if(intval($n_status)==2) {
			$data['recontactcount']=0;
			return $data;
		}
		if(intval($n_status)==1) {
			$data['recontactcount']=0;
			return $data;
		}
		if(intval($n_status)==0) {
			$data['recontactcount']=0;
			return $data;
		}
		// else $qn_status = " 	AND  c.n_status = 3 ";	
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
			// $y = explode('/',$startdate);
			// $x = explode('/',$enddate);
			
			// $startdate = $x[2].'-'.$x[0].'-'.$x[1];
			// $enddate = $y[2].'-'.$y[0].'-'.$y[1];
			
			$qDate = " AND DATE(e.register_date) >= DATE('{$startdate}') AND DATE(e.register_date) <= DATE('{$enddate}') ";
		}
		
		$sql = "SELECT COUNT( 1 ) recontactcount
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate} ";
				// pr($sql);
		// if($n_status!=1){
			// $data['recontactcount']=0;
			// return $data;
		// }
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		return $qData;
		
	}
	
	function allRegis_count(){
		
		
		$data['totalregistrant']=0;
			return $data;
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));
		$n_status = $this->apps->_g('n_status');
		if($n_status =='') $n_status = -1;
		
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		else $qBrandid = "AND  p.brand IN (4,5)";
		// if($n_status>-1) $qn_status = " 	AND  c.n_status={$n_status} ";
		if(intval($n_status)=='-1') $qn_status = " 	AND  c.n_status IN (0,1,2,3) ";
		if(intval($n_status)==2) {
			$qn_status = " 	AND  c.n_status IN (2) ";
		}
		if(intval($n_status)==1) {
			$qn_status = " 	AND  c.n_status IN (1) ";
		}
		if(intval($n_status)==0) {
			$qn_status = " 	AND  c.n_status IN (0) ";
		}
		
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
			// $y = explode('/',$startdate);
			// $x = explode('/',$enddate);
			
			// $startdate = $x[2].'-'.$x[0].'-'.$x[1];
			// $enddate = $y[2].'-'.$y[0].'-'.$y[1];
			
			$qDate = " AND DATE(e.register_date) >= DATE('{$startdate}') AND DATE(e.register_date) <= DATE('{$enddate}') ";
		}
		
		$sql = "SELECT COUNT( 1 ) totalregistrant
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}";
				// pr($sql);
		// if($n_status!=1){
			// $data['totalregistrant']=0;
			// return $data;
		// }
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		return $qData;
		
	}
	
	function rejectedregistrant(){
	
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));
		$n_status = $this->apps->_g('n_status');
		// if($n_status =='') $n_status = 2;
		if($n_status === '') $n_status = 2;
		// pr($n_status);
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		else $qBrandid = "AND  p.brand IN (4,5)";
		// if($n_status>-1) $qn_status = " 	AND  e.n_status={$n_status} ";
		if(intval($n_status)===2) $qn_status = " 	AND  c.n_status = 2 ";
				
			
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
			// $y = explode('/',$startdate);
			// $x = explode('/',$enddate);
			
			// $startdate = $x[2].'-'.$x[0].'-'.$x[1];
			// $enddate = $y[2].'-'.$y[0].'-'.$y[1];
			
			$qDate = " AND DATE(e.register_date) >= DATE('{$startdate}') AND DATE(e.register_date) <= DATE('{$enddate}') ";
		}
		
		$sql = "SELECT COUNT( 1 ) rejectCount
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}";
				// pr($sql);
		if($n_status!=2){
			$data['rejectCount']=0;
			return $data;
		}
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		return $qData;
		
	}
	
	function pendingregistrant(){
	
		$start = intval($this->apps->_g('start'));
		$userid = intval($this->apps->_g('uid'));
		$city = strip_tags($this->apps->_g('areaid')); 
		$brandid = intval($this->apps->_g('brandid'));
		$n_status = $this->apps->_g('n_status');
		
		if($n_status === '') $n_status = 0;
		$qUser = "";
		$qcity = ""; 
		$qBrandid = "";
		$qn_status = "";
		if($userid) $qUser = " 	AND  c.userid={$userid} ";
		if($city) $qcity = " AND  m.area='{$city}' "; 
		if($brandid) $qBrandid = " 	AND  p.brand={$brandid} ";
		else $qBrandid = "AND  p.brand IN (4,5)";
		// if($n_status>-1) $qn_status = " 	AND  e.n_status={$n_status} ";
		if(intval($n_status)===0) $qn_status = " 	AND  c.n_status = 0 ";	
		
		if(intval($n_status)==1) {
			$data['pendingCount']=0;
			return $data;
		}
		if(intval($n_status)==2) {
			$data['pendingCount']=0;
			return $data;
		}
		$qDate = "";
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		if($startdate) $startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
		
			// $y = explode('/',$startdate);
			// $x = explode('/',$enddate);
			
			// $startdate = $x[2].'-'.$x[0].'-'.$x[1];
			// $enddate = $y[2].'-'.$y[0].'-'.$y[1];
			
			$qDate = " AND DATE(e.register_date) >= DATE('{$startdate}') AND DATE(e.register_date) <= DATE('{$enddate}') ";
		}
		
		$sql = "SELECT COUNT( 1 ) pendingCount
				FROM my_circle c
				LEFT JOIN `my_entourage` e  ON e.id = c.friendid
				LEFT JOIN `tbl_user_modist_references` m ON m.userid = c.userid
				LEFT JOIN  `tbl_brand_preferences_references`  r ON r.preferenceid = e.Brand1_ID 
				LEFT JOIN  `my_pages`  p ON p.ownerid = c.userid
				WHERE 1 {$qUser} {$qcity} {$qBrandid} {$qn_status} {$qDate}";
			// pr($sql);
		// if($n_status!=0){
			// $data['pendingCount']=0;
			// return $data;
		// }
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		return $qData;
		
	}
	
}

?>

