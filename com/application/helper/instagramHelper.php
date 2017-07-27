<?php 

class instagramHelper {

	function __construct($apps){

		global $logger;

		$this->logger = $logger;

		$this->apps = $apps;

		if(is_object($this->apps->user)) {

				$uid = intval($this->apps->_request('uid'));

				if($uid==0) $this->uid = intval($this->apps->user->id);

				else $this->uid = $uid;

		}

		$this->dbshema = "istagram";	

		$this->topclass = array(100,4,6);

	}
	
	function inserttoTable($data){
	$posts=$this->_g('hastags');
		if($posts=='')
			{
				$posts='bunyu';
			}
			$url = 'https://api.instagram.com/v1/tags/'.$posts.'/media/recent?client_id=cd07523ce9d14c8da1eb3ffe46411e61';
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => 2
			));

			$result = curl_exec($ch);
			curl_close($ch);
			$results = json_decode($result, true);
			

			foreach ($results['data'] as $row)
			{
				/*//print_r($key);
				$urluser = 'https://api.instagram.com/v1/users/'.$row['user']['id'].'?client_id=cd07523ce9d14c8da1eb3ffe46411e61#';
				$chuser = curl_init();
					curl_setopt_array($chuser, array(
					CURLOPT_URL => $urluser,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => 2
				));

				$resultuser = curl_exec($chuser);
				curl_close($chuser);
				$resultsuser = json_decode($resultuser, true);
				//print_r($resultsuser['data']['counts']);
				$results['data'][$key]['user']['follows']=$resultsuser['data']['counts']['followed_by'];
				*/
				$sql = "SELECT * FROM
				FROM table_user
				WHERE link='' ORDER BY id DESC LIMIT 1";
				$qData = $this->apps->fetch($sql);
				if($qData)
				{
					$idIstagram = $row['user']['id'];
					$username = $row['user']['username'];
					$profile_picture = $row['user']['profile_picture'];
					$full_name = $row['user']['full_name'];
					$comment =$row['comments']['count'];
					$likes =$row['likes']['count'];
					$link = $row['link'];
					$n_status=0;
					
					
					
					
					$sql = "INSERT INTO
							table_user 
								(
									`id_istagram`,
									`username`, 
									`profile_picture`,
									`fullname`,
									`posts`,
									`follow`,
									`images`,
									`comment`,
									`like`,
									`link`,
									`n_status`
								)
					VALUES 		
								(
									{$idIstagram},
									'{$username}',
									'{$profile_picture}',
									'{$img['basic']}',
									'{$img['share']}',
									NOW(),
									0
								) ";
		
					$res = $this->apps->query($sql);
				}
			}
			
	}
	function insertInstagram($data){
		//pr($data);die;
		
			foreach ($data['data'] as $row)
			{
				
				$sql = "SELECT * 
				FROM tabel_user
				WHERE id_istagram='{$row['id']}' ";
				$qData = $this->apps->fetch($sql);
				
				if($qData=='')
				{
					$idUser = $row['user']['id'];
					$idIstagram = $row['id'];
					$username = $row['user']['username'];
					$profile_picture = $row['user']['profile_picture'];
					$full_name = $row['user']['full_name'];
					
					$images = $row['images']['standard_resolution']['url'];
					$imagesThumb = $row['images']['low_resolution']['url'];
					$n_status=0;
					
					
					
					
					$sql = "INSERT INTO
							tabel_user 
								(	`id_user`,
									`id_istagram`,
									`username`, 
									`profile_picture`,
									`fullname`,
									`images`,
									`images_thumb`,
									`date`,
									`n_status`
								)
					VALUES 		
								(	'{$idUser}',
									'{$idIstagram}',
									'{$username}',
									'{$profile_picture}',
									'{$full_name}',
									'{$images}',
									'{$imagesThumb}',
									NOW(),
									0
								) ";
				//pr($sql);
				$res = $this->apps->query($sql);
				}
			}
			
	}
	function galleryfinalist(){
		$sql = "SELECT uf.*, sm.fbId, sm.name AS fb_name
				FROM user_flavors uf
				LEFT JOIN social_member sm
				ON sm.id = uf.userid
				WHERE uf.n_status=2
				ORDER BY uf.id DESC LIMIT 10";
		$qData = $this->apps->fetch($sql,1);

		if(isset($this->uid)){
			$sql = "SELECT usr_flavor_id FROM user_votes 
					WHERE userid = {$this->uid}";
			$rs = $this->apps->fetch($sql,1);
			$fid = array();
			
			if($rs){
				foreach ($rs as $key => $value) {
					$fid[]=$value['usr_flavor_id'];
				}
				foreach ($qData as $key => $value) {
					if(in_array($value['id'], $fid)) $qData[$key]['voted'] = 1;
				}
			}
		}

		
		if($qData) return $qData;
		return false;
	
	}
	
}

?>



