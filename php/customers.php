<?php
require 'config.phplib';

$msg="";
#sessions instead of cookies
if(isset($_SESSION['user'])){
	#Allow to page
}else{
	Header("Location: login.php");
	exit();
}

#set role via sessions not cookies
$role=$_SESSION['role'];



if (array_key_exists('action', $_REQUEST) &&
    array_key_exists('custid', $_REQUEST) &&
    $_REQUEST['action'] == 'delete') {
	$conn = pg_connect('user='.$CONFIG['username'].
		' dbname='.$CONFIG['database']);
	#-----------Modified to avoid SQL injection -----------------
	#pg_query moved to parameterized input using pg_query_params to avoid user input
	#directly added to a query string. 
	$res = pg_query_params($conn, "DELETE FROM customers WHERE customerid=$1", array($_REQUEST['custid']));
	
	if ($res === False) {
		$msg = "Unable to remove customer";
	}
}

else if (array_key_exists('custid', $_REQUEST) &&
    array_key_exists('custname', $_REQUEST) &&
    array_key_exists('limit', $_REQUEST) &&
    array_key_exists('taxid', $_REQUEST)) {

	$conn = pg_connect('user='.$CONFIG['username'].
		' dbname='.$CONFIG['database']);
	#-----------Modified to avoid SQL injection -----------------
	#pg_query moved to parameterized input using pg_query_params to avoid user input
	#directly added to a query string. 
	$res = pg_query_params($conn, "INSERT INTO customers (customerid, customername, creditlimit, taxid) VALUES ($1,$2,$3,$4)", 
		array($_REQUEST['custid'],$_REQUEST['custname'],$_REQUEST['limit'],$_REQUEST['taxid']));
	if ($res === False) {
		$msg="Unable to create customer.";
	}
}
?>

<html>
<head>
<title>HIWA Manage Customers</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
<?php require 'header.php';?>
<div class="title">HIWA Manage Customers</div>
<div class="subtitle">Logged in as <?php echo $_COOKIE['hiwa-user'];?>
	(<?php echo $role; ?>)
</div>

<?php
$conn = pg_connect("user=".$CONFIG['username']." dbname=".$CONFIG['database']);
$res = pg_query("SELECT * FROM customers");
?>
<table class="users">
<tr>
	<th>ID</th>
	<th>Name</th>
	<th>Tax ID or SSN</th>
	<th>Action</th>
</tr>
<?php
$count=1;
while (($row = pg_fetch_assoc($res)) !== False) {
	if ($count % 2 == 0) $class="even"; else $class="odd";
	$count++;
	echo "<tr class=\"$class\">";
	echo "<td>".$row['customerid']."</td>";
	echo "<td>".$row['customername']."</td>";
	echo "<td>".$row['creditlimit']."</td>";
	echo "<td>".$row['taxid']."</td>";
	echo "<td><a href=\"".$_SERVER['SCRIPT_NAME'].
		"?action=delete&custid=".$row['customerid']."\">delete</a></td>";
	echo "</tr>";
}
pg_free_result($res);
pg_close($conn);
?>
</table>	
<p>
<?php if ($msg != "") echo '<div class="err">'.$msg.'</div>'; ?>
<form method="post">
<div class="section">Add user</div>
<table>
<tr>
	<td>Customer ID:</td>
	<td><input type="text" name="custid" size="25"></td>
</tr>
<tr>
	<td>Customer Name:</td>
	<td><input type="text" name="custname" size="25"></td>
</tr>
<tr>
	<td>Credit Limit:</td>
	<td><input type="text" name="limit" size="25"></td>
</tr>
<tr>
	<td>Taxpayer Identification or social security number:</td>
	<td><input type="text" name="taxid" size="25"></td>
</tr>
</table>
<p>
<input type="submit" name="Create customer">
</form>
</body>
</html>
