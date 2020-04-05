<?php
session_start();
if(isset($_SESSION['id_applicant'])){
  header("Location: ../dashboard.php");
  die();
}else{
  if(empty($_GET) || !isset($_GET['id']) || empty($_GET['id'])){
    $_SESSION['error'] = 'id';
    header("Location: application.php");
    die();
  }else{
    require_once("../main.php");
    $sql = new SQL();
    $bind[':id'] = $_GET['id'];
    $application = $sql->run("SELECT a.applicationID, a.residenceID, a.requiredMonth, a.requiredYear, a.status, r.monthlyRental, ua.monthlyIncome, u.fullname, (SELECT COUNT(*) FROM unit WHERE residenceID = a.residenceID AND availability != 'used') as unit FROM application AS a INNER JOIN applicant AS ua ON ua.applicantID = a.applicantID INNER JOIN user AS u ON u.userID = ua.userID INNER JOIN residence AS r ON r.residenceID = a.residenceID WHERE a.applicationID = :id", $bind);
    $applicationList = '';
    if(empty($application)){
      $_SESSION['error'] = 'id';
      header("Location: application.php");
      die();
    }else{
      if(isset($_GET['reject']) && $application[0]['status'] != "Approve"){
        $sql->run("UPDATE application SET status = 'Rejected' WHERE applicationID = :id", $bind);
        header("Location: allocating.php?id=".$_GET['id']);
        die();
      }else if(isset($_GET['wait']) && $application[0]['status'] != "Approve"){
        $sql->run("UPDATE application SET status = 'Wait List' WHERE applicationID = :id", $bind);
        header("Location: allocating.php?id=".$_GET['id']);
        die();
      }else if(!empty($_POST) && $application[0]['status'] != "Approve"){
        if(isset($_POST['unit'], $_POST['date'], $_POST['duration']) && !empty($_POST['unit'])
        && !empty($_POST['date']) && !empty($_POST['duration'])){
          $bind[':u'] = $_POST['unit'];
          $bind[':du'] = $_POST['duration'];
          $bind[':da'] = date("yy-m-d",strtotime($_POST['date']));
          $sql->run("INSERT INTO allocation VALUES('', :id, :u, :da, :du, (SELECT DATE_ADD(:da, INTERVAL :du MONTH ))); UPDATE application SET status = 'Approve' WHERE applicationID = :id;
          UPDATE unit SET availability = 'used' WHERE unitNo = :u; UPDATE application SET status = 'Rejected' WHERE status != 'Approve'", $bind);
        }else{
          $_SESSION['error'] = 'empty';
        }
        header("Location: allocating.php?id=".$_GET['id']);
        die();
      }else{
        $applicationList = '<tr class="row-link" data-href="allocating.php?id='.$application[0]['residenceID'].'"><td>'.$application[0]['residenceID'].'</td><td>'.$application[0]['unit'].'</td><td>'.$application[0]['monthlyRental'].'</td><td>'.$application[0]['fullname'].'</td><td>'.$application[0]['monthlyIncome'].'</td><td>'.$application[0]['requiredMonth'].'</td><td>'.$application[0]['requiredYear'].'</td><th>'.$application[0]['status'].'</th>';
        $unitList = '';
        $bind[':id'] = $application[0]['residenceID'];
        $unit = $sql->run("SELECT * FROM unit WHERE residenceID = :id AND availability = 'available'", $bind);
        foreach($unit as $u){
          $unitList .= '<option value="'.$u['unitNo'].'">'.$u['unitNo'].'</option>';
        }
        $form = '';
        if($application[0]['status'] == "Approve"){
          $bind[':id'] = $_GET['id'];
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
              <label>Unit No</label>
              <select class="form-control" name="unit">'.$unitList.'</select>
            </div>
            <div class="form-group">
              <label>From Date</label>
              <input type="date" class="form-control" name="date" required>
            </div>
            <div class="form-group">
              <label>Duration</label>
              <select class="form-control" name="duration">
                <option value="12">12 Months</option>
                <option value="18">18 Months</option>
              </select>
            </div>
            <div class="d-flex">
              <a href="allocating.php?id='.$_GET['id'].'&reject" class="ml-auto d-block"><button type="button" class="btn btn-sm btn-danger mr-2">Reject</button></a>
              <a href="allocating.php?id='.$_GET['id'].'&wait"><button type="button" class="btn btn-sm btn-warning mr-2">Send to waitlist</button></a>
              <button class="btn btn-sm btn-primary" type="submit">Approve</button>
            </div>
          </form>';
        }
        $bind[':id'] = $_SESSION['id_user'];
        $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
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
                  <div class="col-md-6 mx-auto mb-3">
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
}
?>
