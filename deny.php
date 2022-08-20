<?php
include_once 'header.php';

$id = $_GET['id'];
$userID = $_SESSION['user_id'];


if(isset($_POST['deleteConfirm']))
{
	$confirmDel = $_POST['confirmDel'];

	if($confirmDel == 1)
	{
		$statement = $db->prepare("DELETE FROM pictures WHERE (restaurant_ID = :id)");
		$statement->execute([
		    'id' => $id,
		]);

		$statementDelete = $db->prepare("DELETE FROM restaurant_owners WHERE (user_ID = :userID) AND (restaurant_ID = :id)");
		$statementDelete->execute([
		    'userID' => $userID,
		    'id' => $id,
		]);

		$statement2 = $db->prepare("DELETE FROM restaurants WHERE (ID = :id)");
		$statement2->execute([
		    'id' => $id,
		]);

		header("Location: admin.php");
		die();
	}
	else
	{
		header("Location: admin.php");
		die();
	}
}




  

?>

<div class="about-us section-padding" data-scroll-index='1'>
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center">
        <h3>ČE BOSTE PROŠNJO ZAVRGLI BO IZBRISANA IZ BAZE! NADALJUJEM?</h3>
        <form method="POST" name="deleteConfirm" target="_self" enctype="multipart/form-data">
	              
	            	<input type="radio" id="YES" name="confirmDel" value="1">
					<label for="YES">DA</label>
					<input type="radio" id="NO" name="confirmDel" value="0">
					<label for="NO">NE</label><br />
				
            	<input type="submit" name="deleteConfirm" value="POTRDI" class="submitGray" style="width:200px;">

        </form>

        <span class="section-title-line"></span>
      </div>
    </div>
  </div>
</div>





  <?php 

    include_once 'footer.php';
?>

<footer class="footer-copy">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <p>2018 &copy; Elegant. Website Designed by <a href="http://w3Template.com" target="_blank" rel="dofollow">W3 Template</a></p>
      </div>
    </div>
  </div>
</footer>