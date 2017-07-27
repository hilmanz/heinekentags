<?php
class contentHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
	}
	function listnews2($start=null,$limit=10)
	{
			global $CONFIG;
		
		
		//GET TOTAL
		$sql = "SELECT count(*) total
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 ";
		$total = $this->apps->fetch($sql);		
		
	//pr($sql);exit;
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 AND n_status=1 ORDER BY `date` DESC "; 
		//pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);
		//echo "hasil qury  <br>";
		//pr($rqData );
		$iTotal=5;
		$i=0;
		$itmp=0;
		$result=array();
		if($rqData)
		{
			foreach ($rqData as $row=>$value)
			{
				if($i<=$iTotal)
				{
					if($itmp<8)
					{
						//echo "hasil qury  <8  <br>";
						//echo "result[{$i}][{$itmp}]";
						//pr($value );
						$result[$i][$itmp]=$value;	
						$result[$i][$itmp]['numbr']=$itmp;	
					}
					else
					{
						$i++;
						$itmp=0;
						//echo "hasil qury  stelah 8 buat array  <br>";
						//echo "result[{$i}][{$itmp}]";
						//pr($value );
						$result[$i][$itmp]=$value;	
						$result[$i][$itmp]['numbr']=$itmp;	
					}
					$itmp++;
				}
				
			
			}
			if($i > 0 && count($result[$i]) < 8)
			{
				unset($result[$i]);
			
			}
		}
		
		return $result;
	}
	function list2($start=null,$limit=10)
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
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 ";
		$total = $this->apps->fetch($sql);		
		
	//pr($sql);exit;
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 AND n_status='1' ORDER BY id DESC limit 9,8
			
				
	"; 
		//pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);

		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
			
				$rqData[$key] = $val;
				$sql = "SELECT COUNT(*) total_data
						FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user
						WHERE 1 ORDER BY id DESC limit 9,8";
				// if($val['ownerid']==47){
				// 	pr($sql);
				//  	pr(intval($this->apps->fetch($sql)));exit;
				//  }
				$total_registrant = $this->apps->fetch($sql);
				$rqData[$key]['total_registrant'] = intval($total_registrant['total_data']);
			}
			//pr($val['content'] );exit;
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
	//	pr($result);exit;
		return $result;
	}
	function checkEmailExist($email){
		global $CONFIG;
		$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.contact_us
				WHERE email = '{$email}' LIMIT 1";

		$rs = $this->apps->fetch($sql);

		return $rs;
	}
	
	function changestatus($uid,$status){
		global $CONFIG;
		if ($status=="active")
		{
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user set n_status=1
				WHERE id = '{$uid}'";
		//pr($sql);exit;
		$rs = $this->apps->query($sql);
		return array('status'=>'0');
		}else if ($status=="inactive")
		{
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user set n_status=0
				WHERE id = '{$uid}'";

		$rs = $this->apps->query($sql);
			//pr($sql);exit;
		return array('status'=>'1');
		}

		
	}
	

	function listnews($start=null,$limit=10)
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
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 ";
		$total = $this->apps->fetch($sql);		
		
	//pr($sql);exit;
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 ORDER BY id DESC
			
				
	"; 
		//pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);

		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
			
				$rqData[$key] = $val;
				$sql = "SELECT COUNT(*) total_data
						FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user
						WHERE 1 ORDER BY id DESC";
				// if($val['ownerid']==47){
				// 	pr($sql);
				//  	pr(intval($this->apps->fetch($sql)));exit;
				//  }
				$total_registrant = $this->apps->fetch($sql);
				$rqData[$key]['total_registrant'] = intval($total_registrant['total_data']);
			}
			//pr($val['content'] );exit;
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
	//	pr($result);exit;
		return $result;
	}
	
	
	
	
		
}
	