<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once($_SERVER['DOCUMENT_ROOT'] . '/skytup/classes/sky.autoload.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/skytup/database/db_connect.php');
// Replace with your database credentials

// h=sql10.freemysqlhosting.net&u=sql10602474&p=DI5tDIREY3&d=sql10602474
/*
$host="sql205.epizy.com";
$username="epiz_30079449";
$password="g2PjwnR9nfMc";
$dbname="epiz_30079449_skytup";
$db_public = "epiz_30079449_public";


if(isset($_GET['h'])){
if ($_GET['h']) {
	$host = $_GET['h'];
}
if ($_GET['u']) {
	$username = $_GET['u'];
}
if ($_GET['p']) {
	$password = $_GET['p'];
}
if ($_GET['d']) {
	$dbname = $_GET['d'];
}}



// Establish a connection to the database
//$conn = (new Database($host, $username, $password, $dbname))->getConnect();

$conn = mysqli_connect($host, $username, $password, $dbname);
*/
$conn = $con;
// Check for errors
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

// Get the query from the AJAX request
$query = $_POST["query"];

// Run the query
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
	echo "Error: " . mysqli_error($conn);
	exit();
}

// Display the result
echo "<table>";
while ($row = mysqli_fetch_assoc($result)) {
	echo "<tr>";
	foreach ($row as $key => $value) {
		echo "<td>" . $value . "</td>";
	}
	echo "</tr>";
}
echo "</table>";

// Close the connection
mysqli_close($conn);
