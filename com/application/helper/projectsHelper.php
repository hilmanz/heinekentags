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

	function getMandatoriFieldList(){
		$sql="SELECT * FROM {$this->dbshema}.tbl_mandatory_ref WHERE n_status = 1";
		$rs = $this->apps->fetch($sql,1);

		$size_data = sizeof($rs);
		$data_per_column = ceil($size_data/4);
		$data = array();
		$idx = $yx = 0;
		foreach ($rs as $key => $value) {
			if($data_per_column!=$idx){
				$data[$yx][$idx]=$value;
				$idx++;
			}else{
				$idx=0;
				$yx++;
				$data[$yx][$idx]=$value;
				$idx++;
			}
		}
		return $data;
	}

	function getPlugin(){
		$sql="SELECT *
				FROM {$this->dbshema}.tbl_apps_references
				WHERE n_status = 1";
		$rs = $this->apps->fetch($sql,1);
		
		foreach ($rs as $key => $value) {
			$sql = "SELECT tref.*
					FROM {$this->dbshema}.tbl_addon_register tadd
					LEFT JOIN {$this->dbshema}.tbl_addon_ref tref
					ON tref.id = tadd.addonID
					WHERE tadd.plugID = {$value['id']}
					AND tadd.n_status=1
					AND tref.n_status=1";
			$ra = $this->apps->fetch($sql,1);

			foreach ($ra as $k => $v) {
				$rs[$key]['addon_list'][]=$v;
			}
		}
		
		
		return $rs;
	}

	function getAddon(){
		$sql="SELECT * FROM {$this->dbshema}.tbl_addon_ref WHERE n_status = 1";
		$rs = $this->apps->fetch($sql,1);
		return $rs;
	}
}
?>