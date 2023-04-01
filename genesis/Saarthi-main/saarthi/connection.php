<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "project";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "kerberos";

if(!$con1 = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}
?>
