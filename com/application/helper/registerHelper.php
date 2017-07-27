<?php
class registerHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->config = $CONFIG;
		
	
	}
	
	function addregister($type=2){
		$nama = 'aaa';
		$alamat ='aaa';
		$sekolah='aaa';
		$kelas='aaa';
		$telephone='1222';
		$jadwal=array('2','4');
		$jadwal=serialize($jadwal);
		$query="insert into  my_register(nama,telphone,alamat,nama_sekolah,jadwal) values
				('{$nama}','{$telephone}','{$alamat}','{$sekolah}','{$jadwal}')";
			
			$qdata = $this->apps->query($query);
		return $qdata;
	}
	
	
}
