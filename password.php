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
    <a class="breadcrumb-item" href="profile.php">Edit Profile</a>
    <a class="breadcrumb-item" href="#">Change Password</a>
    <a class="breadcrumb-item" href="#">Submit Application</a>
    </nav></div>
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
              <a class="nav-link" href="logout.php" title="Logout" alt="Log out"><span class="l fa fa-sign-out-alt"></span></a>
            </li>
          </ul>
        </div>
        <div class="col-md-11 pr-md-0">
           <div><span class="fa fa-user-circle"></span> <a class="text" href="profile.php">'.((isset($_SESSION['id_user'])) ? ucwords($bio[0]['name']) : null).'</a></div>
          <div class="content p-2 mt-3">
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
