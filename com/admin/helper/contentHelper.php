<?php
require_once "resize-class.php";
class contentHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->config = $CONFIG;
		
	}
	
	function checkEmailExist($email){
		global $CONFIG;
		$sql = "SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.contact_us
				WHERE email = '{$email}' LIMIT 1";

		$rs = $this->apps->fetch($sql);

		return $rs;
	}
	
	function merge($filename_x, $filename_y, $filename_result, $filename_z,$name) {
		global $CONFIG;
		 // Get dimensions for specified images

		 list($width_x, $height_x) = getimagesize($filename_x);
		 list($width_y, $height_y) = getimagesize($filename_y);
		  list($width_z, $height_z) = getimagesize($filename_z);

		 // Create new image with desired dimensions

		// $image = imagecreatetruecolor(250, $height_x);

		 // Load images and then copy to destination image

		 $image_x = imagecreatefrompng($filename_x);
		 
		 $image_y = imagecreatefromjpeg($filename_y);
		 
		  $image_z = imagecreatefromjpeg($filename_z);
		 
		 $wihte = imagecolorallocate($image_y, 255, 255, 255);
		 $font = $CONFIG['LOCAL_ASSET']."font/ColabLig-webfont.ttf";
		 $font_size = '14'; 
		 imagettftext($image_x, 20, 0, 170,50, $wihte, $font,$name); 
		 imagettftext($image_x, 20, 0, 570,70, $wihte, $font,date('H:i:s')); 
		 
		imageAlphaBlending($image_x, false);
		imageSaveAlpha($image_x, true);	
					
		 //imagecopy($image, $image_x, 0, 0, 0, 0, 50, 500);
		 imagecopy($image_x, $image_y,28, 105,0, -10, 655,670);
		 imagecopy($image_x, $image_z,28, 0,0, -10, 100,100);
		 // Save the resulting image to disk (as JPEG)

		 imagejpeg($image_x, $filename_result);

		 // Clean up

		// imagedestroy($image);
		 imagedestroy($image_x);
		 imagedestroy($image_y);
		 imagedestroy($image_z);
		}
	
	function mergebulet($filename_x, $filename_y, $filename_result) {

		 // Get dimensions for specified images

		 list($width_x, $height_x) = getimagesize($filename_x);
		 list($width_y, $height_y) = getimagesize($filename_y);

		 // Create new image with desired dimensions

		// $image = imagecreatetruecolor(250, $height_x);

		 // Load images and then copy to destination image

		 $image_x = imagecreatefromjpeg($filename_x);
		 $image_y = imagecreatefromjpeg($filename_y);
		 imageAlphaBlending($image_x, false);
		 imageSaveAlpha($image_x, true);	
					
		 //imagecopy($image, $image_x, 0, 0, 0, 0, 50, 500);
		 imagecopy($image_y, $image_x,28, 105,0, -10, 655,670);
		 imagefilledellipse($image, 200, 150, 300, 200, $col_ellipse);
		 // Save the resulting image to disk (as JPEG)

		 imagejpeg($image_x, $filename_result);

		 // Clean up

		// imagedestroy($image);
		 imagedestroy($image_x);
		 imagedestroy($image_y);
		}	
	
    function imagebmp(&$im, $filename = "")
    {
        if (!$im) return false;
        $w = imagesx($im);
        $h = imagesy($im);
        $result = '';

        if (!imageistruecolor($im)) {
            $tmp = imagecreatetruecolor($w, $h);
            imagecopy($tmp, $im, 0, 0, 0, 0, $w, $h);
            imagedestroy($im);
            $im = & $tmp;
        }

        $biBPLine = $w * 3;
        $biStride = ($biBPLine + 3) & ~3;
        $biSizeImage = $biStride * $h;
        $bfOffBits = 54;
        $bfSize = $bfOffBits + $biSizeImage;

        $result .= substr('BM', 0, 2);
        $result .=  pack ('VvvV', $bfSize, 0, 0, $bfOffBits);
        $result .= pack ('VVVvvVVVVVV', 40, $w, $h, 1, 24, 0, $biSizeImage, 0, 0, 0, 0);

        $numpad = $biStride - $biBPLine;
        for ($y = $h - 1; $y >= 0; --$y) {
            for ($x = 0; $x < $w; ++$x) {
                $col = imagecolorat ($im, $x, $y);
                $result .=  substr(pack ('V', $col), 0, 3);
            }
            for ($i = 0; $i < $numpad; ++$i)
                $result .= pack ('C', 0);
        }

        if($filename==""){
            echo $result;
        }
        else
        {
            $file = fopen($filename, "wb");
            fwrite($file, $result);
            fclose($file);
        }
        return true;
    }
	
	function printer($uid,$status,$path){
		global $CONFIG;
		$sql = "SELECT *
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 AND id={$uid}"; 
		//pr($sql);exit;
		$rqData = $this->apps->fetch($sql,1);
		$urlimage=$rqData[0]['images'];
		
		$resizeObj = new resize($urlimage);
 
		// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
		$resizeObj -> resizeImage(640, 640, 'crop');
		 
		// *** 3) Save image
		$resizeObj -> saveImage($path."\print\{$uid}ok.jpg", 100);
		
		
		
		$urlimage2=$rqData[0]['profile_picture'];
		$name=$rqData[0]['username'];
		$resizeObj2 = new resize($urlimage2);
 
		// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
		$resizeObj2 -> resizeImage(100, 100, 'crop');
		 
		// *** 3) Save image
		$resizeObj2 -> saveImage($path."\print\{$uid}ok2.jpg", 100);
		
		
		
		
		//$this->mergebulet($path."\print\bingkai.png",$path."\print\{$uid}ok.jpg",$path."\print\{$uid}final.jpg");
		$this->merge($path."\print\bingkai.jpg",$path."\print\{$uid}ok.jpg",$path."\print\{$uid}final.jpg",$path."\print\{$uid}ok2.jpg",$name);
		
		//exit;;
		
		
		$extension = pathinfo($path."\print\{$uid}final.jpg");
		$newFileName = $path."\print\{$uid}final.bmp";


		$imageSource = imagecreatefromjpeg($path."\print\{$uid}final.jpg");
		$this->imagebmp($imageSource,$newFileName);

				
		$handle = printer_open("Microsoft Xps Document Writer");
		printer_set_option($handle, PRINTER_MODE, "raw"); 
		//printer_set_option($handle, PRINTER_TEXT_ALIGN, PRINTER_TA_LEFT);

		printer_start_doc($handle, "Print"); // Name Document 

		printer_start_page($handle); // Start Logo
		
		$largura = 500; //Image Width in Pixels
		$altura = 640;  //Image Height in Pixels

		$fator_x = $largura / 72; //Image Resolution (72 ppi)
		$fator_y = $largura / 300; //Printer Resolution (300 dpi)
		$fator_z = $fator_x / $fator_y;

		$largura_f = $largura * $fator_z;
		$altura_f = $altura * $fator_z;
		
		printer_draw_bmp($handle,$path."\print\{$uid}final.bmp",1,1,$largura_f, $altura_f);  // Logo Dir, lenght H , With V
		printer_end_page($handle);  // End Logo

		printer_end_doc($handle);   // Close document 
		printer_close($handle);     // Close Pritner
	
	}
	function changestatus($uid,$status){
		global $CONFIG;
		if ($status=="active")
		{
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user set n_status=1 , `date`=NOW()
				WHERE id = '{$uid}'";
	
		$rs = $this->apps->query($sql);
		return array('status'=>'1');
		}else if ($status=="inactive")
		{
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user set n_status=0
				WHERE id = '{$uid}'";

		$rs = $this->apps->query($sql);
			//pr($sql);exit;
		return array('status'=>'2');
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
			WHERE 1 AND n_status=0 ";
		$total = $this->apps->fetch($sql);		
		
	//pr($sql);exit;
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT *,DATE_FORMAT(`date`,'%d-%m-%Y') as `date`
			FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tabel_user 
			WHERE 1 AND n_status=0 ORDER BY `date` DESC 
			
				
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
						WHERE 1  AND n_status=0 ORDER BY `date` DESC ";
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
			WHERE 1 AND n_status=1 ORDER BY id DESC limit 9,8
			
				
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
						WHERE 1 AND n_status=1 ORDER BY id DESC limit 9,8";
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
		private function openImage($file)
	
			{
				// *** Get extension
				$extension = strtolower(strrchr($file, '.'));
			 
				switch($extension)
				{
					case '.jpg':
					case '.jpeg':
						$img = @imagecreatefromjpeg($file);
						break;
					case '.gif':
						$img = @imagecreatefromgif($file);
						break;
					case '.png':
						$img = @imagecreatefrompng($file);
						break;
					default:
						$img = false;
						break;
				}
				return $img;
			}
			
		private $imageResized;
		
		public function resizeImage($newWidth, $newHeight, $option="auto")
		{
		 
			// *** Get optimal width and height - based on $option
			$optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
		 
			$optimalWidth  = $optionArray['optimalWidth'];
			$optimalHeight = $optionArray['optimalHeight'];
		 
			// *** Resample - create image canvas of x, y size
			$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
			imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
		 
			// *** if option is 'crop', then crop too
			if ($option == 'crop') {
				$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
			}
		}
		
		private function getDimensions($newWidth, $newHeight, $option)
		{
		 
		   switch ($option)
			{
				case 'exact':
					$optimalWidth = $newWidth;
					$optimalHeight= $newHeight;
					break;
				case 'portrait':
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight= $newHeight;
					break;
				case 'landscape':
					$optimalWidth = $newWidth;
					$optimalHeight= $this->getSizeByFixedWidth($newWidth);
					break;
				case 'auto':
					$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
					$optimalWidth = $optionArray['optimalWidth'];
					$optimalHeight = $optionArray['optimalHeight'];
					break;
				case 'crop':
					$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
					$optimalWidth = $optionArray['optimalWidth'];
					$optimalHeight = $optionArray['optimalHeight'];
					break;
			}
			return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
		}
		
		private function getSizeByFixedHeight($newHeight)
		{
			$ratio = $this->width / $this->height;
			$newWidth = $newHeight * $ratio;
			return $newWidth;
		}
		 
		private function getSizeByFixedWidth($newWidth)
		{
			$ratio = $this->height / $this->width;
			$newHeight = $newWidth * $ratio;
			return $newHeight;
		}
		 
		private function getSizeByAuto($newWidth, $newHeight)
		{
			if ($this->height < $this->width)
			// *** Image to be resized is wider (landscape)
			{
				$optimalWidth = $newWidth;
				$optimalHeight= $this->getSizeByFixedWidth($newWidth);
			}
			elseif ($this->height > $this->width)
			// *** Image to be resized is taller (portrait)
			{
				$optimalWidth = $this->getSizeByFixedHeight($newHeight);
				$optimalHeight= $newHeight;
			}
			else
			// *** Image to be resizerd is a square
			{
				if ($newHeight < $newWidth) {
					$optimalWidth = $newWidth;
					$optimalHeight= $this->getSizeByFixedWidth($newWidth);
				} else if ($newHeight > $newWidth) {
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight= $newHeight;
				} else {
					// *** Sqaure being resized to a square
					$optimalWidth = $newWidth;
					$optimalHeight= $newHeight;
				}
			}
		 
			return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
		}
		 
		private function getOptimalCrop($newWidth, $newHeight)
		{
		 
			$heightRatio = $this->height / $newHeight;
			$widthRatio  = $this->width /  $newWidth;
		 
			if ($heightRatio < $widthRatio) {
				$optimalRatio = $heightRatio;
			} else {
				$optimalRatio = $widthRatio;
			}
		 
			$optimalHeight = $this->height / $optimalRatio;
			$optimalWidth  = $this->width  / $optimalRatio;
		 
			return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
		}
		private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
		{
			// *** Find center - this will be used for the crop
			$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
			$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
		 
			$crop = $this->imageResized;
			//imagedestroy($this->imageResized);
		 
			// *** Now crop from center to exact requested size
			$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
			imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
		}
		public function saveImage($savePath, $imageQuality="100")
{
    // *** Get extension
    $extension = strrchr($savePath, '.');
    $extension = strtolower($extension);
 
    switch($extension)
    {
        case '.jpg':
        case '.jpeg':
            if (imagetypes() & IMG_JPG) {
                imagejpeg($this->imageResized, $savePath, $imageQuality);
            }
            break;
 
        case '.gif':
            if (imagetypes() & IMG_GIF) {
                imagegif($this->imageResized, $savePath);
            }
            break;
 
        case '.png':
            // *** Scale quality from 0-100 to 0-9
            $scaleQuality = round(($imageQuality/100) * 9);
 
            // *** Invert quality setting as 0 is best, not 9
            $invertScaleQuality = 9 - $scaleQuality;
 
            if (imagetypes() & IMG_PNG) {
                imagepng($this->imageResized, $savePath, $invertScaleQuality);
            }
            break;
 
        // ... etc
 
        default:
            // *** No extension - No save.
            break;
    }
 
    imagedestroy($this->imageResized);
}
	
	
	
		
}
	