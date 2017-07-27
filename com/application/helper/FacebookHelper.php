<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/facebook/facebook.php";
class FacebookHelper {
	var $fb;
	var $user_id;
	var $me;
	var $_access_token;
	var $logger;
	function __construct($apps=null){
		global $logger;
		$this->apps = $apps;
		$this->logger = $logger;
		$this->init();
		
	}
	
	function init(){
		global $FB,$CONFIG,$thisMobile;
	
			$this->fb = new Facebook(array(
			  'appId'  => $FB['appID'],
			  'secret' => $FB['appSecret'],
			));
			//session_destroy();
			$param=$this->apps->_g('parameter');
			$loginReqUri = "http://{$_SERVER['HTTP_HOST']}/bca/public_html/share/fbShare?parameter={$param}"; 
				
			
			
			//pr($this->fb->getUser());
			try{
			
				if($this->fb->getUser()){
					try{
						   
						$this->logger->log('[FACEBOOK] [LOGIN ] : Success login, got logout url ');
						$this->fb->setExtendedAccessToken();
						$data['ac'] = $this->fb->getAccessToken();
						$data['user'] =$this->fb->getUser();
						$data['userProfile']['socimg']= "https://graph.facebook.com/{$this->fb->getUser()}/picture?type=square&return_ssl_resources=1";
						$data['userProfile']= $this->fb->api('/me');
						if(isset($thisMobile))$params['next'] = "{$CONFIG['MOBILE_SITE']}logout.php";
						else $params['next'] = $loginReqUri;
						
						if($this->fb->getUser()){
							$data['urlConnect'] =$this->fb->getLogoutUrl($params);
							
						}else{
							$paramse = array('scope' => 'user_mobile_phone,email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','auth_type'=>'rerequest','redirect_uri'=>"{$loginReqUri}");
							$data['urlConnect'] =$this->fb->getLoginUrl($paramse);
						}
						$userid['id']=$data['user'];
						
						$this->apps->session->setSession('facebook_session','facebook',$data);
						
						//$this->apps->session->setSession($CONFIG['SESSION_NAME'],'WEB',$userid);
					}catch (Exception $e){
					
						$this->logger->log('[FACEBOOK] [LOGIN ] : failed to login , get url login ');
							
						$paramse = array('scope' => 'user_mobile_phone,email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','auth_type'=>'rerequest','redirect_uri'=>"{$loginReqUri}");
					
						$data['urlConnect'] =$this->fb->getLoginUrl($paramse);
						$this->apps->session->setSession('facebook_session','facebook',$data);
						
					}		
					
								
				}else {
					
					$this->logger->log('[FACEBOOK] : get login url ');
					
					$paramse = array('scope' => 'user_mobile_phone,email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','auth_type'=>'rerequest','redirect_uri'=>"{$loginReqUri}");
				
					$data['urlConnect'] =$this->fb->getLoginUrl($paramse);
			
					$this->apps->session->setSession('facebook_session','facebook',$data);
					
				}
				return $data;
			}catch (Exception $e){
			
					$this->logger->log('[FACEBOOK] : get login url , failed authorize ');
					
						$paramse = array('scope' => 'user_mobile_phone,email,user_status,user_activities,publish_actions,user_likes,read_friendlists,user_about_me,user_location,publish_stream,user_relationships,read_stream','auth_type'=>'rerequest','redirect_uri'=>"{$loginReqUri}");
						$data['urlConnect'] =$this->fb->getLoginUrl($paramse);
						$this->apps->session->setSession('facebook_session','facebook',$data);
						return $data;
			}	
	}
	function checkUserLogin(){
		//pr($this->fb->getUser());
		
		if($this->fb->getUser()){
			return true;
		}
		return false;
	}
	function getUser()
	{
		if($this->fb->getUser()){
			$user = $this->fb->getUser();
			return $user;
		}
		return false;
	}
	function getUserDetail($id)
	{
		//echo "https://graph.facebook.com/{$id}/picture?type=square&return_ssl_resources=1";
		$result=$this->fb->api('/'.$id);
		return $result;
	}
	function share($data=''){

			$sql = "SELECT * FROM tbl_result 
				WHERE score={$data['score']}  LIMIT 1";
				
			$qdata = $this->apps->fetch($sql);
		
			if($qdata)
			{
		
				$sessionFacebook = $this->apps->session->getSession('facebook_session','facebook');
					if($data['score']==1)	
					{
						$realimagespath = "http://preview.kanadigital.com/bca/public_html/public_assets/Result_Fashionista_Low.jpg";
					}
					elseif ($data['score']==2)
					{
						$realimagespath = "http://preview.kanadigital.com/bca/public_html/public_assets/resultfoodLover.jpg";
					}	
					elseif($data['score']==3)
					{
						$realimagespath = "http://preview.kanadigital.com/bca/public_html/public_assets/Result_Traveller_Low.jpg";
					}
				$params = array(
					
					  "access_token" =>  $sessionFacebook->ac,// see:   "message" => $message,
					 
					  "picture" => $realimagespath,
					  
					);
		 
				try {
					  $ret = $this->fb->api('/'.$sessionFacebook->user.'/feed', 'POST', $params);
					  $sql = " INSERT INTO tbl_share 
											(userid,score,date,media,n_status) 
											VALUES ('{$data['iduser']}','{$data['score']}',NOW(),'facebook','1')";
						$query = $this->apps->query($sql);
						
						$sql = " INSERT INTO my_score 
										(id_user,score,date,n_status) 
										VALUES ('{$data['iduser']}','{$data['score']}',NOW(),'1')";
						// pr($sql);
						$query = $this->apps->query($sql);
					  
					  
					  session_destroy();
						$this->destroy();
						$result['status']=1;
						$result['messages']='sucsess';
						return $result;
					} catch(Exception $e) {
						 session_destroy();
						$this->destroy();
						$result['status']=3;
						$result['messages']=$e->getMessage();
						return $result;
					
					}
			}
			else
			{
				 session_destroy();
				$this->destroy();
			}
	}
	function destroy()
	{
				global $FB;
		$this->fb = new Facebook(array(
			  'appId'  => $FB['appID'],
			  'secret' => $FB['appSecret'],
			));
		$this->fb->destroySession();
		
		setcookie("fbm_1501595333431183",'');
		
	}
	function getUserLike()
	{
			$sql = "SELECT id,userid FROM user_flavors ";
			$qData = $this->apps->fetch($sql,1);
			
			foreach (	$qData as $datasql)
			{
				$url = 'http://www.whoisyoggy.com/gallery/detail/'.$datasql['userid'].'/'.$datasql['id'];
			
				 

				 $params = 'select comment_count, share_count, like_count,comments_fbid from link_stat where url = "'.$url.'"';
				 $component = urlencode( $params );
					
				 $url = 'http://graph.facebook.com/fql?q='.$component;
				
				 $fbLIkeAndSahre = json_decode(file_get_contents($url)); 
				
				if($fbLIkeAndSahre->data['0']->comments_fbid)
				{
					 $datacount = $this->fb->api('/'.$fbLIkeAndSahre->data['0']->comments_fbid.'/likes');
					
					 foreach ($datacount['data'] as $userdata)
					 {
						$user = $this->fb->api('/'.$userdata['id']);
						$sql ="SELECT count(*) as total,id FROM social_member where   fbid ='{$user['id']}' LIMIT 1 ";
						 
						$qData = $this->apps->fetch($sql);
						$userid['id']=$qData['id'];
						if($qData['total'] == 0 ) 
							{
								//pr($user );
								$sql = " INSERT INTO social_member 
									(fbid,username,nickname,name,last_name,email,city,sex,last_login,login_count,n_status) 
									VALUES ('{$user['id']}','{$user['first_name']}','{$user['first_name']}','{$user['first_name']}','{$user['last_name']}','','','{$user['gender']}',NOW(),'1','1')";
								
								$query = $this->apps->query($sql);
								$userid['id']=$this->apps->getLastInsertId();
							}
							
						if(	$userid['id'])
						{
								$sql ="SELECT userid FROM user_likes where   userid ='{$userid['id']}' and usr_flavor_id ='{$datasql['id']}'LIMIT 1 ";
								$qData = $this->apps->fetch($sql);
								   
								if(!$qData)
								{
									$sql = " INSERT INTO user_likes 
										(usr_flavor_id,userid,voted_date,n_status) 
										VALUES ('{$datasql['id']}','{$userid['id']}',NOW(),'1')";
									
									$query = $this->apps->query($sql);
								}
						}
					 }
				 }
			}
			
			 //return $getFbStatus->like_count;
	}  
	function getLikecount()
	{
		
		$sql = "SELECT id,userid FROM user_flavors "; 
			$qData = $this->apps->fetch($sql,1);
			 pr($qData);
			foreach ($qData as $datasql)
			{
				
				$url = 'http://www.whoisyoggy.com/gallery/detail/'.$datasql['userid'].'/'.$datasql['id'];
			
				pr($datasql['id']);

				 $params = 'select comment_count, share_count, like_count,comments_fbid from link_stat where url = "'.$url.'"';
				 $component = urlencode( $params );
					
				 $url = 'http://graph.facebook.com/fql?q='.$component;
		
				 $fbLIkeAndSahre = json_decode(file_get_contents($url)); 
				if( $fbLIkeAndSahre)
				{
					$likefb = $fbLIkeAndSahre->data['0']->like_count + $fbLIkeAndSahre->data['0']->share_count + $fbLIkeAndSahre->data['0']->comment_count;
					$sql = " UPDATE user_flavors  SET like_fb='{$likefb}' where   id ='{$datasql['id']}'";
			
					$query = $this->apps->query($sql);
					//$datacount = $this->fb->api('/'.$fbLIkeAndSahre->data['0']->comments_fbid.'/likes?limit=500');
				}
			}
		return true;
	}
	function tokken()
	{
	
		$access_token = $this->fb->getAccessToken();
		return $access_token; 
	}
	function tokkenhashtags()
	{
		 $params = "SELECT action_links, actor_id, app_data, app_id, attachment FROM stream WHERE (source_id=me()  ) AND strpos(lower(message),lower('#evanna')) >=0";
				 $component = urlencode( $params );
					
				 $url = 'http://graph.facebook.com/fql?q='.$component;
		 $fbLIkeAndSahre = json_decode(file_get_contents($url)); 
		return $fbLIkeAndSahre;
	}
}
?>