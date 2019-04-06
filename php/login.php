<?php
session_start();

#$_SESSION['user'] = $user_id
if (isset($_SESSION['user'])){
	#loggedin
	Header("Location: menu.php");
	exit();
}else{
	#not logged in yet check login
	#set session parameters based on a valid login
	if(isset($_POST['login']) && isset($_POST['password'])){
		$user = $_REQUEST['login'];
		$pass = $_REQUEST['password'];
		require 'config.phplib';
		$conn = pg_connect("user=".$CONFIG['username'].
				" dbname=".$CONFIG['database']);
		#check database with parameterized sql query for user/pass
		$res = pg_query_params($conn, "SELECT * FROM users WHERE login=$1 AND password=$2", array($_REQUEST['login'],$_REQUEST['password']));
		if(pg_num_rows($res) == 1){
			#get users role to persist in the session
			$resRole = pg_query_params($conn, "SELECT role FROM users WHERE login=$1", array($_REQUEST['login']));
			$_SESSION['user'] = $user;#set username
			$_SESSION['role'] = $resRole;#set role
			echo $_SESSION['user'];
			Header("Location: menu.php");
			exit();
		}else{
			echo "Invalid username or password";
			session_destroy();
		}
	}
}

?>

<html>
<head>
<title>HIWA Login Screen</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
<?php require 'header.php'; ?>

<div class="login">
<p>Welcome to the Horribly Insecure Web Application.</p>


<form method="POST">
<div class="loginfield">
	<div class="loginlabel">Username</div>
	<div class="logininput">
		<input type="text" size="30" name="login">
	</div>
</div>
<div class="loginfield">
	<div class="loginlabel">Password</div>
	<div class="logininput">
		<input type="password" size="30" name="password">
	</div>
</div>
<p/><input type="submit" name="Login"/>
</form>
<p><a href="reset.php">Forgot password?</a></p>
<p/>
Flag: <i>423320a19a2256ba8c8dac04f3bd329f</i>
</div><!-- login -->

</body>
</html>

