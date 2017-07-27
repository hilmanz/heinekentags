<?php

class videoHelper{
	
	
	function __construct($apps){
	global $logger,$CONFIG;
	$this->logger = $logger;
	$this->apps = $apps;
	
	$this->dbshema = "";
	/*0; unread, 1: deleted, 2:read and send */
	}
	
	function getVideoview($id=null){
	if($id)
	{
			$id="and id_video={$id}";
	
	}
		$sql = "SELECT *  FROM my_video 
		WHERE n_status = 1 {$id} order by id_video ";
		$uidData = $this->apps->fetch($sql); 

		if(!$uidData) return false;
		else return $uidData;
	}
	
	function listvideo($start=null,$limit=10)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
	  
		
		$search = strip_tags($this->apps->_p('search'));
		
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND title_video LIKE '%{$search}%' ";
		//$filter .= $enddate ? " AND postdate < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_video			
			WHERE 1 and n_status!='3' {$filter}";
		$total = $this->apps->fetch($sql);		
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_video 
			WHERE 1 and n_status!='3' {$filter}
			ORDER BY id_video  DESC LIMIT {$start},{$limit}"; 
	
		$qData = $this->apps->fetch($sql,1);

		
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		//pr($result);exit;
		return $result;
	}
	
}
