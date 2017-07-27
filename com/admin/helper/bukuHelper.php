<?php
class bukuHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
	}
	
	
	
	

	function listbuku($start=null,$limit=10)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
	  
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (name LIKE '%{$search}%' )";
		$filter .= $enddate ? " AND postdate < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_book			
			WHERE 1 and n_status!=3";
		$total = $this->apps->fetch($sql);		
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_book
			WHERE 1 and n_status!=3
			ORDER BY id_book DESC LIMIT {$start},{$limit}
				
	"; 
	//	pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);

		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;

				$sql = "SELECT COUNT(*) total_data
						FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_book
						WHERE 1 and n_status!=3";
				// if($val['ownerid']==47){
				// 	pr($sql);
				//  	pr(intval($this->apps->fetch($sql)));exit;
				//  }
				$total_data = $this->apps->fetch($sql);
				$rqData[$key]['total_data'] = intval($total_data['total_data']);
			}
			//pr($rqData);exit;
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		$jumlahData=count($qData);
		
		for($i=0;$i<=$jumlahData-1;$i++)
		{
		
			
			foreach($qData[$i] as $key=>$value)
			{
				
				
				
				if($key=='photo')
				{
					$halaman=unserialize($value);
					
					
					foreach($halaman['halaman_cover'] as $k=>$v)
					{
							
							
							$qData[$i]['halaman_cover']=$v;
					}
					foreach($halaman['halaman_inti'] as $k=>$v)
					{
							
							$qData[$i]['halaman_inti'][$k]['urut']=$k+1;
							$qData[$i]['halaman_inti'][$k]['nama']=$v;
					}
					
					foreach($halaman['halaman_buku'] as $k=>$v)
					{
							
							$qData[$i]['halaman_foto'][$k]['urut']=$k+1;
							$qData[$i]['halaman_foto'][$k]['nama']=$v;
					}
				}
				
			}
		}
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		//pr($result);exit;
		return $result;
	}
	
	function addbuku($filephoto=null){
		global $CONFIG;
	
		$title = strip_tags($this->apps->_p('title'));       
		$caption = strip_tags($this->apps->_p('caption'));  
		$type = intval($this->apps->_p('type'));
		$photo = $filephoto; 		
		$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate'))); 
		//pr($startdate);exit;
		
		
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.my_book (`title`, `description`,`content`,`photo`,`type`,`date`,`penulis`,`n_status`) 
							VALUES ('{$title}', '{$caption}', '{$caption}','{$photo}','{$type}',now(),'',1)";
		
		$res = $this->apps->query($sql);
		return $res;
		}
	
	
	function selectupdatedata($id){
		global $CONFIG;
	
		//pr($startdate);exit;
		
		
		$sql = "SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.my_book
			WHERE id_book='{$id}'";
		$res = $this->apps->fetch($sql);
		foreach($res as $key=>$value)
		{
			$data[$key]=$value;
			if($key=='photo')
			{
				$halaman=unserialize($value);
				
				foreach($halaman['halaman_buku'] as $k=>$v)
				{
						
						$data['halaman_foto'][$k]['urut']=$k+1;
						$data['halaman_foto'][$k]['nama']=$v;
				}
			}
			
		}
		
		return $data;
		}
		
	function editbuku($id,$filephoto){
			global $CONFIG;
		
			$title = strip_tags($this->apps->_p('title'));       
			$caption = strip_tags($this->apps->_p('caption'));  
			$type = intval($this->apps->_p('type'));
			$photo = $filephoto; 		
			$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate')));
			
			$sql = "UPDATE  {$CONFIG['DATABASE'][0]['DATABASE']}.my_book set 
					`title`='{$title}',
					`description`='{$caption}',
					`content`='{$caption}',
					`photo`='{$photo}',
					`type`='{$type}',
					`date`=NOW()
					where id_book='{$id}'";
					
			$res = $this->apps->query($sql);
			return $res;
		}	
	function deletebuku($id){
		global $CONFIG;
		
		
		$sql = "UPDATE   {$CONFIG['DATABASE'][0]['DATABASE']}.my_book set n_status='3' where id_book={$id}";
		// pr($sql);exit;
				  	
		$res = $this->apps->query($sql);
		return $res;
		}		
		
}
	