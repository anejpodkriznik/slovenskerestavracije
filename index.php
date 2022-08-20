<?php
include_once 'header.php';

if(isset($_SESSION['user_id'])){
  $userID = $_SESSION['user_id'];
}

?> 
<body onload="HideOnLoad();">
  <!-- Brskaj Restavracije -->
  <script>
    function HideOnLoad() {

      var x1 = document.getElementById("hidclosest");
      var x2 = document.getElementById("hidtype");
      var x3 = document.getElementById("hidrated");
      x1.style.display = "none";
      x2.style.display = "none";
      x3.style.display = "none";
    }

    function openMain() {
      var x1 = document.getElementById("hidbrowse");
      var x2 = document.getElementById("hidclosest");
      var x3 = document.getElementById("hidtype");
      var x4 = document.getElementById("hidrated");
      x1.style.display = "block";
      x2.style.display = "none";
      x3.style.display = "none";
      x4.style.display = "none";
    }

    function openClosest() {
      var x1 = document.getElementById("hidbrowse");
      var x2 = document.getElementById("hidclosest");
      var x3 = document.getElementById("hidtype");
      var x4 = document.getElementById("hidrated");
      x1.style.display = "none";
      x2.style.display = "block";
      x3.style.display = "none";
      x4.style.display = "none";
    }

    function openType() {
      var x1 = document.getElementById("hidbrowse");
      var x2 = document.getElementById("hidclosest");
      var x3 = document.getElementById("hidtype");
      var x4 = document.getElementById("hidrated");
      x1.style.display = "none";
      x2.style.display = "none";
      x3.style.display = "block";
      x4.style.display = "none";
    }


    function openRated() {
      var x1 = document.getElementById("hidbrowse");
      var x2 = document.getElementById("hidclosest");
      var x3 = document.getElementById("hidtype");
      var x4 = document.getElementById("hidrated");
      x1.style.display = "none";
      x2.style.display = "none";
      x3.style.display = "none";
      x4.style.display = "block";
    }
  </script>

  <!-- Prikaz -->
  <div class="portfolio section-padding" data-scroll-index='3'>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 section-title text-center">
          <h3>Restavracije</h3>
          <span class="section-title-line"></span>
        </div>
        <div class="filtering text-center mb-30">
          <button type="button" data-filter='.browse' class="active" onclick="openMain();">Brskaj</button>
          <button type="button" data-filter='.closest' onclick="openClosest();">Najbližje</button>
          <button type="button" data-filter='.type' onclick="openType();">Vrsta hrane</button>
          <button type="button" data-filter='.rated' onclick="openRated();">Najbolše ocenjene</button>
        </div>
        <div class="gallery no-padding col-lg-12 col-sm-12">

          <!-- PRIKAŽI BROWSE -->
          <div class="restDiv browse" id="hidbrowse">
            <div id="searchDiv">

              <form method="POST" name="searchRest" target="_self">
                <input type="text" name="searchValue" value="<?php if(isset($_POST['searchRest'])) { echo $_POST['searchValue']; } ?>" id="searchRest" placeholder="Išči" title="searchRest">
                <input type="submit" name="searchRest" value="Prijava" class="submitGray" style="visibility: hidden;">
              </form>
              <?php
              if(isset($_POST['searchRest']))
              {
                $searchFilter = $_POST['searchValue'];
                $stmt = $db->query("SELECT r. ID AS id, r. name AS name, r. bio AS bio, c. name AS city, p. url AS url FROM restaurants r INNER JOIN cities c ON r.city_ID = c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID  WHERE(r.approved = 1) AND r.name LIKE '%{$searchFilter}%' ORDER BY name ASC");
              }
              else if(isset($_POST['filterFoodType']))
              {
                $searchFilterType = $_POST['foodTypeFilter'];
                $stmt = $db->query("SELECT r. ID AS id, r. name AS name, r. bio AS bio, c. name AS city, p. url AS url FROM restaurants r INNER JOIN cities c ON r.city_ID = c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID INNER JOIN food f ON f.restaurant_ID=r.ID INNER JOIN food_Types ft ON ft.ID=f.type_ID WHERE(r.approved = 1) AND ft.type LIKE '%{$searchFilterType}%' GROUP BY name ORDER BY name ASC");

              }
              else
              {
                $stmt = $db->query("SELECT r. ID AS id, r. name AS name, r. bio AS bio, c. name AS city, p. url AS url FROM restaurants r INNER JOIN cities c ON r.city_ID = c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID WHERE(r.approved = 1) ORDER BY name ASC");
              }

              if(isset($_POST['filterFoodType']))
              {
                echo "<div class='filter'>Filter: " . $searchFilterType . "</div><a href='index.php'><img src='images/deny.jpg' class='denyAppr'></a>";
              }

              ?>
              <table id="ListTable">
                <?php

                while ($row = $stmt->fetch())
                {
                  ?> 
                  <tr><td class="tablePicTd"><a href="restaurant.php?id=<?php echo $row['id'] ?>"><img src="<?php echo $row['url']  ?>" href="index.php" alt="restPic" class="tablePic"></a>
                  </td>
                  <td class="ListTableCol1 col-lg-12 section-title"><a href="restaurant.php?id=<?php echo $row['id'] ?>"><?php
                  echo "<h4 class='h4table'>" . $row['name']. "</h4>" . $row['city'];
                  echo "</a></td>";
                  echo "<td class='ListTableCol2'>". $row['bio'] . "</td>";
                  echo "<td class='ListTableCol3'>";

                  //preštejemo ocene
                  $stmtCount = $db->prepare("SELECT COUNT(*) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                  $stmtCount->execute(array(':restID' => $row['id']));
                  $countVote = $stmtCount->fetchColumn();

                  //prešetejemo vrednost ocen
                  $stmtSum = $db->prepare("SELECT SUM(rating) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                  $stmtSum->execute(array(':restID' => $row['id']));
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
                    $stmtrtg->execute(array(':userID' => $userID, ':restID' => $row['id']));
                    $rating = $stmtrtg->fetch();

                    $tempID = $row['id'];

                    echo "<br><div class='avgRate'>";

                    if($rating)
                    {
                      echo "Moja ocena:<br><a href='rateDelete.php?id=$tempID' class='hrefDelete'><i class='fa fa-star avg' title='Rate Avg'></i></a> " . $rating['rating'] . "<br>";
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
                    echo "<br><div class='avgRate'>";                    
                  }

                  echo "Ocena:<br><i class='fa fa-star' title='Rate Avg' style='color: #ffd700;'></i> " . $avgRating . "</div>";
                  echo "</td></tr>";
                }
                ?>
              </table>

            </div>
          </div>
          <!-- KONEC PRIKAŽI MAIN -->


          <!-- PRIKAŽI NAJBLIŽJE -->
          <div class="restDiv closest" id="hidclosest">
            <div id="searchDiv">
              <?php if(isset($_SESSION['user_id'])){ ?>

                <table id="ListTable">
                  <?php
                  $cityIDu = $_SESSION['cityID'];
                  $stmt = $db->prepare("SELECT r. ID AS id, r. name AS name, r. bio AS bio, r. city_ID AS city_ID, c. ID AS cID, c. name AS city, p. url AS url FROM restaurants r INNER JOIN cities c ON r.city_ID=c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID WHERE((r.approved = 1) AND (c.ID = :cityName))");
                  $stmt->execute(array(':cityName' => $cityIDu));

                  while ($row = $stmt->fetch())
                  {
                    ?> 
                    <tr><td class="tablePicTd"><a href="restaurant.php?id=<?php echo $row['id'] ?>"><img src="<?php echo $row['url']  ?>" href="index.php" alt="restPic" class="tablePic"></a>
                    </td>
                    <td class="ListTableCol1 col-lg-12 section-title"><a href="restaurant.php?id=<?php echo $row['id'] ?>"><?php
                    echo "<h4 class='h4table'>" . $row['name']. "</h4>" . $row['city'];
                    echo "</a></td>";
                    echo "<td class='ListTableCol2'>". $row['bio'] . "</td>";
                    echo "<td class='ListTableCol3'>";

                  //preštejemo ocene
                    $stmtCount = $db->prepare("SELECT COUNT(*) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                    $stmtCount->execute(array(':restID' => $row['id']));
                    $countVote = $stmtCount->fetchColumn();

                  //prešetejemo vrednost ocen
                    $stmtSum = $db->prepare("SELECT SUM(rating) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                    $stmtSum->execute(array(':restID' => $row['id']));
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
                      $stmtrtg->execute(array(':userID' => $userID, ':restID' => $row['id']));
                      $rating = $stmtrtg->fetch();

                      $tempID = $row['id'];

                      echo "<br><div class='avgRate'>";

                      if($rating)
                      {
                        echo "Moja ocena:<br><a href='rateDelete.php?id=$tempID' class='hrefDelete'><i class='fa fa-star avg' title='Rate Avg'></i></a> " . $rating['rating'] . "<br>";
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
                      echo "<br><div class='avgRate'>";                    
                    }

                    echo "Ocena:<br><i class='fa fa-star' title='Rate Avg' style='color: #ffd700;'></i> " . $avgRating . "</div>";
                    echo "</td></tr>";
                  }
                  ?>
                </table>
              <?php }else{ echo "Za filter po oddaljenosti se prijavite."; } ?>
            </div>
          </div>
          <!-- KONEC PRIKAŽI NAJBLIŽJE -->


          <!-- PRIKAŽI PO TIPU HRANE -->
          <div class="restDiv type" id="hidtype">
            <div id="searchDiv">
              <br>
              <div class="inner filtering text-center mb-30">
                <form method="POST" name="filterFoodType" target="_self">
                  <select name="foodTypeFilter" style="width: 25%; background-color: white; height: 50px; ">
                    <?php
                    $stmt = $db->query("SELECT * FROM food_Types ORDER BY type ASC");
                    while ($row = $stmt->fetch())
                    {    
                      ?>
                      <option value="<?php echo $row['type'];?>"> <?php echo $row['type'] ?> </option>
                      <?php
                    }
                    ?>
                  </select>
                  <input type="submit" value="Pojdi" name="filterFoodType" style="display: inline-block; width:10%; height: 50px; background-color: #fafafa;">
                </form>
              </div>
            </div>
          </div>
          <!-- KONEC PRIKAŽI PO TIPU HRANE -->

          <!-- PRIKAŽI NAJBOLJE OCENJENE -->
          <div class="restDiv rated" id="hidrated">
            <div id="searchDiv">

              <table id="ListTable">
                <?php
                $stmt = $db->query("SELECT r. ID AS id, r. name AS name, r. bio AS bio, c. name AS city, p. url AS url, AVG(ra.rating) AS ratingavg, ra.restaurant_ID AS rateresID FROM restaurants r INNER JOIN ratings ra ON r.ID=ra.restaurant_ID INNER JOIN cities c ON r.city_ID = c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID WHERE(r.approved = 1) GROUP BY rateresID ORDER BY ratingavg DESC");


                while ($row = $stmt->fetch())
                {
                  ?> 
                  <tr><td class="tablePicTd"><a href="restaurant.php?id=<?php echo $row['id'] ?>"><img src="<?php echo $row['url']  ?>" href="index.php" alt="restPic" class="tablePic"></a>
                  </td>
                  <td class="ListTableCol1 col-lg-12 section-title"><a href="restaurant.php?id=<?php echo $row['id'] ?>"><?php
                  echo "<h4 class='h4table'>" . $row['name']. "</h4>" . $row['city'];
                  echo "</a></td>";
                  echo "<td class='ListTableCol2'>". $row['bio'] . "</td>";
                  echo "<td class='ListTableCol3'>";

                  //preštejemo ocene
                  $stmtCount = $db->prepare("SELECT COUNT(*) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                  $stmtCount->execute(array(':restID' => $row['id']));
                  $countVote = $stmtCount->fetchColumn();

                  //prešetejemo vrednost ocen
                  $stmtSum = $db->prepare("SELECT SUM(rating) FROM ratings ra INNER JOIN restaurants r ON ra.restaurant_ID=r.ID INNER JOIN users u ON ra.user_ID=u.ID WHERE (restaurant_ID = :restID)");
                  $stmtSum->execute(array(':restID' => $row['id']));
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
                    $stmtrtg->execute(array(':userID' => $userID, ':restID' => $row['id']));
                    $rating = $stmtrtg->fetch();

                    $tempID = $row['id'];

                    echo "<br><div class='avgRate'>";

                    if($rating)
                    {
                      echo "Moja ocena:<br><a href='rateDelete.php?id=$tempID' class='hrefDelete'><i class='fa fa-star avg' title='Rate Avg'></i></a> " . $rating['rating'] . "<br>";
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
                    echo "<br><div class='avgRate'>";                    
                  }

                  echo "Ocena:<br><i class='fa fa-star' title='Rate Avg' style='color: #ffd700;'></i> " . $avgRating . "</div>";
                  echo "</td></tr>";
                }
                ?>
              </table>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <!-- End Prikaz -->

  <?php 

  include_once 'footer.php';
  ?>

  <script>
    refresh1(){
      <?php
      $stmt = $db->query("SELECT r. ID AS id, r. name AS name, r. bio AS bio, c. name AS city, p. url AS url FROM restaurants r INNER JOIN cities c ON r.city_ID = c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID INNER JOIN food f ON f.restaurant_ID=r.ID INNER JOIN food_Types ft ON ft.ID=f.type_ID WHERE(r.approved = 1) AND (ft.ID = 6) ORDER BY name ASC");
      ?>
    }

  </script>