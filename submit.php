<?php
session_start();
if(empty($_GET) || !isset($_GET['id']) || empty($_GET['id'])){
  header("Location: residence.php");
  die();
}else if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else if(empty($_GET) || !isset($_GET['id']) || empty($_GET['id'])){
  $_SESSION['error'] = 'id';
  header("Location: residence.php");
  die();
}else{
  require_once("main.php");
  $sql = new SQL();
  $bind[':id'] = $_GET['id'];
  $residence = $sql->run("SELECT * FROM residence WHERE residenceID = :id", $bind);
  if(empty($residence)){
    $_SESSION['error'] = 'id';
    header("Location: residence.php");
    die();
  }else{
    if(!empty($_POST)){
      if(isset($_SESSION['id_applicant'])){
        if(isset($_POST['month'], $_POST['year']) && !empty($_POST['month']) && !empty($_POST['year'])){
          unset($bind);
          $bind[':aID'] = $_SESSION['id_applicant'];
          $bind[':rID'] = $_GET['id'];
          $bind[':month'] = $_POST['month'];
          $bind[':year'] = $_POST['year'];
          $sql->run("INSERT INTO application VALUES('', :aID, :rID, CURRENT_TIMESTAMP, :month, :year, 'New')", $bind);
          header("Location: application.php");
          die();
        }else{
          $_SESSION['error'] = "empty";
          header(":Location: submit.php?id=".$_GET['id']);
          die();
        }
      }else{
        $_SESSION['error'] = "login";
        header("Location: submit.php?id=".$_GET['id']);
        die();
      }
    }
  }
  if(isset($_SESSION['id_user'])){
    $bind[':id'] = $_SESSION['id_user'];
    $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
  }

  die('<!DOCTYPE html>
  <html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Submit Application | Kosan</title>
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
              <a class="nav-link" href="dashboard.php" alt="Dashboard"><span class="l fa fa-home"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="residence.php" alt="Residence"><span class="l fa fa-city l-active"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="application.php" alt="Application"><span class="l fa fa-list"></span></a>
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
            <div class="col-md-5 p-0">
              <form method="POST">
                <div class="form-group">
                  <label>Require Month</label>
                  <input type="number" class="form-control" name="month" required>
                </div>
                <div class="form-group">
                  <label>Require Year</label>
                  <input type="number" class="form-control" name="year" required>
                </div>
                '.((isset($_SESSION['id_applicant'])) ? '<button class="btn btn-primary btn-sm d-block ml-auto" type="submit">Submit Application</button>' :
                '<div class="d-flex"><a href="login.php" class="ml-auto d-block mr-2"><button class="btn btn-primary btn-sm" type="button">Login</button></a><a href="register.php"><button class="btn btn-primary btn-sm" type="button">Register</button></a></div>').'
              </form>
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
?>
