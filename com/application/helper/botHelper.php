<?php 

class botHelper {
	
	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	}
	 
	 // email	: hostess@sampoerna.com
	 // usercode : mop given id
	 // userid : id incremental
	 // nstatus
	 // username
	 
	 function migratetableuserprofile(){
			$sql = "
				  CREATE TABLE   tbl_user_profile_modist (
                  id  int identity primary key ,
                  usercode int  ,
                  modistid int ,
                  username varchar(200) ,
                  email varchar(200) ,
                  synch int ,
                  n_status int  
                ) ;  ";
			$this->apps->openmssql();
			$rs = $this->apps->querymssql($sql); 
			
			if(!$rs)	{
				echo 'failed to create';
			}else{
				echo 'ok, table created';
			}
			$this->apps->closemssql();
		}
		
		function modistinjectdistuserprofile(){
			// pr('masukkk');exit;
			
			$sql ="
			INSERT INTO tbl_user_profile_modist
			( usercode,modistid,username,email,synch,n_status )
			SELECT Id,Id,Login,'hostess@sampoerna.com',0,1 
			FROM View_BrandPresenter 
			";
			$this->apps->openmssql();
			$rs = $this->apps->querymssql($sql); 
			
			if(!$rs)	{
				echo 'failed to save data';
			}else{
				echo 'ok, row table saved';
			}
			$this->apps->closemssql();
		}
		
		
		function checkdatahasentry(){
			
		// $sql =" SELECT TOP 1  * FROM View_BrandPresenter  WHERE   ValidThrough >= CURRENT_TIMESTAMP ";
		
		// $this->apps->openmssql();
		// $rs = $this->apps->fetchmssql($sql);	
		// if($rs)	$rs = $rs[0];
		// $this->apps->closemssql();
			
			$sql =" SELECT TOP 1  * FROM tbl_user_profile_modist ";
			
			$this->apps->openmssql();
			$rs = $this->apps->fetchmssql($sql);	
			if($rs)	$rs = $rs[0];
			$this->apps->closemssql();
			
			pr($rs);
			
		}
		
		 
	 
	 
	 
	 
}
?>