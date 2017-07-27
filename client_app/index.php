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
<img src="images/bg.png" id="bg" alt="">
<div id="fb-root"></div>
	<div id="body">
		<div id="user-info"></div>
		<div  id="logo"  >
				<?php 
					if($rs->login_type==1){
				?>
				<div id="buttonbox">
					<div class="fb-like-box" data-href="<?php echo $rs->fb_page;?>" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false">
					</div>
				</div>
				
				<a href="#" id="btnLogin"> <img src="images/btn_login.png" /> </a>
				<div id="fb-root"></div>
				<?php 
					}elseif($rs->login_type==2){
				?>

				<?php
					}
				?>
       </div><!-- end #logo -->
    </div><!-- end #body -->
</body>
<script>
<?php 
	if($rs->login_type==1){
?>
window.fbAsyncInit = function() {
FB.init({ appId: '258326611027452',
status: true,
cookie: true,
xfbml: true,
oauth: true});

function updateButton(response) {
var button = document.getElementById('logo');

if(!response.authResponse)
{
	 $('#logo').addClass("beforeLogin");
}
else
{
 $('#logo').removeClass("beforeLogin");

 }
if (response.authResponse) {
//user is already logged in and connected
var userInfo = document.getElementById('user-info');

FB.api('/me', function(response) {

 
FB.api('/me/likes/<?php echo $rs->fb_id;?>', function(responselike) {

		if(responselike.data.length){
				self.location='success.php';
				 
		}else{
				self.location='like.php?fbid='+response['id'];
					//console.log('sss'); 
			
		}
});
 
});

button.onclick = function() {
FB.logout(function(response) {
var userInfo = document.getElementById('user-info');
userInfo.innerHTML="";
});
};
} else {
//user is not connected to your app or logged out
button.onclick = function() {
FB.login(function(response) {
if (response.authResponse) {
	FB.api('/me', function(response) {
	//var userInfo = document.getElementById('user-info');
	 
		FB.api('/me/likes/286834058163926', function(responselike) {
		
			 if(responselike.data.length){
					self.location='success.php';
				 
				}else{
					self.location='index.php';
					
				} 
		});
		
	});
} else {
	//user cancelled login or did not grant authorization
	//self.location='loginface.html';
	
}
}, {scope:'email'});
}
}
}

// run once with current status and whenever the status changes
FB.getLoginStatus(updateButton);
FB.Event.subscribe('auth.statusChange', updateButton);
FB.Event.subscribe('edge.create', function(href, widget) {
		self.location='success.php';
});
};


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