<?php

class regiterHelper {
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
 	
		$this->config =$CONFIG;
		 
	}

	
}
