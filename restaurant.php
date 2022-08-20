<?php
include_once 'header.php';

$restaurantID = $_GET['id'];
if(isset($_SESSION['user_id'])){
  $userID = $_SESSION['user_id'];
}

$stmt = $db->prepare("SELECT r.name AS name, p.url AS url, r.bio AS bio, c.name AS city FROM pictures p INNER JOIN restaurants r ON p.restaurant_ID=r.ID INNER JOIN cities c ON c.ID=r.city_ID WHERE (r.ID = :restaurantID)");
$stmt->execute(array(':restaurantID' => $restaurantID));
$restaurant = $stmt->fetch();
?>
          
<style>
td{
  border: 0px solid black;
  padding: .5%;
}

</style>

<!-- Prikaz -->
<div class="portfolio section-padding" data-scroll-index='3'>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 section-title text-center">
        <h3><?php echo $restaurant['name'] ?></h3>
        <span class="section-title-line"></span>
      </div>
      <div class="gallery no-padding col-lg-12 col-sm-12">
        <div class="restDiv browse">

            <table style="border: 0px solid black; width: 80%; margin-left:10%">
              <tr>
                <td rowspan="2" style="width:46%; margin-right: 2%; vertical-align: top;">
                  <img src="<?php echo $restaurant['url'] ?>" href="index.php" alt="restPic" style="width: 100%; ">
                  <br><br>

                  <?php
                  echo "<h4 class='h4table'>" . $restaurant['name']. "</h4> Lokacija: " . $restaurant['city'];

                      //preštejemo ocene
                    $stmtCount = $db->prepare("SELECT COUNT(*) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                    $stmtCount->execute(array(':restID' => $restaurantID));
                    $countVote = $stmtCount->fetchColumn();

                    //prešetejemo vrednost ocen
                    $stmtSum = $db->prepare("SELECT SUM(rating) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                    $stmtSum->execute(array(':restID' => $restaurantID));
                    $sumVote = $stmtSum->fetchColumn();

                    //izračunamo povprečno oceno
                    if($countVote != 0){
                      $avgRating = $sumVote / $countVote;
                    }
                    else{
                      $avgRating = 0;
                    }

                    $avgRating = round($avgRating, 2);

                    //poizvedba ocen uporabnika
                    if(isset($_SESSION['user_id']))
                    {

                      $stmtrtg = $db->prepare("SELECT * FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE ((user_ID = :userID) AND (restaurant_ID = :restID))");
                      $stmtrtg->execute(array(':userID' => $userID, ':restID' => $restaurantID));
                      $rating = $stmtrtg->fetch();

                      $tempID = $restaurantID;
                    }
                  ?>
                  
                   <table id="voteTable">
                      <tr>
                        <td class="tdvote">
                          <?php
                          if(isset($rating))
                          {
                            if($rating)
                            {
                              echo "Moja ocena:<br><a href='rateDelete.php?id=$tempID'><i class='fa fa-star avg' title='Rate Avg'></i></a> " . $rating['rating'] . "<br>";
                            }
                            else
                            {
                              echo "Moja ocena:<br><i class='fa fa-star' style='color: #ffd700;' title='Rate Avg'></i>Neocenjeno <br>";
                            }
                          ?>
                          <ul class="list-inline rating-list">
                            <li><a href="rate.php?id=<?php echo $tempID ?>&rateValue=5"><i class="fa fa-star" title="Rate 5"></i></a></li>
                            <li><a href="rate.php?id=<?php echo $tempID ?>&rateValue=4"><i class="fa fa-star" title="Rate 4"></i></a></li>
                            <li><a href="rate.php?id=<?php echo $tempID ?>&rateValue=3"><i class="fa fa-star" title="Rate 3"></i></a></li>
                            <li><a href="rate.php?id=<?php echo $tempID ?>&rateValue=2"><i class="fa fa-star" title="Rate 2"></i></a></li>
                            <li><a href="rate.php?id=<?php echo $tempID ?>&rateValue=1"><i class="fa fa-star" title="Rate 1"></i></a></li>
                          </ul> 
                          <?php 
                          }
                          else
                          {
                            echo "Za ocenjevanje se prijavite.";
                          }
                          ?>
                        </td>
                        <td class="tdvote">
                          <?php
                            echo "Ocena:<br><i class='fa fa-star' title='Rate Avg' style='color: #ffd700;'></i> " . $avgRating . "</div>";
                          ?>
                        </td>

                      </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                  <textarea readonly class="textarea"><?php echo $restaurant['bio'] ?></textarea>
                  <div class="functDiv"> 
                    <a href="book_a_table.php?id=<?php echo $restaurantID ?>" class="functHref">
                      <button class="functButton">
                        Rezerviraj mizo
                      </button>
                    </a>
                  </div>
                  <div class="functDiv">
                    <a href="show_menu.php?id=<?php echo $restaurantID ?>" class="functHref">
                      <button class="functButton">
                        Meni
                      </button>
                    </a>
                  </div>
                </td>
              </tr>
              <tr>
                <td>

                </td>
              </tr>
            </table>
      </div>
    </div>
  </div>
</div>
</div>
<!-- End Prikaz -->

<?php 
  include_once 'footer.php';
?>
