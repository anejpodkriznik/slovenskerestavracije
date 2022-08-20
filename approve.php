<?php
include_once 'header.php';

$id = $_GET['id'];

$statement = $db->prepare("UPDATE restaurants SET approved=1 WHERE (ID = :id)");
$statement->execute([
	'id' => $id,
]);

header("Location: admin.php");
die();
?>