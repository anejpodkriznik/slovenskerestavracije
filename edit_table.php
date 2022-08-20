<?php
include_once 'header.php';

$restaurantID = $_GET['id'];
$guests = $_GET['guests'];
$action = $_GET['value'];
$tid = $_GET['tid'];

//+1
if($action == 1)
{
    $statement = $db->prepare("INSERT INTO tables (restaurant_ID, guests) VALUES (:restaurantID, :guests)");
	 $statement->execute([
		'restaurantID' => $restaurantID,
		'guests' => $guests,
	]);
}

//-1
else if($action == 2)
{
	$statement2 = $db->prepare("DELETE FROM tables WHERE (restaurant_ID = :id) AND (guests = :guests) AND (ID = :tid)");
	$statement2->execute([
		'id' => $restaurantID,
		'guests' => $guests,
		'tid' => $tid,
	]);
}

//delete all
else if($action == 3)
{
	$statement2 = $db->prepare("DELETE FROM tables WHERE (restaurant_ID = :id) AND (guests = :guests)");
	$statement2->execute([
		'id' => $restaurantID,
		'guests' => $guests,
	]);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
die();