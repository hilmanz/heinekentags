<?php
/**
 * @author duf
 *
 */
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Twitter/tmhOAuth.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhUtilities.php";

class twitterHelpernew {

	var $tmhOAuth;
	var $oauth;
		
	function __construct($apps=null){
		$this->apps = $apps;
		
	}
	
	function init(){
		global $TWITTER;
		// pr('1');
		// pr($this->apps->session->getSession('twitter_session','twitter'));
			$this->tmhOAuth = new tmhOAuth(array(
							  'consumer_key'    => '4Ku3yo2fV75rCIlCUuoS6hTTx',
							  'consumer_secret' =>  'ka485I9ZZFE0qOV4HCQdOZ81UKINTAlewYsJ8yfBMrqLi2PpSV'
							));
		// pr($this->tmhOAuth);exit;
	}
	
	function authorize(){
		global $CONFIG,$thisMobile;
		
		if(@strip_tags($this->apps->_g('oauth_verifier'))){
		
			if(@isset($this->apps->session->getSession('twitter_session','twitter_permission')->loginPermission)){
				$this->init();
				
				$request_code = unserialize(urldecode64(@$this->apps->session->getSession('twitter_session','twitter')->c));
				//$this->tmhOAuth->config['user_token']  = $request_code['oauth_token'];
				//$this->tmhOAuth->config['user_secret'] = $request_code['oauth_token_secret'];
				$this->tmhOAuth->config['user_token']  = strip_tags($_REQUEST['oauth_token']);
				$this->tmhOAuth->config['user_secret'] = $request_code['oauth_token_secret'];
			
				$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), 
												array(
												'oauth_verifier' => strip_tags($_REQUEST['oauth_verifier'])
												)
				);
		
				if ($code == 200) {
					$access_token = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
					//pr($access_token);die;
					//get user detail
					
					
					$this->tmhOAuth->config['user_token']  = $access_token['oauth_token'];
					$this->tmhOAuth->config['user_secret'] = $access_token['oauth_token_secret'];
					$paramsGetUser = array('screen_name' => $access_token['screen_name'],'include_entities'=>true);
		
					$requestGetUser = $this->tmhOAuth->request('GET', $this->tmhOAuth->url("1.1/users/show"), $paramsGetUser);
					
					$GetUsers = json_decode($this->tmhOAuth->response['response'],true);
					
					$data['twitter_id'] = $access_token['user_id'];
					$data['oauth_verifier'] = $_REQUEST['oauth_verifier'];
					$data['token']= $access_token['oauth_token'];
					$data['secret'] = $access_token['oauth_token_secret'];
					$data['user'] = $access_token['screen_name'];
						$userprofile['name'] =  $GetUsers['name'];
						$userprofile['gender'] =  ""; //ga ketemu
						$userprofile['email'] =  ""; //ga ketemu
					$userprofile['socimg']= $GetUsers['profile_background_image_url'];
				
					$data['userProfile'] = $userprofile;
					$data['login'] = true;
					
					$this->apps->session->setSession('twitter_session','twitter',$data);
					$permission['loginPermission'] = false;
					$this->apps->session->setSession('twitter_session','twitter_permission',$permission);
					if(!$this->apps->session->get('user')){
						if(isset($thisMobile))	sendRedirect("{$CONFIG['MOBILE_SITE']}service/soursallyApiMedia/checklogintwitters?page=login");
						else sendRedirect("{$CONFIG['BASE_DOMAIN']}service/soursallyApiMedia/checklogintwitters?page=login");
						exit;
					}else{
						if(isset($thisMobile))	sendRedirect("{$CONFIG['MOBILE_SITE']}service/soursallyApiMedia/checklogintwitters?page=login");
						else sendRedirect("{$CONFIG['BASE_DOMAIN']}service/soursallyApiMedia/checklogintwitters?page=login");
						exit;
					}
				}			
			
			
			}
		}
		
		if($this->apps->_g("loginType")=="twitter"){
		
			$permission['loginPermission'] = false;
			$this->apps->session->setSession('twitter_session','twitter_permission',$permission);
			if(isset($thisMobile))	sendRedirect("{$CONFIG['MOBILE_SITE']}service/soursallyApiMedia/checklogintwitters?page=login");
			else sendRedirect("{$CONFIG['BASE_DOMAIN']}service/soursallyApiMedia/checklogintwitters?page=login");
			exit;
		}
		
		return false;
	}
	function authorize2(){
		global $CONFIG,$thisMobile;
		
		
		
			
				$this->init();
				
				$request_code = unserialize(urldecode64(@$this->apps->session->getSession('twitter_session','twitter')->c));
				
				$this->tmhOAuth->config['user_token']  = strip_tags($_REQUEST['oauth_token']);
				$this->tmhOAuth->config['user_secret'] = $request_code['oauth_token_secret'];
			
				$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), 
												array(
												'oauth_verifier' => strip_tags($_REQUEST['oauth_verifier'])
												)
				);
			//pr($code);
				if ($code == 200) {
					$access_token = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
					$data['twitter_id'] = $access_token['screen_name'];
					$data['token']= $access_token['oauth_token'];
					$data['secret'] = $access_token['oauth_token_secret'];
					$data['user'] = $access_token['screen_name'];
						$userprofile['name'] =  $access_token['screen_name'];
						$userprofile['gender'] =  $access_token['screen_name']; //ga ketemu
						$userprofile['email'] =  $access_token['screen_name']; //ga ketemu
					$userprofile['socimg']= "https://api.twitter.com/1/users/profile_image/{$access_token['screen_name']}";
					$data['userProfile'] = $userprofile;
					$data['login'] = true;
					$this->apps->session->setSession('twitter_session','twitter',$data);
					$permission['loginPermission'] = false;
					$this->apps->session->setSession('twitter_session','twitter_permission',$permission);
					if(!$this->apps->session->get('user')){
						if(isset($thisMobile))	header("location:{$CONFIG['MOBILE_SITE']}testApi/twitters??page=login");
						else header("location:{$CONFIG['BASE_DOMAIN']}?page=login");
						exit;
					}else{
						if(isset($thisMobile))	header("location:{$CONFIG['MOBILE_SITE']}testApi/twitters??page=login");
						else header("location:{$CONFIG['BASE_DOMAIN']}testApi/twitters?page=login");
						exit;
					}
				}			
			
			
			
		
		
		
		
		return false;
	}
	function request_login_link(){
		global $TWITTER,$thisMobile,$logger,$CONFIG; 
		$this->init();
		//$logger->info("thisMobile->{$thisMobile}");
		if(isset($thisMobile))$callbackurl = $TWITTER['LOGIN_CALLBACK_MOBILE'];
		else $callbackurl =  $CONFIG['BASE_DOMAIN'].'service/soursallyApiMedia/checklogintwitters?loginType=twitter';;
   		$callback = isset($_REQUEST['oob']) ? 'oob' : $callbackurl;
   		$params = array(
    		'oauth_callback'=> $callback
   			// 'x_auth_access_type'=>'write'
  		);
		//$logger->info(json_encode($params));
		$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/request_token', ''), $params);
				//pr($this->tmhOAuth);
		$logger->info("code : {$code}");
		
	  	if ($code == 200) {
		  //berhasil dapet access token
	    	$oauth = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
			$data['c'] = urlencode64(serialize($oauth));
	    	
		   	$method = 'authorize';
	    	$force  = '';
	    	$authurl = $this->tmhOAuth->url("oauth/{$method}", '') .  "?oauth_token={$oauth['oauth_token']}{$force}";
	    	$data['urlConnect'] = $authurl;
			$data['login'] = false;
			$this->apps->session->setSession('twitter_session','twitter',$data);
			$logger->info(json_encode($data));
			return $data;
		
	  	} else {
	    	return false;
	  	}
	}

	function remove_tweet(){
	
		if($twitter['token']!=null && $twitter['secret']!=null){
			$this->tmhOAuth->config['user_token']  = $twitter['token'];
			$this->tmhOAuth->config['user_secret'] = $twitter['secret'];
			$id = $this->apps->Request->getParam("id");
			if(strlen($id)>8){
				$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url("1/statuses/destroy/{$id}"));
				if($code==200){
					//flag deleted
					//$this->flag_deleted_tweet($this->user_id,$twitter['twitter_id'],$id);
					return false; //the tweet has been deleted successfully'
				}else{
					return false ;//tweet not found
				}
			}else{
				return  false; //Cannot remove tweet. u need to specify the tweet id
			}
		}
		return  false; //unauthorized access
	}
	
	function update_tweet(){
		global $CONFIG;
		$this->init();
		
		
		// if($this->tmhOAuth->config['consumer_key']!=$this->apps->_p('consumer_key') || $this->tmhOAuth->config['consumer_secret']!=$this->apps->_p('consumer_secret'))
		// {
			// $result['status']=2;
			// $result['messages']='config not falid';
			// return $result;
		// }
		// echo"dd";
		$getSession = $this->apps->session->getSession('twitter_session','twitter');
		
		$this->tmhOAuth->config['user_token']  = $getSession->token;;
		$this->tmhOAuth->config['user_secret'] = $getSession->secret;
		$this->tmhOAuth->config['oauth_verifier'] = $getSession->oauth_verifier;
		
		$twitdata =@$this->apps->session->getSession($CONFIG['SESSION_NAME'],'twitterShare');
		if($twitdata)
		{
			$link= $twitdata->link;
			$desc= $twitdata->desc;
			
			$hastag= $twitdata->hastag;
			$realimagespath = $twitdata->image;
		}
		else
		{
			$desc="Orange-lover detected! The person who made this gotta be the one that spreads the love around!";
			$realimagespath = "http://preview.kanadigital.com/soursally-2014/public_html/public_assets/yoggy/share_5387ed9d2fd15.jpg";
		}
		$image=file_get_contents(''.$realimagespath.'');
		$params = array( 'status'=>$desc,'media[]'  => $image);
		$updateStatus = $this->tmhOAuth->request('POST',$this->tmhOAuth->url("1.1/statuses/update_with_media"), $params,true,true);
		$response =  $this->tmhOAuth->extract_params($this->tmhOAuth->response["response"]);
		
		if($updateStatus == 200){
			$result['status']=1;
			$result['messages']='sucsess';
			return $result;
			
		}else{
			$result['status']=3;
			$result['messages']='Updating twitter status is failed, please try again';
			return $result;
			
		}
		
	}
	
	function getUserLogin(){
		return @$this->apps->session->getSession('twitter_session','twitter')->login;
	}
	function getUserTimeline($since_id=0,$count=5){
		
		$this->init();
		$request_code = $this->apps->session->getSession('twitter_session','twitter');
		
		$this->tmhOAuth->config['user_token']  = $request_code->token;
		$this->tmhOAuth->config['user_secret'] = $request_code->secret;
		
		
		$params = array('screen_name' => $request_code->userProfile->name,'include_entities'=>true);
		
		$status = $this->tmhOAuth->request('GET', $this->tmhOAuth->url("1.1/users/show"), $params);
		
		if($status == 200){
			$rs = json_decode($this->tmhOAuth->response['response'],true);
			return $rs;
		}else{
			return array();
		}
	}
	function getHomeTimeline(){
		
		$this->init();
		$request_code = $this->apps->session->getSession('twitter_session','twitter');
		
		$this->tmhOAuth->config['user_token']  = $request_code->token;
		$this->tmhOAuth->config['user_secret'] = $request_code->secret;
		
		// $params = array('screen_name' => $request_code->twitter_id,"since_id"=>$since_id,"count"=>$count);
		
		$status = $this->tmhOAuth->request('GET', $this->tmhOAuth->url("1.1/statuses/home_timeline"));
		
		if($status == 200){
			$rs = json_decode($this->tmhOAuth->response['response'],true);
			// pr($rs);exit;
			return $rs;
		}else{
			return array();
		}
	}
}
?>