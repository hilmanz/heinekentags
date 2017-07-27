<?php 

class projectsHelper {


	function __construct($apps){
		global $CONFIG,$logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshemaWeb =$CONFIG['DATABASE'][0]['DATABASE'];	
		$this->dbshema =$CONFIG['DATABASE'][0]['DATABASE'];	
	}
	
	function brandlist(){
		global $CONFIG;
		
		$sql = "SELECT id, name FROM {$this->dbshemaWeb}.social_member WHERE 1 GROUP BY name";
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	
	}

	function reportquerylist($start=null,$limit=10){
		global $CONFIG;  
		
		$start = intval($this->apps->_g('start')); 
		$brandid = $this->apps->_g('brandid');  
		//pr($brandid);exit;
		//revert date on get method
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate')); 
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
				 
		$qBrandid = "";
		
		$qProject = ""; 
		if($brandid)
			{		
				$qBrandid = " 	AND  tt.userid ='{$brandid}' ";
				//pr($qBrandid);exit;
			}
		
		if($startdate)
		$startdate = date("Y-m-d",strtotime($startdate));
		if($enddate) $enddate = date("Y-m-d",strtotime($enddate));
		
		if($this->apps->user->type<666){
			$qProject = " AND tr.tpl_id = {$this->uid} ";		
		}
		
		$qLimit = " LIMIT {$start},{$limit}"; 
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		$qDate = ""; 
		if($startdate&&$enddate){
		
		// echo "ss";exit;
			//$qDate = " AND DATE(tr.collect_date) >= DATE('{$startdate}') AND DATE(tr.collect_date) <= DATE('{$enddate}') ";
				$qDate = "AND DATE(tr.collected_date)  BETWEEN  DATE('{$startdate}')  AND DATE('{$enddate}')";
		}
		
		//GET TOTAL
		$sql = "SELECT count(1) total 		
				FROM {$this->dbshemaWeb}.tbl_reporting tr 
				LEFT JOIN {$this->dbshemaWeb}.tbl_template tt ON tr.tpl_id = tt.id
				LEFT JOIN {$this->dbshemaWeb}.my_profile mp ON tt.userid = mp.ownerid
				WHERE 1 {$qDate}{$qBrandid}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "SELECT mp.brand, tr.*,DATE_FORMAT(tr.collected_date ,'%d/%m/%Y %H:%i:%S') as codate,YEAR(
				CURRENT_TIMESTAMP ) - YEAR( tr.birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( tr.birthday, 5 ) ) AS age				
				FROM {$this->dbshemaWeb}.tbl_reporting tr 
				LEFT JOIN {$this->dbshemaWeb}.tbl_template tt ON tr.tpl_id = tt.id
				LEFT JOIN {$this->dbshemaWeb}.my_profile mp ON tt.userid = mp.ownerid
				WHERE 1 {$qDate}{$qBrandid} {$qLimit}  ";
		$qData = $this->apps->fetch($sql,1);
	    //pr($sql);
		if($start==0)$start=1;
		$no = 0+$start;
		if($qData){
			foreach($qData as $key => $val){
				$qData[$key]['no'] = $no++; 
			}
			// return $qData;
			$result['result'] = $qData;
			$result['total'] = intval($total['total']);
			return $result;
		}
		return false;
	}
}
?>