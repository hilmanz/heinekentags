<?php 

class settingHelper {
	
	function __construct($apps){
		global $logger,$CONFIG;
		
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	} 
	
	function projectList(){
		
		$start = false;
			$qProject = "  ";
		if($this->apps->user->type<666){
			$qProject = " AND uid = {$this->uid} ";
		}
		$sql = "SELECT * FROM `tbl_brand_master` WHERE 1 {$qProject} AND n_status IN (1,2)";
		$qData = $this->apps->fetch($sql,1);
		//pr($qProject);exit;
		$no = 1;
		foreach($qData as $key => $val){
			$qData[$key]['no'] = $no;
			$no++;
		
		}
		return $qData;
	}
	
	function getDesign(){
		$project = intval($this->apps->_g('projects'));
		if(!$project) $project = intval($this->apps->_g('id'));
		if(!$project)return false;
		$sql ="SELECT * FROM customize_templates WHERE brand = {$project} ORDER BY n_status desc,id DESC";
		 
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $key => $val){
			$qData[$key]['type']='text';			
			$qData[$key]['values']='';	
			$qData[$key]['updateon']='';			
			if($val['images']!='')	$qData[$key]['type']='upload';
			if($val['color']!='')	$qData[$key]['type']='color';
			if($val['size']!='')	$qData[$key]['type']='size';
			if($val['style']!='')	$qData[$key]['type']='style';
			
			if($val['images']!='')	$qData[$key]['values']=$val['images'];
			if($val['color']!='')	$qData[$key]['values']=$val['color'];
			if($val['size']!='')	$qData[$key]['values']=$val['size']; 
			if($val['style']!='')	$qData[$key]['values']=$val['style']; 
			if($val['textfill']!='')	$qData[$key]['values']=$val['textfill']; 
			
			if($val['images']!='')	$qData[$key]['updateon']='images';
			if($val['color']!='')	$qData[$key]['updateon']='color';
			if($val['size']!='')	$qData[$key]['updateon']='size'; 
			if($val['style']!='')	$qData[$key]['updateon']='style';  
			if($val['textfill']!='')	$qData[$key]['updateon']='textfill'; 
			
		}
		// pr($qData);
		return $qData;
	}
	
	 
	
 
	function getHapus($cid){
		global $CONFIG;
		
		if($this->apps->user->type<666) return false;
		
		if($cid){
			$sql = "delete {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master from tbl_brand_master where id={$cid} ";
	
			// pr($sql);exit;
			$qdata  =  $this->apps->query($sql);

					if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
	
	
	function insertbrand(){
			global $CONFIG;
				//pr($_POST);
				if($this->apps->user->type<666) return array('status'=>3,'msg'=>'You dont have privilage to access this service.');
				
				$projectname = strip_tags($this->apps->_p('projectName')); 
				$codename = preg_replace('/\s+/', '_', strtolower($projectname));
				$name = strip_tags($this->apps->_p('name'));
				$email = strip_tags($this->apps->_p('userName'));
				$password = strip_tags($this->apps->_p('password'));
				$confirm = strip_tags($this->apps->_p('confirm'));
				
				
				//check valid projectname
				
				if(ctype_alpha(str_replace(' ', '', $projectname))==false||$projectname=='')
				return array('status'=>2,'msgpr'=>'* Please enter your project name ','pr'=>$projectname
							,'name'=>$name,'email'=>$email
				
				);
				
				//check valid name
						
				if(ctype_alpha(str_replace(' ', '', $name))==false||$name=='')
				return array('status'=>2,'msgn'=>'* Please enter your name ','pr'=>$projectname
							,'name'=>$name,'email'=>$email);
				
				
				//check valid email
				$valid_email = $this->apps->is_valid_email($email);
				if(!$valid_email) return array('status'=>2,'msge'=>'* You must enter a valid email address *','pr'=>$projectname
							,'name'=>$name,'email'=>$email);
				
				
				//check valid Password
				
				if($password!=$confirm or $password=="")
				{
						return array('status'=>3,'msgp'=>'* The passwords you entered did not match','pr'=>$projectname,'name'=>$name,'email'=>$email);
				}
				$valid_pass=preg_match('/\A(?=[\x20-\x7E]*?[A-Z])(?=[\x20-\x7E]*?[a-z])(?=[\x20-\x7E]*?[0-9])[\x20-\x7E]{6,}\z/', $password);
				if(!$valid_pass)
				return array('status'=>2,'msgp'=>'* The password you entered doesn\'t meet the minimum security requirements','pr'=>$projectname,'name'=>$name,'email'=>$email);
		
				
				


				$submit = $this->apps->_p('submit');



				
				$uuid=0;
				$add_msg = array();
					if($submit){
							//check email whether it is ever registered
							$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.social_member
									WHERE email = '{$email}' AND n_status=1 LIMIT 1";
							$rs = $this->apps->fetch($sql);
							//var_dump($rs);exit;
							if($rs){
								$uuid = intval($rs['id']);
								$add_msg['email'] = "This ".$email." has been registered in this website.";
								return array('status'=>6,'msgee'=>$add_msg['email']);
							}else{
								//check confirm password is same
								if($password!=$confirm)
								
								{
								
									return array('status'=>2,'msgp'=>'* The passwords you entered did not match','pr'=>$projectname,'name'=>$name,'email'=>$email);
								}
								
								
								$hash = $this->encrypt($password);
								$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.social_member (`name`,`email`,`username`,`salt`,`password`,n_status,deviceid) 
								VALUES ('{$name}', '{$email}','{$email}','1234568','{$hash}',1,'host@touchbaseconnect.com')";
								$rsi = $this->apps->query($sql);
								$ownerID = $this->apps->getLastInsertId();
								if($rsi){
									$uuid = intval($this->apps->getLastInsertId());
								}else{
									return array('status'=>4,'msg'=>'Unknown error');
								}
								
							}

							$sql1 = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master (`brandname`, `codename`,`uid`,`n_status`) 
									VALUES ('{$projectname}', '{$codename}',{$uuid}, '2')";
							//var_dump($sql1);exit;
					
							$res = $this->apps->query($sql1);
							$lastID = $this->apps->getLastInsertId();
							//pr($lastID);exit;
							if($lastID>0){
								$created_date = date("Y-m-d H:i:s");
								$closed_date = date("Y-m-d H:i:s",strtotime("+30 days"));
								$sql2 = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.my_profile (`name`, `created_date`,`closed_date`,`type`,`brand`,`city`,ownerid,n_status) 
									VALUES ('{$name}', '{$created_date}', '{$closed_date}', '100', '{$lastID}', '', '{$ownerID}',1)";
								$res = $this->apps->query($sql2);
					
								$this->copyTemplates($lastID);
								return array('status'=>1,'msg'=>'Success','notif'=>$add_msg['email']);
							}
						}
		
		return $res;
	
		}
	
	function selectupdatebrand($id=NULL)
	{
		
		global $CONFIG;
		$sql = "select * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master where {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master.id={$id}";
		// pr($sql);
		// fetch()
		$qData = $this->apps->fetch($sql);
		return $qData;
	
	}
	
	function updatethebrand($id=NULL){
		global $CONFIG;
		//pr($_POST);
				$brand = $this->apps->_p('brand'); 
				$code = $this->apps->_p('code');   
				$submit = $this->apps->_p('submit');
				
		if($this->apps->user->type<666) return false;		
	
		if(isset($submit)){
		
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master SET `brandname` = '{$brand}',`codename`='{$code}' WHERE id = {$id} "; 
			
		
			$res = $this->apps->query($sql);
		
			
			return $res;
		}
		
		return false;
	}
	
	 
	function updateDesignRow($images=false)
 
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
 
		if($this->apps->user->type<666) $brand = $this->apps->user->brand;
		else $brand = $this->apps->_p('brand');
		$updateon = $this->apps->_p('updateon');  
		$designid = $this->apps->_p('designid');  
		$values = $this->apps->_p($updateon); 
		if($images)$values = $images; 
		$sql =" SELECT brandname FROM tbl_brand_master WHERE id = {$brand} LIMTI 1";
		$projectname = $this->apps->fetch($sql);
		if($projectname) $projectname = $projectname['brandname'];
		else $projectname= "";  
		 
		if(!$images){
			//cek has images if images buggy
			$sql = "SELECT images FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates WHERE id = {$designid} LIMIT 1";
			$isimagesdata = $this->apps->fetch($sql);
			if($isimagesdata){
				if($isimagesdata['images']!='') return false;
			}
		}
				
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$updateon}` = '{$values}' 
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}

	function updateDesignRow2($images=false)
 
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
 
		if($this->apps->user->type<666) $brand = $this->apps->user->brand;
		else $brand = $this->apps->_p('brand');
		


		//get All design id
		$design = $this->getDesign($brand);
		//pr($design);exit;
		$data = array();
		foreach ($design as $key => $value) {
			if($value['sections']){
				$data[$value['sections']]=array('id'=>$value['id'],'brand'=>$value['brand'],'sections'=>$value['sections'],'type'=>$value['type'],'values'=>$value['values'],'field'=>$value['updateon']);
			}
			
		}
		//pr($design);exit;
		// $updateon = $this->apps->_p('updateon');  
		//$designid = $this->apps->_g('id');  
		// $values = $this->apps->_p($updateon); 
		
		//if($images)$values = $images; 
		
		$sql =" SELECT brandname FROM tbl_brand_master WHERE id = {$brand} LIMIT 1";
		$projectname = $this->apps->fetch($sql);
		//pr($sql);exit;
		if($projectname) $projectname = $projectname['brandname'];
		else $projectname= "";  
		 
		// if(!$images){
		// 	//cek has images if images buggy
		// 	$sql = "SELECT images FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates WHERE id = {$designid} LIMIT 1";
		// 	$isimagesdata = $this->apps->fetch($sql);
		// 	if($isimagesdata){
		// 		if($isimagesdata['images']!='') return false;
		// 	}
		// }
		
		//home bg
		if(isset($images['home_bg'])){
			$home_bg = $images['home_bg'];
			$designid = $data['home']['id'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `images` = '{$home_bg}' 
					WHERE id = {$designid} LIMIT 1"; 
			//pr($sql);exit;
			$res = $this->apps->query($sql);
		}
		if(isset($images['main_bg'])){
			$main_bg = $images['main_bg'];
			$designid = $data['global']['id'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `images` = '{$main_bg}' 
					WHERE id = {$designid} LIMIT 1"; 
			$res = $this->apps->query($sql);
		}
		$var = 'button-menu';
		if(isset($images[$var])){
			$main_bg = $images[$var];
			$designid = $data[$var]['id'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `images` = '{$main_bg}' 
					WHERE id = {$designid} LIMIT 1"; 
			$res = $this->apps->query($sql);
		}
		$var = 'button-submit';
		if(isset($images[$var])){
			$main_bg = $images[$var];
			$designid = $data[$var]['id'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `images` = '{$main_bg}' 
					WHERE id = {$designid} LIMIT 1"; 
			//pr($sql);exit;
			$res = $this->apps->query($sql);
		}
		$var = 'button-submit-disable';
		if(isset($images[$var])){
			$main_bg = $images[$var];
			$designid = $data[$var]['id'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `images` = '{$main_bg}' 
					WHERE id = {$designid} LIMIT 1"; 
			$res = $this->apps->query($sql);
		}

		//color
		$var = 'text-label-menu-color';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;

			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'form-cell-text-color';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;

			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'form-cell-background';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;

			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'form-cell-background-disable';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;

			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'text-label-color';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;

			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'text-thanks-color';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;
			if(isset($data[$var])){
				$designid = $data[$var]['id'];
				$type = $data[$var]['type'];
				$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
					WHERE id = {$designid} LIMIT 1";
			}else{
				$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates
						(`brand`,`brandname`,`sections`,`color`,`n_status`) VALUES ({$brand},'{$projectname}','{$var}','{$val}',1)";
			}
			//pr($sql);exit;
			$res = $this->apps->query($sql);
		}

		$var = 'text-menu-report-color';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;
			if(isset($data[$var])){
				$designid = $data[$var]['id'];
				$type = $data[$var]['type'];
				$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
					WHERE id = {$designid} LIMIT 1";
			}else{
				$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates
						(`brand`,`brandname`,`sections`,`color`,`n_status`) VALUES ({$brand},'{$projectname}','{$var}','{$val}',1)";
			}
			$res = $this->apps->query($sql);
		}

		$var = 'text-label-menu-background-color';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			if (strpos($field,'#') === false) $val = '#'.$field;
			else $val = $field;
			if(isset($data[$var])){
				$designid = $data[$var]['id'];
				$type = $data[$var]['type'];
				$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
					WHERE id = {$designid} LIMIT 1";
			}else{
				$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates
						('brand','brandname','sections','color','n_status') VALUES ({$brand},'{$projectname}','{$var}','{$val}',1)";
			}
			$res = $this->apps->query($sql);
		}

		//size
		$var = 'text-size';
		if($this->apps->_p($var)){
			$field = intval($this->apps->_p($var));
			$val = $field;	
	
			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			//pr($sql);exit;
			$res = $this->apps->query($sql);
		}
		$var = 'textbox-text-size';
		if($this->apps->_p($var)){
			$field = intval($this->apps->_p($var));		
			$val = $field;
	
			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'label-text-size';
		if($this->apps->_p($var)){
			$field = intval($this->apps->_p($var));	
			$val = $field;	
	
			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'subtitle-text-thanks-size';
		if($this->apps->_p($var)){
			$field = intval($this->apps->_p($var));	
			$val = $field;	
	
			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'title-text-thanks-size';
		if($this->apps->_p($var)){
			$field = intval($this->apps->_p($var));	
			$val = $field;	
	
			$designid = $data[$var]['id'];
			$type = $data[$var]['type'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$val}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}
		$var = 'thankyou-new-registrant-offline';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			//pr($data);exit;
			$designid = $data[$var]['id'];
			$type = $data[$var]['field'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$field}' 
				WHERE id = {$designid} LIMIT 1";
			//pr($sql);exit;
			$res = $this->apps->query($sql);
		}
		$var = 'thankyou-new-registrant-offline-title';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
			//pr($data);exit;
			$designid = $data[$var]['id'];
			$type = $data[$var]['field'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$field}' 
				WHERE id = {$designid} LIMIT 1";
			//pr($sql);exit;
			$res = $this->apps->query($sql);
		}

		$var = 'thankyou-new-registrant-online';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
	
			$designid = $data[$var]['id'];
			$type = $data[$var]['field'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$field}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}

		$var = 'thankyou-new-registrant-online-title';
		if($this->apps->_p($var)){
			$field = strip_tags($this->apps->_p($var));		
	
			$designid = $data[$var]['id'];
			$type = $data[$var]['field'];
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$type}` = '{$field}' 
				WHERE id = {$designid} LIMIT 1";
			$res = $this->apps->query($sql);
		}

		//exit;
		if($res){
			return true;
		}
		return false;
	}
	
	function addDesignRow($images=false)
	{
		global $CONFIG;
		if($this->apps->user->type<666) $brand = $this->apps->user->brand;
		else $brand = $this->apps->_p('brand');
		$updateon = $this->apps->_p('updateon');   
		$sections = $this->apps->_p('sections');  
		$values = $this->apps->_p($updateon); 
		if($images)$values = $images;
		
		$sql =" SELECT brandname FROM tbl_brand_master WHERE id = {$brand} LIMIT 1";
		$projectname = $this->apps->fetch($sql);
		if($projectname) $projectname = $projectname['brandname'];
		else $projectname= "";  
		   
			$sql = "INSERT INTO customize_templates (`brand`, `brandname`, `sections`, `{$updateon}`,n_status) 
					VALUES ('{$brand}', '{$projectname}', '{$sections}', '{$values}', 1 )";
			// pr($sql);exit;
			$res = $this->apps->query($sql);
			$lastID = $this->apps->getLastInsertId();
			 if($lastID>0){
				return true;
			}
			return false;
	}
	
	 
	function unplublishDesign(){
		global $CONFIG;
		$designid = intval($this->apps->_p('designid'));  
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `n_status` = '0' 
				WHERE id = {$designid} {$qBrand} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 	function plublishDesign(){
		global $CONFIG;
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		$designid = intval($this->apps->_p('designid'));  
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `n_status` = '1' 
				WHERE id = {$designid} {$qBrand} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 
	 function trashDesign(){
		global $CONFIG;
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		$designid = intval($this->apps->_p('designid'));  
		$sql = "DELETE FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates  
				WHERE id = {$designid} {$qBrand} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 
	function getEvent($child=false){
		$project = intval($this->apps->_g('id'));
		if(!$project)return false;
		global $CONFIG;
		
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = " AND brand={$project} ";
		
		$sql = "  SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE parentid = 0 {$qBrand} AND n_status = 1 GROUP BY id ";
		  
		$qData = $this->apps->fetch($sql,1);
		if($child){
		$newdata = array();
			if($qData){
				$n=0;
				foreach($qData as $key => $val){ 
					$childevent = $this->getChildEvent($val['id'],$val['brand']);
					if($childevent){
					 
								$qData[$key]['submenu'] = $childevent;
						 
					}
					 
				} 
			}
		}
	 // pr($qData);
		return $qData;
		
	}
 
	 function getChildEvent($parentid=false,$brand=false){
	  global $CONFIG;
		if($brand==false) return array();
		if($parentid==false) return array();
		
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = " AND brand={$brand} ";
		
		$sql = "  SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE parentid = {$parentid} AND parentid<>0 {$qBrand} GROUP BY id";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
		
	}
	
		
	function getAppsID($schemaid=false){
	 global $CONFIG;
		if($schemaid==false)  $qSchemaID = "";
		else $qSchemaID = " AND schemaid = '{$schemaid}' ";
		
		$sql = "  SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_apps_references WHERE 1 {$qSchemaID} AND usercreator={$this->uid} AND n_status= 1  ";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
		
	}
	
		
	function addNewAppsID($schemaid=false){
		global $CONFIG;
		$data['result'] = false;
		$data['data'] = array(); 
		$schemaid = $this->apps->_p('schemaid');
		// $appsid= $this->apps->_p('appsid');
		// $appsid= $this->apps->_p('appsid');
		$appsname= $this->apps->_p('appsname');
		$datetimes = date("Y-m-d H:i:s");
		$appsid = md5($schemaid.$appsname);
		$sql = "  
		INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_apps_references  ( `schemaid`, `appsid`, `appsnames`, `datetimes`, `usercreator`, `n_status`) 
		VALUES ( '{$schemaid}', '{$appsid}', '{$appsname}', '{$datetimes}', '{$this->uid}', '1')
		ON DUPLICATE KEY UPDATE 
		appsid= VALUES(appsid),
		datetimes= VALUES(datetimes),
		usercreator= VALUES(usercreator),
		appsnames= VALUES(appsnames)
		";
		$qData = $this->apps->query($sql);
		$lastID = $this->apps->getLastInsertId();
		 if($lastID>0){
			$data['result'] = true;
			$data['data'] = array('appsid'=>$appsid); 
		}
		return $data;
		
	}
	
	
	function updateEventRow($images=false) 
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
		if($this->apps->user->type<666) $brand =  $this->apps->user->brand;
		else $brand = $this->apps->_p('brand');
		$name = $this->apps->_p('name');  
		$schemaid = $this->apps->_p('schemaid');  
	 
		$designid = $this->apps->_p('designid');  
		$parentid = $this->apps->_p('parentid');  
		 
		 
		$qUpdate = "";
		if($name){
			$qUpdate = "  brand={$brand},  name='{$name}'  ";
		}
		if($schemaid){
			$qUpdate = "  brand={$brand},  name='{$name}', schemaid='{$schemaid}' ";
		}
		if($images){
			$files = $images;
			$fullurlpath = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$name}/{$files}";
			$qUpdate = " brand={$brand}, name='{$name}', url='{$fullurlpath}' ,files='{$files}'  ";
		}
		if(!$qUpdate) return false;
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event SET {$qUpdate}
				WHERE id = {$designid} AND parentid={$parentid} LIMIT 1";
		 // pr($sql);
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
	
	function addEventRow($images=false) 
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
		if($this->apps->user->type<666) $brand =  $this->apps->user->brand;
		else $brand = $this->apps->_p('brand');
		$name = $this->apps->_p('name');  
		$schemaid = $this->apps->_p('schemaid');   
		$parentid = $this->apps->_p('parentid');  
		
		 
		 $files = '';
		 $fullurlpath = '';
		
		if($images){
			$files = $images;
			$fullurlpath = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$name}/{$files}";
			 
		}
	 
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
				(`id`, `parentid`, `pagestype`, `brand`, `otherid`, `name`, `schemaid`, `url`, `files`, `n_status`) 
				VALUES 
				(NULL, '{$parentid}', '1', '{$brand}', '0', '{$name}', '{$schemaid}', '{$fullurlpath}', '{$files}', '1') ";
		 // pr($sql);
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
	
	
	
	function unplublishEvent(){
		global $CONFIG;
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		
		$designid = intval($this->apps->_p('designid'));  
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event SET `n_status` = '0' 
				WHERE id = {$designid} {$qBrand} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 	function plublishEvent(){
		global $CONFIG;
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		$designid = intval($this->apps->_p('designid'));  
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event SET `n_status` = '1' 
				WHERE id = {$designid} {$qBrand} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 
	 function trashEvent(){
		global $CONFIG;
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		$designid = intval($this->apps->_p('designid'));  
		$sql = "DELETE FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event  
				WHERE id = {$designid} {$qBrand} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
	
	
	
	function getRegsitrationFields(){
	 global $CONFIG;
		$eventid = $this->apps->_g('projects');
		if($eventid==false) return array();
		else $qEventId = " AND eventid = '{$eventid}' ";
		
		$sql = "  SELECT * FROM    {$CONFIG['DATABASE'][0]['DATABASE']}.`tbl_mandatory_field` WHERE 1 {$qEventId} ORDER BY n_status DESC , `index` ASC  ";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
		
	}
	
	
	function getAllRegistrationsFields(){
		 global $CONFIG;
		$sql = "  SELECT * FROM    {$CONFIG['DATABASE'][0]['DATABASE']}.`tbl_mandatory_field`  GROUP BY fieldnames ";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
		
	}
	
	function addRegistrationFields(){
		global $CONFIG;
		$data['result'] = false;
		$data['data'] = array();

	
		
		$fieldnames = $this->apps->_p('fieldnames');
		$fieldnamesnew = $this->apps->_p('fieldnamesnew');
		
		if($fieldnamesnew!='')$fieldnames = $fieldnamesnew;
		$appsVariables= $this->apps->_p('appsVariables');
		$index= intval($this->apps->_p('index'));
		$eventid= intval($this->apps->_p('eventid'));
		$mandatory= intval($this->apps->_p('mandatory')); 
		
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		
		$sql =" SELECT id FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE  id = {$eventid}  {$qBrand} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['id']) return $data;
		}
		
		
		$sql = "  
		INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field   (  `fieldnames`, `appsVariables`, `index`, `eventid`, `mandatory`, `n_status`) 
		VALUES ( '{$fieldnames}', '{$appsVariables}', '{$index}', '{$eventid}', '{$mandatory}',  1)
		ON DUPLICATE KEY UPDATE 
		appsVariables= VALUES(appsVariables),
		`index`= VALUES(`index`),
		mandatory= VALUES(mandatory) 
		";
		// pr($sql);
		$qData = $this->apps->query($sql);
		$lastID = $this->apps->getLastInsertId();
		 if($lastID>0){
			$data['result'] = true;
			$data['data'] = array('lastID'=>$lastID); 
		}
		return $data;
		
	}
	
	function saveRegistrationFields(){
		global $CONFIG;
		$data['result'] = false;
		$data['message'] = 'failed';
		$fieldnames = $this->apps->_p('fieldnames');
		$id = $this->apps->_p('id');
	 
		$appsVariables= $this->apps->_p('appsVariables');
		$index= $this->apps->_p('index');
		$eventid= $this->apps->_p('eventid');
		$mandatory= $this->apps->_p('mandatory'); 
		
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		
		$sql =" SELECT id FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE  id = {$eventid}  {$qBrand} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['id']) return $data;
		}
		
		$sql = "  
		UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field  
		SET 
		`fieldnames` = '{$fieldnames}' , 
		`appsVariables` = '{$appsVariables}', 
		`index` = '{$index}', 
		`eventid` = '{$eventid}', 
		`mandatory` = '{$mandatory}'  
		WHERE id = {$id} LIMIT 1
		";
		
		// pr($sql);
		$qData = $this->apps->query($sql);
	 
		 if($qData){
			$data['result'] = true; 
			$data['message'] = 'success update'; 
		}else{
			$data['message'] ='failed to update, fields already exists'; 
		}
		return $data;
		
	}
	
	function unplublishRegistrationFields(){
		global $CONFIG; 
		$id = $this->apps->_p('designid');
		
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		
		$sql =" SELECT eventid FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field WHERE id = {$id} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['eventid']) return $data;
		}
		
		$sql =" SELECT id FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE  id = {$qData['eventid']}  {$qBrand} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['id']) return $data;
		}
		
		$sql = "  
		UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field  
		SET 
		n_status = 0
		WHERE id = {$id} LIMIT 1
		";
		
		// pr($sql);
		$qData = $this->apps->query($sql);
	 
		if($qData) return true;
	 
		return false;
		
	}
	
	function plublishRegistrationFields(){
		global $CONFIG; 
		$id = $this->apps->_p('designid');
		
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		$sql =" SELECT eventid FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field WHERE id = {$id} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['eventid']) return $data;
		}
		
		$sql =" SELECT id FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE  id = {$qData['eventid']}  {$qBrand} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['id']) return $data;
		}
		
		$sql = "  
		UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field  
		SET 
		n_status = 1
		WHERE id = {$id} LIMIT 1
		";
		
		// pr($sql);
		$qData = $this->apps->query($sql);
	 
		if($qData) return true;
	 
		return false;
		
	}
	function trashRegistrationFields(){
		global $CONFIG; 
		$id = $this->apps->_p('designid');
	  
		if($this->apps->user->type<666) $qBrand = "  AND brand = {$this->apps->user->brand} ";
		else $qBrand = "  ";
		
		$sql =" SELECT eventid FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field WHERE id = {$id} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['eventid']) return $data;
		}
		
		$sql =" SELECT id FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE  id = {$qData['eventid']}  {$qBrand} LIMIT 1  " ;
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if(!$qData['id']) return $data;
		}
		
		$sql = "  
		DELETE FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field  
		 WHERE id = {$id} LIMIT 1
		";
		
		// pr($sql);
		$qData = $this->apps->query($sql);
	 
		if($qData) return true;
	 
		return false;
		
	}
	
	function copyTemplates($brand=0){
		global $CONFIG; 
		//if($brand==0)$brand= intval($this->apps->_p('brand'));
		$sql =" SELECT brandname FROM tbl_brand_master WHERE id = {$brand} LIMIT 1";
		$projectname = $this->apps->fetch($sql);
		if($projectname) $projectname = $projectname['brandname'];
		else $projectname= ""; 
		
		$existingbrand = 4;
		
		$sql ="
		INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.`customize_templates`  (  `brand`, `brandname`, `sections`, `images`, `color`, `size`, `style`, `textfill`, `n_status`)  
		SELECT '{$brand}' `brand`, '{$projectname}' `brandname`, `sections`, `images`, `color`, `size`, `style`, `textfill`, `n_status` FROM {$CONFIG['DATABASE'][0]['DATABASE']}.`customize_templates` WHERE brand={$existingbrand} 

		";
		
		//pr($sql);exit;
		$qDataCopy = $this->apps->query($sql);
		// if($qDataCopy)return true;
		// else return false;
		
	}	
	 
	function getFonts(){
	global $CONFIG; 
		$sql = " SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_font_mapping ";
		$fontdata = $this->apps->fetch($sql,1);
		if($fontdata)return $fontdata;
		return false;
		
	}

	protected function encrypt($string)
	{	
		$ENC_KEY='youknowwho2014';
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($ENC_KEY), $string, MCRYPT_MODE_CBC, md5(md5($ENC_KEY))));
	}
	protected function decrypt($encrypted)
	{
		$ENC_KEY='youknowwho2014';
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($ENC_KEY), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($ENC_KEY))), "\0");
	}
	

	function projectPublish($id=null){
		global $CONFIG;
		if($this->apps->user->type<666) $qBrand = "  AND uid = {$this->apps->user->id} ";
		else $qBrand = "  ";

		$sql = "SELECT n_status FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master WHERE id={$id} LIMIT 1";
		$rs = $this->apps->fetch($sql);

		if($rs['n_status']==1){
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master SET `n_status` = '2' 
				WHERE id = {$id} {$qBrand} LIMIT 1";
		}else{
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master SET `n_status` = '1' 
				WHERE id = {$id} {$qBrand} LIMIT 1";
		}

		//pr($sql);exit;
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}

	function getProjectDetail(){
		global $CONFIG;
		$id = intval($this->apps->_g('id'));
		if(!$id) $id = $this->apps->user->brand;
		$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master WHERE id={$id}";
		$rs = $this->apps->fetch($sql);

		return $rs;
	}

	function updateProject(){
		global $CONFIG;
		if($this->apps->user->type<666) $qBrand = "  AND uid = {$this->apps->user->id} ";
		else $qBrand = "  ";
		
		$brand_name = strip_tags($this->apps->_p('projectName'));
		$id = intval($this->apps->_g('id'));
		
		if($brand_name==""){			 
			return array('status'=>2,'msg'=>'Please enter your project name *','brandname'=>$brand_name);
		}else{
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master SET `brandname` = '{$brand_name}' 
			WHERE id = {$id} {$qBrand} LIMIT 1"; 
			$res = $this->apps->query($sql);  		
		}		
		
	}

	function createEvent(){
		global $CONFIG;
	
		if($this->apps->user->type<666) $qBrand = "  AND uid = {$this->apps->user->id} ";
		else $qBrand = "  ";

		$brand = intval($this->apps->_p('projectid'));
		$event_name = strip_tags($this->apps->_p('event_name')); 
		//get name
		$reg_data = array();
		if(isset($_POST['reg'])){
			foreach ($_POST['reg'] as $key => $value) {
				$sql = "SELECT name,code_name FROM tbl_mandatory_ref 
						WHERE code_name = '{$value}' AND n_status = 1 LIMIT 1";
				$rs = $this->apps->fetch($sql); 
				$reg_data[$key]=$rs; 
			}
		}

		

		$register_fields = serialize($_POST['reg']); 
		$plugin = $addon = $data = array();
		if(isset($_POST['plugin'])){
			$plugin = $_POST['plugin_order'];
			$addon = $_POST['addon'];
			$addon_msg = $_POST['addon_msg'];
			// pr($plugin);
			// pr($addon);
			// pr($addon_msg);
			foreach ($plugin as $key => $value) {
				//pr($addon[$value]);exit;
				$data[$key]['plugin']=strip_tags($value);
				$addonn = array();
				foreach ($addon[$value] as $k=>$v) {
					$addonn[$k]['id']=intval($v);
					$addonn[$k]['msg']=strip_tags($addon_msg[$value][$v]);
				} 
				$data[$key]['addon']=serialize($addonn);
			}
		}
		

		$sql="INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
				(`parentid`,`pagestype`,`brand`,`name`,`n_status`)
			VALUES 
				(0,1,{$brand},'{$event_name}',1)";
		$rs = $this->apps->query($sql);
		
		if($rs){
			$parentid = $this->apps->getLastInsertId();
			//Register

			foreach ($reg_data as $key => $value) {
				$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field
						(`fieldnames`,`appsVariables`,`index`,`eventid`,`mandatory`,`n_status`)
						VALUES
						('{$value['name']}','{$value['code_name']}',{$key},{$parentid},1,1)";
				$tbl_mandatory_field = $this->apps->query($sql);
			}

			$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
					(`parentid`,`pagestype`,`brand`,`name`,`register_fields`,`n_status`)
					VALUES
					({$parentid},1,{$brand},'registration','{$register_fields}',1)";
			$reg = $this->apps->query($sql);
			sleep(1);
			foreach ($data as $key => $value) {
				$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
					(`parentid`,`pagestype`,`brand`,`name`,`schemaid`,`addonid_lists`,`n_status`)
					VALUES
					({$parentid},1,{$brand},'games','{$value['plugin']}','{$value['addon']}',1)";
				$plugin = $this->apps->query($sql);
				sleep(1);
			}
			if($reg) return true; 
		}
		return false;
	}
	
	function getEventList($start=null,$limit=5){
		global $CONFIG; 
		
		$brandid = $this->apps->_g('id');
		
		$result['result'] = false;
		$result['total'] = 0;
		$userid = $this->apps->user->id;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		if($this->apps->user->type<666){
			$sql = "SELECT COUNT(1) total 
					FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event ce
					LEFT JOIN {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master tbm 
					ON ce.brand = tbm.id
					WHERE parentid = 0 AND tbm.uid= {$userid} AND ce.n_status = 1";
		}else{
			$sql = "SELECT COUNT(1) total FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
					WHERE parentid = 0 AND brand = {$brandid} AND n_status=1";
		}
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		 
		if($this->apps->user->type<666){
			$sql = "SELECT ce . * , 
					tbm.id brandmasterid,tbm.brandname,tbm.codename,tbm.uid,tbm.n_status 
					FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event ce
					LEFT JOIN {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master tbm 
					ON ce.brand = tbm.id
					WHERE parentid = 0 AND tbm.uid= {$userid} AND ce.n_status = 1
					ORDER BY ce.id DESC
					LIMIT {$start},{$limit}";
		}else{
			$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
					WHERE	 parentid = 0 AND brand = {$brandid} AND n_status=1 
					ORDER BY id DESC
					LIMIT {$start},{$limit}";
		}
		//pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);
		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;
			}
			
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	
	}
	
	function deletedataevent(){
		global $CONFIG;
		
		$id = $this->apps->_g('id'); 
		
		if($id){
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event SET n_status = 3 WHERE id = {$id}";
			// pr($sql);exit;
			$qdata  =  $this->apps->query($sql);
			return $qdata;		
		}
		return false;
	}
	
	function getprocessupdate(){
		global $CONFIG;
		
		if($this->apps->user->type<666) $qBrand = "  AND uid = {$this->apps->user->id} ";
		else $qBrand = "  ";
		$id = $this->apps->_g('id'); 
		
		//registration 
		$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
				WHERE id={$id} LIMIT 1"; 
		$result  = $this->apps->fetch($sql);
		
		$sql1 = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
					WHERE parentid={$id} AND name='registration'
					LIMIT 1";
		$result1 = $this->apps->fetch($sql1);  
		$result['reg']= unserialize($result1['register_fields']);
		// pr($result['reg']);
		//games
		$sqlgames = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
					WHERE parentid={$id} AND name='games'	 
					LIMIT 1";
		$result2 = $this->apps->fetch($sqlgames); 
		$result['plugin']= unserialize($result2['addonid_lists']);
		$result['schemaid'] = $result2['schemaid'];
		//pr($result['plugin']); exit;
		if($result['plugin']){
			foreach ($result['plugin'] as $key => $val){
				 
					$result['addonid'][$key]=$val['id']; 
					$result['addonmsg'][$val['id']]=$val['msg']; 
					
			}
		}
		// pr($result);		
		// exit;
		return $result;	
	}
	
	function updatedataevent(){
		global $CONFIG;
		error_reporting(0);
		if($this->apps->user->type<666) $qBrand = "  AND uid = {$this->apps->user->id} ";
		else $qBrand = "  ";
		
		$id = $this->apps->_g('id'); 

		$brand = intval($this->apps->_g('brand'));
		$event_name = strip_tags($this->apps->_p('event_name')); 
		//get name
		$reg_data = array();
		if(isset($_POST['reg'])){
			foreach ($_POST['reg'] as $key => $value) {
				$sql = "SELECT name,code_name FROM tbl_mandatory_ref 
						WHERE code_name = '{$value}' AND n_status = 1 LIMIT 1";
				$rs = $this->apps->fetch($sql); 
				$reg_data[$key]=$rs; 
			}
		}

		

		$register_fields = serialize($_POST['reg']); 
		$plugin = $addon = $data = array();
		if(isset($_POST['plugin'])){
			$plugin = $_POST['plugin_order'];
			$addon = $_POST['addon'];
			$addon_msg = $_POST['addon_msg'];
			// pr($plugin);
			// pr($addon);
			// pr($addon_msg);exit;
			if($plugin){
				foreach ($plugin as $key => $value) {
					// pr($addon[$value]);exit;
					$data[$key]['plugin']=strip_tags($value);
					$addonn = array();
					if($addon[$value]){
						foreach ($addon[$value] as $k=>$v) {
							$addonn[$k]['id']=intval($v);
							$addonn[$k]['msg']=strip_tags($addon_msg[$value][$v]);
						} 

						$data[$key]['addon']=serialize($addonn);
					}
				}
			}
		}
		//pr($data);exit;

		$sql="UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
				SET name='{$event_name}' WHERE id={$id} ";
		$rs = $this->apps->query($sql);
		
		if($rs){
			//Register
			
			$sql1 = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field
					WHERE eventid={$id} AND n_status=1";
			$qData = $this->apps->fetch($sql,1);
			$newdatause = array(); 
			foreach($qData as $key => $val){
				
				$newdatause[]=$val['appsVariables'];
							
			}
			
			$sizedataofreg = sizeof($reg_data);
			$emptyvar = array();
			
			foreach ($reg_data as $key => $value) {
				
				if(!in_array($value['code_name'], $newdatause)){
					$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field
						(`fieldnames`,`appsVariables`,`index`,`eventid`,`mandatory`,`n_status`)
						VALUES
						('{$value['name']}','{$value['code_name']}',{$key},{$parentid},1,1)";
					$tbl_mandatory_field = $this->apps->query($sql);
			
				}else{
					$emptyvar[] = $value['code_name'];
				}
			
			}
			
			$emptyvar = "'".implode("','",$emptyvar)."'";
			
			//and apps <>	
			$sql1 = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field
					WHERE eventid={$id} AND n_status=1 AND appsVariables NOT IN({$emptyvar}) ";
			$qData = $this->apps->fetch($sql,1);
			if(sizeof($qData)>0){
				foreach($qData as $key => $val){
					$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_mandatory_field
						SET `n_status`= 2 WHERE eventid={$id} AND n_status=1 ";
					$tbl_mandatory_field = $this->apps->query($sql);
				
				}
				
			}
			
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
					SET register_fields = '{$register_fields}'
					WHERE parentid = '{$id}' AND n_status=1 AND name='registration' ";
			$reg = $this->apps->query($sql);
			sleep(1);
			//pr($data);exit;
			foreach ($data as $key => $value) {
				$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
						WHERE parentid = '{$id}' AND name='games'
						AND n_status=1 AND schemaid='{$value['plugin']}' LIMIT 1";
				$check = $this->apps->fetch($sql);
				//var_dump($check);exit;
				if($check){

					$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
						SET addonid_lists = '{$value['addon']}'
						WHERE parentid = '{$id}' AND name='games' 
						AND n_status=1 AND schemaid='{$value['plugin']}' ";
				}else{
					$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
					(`parentid`,`pagestype`,`brand`,`name`,`schemaid`,`addonid_lists`,`n_status`)
					VALUES
					({$id},1,{$brand},'games','{$value['plugin']}','{$value['addon']}',1)";
				}
				//pr($sql);exit;
				$plugin = $this->apps->query($sql);
				sleep(1);
			}
			if($reg) return true; 
		}
		return false;
	}
	
	/*
	function updatedataevent(){
		global $CONFIG;
		// pr($_POST);		
		
		if($this->apps->user->type<666) $qBrand = "  AND uid = {$this->apps->user->id} ";
		else $qBrand = "  ";
		
		$id = $this->apps->_g('id'); 
		$event_name = $this->apps->_p('event_name');
		$serializeevent = serialize($_POST['reg']);
		$addonevent = serialize($_POST['addon']);
		// pr($addonevent);	
		// pr($serializeevent);	
			// exit;
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
				SET name = '{$event_name}' WHERE id = '{$id}'"; 
		$qData = $this->apps->query($sql);
			
		$sqlregis = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event 
					SET  register_fields = '{$serializeevent}'
					WHERE parentid = '{$id}' AND name = 'registration'"; 
		$qDataregis = $this->apps->query($sqlregis);
			
		$sqladdon = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event  
					addonid_lists = '{$addonevent}'
					WHERE parentid = '{$id}' AND name = 'games'"; 
		$qDataaddon = $this->apps->query($sqladdon);
			
		
	
	}
	*/
}
?>