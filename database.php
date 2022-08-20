<?php
    $user = 'root';
    $pass = '';
    $server = 'localhost';
    $db_name = 'slovenskerestavracije';

    //$link = mysqli_connect($server, $user, $pass, $db_name);
	//mysqli_query($link, "SET NAMES 'utf8'");

	$db = new PDO("mysql:host=$server;dbname=$db_name", $user, $pass);
        
 	$db_salt = "434su5jg5";
?>