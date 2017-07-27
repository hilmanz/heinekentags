<?php
class share extends App{
	
	
	function beforeFilter(){
	  
	global $LOCALE,$CONFIG;
		$this->contentHelper = $this->useHelper("contentHelper");
		$this->twitterHelper  = $this->useHelper('twitterHelper');
		$this->FacebookHelper  = $this->useHelper('FacebookHelper');
		$this->appsHelper  = $this->useHelper('appsHelper');
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		

		
	}
	
	function main(){
			
	error_reporting(E_ALL);
		global $LOCALE,$CONFIG; 
		$flavorid=123;
			$module = 'share/main';
			$param=$this->_g('parameter');
			
			if($this->_g('loginType')=='twitter')
				{
						$statusLoginTwitter=0; 
						if(@isset($this->session->getSession('twitter_session','twitter')->token))
							{
								
								
								$twit= $this->twitterHelper->update_tweet();
								//sendRedirect($CONFIG['BASE_DOMAIN'].'/mixing');die;
								
								session_destroy();
								
							}
						else
							{
								if(isset($_REQUEST['oauth_token']))
								{
									
									pr($this->twitterHelper->authorize());exit;
									
								}
								else
								{
									
								
									$urlConnect=$this->twitterHelper->request_login_link($flavorid,$module);
									
									sendRedirect("{$urlConnect['urlConnect']}");die;
								}
							}
				}
			
			$urlConnectTwt=$this->twitterHelper->request_login_link($flavorid,$module);
		
			$this->assign('loginTwitter',$urlConnectTwt['urlConnect']);
			$this->assign('success',"Data Has Been Successfully Insert");
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/contact_us.html');
						
		
		
	}
	function getUser()
	{
		//getuser 
		//insert to db
	}
	function getVideoFb()
	{
		$tokken=$this->FacebookHelper->tokken();
		$tokkenhashtags=$this->FacebookHelper->tokkenhashtags();
		$date=strtotime("now");
		// echo date('Ymd H:i:s',strtotime("now"));
		$posts=$this->_p('hastags');
			if($posts=='')
			{
				$posts=urlencode('#TakeMeToDreamIsland');
			}
		// $getData =  file_get_contents('https://graph.facebook.com/v1.0/search?access_token='.$tokken.'&q='.$posts.'&type=post&limit=500&since='.strtotime(date('20141227 00:00:00')).'&until='.$date.'');
		// $result =json_decode($getData);
		
		$url = 'https://graph.facebook.com/v1.0/search?access_token='.$tokken.'&q='.$posts.'&type=post&limit=50&since='.strtotime(date('20141227 00:00:00')).'&until='.$date.'';
		// pr($url);
		$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		  $datas = curl_exec($curl);
		  $info=curl_getinfo($curl);
		  curl_close($curl);
		  $results = json_decode($datas, true);
		  // pr($info);
		// pr($results);
		foreach($results['data'] as $key=>$row)
		{
			if( $row['type']=='video')
			{
				//$this->appsHelper->insertVideo();
				//$user=$this->FacebookHelper->getUserDetail($row->from->id);
				// pr($row);
				$data[$key]['post_id']= $row['id'];
				$data[$key]['id_facebook']= $row['from']['id'];
				$data[$key]['username']=$row['from']['name'];
				$data[$key]['profile_picture']="https://graph.facebook.com/{$row['from']['id']}/picture?type=square&return_ssl_resources=1";
				$data[$key]['fullname']= $row['from']['name'];
				$data[$key]['posts']= $row['description'];
				$data[$key]['images_thumb']= $row['picture'];
				$data[$key]['link_content']= 'https://www.facebook.com/'.$row['id'];
				$data[$key]['link_video']= $row['link'];
				$data[$key]['refrance']= 'Facebook';
				// pr($data);
				$this->appsHelper->insertVideo($data);
			}
		}
		print('sucsess');
		die; 
	}
	function getVideoTwt()
	{
		$result=$this->twitterHelper->getHastags();
		// pr($result);die;
		foreach($result['statuses'] as $key=>$row)
		{
			
			if(@$row['entities']['urls'] && @$row['entities']['media'])
			{
				
				$data[$key]['post_id']=$row['id_str'];
				$data[$key]['id_twitter']=$row['user']['id_str'];
				$data[$key]['username']=$row['user']['name'];
				$data[$key]['profile_picture']=$row['user']['profile_image_url_https'];
				$data[$key]['fullname']=$row['user']['name'];
				$data[$key]['posts']=$row['text'];
				$data[$key]['images_thumb']=@$row['entities']['media']['0']['media_url_https'];
				$data[$key]['link_video']=@$row['entities']['urls']['0']['expanded_url'];
				$data[$key]['refrance']='twitter';
				// pr($data);
				$this->appsHelper->insertVideo($data);
			}
			elseif($row['entities']['urls'])
			{
				$data[$key]['post_id']=$row['id_str'];
				$data[$key]['id_twitter']=$row['user']['id_str'];
				$data[$key]['username']=$row['user']['name'];
				$data[$key]['profile_picture']=$row['user']['profile_image_url_https'];
				$data[$key]['fullname']=$row['user']['name'];
				$data[$key]['posts']=$row['text'];
				$data[$key]['images_thumb']=@$row['entities']['media']['0']['media_url_https'];
				$data[$key]['link_video']=@$row['entities']['urls']['0']['expanded_url'];
				$data[$key]['refrance']='twitter';
				// pr($data);
				$this->appsHelper->insertVideo($data);
			}
		
		}
		print('sucsess');
		die;
	}
	function getVideoInst()
	{
		$posts=$this->_request('hastags');
			if($posts=='')
			{
				$posts='TakeMeToDreamIsland';
			}
			$url = 'https://api.instagram.com/v1/tags/'.$posts.'/media/recent?client_id=cd07523ce9d14c8da1eb3ffe46411e61&count=250';
			// pr($url);
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
			
			//$this->instagramHelper->insertInstagram($results);
			foreach($results['data'] as $key=>$row)
			{
				
				if(@$row['type']=='video')
				{
					
					$data[$key]['post_id']=$row['id'];
					$data[$key]['id_instagram']=$row['user']['id'];
					$data[$key]['username']=$row['user']['username'];
					$data[$key]['profile_picture']=$row['user']['profile_picture'];
					$data[$key]['fullname']=$row['user']['full_name'];
					$data[$key]['posts']=$row['caption']['text'];
					$data[$key]['images_thumb']=@$row['images']['standard_resolution']['url'];
					$data[$key]['link_content']= $row['link'];
					$data[$key]['link_video']=@$row['videos']['standard_resolution']['url'];
					$data[$key]['refrance']='instagram';
					// pr($data);
					$this->appsHelper->insertVideo($data);
				}
			
			}
			// pr($results);
			print('sucsess');
			die;
		
	}
	function twitterShare()
	{
		//untuk share twitter
		$flavorid=123;
			$param=$this->_g('parameter');
			
			$module = 'share/twitterShare?parameter='.$param;
			if($this->_g('loginType')=='twitter')
				{
						$statusLoginTwitter=0; 
						if(@isset($this->session->getSession('twitter_session','twitter')->token))
							{
								
								$result = $this->appsHelper->syncTwitter();
								//$twit= $this->twitterHelper->update_tweet();
							
								//sendRedirect($CONFIG['BASE_DOMAIN'].'/mixing');die;
								$data['iduser']=$result['iduser'];
								$data['score']=base64_decode($param);
								$twit= $this->twitterHelper->update_tweet($data);
								//pr($this->session->getSession('twitter_session','twitter'));die;
								session_destroy();
								echo"<script> window.close(); </script>";
							}
						else
							{
								if(isset($_REQUEST['oauth_token']))
								{
									
									pr($this->twitterHelper->authorize());
									
								}
								else
								{
									
								
									$urlConnect=$this->twitterHelper->request_login_link($flavorid,$module);
									
									sendRedirect("{$urlConnect['urlConnect']}");
								}
							}
				}
			else
			{
				$urlConnect=$this->twitterHelper->request_login_link($flavorid,$module);
									
									sendRedirect("{$urlConnect['urlConnect']}"); 
			}
	}
	function fbShare()
	{
		// echo"sss";die;
		//untuk simpan user
		
		if($this->FacebookHelper->checkUserLogin())
		{
			$checkUser ='';
				$sessionFacebook = $this->session->getSession('facebook_session','facebook');
			//	pr($sessionFacebook);die;
			$result = $this->appsHelper->syncFacebook();
			$param=$this->_g('parameter');
		
			if($result['status']==1 )
			{
				$data['iduser']=$result['iduser'];
				$data['score']=base64_decode($param);
				
				$this->FacebookHelper->share($data);
				
			}
				//return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/sharefb.html');
			session_destroy();
			echo"<script> window.close(); </script>";
		}
		else
		{
			$urlConect=$this->FacebookHelper->init();
			sendRedirect("{$urlConect['urlConnect']}");
			//pr($urlConect);die;
		}
		
	}	
}
?>