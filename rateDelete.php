<?php
include_once 'header.php';

$restaurantID = $_GET['id'];
$user_ID = $_SESSION['user_id'];


//preverimo če že obstaja glas istega uporabnika
$stmtVoteExists = $db->prepare("SELECT COUNT(*) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE ((user_ID = :userID) AND (restaurant_ID = :restID))");
$stmtVoteExists->execute(array(':userID' => $user_ID, ':restID' => $restaurantID));
$voteExists = $stmtVoteExists->fetchColumn();


//če je ga izbrišem
if($voteExists >= 1)
{
    $statementDelete = $db->prepare("DELETE FROM ratings WHERE (user_ID = :userID) AND (restaurant_ID = :id)");
	$statementDelete->execute([
	    'userID' => $user_ID,
	    'id' => $restaurantID,
	]);
}


header('Location: ' . $_SERVER['HTTP_REFERER']);
die();