<?php
class locationHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
	}
	

	
	

	function listlocation($start=null,$limit=10)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
	  
		
	
		
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		
		
		$sql = "SELECT count(*) total
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}location
			WHERE 1 ";
		$total = $this->apps->fetch($sql);		
		
	//pr($sql);exit;
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.location 
			WHERE 1 
			ORDER BY id_location DESC LIMIT {$start},{$limit}
				
	"; 
		//pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);

		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;

				$sql = "SELECT COUNT(*) total_data
						FROM {$CONFIG['DATABASE'][0]['DATABASE']}.location
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
	
	function selectupdatedata($id){
		global $CONFIG;
	
		//pr($startdate);exit;
		
		
		$sql = "select * from {$CONFIG['DATABASE'][0]['DATABASE']}.location where id_location='{$id}' ";
			//pr($sql);exit;
		$res = $this->apps->fetch($sql);
		
	
		return $res;
		}
		
	function editnews($id, $images=false){
		global $CONFIG;
	
		$title = strip_tags($this->apps->_p('title'));       
		$description = strip_tags($this->apps->_p('description'));  
		$content =  $_POST['content'];  
		$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate'))); 
		//pr($startdate);exit;
		
		
		$sql = "UPDATE  {$CONFIG['DATABASE'][0]['DATABASE']}.news set `title`='{$title}',`description`='{$description}',`content`='{$content}',`date`='{$startdate}' where id_news={$id}";
		//pr($sql);exit;
				  	
		$res = $this->apps->query($sql);
		//pr($images);exit;
			if($images!='') 
			{
			//echo "ss";exit;
			$sql2 = "UPDATE  {$CONFIG['DATABASE'][0]['DATABASE']}.news SET img = '{$images}' WHERE id_news = {$id}";
			$res2 = $this->apps->query($sql2);
			return true;
			}else
			{
			//echo "trus";exit;
			return true;
			}
		}	
	function deletenews($id){
		global $CONFIG;

		$sql = "DELETE FROM  {$CONFIG['DATABASE'][0]['DATABASE']}.news where id_news={$id}";
		//pr($sql);exit;
				  	
		$res = $this->apps->query($sql);
		return $res;
		}		
		
}
	