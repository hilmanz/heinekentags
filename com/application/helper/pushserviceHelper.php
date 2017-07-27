<?php 

class pushserviceHelper {

	function __construct($apps){
		global $CONFIG,$logger;
		echo"aaa";
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshemaWeb =$CONFIG['DATABASE'][0]['DATABASE'];	
		$this->dbshema =$CONFIG['DATABASE'][0]['DATABASE'];	
	} 
	
	function insert(){ 
		
		$userid =$this->apps->_p('userid');
		$fb_id = $this->apps->_p('fbId');
		$twitter_id = $this->apps->_p('twtId');
		$email = $this->apps->_p('email');
		$firstName = $this->apps->_p('firstName');
		$lastName = $this->apps->_p('lastName');
		$middelName = $this->apps->_p('middelName');
		$bridthday = $this->apps->_p('bridthday');
		$location = $this->apps->_p('location');
		// $collected_date = $this->apps->_p('collected_date');
		$result['status'] =0;
		$result['msg']='proses filed';
		
		if(!$userid || @$this->uid=='')
			{
				$result['msg']='user not login';
				return $result;
			}
		$sqlGetTemplate ="select count(1) as total,id from tbl_template where userid='{$userid}' limit 1";
		$rsGetTemplate = $this->apps->fetch($sqlGetTemplate);
		
		if($rsGetTemplate)
		{
			$templateid=$rsGetTemplate['id'];
			
			
				
				$sql = " INSERT INTO tbl_reporting 
						(tpl_id ,email,fb_id,twitter_id,first_name,last_name,middle_name,birthday,location,collected_date,n_status) 
						VALUES ('{$templateid}','{$email}','{$fb_id}','{$twitter_id}','{$firstName}','{$lastName}','{$middelName}','{$bridthday}','{$location}',NOW(),'1')";	 
				
				$rsUpdate = $this->apps->query($sql);
				if(!$rsUpdate)
				{
						$result['status'] =0;
						$result['msg']='proses  problem';
						return $result;
				}
				else
				{
						$result['status'] =1;
						$result['msg']='proses berhasil';
						return $result;
				
				}
			
		}
		else
		{
			$result['msg']='user not found';
			return $result;
		}
		
	}
	function getTemplate(){ 
	
		$userid =$this->apps->_p('user_id');
		$lastUpdate = $this->apps->_p('last_update');
		$templateId = $this->apps->_p('template_id');
		if(!$userid || @$this->uid=='')
			{
				$result['status'] =0;
				$result['msg']='user not login';
				return $result;
			}
		$sqlGetTemplate ="select * from tbl_template where id='{$templateId}' and userid='{$userid}' and modified_date='{$lastUpdate}' limit 1";
		$rsGetTemplate = $this->apps->fetch($sqlGetTemplate);
		if($rsGetTemplate)
		{
			
			$result['status'] =1;
			$result['data'] =$rsGetTemplate;
			$result['msg']='proses suksess';
			return $result;
		
		}
		$result['status'] =0;
		$result['msg']='proses filed';
		return $result;
	}
	
}

?>

