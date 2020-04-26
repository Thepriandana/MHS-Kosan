<?php
session_start();
if(!isset($_SESSION['id_applicant'])){
  header("Location: login.php");
}else if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else if(!empty($_POST)){
  if(isset($_POST['current'], $_POST['new'], $_POST['re'])){
    if($_POST['new'] !== $_POST['re']){
      $_SESSION['error'] = "notmatch";
    }else{
      require_once("main.php");
      $sql = new SQL();
      $bind[':id'] = $_SESSION['id_user'];
      $bind[':p']  = hash("sha256", $_POST['current']);
      $checkPass = $sql->run("SELECT userID FROM user WHERE userID = :id AND password = :p", $bind);
      if(empty($checkPass)){
        $_SESSION['error'] = "current";
      }else{
        $bind[':p']  = hash("sha256", $_POST['new']);
        $sql->run("UPDATE user SET password = :p WHERE userID = :id", $bind);
      }
    }
  }else{
    $_SESSION['error'] = "empty";
  }
  header("Location: password.php");
  die();
}else{
  require_once("main.php");
  $sql = new SQL();
  $bind[':id'] = $_SESSION['id_user'];
  $bio = $sql->run("SELECT u.fullname as name, a.email, a.monthlyIncome as mi FROM user AS u INNER JOIN applicant AS a ON a.userID = u.userID WHERE u.userID = :id", $bind);
  $error = '';
  if(isset($_SESSION['error'])){
    if($_SESSION['error'] === "empty"){
      $error = "Please fill required fields.";
    }else if($_SESSION['error'] === "notmatch"){
      $error = "Password and Repassword does not match";
    }else if($_SESSION['error'] === "current"){
      $error = "Wrong current password";
    }
    $error = '<span class="d-block alert alert-danger text-center w-100 p-1">'.$error.'</span>';
    unset($_SESSION['error']);
  }

  die('<!DOCTYPE html>
  <html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Dashboard | Kosan</title>
    <link rel="icon" href="images/logo_kosan.ico">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/kosan.css">
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
              <a class="nav-link l" href="residence.php" alt="Residence">
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
              <a class="nav-link l l-active" href="profile.php" alt="Profile">
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
                  <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><a href="password.php">Change Password</a></li>
                </ol>
              </nav>
            </div>
            <div class="col">
              <p class="text-right">'.ucwords($bio[0]['name']).'</p>
            </div>
          </div>
          <div class="content p-2">
          <p>Please enter your new password.</p>
            <div class="col-md-5 p-0">
              <form method="POST">
                '.$error.'
                <div class="form-group">
                  <label>Current Password</label>
                  <input type="password" class="form-control" name="current" required>
                </div>
                <div class="form-group">
                  <label>New Password</label>
                  <input type="password" class="form-control" name="new" required>
                </div>
                <div class="form-group">
                  <label>Re-password</label>
                  <input type="password" class="form-control" name="re" required>
                </div>
                <div class="d-flex">
                  <button type-"submit" class="btn btn-sm btn-primary ml-auto">Change Password</button>
                </div>
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
