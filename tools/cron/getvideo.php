<?php 
$resultFB = file_get_contents('http://preview.kanadigital.com/heinekentags/public_html/share/getVideoFb');
$resulttwitter = file_get_contents('http://preview.kanadigital.com/heinekentags/public_html/share/getVideoTwt');
$resultinstagram =  file_get_contents('http://preview.kanadigital.com/heinekentags/public_html/share/getVideoInst');
echo "<pre> status FB"; 
print_r($resultFB);
echo "<pre> status Twitter"; 
print_r($resulttwitter);
echo "<pre> status Instagram"; 
print_r($resultinstagram);
?>

