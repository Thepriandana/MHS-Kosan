<?php
session_start();
if(!isset($_SESSION['id_applicant'])){
  header("Location: login.php");
}else if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else if(empty($_GET) || !isset($_GET['id']) || empty($_GET['id'])){
  $_SESSION['error'] = 'id';
  header("Location: application.php");
  die();
}else{
  require_once("main.php");
  $sql = new SQL();
  $bind[':id'] = $_GET['id'];
  $application = $sql->run("SELECT a.applicationID, a.residenceID, a.status, r.monthlyRental, (SELECT COUNT(*) FROM unit WHERE residenceID = a.residenceID AND availability != 'used') as unit FROM application AS a INNER JOIN residence AS r ON r.residenceID = a.residenceID WHERE a.applicationID = :id", $bind);
  if(empty($application)){
    $_SESSION['error'] = 'id';
    header("Location: application.php");
    die();
  }else{
    if(isset($_GET['remove'])){
      $sql->run("DELETE FROM application WHERE applicationID = :id", $bind);
      header("Location: application.php");
      die();
    }else if(!empty($_POST)){
      if(isset($_POST['month'], $_POST['year']) && !empty($_POST['month']) && !empty($_POST['year'])){
        $bind[':m'] = $_POST['month'];
        $bind[':y'] = $_POST['year'];
        $sql->run("UPDATE application SET requiredMonth = :m, requiredYear = :y WHERE applicantID = :id", $bind);
      }else{
        $_SESSION['error'] = "empty";
      }
      header("Location: view.php?id=".$_GET['id']);
    }else{
      $form = '';
      $a = $application[0];
      $applicantList = '<tr class="row-link" data-href="view.php?id='.$a['applicationID'].'"><td>'.$a['residenceID'].'</td><td>'.$a['unit'].'</td><td>'.$a['monthlyRental'].'</td><td>'.$a['status'].'</td>';
      if($application[0]['status'] == "Approve"){
        $allocation = $sql->run("SELECT * FROM allocation WHERE applicationID = :id", $bind);
        $a = $allocation[0];
        $form = '<table class="table table-hover table-striped table-sm border">
          <tr>
            <td>Unit No</td>
            <td>'.$a['unitNo'].'</td>
          </tr>
          <tr>
            <td>Duration</td>
            <td>'.$a['duration'].' months</td>
          </tr>
          <tr>
            <td>Date Start</td>
            <td>'.date("d M Y",strtotime($a['formDate'])).'</td>
          </tr>
          <tr>
            <td>Date End</td>
            <td>'.date("d M Y",strtotime($a['endDate'])).'</td>
          </tr>
        </table>';
      }else{
        $form = '<form method="POST">
          <div class="form-group">
            <label>Require Month</label>
            <input type="number" class="form-control" name="month" required placeholder="'.$application[0]['requiredMonth'].'" value="'.$application[0]['requiredMonth'].'">
          </div>
          <div class="form-group">
            <label>Require Year</label>
            <input type="number" class="form-control" name="year" required placeholder="'.$application[0]['requiredYear'].'" value="'.$application[0]['requiredYear'].'">
          </div>
          <div class="d-flex">
            <a class="d-block ml-auto mr-2" href="view.php?id='.$_GET['id'].'&remove=1" onclick="return confirm(\'Are you sure?\')"><button class="btn btn-danger btn-sm" type="button">Remove</button></a>
            <button class="btn btn-primary btn-sm" type="submit">Save Application</button>
          </div>
        </form>';
      }
      $bind[':id'] = $_SESSION['id_user'];
      $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
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
        <div class="col-md-10 border shadow-sm py-3 mx-auto row">
          <div class="col-md-1 border-right p-0">
            <ul class="nav d-md-flex">
              <li class="nav-item">
                <a class="nav-link" href="dashboard.php" alt="Dashboard"><span class="l l-home"></span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="residence.php" alt="Residence"><span class="l l-residence"></span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="application.php" alt="Application"><span class="l l-application l-active"></span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="profile.php" alt="Profile"><span class="l l-profile"></span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php" alt="Log out"><span class="l l-logout"></span></a>
              </li>
            </ul>
          </div>
          <div class="col-md-11 pr-md-0">
            <p class="text-right">'.ucwords($bio[0]['name']).'</p>
            <div class="content p-2">
              <table class="table table-hover table-striped table-sm border shadow-sm rounded">
                <thead>
                  <th>Residence ID</th>
                  <th>Available Unit</th>
                  <th>Monthly Rental</th>
                  <th>Status</th>
                </thead>
                <tbody>
                  '.$applicantList.'
                </tbody>
              </table>
              <div class="col-md-5 p-0">
                '.$form.'
              </div>
            </div>
            <div class="bottom bg-secondary p-2 rounded">
            </div>
          </div>
        </div>
      </div>
    </body>
    </html>
  ');
    }
  }
}
?>
