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
        if(isset($_POST['month'])&& !empty($_POST['month'])){
          $tmp = explode("/", $_POST['month']);
          if(!is_array($tmp) || count($tmp) != 2){
            header(":Location: submit.php?id=".$_GET['id']);
            die();
          }else{
            unset($bind);
            $bind[':aID'] = $_SESSION['id_applicant'];
            $bind[':rID'] = $_GET['id'];
            $bind[':month'] = $tmp[0];
            $bind[':year'] = $tmp[1];
            $sql->run("INSERT INTO application VALUES('', :aID, :rID, CURRENT_TIMESTAMP, :month, :year, 'New')", $bind);
            header("Location: application.php");
            die();
          }
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
    <link rel="icon" href="images/logo_kosan.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/kosan.css">
    <link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
    <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="container mt-5">
      <div class="col-md-10 rounded border shadow-sm py-3 mx-auto row px-0 bg-white">
        <div class="col-auto border-right p-0">
          <ul class="nav d-block">
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
                <small>Profile</small>
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
                  <li class="breadcrumb-item"><a href="residence.php">Residence</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><a href="submit.php?id='.$_GET['id'].'">Submit Application</a></li>
                </ol>
              </nav>
            </div>
            <div class="col">
              <p class="text-right">'.((isset($_SESSION['id_user'])) ? ucwords($bio[0]['name']) : null).'</p>
            </div>
          </div>
          <div class="content p-2">
            <div class="col-md-5 p-0">
              <form method="POST">
                <div class="form-group">
                  <label>Required Month & Year</label>
                  <input id="month" placeholder="Click here to select" class="form-control" type="text" name="month" required>
                </div>
                '.((isset($_SESSION['id_applicant'])) ? '<button class="my-2 btn btn-primary btn-sm d-block" type="submit">Submit Application</button>' :
                '<div class="d-flex my-2"><a href="login.php" class="ml-auto d-block mr-2"><button class="btn btn-primary btn-sm" type="button">Login</button></a><a href="register.php"><button class="btn btn-primary btn-sm" type="button">Register</button></a></div>').'
              </form>
            </div>
          </div>
          <div class="bottom bg-secondary p-2 rounded">
          </div>
        </div>
      </div>
    </div>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.ui.monthpicker.js"></script>
    <script>
    $("#month").monthpicker({
      showIcon: false,
      Button: false,
      maxDate: new Date(\'1-12-2073\'),
      minDate: new Date(\'1-12-2019\')
    });
    </script>
  </body>
  </html>
');
}
?>
