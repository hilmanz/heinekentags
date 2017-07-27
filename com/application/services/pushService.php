<?php
class pushService extends ServiceAPI{

	
	function beforeFilter(){
	
		//$this->appsHelper = $this->useHelper('appsHelper'); 
	
		$this->pushserviceHelper  = $this->useHelper('pushserviceHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
		
	}
	function main()
	{
	
	
	}
	 
	
	function pushreport(){
		
			global $CONFIG;
			$result = $this->pushserviceHelper->insert();
			return $result;
	}
	function pullreport(){
		
			global $CONFIG;
			$result = $this->pushserviceHelper->getTemplate();
			return $result;
	}
	/*function checklogintwitters(){

		global $CONFIG;
	

		if(@isset($this->session->getSession('twitter_session','twitter')->login))
		{
			
			
				if(@isset($this->session->getSession('twitter_session','twitter')->token))

				{
				
						$getSession = $this->session->getSession('twitter_session','twitter');
						$result['twitter_id'] = $getSession->twitter_id;
						$result['user_token'] =$getSession->token;
						$result['user_secret'] =$getSession->secret;
						$result['oauth_verifier'] =$getSession->oauth_verifier;
						$result['username'] =$getSession->userProfile->name;
						$result['status'] =1;
						$result['messages'] ='login sucsess';
						
						if($this->_g('pagetype')=='login')
						{
						
						$twitdata =@$this->session->getSession('twitdata_session','twitdata');
						$twit= $this->appsHelper->twitterUpdates(@$twitdata->imagesrealpath);	
						
							sendRedirect("{$CONFIG['BASE_DOMAIN']}service/content/closeme");die;
						}
						return $result;
					

				}

				else
				{
					
					if(!isset($_REQUEST['oauth_token']))
					{
						$urlConnect=$this->twitterHelper->request_login_link();
						$result['urlAuthorize'] =$urlConnect['urlConnect'];
						$result['status'] =2;
						$result['messages'] ='authorize ';
						
						if($this->_g('pagetype')=='login')
						{
							sendRedirect("{$urlConnect['urlConnect']}");die;
						}
						
						return $result;
						
						//sendRedirect("{$urlConnect['urlConnect']}");die;
						//pr($result);die;
						
					}
					
					pr($this->twitterHelper->authorize());die;


				}

			}

		else
			{
				
				$urllogin=$this->twitterHelper->request_login_link();
				$result['urlConnect'] =$urllogin['urlConnect'];
				$result['status'] =0;
				$result['messages'] ='login ';
				
				return $result;

			}

	}
	
	function closeme(){
		$data['login'] = false;
		$this->session->setSession('twitter_session','twitter',$data);
		exit;
	}
 */
}
?>
