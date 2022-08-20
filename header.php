<?php
	session_start();
    include_once 'database.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Slovenske restavracije</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.min.css"/>
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css"/>
<link rel="stylesheet" type="text/css" href="css/owl.theme.default.min.css"/>
<link rel="stylesheet" type="text/css" href="css/custom.css"/>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>

<!-- Font Google -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container"> <a class="navbar-brand navbar-logo" href="index.php"> <img src="images/logoWhite.png" href="index.php" alt="logo" class="logo-1"> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="fas fa-bars"></span> </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">

        <!-- always visible -->
        <li class="nav-item"> <a class="nav-link" href="index.php" >Domov</a> </li>


        <?php //if user is logged out show register + login
        if(!isset($_SESSION['user_id']))
        { ?> 
          <li class="nav-item"> <a class="nav-link" href="login.php">Prijava</a> </li>
          <li class="nav-item"> <a class="nav-link" href="register.php">Registracija</a> </li>
          <?php 
        } ?>

        <?php //if user is logged AND AN ADMIN in show logout and register restaurant
        if(isset($_SESSION['user_id']) && isset($_SESSION['adm']))
        {
          if($_SESSION['adm'] == 1)
          {
            ?><li class="nav-item"> <a class="nav-link" href="admin.php" >Admin</a> </li> <?php 
          }
        } ?>

        <?php //if user is logged in show logout and register restaurant
        if(isset($_SESSION['user_id']))
        { ?> 
          <li class="nav-item"> <a class="nav-link" href="my_profile.php">Moj Profil</a> </li>
          <li class="nav-item"> <a class="nav-link" href="register_restaurant.php" >Registriraj restavracijo</a> </li>
          <li class="nav-item"> <a class="nav-link" href="logout.php">Odjava</a> </li> <?php 
        } ?>

      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar --> <!-- Banner Image -->

<div class="banner text-center" data-scroll-index='0'>
  <div class="banner-overlay">
    <div class="container">
      <h1 class="text-capitalize">Največja zbirka restavracij po sloveniji!</h1>
      <p>Rezervirajte si mizo v svoji najljubši restavraciji, ali pa spoznajde nove okuse, ki jih ponujajo slovenske restavracije.</p>
      <!--<a href="#" class="banner-btn">Get Started</a> -->
    </div>
  </div>
</div>

<!-- End Banner Image --> 