<?php
include_once 'header.php';

if(!isset($_SESSION['user_id'])){
  header("Location: index.php");
}

$statement = $db->prepare("SELECT * FROM users u WHERE (ID = :id)");
$statement->execute(array(':id' => $_SESSION['user_id']));
$user = $statement->fetch();

$id = $user['ID'];
$name = $user['name'];
$surname = $user['surname'];
$mail = $user['email'];
$cityID = $user['city_ID'];
$houseNmb = $user['house_number'];
$mestoID = $user['city_ID'];


//posodobitev info
if(isset($_POST['updateinfo']))
{
  $name = $_POST['name'];    
  $surname = $_POST['surname'];    
  $email = $_POST['email'];    
  $password = $_POST['password'];    
  $admin = 0;
  $city_ID = $_POST['city'];
  $houseNmb = $_POST['house_number'];    
  

  $statement = $db->prepare("SELECT * FROM users u WHERE (ID = :id)");
  $statement->execute(array(':id' => $_SESSION['user_id']));
  $user = $statement->fetch();

  $password = sha1($db_salt.$password);

  if($password == $user['password'])
  {      
    $statement = $db->prepare("UPDATE users SET name = :name, surname = :surname, email = :email, city_ID = :city_ID, house_number = :house_number WHERE (ID = :id)");
    $statement->execute([
      'id' => $id,
      'name' => $name,
      'surname' => $surname,
      'email' => $email,
      'city_ID' => $city_ID,
      'house_number' => $houseNmb,
    ]);

    $ChangeStatus = 4;

  }
  else
  {
    $ChangeStatus = 3;
  }
}



//sprememba gesla
if(isset($_POST['changepassword']))
{
  $passOld = $_POST['passwordold'];    
  $passNew1 = $_POST['passwordnew'];    
  $passNew2 = $_POST['passwordconf'];    
  

  $statement = $db->prepare("SELECT * FROM users u WHERE (ID = :id)");
  $statement->execute(array(':id' => $_SESSION['user_id']));
  $user = $statement->fetch();

  $passOld = sha1($db_salt.$passOld);

  if($passOld == $user['password'])
  {
    if($passNew1 == $passNew2)
    {
      $passNew1 = sha1($db_salt.$passNew1);
      
      $statement = $db->prepare("UPDATE users SET password = :password WHERE (ID = :id)");
      $statement->execute([
        'id' => $id,
        'password' => $passNew1,
      ]);

      $ChangeStatus = 1;
    }
    else
    {
      $ChangeStatus = 2;
    }
  }
  else
  {
    $ChangeStatus = 3;
  }
}


?>

<!-- Edit profile -->
<div class="about-us section-padding" data-scroll-index='1'>
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center" style="" >
        <h3>Urejanje računa</h3>
        <span class="section-title-line"></span>
      </div>
      <?php
          if(isset($ChangeStatus))
            {
              if($ChangeStatus == 1)
              {
                echo "<div id='passSuccess'>Geslo spremenjeno</div><br>";
              }
              else if($ChangeStatus == 2)
              {
                echo "<div id='passErr'>Gesli se ne ujemata</div><br>";
              }
              else if($ChangeStatus == 3)
              {
                echo "<div id='passErr'>Geslo ni pravilno</div><br>";
              }
              else if($ChangeStatus == 4)
              {
                echo "<div id='passSuccess'>Podatki o uporabniku posodobljeni</div><br>";
              }
            }
            ?>
      <div class="col-md-6 mb-50" style="margin-left: 25%;">
        
        <div class="section-info" style="border: 0px solid black; min-width: 400px !important;">
          <p> 
            <form method="POST" name="updateinfo" target="_self">
              <div class="inputTitle" > Ime:</div> <input name="name" value="<?php echo $name; ?>" required="required" type="text" class="inputGray" style="width: 75%; display: inline-block;"/>
            
              <div class="inputTitle"> Priimek:</div> <input name="surname" value="<?php echo $surname; ?>" required="required" type="text" class="inputGray" style="width: 75%;"/>
           
              <div class="inputTitle"> E-pošta:</div> <input name="email" value="<?php echo $mail; ?>" required="required" type="text" class="inputGray" style="width: 75%;"/>
             
              <div class="inputTitle2"> Mesto:</div><select name="city" style="width: 75%; background-color: #fafafa;">
              <?php
                $stmt = $db->query("SELECT * FROM cities ORDER BY name ASC");
                while ($row = $stmt->fetch())
                {    
                  ?>
                  <option value="<?php echo $row['ID'];?>" <?php if($row['ID']==$mestoID){ echo "selected"; }  ?>> <?php echo $row['name'] ?> </option> <?php
                }
                ?>
              </select>

              <div class="inputTitle2"> Hišna številka:</div><input name="house_number" value="<?php echo $houseNmb; ?>" required="required" type="text" class="inputGray" style="width: 75%;" />

              <div class="inputTitle"> Geslo:</div> <input name="password" placeholder="Vnesi geslo" required="required" type="password" class="inputGray" style="width: 75%;"/>


              <input type="submit" name="updateinfo" value="Posodobi podatke" class="submitWhite"> 
            </form>
          </p>
        </div>
      </div>
      <br>
      <div class="col-md-12 section-title text-center">
        <h3>Spremeni geslo</h3>
        <span class="section-title-line"></span>
      </div>
      <div class="col-md-6 mb-50" style="margin-left:25%">
        <div class="section-info" style="border: 0px solid black; min-width: 400px !important;">
          <p> 
            <form method="POST" name="changepassword" target="_self">
              
              <input name="passwordold" placeholder="Staro geslo" required="required" type="password" class="inputGray" />

              <input name="passwordnew" placeholder="Novo Geslo" required="required" type="password" class="inputGray" />

              <input name="passwordconf" placeholder="Potrdi geslo" required="required" type="password" class="inputGray" />


              <input type="submit" name="changepassword" value="Posodobi geslo" class="submitWhite"> 
            </form>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End edit profile --> 

<?php
    include_once 'footer.php';
?>

<style type="text/css">

</style>