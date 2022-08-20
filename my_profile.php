<?php
include_once 'header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: index.php");
}
?>

<!-- Login -->
<div class="about-us section-padding">
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center">
        <h3>Uredi svoj profil!</h3>
        <p>Urejanje profila in pregled svojih restavracij.</p>
        <span class="section-title-line"></span>
      </div>
      <div id="content">
        <div class="UserContainer" style="margin-left: 20%;">
          <a href="edit_profile.php"><img src="images/5.png" alt="Avatar" class="image"></a>
          <div class="middle">
            <a href="edit_profile.php"><div class="text">Uredi moj profil</div></a>
          </div>
        </div>
        <div class="UserContainer" style="margin-left: 10%;">
          <a href="restaurant_view.php"><img src="images/restaurant.png" alt="Avatar" class="image"></a>
          <div class="middle">
            <a href="restaurant_view.php"><div class="text">Pregled mojih restavracij</div></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Login --> 

<style>


</style>

<?php
    include_once 'footer.php';
?>