<?php
class bannerHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
	}
	
	
	
	

	function listbanner()
	{
		global $CONFIG;
		
		
		$sql = "SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_banner
			WHERE 1 and n_status!=3
			ORDER BY id_banner DESC LIMIT 3"; 
	
		$rqData = $this->apps->fetch($sql,1);

		
		$result['result'] = $rqData;
		
		return $result;
	}
	
	function addbanner($filephoto=null){
		global $CONFIG;
	
		$title = strip_tags($this->apps->_p('title'));       
		$caption = strip_tags($this->apps->_p('caption'));  
		$folder = intval($this->apps->_p('folder')); 
		$url = strip_tags($this->apps->_p('urlbanner')); 
		$photo = $filephoto; 		
		
		
		
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.my_banner (`title`, `caption`,`url`,`photo`,`date`,`n_status`) 
							VALUES ('{$title}', '{$caption}', '{$url}','{$photo}',now(),1)";
			  	
		$res = $this->apps->query($sql);
		return $res;
		}
	function addFolder(){
		global $CONFIG;
	
		$nameFolder = strip_tags($this->apps->_p('nameFolder'));       
		$caption = strip_tags($this->apps->_p('caption'));    
		
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.my_folder (`name_folder`,`caption_folder`, `date`,`n_status`) 
							VALUES ('{$nameFolder}','{$caption}',now(),1)";
		//pr($sql);exit;		  	
		$res = $this->apps->query($sql);
		return $res;
		}	
	function getFolder(){
		global $CONFIG;
	
		
		$sql = "select * from {$CONFIG['DATABASE'][0]['DATABASE']}.my_folder ";
		$res = $this->apps->fetch($sql,1);
		
		return $res;
		}
	function folder($id){
		global $CONFIG;
	
		
		$sql = "select * from {$CONFIG['DATABASE'][0]['DATABASE']}.my_folder where id_folder='{$id}' ";
		$res = $this->apps->fetch($sql);
		
		return $res;
		}
	function selectupdatedata($id){
		global $CONFIG;
	
		//pr($startdate);exit;
		
		
		$sql = "SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_banner
			WHERE id_banner='{$id}'";
		$res = $this->apps->fetch($sql);
		
		
		return $res;
		}
		
	function editbanner($id,$filephoto){
		global $CONFIG;
	
		$title = strip_tags($this->apps->_p('title'));       
		$caption = strip_tags($this->apps->_p('caption'));  
		$url = strip_tags($this->apps->_p('urlbanner')); 
		$type = '1'; 
		$photo ="";
		if($filephoto)
		{
			$photo =",photo='{$filephoto}'";
		}
		$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate')));
		
		$sql = "UPDATE  {$CONFIG['DATABASE'][0]['DATABASE']}.my_banner set `title`='{$title}',`caption`='{$caption}',`url`='{$url}'{$photo},`date`='{$startdate}' where id_banner='{$id}'";
			// pr($sql );exit;
		$res = $this->apps->query($sql);
		return $res;
		}	
	function deletebanner($id){
		global $CONFIG;
		
		
		$sql = "UPDATE   {$CONFIG['DATABASE'][0]['DATABASE']}.my_banner set n_status='3' where id_banner={$id}";
		//pr($sql);exit;
				  	
		$res = $this->apps->query($sql);
		return $res;
		}		
		
}
	