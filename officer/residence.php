<?php
session_start();
if(isset($_SESSION['id_applicant'])){
  header("Location: ../dashboard.php");
  die();
}else{
  require_once("../main.php");
  $sql = new SQL();
  $bind[':id'] = $_SESSION['id_user'];
  $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
  $residence = $sql->run("SELECT *, (SELECT COUNT(*) FROM unit WHERE residenceID = residence.residenceID AND availability != 'used') as unit FROM residence");
  $residenceList = '<tr><td colspan="5" class="text-center">There is no available residence data</td></tr>';
  if(!empty($residence)){
    $residenceList = '';
    foreach($residence as $r){
      $residenceList .= '<tr class="row-link" data-href="edit.php?id='.$r['residenceID'].'"><td>'.$r['residenceID'].'</td><td>'.$r['address'].'</td><td>'.$r['monthlyRental'].'</td><td>'.$r['sizePerUnit'].'</td><td>'.$r['unit'].'</td>';
    }
  }
  if(isset($_SESSION['error'])){
    unset($_SESSION['error']);
  }
  die('
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Residence | Kosan</title>
  <link rel="icon" href="image/icon.svg">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/kosan.css">
  <link href="../fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
  <script src="../js/jquery-3.4.1.min.js" type="text/javascript"></script>
  <script src="../js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
  <div class="container mt-5">
    <div class="col-md-10 rounded border shadow-sm py-3 mx-auto row px-0 bg-white">
      <div class="col-auto border-right p-0">
        <ul class="nav" style="display: block;">
          <li class="nav-item">
            <a class="nav-link l" href="dashboard.php" alt="Dashboard" style="/*! border-bottom: 1px solid; */">
              <span class="fa fa-home"></span>
              <small>Home</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l l-active" href="residence.php" alt="Residence">
              <span class="fa fa-city"></span>
              <small>Residence</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l" href="application.php" alt="Application">
              <span class="fa fa-list"></span>
              <small>Application</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l" href="profile.php" alt="Profile">
              <span class="fa fa-user"></span>
              <small>User</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l" href="logout.php" alt="Log out" style="border: 0;">
              <span class="fa fa-sign-out-alt" style="width: 100%;"></span>
              <small>Log Out</small>
            </a>
          </li>
        </ul>
      </div>
      <div class="col">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb ml-2 bg-white px-3 py-2 border mb-3">
                <li class="breadcrumb-item active" aria-current="page"><a href="residence.php">Residence</a></li>
              </ol>
            </nav>
          </div>
          <div class="col">
            '.((isset($_SESSION['id_user'])) ? '<p class="text-right">'.ucwords($bio[0]['name'])."</p>" : $account).'
          </div>
        </div>
        <div class="content p-2">
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
          <div class="d-flex">
            <a href="setup.php" class="d-block ml-auto"><button class="btn btn-sm btn-primary">Set Up New Residence</button></a>
          </div>
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
