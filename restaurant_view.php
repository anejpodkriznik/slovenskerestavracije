<?php
  include_once 'header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: index.php");
}

?> 


<!-- Prikaz -->
<div class="portfolio section-padding" data-scroll-index='3'>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 section-title text-center">
        <h3>Prikaz mojih restavracij</h3>
        <span class="section-title-line"></span>
      </div>
      <div class="gallery no-padding col-lg-12 col-sm-12">
        <div class="restDiv browse">
          <div id="searchDiv">

            <?php
            $stmt = $db->prepare("SELECT r. ID AS id, r.approved AS approved, r. name AS name, r. bio AS bio, c. name AS city, p. url AS url FROM restaurants r INNER JOIN cities c ON r.city_ID = c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID INNER JOIN restaurant_owners ro ON ro.restaurant_ID=r.ID INNER JOIN users u ON ro.user_ID=u.ID WHERE(ro.user_ID = :id) ORDER BY name ASC");

            $stmt->execute(array(':id' => $_SESSION['user_id']));

            while ($row = $stmt->fetch())
            {
              ?>
              <a href="edit_restaurant.php?id=<?php echo $row['id'] ?>" class="restaurantBoxHref" style="background-image: url('<?php echo $row['url']; ?>');">
                <div class="RestaurantContainer">
                  <div class="middleR">
                    <div class="textR" style="height:30px; text-transform: uppercase; font-size: 16px; background-color:rgba(255,255,255,0.9);"><?php echo $row['name']; ?>
                  </div>
                </div>
              </div>
              </a><?php
            } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Prikaz -->
<style type="text/css">
  .restaurantBoxHref{
    
    background-size:
    cover; width:18%;
    height: 300px;
    border: 1px solid black;
    float: left;
    margin-bottom: 50px;
    margin-left: 5.5%;
    text-align: center;
    padding-top: 268px;
  }
</style>

<?php 
    include_once 'footer.php';
?>
