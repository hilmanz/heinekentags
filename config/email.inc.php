<?php
$EMAIL['Password']['Reset'] = "
Hi {$qData['name']} {$qData['last_name']},<br><br>

Your BEAT password has been reset to {$password}<br><br>

Please login with this password and change it to something you will remember.<br><br>

Sincerely,<br>
BEAT team
"
;
$EMAIL['Password']['Link'] = "
Hi {$qData['name']} {$qData['last_name']},<br><br>

Click this link to reset your email password: <a href='{$reset_link}'>Reset Link</a><br><br>

Sincerely,<br>
BEAT team
";

$EMAIL['Pin']['Reset'] = "
Hi {$qData['name']} {$qData['last_name']},<br><br>

Here's your BEAT Personal Identification Number (PIN) as requested: {$pin}<br><br>

Sincerely,<br>
BEAT team
"
;


$STATIC['ASSETS_DOMAIN_WEB'] = 'https://www.neversaymaybe.co.id/assets/';

$EMAIL['TEMPLATE']['MAIL'] =' 
<html>
	<head>
		<title>email-blast1</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
		<body bgcolor="#666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
			<table  width="700"border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
				<tr>
					<td background="images/email_01.png" headers="129">
						<img src="https://www.neversaymaybe.co.id/assets/email/email_01.png" width="700" height="129" alt="">
					</td>
				</tr>
				<tr>
					<td background="https://www.neversaymaybe.co.id/assets/email/email_02.png">
						<div style="font-family:Arial, Helvetica, sans-serif; padding:0 30px; font-size:14px; line-height:1.4;">
							<h2>Hi '.$username.',</h2>
							'.$message.'
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<img src="https://www.neversaymaybe.co.id/assets/email/email_04.jpg" width="700" height="183" alt="">
					</td>
				</tr>
			</table>
		</body>
</html>';
						
 
?>