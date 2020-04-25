<?php
session_start();
if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else{
  require_once("main.php");
  $sql = new SQL();
  if(isset($_SESSION['id_user'])){
    $bind[':id'] = $_SESSION['id_user'];
    $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
  }
  $residence = $sql->run("SELECT *, (SELECT COUNT(*) FROM unit WHERE residenceID = residence.residenceID AND availability != 'used') as unit FROM residence");
  $residenceList = '<tr><td colspan="5" class="text-center">There is no available residence unit now</td></tr>';
  if(!empty($residence)){
    $residenceList = '';
    foreach($residence as $r){
      $residenceList .= '<tr class="row-link" data-href="submit.php?id='.$r['residenceID'].'"><td>'.$r['residenceID'].'</td><td>'.$r['address'].'</td><td>'.$r['monthlyRental'].'</td><td>'.$r['sizePerUnit'].'</td><td>'.$r['unit'].'</td>';
    }
  }
  if(isset($_SESSION['error'])){
    unset($_SESSION['error']);
  }
  die('<!DOCTYPE html>
  <html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Residence | Kosan</title>
    <link rel="icon" href="image/icon.svg">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/kosan.css">
    <link href="fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
    <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="container mt-5">
    <div class="container-fluid col-md-11 row mx-auto mr-3 mt-3">
              <div class="col-sm-1"><img src="img/logo_dbkl.png" class="img-fluid" width="100"></div><div class="col-sm-11"><h1>MHS Kosan</h1></div></div>
           <div class="col-md-11 mx-auto mt-3">
        <nav class="breadcrumb">
    <a class="breadcrumb-item" href="index.php">MHSKosan</a>
    <a class="breadcrumb-item" href="#">Residence</a>
    </nav></div>
      <div class="col-md-10 border shadow-sm py-3 mx-auto row">
        <div class="col-md-1 border-right p-0">
          <ul class="nav d-md-flex">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php" title="Dashboard" alt="Dashboard"><span class="l fa fa-home"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="residence.php" title="Residence" alt="Residence"><span class="l fa fa-city l-active"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="application.php" title="Application" alt="Application"><span class="l fa fa-list"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="profile.php" title="Profile" alt="Profile"><span class="l fa fa-user"></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php" title="Logout" alt="Log out" onclick="return confirm(\'Are you sure?\')"><span class="l fa fa-sign-out-alt"></span></a>
            </li>
          </ul>
        </div>
        <div class="col-md-11 pr-md-0">
        <div><span class="fa fa-user-circle"></span> <a class="text" href="profile.php">'.((isset($_SESSION['id_user'])) ? ucwords($bio[0]['name']) : null).'</a></div>
          <div class="content p-2 mt-3">
          <h2>List of Residences</h2>
            <table class="table table-hover table-striped table-sm border shadow-sm rounded">
              <thead>
                <th>Residence ID</th>
                <th>Address</th>
                <th>Monthly Rental</th>
                <th>Size</th>
                <th>Available Unit</th>
              </thead>
              <tbody>
                '.$residenceList.'
              </tbody>
            </table>
          </div>
          <div class="bottom bg-secondary p-2 rounded">
          </div>
        </div>
      </div>
    </div>
    <script>
    jQuery(document).ready(function($) {
      $(".row-link").click(function() {
        window.location = $(this).data("href");
      });
    });
    </script>
  </body>
  </html>
');
}
?>
