<?php
require 'config.phplib';
?>
<html>
<head>
<title>HIWA Recover Password</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
<?php require 'header.php';

if (array_key_exists("username", $_POST)) {

	if (array_key_exists("confirmed", $_POST)) {
		print($_POST['confirmed']);
		if ($_POST['confirmed'] == "on") {
			print("<p>A password reset link has been emailed to $_POST[username].</p>");
			print("<p>If you did not receive an email in a few minutes, please check your Spam folder.</p>");
			exit();
		}
	}

	$conn = pg_connect('user='.$CONFIG['username'].
		' dbname='.$CONFIG['database']);
	#---------Modified to make blind sqli to enumerate users more difficult. -------
	$res = pg_query_params($conn, "SELECT role FROM users WHERE login=$1", array($_POST[username]));
	;
	#--------Removed if statement so that the confirmation will appear regardless of if the user exists or not---------
	print('<P>By continuing this process, you will reset the password of <span style="font-weight:bold">'.
			$_POST['username'].'</span>.</p>');
	print("<p>To continue, check the box and hit the Submit button.</p>");
	print('<FORM method="post">');
	print('<input type="hidden" name="username" value="'.$_POST['username'].'">');
	print('<input type="checkbox" name="confirmed">');
	print('<input type="submit">');
	print('</form>');
	exit();
	
}
?>
<p>To begin the password reset process, please enter your username in the secure field below.</p>
<form method="POST">
<input type="password" name="username" width="30" placeholder="Enter your login">
<p><input type="submit"></p>
</form>
</body>
</html>


