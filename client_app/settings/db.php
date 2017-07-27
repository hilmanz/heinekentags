<?php

class db{
	protected $conn;
	protected $index = 0;
	function open_db(){
	
		$CONFIG['DATABASE'][0]['HOST'] 		= "localhost";
		$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
		$CONFIG['DATABASE'][0]['PASSWORD'] 	= "";
		$CONFIG['DATABASE'][0]['DATABASE'] 	= "brandifi_2014";
		
		$host = $CONFIG['DATABASE'][$this->index]['HOST'];
		$user = $CONFIG['DATABASE'][$this->index]['USERNAME'];
		$password = $CONFIG['DATABASE'][$this->index]['PASSWORD'] ;
		// print_r( $CONFIG);
		$this->conn = mysql_connect($host,$user,$password);

		if($this->conn)$result =  "can used db";
		else $result =  "cant connect";
		
		return $result;

	}
	
	function setSocketDB($index=0){
		$this->index=$index;
	}
	function query($sql){
	
		$this->open_db();
		$query = mysql_query($sql,$this->conn);
		print mysql_error();
		$this->close_db();
		if($query) return true;
		else return false;
		// return $sql;
		
	}
	
	function fetch($sql,$all=false){
		$this->open_db();
		$data = array();
		$query = mysql_query($sql,$this->conn);
		if($all==true) {
			while($row = mysql_fetch_object($query)){
			$data[] = $row;
			}
		
		}else $data = mysql_fetch_object($query);
		
		$this->close_db();
		return $data;
	}
	
	function close_db(){
		if($this->conn!=null){mysql_close($this->conn);}
	}

	
}


?>
