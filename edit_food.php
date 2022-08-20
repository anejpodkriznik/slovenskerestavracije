<?php
include_once 'header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: index.php");
}

$foodid = $_GET['id'];
$value = $_GET['value'];

if($value == 1)
{
	$statement = $db->prepare("DELETE FROM food WHERE (ID = :id)");
	$statement->execute([
		'id' => $foodid,
	]);	
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
die();