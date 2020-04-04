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
  $application = $sql->run("SELECT a.applicationID, a.residenceID, a.requiredMonth, a.requiredYear, a.status, r.monthlyRental, ua.monthlyIncome, u.fullname, (SELECT COUNT(*) FROM unit WHERE residenceID = a.residenceID AND availability != 'used') as unit FROM application AS a INNER JOIN applicant AS ua ON ua.applicantID = a.applicantID INNER JOIN user AS u ON u.userID = ua.userID INNER JOIN residence AS r ON r.residenceID = a.residenceID");
  $applicationList = '<tr><td colspan="8" class="text-center">There is no available residence data</td></tr>';
  if(!empty($application)){
    $applicationList = '';
    foreach($application as $r){
      $applicationList .= '<tr class="row-link" data-href="allocating.php?id='.$r['residenceID'].'"><td>'.$r['residenceID'].'</td><td>'.$r['unit'].'</td><td>'.$r['monthlyRental'].'</td><td>'.$r['fullname'].'</td><td>'.$r['monthlyIncome'].'</td><td>'.$r['requiredMonth'].'</td><td>'.$r['requiredYear'].'</td><th>'.$r['status'].'</th>';
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/kosan.css">
    <link href="../fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
    <script src="../js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="container mt-5">
      <div class="col-md-10 border shadow-sm py-3 mx-auto row">
        <div class="col-md-1 border-right p-0">
          <ul class="nav d-md-flex">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php" alt="Dashboard"><span class="l fa fa-home"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="residence.php" alt="Residence"><span class="l fa fa-city"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="application.php" alt="Application"><span class="l fa fa-list l-active"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="profile.php" alt="Profile"><span class="l fa fa-user"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php" alt="Log out"><span class="l fa fa-sign-out-alt"></span></a>
            </li>
          </ul>
        </div>
        <div class="col-md-11 pr-md-0">
          <p class="text-right">'.((isset($_SESSION['id_user'])) ? ucwords($bio[0]['name']) : null).'</p>
          <div class="content p-2">
            <table class="table table-hover table-striped table-sm border shadow-sm rounded">
              <thead>
                <th>Residence ID</th>
                <th>Available Unit</th>
                <th>Monthly Rental</th>
                <th>Full name</th>
                <th>Monthly Income</th>
                <th>Month Require</th>
                <th>Year Require</th>
                <th>Status</th>
              </thead>
              <tbody>
                '.$applicationList.'
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
