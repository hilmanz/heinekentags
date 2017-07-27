<?php
class careerHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
	}
	
	
	
	

	function listcareer($start=null,$limit=10)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
	  
		// $projectid = intval($this->apps->_g('projects'));
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (name LIKE '%{$search}%' )";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND postdate >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND postdate < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_career 
			WHERE 1 ";
		$total = $this->apps->fetch($sql);		
		
	//pr($sql);exit;
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_career 
			WHERE 1 
			ORDER BY id_career DESC LIMIT {$start},{$limit}
				
	"; 
	//	pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);

		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;

				$sql = "SELECT COUNT(*) total_data
						FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_career
						WHERE 1";
				// if($val['ownerid']==47){
				// 	pr($sql);
				//  	pr(intval($this->apps->fetch($sql)));exit;
				//  }
				$total_registrant = $this->apps->fetch($sql);
				$rqData[$key]['total_registrant'] = intval($total_registrant['total_data']);
			}
			//pr($rqData);exit;
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		//pr($result);exit;
		return $result;
	}
	
	function addlocation(){
		global $CONFIG;
	
		$name = strip_tags($this->apps->_p('name'));       
		$logitude = strip_tags($this->apps->_p('logitude'));  
		$atitude = strip_tags($this->apps->_p('atitude'));  
		$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate'))); 
		//pr($startdate);exit;
		
		
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.location (`name`, `logitude`,`attitude`,`date`,`n_status`) 
							VALUES ('{$name}', '{$logitude}', '{$atitude}','{$startdate}',1)";
		//pr($sql);exit;		  	
		$res = $this->apps->query($sql);
		return $res;
		}
			
	function selectupdatedata($id){
		global $CONFIG;
	
		//pr($startdate);exit;
		
		
		$sql = "select * from {$CONFIG['DATABASE'][0]['DATABASE']}.location where id_location='{$id}' ";
			//pr($sql);exit;
		$res = $this->apps->fetch($sql);
		
		//pr($res);exit;
		return $res;
		}
		
	function editlocation($id){
		global $CONFIG;
	
		$name = strip_tags($this->apps->_p('name'));       
		$logitude = strip_tags($this->apps->_p('logitude'));  
		$attitude = strip_tags($this->apps->_p('attitude'));  
		$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate'))); 
		//pr($startdate);exit;
		
		
		$sql = "UPDATE  {$CONFIG['DATABASE'][0]['DATABASE']}.location set `name`='{$name}',`logitude`='{$logitude}',`attitude`='{$attitude}',`date`='{$startdate}' where id_location={$id}";
	//	pr($sql);exit;
				  	
		$res = $this->apps->query($sql);
		return $res;
		}	
	function deletelocation($id){
		global $CONFIG;

		$sql = "DELETE FROM  {$CONFIG['DATABASE'][0]['DATABASE']}.location where id_location={$id}";
		//pr($sql);exit;
				  	
		$res = $this->apps->query($sql);
		return $res;
		}		
		
}
	