<?php

class soursallyApiMedia extends ServiceAPI{

	

	
	function beforeFilter(){
	
		$this->appsHelper = $this->useHelper('appsHelper'); 
		$this->uploadHelper = $this->useHelper('uploadHelper'); 
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->twitterHelper  = $this->useHelper('twitterHelper');
		$this->FacebookHelper  = $this->useHelper('FacebookHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
	}
	function loginFb()
	{
		$urlConect=$this->FacebookHelper->init();
		$result['status'] = 0;
		$result['messages'] = 'login facebook';
		$result['urlConnect'] = $urlConect['urlConnect'];
				
		return $result;
	}	
	function setfbLogin()
	{
		global $CONFIG;
		$data['userProfile']['id'] =$this->_p('idFb'); 
		$data['userProfile']['name'] =$this->_p('name'); 
		$data['userProfile']['gender'] =$this->_p('gender'); 
		$data['userProfile']['email'] =$this->_p('email'); 
		$data['userProfile']['username'] =$this->_p('username'); 
		$data['userProfile']['location']['name'] =$this->_p('location'); 
		$data['userProfile']['last_name'] =$this->_p('lastName'); 
		$result['status'] = 0;
		$result['msg'] = 'problem session user';
		$this->session->setSession('facebook_session','facebook',$data);
		$result = $this->appsHelper->syncFacebook();
		
		print json_encode($result);exit;
		
	}	
	function checkFbweb()

	{ 
		global $CONFIG;
		$session="";
		
		  
			
			
				$urlConect=$this->FacebookHelper->init();
				$result['status'] = 0;
				$result['messages'] = 'login facebook';
				$result['urlConnect'] = $urlConect['urlConnect'];
				
				print json_encode($result);exit; 
		
	}
	function checkFbs()
	{ 
		
		echo "<script> window.close(); </script>";
	}
	function checkFb()

	{ 
		global $CONFIG;
		$session="";
		
		  
			
			if($this->FacebookHelper->checkUserLogin()==true)

			{
					$sessionFacebook = $this->session->getSession('facebook_session','facebook');
					$user = $this->session->getSession($CONFIG['SESSION_NAME'],'WEB');
					$result['AccessToken'] = $sessionFacebook->ac;
					$result['fb_id'] = $sessionFacebook->user;
					$result['username'] = $sessionFacebook->userProfile->name;
					$result['gender'] = $sessionFacebook->userProfile->gender;
					$result['email'] = $sessionFacebook->userProfile->email;
					$result['urlLogout'] = $sessionFacebook->urlConnect;
					$result['status'] = 1;
					$result['userid'] = $user->id;
					$result['messages'] = 'sucsess login';
					$result['ll']=$this->appsHelper->syncFacebook();
					$data['login'] = false;
					// unset($_SESSION['facebook_session']); 
					// unset($_SESSION['fb_693478684043323_code']);
					// unset($_SESSION['fb_693478684043323_access_token']);
					// unset($_SESSION['fb_693478684043323_user_id']);
					$this->FacebookHelper->destroy();
					//pr($_SESSION);
					//session_destroy();
					return $result;
					echo "<script> window.close(); </script>";
				
					
				
				

			}

			else

			{

				
				
			
				$urlConect=$this->FacebookHelper->init();
				$result['status'] = 0;
				$result['messages'] = 'login facebook';
				$result['urlConnect'] = $urlConect['urlConnect'];
				
				print json_encode($result);exit; 
			}

	}
	function twitterShare()
	{
		$this->session->setSession('twitdata_session','twitdata',$twitdata); 
		$checkTwitter = $this->checklogintwitters();
									
		
	}
	function fbShare()
	{ 
		global $CONFIG;
		if($this->FacebookHelper->checkUserLogin()==true)
		{
				
				$sessionFacebook = $this->session->getSession('facebook_session','facebook');
				$data = $this->appsHelper->getimagesShare($sessionFacebook->user);

			if($data)
					{	
						$result =$this->FacebookHelper->share($data);
						
						return $result;
						
					}

				else

					{

						$result['status']=0;
						$result['message']='no data recieved ';
							
						return $result;

					}
		}
		else
		{
			$this->checkFb();
		}

	}
	function share($typeMedia='facebook')
	{
			global $CONFIG;
			
			$checkFacebook = $this->checkFb();
			if($checkFacebook ['status']==0)
			{
				$result = $checkFacebook;
				return $result;
			}
			$sessionFacebook = $this->session->getSession('facebook_session','facebook');
			$data = $this->appsHelper->getimagesShare($sessionFacebook->user);

			if($data)
			{
				//$typeMedia = strip_tags($this->_p('typeMedia'));
				if($typeMedia=='twitter')
				{
					if(@isset($this->session->getSession('twitter_session','twitter')->token))
						{
									
							$twit= $this->appsHelper->twitterUpdates($data);	
						
							sendRedirect("{$CONFIG['BASE_DOMAIN']}service/soursallyApiMedia/closeme");die;
							
						}
					else 
						{
							$checkTwitter = $this->checklogintwitters();
							if($checkTwitter['status'] != 1)
								{
									return array('result'=>false,'data'=>array(),'mediapost'=>$checkTwitter);
								}
						}
				}
				elseif($typeMedia=='facebook')
				{
					$result =$this->FacebookHelper->share($data);
					
					sendRedirect("{$CONFIG['BASE_DOMAIN']}service/soursallyApiMedia/closeme");die;
				}
				else
				{
					return array('result'=>false,'data'=>array(),'mediapost'=>false);
				}
			}
			
			return array('result'=>false,'data'=>$data,'mediapost'=>false);
			
	}
	function checklogintwitters(){

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
						$result['statush'] =1;
						$result['messages'] ='login sucsess';
						$twitdata =@$this->session->getSession('twitdata_session','twitdata');
						$imagesrealpath='';
						if($twitdata)
						{
							$imagesrealpath=@$twitdata->imagesrealpath;
						}
						elseif($this->_p('image'))
						{
							$imagesrealpath= $this->_p('image');
						}
						
						//$twit= $this->appsHelper->twitterUpdates($imagesrealpath);
						$twit= $this->twitterHelper->update_tweet();
						$data['login'] = false;
					
						$this->session->setSession('twitter_session','twitter',$data);
						echo "<script> window.close(); </script>";
						// if($this->_g('pagetype')=='login')
						// {
						
						// $twitdata =@$this->session->getSession('twitdata_session','twitdata');
						// $twit= $this->appsHelper->twitterUpdates(@$twitdata->imagesrealpath);	
						
							// sendRedirect("{$CONFIG['BASE_DOMAIN']}service/content/closeme");die;
						// }
						return $result;
					

				}

				else
				{
					
					if(!isset($_REQUEST['oauth_token']))
					{
						$urlConnect=$this->twitterHelper->request_login_link();
						$result['urlConnect'] =$urlConnect['urlConnect'];
						$result['status'] =2;
						$result['messages'] ='authorize ';
						
						$checkPage = @explode("?",$_SERVER['REQUEST_URI']);
						if(isset($checkPage[1]))
						{
							$Page = explode('=',$checkPage[1]);
							if(isset($Page[1]))
							{
								if($Page[1]=='login' || $Page[1]=='twitter')
								{
									sendRedirect("{$urlConnect['urlConnect']}");die;
								}
							}
						
						}
						if($this->_g('pagetype')=='login')
						{
							sendRedirect("{$urlConnect['urlConnect']}");die;
						}
						if($this->_g('page')=='login')
						{
							sendRedirect("{$urlConnect['urlConnect']}");die;
						}
						print json_encode($result);exit; 
						
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
				$result['coba'] =$this->_p('image');
				$result['messages'] ='login ';
				
				print json_encode($result);exit; 

			}

	}
	function likeFacebook(){
	
		//if($this->FacebookHelper->checkUserLogin()==true)
		if($this->session->getSession('facebook_session','facebook'))
		{
				
				$usr_flavor_id = $this->_p('href');
		
				if($usr_flavor_id)
					{
						$result= $this->appsHelper->likeUpdates();	
						print json_encode($result);exit;  
					}
		
		}
	
		$result['status'] = 0;
		$result['msg'] = 'no user flavor';
		print json_encode($result);exit;  
	}
	function whoislike(){
		ini_set('max_execution_time', 400);
		pr($this->FacebookHelper->getUserLike());exit;
	}
	function getCountLike(){
		
		ini_set('max_execution_time', 400);
		pr($this->FacebookHelper->getLikecount());exit;
	}
	function closeme(){
		$data['login'] = false;
		$this->session->setSession('twitter_session','twitter',$data);
		$this->session->setSession('facebook_session','facebooks',$data);
		exit;
	}

}

?>