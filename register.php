<?php
include_once 'header.php';

unset($_SESSION['reg_info']);
if(isset($_SESSION['user_id'])){
  header("Location: index.php");
}

if(isset($_POST['register']))
{
  //PREVERI CE SO SPREJETE VREDNOSTI FORME PRIJAVA PRAVILNE
  //$_SESSION['user'] = $_POST['email'];
  //echo "logged in as" . $_SESSION['user'];

  $name = $_POST['name'];    
  $surname = $_POST['surname'];    
  $email = $_POST['email'];    
  $password = $_POST['password'];    
  $passwordconf = $_POST['passwordconf'];
  $admin = 0;
  $city_ID = $_POST['city'];
  $houseNmb = $_POST['house_number'];

  $statement = $db->prepare("SELECT * FROM users WHERE (email = :email)");
  $statement->execute(array(':email' => $email));
  $count = $statement->fetchColumn();

  if($count >= 1)
  {
    $_SESSION['reg_info'] = 2;
  }
  else
  {
    if($password == $passwordconf)
    {
      $password = sha1($db_salt.$password);
      
      $statement = $db->prepare("INSERT INTO users (name, surname, email, password, admin, city_ID, house_number) VALUES (:name, :surname, :email, :password, :admin, :city_ID, :house_number)");
      $statement->execute([
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'password' => $password,
        'admin' => $admin,
        'city_ID' => $city_ID,
        'house_number' => $houseNmb,
      ]);
      /*
      $statement = $db->prepare("SELECT * FROM users WHERE (email = :email)AND (password = :password)");
      $statement->execute(array(':email' => $email, ':password' => $password));
      $user = $statement->fetch();

      $_SESSION['user_id'] = $user['ID'];
      $_SESSION['name'] = $user['name'] . " " . $user['surname'];
      */

      $statement2 = $db->prepare("SELECT * FROM users WHERE (email = :email) AND (password = :password)");
      $statement2->execute(array(':email' => $email, ':password' => $password));
      $user = $statement2->fetch();


      echo $_SESSION['user_id'] = $user['ID'];
      echo $_SESSION['name'] = $user['name'] . " " . $user['surname'];
      echo $_SESSION['adm'] = $user['admin'];

      header("Location: index.php");
      die();
    }
    else
    {
      //echo "Passwords do not match";
      $_SESSION['reg_info'] = 1;
    }
  } 
}


    
?>

<!-- Register -->
<div class="about-us section-padding">
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center">
        <h3>Registracija</h3>
        <p>Ustvarite nov račun.</p>
        <span class="section-title-line"></span>
      </div>
      <div class="col-md-6 mb-50" style="margin-left:25%">
        <div class="section-info">
          <div id="register_msg">
            <?php
            if(isset($_SESSION['reg_info']))
            {
              if($_SESSION['reg_info'] == 1)
              {
                echo "Gesli se ne ujemata!";
              }
              else if($_SESSION['reg_info'] == 2)
              {
                echo "Uporabnik že obstaja!";
              }

            }
            ?>
          </div>
          <p> 
            <form method="POST" name="register" target="_self">
              <input name="name" placeholder="Ime" required="required" type="text" class="inputGray" />
              <br/>
              <input name="surname" placeholder="Priimek" required="required" type="text" class="inputGray" />
              <br/>
              <input name="email" placeholder="E-pošta" required="required" type="text" class="inputGray" />
              <br/>
              <select name="city" style="background-color: #fafafa;">
              <?php
                $stmt = $db->query("SELECT * FROM cities ORDER BY name ASC");
                while ($row = $stmt->fetch())
                {    
                  echo "<option value=" . $row['ID'] . ">" . $row['name'] . "</option>";
                }
                ?>
              </select>
              <input name="house_number" placeholder="Hišna številka" required="required" type="text" class="inputGray" />
              <br/>
              <input name="password" placeholder="Geslo" required="required" type="password" class="inputGray" />
              <br/>
              <input name="passwordconf" placeholder="Potrdi geslo" required="required" type="password" class="inputGray"/>
              <br/>


              <input type="submit" name="register" value="Registracija" class="submitWhite"> 


            </form>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- End Register --> 

<?php
    include_once 'footer.php';
?>