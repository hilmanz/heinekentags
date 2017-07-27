<?php
/**
 * @author duf
 *
 */
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Twitter/tmhOAuth.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhUtilities.php";

class twitterHelper {

	var $tmhOAuth;
	var $oauth;
		
	function __construct($apps=null){
		$this->apps = $apps;
		
	}
	
	function init(){
		global $TWITTER;
		
			$this->tmhOAuth = new tmhOAuth(array(
							  'consumer_key'    => 'ugleFy15YeyiG5w8Fvkqx1cmz',
							  'consumer_secret' =>  'j2EJJgAftrh9rPRFwEmWrUKRR8AmZZkttB0w6iqZfeEtZWviJe'
							));
	
	}
	
	function authorize(){
		global $CONFIG,$thisMobile;
		$flavourid = intval($this->apps->_g('flavourid'));
		$mdl = strip_tags($this->apps->_g('mdl'));
		$func = strip_tags($this->apps->_g('func'));
		$userid = intval($this->apps->_g('userid'));
		// if(@strip_tags($this->apps->_g('oauth_verifier'))){
		
			// if(@isset($this->apps->session->getSession('twitter_session','twitter_permission')->loginPermission)){
				$this->init();
				
				$request_code = unserialize(urldecode64(@$this->apps->session->getSession('twitter_session','twitter')->c));
				
				$this->tmhOAuth->config['user_token']  = strip_tags($_REQUEST['oauth_token']);
				$this->tmhOAuth->config['user_secret'] = $request_code['oauth_token_secret'];
			
				$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), 
												array(
												'oauth_verifier' => strip_tags($_REQUEST['oauth_verifier'])
												)
				);
		
				if ($code == 200) {
					$access_token = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
					
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
							if(isset($thisMobile))	sendRedirect($CONFIG['MOBILE_SITE']."{$mdl}/{$func}&loginType=twitter");
							else sendRedirect($CONFIG['BASE_DOMAIN']."{$mdl}/{$func}&loginType=twitter");
							//exit;
						
					}else{
						
							if(isset($thisMobile))	sendRedirect($CONFIG['MOBILE_SITE']."{$mdl}/{$func}&loginType=twitter");
							else sendRedirect($CONFIG['BASE_DOMAIN']."{$mdl}/{$func}&loginType=twitter");
							//exit;
						
					}
				}			
			
			
			// }
		// }
		  
		// if($this->apps->_g("loginType")=="twitter"){
			
			// $permission['loginPermission'] = false;
			// $this->apps->session->setSession('twitter_session','twitter_permission',$permission);
			// if(isset($thisMobile))	sendRedirect($CONFIG['MOBILE_SITE']."{$mdl}/{$func}&loginType=twitter");
							// else sendRedirect($CONFIG['BASE_DOMAIN']."{$mdl}/{$func}&loginType=twitter");
							exit;
			
		// }
		
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
						//exit;
					}else{
						if(isset($thisMobile))	header("location:{$CONFIG['MOBILE_SITE']}testApi/twitters??page=login");
						else header("location:{$CONFIG['BASE_DOMAIN']}testApi/twitters?page=login");
						//exit;
					}
				}			
			
			
			
		
		
		
		
		return false;
	}
	function request_login_link($flavourid=null,$module=null){
		global $TWITTER,$thisMobile,$logger;
		$this->init();
		//$logger->info("thisMobile->{$thisMobile}");
		
		$modl=explode('/',$module);
		$userid = intval($this->apps->_g('userid'));
		if(isset($thisMobile))$callbackurl = "http://{$_SERVER['HTTP_HOST']}/bca/public_html/{$module}&loginType=twitter&mdl={$modl['0']}&func={$modl['1']}";
		else $callbackurl = "http://{$_SERVER['HTTP_HOST']}/bca/public_html/{$module}&loginType=twitter&mdl={$modl['0']}&func={$modl['1']}";
   		$callback = isset($_REQUEST['oob']) ? 'oob' : $callbackurl;
		
   		$params = array(
    		'oauth_callback'=> $callback
   			// 'x_auth_access_type'=>'write'
  		);
		
		$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/request_token', ''), $params);
		
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
	
	function update_tweet($data=''){ 

		global $CONFIG;

		$this->init();

		$getSession = $this->apps->session->getSession('twitter_session','twitter');

		

		$this->tmhOAuth->config['user_token']  = $getSession->token;;

		$this->tmhOAuth->config['user_secret'] = $getSession->secret;

		$this->tmhOAuth->config['oauth_verifier'] = $getSession->oauth_verifier;
		$flavourid = $this->apps->_g('flavorid');
		$sql = "SELECT * FROM tbl_result 
				
				WHERE score={$data['score']}  LIMIT 1";
		$qdata = $this->apps->fetch($sql);
		
		if($qdata)
		{

		
			$desc= "test";
	 
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
			$image=file_get_contents(''.$realimagespath.'');

			//$params = array( 'status'=>'','media[]'  => $image,'lat'=>'-6.258570','long'=>'106.855840','display_coordinates'=>true);
			$params = array( 'status'=>'','media[]'  => $image);
			$updateStatus = $this->tmhOAuth->request('POST',$this->tmhOAuth->url("1.1/statuses/update_with_media"), $params,true,true);

			$response =  $this->tmhOAuth->extract_params($this->tmhOAuth->response["response"]);

			if($updateStatus == 200){

				$result['status']=1;

				$result['messages']='sucsess';  
				 $sql = " INSERT INTO tbl_share 
										(userid,score,date,media,n_status) 
										VALUES ('{$data['iduser']}','{$data['score']}',NOW(),'twitter','1')";
						$query = $this->apps->query($sql);
					
						$sql = " INSERT INTO my_score 
										(id_user,score,date,n_status) 
										VALUES ('{$data['iduser']}','{$data['score']}',NOW(),'1')";
						// pr($sql);
						$query = $this->apps->query($sql);
				return $result;

				

			}else{

				$result['status']=3;

				$result['messages']='Updating twitter status is failed, please try again';

				return $result;

				

			}
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
	function getHastags(){
		
		$this->init();
		$request_code = $this->apps->session->getSession('twitter_session','twitter');
		
		//$this->tmhOAuth->config['user_token']  = $request_code->token; 
		//$this->tmhOAuth->config['user_secret'] = $request_code->secret;
		$posts=$this->apps->_request('hastags');
			if($posts=='')
			{
				$posts=urlencode('#TakeMeToDreamIsland');
			}
			else
			{
				$posts=urlencode('#'.$posts);
			
			}
		 $params = array('q' => $posts,'include_entities'=>true);
		// pr($params);
		$status = $this->tmhOAuth->request('GET', $this->tmhOAuth->url("1.1/search/tweets"),$params);
		// pr($this->tmhOAuth);exit;
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