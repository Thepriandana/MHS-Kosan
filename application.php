<?php
session_start();
if(!isset($_SESSION['id_applicant'])){
  header("Location: login.php");
}else if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else{
  require_once("main.php");
  $sql = new SQL();
  $bind[':id'] = $_SESSION['id_user'];
  $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
  $bind[':id'] = $_SESSION['id_applicant'];
  $applicant = $sql->run("SELECT a.applicationID, a.residenceID, a.status, r.monthlyRental, (SELECT COUNT(*) FROM unit WHERE residenceID = a.residenceID AND availability != 'used') as unit FROM application AS a INNER JOIN residence AS r ON r.residenceID = a.residenceID WHERE a.applicantID = :id", $bind);
  $applicantList = '<tr><td colspan="5" class="text-center">There is no Application request</td></tr>';
  if(!empty($applicant)){
    $applicantList = '';
    $i = 0;
    foreach($applicant as $a){
      $i++;
      $applicantList .= '<tr class="row-link" data-href="view.php?id='.$a['applicationID'].'"><td>'.$i.'</td><td>'.$a['residenceID'].'</td><td>'.$a['unit'].'</td><td>'.$a['monthlyRental'].'</td><td>'.$a['status'].'</td>';
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
    <title>Application | Kosan</title>
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
    <a class="breadcrumb-item" href="#">Application</a>
    </nav></div>
      <div class="col-md-10 border shadow-sm py-3 mx-auto row">
        <div class="col-md-1 border-right p-0">
          <ul class="nav d-md-flex">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php" title="Dashboard" alt="Dashboard"><span class="l fa fa-home"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="residence.php" title="Residence" alt="Residence"><span class="l fa fa-city"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="application.php" title="Application" alt="Application"><span class="l fa fa-list l-active"></span></a>
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
        <div><span class="fa fa-user-circle"></span> <a class="text" href="profile.php">'.ucwords($bio[0]['name']).'</a></div>
          <div class="content p-2 mt-3">
          <h2>Housing Applications</h2>
            <table class="table table-hover table-striped table-sm border shadow-sm rounded">
              <thead>
                <th>No</th>
                <th>Residence ID</th>
                <th>Available Unit</th>
                <th>Monthly Rental</th>
                <th>Status</th>
              </thead>
              <tbody>
                '.$applicantList.'
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
