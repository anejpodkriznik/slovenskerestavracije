<?php
  include_once 'header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: index.php");
}

$restaurantID = $_GET['id'];

if(isset($_POST['updatetables']))
{
  $guests =  $_POST['guests'];
  $number = $_POST['number'];   
  $repeat = 0;

  while($repeat < $number)
  {
    $statement = $db->prepare("INSERT INTO tables (restaurant_ID, guests) VALUES (:restaurant_ID, :guests)");
    $statement->execute([
      'restaurant_ID' => $restaurantID,
      'guests' => $guests,
    ]); 

    $repeat++;
  } 
}

if(isset($_POST['editFood']))
{
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $foodID = $_POST['foodID'];
  $restID = $_POST['restID'];
  $foodTypeID = $_POST['foodtype'];

  $statement = $db->prepare("UPDATE food SET name = :name, description = :description, price = :price, type_ID = :typeID WHERE (ID = :id)");
  $statement->execute([
    'id' => $foodID,
    'name' => $name,
    'description' => $description,
    'price' => $price,
    'typeID' => $foodTypeID,
  ]);

}


if(isset($_POST['addFood']))
{
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $restID = $_POST['restID'];
  $foodTypeID = $_POST['foodtype'];

  $statementadd = $db->prepare("INSERT INTO food (name, price, description, type_ID, restaurant_ID) VALUES (:name, :price, :description, :typeID, :restaurantID)");
  $statementadd->execute([
    'name' => $name,
    'price' => $price,
    'description' => $description,
    'typeID' => $foodTypeID,
    'restaurantID' => $restID,
  ]);

}

?> 

<div class="portfolio section-padding" data-scroll-index='3'>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 section-title text-center">
        <h3>Uredi jedilnik</h3>
        <span class="section-title-line"></span>
      </div>

      <div class="gallery no-padding col-lg-12 col-sm-12">
        <div class="restDiv browse">
          <div id="searchDiv">

            <table id="foodListTable">
              <?php
              $findFood = $db->prepare("SELECT f.ID AS foodID, f.name AS name, f.type_ID AS typeID, f.price AS price, f.description AS description, ft.type AS type FROM food f INNER JOIN food_types ft ON ft.ID=f.type_ID INNER JOIN restaurants r ON r.ID=f.restaurant_ID WHERE (f.restaurant_ID = :restaurantID)");
              $findFood->execute(array(':restaurantID' => $restaurantID));
              while ($rowFood = $findFood->fetch())
              {
                ?> 
                <tr>
                  <form method="POST" name="editFood" target="_self">
                    <td class="foodListTableCol1" >
                      <input type="text" name="foodID" value="<?php echo $rowFood['foodID']; ?>" hidden>
                      <input type="text" name="restID" value="<?php echo $restaurantID; ?>" hidden>
                      <input type="text" name="name" value="<?php echo $rowFood['name']; ?>">
                    </td>
                    <td class="foodListTableCol2">
                      <input type="text" name="description" value="<?php echo $rowFood['description']; ?>">
                    </td>
                    <td class="foodListTableCol3">
                      <select name="foodtype">
                        <?php
                        $stmt1 = $db->query("SELECT * FROM food_types ORDER BY type ASC");
                        while ($rowt = $stmt1->fetch())
                        {    
                          ?>
                          <option value="<?php echo $rowt['ID'];?>" <?php if($rowt['ID']==$rowFood['typeID']){ echo "selected"; } ?>> <?php echo $rowt['type'] ?> </option> <?php
                        }
                        ?>
                      </select>
                    </td>
                    <td class="foodListTableCol4">
                      <input type="number" name="price" min="0" value="<?php echo $rowFood['price']; ?>" step=".01" style="width: 60px;">
                      <a href="edit_food.php?id=<?php echo $rowFood['foodID']; ?>&value=1"><img src='images/delete.png' class='foodEditImg'></a>
                      <input type="submit" name="editFood" hidden>
                    </td>
                  </form>
                </tr>
                <?php
              }
              ?>
                <tr>
                  <form method="POST" name="addFood" target="_self">
                    <td class="foodListTableCol1" >
                      <input type="text" name="restID" value="<?php echo $restaurantID; ?>" hidden>
                      <input type="text" name="name" placeholder="Hrana">
                    </td>
                    <td class="foodListTableCol2">
                      <input type="text" name="description" placeholder="Opis">
                    </td>
                    <td class="foodListTableCol3">
                      <select name="foodtype" >
                        <?php
                        $stmt1 = $db->query("SELECT * FROM food_types ORDER BY type ASC");
                        while ($rowt = $stmt1->fetch())
                        {    
                          ?>
                          <option value="<?php echo $rowt['ID'];?>" > <?php echo $rowt['type'] ?> </option> <?php
                        }
                        ?>
                      </select>
                    </td>
                    <td class="foodListTableCol4">
                      <input type="number" name="price" min="0" placeholder="cena" step=".01" style="width: 114px;">
                      <input type="submit" name="addFood" hidden>
                    </td>
                  </form>
                </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
  include_once 'footer.php';
?>