<?php
    $user = 'b452c9d3e65c15';
    $pass = '867b1917';
    $server = 'eu-cdbr-west-03.cleardb.net';
    $db_name = 'heroku_804462f04350d2d';

    //$link = mysqli_connect($server, $user, $pass, $db_name);
	//mysqli_query($link, "SET NAMES 'utf8'");

	$db = new PDO("mysql:host=$server;dbname=$db_name", $user, $pass);
        
 	$db_salt = "434su5jg5";
?>
