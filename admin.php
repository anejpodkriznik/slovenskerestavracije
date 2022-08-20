<?php
  include_once 'header.php';

if(($_SESSION['adm'] != 1)){
  header("Location: index.php");
}

?> 
          
<!-- Admin -->
<?php //if user is logged AND AN ADMIN  show logout and register restaurant
if(isset($_SESSION['user_id']) && isset($_SESSION['adm']))
{
  if($_SESSION['adm'] == 1)
  { ?>

    <div class="portfolio section-padding" data-scroll-index='3'>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 section-title text-center">
            <h3>Neregistrirane restavracije</h3>
            <span class="section-title-line"></span>
          </div>

          <div class="gallery no-padding col-lg-12 col-sm-12">
            <div class="restDiv browse">
              <div id="searchDiv">

                <form method="POST" name="searchRest" target="_self">
                  <input type="text" name="searchRest" id="searchRest" placeholder="Išči" title="searchRest">
                  <input type="submit" name="searchRest" value="Prijava" class="submitGray" style="visibility: hidden;">
                </form>

                <table id="ListTable">
                  <?php
                  $stmt = $db->query("SELECT r. ID AS id, r. name AS name, r. bio AS bio, c. name AS city, p. url AS url FROM restaurants r INNER JOIN cities c ON r.city_ID = c.ID INNER JOIN pictures p ON p.restaurant_ID = r.ID WHERE(r.approved = 0) ORDER BY name ASC");
                  while ($row = $stmt->fetch())
                  {
                    ?> 
                    <tr><td class="tablePicTd"> <img src="<?php echo $row['url'] ?>" alt="restPic" class="tablePic">
                    </td>
                    <td class="ListTableCol1 col-lg-12 section-title"><a href="restaurant.php?id=<?php echo $row['id'] ?>"><?php
                    echo "<h4 class='h4table'>" . $row['name']. "</h4>" . $row['city'];
                    echo "</a></td>";
                    echo "<td class='ListTableCol2'>". $row['bio'] . "</td>";
                    echo "<td class='ListTableCol3'>";
                    ?><a href="approve.php?id=<?php echo $row['id'] ?>"><img src='images/approve.jpg' class='denyAppr'></a> <br /> <a href="deny.php?id=<?php echo $row['id'] ?>"><img src='images/deny.jpg' class='denyAppr'> </a> </td> <?php
                    echo "</tr>";
                  }
                  ?>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php 
  }
} 
//<!-- End Admin --> 

  include_once 'footer.php';
?>