<?php
include_once 'header.php';

unset($_SESSION['reg_info']);
if(isset($_SESSION['user_id'])){
  header("Location: index.php");
}

if(isset($_POST['login']))
{
  //PREVERI CE SO SPREJETE VREDNOSTI FORME PRIJAVA PRAVILNE
  //$_SESSION['user'] = $_POST['email'];
  //echo "logged in as" . $_SESSION['user'];

  $email = $_POST['email'];    
  $password = $_POST['password'];
  $password = sha1($db_salt.$password);

  $statement = $db->prepare("SELECT * FROM users WHERE (email = :email) AND (password = :password)");
  $statement->execute(array(':email' => $email, ':password' => $password));
  $user = $statement->fetch();

  if($user)
  {
    echo $_SESSION['user_id'] = $user['ID'];
    echo $_SESSION['name'] = $user['name'] . " " . $user['surname'];
    echo $_SESSION['adm'] = $user['admin'];
    echo $_SESSION['cityID'] = $user['city_ID'];

    header("Location: index.php");
    die();
  //echo $_SESSION['name'];
  }
  else
  {
    //echo "Prijava ni uspela!";
    $_SESSION['login_fail'] = 1;
  }
}
?>

<!-- Login -->
<div class="about-us section-padding">
  <div class="container">
    <div class="row">
      <div class="col-md-12 section-title text-center">
        <h3>Prijava</h3>
        <p>Za uporabo vseh funkcij, ki jih ponujamo, se prijavite.</p>
        <span class="section-title-line"></span>
      </div>
      <div class="col-md-6 mb-50" style="margin-left:25%">
        <div class="section-info">
          <?php
          if(isset($_SESSION['login_fail']))
          {
            ?><div id="login_msg">Napačno upšorabniško ime ali geslo!</div><?php
          }
          ?>

          <p> 
            <form method="POST" name="login" target="_self">

              <input name="email" placeholder="E-pošta" required="required" type="text" class="inputWhite" />
              <br/>
              <input name="password" placeholder="Geslo" required="required" type="password" class="inputWhite" />
              <br/>


              <input type="submit" name="login" value="Prijava" class="submitGray">

            </form>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Login --> 

<?php
    include_once 'footer.php';
?>