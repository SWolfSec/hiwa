<?php
require 'config.phplib';

$msg="";
#Updated Cookies to sessions
if(isset($_SESSION['user'])){
	#Allow to page
}else{
	Header("Location: login.php");
	exit();
}
#set role via session instead of cookie
$role=$_SESSION['role'];

if ($role != 'admin') Header("Location: menu.php");
?>
<html>
<head>
<title>HIWA Manage Users</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
<?php require 'header.php';

if (array_key_exists("ip", $_REQUEST)) {
	#Add in a check for valid ip address using the filer_var function
	if (filter_var($_REQUEST[ip], FILTER_VALIDATE_IP)){
		echo "<P>Valid IP Address found, pinging target IP address</P>";		
		#-------------New Execution Fixed------------
		#By taking the user input $_REQUEST[ip] out of the command and using the escapeshellarg() function it will strip any attempts at
		#escaping the input
		#Placed the command into a variable to make it cleaner
		$command = "ping -c 3".escapeshellarg($_REQUEST[ip]);
		exec($command, $out);
		echo "<div><pre>\r\n";
		echo implode("\r\n", $out)."\r\n";
		echo "</pre></div>";
	}else{
		echo "This is not a valid IP Address. You may only ping no command injection for you.";
	}
}
	
?>

<form>
<table>
<tr>
<td>Check hostname</td>
<td><input type="text" name="ip" placeholder="IP address or hostname" width="50"></td>
</tr>
<tr>
<td colspan="2" style="text-align: right"><input type="submit" value="Check!"/></td>
</tr>
</table>
</form>

</body>
</html>

	
