<?php
class galleryHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
	}
	
	
	
	

	function listgallery($start=null,$limit=10)
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
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.gallery			
			WHERE 1 and n_status!=3";
		$total = $this->apps->fetch($sql);		
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.gallery g
				LEFT JOIN {$CONFIG['DATABASE'][0]['DATABASE']}.my_folder mf  on g.folder=mf.id_folder
			WHERE 1 and g.n_status!=3
			ORDER BY id_gallery DESC LIMIT {$start},{$limit}
				
	"; 
	//	pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);

		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;

				$sql = "SELECT COUNT(*) total_data
						FROM {$CONFIG['DATABASE'][0]['DATABASE']}.gallery
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
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		//pr($result);exit;
		return $result;
	}
	
	function addgallery($filephoto=null){
		global $CONFIG;
	
		$title = strip_tags($this->apps->_p('title'));       
		$caption = strip_tags($this->apps->_p('caption'));  
		$folder = intval($this->apps->_p('folder')); 
		$type = '1'; 
		$photo = $filephoto; 		
		$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate'))); 
		//pr($startdate);exit;
		
		
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.gallery (`title`, `caption`,`type`,`folder`,`photo`,`date`,`n_status`) 
							VALUES ('{$title}', '{$caption}', '{$type}','{$folder}','{$photo}',now(),1)";
		//pr($sql);exit;		  	
		$res = $this->apps->query($sql);
		return $res;
		}
	function addFolder(){
		global $CONFIG;
	
		$nameFolder = strip_tags($this->apps->_p('nameFolder'));       
		
		
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.my_folder (`name_folder`, `date`,`n_status`) 
							VALUES ('{$nameFolder}',now(),1)";
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
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.gallery g
				LEFT JOIN {$CONFIG['DATABASE'][0]['DATABASE']}.my_folder mf  on g.folder=mf.id_folder
			WHERE id_gallery='{$id}'";
		$res = $this->apps->fetch($sql);
		
		
		return $res;
		}
		
	function editgallery($id,$filephoto){
		global $CONFIG;
	
		$title = strip_tags($this->apps->_p('title'));       
		$caption = strip_tags($this->apps->_p('caption'));  
		$folder = intval($this->apps->_p('folder')); 
		$type = '1'; 
		$photo ="";
		if($filephoto)
		{
			$photo =",photo='{$filephoto}'";
		}
		$startdate =  date('Y-m-d H:i:s', strtotime($this->apps->_p('startdate')));
		
		$sql = "UPDATE  {$CONFIG['DATABASE'][0]['DATABASE']}.gallery set `title`='{$title}',`caption`='{$caption}',`folder`='{$folder}'{$photo},`date`='{$startdate}' where id_gallery='{$id}'";
			  	
		$res = $this->apps->query($sql);
		return $res;
		}	
	function deletegallery($id){
		global $CONFIG;
		$sql = "select * from {$CONFIG['DATABASE'][0]['DATABASE']}.gallery where id_gallery='{$id}' ";
		$res = $this->apps->fetch($sql);
		
		$sql = "UPDATE   {$CONFIG['DATABASE'][0]['DATABASE']}.gallery set n_status='3' where id_gallery={$id}";
		//pr($sql);exit;
				  	
		$res = $this->apps->query($sql);
		return $res;
		}		
		
}
	