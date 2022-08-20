<?php
    include_once 'database.php';
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container"> <a class="navbar-brand navbar-logo" href="#"> <img src="images/logoWhite.png" href="" data-scroll-nav="0" alt="logo" class="logo-1"> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="fas fa-bars"></span> </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">

        <!-- always visible -->
        <li class="nav-item"> <a class="nav-link" href="" data-scroll-nav="0">Domov</a> </li>

        <!-- not logged in -->
        <?php //if user is logged out show register + login
        if(!isset($_SESSION['user_id']))
        { ?> 
          <li class="nav-item"> <a class="nav-link" href="#" data-scroll-nav="1">Prijava</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#" data-scroll-nav="2">Registracija</a> </li>
        <?php } ?>

        <!-- logged in + admin -->
        <?php //if user is logged AND AN ADMIN in show logout and register restaurant
        if(isset($_SESSION['user_id']) && isset($_SESSION['adm']))
        {
          if($_SESSION['adm'] == 1)
          {
            ?><li class="nav-item"> <a class="nav-link" href="#" data-scroll-nav="3">Admin</a> </li>
        <?php }
        } ?>

        <!-- logged in -->
        <?php //if user is logged in show logout and register restaurant
        if(isset($_SESSION['user_id']))
        { ?> 
          <li class="nav-item"> <a class="nav-link" href="#" data-scroll-nav="4">restavracija</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#" data-scroll-nav="5">Brskaj</a> </li>
          <li class="nav-item"> <a class="nav-link" href="#" data-scroll-nav="6">Registriraj restavracijo</a> </li>
          <li class="nav-item"> <a class="nav-link" href="logout.php">Odjava</a> </li>
        <?php } ?>

      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar --> 