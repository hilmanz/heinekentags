<?php
	include_once "settings/db.php";
	$db = new db();
	
	$sql = "
		SELECT * 
		FROM brandifi_2014.my_profile mp
		LEFT JOIN brandifi_2014.tbl_template tt ON mp.ownerid = tt.userid
		WHERE 1
	";

	$rs = $db->fetch($sql);
	if($rs->login_type==1){
		$fbid=$_GET['fbid'];
		$sql = "
			SELECT * 
			FROM brandifi_2014.tbl_reporting
			WHERE fb_id = {$fbid} LIMIT 1
		";
		$check = $db->fetch($sql);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta charset="utf-8" />
<meta name = "viewport" content = "width=device-width, maximum-scale = 1, minimum-scale=1" />
<meta name="keywords" content="social media monitoring, social media monitoring indonesia,media monitoring indonesia,sentiment analysis bahasa indonesia,sonar media monitoring,sonar social media monitoring" />
<meta name="robots" content="index,all"/>
<link type="text/css" href="css/heineken.css" rel="stylesheet" />
<!-- Add jQuery library -->
<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script src="js/modernizr.js"></script>
<script src="js/heineken.js"></script>
<title><?php echo $rs->brand;?></title>
<!--[if (gte IE 6)&(lte IE 8)]>
    <script type="text/javascript" src="js/selectivizr-min.js"></script>
<![endif]-->
</head>

<body>
 <div id="body">
 <div id="fb-root"></div>
 	<?php 
		if($rs->login_type==1){
	?>
		<div class="fb-like" data-href="http://www.facebook.com/286834058163926" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
	<?php 
		}elseif($rs->login_type==2){
	?>

	<?php
		}
	?>
 </div><!-- end #body -->
</body>
<script>
<?php 
	if($rs->login_type==1){
		if($check){
?>
		self.location='success.php';
<?php
		}
?>
var FB;
$(document).ready(function(){
	window.fbAsyncInit = function() {
		FB.init({ appId: '258326611027452',
		status: true,
		cookie: true,
		xfbml: true,
		oauth: true,
		version: '2.0'});

		// run once with current status and whenever the status changes
		FB.getLoginStatus(updateButton);
		FB.Event.subscribe('auth.statusChange', updateButton);

		FB.Event.subscribe('edge.create', function(href, widget) {
			console.log('foo');
				self.location='success.php';
		});

	};
	
		
});

function updateButton(response) {
		console.log(response);
			if (response.authResponse) {
			//user is already logged in and connected
			var userInfo = document.getElementById('user-info');

			FB.api('/me', function(response) {
				console.log(response);
				 var myfbid = response['id'];
				// FB.api('/me/likes/286834058163926', function(responselike) {
				// 	console.log(responselike);
				// 		if(responselike.data[0]){
				// 			self.location='success.php';
				// 		}else{
				// 			//self.location='like.php';
									 
				// 		}
				// });
			 
			});



			} else { 
				self.location='index.php';
			}
		}

 (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
<?php
	}elseif($rs->login_type==2){
?>

<?php
	}
?>
</script>
  
</html>