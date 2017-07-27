<?php
class content extends ServiceAPI{

	
	function beforeFilter(){
	
		$this->appsHelper = $this->useHelper('appsHelper'); 
		$this->uploadHelper = $this->useHelper('uploadHelper'); 
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->twitterHelper  = $this->useHelper('twitterHelper');
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
	}
 
	 
	
	function uploads(){
	// echo"sss";die;
			global $CONFIG;
			$type = strip_tags($this->_p('type'));
			$twit['sendtwitterpost'] = false; 
			$twit['message'] = "failed"; 
			//check twitter  login
		
			//pr($checkTwitter);
			if($type=='images'){
				//check Addon
				$checkAddon = $this->appsHelper->checkAddon();

				$checkimages = $this->appsHelper->checkdataimages($_FILES['images']['name']);
					$checkimages=true;
				if($checkimages){
						$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contests/";
						$imagesfiles = false;
						$images = false;
						
						if (isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
							if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 20000000) {
						
								$images = $this->uploadHelper->uploadThisImage($_FILES['images'],$path);						
							} else {
								$success = false;
							}
						} else {
							$success = false;
						}
						$realfilename = @$_FILES['images']['name'];
						
						
						 
						if($images)$imagesfiles = $images['arrImage']['filename'];
						else $imagesfiles = false;
						
						
					 // pr($imagesfiles);
						$data =  $this->appsHelper->uploadContents(false,$imagesfiles,$realfilename);  
						if($imagesfiles)$data['realfilename'] =$CONFIG['PUBLIC_ASSETS_DOMAIN_PATH']."contests/".$imagesfiles;
						else $data['realfilename'] =$CONFIG['PUBLIC_ASSETS_DOMAIN_PATH']."contests/default.jpg";
						 
						 
						if($data){
						
							if($data['status']){
								
								if($imagesfiles)$data['images'] ='uploaded';
								else $data['images'] = 'not found';
								
								if($CONFIG['TWITURL']){									 
									$path = $CONFIG['BASE_DOMAIN_PATH'];									
									$imagesminis = base64_encode("public_assets/contests/{$imagesfiles}");						
									$twitdata['imagesrealpath']= $path."?timages=".$imagesminis;
								}else{ 
									$twitdata['imagesrealpath']= $path .$imagesfiles; 
								}
								$this->session->setSession('twitdata_session','twitdata',$twitdata); 
									$checkTwitter = $this->checklogintwitters();
									
										if($checkTwitter['status'] != 1)
										{
											$checkTwitter['addon'] = $checkAddon;
											return array('result'=>false,'data'=>array(),'twitpost'=>$checkTwitter);
										}
										
									 
								return array('result'=>true,'data'=>$data,'twitpost'=>$twit);
							}
							return array('result'=>false,'data'=>$data,'twitpost'=>$twit);
						}
					 
					return array('result'=>false,'data'=>$data,'twitpost'=>$twit);
				}
			}
			 
			return array('result'=>false,'data'=>array(),'twitpost'=>$twit);
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
 
}
?>
